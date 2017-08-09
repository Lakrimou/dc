<?php

namespace Doctors\EndpointBundle\Controller;

use Doctors\AdminBundle\Entity\Doctor;
use Doctors\AdminBundle\Entity\Evaluation;
use Doctors\AdminBundle\Entity\TokenDoctor;
use Doctors\AdminBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class EvaluationApiController extends Controller
{
        public function addEvaluationAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        /*$userAgent = $request->headers->get('User-Agent');
        $em = $this->getDoctrine()->getManager();
        $evaluation = new Evaluation();
        $appointment = $em->getRepository('DoctorsAdminBundle:Appointment')->findByStatus('مؤكد');*/
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $evaluation = new Evaluation();
            //get evaluation
            $evaluationName = $request->request->get('evaluationName');
            /*var_dump('____________________________________evaaaaaaluaaaaaaaaaationnnnnnn_____________________');
            var_dump($evaluationName);*/
            //get feedback
            $feedback = $request->request->get('feedback');

            $appointment_id = $request->request->get('appointment_id');
            /*var_dump($appointment_id);*/
            //get appointments
            $appointment = $em->getRepository('DoctorsAdminBundle:Appointment')->find($appointment_id);
            /*var_dump($appointment);*/
            $doctorId = $appointment->getDoctor();
            $doctorApp = $em->getRepository('DoctorsAdminBundle:Doctor')->find($doctorId);
            /*var_dump('------------------doctor----------------');*/
            $doctor = new Doctor();
            $doctor = $doctorApp;
            $evaluation->setDoctor($doctor);
            $user = $appointment->getUser();
            $userApp = $em->getRepository('DoctorsAdminBundle:User')->find($appointment->getUser()->getId());
            $user = new User();
            $user = $userApp;
            $evaluation->setDoctor($doctor);
            $evaluation->setUser($user);
            $evaluation->setEvaluation($evaluationName);
            $evaluation->setFeedback($feedback);
            $evaluation->setIsActiveEvaluation(1);
            $evaluation->setStatusEvaluation(0);
            $evaluation->setCreatedAtEvaluation(new \DateTime());
            $evaluation->setStatusEvaluation(0);
            $evaluation->setAppointment($appointment);
            $em->persist($evaluation);
            $em->flush();
            $resultat = array(
                'success' => 1,
                'appointment_id' => $appointment->getId()
            );
            return new JsonResponse($resultat);
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }


    public function api_list_evaluationByDoctorAction(Request $request, $id_doctor)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $evaluations = $em->getRepository('DoctorsAdminBundle:Evaluation')->findBy(
                array('isActiveEvaluation' => 1, 'doctor' => $id_doctor),
                array('id' => 'desc')
            );
            $i = 0;
            $evaluationArray = array();
            $tab_return = array();
            if(empty($evaluations)){
                $tab_return['success'] = 0  ;
                $tab_return['data'] = array() ;
            }else{

            foreach ($evaluations as $evaluation) {
                $evaluationArray[$i]["evaluation_id"] = $evaluation->getId();
                if($evaluation->getEvaluation()== "ممتاز")
                {
                    $evaluationArray[$i]["evaluation"] = 3;
                }elseif($evaluation->getEvaluation()== "جيد")
                {
                    $evaluationArray[$i]["evaluation"] = 2;
                }elseif ($evaluation->getEvaluation()== "سيء")
                {
                    $evaluationArray[$i]["evaluation"] = 1;
                }
                $evaluationArray[$i]["evaluation_value"] = $evaluation->getEvaluation();
                $evaluationArray[$i]["feedback"] = $evaluation->getFeedback();
                $evaluationArray[$i]['doctor_id'] = $evaluation->getDoctor()->getId();
                $evaluationArray[$i]['name_doctor'] = $evaluation->getDoctor()->getNameDoctor();
                $evaluationArray[$i]['photo_doctor'] = 'uploads/doctors/'.$evaluation->getDoctor()->getPhotoDoctor();
                $evaluationArray[$i]['user_id'] = $evaluation->getUser()->getId();
                $evaluationArray[$i]['username'] = $evaluation->getUser()->getName();
                $evaluationArray[$i]['photo_user'] = 'uploads/users/'.$evaluation->getUser()->getPhoto();
                $evaluationArray[$i]['appointment_id'] = $evaluation->getAppointment()->getId();
                $i++;
            }
                $tab_return['success'] = 1  ;
                $tab_return['data'] = $evaluationArray ;
            }
            return new JsonResponse($tab_return);
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

    public function api_list_evaluationByUserAction(Request $request, $id_user)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $evaluations = $em->getRepository('DoctorsAdminBundle:Evaluation')->findBy(
                array('isActiveEvaluation' => 1, 'user' => $id_user),
                array('id' => 'desc')
            );
            $i = 0;
            $evaluationArray = array();
            $tab_return = array();
            if(empty($evaluations)){
                $tab_return['success'] = 0  ;
                $tab_return['data'] = array() ;
            }else{
            foreach ($evaluations as $evaluation) {
                $evaluationArray[$i]["evaluationId"] = $evaluation->getId();
                if($evaluation->getEvaluation()== "ممتاز")
                {
                    $evaluationArray[$i]["evaluation"] = 3;
                }elseif($evaluation->getEvaluation()== "جيد")
                {
                    $evaluationArray[$i]["evaluation"] = 2;
                }elseif ($evaluation->getEvaluation()== "سيء")
                {
                    $evaluationArray[$i]["evaluation"] = 1;
                }
                $evaluationArray[$i]["evaluation_value"] = $evaluation->getEvaluation();
                $evaluationArray[$i]["feedback"] = $evaluation->getFeedback();
                $evaluationArray[$i]['doctor_id'] = $evaluation->getDoctor()->getId();
                $evaluationArray[$i]['name_doctor'] = $evaluation->getDoctor()->getNameDoctor();
                $evaluationArray[$i]['photo_doctor'] = 'uploads/doctors/'.$evaluation->getDoctor()->getPhotoDoctor();
                $evaluationArray[$i]['user_id'] = $evaluation->getUser()->getId();
                $evaluationArray[$i]['username'] = $evaluation->getUser()->getName();
                $evaluationArray[$i]['photo_user'] = 'uploads/users/'.$evaluation->getUser()->getPhoto();
                $evaluationArray[$i]['appointment_id'] = $evaluation->getAppointment()->getId();
                $i++;
            }
                $tab_return['success'] = 1  ;
                $tab_return['data'] = $evaluationArray ;
            }
            return new JsonResponse($tab_return);
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

    public function api_edit_evaluationAction(Request $request, $evaluation_id)
    {
        $em = $this->getDoctrine()->getManager();
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $evaluationForUpdate = $em->getRepository('DoctorsAdminBundle:Evaluation')->find($evaluation_id);

            if (!empty($evaluationForUpdate)) {
                $evaluation = $request->request->get('evaluation');
                var_dump($evaluation);
                if (isset($evaluation)) {
                    $evaluationForUpdate->setEvaluation($evaluation);
                }
                $feedback = $request->request->get('feedback');
                if (isset($feedback)) {
                    $evaluationForUpdate->setFeedback($feedback);
                }

                $em->merge($evaluationForUpdate);
                $em->flush();
                $result = array(
                    'success' => 1,
                    'id' => $evaluationForUpdate->getId()
                );
                return new JsonResponse($result);
                $result = array(
                    'success' => 0,
                    'exist' => 1
                );
                return new JsonResponse($result);
            } else {
                $tab = array('auth' => 0);
                return new JsonResponse($tab);
            }
        }
    }

    public function api_delete_evaluationAction(Request $request, $evaluation_id)
    {
        $em = $this->getDoctrine()->getManager();
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $evaluationForUpdate = $em->getRepository('DoctorsAdminBundle:Evaluation')->find($evaluation_id);
            if (!empty($evaluationForUpdate)) {
                $evaluationForUpdate->setIsActiveEvaluation(0);
                $em->merge($evaluationForUpdate);
                $em->flush();
                $result = array(
                    'success' => 1,
                    'id' => $evaluation_id
                );
            } else {
                $result = array(
                    'exist' => 0
                );
            }
            return new JsonResponse($result);
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

    public function auth_key($key)
    {
        $em = $this->getDoctrine()->getManager();
        $codes = $em->getRepository('DoctorsEndpointBundle:Code')->findAll();
        $val = "";
        foreach ($codes as $code) {
            $val = $code->getNumber();
        }
        if ($val == md5($key)) {
            return true;
        } else {
            return false;
        }
    }
}