<?php

namespace Doctors\EndpointBundle\Controller;

use Doctors\AdminBundle\Entity\Appointment;
use Doctors\AdminBundle\Entity\Notification;
use Doctors\AdminBundle\Entity\TokenDoctor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class AppointmentApiController extends Controller
{
    public function addAppointmentAction(Request $request)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $doctor_id = $request->request->get('doctor_id');
            $doctor = $em->getRepository('DoctorsAdminBundle:Doctor')->find($doctor_id);
            $dateAppointmt = $request->request->get('date_appointment');
            $dateAppointmtdate = substr($dateAppointmt, 0, 2);
            $dateAppointmtMonth = substr($dateAppointmt, 3, 2);
            $dateAppointmtYear = substr($dateAppointmt, 6, 4);
            $dateAppt = $request->request->get('timeOfAppt');
            $dateApptHour = substr($dateAppt, 0, 2);
            $dateApptMinute = substr($dateAppt, 3, 2);
            $dateApptSeconds = substr($dateAppt, 6, 2);
            $dateFormattedAppointment = $dateAppointmtYear . '-' . $dateAppointmtMonth . '-' . $dateAppointmtdate . ' ' . $dateApptHour . ':' . $dateApptMinute . ':' . $dateApptSeconds . '00';
            $dateAppointment = new \DateTime($dateFormattedAppointment);
            $appointment = $em->getRepository('DoctorsAdminBundle:Appointment')->findBy(
                array('appointment' => $dateAppointment, 'doctor' => $doctor)
            );
            if (empty($appointment)) {
                $newAppointment = new Appointment();
                $newAppointment->setIsActiveAppointment(1);
                $newAppointment->setCreatedAtAppointment(new \Datetime());
                $newAppointment->setStatus('معلق');
                $user_id = $request->request->get('user_id');
                $user = $em->getRepository('DoctorsAdminBundle:User')->find($user_id);
                $newAppointment->setDoctor($doctor);
                $newAppointment->setUser($user);
                $newAppointment->setAppointment($dateAppointment);
                $newAppointment->setEval(0);
                $em->persist($newAppointment);
                $em->flush();
                $tokens = $em->getRepository('DoctorsAdminBundle:TokenDoctor')->findBy(
                    array('doctor' => $doctor)
                );
                if (!empty($tokens)) {
                    $tab = array();
                    foreach ($tokens as $token) {
                        $tab[] = $token->getTokenDoctor();
                    }
                }
                $tab_token = $tab;
                $title = "الأطباء ";
                $notification = new Notification();
                $text = '  قام المستخدم ' . $newAppointment->getUser()->getName() . ' بحجز موعد جديد ';
                $notification->setMessage($text);
                $notification->setType("appointment");
                $notification->setDate(new \DateTime());
                $notification->setSeen(0);
                $notification->setIsDoctor(1);
                $notification->setAppointment($newAppointment);
                $this->sendNotificationAppointment($tab_token, $title, $text);
                $em->persist($notification);
                $em->flush();
                $result = array(
                    'success' => 1,
                    'id' => $newAppointment->getId()
                );

            } else {
                $result = array(
                    'success' => 0,
                    'exist' => 1
                );
            }
            return new JsonResponse($result);
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

    public function loadDataHourWorkAction(Request $request)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $dateRDV = $request->request->get('dateAppointment');
            $dateRDV = new \DateTime($dateRDV);
            $em = $this->getDoctrine()->getManager();
            /*var_dump('_____________dateAppointment__________');
            var_dump($dateRDV);*/
            $dateRDVEnd = clone $dateRDV;
            $doctor_id = $request->request->get('doctor_id');
            /*var_dump('______doctor_id_______________');
            var_dump($doctor_id);*/
            $user_id = $request->request->get('user_id');
            $doctor = $em->getRepository('DoctorsAdminBundle:Doctor')->find($doctor_id);
            /*var_dump('________Doctor who have the doctor_id________');*/
            if (!empty($doctor)) {
                $dateRDVDay = $dateRDV->format('d');
                $dateRDVMonth = $dateRDV->format('m');
                $dateRDVYear = $dateRDV->format('Y');
                $dateRDVFormatted = $dateRDVYear . '-' . $dateRDVMonth . '-' . $dateRDVDay;
                $appointments = $em->getRepository('DoctorsAdminBundle:Appointment')->findCorrespendingAppointment($dateRDVFormatted, $doctor->getId());
                $em = $this->getDoctrine()->getManager();
                $doctor = $em->getRepository('DoctorsAdminBundle:Doctor')->find($doctor_id);
                $startTimeWork = $doctor->getStartTimeWork();
                $endTimeWork = $doctor->getEndTimeWork();
                $startWorkTimeHours = $startTimeWork->format('H');
                $startWorkTimeMinutes = $startTimeWork->format('i');
                $startWorkTimeSeconds = $startTimeWork->format('s');
                /* add hours and minutes for the selected day */
                $dateRDV->add(new \DateInterval('PT' . $startWorkTimeHours . 'H'));
                $dateRDV->add(new \DateInterval('PT' . $startWorkTimeMinutes . 'M'));
                $interval = '30';
                $timesWorkday = array();
                $timesWorkday[] = $dateRDV;
                $dateStarted = clone $dateRDV;
                $endTimeWorkHours = $endTimeWork->format('H');
                $endTimeWorkMinutes = $endTimeWork->format('i');
                $endTimeWorkSeconds = $endTimeWork->format('s');
                $dateRDVEnd->add(new \DateInterval('PT' . $endTimeWorkHours . 'H'));
                $dateRDVEnd->add(new \DateInterval('PT' . $endTimeWorkMinutes . 'M'));
                $dateEnded = clone $dateRDVEnd;
                $test = true;
                do {
                    $dateStarted = $this->addMinutes($dateStarted, $interval, $timesWorkday);
                    $timesWorkday[] = $dateStarted;
                    $dateStartedHour = $dateStarted->format('H');
                    $dateStartedMinute = $dateStarted->format('i');
                    $dateEndedHour = $dateEnded->format('H');
                    $dateEndedMinute = $dateEnded->format('i');
                    if ($dateEndedHour == $dateStartedHour) {
                        $test = false;
                    }
                    $dateStarted = clone $dateStarted;
                } while ($test);
                $appnmt = array();
                foreach ($appointments as $appointment) {
                    foreach ($appointment as $apt) {
                        $appnmt[] = $apt;
                    }
                }
                foreach ($timesWorkday as $time) {
                    foreach ($appnmt as $appt) {
                        if ($time == $appt) {
                            $time->disponible = 0;
                            $time = clone $time;
                        } else {
                            $time->disponible = 1;
                        }
                    }
                }
                $tab = array();
                foreach ($timesWorkday as $key => $value) {
                    $tab[$key]["date"] = $value->format('Y-m-d');
                    $tab[$key]["time"] = $value->format('H:i');
                    $tab[$key]["disponible"] = $value->disponible;
                }
                $result = array();
                $result['success'] = 1;
                $result['data'] = $tab;
                return new JsonResponse($result);
            } else {
                $result = array(
                    'success' => 0
                );
                return new JsonResponse($result);
            }
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

    public function addMinutes(\DateTime $startTimeWorked, $interval, array $timesWorkday)
    {
        $startTimeWorked->add(new \DateInterval('PT' . $interval . 'M'));
        return $startTimeWorked;
    }

    public function api_appointment_list_confirmedAction(Request $request)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $status = $request->request->get('status');
            $appointments = $em->getRepository('DoctorsAdminBundle:Appointment')->findBy(
                array(
                    'status' => $status
                ),
                array('id' => 'desc')
            );
            $i = 0;
            $appointmentArray = array();
            foreach ($appointments as $appointment) {
                $appointmentArray[$i]["appointmentId"] = $appointment->getId();
                $appointmentArray[$i]["statusAppointment"] = $appointment->getStatus();
                $appointmentArray[$i]["appointment"] = $appointment->getAppointment();
                $appointmentArray[$i]['doctor'] = $appointment->getDoctor()->getId();
                $appointmentArray[$i]['user'] = $appointment->getUser()->getId();
                $i++;
            }
            return new JsonResponse($appointmentArray);
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

    public function api_delete_appointmentAction(Request $request, $appointment_id)
    {
        $em = $this->getDoctrine()->getManager();
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $appointment = $em->getRepository('DoctorsAdminBundle:Appointment')->find($appointment_id);
            if (!empty($appointment)) {
                $appointment->setIsActiveAppointment(0);
                $em->merge($appointment);
                $em->flush();
                $result = array(
                    'success' => 1,
                    'id' => $appointment_id
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

    public function api_get_appointment_by_doctorAction(Request $request, $doctor_id)
    {
        $em = $this->getDoctrine()->getManager();
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $doctor = $em->getRepository('DoctorsAdminBundle:Doctor')->find($doctor_id);
            $appointments = $em->getRepository('DoctorsAdminBundle:Appointment')->findBy(
                array('doctor' => $doctor, 'isActiveAppointment' => 1),
                array('id' => 'desc')
            );
            $i = 0;
            $appointmentArray = array();
            $tab_return = array();
            if (empty($appointments)) {
                $tab_return['success'] = 0;
                $tab_return['data'] = array();
            } else {
                foreach ($appointments as $appointment) {
                    $appointmentArray[$i]["appointmentId"] = $appointment->getId();
                    $appointmentArray[$i]["statusAppointment"] = $appointment->getStatus();
                    $appointmentArray[$i]["appointment"] = $appointment->getAppointment();
                    $appointmentArray[$i]["date"] = $appointment->getAppointment()->format('Y-m-d');
                    $appointmentArray[$i]["time"] = $appointment->getAppointment()->format('H:i');
                    $appointmentArray[$i]['doctor'] = $appointment->getDoctor()->getId();
                    $appointmentArray[$i]['namedoctor'] = $appointment->getDoctor()->getNameDoctor();
                    $appointmentArray[$i]['user'] = $appointment->getUser()->getId();
                    $appointmentArray[$i]['userName'] = $appointment->getUser()->getName();
                    $appointmentArray[$i]['reason'] = $appointment->getReason();
                    $i++;
                }
                $tab_return['success'] = 1;
                $tab_return['data'] = $appointmentArray;
            }

            return new JsonResponse($tab_return);
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

    public function api_get_appointment_by_userAction(Request $request, $user_id)
    {
        $em = $this->getDoctrine()->getManager();
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $user = $em->getRepository('DoctorsAdminBundle:User')->find($user_id);
            $appointments = $em->getRepository('DoctorsAdminBundle:Appointment')->findBy(
                array('user' => $user, 'isActiveAppointment' => 1),
                array('id' => 'desc')
            );
            $i = 0;
            $appointmentArray = array();
            $tab_return = array();
            if (empty($appointments)) {
                $tab_return['success'] = 0;
                $tab_return['data'] = array();
            } else {
                foreach ($appointments as $appointment) {
                    $appointmentArray[$i]["appointmentId"] = $appointment->getId();
                    $appointmentArray[$i]["statusAppointment"] = $appointment->getStatus();
                    $appointmentArray[$i]["date"] = $appointment->getAppointment()->format('Y-m-d');
                    $appointmentArray[$i]["time"] = $appointment->getAppointment()->format('H:i');
                    $appointmentArray[$i]['doctor'] = $appointment->getDoctor()->getId();
                    $appointmentArray[$i]['doctorName'] = $appointment->getDoctor()->getNameDoctor();
                    $appointmentArray[$i]['user'] = $appointment->getUser()->getId();
                    $appointmentArray[$i]['userName'] = $appointment->getUser()->getName();
                    $appointmentArray[$i]['reason'] = $appointment->getReason();
                    if ($appointment->getEval() == false) {
                        $appointmentArray[$i]['eval'] = 0;
                        $appointmentArray[$i]['id'] = null;
                        $appointmentArray[$i]['evaluation'] = null;
                        $appointmentArray[$i]['feedback'] = null;
                    } else {
                        $evaluations = $em->getRepository('DoctorsAdminBundle:Evaluation')->findAll();
                        foreach ($evaluations as $evaluationVal) {
                            if ($evaluationVal->getAppointment() == $appointment) {
                                if ($evaluationVal->getStatusEvaluation() == false) {
                                    $appointmentArray[$i]['eval'] = 1;
                                    $appointmentArray[$i]['id'] = $evaluationVal->getId();
                                    $appointmentArray[$i]['evaluation'] = $evaluationVal->getEvaluation();
                                    $appointmentArray[$i]['feedback'] = $evaluationVal->getFeedback();
                                } else {
                                    $appointmentArray[$i]['eval'] = 2;
                                    $appointmentArray[$i]['id'] = $evaluationVal->getId();
                                    $appointmentArray[$i]['evaluation'] = $evaluationVal->getEvaluation();
                                    $appointmentArray[$i]['feedback'] = $evaluationVal->getFeedback();
                                }
                            }
                        }
                    }

                    if ($appointmentArray[$i]['eval'] == true) {
                        /*$appointmentArray[$i][]*/
                    }
                    $i++;
                }
                $tab_return['success'] = 1;
                $tab_return['data'] = $appointmentArray;
            }
            return new JsonResponse($tab_return);
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }


    public function api_update_status_appointmentAction(Request $request, $appointment_id)
    {
        $em = $this->getDoctrine()->getManager();
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $status = $request->request->get('status');
            if (isset($status)) {
                $appointment = $em->getRepository('DoctorsAdminBundle:Appointment')->find($appointment_id);
                if (!empty($appointment)) {
                    if ($status == "مرفوض") {
                        $reason = $request->request->get('reason');
                        $appointment->setReason($reason);
                    }
                    $appointment->setStatus($status);
                    $em->merge($appointment);
                    $em->flush();
                    $user = $appointment->getUser();
                    $tokens = $em->getRepository('DoctorsAdminBundle:TokenUser')->findBy(
                        array('user' => $user)
                    );
                    if (!empty($tokens)) {
                        $tab = array();
                        foreach ($tokens as $token) {
                            $tab[] = $token->getToken();
                        }
                    }//problems with tokens when is empty
                    $tab_token = $tab;
                    $title = "الأطباء ";
                    $notification = new Notification();
                    if ($appointment->getStatus() == "مؤكد") {
                        $text = 'تمت الموافقة على الموعد';
                        $notification->setMessage($text);
                        $notification->setType("appointment");
                        $notification->setDate(new \DateTime());
                        $notification->setSeen(0);
                        $notification->setIsDoctor(0);
                        $notification->setAppointment($appointment);
                    }
                    if ($appointment->getStatus() == "مرفوض") {
                        $text = 'تم رفض الموعد مع الطبيب';
                        $notification->setMessage($text);
                        $notification->setType("appointment");
                        $notification->setDate(new \DateTime());
                        $notification->setSeen(0);
                        $notification->setIsDoctor(0);
                        $notification->setAppointment($appointment);
                    }
                    $this->sendNotificationAppointment($tab_token, $title, $text);
                    $em->persist($notification);
                    $em->flush();
                    $result = array(
                        'success' => 1,
                        'id' => $appointment_id
                    );
                } else {
                    $result = array(
                        'exist' => 0
                    );
                }
                return new JsonResponse($result);
            }

        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }


    public function sendNotificationAppointment($token, $title, $text)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'to' => $token,
            'notification' => array('title' => $title, 'body' => $text),
            'data' => array('message' => 'Doctors notification')
        );
        $headers = array(
            'Authorization: key=AAAA_hUBCYE:APA91bHsJRk0F6sty7Vz1L6HyUoWqHp1wxKLNKTn4N2BT3sEoONGI5I1HTwl3HlswJ-_1JW-OnArf__NaSBikXBnIa-XMeTsKl8m2hcuU2S5SvrlyRzNOJ23bvUO_aIdhMts1ocg9LjY',
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
        return true;
    }

    public function api_send_notification_corn_jobsAction(Request $request)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $nowDate = date('Y-m-d');
            $appointments = $em->getRepository('DoctorsAdminBundle:Appointment')->findAppointmentsDate($nowDate);
            if (!empty($appointments)) {
                $appt = array();
                $i = 0;
                foreach ($appointments as $appointment) {

                    $minutes = 60;
                    $appt[$i]['appointment'] = $appointment['appointment']->add(new \DateInterval('PT' . $minutes . 'M'));
                    $appt[$i]['user_id'] = $appointment[1];
                    $i++;
                }
                foreach ($appt as $app) {
                    foreach ($app as $appointmentPlusHour) {
                        $dattte = new \DateTime();
                        if ($appointmentPlusHour == $dattte) {
                            $user = $em->getRepository('DoctorsAdminBundle:User')->find($app['user_id']);
                            $tokens = $em->getRepository('DoctorsAdminBundle:TokenUser')->findBy(
                                array('user' => $user)
                            );
                            if (!empty($tokens)) {
                                $tab = array();
                                foreach ($tokens as $token) {
                                    $tab[] = $token->getTokenUser();
                                }
                            }
                            $tab_token = $tab;
                            $title = "الأطباء";
                            $notification = new Notification();
                            $text = 'يمكنك تقييم موعدك';
                            $notification->setMessage($text);
                            $notification->setType("appointment");
                            $notification->setDate(new \DateTime());
                            $notification->setSeen(0);
                            $notification->setIsDoctor(0);
                            $notification->setAppointment($appointmentPlusHour);
                            $this->sendNotificationAppointment($tab_token, $title, $text);
                            $em->persist($notification);
                            $em->flush();
                        }
                    }
                }
            }return true;
        }
        else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

    public function cornJobsNotificationRecallAction(Request $request)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $nowDate = date('Y-m-d');
            $nowDateTime = new \DateTime();
            $appointments = $em->getRepository('DoctorsAdminBundle:Appointment')->findAppointmentsDate($nowDate);
            //var_dump($appointments);
            $minutes = 60;
            $nowPlusHourDateTime = $nowDateTime->add(new \DateInterval('PT' . $minutes . 'M'));
            foreach ($appointments as $appointment)
            {
                if($appointment == $nowPlusHourDateTime)
                {
                    //var_dump($appointment);
                    $user_id = $em->getRepository('DoctorsAdminBundle:User')->find($appointment->getUser()->getId());
                    $user = $em->getRepository('DoctorsAdminBundle:User')->find($user_id);
                    $tokens = $em->getRepository('DoctorsAdminBundle:TokenUser')->findBy(
                        array('user' => $user)
                    );
                    if (!empty($tokens)) {
                        $tab = array();
                        foreach ($tokens as $token) {
                            $tab[] = $token->getTokenUser();
                        }
                    }
                    $tab_token = $tab;
                    $title = " تذكير بموعد";
                    $notification = new Notification();
                    $text = 'تذكير لديكم موعد بعد ساعة';
                    $notification->setMessage($text);
                    $notification->setType("appointment");
                    $notification->setDate(new \DateTime());
                    $notification->setSeen(0);
                    $notification->setIsDoctor(0);
                    $notification->setAppointment($appointment);
                    $this->sendNotificationAppointment($tab_token, $title, $text);
                    $em->persist($notification);
                    $em->flush();
                }
            }
            return true;
        }else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

    public function api_notificationListAction(Request $request)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $isDoctor = $request->request->get('isDoctor');
            $identifiant = $request->request->get('identifiant');
            if ($isDoctor == 0) {
                $user = $em->getRepository('DoctorsAdminBundle:User')->find($identifiant);
                $appointments = $em->getRepository('DoctorsAdminBundle:Appointment')->findBy(
                    array('user' => $user)
                );
                $i = 0;
                $notificationArray = array();
                foreach ($appointments as $appointment) {
                    $notifications = $em->getRepository('DoctorsAdminBundle:Notification')->findBy(
                        array('appointment' => $appointment)
                    );
                    foreach ($notifications as $notification) {
                        if ($notification->getIsDoctor() == 0) {
                            $notificationArray[$i]['notificationId'] = $notification->getId();
                            $notificationArray[$i]['notificationMessage'] = $notification->getMessage();
                            $notificationArray[$i]['notificationType'] = $notification->getType();
                            $notificationArray[$i]['notificationDate'] = $notification->getDate()->format('Y-m-d');
                            $notificationArray[$i]['notificationTime'] = $notification->getDate()->format('H:i');
                            if($notification->getSeen()== false){
                                $notificationArray[$i]['notificationSeen'] = 0;
                            }else{
                                $notificationArray[$i]['notificationSeen'] = 1;
                            }
                            if($notification->getIsDoctor()==false){
                                $notificationArray[$i]['notificationIsDoctor'] = 0;
                            }else{
                                $notificationArray[$i]['notificationIsDoctor'] = 1;
                            }
                            $i++;
                        }
                    }
                }
            } else {
                $doctor = $em->getRepository('DoctorsAdminBundle:Doctor')->find($identifiant);
                $appointments = $em->getRepository('DoctorsAdminBundle:Appointment')->findBy(
                    array('doctor' => $doctor)
                );
                $i = 0;
                $notificationArray = array();
                foreach ($appointments as $appointment) {
                    $notifications = $em->getRepository('DoctorsAdminBundle:Notification')->findBy(
                        array('appointment' => $appointment)
                    );
                    foreach ($notifications as $notification) {
                        if ($notification->getIsDoctor() == 1) {
                            $notificationArray[$i]['notificationId'] = $notification->getId();
                            $notificationArray[$i]['notificationMessage'] = $notification->getMessage();
                            $notificationArray[$i]['notificationType'] = $notification->getType();
                            $notificationArray[$i]['notificationDate'] = $notification->getDate()->format('Y-m-d');
                            $notificationArray[$i]['notificationTime'] = $notification->getDate()->format('H:i');
                            if($notification->getSeen()== false){
                                $notificationArray[$i]['notificationSeen'] = 0;
                            }else{
                                $notificationArray[$i]['notificationSeen'] = 1;
                            }
                            if($notification->getIsDoctor()==false){
                            $notificationArray[$i]['notificationIsDoctor'] = 0;
                            }else{
                                $notificationArray[$i]['notificationIsDoctor'] = 1;
                            }
                            $i++;
                        }
                    }
                }
            }

            return new JsonResponse($notificationArray);

        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

    public function api_update_notificationListAction(Request $request)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $isDoctor = $request->request->get('isDoctor');
            $identifiant = $request->request->get('identifiant');
            if ($isDoctor == 0) {
                $user = $em->getRepository('DoctorsAdminBundle:User')->find($identifiant);
                $appointments = $em->getRepository('DoctorsAdminBundle:Appointment')->findBy(
                    array('user' => $user)
                );
                $i = 0;
                $notificationArray = array();
                foreach ($appointments as $appointment) {
                    $notifications = $em->getRepository('DoctorsAdminBundle:Notification')->findBy(
                        array('appointment' => $appointment)
                    );
                    foreach ($notifications as $notification) {
                        if ($notification->getIsDoctor() == 0) {
                            $notification->setSeen(1);
                            $em->merge($notification);
                            $em->flush();
                            $i++;
                        }
                    }
                }
            } else {
                $doctor = $em->getRepository('DoctorsAdminBundle:Doctor')->find($identifiant);
                $appointments = $em->getRepository('DoctorsAdminBundle:Appointment')->findBy(
                    array('doctor' => $doctor)
                );
                $i = 0;
                $notificationArray = array();
                foreach ($appointments as $appointment) {
                    $notifications = $em->getRepository('DoctorsAdminBundle:Notification')->findBy(
                        array('appointment' => $appointment)
                    );
                    foreach ($notifications as $notification) {
                        if ($notification->getIsDoctor() == 1) {
                            $notification->setSeen(1);
                            $em->merge($notification);
                            $em->flush();
                            $i++;
                        }
                    }
                }
            }
            $tab = array('success'=>1);
            return new JsonResponse($tab);

        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

/*    public function testNotifAction(Request $request)
    {
        $token = "f3zDJvf6_A8:APA91bHMHEAhdN7KyIjtsRk7lf2Skoy1MVUdU604b18QVAbh4zvWeB3638ag7P-d9QXhAZmTXlPGP6rWiNI5jed7EYhJbbbs3FGU_A2nHUJZblJtBXmMGM7Y0FezQ_HpIi37JB0kaTXL";
        $title = "doctor";
        $text="C de bla bla pour le test";
        $this->sendNotificationAppointment($token, $title, $text);
        return new JsonResponse(array('success'=>1));
    }*/

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