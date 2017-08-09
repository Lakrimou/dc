<?php

namespace Doctors\EndpointBundle\Controller;

use Doctors\AdminBundle\Entity\TokenDoctor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


class DoctorApiController extends Controller
{

    public function api_doctor_listAction(Request $request)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $doctors = $em->getRepository('DoctorsAdminBundle:Doctor')->findBy(
                array('isActiveDoctor' => '1'),
                array('id' => 'desc')
            );
            $i = 0;
            $doctorArray = array();
            $tab_return = array();
            if(empty($doctors)){
                $tab_return['success'] = 0  ;
                $tab_return['data'] = array() ;
            }else{
            foreach ($doctors as $doctor) {
                $doctorArray[$i]["doctorId"] = $doctor->getId();
                $doctorArray[$i]["nameDoctor"] = $doctor->getNameDoctor();
                $doctorArray[$i]["nationality"] = $doctor->getNationality();
                $doctorArray[$i]["phoneNumberDoctor"] = $doctor->getPhoneNumberDoctor();
                $doctorArray[$i]["emailDoctor"] = $doctor->getEmailDoctor();
                $doctorArray[$i]['workplaceName'] = $doctor->getWorkplaceName();
                $doctorArray[$i]['workplace'] = $doctor->getWorkplace();
                $doctorArray[$i]['countryDoctor'] = $doctor->getCountryDoctor();
                $doctorArray[$i]['photoDoctor'] = 'uploads/doctors/'.$doctor->getPhotoDoctor();
                $doctorArray[$i]['townDoctor'] = $doctor->getTownDoctor();
                $doctorArray[$i]['cityDoctor'] = $doctor->getCityDoctor();
                $doctorArray[$i]['speciality'] = $doctor->getSpeciality()->getNameSpeciality();
                $doctorArray[$i]['longitude'] = $doctor->getLongitude();
                $doctorArray[$i]['lattitude'] = $doctor->getLattitude();
                $doctorArray[$i]['profile_completed'] = $doctor->getIsCompleted();
                $i++;
            }
                $tab_return['success'] = 1  ;
                $tab_return['data'] = $doctorArray ;
            }
            return new JsonResponse($tab_return);
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }


    public function api_doctor_listDoctor_SpecialityAction(Request $request, $id)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $doctors = $em->getRepository('DoctorsAdminBundle:Doctor')->findBy(
                array('isActiveDoctor' => '1', 'speciality'=>$id),
                array('id' => 'desc')
            );
            $i = 0;
            $doctorArray = array();
            $tab_return = array();
            if(empty($doctors)){
                $tab_return['success'] = 0  ;
                $tab_return['data'] = array() ;
            }else{
                foreach ($doctors as $doctor) {
                    $doctorArray[$i]["doctorId"] = $doctor->getId();
                    $doctorArray[$i]["nameDoctor"] = $doctor->getNameDoctor();
                    $doctorArray[$i]["phoneNumberDoctor"] = $doctor->getPhoneNumberDoctor();
                    $doctorArray[$i]['workplaceName'] = $doctor->getWorkplaceName();
                    $doctorArray[$i]['countryDoctor'] = $doctor->getCountryDoctor();
                    $doctorArray[$i]['townDoctor'] = $doctor->getTownDoctor();
                    $doctorArray[$i]['cityDoctor'] = $doctor->getCityDoctor();
                    $doctorArray[$i]['speciality'] = $doctor->getSpeciality()->getNameSpeciality();
                    $doctorArray[$i]['longitude'] = $doctor->getLongitude();
                    $doctorArray[$i]['lattitude'] = $doctor->getLattitude();
                    $i++;
                }
                $tab_return['success'] = 1  ;
                $tab_return['data'] = $doctorArray ;
            }
            return new JsonResponse($tab_return);
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

    public function profile_completeDoctorAction(Request $request, $id)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $doctor = $em->getRepository('DoctorsAdminBundle:Doctor')->findOneBy(
                array(
                    'id' => $id,
                )
            );
            if (!empty($doctor)) {
                $doctor->setIsCompleted(1);
                $phoneNumberDoctor = $request->get('phoneNumberDoctor');
                if (isset($phoneNumberDoctor)) {
                    $phoneNumberDoctor = $request->request->get('phoneNumberDoctor');
                    $doctor->setPhoneNumberDoctor($phoneNumberDoctor);
                }
                $passwordDoctor = $request->request->get('passwordDoctor');
                if (isset($passwordDoctor)) {
                    $doctor->setPasswordDoctor($passwordDoctor);
                    $doctor->setPasswordDoctor($passwordDoctor);
                }
                $nationality = $request->request->get('nationality');
                if (isset($nationality)) {
                    $doctor->setNationality($nationality);
                }
                $nameDoctor = $request->get('nameDoctor');
                if (isset($nameDoctor)) {
                    $doctor->setNameDoctor($request->get('nameDoctor'));
                }
                $countryDoctor = $request->request->get('countryDoctor');
                if (isset($countryDoctor)) {
                    $doctor->setCountryDoctor($countryDoctor);
                }
                $townDoctor = $request->request->get('townDoctor');
                if (isset($townDoctor)) {
                    $doctor->setTownDoctor($townDoctor);
                }
                $cityDoctor = $request->request->get('cityDoctor');
                if (isset($cityDoctor)) {
                    $doctor->setCityDoctor($cityDoctor);
                }
                $photoDoctor = $request->files->get('photoDoctor');
                if (isset($photoDoctor)) {
                    $fileName = md5(uniqid()) . '.' . $photoDoctor->guessExtension();

                    $doctor->setPhotoDoctor($fileName);

                    $photoDoctor->move(
                        $this->container->getParameter('photoDoctor_directory'), $fileName
                    );
                }
                $emailDoctor = $request->request->get('emailDoctor');
                if (isset($emailDoctor)) {
                    $doctor->setEmailDoctor($emailDoctor);
                }
                $workplace = $request->request->get('workplace');
                if (isset($workplace)) {
                    $doctor->setWorkplace($workplace);
                }
                $workplaceName = $request->request->get('workplaceName');
                if (isset($workplaceName)) {
                    $doctor->setWorkplaceName($workplaceName);
                }
                $startTimeWork = $request->request->get('startTimeWork');
                if (isset($startTimeWork)) {
                    $startTimeWork = new \DateTime($startTimeWork);
                    $doctor->setStartTimeWork($startTimeWork);
                }
                $endTimeWork = $request->request->get('endTimeWork');
                if (isset($endTimeWork)) {
                    $endTimeWork = new \DateTime($endTimeWork);
                    $doctor->setEndTimeWork($endTimeWork);
                }
                $longitude = $request->request->get('longitude');
                if (isset($longitude)) {
                    $doctor->setLongitude($longitude);
                }
                $lattitude = $request->request->get('lattitude');
                if (isset($lattitude)) {
                    $doctor->setLattitude($lattitude);
                }
                $speciality_id = $request->request->get('speciality_id');
                if (isset($speciality)) {
                    $speciality = $em->getRepository('DoctorsAdminBundle:Speciality')->find($speciality_id);
                    $doctor->setSpeciality($speciality);
                }
                $em->merge($doctor);
                $em->flush();
                $result = array(
                    'success' => 1,
                    'id' => $doctor->getId()
                );
                return new JsonResponse($result);

            } else {

                $result = array(
                    'success' => 0,
                    'exist' => 1
                );
                return new JsonResponse($result);

            }
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }


    public function api_list_doctor_by_countryAction(Request $request, $country)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $doctors = $em->getRepository('DoctorsAdminBundle:Doctor')->findBy(
                array(
                    'countryDoctor'=>$country,
                    'isActiveDoctor'=>1
                ),
                array(
                    'id'=>'desc'
                )
            );
            $i = 0;
            $doctorArray = array();
            foreach ($doctors as $doctor) {
                $doctorArray[$i]["doctorId"] = $doctor->getId();
                $doctorArray[$i]["nameDoctor"] = $doctor->getNameDoctor();
                $doctorArray[$i]["phoneNumberDoctor"] = $doctor->getPhoneNumberDoctor();
                $doctorArray[$i]['workplaceName'] = $doctor->getWorkplaceName();
                $doctorArray[$i]['countryDoctor'] = $doctor->getCountryDoctor();
                $doctorArray[$i]['townDoctor'] = $doctor->getTownDoctor();
                $doctorArray[$i]['cityDoctor'] = $doctor->getCityDoctor();
                $doctorArray[$i]['speciality'] = $doctor->getSpeciality()->getNameSpeciality();
                $doctorArray[$i]['longitude'] = $doctor->getLongitude();
                $doctorArray[$i]['lattitude'] = $doctor->getLattitude();
                $i++;
            }
            return new JsonResponse($doctorArray);
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

    public function api_list_doctor_by_country_townAction(Request $request, $country, $town)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $doctors = $em->getRepository('DoctorsAdminBundle:Doctor')->findBy(
                array(
                    'countryDoctor'=>$country,
                    'townDoctor'=>$town,
                    'isActiveDoctor'=>1
                ),
                array(
                    'id'=>'desc'
                )
            );
            $i = 0;
            $doctorArray = array();
            foreach ($doctors as $doctor) {
                $doctorArray[$i]["doctorId"] = $doctor->getId();
                $doctorArray[$i]["nameDoctor"] = $doctor->getNameDoctor();
                $doctorArray[$i]["phoneNumberDoctor"] = $doctor->getPhoneNumberDoctor();
                $doctorArray[$i]['workplaceName'] = $doctor->getWorkplaceName();
                $doctorArray[$i]['countryDoctor'] = $doctor->getCountryDoctor();
                $doctorArray[$i]['townDoctor'] = $doctor->getTownDoctor();
                $doctorArray[$i]['cityDoctor'] = $doctor->getCityDoctor();
                $doctorArray[$i]['speciality'] = $doctor->getSpeciality()->getNameSpeciality();
                $doctorArray[$i]['longitude'] = $doctor->getLongitude();
                $doctorArray[$i]['lattitude'] = $doctor->getLattitude();
                $i++;
            }
            return new JsonResponse($doctorArray);
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

    public function addDoctorPictureAction(Request $request, $id)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $doctor = $em->getRepository('DoctorsAdminBundle:Doctor')->find($id);
            if(!empty($doctor))
            {
                $photo = $request->request->get('photo');
                str_replace(' ', '+', $photo);
                $data = base64_decode($photo);
                $name = uniqid() . '.png';
                $piiic = $this->container->getParameter('photoDoctor_directory').'/'.$name;
                $success = file_put_contents($piiic, $data);
                if ($success) {
                    $doctor->setPhotoDoctor($name);
                    $em->merge($doctor);
                    $em->flush();
                    return new JsonResponse(array('success'=>1, 'name'=>$name));
                }else{
                    return new JsonResponse(array('success'=>0, 'error'=>1));
                }
            }else{
                return new JsonResponse(array('success'=>0, 'exist'=>1));
            }
        }else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

    public function updateDoctorPictureAction(Request $request, $id)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            //$user_id = $request->request->get('id');
            $doctor = $em->getRepository('DoctorsAdminBundle:Doctor')->find($id);
            if(!empty($doctor))
            {
                $photo = $request->request->get('photo');
                str_replace(' ', '+', $photo);
                $data = base64_decode($photo);
                $name = uniqid() . '.png';
                $piiic = $this->container->getParameter('photoDoctor_directory').'/'.$name;
                $success = file_put_contents($piiic, $data);
                if ($success) {
                    unlink($this->container->getParameter('photoDoctor_directory') . '/' . $doctor->getPhotoDoctor());
                    $doctor->setPhotoDoctor($name);
                    $em->merge($doctor);
                    $em->flush();
                    return new JsonResponse(array('success'=>1, 'name' => $name));
                }
                else{
                    return new JsonResponse(array('success'=>0, 'error'=>1));
                }
            }else{
                return new JsonResponse(array('success'=>0, 'exist'=>1));
            }
        }else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

    public function api_list_doctor_by_country_town_cityAction(Request $request, $country, $town, $city)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $doctors = $em->getRepository('DoctorsAdminBundle:Doctor')->findBy(
                array(
                    'countryDoctor'=>$country,
                    'townDoctor'=>$town,
                    'cityDoctor'=>$city,
                    'isActiveDoctor'=>1
                ),
                array(
                    'id'=>'desc'
                )
            );
            $i = 0;
            $doctorArray = array();
            foreach ($doctors as $doctor) {
                $doctorArray[$i]["doctorId"] = $doctor->getId();
                $doctorArray[$i]["nameDoctor"] = $doctor->getNameDoctor();
                $doctorArray[$i]["phoneNumberDoctor"] = $doctor->getPhoneNumberDoctor();
                $doctorArray[$i]['workplaceName'] = $doctor->getWorkplaceName();
                $doctorArray[$i]['countryDoctor'] = $doctor->getCountryDoctor();
                $doctorArray[$i]['townDoctor'] = $doctor->getTownDoctor();
                $doctorArray[$i]['cityDoctor'] = $doctor->getCityDoctor();
                $doctorArray[$i]['speciality'] = $doctor->getSpeciality()->getId();
                $doctorArray[$i]['speciality'] = $doctor->getSpeciality()->getNameSpeciality();
                $doctorArray[$i]['longitude'] = $doctor->getLongitude();
                $doctorArray[$i]['lattitude'] = $doctor->getLattitude();
                $i++;
            }
            return new JsonResponse($doctorArray);
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

    public function api_resetAction(Request $request)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $phoneNumber = $request->get('phoneNumber');
            $em = $this->getDoctrine()->getManager();
            $doctor = $em->getRepository('DoctorsAdminBundle:Doctor')->findOneBy(
                array(
                    'phoneNumberDoctor' => $phoneNumber,
                )
            );
            $user = $em->getRepository('DoctorsAdminBundle:User')->findOneBy(
                array(
                    'phoneNumber' => $phoneNumber,
                )
            );
            if (!empty($doctor)) {
                $passwordDoctor = $doctor->getPasswordDoctor();
                $msg = urlencode("كلمة سر حسابك في تطبيق الأطباء هي    " . $passwordDoctor);
                $phone = $doctor->getPhoneNumberDoctor();
                //SMS
                $result = array(
                    'success' => 1,
                    'id' => $doctor->getId()
                );
            } elseif (!empty($user)) {
                $password = $user->getPassword();
                $msg = urlencode("كلمة سر حسابك في تطبيق الأطباء هي    " . $password);
                $phone = $user->getPhoneNumber();
                //SMS
                $result = array(
                    'success' => 1,
                    'id' => $user->getId()
                );
            }elseif (empty($doctor) AND empty($user)) {
                $result = array(
                    'success' => 0,
                );
            }
            return new  JsonResponse($result);
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

    public function api_loginAction(Request $request) {
        $key = $request->headers->get('key');

        $phoneNumber = $request->get('phoneNumber');
        $password = $request->get('password');

        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $doctor = $em->getRepository('DoctorsAdminBundle:Doctor')->findOneBy(
                array(
                    'phoneNumberDoctor' => $phoneNumber,
                    'passwordDoctor' => $password,
                    'isActiveDoctor' => 1
                )
            );
            $user = $em->getRepository('DoctorsAdminBundle:User')->findOneBy(
                array(
                    'phoneNumber' => $phoneNumber,
                    'password' => $password,
                    'isActive' => 1
                )
            );
            $verified =0;
            $completed=0;
            if(!empty($doctor))
            {
                if($doctor->getIsCompleted() == false){
                    $completed = 0;
                }else{
                    $completed = 1;
                }
                $result = array(
                    'login'=>1,
                    'isDoctor'=>1,
                    'isUser'=>0,
                    'doctor_id'=>$doctor->getId(),
                    'completed'=>$completed
                );
            }
            elseif (!empty($user))
            {
                if($user->getVerified() == false){
                    $verified = 0;
                }else{
                    $verified = 1;
                }
                $result = array(
                    'login'=>1,
                    'isDoctor'=>0,
                    'isUser'=>1,
                    'user_id'=>$user->getId(),
                    'verified'=>$verified
                );
            }
            elseif (empty($doctor) AND empty($user)) {
                $result = array(
                    'login' => 0,
                    'isDoctor'=> 0,
                    'isUser'=>0
                );
            }
            return new JsonResponse($result);
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

    public function showDoctorProfileAction(Request $request, $id)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $doctor = $em->getRepository('DoctorsAdminBundle:Doctor')->find($id);
            $i = 0;
            $doctorArray = array();
            $tab_return = array();
            if (empty($doctor)) {
                $tab_return['success'] = 0;
                $tab_return['data'] = array();
            }
            if (!empty($doctor)) {
                $doctorArray["doctorId"] = $doctor->getId();
                $doctorArray["nameDoctor"] = $doctor->getNameDoctor();
                $doctorArray["phoneNumberDoctor"] = $doctor->getPhoneNumberDoctor();
                $doctorArray["nationality"] = $doctor->getNationality();
                $doctorArray['countryDoctor'] = $doctor->getCountryDoctor();
                $doctorArray['townDoctor'] = $doctor->getTownDoctor();
                $doctorArray['cityDoctor'] = $doctor->getCityDoctor();
                $doctorArray['workplace'] = $doctor->getWorkplace();
                $doctorArray['workplaceName'] = $doctor->getWorkplaceName();
                $doctorArray['startTimeWork'] = $doctor->getStartTimeWork()->format('H:i');
                $doctorArray['endTimeWork'] = $doctor->getEndTimeWork()->format('H:i');
                $doctorArray['longitude'] = $doctor->getLongitude();
                $doctorArray['lattitude'] = $doctor->getLattitude();
                $doctorArray['idSpeciality'] = $doctor->getSpeciality()->getId();
                $doctorArray['speciality'] = $doctor->getSpeciality()->getNameSpeciality();
                $doctorArray['photoDoctor'] = '/uploads/doctors/' . $doctor->getPhotoDoctor();
                $doctorArray['emailDoctor'] = $doctor->getEmailDoctor();
                if ($doctor->getIsCompleted() == false) {
                    $doctorArray['completed'] = 0;
                } else {
                    $doctorArray['completed'] = 1;
                }

                $tab_return['success'] = 1;
                $tab_return['data'] = $doctorArray;
            }
            return new JsonResponse($tab_return);
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }

    }

    public function searchDoctorAction(Request $request)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $i = 0;
            //$StaffsInfos = array();
            $tab_where['isActiveDoctor'] = 1;
            $tab_where['isCompleted'] = 1;
            $countryDoctor = $request->get('countryDoctor');
            if (isset($countryDoctor) && $countryDoctor != "") {
                $tab_where['countryDoctor'] = $countryDoctor;
            }
            $townDoctor = $request->request->get('townDoctor');
            if(isset($townDoctor) && $townDoctor != ""){
                $tab_where['townDoctor'] = $townDoctor;
            }
            $cityDoctor = $request->request->get('cityDoctor');
            if(isset($cityDoctor) && $cityDoctor !=""){
                $tab_where['cityDoctor'] = $cityDoctor;
            }
            $speciality_id = $request->request->get('speciality_id');
            if(isset($speciality_id) && $speciality_id != ""){
                $speciality = $em->getRepository('DoctorsAdminBundle:Speciality')->find($speciality_id);
                $tab_where['speciality'] = $speciality;
            }
            $doctors = $em->getRepository('DoctorsAdminBundle:Doctor')->findBy(
                $tab_where
            );
            $coordinatesArray = array() ;
            foreach ($doctors as $doctor) {
                $coordinatesArray[$i]['doctorId'] = $doctor->getId();
                $coordinatesArray[$i]['nameDoctor'] = $doctor->getNameDoctor();
                $coordinatesArray[$i]['photoDoctor'] = '/uploads/doctors/' . $doctor->getPhotoDoctor();
                $coordinatesArray[$i]['countryDoctor'] = $doctor->getCountryDoctor();
                $coordinatesArray[$i]['townDoctor'] = $doctor->getTownDoctor();
                $coordinatesArray[$i]['workplaceDoctor'] = $doctor->getWorkplace();
                $coordinatesArray[$i]['workplaceNameDoctor'] = $doctor->getWorkplaceName();
                $coordinatesArray[$i]['idSpeciality'] = $doctor->getSpeciality()->getId();
                $coordinatesArray[$i]['speciality'] = $doctor->getSpeciality()->getNameSpeciality();
                $coordinatesArray[$i]['longitude'] = $doctor->getLongitude();
                $coordinatesArray[$i]['lattitude'] = $doctor->getLattitude();
                $i++;
            }
            return new JsonResponse($coordinatesArray);
        }
    }


    public function calculDistanceAction(Request $request)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $currentLongitudeUser = $request->request->get('longitude');
            $currentAttitudeUser = $request->request->get('lattitude');
            $doctors = $em->getRepository('DoctorsAdminBundle:Doctor')->findBy(
                array('isActiveDoctor'=>1, 'isCompleted'=>1)
            );
            $i = 0;
            //$StaffsInfos = array();
            $theta = array();
            $dist = array();
            $kilo = array();
            $coordinatesArray = array();
            foreach ($doctors as $doctor) {
                $coordinatesArray[$i]['id'] = $doctor->getId();
                $coordinatesArray[$i]['name'] = $doctor->getNameDoctor();
                $coordinatesArray[$i]['photoDoctor'] = '/uploads/doctors/' . $doctor->getPhotoDoctor();
                $coordinatesArray[$i]['countryDoctor'] = $doctor->getCountryDoctor();
                $coordinatesArray[$i]['townDoctor'] = $doctor->getTownDoctor();
                $coordinatesArray[$i]['workplaceDoctor'] = $doctor->getWorkplace();
                $coordinatesArray[$i]['workplaceNameDoctor'] = $doctor->getWorkplaceName();
                $coordinatesArray[$i]['speciality'] = $doctor->getSpeciality()->getNameSpeciality();
                $coordinatesArray[$i]['longitude'] = $doctor->getLongitude();
                $coordinatesArray[$i]['lattitude'] = $doctor->getLattitude();
                $theta[$i] = $currentLongitudeUser - $coordinatesArray[$i]['longitude'];
                $dist[$i] = sin(deg2rad($currentAttitudeUser)) * sin(deg2rad(trim($coordinatesArray[$i]['lattitude']))) + cos(deg2rad($currentAttitudeUser)) * cos(deg2rad(trim($coordinatesArray[$i]['lattitude']))) * cos(deg2rad($theta[$i]));
                $dist[$i] = acos($dist[$i]);
                $dist[$i] = rad2deg($dist[$i]);
                $kilo[$i] = $dist[$i] * 60 * 1.1515 * 1.609344;
                $coordinatesArray[$i]["distance"] = $kilo[$i];
                $i++;
            }
            usort($coordinatesArray, function ($a, $b) {
                return $a['distance'] - $b['distance'];
            });
            return new JsonResponse($coordinatesArray);
        }
    }

    public function api_edit_doctorAction(Request $request, $id)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $doctor = $em->getRepository('DoctorsAdminBundle:Doctor')->findOneBy(
                array(
                    'id' => $id,
                )
            );
            if (!empty($doctor)) {
                $phoneNumberDoctor = $request->get('phoneNumberDoctor');
                if (isset($phoneNumberDoctor)) {
                    $phoneNumberDoctor = $request->request->get('phoneNumberDoctor');
                    $doctor->setPhoneNumberDoctor($phoneNumberDoctor);
                }
                $passwordDoctor = $request->request->get('passwordDoctor');
                if (isset($passwordDoctor)) {
                    $doctor->setPasswordDoctor($passwordDoctor);
                    $doctor->setPasswordDoctor($passwordDoctor);
                }
                $nationality = $request->request->get('nationality');
                if (isset($nationality)) {
                    $doctor->setNationality($nationality);
                }
                $nameDoctor = $request->get('nameDoctor');
                if (isset($nameDoctor)) {
                    $doctor->setNameDoctor($request->get('nameDoctor'));
                }
                $countryDoctor = $request->request->get('countryDoctor');
                if (isset($countryDoctor)) {
                    $doctor->setCountryDoctor($countryDoctor);
                }
                $townDoctor = $request->request->get('townDoctor');
                if (isset($townDoctor)) {
                    $doctor->setTownDoctor($townDoctor);
                }
                $cityDoctor = $request->request->get('cityDoctor');
                if (isset($cityDoctor)) {
                    $doctor->setCityDoctor($cityDoctor);
                }
                $photoDoctor = $request->files->get('photoDoctor');
                if (isset($photoDoctor)) {
                    $fileName = md5(uniqid()) . '.' . $photoDoctor->guessExtension();

                    $doctor->setPhotoDoctor($fileName);

                    $photoDoctor->move(
                        $this->container->getParameter('photoDoctor_directory'), $fileName
                    );
                }
                $emailDoctor = $request->request->get('emailDoctor');
                if (isset($emailDoctor)) {
                    $doctor->setEmailDoctor($emailDoctor);
                }
                $workplace = $request->request->get('workplace');
                if (isset($workplace)) {
                    $doctor->setWorkplace($workplace);
                }
                $workplaceName = $request->request->get('workplaceName');
                if (isset($workplaceName)) {
                    $doctor->setWorkplaceName($workplaceName);
                }
                $startTimeWork = $request->request->get('startTimeWork');
                if (isset($startTimeWork)) {
                    $startTimeWork = new \DateTime($startTimeWork);
                    $doctor->setStartTimeWork($startTimeWork);
                }
                $endTimeWork = $request->request->get('endTimeWork');
                if (isset($endTimeWork)) {
                    $endTimeWork = new \DateTime($endTimeWork);
                    $doctor->setEndTimeWork($endTimeWork);
                }
                $longitude = $request->request->get('longitude');
                if (isset($longitude)) {
                    $doctor->setLongitude($longitude);
                }
                $lattitude = $request->request->get('lattitude');
                if (isset($lattitude)) {
                    $doctor->setLattitude($lattitude);
                }
                $speciality_id = $request->request->get('speciality_id');
                if (isset($speciality)) {
                    $speciality = $em->getRepository('DoctorsAdminBundle:Speciality')->find($speciality_id);
                    $doctor->setSpeciality($speciality);
                }
                $em->merge($doctor);
                $em->flush();
                $result = array(
                    'success' => 1,
                    'id' => $doctor->getId()
                );
                return new JsonResponse($result);

            } else {

                $result = array(
                    'success' => 0,
                    'exist' => 1
                );
                return new JsonResponse($result);

            }
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

    public function api_delete_doctorAction(Request $request, $doctor_id)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $doctor = $em->getRepository('DoctorsAdminBundle:Doctor')->find($doctor_id);
            if (!empty($doctor)) {
                $doctor->setIsActiveDoctor(0);
                $em->merge($doctor);
                $em->flush();
                $result = array(
                    'success' => 1,
                    'id' => $doctor_id
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

    public function api_add_token_doctorAction(Request $request)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $doctor_id = $request->request->get('doctor_id');
            $doctor = $em->getRepository('DoctorsAdminBundle:Doctor')->find($doctor_id);
            if(isset($doctor))
            {
                $token = $request->request->get('token');
                $tokenDoctor = new TokenDoctor();
                $tokenDoctor->setDoctor($doctor);
                $tokenDoctor->setTokenDoctor($token);
                $em->persist($tokenDoctor);
                $em->flush();
                $resultat = array(
                    'success'=>1,
                    'token_id'=>$tokenDoctor->getId()
                );
            }
            else{
                $resultat = array(
                    'success'=>0,
                    'exist'=>1
                );
            }
            return new JsonResponse($resultat);
        }else {
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