<?php
namespace Kamran\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use Kamran\UtilBundle\Base\Util;
use Kamran\UserBundle\Helper\EmailTemplates;

use Kamran\UserBundle\Form\Type\ForgetPasswordType;
use Kamran\UserBundle\Form\Type\ChangePasswordType;
use Kamran\UserBundle\Form\Type\ChangeProfileType;
use Kamran\UserBundle\Form\Type\RegisterType;
use Kamran\UserBundle\Entity\User;

/**
 * Account controller
*/
class AccountController extends Controller{

    /**
     * @Route("/register", name="account_register")
     * @Template()
     */
    public function registerAction(Request $request){
        //$user = $this->get('security.context')->getToken()->getUser();
        $basePath = $request->getScheme().'://'.$request->getHost().$request->getBaseUrl();


        $user = new User();
        $form = $this->createForm(new RegisterType(), $user );

        if ($request->getMethod() === 'POST'){
            $form->handleRequest($request);

            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $rawPassword = $user->getPassword();
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);

                $password = $encoder->encodePassword( $user->getPassword(), $user->getSalt());
                $user->setPassword($password);
                $user->setIsActive(0);

                //assign default user role
                $role = $em->getRepository("Kamran\UserBundle\Entity\Role")->findOneBy(array("name"=>'ROLE_USER'));
                if(!$role){
                    throw new \Exception("Role " . $options['role'] . " not found.");
                }
                $user->addRole($role);


                $em->persist($user);
                $em->flush();

                $message = \Swift_Message::newInstance()
                    ->setSubject('Set Message Subject')
                    ->setFrom('From_Email')
                    ->setTo($user->getEmail())
                    ->setBody( EmailTemplates::signupEmail( $user->getUsername() , $user->getEmail() , $rawPassword , $basePath.'/email-confirm/'.$user->getEmail() ) , 'text/html')
                ;
                $this->get('mailer')->send($message);

                return $this->redirect($this->generateUrl('account_register_complete'));
                // send email
            }

        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/register/complete", name="account_register_complete")
     * @Template()
     */
    public function registerCompleteAction(Request $request){

    }


    /**
     * @Route("/email-confirm/{link}", name="account_register_confirm")
     * @Template()
     */
    public function emailConformAction(Request $request, $link ){


        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("Kamran\UserBundle\Entity\User")->findOneBy(array("email"=>$link));

        if($user){
            $user->setIsActive(1);
            $user->setIsConfirmed(1);

            $em->persist($user);
            $em->flush();

        }else{
            return $this->redirect($this->generateUrl('login'));
        }

    }

    /**
     * @Route("/forgetpassword", name="account_forgetpassword")
     * @Template()
     */
    public function forgetpasswordAction(Request $request){

        $basePath = $request->getScheme().'://'.$request->getHost().$request->getBaseUrl();
        $form = $this->createForm(new ForgetPasswordType());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            if(array_key_exists('username',$data)){

                $em = $this->getDoctrine()->getManager();
                $user = $em->getRepository("Kamran\UserBundle\Entity\User")->findOneBy(array("username"=>$data['username']));

                if($user){
                    $factory = $this->get('security.encoder_factory');
                    $encoder = $factory->getEncoder($user);

                    $newPassword = Util::randString(10);

                    $password = $encoder->encodePassword( $newPassword, $user->getSalt());
                    $user->setPassword($password);

                    $em->persist($user);
                    $em->flush();

                    $message = \Swift_Message::newInstance()
                        ->setSubject('Reset Password')
                        ->setFrom('From_Email')
                        ->setTo($user->getEmail())
                        ->setBody( EmailTemplates::resetpasswordEmail( $user->getUsername() , $newPassword , $basePath ), 'text/html' )
                    ;
                    $this->get('mailer')->send($message);

                    return $this->redirect($this->generateUrl('login'));
                }

            }else{
                echo 'No username';
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/changepassword", name="account_changepassword")
     * @Template()
     */
    public function changepasswordAction(Request $request){
        //
        $form = $this->createForm(new ChangePasswordType());
        $form->handleRequest($request);
        $message = '';

        /*if ($this->get('security.context')->isGranted('ROLE_USER')) {
            echo 'Yes , you have access';
        }*/

        if ($form->isValid()) {

            $userToken = $this->get('security.context')->getToken()->getUser();
            $data = $form->getData();
            $currentPassword = $data['curr_password'];
            $newPassword  = $data['password'];

            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($userToken);

            $response = $encoder->isPasswordValid(
                $userToken->getPassword(),
                $currentPassword ,
                $userToken->getSalt()
            );

            if($response){
                $em = $this->getDoctrine()->getManager();
                $user = $em->getRepository("Kamran\UserBundle\Entity\User")->findOneBy(array("id"=>$userToken->getId() , 'email'=>$userToken->getEmail()));
                $user->setPassword($newPassword);
                $encoder = $factory->getEncoder($user);
                $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $user->setPassword($password);
                $em->persist($user);
                $em->flush();
                echo('Password Changed sucessfully');
            }
        }else{
            $errors = $form->getErrors();
            foreach($errors as $error) {
                $message .= '- ' . $error->getMessageTemplate();
            }
        }

        return array(
            'form' => $form->createView(),
            'error' => $message
        );
    }

    /**
     * @Route("/changeprofile", name="account_changeprofile")
     * @Template()
     */
    public function changeprofileAction(Request $request){

        $identity = $this->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("Kamran\UserBundle\Entity\User")->findOneBy(array("id"=>$identity->getId()));


        $form = $this->createForm(new ChangeProfileType(), $user);
        $message = '';

        if ($request->getMethod() === 'POST'){
            $form->handleRequest($request);
            if ($form->isValid()) {
                $oldAvatar = $user->getAvatar();
                $user->uploadAvatarFile('edit', $oldAvatar );

                $em->persist($user);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Your changes saved!');

            }else{
                $errors = $form->getErrors();
                foreach($errors as $error) {
                    $message .= '- ' . $error->getMessageTemplate();
                }
            }
        }

        return array(
            'form' => $form->createView(),
            'user' => $user ,
            'error' => $message
        );
    }


}//@
