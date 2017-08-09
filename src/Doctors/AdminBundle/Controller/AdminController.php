<?php

namespace Doctors\AdminBundle\Controller;

use Doctors\AdminBundle\Entity\Appointment;
use Doctors\AdminBundle\Entity\Doctor;
use Doctors\AdminBundle\Entity\Evaluation;
use Doctors\AdminBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class AdminController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $doctorNumbers = $em->getRepository('DoctorsAdminBundle:Doctor')->getDoctorNumbers();
        $appointmentNumbers = $em->getRepository('DoctorsAdminBundle:Appointment')->getAppointmentNumbers();
        $userNumbers = $em->getRepository('DoctorsAdminBundle:User')->getUserNumbers();
        $evaluationNumbers = $em->getRepository('DoctorsAdminBundle:Evaluation')->getEvaluationNumbers();
        $specialities = $em->getRepository('DoctorsAdminBundle:Speciality')->findBy(
            array('isActiveSpeciality'=>1)
        );
        $doctorsSpecialities = array();
        foreach ($specialities as $speciality)
        {
            $doctors = $em->getRepository('DoctorsAdminBundle:Doctor')->findBy(
               array(
                   'speciality'=>$speciality,
                   'isActiveDoctor'=>1
                   )
            );
            $doctorsSpecialities[$speciality->getNameSpeciality()] = count($doctors);

        }
        $lastUsers = $em->getRepository('DoctorsAdminBundle:User')->findBy(
            array('isActive'=>1),
            array('id'=>'desc'),
            5,
            0
        );
        $lastEvaluations = $em->getRepository('DoctorsAdminBundle:Evaluation')->findBy(
            array('isActiveEvaluation'=>1),
            array('id'=>'desc'),
            5,
            0
        );
        $lastAppointments = $em->getRepository('DoctorsAdminBundle:Appointment')->findBy(
            array('isActiveAppointment'=>1),
            array('id'=>'desc'),
            5,
            0
        );
        //appointments
        $appointmentConfirmedNumbers = $em->getRepository('DoctorsAdminBundle:Appointment')->getConfirmedAppointmentNumbers();
        $appointmnentUnconfirmedNumbers = $em->getRepository('DoctorsAdminBundle:Appointment')->getUnConfirmedAppointmentNumbers();
        $appointmentExceededNumbers = $em->getRepository('DoctorsAdminBundle:Appointment')->getExceededAppointmentNumbers();
        $appointmentWaintingNumbers = $em->getRepository('DoctorsAdminBundle:Appointment')->getWaitingAppointmentNumbers();
        $confirmedPercentAppointment = $this->divisionStatistique($appointmentConfirmedNumbers, $appointmentNumbers);
        $unconfirmedPercentAppointment = $this->divisionStatistique($appointmnentUnconfirmedNumbers, $appointmentNumbers);
        $exceededPercentAppointment = $this->divisionStatistique($appointmentExceededNumbers, $appointmentNumbers);
        $waitingPercentAppointment = $this->divisionStatistique($appointmentWaintingNumbers,$appointmentNumbers);
        return $this->render('DoctorsAdminBundle:Admin:index.html.twig', array(
            'confirmedPercentAppointment'=>$confirmedPercentAppointment,
            'unconfirmedPercentAppointment'=>$unconfirmedPercentAppointment,
            'exceededPercentAppointment'=>$exceededPercentAppointment,
            'waitingPercentAppointment'=>$waitingPercentAppointment,
            'appointmentConfirmedNumbers'=>$appointmentConfirmedNumbers,
            'appointmnentUnconfirmedNumbers'=>$appointmnentUnconfirmedNumbers,
            'appointmentExceededNumbers'=>$appointmentExceededNumbers,
            'appointmentWaintingNumbers'=>$appointmentWaintingNumbers,
            'evaluationNumbers'=>$evaluationNumbers,
            'userNumbers'=>$userNumbers,
            'doctorNumbers'=>$doctorNumbers,
            'doctorsSpecialities'=>$doctorsSpecialities,
            'lastUsers'=>$lastUsers,
            'lastEvaluations'=>$lastEvaluations,
            'lastAppointments'=>$lastAppointments,
            'appointmentNumbers'=>$appointmentNumbers));
    }
    public function divisionStatistique($a, $b)
    {
        $result = 0;
        if ($b == 0){
            $result = null;
            return $result;
        }else {
            $result = $a*100 / $b;
            return $result;
        }
    }

}
