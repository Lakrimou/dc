<?php

namespace Doctors\AdminBundle\Controller;

use Doctors\AdminBundle\Entity\Email;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;



class EmailController extends Controller
{
    public function addEmailAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $emailValue =$request->request->get('email');
        $email = new Email();
        $email->setEmail($emailValue);
        $em->persist($email);
        $em->flush();
        $result = array(
            'success' => 1
        );
        return new JsonResponse($result);
    }
}
