<?php

namespace Doctors\EndpointBundle\Controller;

use Doctors\AdminBundle\Entity\TokenUser;
use Doctors\AdminBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


class UserApiController extends Controller

{
    public function api_addAction(Request $request)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            // ...........
            $result = array(
                'success' => 1
            );
            return new JsonResponse($result);
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

    public function api_user_listAction(Request $request)
    {


        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $users = $em->getRepository('DoctorsAdminBundle:User')->findBy(
                array('isActive' => '1'),
                array('id' => 'desc')
            );
            $i = 0;
            $usersArray = array();
            foreach ($users as $user) {
                $usersArray[$i]["userId"] = $user->getId();
                $usersArray[$i]["name"] = $user->getName();
                $usersArray[$i]["phoneNumber"] = $user->getPhoneNumber();
                $i++;
            }
            return new JsonResponse($usersArray);
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }


    public function api_register_userAction(Request $request)
    {
        $key = $request->headers->get('key');
        $phoneNumber = $request->request->get('phoneNumber');
        $password = $request->request->get('password');
        $token = $request->request->get('token');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('DoctorsAdminBundle:User')->findOneBy(
                array(
                    'phoneNumber' => $phoneNumber
                )
            );
            $doctor = $em->getRepository('DoctorsAdminBundle:Doctor')->findOneBy(
                array(
                    'phoneNumberDoctor' => $phoneNumber,
                )
            );
            if (empty($user) AND empty($doctor)) {
                $user = new User();
                $user->setPhoneNumber($phoneNumber);
                $user->setPassword($password);
                $user->setIsActive(1);
                $user->setVerified(0);
                $random = rand(3000, 9999);
                $user->setCode($random);
                $user->setCreatedAt(new \DateTime());
                $em->persist($user);

                $tokenUser = new TokenUser();
                $tokenUser->setUser($user);
                $tokenUser->setToken($token);
                $em->persist($tokenUser);
                $em->flush();
                //  SMS
                return new JsonResponse(array(
                    'success' => 1,
                    'id' => $user->getId()
                ));
            } else {
                return new JsonResponse(
                    array('success' => 0, 'exist' => 1, 'phoneNumber'=>$phoneNumber, 'token'=>$token, 'password'=>$password)
                );
            }
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }


    public function verifyAction(Request $request, $id)
    {

        $key = $request->headers->get('key');

        $code = $request->get('code');

        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('DoctorsAdminBundle:User')->findOneBy(
                array(
                    'id' => $id,
                )
            );
            if (!empty($user)) {
                if ($user->getCode() == $code) {
                    $user->setVerified(1);
                    $result = array(
                        'verification' => 1,
                        'id' => $id
                    );
                } else {
                    $result = array(
                        'verification' => 0,
                        'id' => $id
                    );
                }
                $em->merge($user);
                $em->flush();
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

    public function addPictureAction(Request $request, $id)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $user_id = $request->request->get('id');
            $user = $em->getRepository('DoctorsAdminBundle:User')->find($id);
            if(!empty($user))
            {
                $photo = $request->request->get('photo');
                str_replace(' ', '+', $photo);
                $data = base64_decode($photo);
                $name = uniqid() . '.png';
                $piiic = $this->container->getParameter('photoUser_directory').'/'.$name;
                $success = file_put_contents($piiic, $data);
                if ($success) {
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

    public function updatePictureAction(Request $request, $id)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $user_id = $request->request->get('id');
            $user = $em->getRepository('DoctorsAdminBundle:User')->find($id);
            if(!empty($user))
            {
                $photo = $request->request->get('photo');
                str_replace(' ', '+', $photo);
                $data = base64_decode($photo);
                $name = uniqid() . '.png';
                $piiic = $this->container->getParameter('photoUser_directory').'/'.$name;
                $success = file_put_contents($piiic, $data);
                if ($success) {
                    unlink($this->container->getParameter('photoUser_directory') . '/' . $user->getPhoto());
                    $user->setPhoto($name);
                    $em->merge($user);
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

    public function profile_completeAction(Request $request, $id)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('DoctorsAdminBundle:User')->findOneBy(
                array(
                    'id' => $id,
                )
            );
            if (!empty($user)) {
                $name = $request->get('name');
                if (isset($name)) {
                    $user->setName($request->get('name'));
                }
                $country = $request->request->get('country');
                if (isset($country)) {
                    $user->setCountry($country);
                }
                $town = $request->request->get('town');
                if (isset($town)) {
                    $user->setTown($town);
                }
                $city = $request->request->get('city');
                if (isset($city)) {
                    $user->setCity($city);
                }
                $photo = $request->files->get('photo');
                if (isset($photo)) {
                    $fileName = md5(uniqid()) . '.' . $photo->guessExtension();

                    $user->setPhoto($fileName);

                    $photo->move(
                        $this->container->getParameter('photoUser_directory'), $fileName
                    );
                } else {
                    $user->setPhoto('avatar.jpg');
                }
                $email = $request->request->get('email');
                if (isset($email)) {
                    $user->setEmail($email);
                }
                $em->merge($user);
                $em->flush();
                $result = array(
                    'success' => 1,
                    'id' => $user->getId()
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

    public function show_profile_userAction(Request $request, $id)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('DoctorsAdminBundle:User')->find($id);
            $i = 0;
            $userArray = array();
            $tab_return = array();
            if (empty($user)) {
                $tab_return['success'] = 0;
                $tab_return['data'] = array();
            }
            if (!empty($user)) {
                $userArray["userId"] = $user->getId();
                $userArray["name"] = $user->getName();
                $userArray["phoneNumber"] = $user->getPhoneNumber();
                $userArray['country'] = $user->getCountry();
                $userArray['town'] = $user->getTown();
                $userArray['city'] = $user->getCity();
                $userArray['photo'] = '/uploads/users/' . $user->getPhoto();
                $userArray['email'] = $user->getEmail();
                if($user->getVerified() == false){
                    $userArray['verified'] = 0;
                }else{
                    $userArray['verified'] = 1;
                }

                $tab_return['success'] = 1;
                $tab_return['data'] = $userArray;
            }
            return new JsonResponse($tab_return);
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

    public function api_edit_userAction(Request $request, $id)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('DoctorsAdminBundle:User')->findOneBy(
                array(
                    'id' => $id,
                )
            );
            if (!empty($user)) {
                $phoneNumber = $request->get('phoneNumber');
                if (isset($phoneNumber)) {
                    $userPhoneNumber = $em->getRepository('DoctorsAdminBundle:User')->findOneBy(
                        array(
                            'phoneNumber' => $phoneNumber,
                        )
                    );
                    if (empty($userPhoneNumber)) {
                        $user->setPhoneNumber($request->get('phoneNumber'));
                    } else {
                        $resultat = array();
                        $resultat['success'] = 0;
                        $resultat['exist_phoneNumber'] = 1;
                        return new JsonResponse($resultat);
                    }
                }
                if (isset($password)) {
                    $user->setPassword($request->get('password'));
                }
                $password = $request->get('password');
                if (isset($password)) {
                    $user->setPassword($request->get('password'));
                }
                $name = $request->get('name');
                if (isset($name)) {
                    $user->setName($request->get('name'));
                }
                $country = $request->request->get('country');
                if (isset($country)) {
                    $user->setCountry($country);
                }
                $town = $request->request->get('town');
                if (isset($town)) {
                    $user->setTown($town);
                }
                $city = $request->request->get('city');
                if (isset($city)) {
                    $user->setCity($city);
                }
                $photo = $request->files->get('photo');
                if (isset($photo)) {
                    $fileName = md5(uniqid()) . '.' . $photo->guessExtension();

                    $user->setPhoto($fileName);

                    $photo->move(
                        $this->container->getParameter('photoUser_directory'), $fileName
                    );
                }
                $email = $request->request->get('email');
                if (isset($email)) {
                    $user->setEmail($email);
                }
                $em->merge($user);
                $em->flush();
                $result = array(
                    'success' => 1,
                    'id' => $user->getId()
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

    public
    function resetAction(Request $request)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $phoneNumber = $request->get('phoneNumber');

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('DoctorsAdminBundle:User')->findOneBy(
                array(
                    'phoneNumber' => $phoneNumber,
                )
            );
            if (!empty($user)) {
                $password = $user->getPassword();

                $msg = urlencode("كلمة سر حسابك في تطبيق الأطباء هي    " . $password);

                $phone = $user->getPhoneNumber();

                //SMS

                $result = array(
                    'success' => 1,
                    'id' => $user->getId()
                );
            } else {
                $result = array(
                    'exist' => 0
                );
            }
            return new  JsonResponse($result);
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

    public
    function loginAction(Request $request)
    {
        $key = $request->headers->get('key');

        $phoneNumber = $request->get('phoneNumber');
        $password = $request->get('password');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('DoctorsAdminBundle:User')->findOneBy(
                array(
                    'phoneNumber' => $phoneNumber,
                    'password' => $password,
                    'isActive' => '1'
                )
            );

            if (empty($user)) {
                $result = array(
                    'login' => 0
                );
            } else {
                $result = array(
                    'login' => 1,
                    'userId' => $user->getId(),
                    'verification' => $user->getVerified()
                );
            }

            return new JsonResponse($result);
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }

    public
    function api_add_token_userAction(Request $request)
    {
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $em = $this->getDoctrine()->getManager();
            $user_id = $request->request->get('user_id');
            $user = $em->getRepository('DoctorsAdminBundle:User')->find($user_id);
            if (isset($user)) {
                $token = $request->request->get('token');
                $tokenUser = new TokenUser();
                $tokenUser->setUser($user);
                $tokenUser->setToken($token);
                $em->persist($tokenUser);
                $em->flush();
                $resultat = array(
                    'success' => 1,
                    'token_id' => $tokenUser->getId()
                );
            } else {
                $resultat = array(
                    'success' => 0,
                    'exist' => 1
                );
            }
            return new JsonResponse($resultat);
        } else {
            $tab = array('auth' => 0);
            return new JsonResponse($tab);
        }
    }
    
    public function api_delete_userAction(Request $request, $user_id)
    {
        $em = $this->getDoctrine()->getManager();
        $key = $request->headers->get('key');
        if ($this->auth_key($key)) {
            $user = $em->getRepository('DoctorsAdminBundle:User')->find($user_id);
            if (!empty($user)) {
                $user->setIsActive(0);
                $em->merge($user);
                $em->flush();
                $result = array(
                    'success' => 1,
                    'id' => $user_id
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