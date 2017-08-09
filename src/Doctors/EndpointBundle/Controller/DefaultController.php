<?php

namespace Doctors\EndpointBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DoctorsEndpointBundle:Default:index.html.twig');
    }

    public function contactAction(Request $request)
    {

        $name = $request->request->get('name');
        $mail = $request->request->get('mail');
        $objet = $request->request->get('objet');
        $message = $request->request->get('message');

        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 587, "tls")
            ->setUsername('soulution.hal12345@gmail.com')
            ->setPassword(soulution123456);

        $mailer = \Swift_Mailer::newInstance($transport);
        $message = \Swift_Message::newInstance($objet)
            ->setFrom('bohliaymen1000@gmail.com')
            ->setTo(['soulution.hal12345@gmail.com' => 'Receiver Name'])
            ->setBody($name. "\n\n" .$mail. "\n\n" . $message);



        if ($this->get('mailer')->send($message))
        {
            $session = $request->getSession();
            $session->getFlashBag()->add('msg', 'تم ارسال رسالتك بنجاح');
            return $this->redirectToRoute('homepage');
        }
        else
        {
            $session = $request->getSession();
            $session->getFlashBag()->add('msg', 'لم يتم إرسال رسالتك');
            return $this->redirectToRoute('homepage');

        }

    }


}
