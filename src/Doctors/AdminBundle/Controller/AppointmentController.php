<?php

namespace Doctors\AdminBundle\Controller;


use Doctors\AdminBundle\Entity\Appointment;
use Doctors\AdminBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AppointmentController extends Controller
{

    //List of users
    public function appointmentsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $appointments = $em->getRepository('DoctorsAdminBundle:Appointment')->findBy(array(), array('id' => 'desc'));
        return $this->render('DoctorsAdminBundle:Appointment:appointments.html.twig', array('appointments'=>$appointments));
    }

    // show user informations
    public function showAppointmentAction(Request $request, Appointment $appointment)
    {
        $em = $this->getDoctrine()->getManager();
        $specificAppointment = $em->getRepository('DoctorsAdminBundle:Appointment')->find($appointment->getId());
        return $this->render('DoctorsAdminBundle:Appointment:showAppointment.html.twig', array('appointment'=>$specificAppointment));
    }

    //Add user
    public function addAppointmentAction(Request $request)
    {
        $appointment = new Appointment();
        $form = $this->createForm('Doctors\AdminBundle\Form\AppointmentType', $appointment);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $appointment->setIsActiveAppointment(1);
            $appointment->setCreatedAtAppointment(new \Datetime());
            $appointment->setEval(0);
            $dateAppointmt = $request->request->get('doctors_adminbundle_appointment');
            var_dump($dateAppointmt);
            $dateAppointmtdate = substr($dateAppointmt['appointment'],0,2);
            $dateAppointmtMonth = substr($dateAppointmt['appointment'],3,2);
            $dateAppointmtYear = substr($dateAppointmt['appointment'],6,4);
            $dateAppt = $request->request->get('dateAppt');
            $dateApptHour = substr($dateAppt,11,2);
            $dateApptMinute = substr($dateAppt,14,2);
            $dateApptSeconds = substr($dateAppt,17,2);
            $dateFormattedAppointment = $dateAppointmtYear.'-'.$dateAppointmtMonth.'-'.$dateAppointmtdate.' '.$dateApptHour.':'.$dateApptMinute.':'.$dateApptSeconds;
            var_dump($dateAppointmtdate);
            var_dump($dateAppointmtMonth);
            var_dump($dateAppointmtYear);
            var_dump($dateAppt);
            var_dump('time of appointment');
            var_dump($dateApptHour);
            var_dump($dateApptMinute);
            var_dump($dateApptSeconds);
            $dateAppointment = new \DateTime($dateFormattedAppointment);
            var_dump($dateAppointment);
            /*$format = 'Y-m-d H:i:s';
            $dateAppointmt = \DateTime::createFromFormat($format, $dateAppointmt['appointment']);*/
            //$date = new \DateTime::createFromFormat('Y-m-d hh:i', $dateAppointmt['appointment']);
            /*var_dump($dateAppointment);*/
            $appointment->setAppointment($dateAppointment);
            $em->persist($appointment);
            $em->flush();
            $session = $request->getSession();
            $session->getFlashBag()->add('msg', 'تمت اضافة الموعد بنجاح');
            return $this->redirectToRoute('doctors_admin_appointment');
        }
        return $this->render('DoctorsAdminBundle:Appointment:ajouterAppointment.html.twig', array(
            'appointment' => $appointment,
            'form' => $form->createView(),
        ));
    }


    public function loadDataHourWorkAction(Request $request)
    {
        $dateRDV = $request->request->get('dateAppointment');
        $dateRDV = new \DateTime($dateRDV);
        $em = $this->getDoctrine()->getManager();
        $dateRDVEnd = clone $dateRDV;
        /*var_dump($dateRDV);*/
        $doctor_id = $request->request->get('doctor_id');
        $user_id = $request->request->get('user_id');
        $doctor = $em->getRepository('DoctorsAdminBundle:Doctor')->find($doctor_id);

        $dateRDVDay = $dateRDV->format('d');
        $dateRDVMonth = $dateRDV->format('m');
        $dateRDVYear = $dateRDV->format('Y');
        $dateRDVFormatted = $dateRDVYear.'-'.$dateRDVMonth.'-'.$dateRDVDay;
        $appointments = $em->getRepository('DoctorsAdminBundle:Appointment')->findCorrespendingAppointment($dateRDVFormatted, $doctor->getId());
        /*var_dump($appointments);*/
        /*    array(
                'doctor' => $doctor
            )
        );
        $tab = array();
        $i = 0 ;
        foreach($appointments as $appointment){

            if($appointment->getAppointment()->format('Y-m-d') == $dateRDV->format('Y-m-d'))
            {
                $tab[$i] = $appointment ;
                    $i++ ;
            }
        }
        print_r($tab);*/

        /*$appointment = $em->getRepository('DoctorsAdminBundle:Appointment')->findCorrespendingAppointment($dateRDV,$user_id,$doctor_id);*/
        $em = $this->getDoctrine()->getManager();
        $doctor = $em->getRepository('DoctorsAdminBundle:Doctor')->find($doctor_id);
        $startTimeWork = $doctor->getStartTimeWork();
        $endTimeWork = $doctor->getEndTimeWork();
        /*var_dump($startTimeWork);*/
        /*$startTimeWork = new \DateTime($startTimeWork);
        $endTimeWork = new \DateTime($endTimeWork);*/
        $startWorkTimeHours = $startTimeWork->format('H');
        $startWorkTimeMinutes = $startTimeWork->format('i');
        $startWorkTimeSeconds = $startTimeWork->format('s');
        /* add hours and minutes for the selected day */
        $dateRDV->add(new \DateInterval('PT'.$startWorkTimeHours.'H'));
        $dateRDV->add(new \DateInterval('PT'.$startWorkTimeMinutes.'M'));
        $interval = '30';
        /*var_dump('___initialement startTimeWork______');
        var_dump($startTimeWork);
        var_dump('_____intitialemeent endTimeWork____');
        var_dump($endTimeWork);
        var_dump($interval);
        var_dump('______initialement dateRDV___________');
        var_dump($dateRDV);*/
        $timesWorkday = array();
        $timesWorkday[]=$dateRDV;
        $dateStarted = clone $dateRDV;
       /* var_dump('-----timesWorkDay intialisé avec dateRDV');
        var_dump($timesWorkday);*/
        /*$startTimeWork = $this->addMinutes($dateStarted, $interval);*/
        /*var_dump('____apres lajout startTimeWork');
        var_dump($startTimeWork);
        var_dump('_____apres lajout dateRDV____');
        var_dump($dateRDV);*/
        /*array_push($timesWorkday, $startTimeWork);*/
        /*var_dump('_____aprs lajout timeWorkday_______');
        var_dump($timesWorkday);*/
        $endTimeWorkHours = $endTimeWork->format('H');
        $endTimeWorkMinutes = $endTimeWork->format('i');
        $endTimeWorkSeconds = $endTimeWork->format('s');
        $dateRDVEnd->add(new \DateInterval('PT'.$endTimeWorkHours.'H'));
        $dateRDVEnd->add(new \DateInterval('PT'.$endTimeWorkMinutes.'M'));
       /* array_push($timesWorkday, $dateRDVEnd);*/
        /*var_dump($timesWorkday);
        var_dump($startWorkTimeHours);
        var_dump($endTimeWorkHours);
        var_dump($startWorkTimeHours == $endTimeWorkHours);*/
        $dateEnded = clone $dateRDVEnd;
        /*$dateStartedHour = $dateStarted->format('H');
        $dateStartedMinute = $dateStarted->format('i');
        $dateEndedHour = $dateEnded->format('H');
        $dateEndedMinute = $dateEnded->format('i');*/
        $test = true;
        do
        {
            /*var_dump('boucle while');*/
            $dateStarted = $this->addMinutes($dateStarted, $interval, $timesWorkday);
            /*$dateStarted->disponible = 0 ;*/
            $timesWorkday[]=$dateStarted;
            $dateStartedHour = $dateStarted->format('H');
            /*var_dump($dateStartedHour);*/
            $dateStartedMinute = $dateStarted->format('i');
            $dateEndedHour = $dateEnded->format('H');
            $dateEndedMinute = $dateEnded->format('i');
            /*var_dump($dateEndedHour);*/
            if($dateEndedHour == $dateStartedHour){
                $test = false;
            }
            $dateStarted = clone $dateStarted;
            /*array_push($timesWorkday, $startTimeWork);*/
        }while($test);

        /*var_dump($appointments);

        var_dump('-----------tableau des temps-------');

        var_dump($timesWorkday);*/
        $appnmt = array();
        foreach ($appointments as $appointment)
        {
            foreach ($appointment as $apt)
            {
                $appnmt[] = $apt;
            }
        }
        /*var_dump('-------appnmt------');
        var_dump($appnmt);*/

        foreach ($timesWorkday as $time)
        {
            foreach ($appnmt as $appt)
            {
                if($time == $appt)
                {
                    $time->disponible= 0;
                    $time =  clone $time;
                }
                else{
                    $time->disponible= 1;
                }
            }
        }
        /*foreach ($appointments as $appointment)
        {
            echo '<pre>';
                print_r($appointment);
                echo '</pre>';
            foreach ($appointment as $apt)
            {
                var_dump('------apt----');
                echo '<pre>';
                print_r($apt);
                echo '</pre>';

                foreach ($timesWorkday as $timeworkday)
                {
                    if($apt == $timeworkday)
                    {
                        $timeworkday->disponible = 0;
                    }
                    else
                    {
                        $timeworkday->disponible = 1;
                    }
                }
            }
        }*/
        /*array_push($timesWorkday, $doctor_id);*/
        /*var_dump($timesWorkday);*/
        /*return new JsonResponse(array('dateWork'=>$timesWorkday,'doctor_id'=>$doctor_id,'success'=>'success'));*/
        return new JsonResponse($timesWorkday);
    }

    public function addMinutes(\DateTime $startTimeWorked, $interval, array $timesWorkday)
    {

        $startTimeWorked->add(new \DateInterval('PT'.$interval.'M'));

        //array_push($timesWorkday, $startTimeWorked);
        /*$startWorkTimeHours = $startTimeWork->format('H');
        $startWorkTimeMinutes = $startTimeWork->format('i');
        $startWorkTimeSeconds = $startTimeWork->format('s');
        var_dump($startWorkTimeMinutes);
        $endWorkTimeHours = $endTimeWork->format('H');
        $endWorkTimeMinutes = $endTimeWork->format('i');
        $endWorkTimeSeconds = $endTimeWork->format('s');
        var_dump($endWorkTimeHours);
        $startTimeWorkStamp = $startTimeWork->getTimestamp();
        $intervalStamp = $interval->getTimestamp();

        $date = $startTimeWorkStamp+$interval*60000;
        $date = new \DateTime(new \DateTime(newdate + ' ' + newTime) + minutes * 60000);*/
        /*var_dump($startTimeWorked);*/
        return $startTimeWorked;
    }





    /*private function arabic_e2w($str)
    {
        $arabic_eastern = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
        $arabic_western = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        for ($i=0; $i<=9; $i++)
        {
            $arabic_eastern[$i] = utf8_encode($arabic_eastern[$i]);
        }
        var_dump($arabic_eastern);
        return str_replace($arabic_eastern, $arabic_western, $str);

    }*/

    //Update user
    public function editAppointmentAction(Request $request, Appointment $appointment)
    {
        $editForm = $this->createForm('Doctors\AdminBundle\Form\EditAppointmentType', $appointment);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted()) {
        	$reason = $request->request->get('unconfirmedReason');
            if(isset($reason))
            {
                $appointment->setReason($reason);
            }
            /*$dateAppointmt = $request->request->get('doctors_adminbundle_appointment');
            $dateAppointment = new \DateTime($dateAppointmt['appointment']);
            $appointment->setAppointment($dateAppointment);*/
            $this->getDoctrine()->getManager()->flush();
            $dateAppointment = $appointment->getAppointment();
            $session = $request->getSession();
            $session->getFlashBag()->add('msg', 'تم تعديل الموعد  بنجاح');
            return $this->redirectToRoute('doctors_admin_appointment');
        }

        return $this->render('DoctorsAdminBundle:Appointment:editAppointment.html.twig', array(
            'appointment' =>$appointment,
            'edit_form' =>$editForm->createView(),
        ));
    }

    //delete user
    public function deleteAppointmentAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $appointment = $em->getRepository('DoctorsAdminBundle:Appointment')->find($id);
        $appointment->setIsActiveAppointment(0);
        $em->merge($appointment);
        $em->flush();
        $session = $request->getSession();
        $session->getFlashBag()->add('msg', 'تم حذف المستخدم بنجاح');
        return $this->redirectToRoute('doctors_admin_appointment');
    }
}