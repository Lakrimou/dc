<?php

namespace Doctors\AdminBundle\Controller;

use Doctors\AdminBundle\Entity\Appointment;
use Doctors\AdminBundle\Entity\Doctor;
use Doctors\AdminBundle\Entity\Evaluation;
use Doctors\AdminBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;

class DoctorController extends Controller
{

    //list of Doctors
    public function doctorsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $doctors = $em->getRepository('DoctorsAdminBundle:Doctor')->findBy(array(), array('id' => 'desc'));
        return $this->render('DoctorsAdminBundle:Doctor:doctors.html.twig', array('doctors' => $doctors));
    }

    // show doctor informations
    public function showDoctorAction(Request $request, Doctor $doctor)
    {
        $em = $this->getDoctrine()->getManager();
        $specificDoctor = $em->getRepository('DoctorsAdminBundle:Doctor')->find($doctor->getId());
        $appointments = $em->getRepository('DoctorsAdminBundle:Appointment')->findBy(
            array('isActiveAppointment' => 1, 'doctor' => $specificDoctor),
            array('id' => 'desc')
        );
        return $this->render('DoctorsAdminBundle:Doctor:showDoctor.html.twig', array('doctor' => $specificDoctor, 'appointments' => $appointments));
    }

    //add Doctor
    public function addDoctorAction(Request $request)
    {
        $doctor = new Doctor();
        $form = $this->createForm('Doctors\AdminBundle\Form\DoctorType', $doctor);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $doctor->setCreatedAtDoctor(new \DateTime());
            $doctor->setIsActiveDoctor(1);
            $doctor->setIsCompleted(1);
            $file = $doctor->getPhotoDoctor();
            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()) . '.' . 'jpg';
            $file->move(
                $this->getParameter('photoDoctor_directory'),
                $fileName
            );
            $doctor->setPhotoDoctor($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($doctor);
            $em->flush();
            return $this->redirectToRoute('doctors_admin_doctors');
        }
        return $this->render('DoctorsAdminBundle:Doctor:ajouterDoctor.html.twig', array(
            'doctor' => $doctor,
            'form' => $form->createView(),
        ));
    }

    //Update doctor
    public function editDoctorAction(Request $request, Doctor $doctor)
    {
        $editForm = $this->createForm('Doctors\AdminBundle\Form\EditDoctorType', $doctor);
        $editPictureForm = $this->createForm('Doctors\AdminBundle\Form\EditPictureDoctorType', $doctor);
        $editForm->handleRequest($request);
        /*$editPictureForm->handleRequest($request);*/
        if ($editForm->isSubmitted()) {
            $file = $doctor->getPhotoDoctor();
            $doctor->setPhotoDoctor($file); // Store the current value from the DB before overwriting below
            if ($file instanceof UploadedFile) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('photoDoctor_directory'),
                    $fileName
                );
                $doctor->setPhotoDoctor($fileName);
            }
            $this->getDoctrine()->getManager()->flush();
            if ($request->isXmlHttpRequest()) {

                return new JsonResponse(array('message' => 'Success!', 'success' => true), 200);

            }
            $session = $request->getSession();
            $session->getFlashBag()->add('msg', 'تم تعديل معلومات الطبيب  بنجاح');
            return $this->redirectToRoute('doctors_admin_doctors');
        }
        /*var_dump($editForm->getErrors());*/
        return $this->render('DoctorsAdminBundle:Doctor:editDoctor.html.twig', array(
            'doctor' => $doctor,
            'edit_form' => $editForm->createView(),
            'editPicForm' => $editPictureForm->createView(),
        ));
    }


    public function editPhotoDoctorAction(Request $request, Doctor $doctor)
    {

        $editPictureForm = $this->createForm('Doctors\AdminBundle\Form\EditPictureDoctorType', $doctor);
        $editForm = $this->createForm('Doctors\AdminBundle\Form\EditDoctorType', $doctor);
        $editPictureForm->handleRequest($request);
        if ($editPictureForm->isSubmitted()) {
            $file = $doctor->getPhotoDoctor();
            $doctor->setPhotoDoctor($file); // Store the current value from the DB before overwriting below
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('photoDoctor_directory'),
                $fileName
            );
            $doctor->setPhotoDoctor($fileName);
            $this->getDoctrine()->getManager()->flush();
            if ($request->isXmlHttpRequest()) {

                return new JsonResponse(array('message' => 'Success!', 'success' => true, 'photo' => $doctor->getPhotoDoctor()), 200);

            }
            $session = $request->getSession();
            $session->getFlashBag()->add('msg', 'تم تعديل معلومات الطبيب  بنجاح');
            return $this->redirectToRoute('doctors_admin_doctors');
        }
        return $this->render('DoctorsAdminBundle:Doctor:editDoctor.html.twig', array(
            'doctor' => $doctor,
            'edit_form' => $editForm->createView(),
            'editPicForm' => $editPictureForm->createView(),
        ));

    }

    //delete doctor
    public function deleteDoctorAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $doctor = $em->getRepository('DoctorsAdminBundle:Doctor')->find($id);
        $doctor->setIsActiveDoctor(0);
        $em->merge($doctor);
        $em->flush();
        $session = $request->getSession();
        $session->getFlashBag()->add('msg', 'تم حذف الطبيب بنجاح');
        return $this->redirectToRoute('doctors_admin_doctors');
    }

    public function loadDataHourWorkAction(Request $request)
    {
        $dateRDV = $request->request->get('dateAppointment');
        $doctor_id = $request->request->get('doctor_id');
        $em = $this->getDoctrine()->getManager();
        $doctor = $em->getRepository('DoctorsAdminBundle:Doctor')->find($doctor_id);
        return new JsonResponse(array('message' => 'Success!', 'success' => true, 'doctor_id' => $doctor_id, 'dateRDV' => $dateRDV, 'doctor' => $doctor, 'startTimeWork' => $doctor->getStartTimeWork(), 'endTimeWork' => $doctor->getEndTimeWork()), 200);
    }


    public function addFastDoctorAction(Request $request)
    {
        $doctor = new Doctor();
        $form = $this->createForm('Doctors\AdminBundle\Form\AddFastDoctorType', $doctor);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $doctor->setIsActiveDoctor(1);
            $doctor->setIsCompleted(0);
            $doctor->setCreatedAtDoctor(new \DateTime());
            $file = $request->files->get('photoDoctor');
            if (empty($file)) {
                $doctor->setPhotoDoctor('avatar.jpg');
            }
            $em->persist($doctor);
            $em->flush();
            return $this->redirectToRoute('doctors_admin_doctors');
        }
        return $this->render('DoctorsAdminBundle:Doctor:addFastDoctor.html.twig', array(
            'doctor' => $doctor,
            'form' => $form->createView(),
        ));
    }

}
