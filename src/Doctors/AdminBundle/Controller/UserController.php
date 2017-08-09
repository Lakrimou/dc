<?php

namespace Doctors\AdminBundle\Controller;


use Doctors\AdminBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    //List of users
    public function usersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('DoctorsAdminBundle:User')->findBy(array(), array('id' => 'desc'));
        return $this->render('DoctorsAdminBundle:User:users.html.twig', array('users'=>$users));
    }

    // show user informations
    public function showUserAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $specificUser = $em->getRepository('DoctorsAdminBundle:User')->find($user->getId());
        return $this->render('DoctorsAdminBundle:User:showUser.html.twig', array('user'=>$specificUser));
    }

    //Add user
    public function addUserAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('Doctors\AdminBundle\Form\UserType', $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user->setIsActive(1);
            $random = rand(3000, 9999);
            $user->setCode($random);
            $user->setVerified(1);
            $file = $user->getPhoto();
            if(!empty($file))
            {
                // Generate a unique name for the file before saving it
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move(
                    $this->getParameter('photoUser_directory'),
                    $fileName
                );
            }else{
                $fileName = 'avatar.jpg';
            }
            $user->setPhoto($fileName);
            $user->setCreatedAt(new \Datetime());
            $em->persist($user);
            $em->flush();
            $session = $request->getSession();
            $session->getFlashBag()->add('msg', 'تمت اضافة المستخدم بنجاح');
            return $this->redirectToRoute('doctors_admin_users');
        }
        return $this->render('DoctorsAdminBundle:User:ajouterUser.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    //Update user
    public function editUserAction(Request $request, User $user)
    {
        $editPictureForm = $this->createForm('Doctors\AdminBundle\Form\EditPictureUserType', $user);
        $editForm = $this->createForm('Doctors\AdminBundle\Form\EditUserType', $user);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted()) {
            $file = $user->getPhoto();
            if($file instanceof UploadedFile)
            {
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('photoUser_directory'),$fileName);
                $user->setPhoto($fileName);
            }
            $this->getDoctrine()->getManager()->flush();
            if ($request->isXmlHttpRequest()) {

                return new JsonResponse(array('message' => 'Success!','success' => true), 200);

            }

            $session = $request->getSession();
            $session->getFlashBag()->add('msg', 'تم تعديل المستخدم  بنجاح');
            return $this->redirectToRoute('doctors_admin_users');
        }

        return $this->render('DoctorsAdminBundle:User:edit.html.twig', array(
            'user' =>$user,
            'edit_form' =>$editForm->createView(),
            'editPicForm'=>$editPictureForm->createView()
        ));
    }





    //update photo user
    public function editPhotoUserAction(Request $request, User $user)
    {

        $editPictureForm = $this->createForm('Doctors\AdminBundle\Form\EditPictureUserType', $user);
        $editForm = $this->createForm('Doctors\AdminBundle\Form\EditUserType', $user);
        $editPictureForm->handleRequest($request);
        if ($editPictureForm->isSubmitted()) {
            $file = $user->getPhoto();
            $user->setPhoto($file); // Store the current value from the DB before overwriting below
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('photoUser_directory'),
                $fileName
            );
            $user->setPhoto($fileName);
            $this->getDoctrine()->getManager()->flush();
            if ($request->isXmlHttpRequest()) {

                return new JsonResponse(array('message' => 'Success!','success' => true, 'photo'=>$user->getPhoto()), 200);

            }
            $session = $request->getSession();
            $session->getFlashBag()->add('msg', 'تم تعديل معلومات المستخدم  بنجاح');
            return $this->redirectToRoute('doctors_admin_users');
        }
        /*var_dump($editForm->getErrors());*/
        return $this->render('DoctorsAdminBundle:User:edit.html.twig', array(
            'user' =>$user,
            'edit_form' =>$editForm->createView(),
            'editPicForm'=>$editPictureForm->createView(),
        ));

    }














    //delete user
    public function deleteUserAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('DoctorsAdminBundle:User')->find($id);
        $user->setIsActive(0);
        $em->merge($user);
        $em->flush();
        $session = $request->getSession();
        $session->getFlashBag()->add('msg', 'تم حذف المستخدم بنجاح');
        return $this->redirectToRoute('doctors_admin_users');
    }
}