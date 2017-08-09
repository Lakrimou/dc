<?php

namespace Doctors\EndpointBundle\Controller;

use Doctors\AdminBundle\Entity\Speciality;
use Doctors\AdminBundle\Entity\TokenUser;
use Doctors\AdminBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


class SpecialityApiController extends Controller
{
    public function api_list_SpecialityAction(Request $request)
    {
        $key = $request->headers->get('key');
        if($this->auth_key($key))
        {
            $em = $this->getDoctrine()->getManager();
            $speciality = $em->getRepository('DoctorsAdminBundle:Speciality')->findBy(
                array('isActiveSpeciality' => '1'),
                array('id' => 'desc')
            );
            $specialityArray = array();
            $i = 0;
            $tab_return = array();
            if(empty($speciality)){
                $tab_return['success'] = 0  ;
                $tab_return['data'] = array() ;
            }else{
            foreach ($speciality as $spec)
            {
                $specialityArray[$i]["id"] = $spec->getId();
                $specialityArray[$i]["nameSpeciality"] = $spec->getNameSpeciality();
                $i++;
            }
                $tab_return['success'] = 1  ;
                $tab_return['data'] = $specialityArray ;
            }
            return new JsonResponse($tab_return);
        } else {
            $tab = array('auth'=>0);
            return new JsonResponse($tab);
        }
    }

    public function api_register_specialityAction(Request $request)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $specialityName = $request->request->get('specialityName');
            $speciality = $em->getRepository('DoctorsAdminBundle:Speciality')->findOneBy(
                array(
                    'nameSpeciality' => $specialityName
                )
            );

            if (empty($speciality)) {
                $speciality = new Speciality();
                $speciality->setNameSpeciality($specialityName);
                $speciality->setIsActiveSpeciality(1);
                $speciality->setCreatedAtSpeciality(new \DateTime());
                $em->persist($speciality);
                $em->flush();
                //  SMS
                return new JsonResponse(array(
                    'succcess' => 1,
                    'id' => $speciality->getId()
                ));
            } else {
                return new JsonResponse(
                    array('success' => 0, 'exist' => 1)
                );
            }
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }


    public function api_edit_specialityAction(Request $request, $id)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $speciality = $em->getRepository('DoctorsAdminBundle:Speciality')->findOneBy(
                array(
                    'id' => $id,
                )
            );
            if (!empty($speciality)) {
                $nameSpeciality = $request->request->get('nameSpeciality');
                if (isset($nameSpeciality)) {
                    $speciality->setNameSpeciality($request->get('nameSpeciality'));
                }
                $em->merge($speciality);
                $em->flush();
                $result = array(
                    'success' => 1,
                    'id' => $speciality->getId()
                );
                return new JsonResponse($result);

            } else {

                $result = array(
                    'success' => 0,
                    'exist' => 1
                );
                return new JsonResponse($result);

            }
        }else{
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

    public function api_delete_specialityAction(Request $request, $speciality_id)
    {
        $em = $this->getDoctrine()->getManager();
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $speciality = $em->getRepository('DoctorsAdminBundle:Speciality')->find($speciality_id);
            if (!empty($speciality)) {
                $speciality->setIsActiveSpeciality(0);
                $em->merge($speciality);
                $em->flush();
                $result = array(
                    'success' => 1,
                    'id' => $speciality_id
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