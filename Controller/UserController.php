<?php
namespace Kamran\UserBundle\Controller;

use Kamran\ContactsBundle\Entity\Employee;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\FormError;

use Kamran\UtilBundle\Base\Util;
use Kamran\UserBundle\Helper\EmailTemplates;

use Kamran\UserBundle\Entity\User;
use Kamran\UserBundle\Entity\Role;
use Kamran\UserBundle\Entity\RolePermissions;
use Kamran\UserBundle\Form\Type\UserType;
use Kamran\UserBundle\Form\Type\RoleType;

use Kamran\ContactsBundle\Entity\EmployeeContacts;
use Kamran\ContactsBundle\Entity\EmployeeEmail;




/**
 * UserController
 * @Route("/user")
 */
class UserController extends Controller
{

  /**
   * @Route("/", name="user_index")
   * @Template()
   */
  public function indexAction(Request $request){
      return array();
  }


  /**
   * @Route("/add", name="user_add")
   * @Template()
   */
  public function addAction(Request $request){

      $basePath = $request->getScheme().'://'.$request->getHost().$request->getBaseUrl();

      $user = new User();
      $form = $this->createForm(new UserType(), $user);

      if ($request->getMethod() === 'POST'){
          $form->handleRequest($request);

          if ($form->isValid()) {

             $em = $this->getDoctrine()->getManager();
              $rawPassword = $user->getPassword();

             $factory = $this->get('security.encoder_factory');
             $encoder = $factory->getEncoder($user);

             $password = $encoder->encodePassword( $user->getPassword() , $user->getSalt());
             $user->setPassword($password);
             $em->persist($user);
             $em->flush();

             $sendEmail = $form->get('sendmail')->getData();

             if($sendEmail){
                 $message = \Swift_Message::newInstance()
                     ->setSubject('Subject_Text')
                     ->setFrom('From_Email')
                     ->setTo($user->getEmail())
                     ->setBody( EmailTemplates::createUserEmail( $user->getUsername() , $user->getEmail() , $rawPassword , $basePath.'/email-confirm/'.$user->getEmail() ) , 'text/html')
                 ;
                 $this->get('mailer')->send($message);
             }

              $this->get('session')->getFlashBag()->add('notice', 'New user created successfully.');
              return $this->redirect($this->generateUrl('user_index'));
          }
      }

      return array(
          'form' => $form->createView(),
      );
  }


    /**
     * @Route("/edit/{id}", name="user_edit",options={"expose" = true})
     * @Template()
     */
    public function editUserAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository("Kamran\UserBundle\Entity\User")->find($id);
        $form = $this->createForm( new UserType() , $entity );

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }


    /**
     * @Route("/remove/{id}",
     *         name="user_remove",
     *         requirements={"id" = "\d+"})
     */
    public function removeUserAction($id) {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository("Kamran\UserBundle\Entity\User")->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('user with id=' . $id . ' not found');
        }

        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Selected user removed successfully.');
        return $this->redirect($this->generateUrl('user_index'));
    }






    /**
   * @Route("/roles", name="user_roles_index")
   * @Template()
   */
  public function rolesAction(Request $request){

      $em = $this->getDoctrine()->getManager();
      $repository = $em->getRepository("Kamran\UserBundle\Entity\Role");
      $roles = $repository->findAll();

      return array('roles'=>$roles);
  }

  /**
   * @Route("/roles/add", name="user_roles_add")
   * @Template()
   */
  public function addroleAction(Request $request){

      $entity = new Role();
      $form = $this->createForm(new RoleType() , $entity );

      if ($request->getMethod() === 'POST'){
          $form->handleRequest($request);

          if ($form->isValid()) {
              $em = $this->getDoctrine()->getManager();
              $em->persist($entity);
              $em->flush();

              //add permissions tags
              $permision = new RolePermissions();
              $permision->setRoleId($entity->getId());
              $permision->setList('');
              $em->persist($permision);
              $em->flush();

              $this->get('session')->getFlashBag()->add('notice', 'New user role created successfully.');
              return $this->redirect($this->generateUrl('user_roles_index'));
          }
      }

      return array(
          'form' => $form->createView(),
      );
  }


  /**
   * @Route("/roles/edit/{id}", name="user_roles_edit")
   * @Template()
   */
  public function editroleAction(Request $request, $id){

      $em = $this->getDoctrine()->getManager();

      $entity = $em->getRepository("Kamran\UserBundle\Entity\Role")->find($id);

      $form = $this->createForm(new RoleType(), $entity);
      if ($request->getMethod() === 'POST'){
          $form->handleRequest($request);
          if ($form->isValid()) {
              $em = $this->getDoctrine()->getManager();
              $em->persist($entity);
              $em->flush();
              $this->get('session')->getFlashBag()->add('notice', 'Role modifications successfully saved.');
              return $this->redirect($this->generateUrl('user_roles_index'));
          }
      }

      return array(
          'form' => $form->createView(),
          'id' => $id
      );
  }


    /**
     * @Route("roles/{id}",
     *         name="user_roles_remove",
     *         requirements={"id" = "\d+"})
     */
    public function removeRoleAction($id) {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository("Kamran\UserBundle\Entity\Role")->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('category with id=' . $id . ' not found');
        }
        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Selected role removed successfully.');
        return $this->redirect($this->generateUrl('user_roles_index'));
    }



    /**
     * @Route("/upload",name="prospection_admin_upload" ,options={"expose"=true})
     * @Template()
     */
    public function uploadAction(){
        $editId = 10;
        if (!preg_match('/^\d+$/', $editId) && $editId!='test')
        {
            throw new \Exception("Bad edit id");
        }
        $file  = $this->get('punk_ave.file_uploader')->handleFileUpload(array('folder' => '/photo/' . $editId), true, true);
        return new Response(json_encode($file));

    }






}//@
