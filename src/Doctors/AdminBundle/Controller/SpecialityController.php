<?php

namespace Doctors\AdminBundle\Controller;

use Doctors\AdminBundle\Entity\Speciality;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Speciality controller.
 *
 */
class SpecialityController extends Controller
{
    /**
     * Lists all speciality entities.
     *
     */
    public function indexSpecialityAction()
    {
        $em = $this->getDoctrine()->getManager();

        $specialities = $em->getRepository('DoctorsAdminBundle:Speciality')->findBy(array(), array('id' => 'desc'));

        return $this->render('DoctorsAdminBundle:speciality:indexSpeciality.html.twig', array(
            'specialities' => $specialities,
        ));
    }

    /**
     * Creates a new speciality entity.
     *
     */
    public function newSpecialityAction(Request $request)
    {
        $speciality = new Speciality();
        $form = $this->createForm('Doctors\AdminBundle\Form\SpecialityType', $speciality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $speciality->setIsActiveSpeciality(1);
            $speciality->setCreatedAtSpeciality(new \DateTime());
            $em->persist($speciality);
            $em->flush();
            $session = $request->getSession();
            $session->getFlashBag()->add('msg', 'تم اضافة الاختصاص  بنجاح');
            return $this->redirectToRoute('doctorSpeciality_index');
        }

        return $this->render('DoctorsAdminBundle:speciality:newSpeciality.html.twig', array(
            'speciality' => $speciality,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a speciality entity.
     *
     */
    public function showSpecialityAction(Speciality $speciality)
    {
        return $this->render('DoctorsAdminBundle:speciality:showSpeciality.html.twig', array(
            'speciality' => $speciality,
        ));
    }

    /**
     * Displays a form to edit an existing speciality entity.
     *
     */
    public function editSpecialityAction(Request $request, Speciality $speciality)
    {
        $editForm = $this->createForm('Doctors\AdminBundle\Form\SpecialityType', $speciality);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $session = $request->getSession();
            $session->getFlashBag()->add('msg', 'تم تعديل بيانات الاختصاص  بنجاح');
            return $this->redirectToRoute('doctorSpeciality_index');
        }

        return $this->render('DoctorsAdminBundle:speciality:editSpeciality.html.twig', array(
            'speciality' => $speciality,
            'edit_form' => $editForm->createView(),
        ));
    }

    //delete doctor
    public function deleteSpecialityAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $doctor = $em->getRepository('DoctorsAdminBundle:Speciality')->find($id);
        $doctor->setIsActiveSpeciality(0);
        $em->merge($doctor);
        $em->flush();
        $session = $request->getSession();
        $session->getFlashBag()->add('msg', 'تم حذف الاختصاص بنجاح');
        return $this->redirectToRoute('doctorSpeciality_index');
    }
}
