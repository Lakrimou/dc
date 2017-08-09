<?php

namespace Doctors\AdminBundle\Controller;

use Doctors\AdminBundle\Entity\Appointment;
use Doctors\AdminBundle\Entity\Doctor;
use Doctors\AdminBundle\Entity\Evaluation;
use Doctors\AdminBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class EvaluationController extends Controller
{

    //list of evaluations
    public function evaluationsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $appointment = $em->getRepository('DoctorsAdminBundle:Appointment')->findByStatus('مؤكد');
        $evaluations = $em->getRepository('DoctorsAdminBundle:Evaluation')->findBy(array('appointment'=>$appointment), array('id' => 'desc'));
        return $this->render('DoctorsAdminBundle:Evaluation:evaluations.html.twig', array('evaluations'=>$evaluations, 'appointments'=>$appointment));
    }

    // show evaluation informations
    public function showEvaluationAction(Request $request, Evaluation $evaluation)
    {
        $em = $this->getDoctrine()->getManager();
        $specificEvaluation = $em->getRepository('DoctorsAdminBundle:Evaluation')->find($evaluation->getId());
        return $this->render('DoctorsAdminBundle:Evaluation:showEvaluation.html.twig', array('evaluation'=>$specificEvaluation));
    }

        //add Evaluation
    public function addEvaluationAction(Request $request)
    {
        /*$userAgent = $request->headers->get('User-Agent');*/
        $em = $this->getDoctrine()->getManager();
        $evaluation = new Evaluation();
        $appointment = $em->getRepository('DoctorsAdminBundle:Appointment')->findByStatus('مؤكد');


        $form = $this->createForm('Doctors\AdminBundle\Form\EvaluationType', $evaluation);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $evaluationAppointmentId = $request->request->get('evaluation_id');
            $app = $em->getRepository('DoctorsAdminBundle:Appointment')->find($evaluationAppointmentId);
            $doctorId = $app->getDoctor();
            $app->setEval(1);
            $doctorApp = $em->getRepository('DoctorsAdminBundle:Doctor')->find($doctorId);
            $doctor = new Doctor();
            $doctor = $doctorApp;
            $evaluation->setDoctor($doctor);
            $userApp = $em->getRepository('DoctorsAdminBundle:User')->find($app->getUser()->getId());
            $user = new User();
            $user = $userApp;
            $evaluation->setUser($user);
            $evaluation->setIsActiveEvaluation(1);
            $evaluation->setCreatedAtEvaluation(new \DateTime());
            $evaluation->setStatusEvaluation(0);
            $evaluation->setAppointment($app);
            $em->persist($evaluation);
            $em->flush();
            $session = $request->getSession();
            $session->getFlashBag()->add('msg', 'تم اضافة التقييم  بنجاح');
            return $this->redirectToRoute('doctors_admin_evaluation');
        }
        return $this->render('DoctorsAdminBundle:Evaluation:ajouterEvaluation.html.twig', array(
            'evaluation'=>$evaluation,
            'form'=>$form->createView(),
            'list' => $appointment
        ));
    }


    //Update evaluation
    public function editEvaluationAction(Request $request, Evaluation $evaluation)
    {
        $editForm = $this->createForm('Doctors\AdminBundle\Form\EvaluationType', $evaluation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted()) {
            $em = $this->getDoctrine()->getManager() ;
            $this->getDoctrine()->getManager()->flush();
            $session = $request->getSession();
            $session->getFlashBag()->add('msg', 'تم تعديل التقييم  بنجاح');
            return $this->redirectToRoute('doctors_admin_evaluation');
        }

        return $this->render('DoctorsAdminBundle:Evaluation:editEvaluation.html.twig', array(
            'evaluation' =>$evaluation,
            'edit_form' =>$editForm->createView(),
        ));
    }

    //delete evaluation
    public function deleteEvaluationAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $evaluation = $em->getRepository('DoctorsAdminBundle:Evaluation')->find($id);
        $evaluation->setIsActiveEvaluation(0);
        $em->merge($evaluation);
        $em->flush();
        $session = $request->getSession();
        $session->getFlashBag()->add('msg', 'تم حذف التقييم بنجاح');
        return $this->redirectToRoute('doctors_admin_evaluation');
    }

    //confirm To show the evaluation
    public function confirmEvaluationAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $evaluation = $em->getRepository('DoctorsAdminBundle:Evaluation')->find($id);
        $evaluation->setStatusEvaluation(1);
        $em->merge($evaluation);
        $em->flush();
        $session = $request->getSession();
        $session->getFlashBag()->add('msg', 'تمت الموافقة على التقييم');
        return $this->redirectToRoute('doctors_admin_evaluation');
    }

    //unconfirm To show evaluation
    public function unconfirmEvaluationAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $evaluation = $em->getRepository('DoctorsAdminBundle:Evaluation')->find($id);
        $evaluation->setStatusEvaluation(0);
        $em->merge($evaluation);
        $em->flush();
        $session = $request->getSession();
        $session->getFlashBag()->add('msg', 'لم تتم الموافقة على التقييم ');
        return $this->redirectToRoute('doctors_admin_evaluation');
    }
}
