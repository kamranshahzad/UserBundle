<?php

namespace Kamran\UserBundle\Controller\Rest;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Kamran\UtilBundle\Base\Util;


/**
 * @Route("/api")
 */
class ApiController extends FOSRestController
{

    /**
     * @Route(
     *      "/ping",
     *      name = "api.service.ping",
     *      defaults = {
     *          "_format" = "json"
     *      },
     *      requirements = {
     *          "_method" = "GET"
     *      })
     * @Rest\View
     */
    public function pingserviceAction(Request $request){
        return new Response(json_encode(array('status'=>'200')));
    }

    /**
     * @Route(
     *      "/users",
     *      name = "api.get_users",
     *      defaults = {
     *          "_format" = "json"
     *      },
     *      requirements = {
     *          "_method" = "GET"
     *      },
     *      options = {
     *          "expose" = true
     *      },
     * )
     * @Rest\View
     */
    public function getUsersAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository("Kamran\UserBundle\Entity\User")->findAll();
        $outputArray = array();
        foreach($users as $user){
            $array = array();
            $array['id']    = $user->getId();
            $array['username']  = $user->getUsername();
            $array['email']  = $user->getEmail();
            $array['firstname']  = $user->getFirstname();
            $array['lastname']  = $user->getLastname();
            $array['isactive']  = ($user->getIsActive()) ? 'Yes' : 'No';
            $array['createddate']  = $user->getCreatedDate()->format("d-m-Y");
            $outputArray[]  = $array;
        }

        return new Response(json_encode($outputArray));
    }


    /**
     * @Route(
     *      "/roles",
     *      name = "api.get_roles",
     *      defaults = {
     *          "_format" = "json"
     *      },
     *      requirements = {
     *          "_method" = "GET"
     *      })
     * @Rest\View
     */
    public function getRolesAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $roles = $em->getRepository("Kamran\UserBundle\Entity\Role")->findAll();
        $outputArray = array();
        foreach($roles as $role){
            $array = array();
            $array['id']    = $role->getId();
            $array['name']  = $role->getName();
            $array['role']  = $role->getRole();
            $outputArray[]  = $array;
        }

        return new Response(json_encode($outputArray));
    }


    /**
     * @Route(
     *      "/images/uploads",
     *      name = "user.images.uploads",
     *      defaults = {
     *          "_format" = "json"
     *      },
     *      requirements = {
     *          "_method" = "POST"
     *      },
     *      options = {
     *          "expose" = true
     *      }
     * )
     *
     * @Rest\View
     */
    public function uploadImagesAction(Request $request){

        $helper = $this->get('base.helper');
        $path = $this->get('router')->generate('user.images.uploads');
        $scriptPath = $request->getHttpHost().$path;

        $uploadOptions = array(
            'upload_dir' => $helper->webDir2.'/uploads',
            'upload_url' => $helper->webDir2.'/uploads',
            //'script_url' => $scriptPath ,
            //'accept_file_types' => '/\.(gif|jpe?g|png)$/i',
            //'max_file_size' => 1024*1024*8
        );

        $files = new \Kamran\UserBundle\BlueImp\UploadHandler($uploadOptions);

        return new Response(json_encode(array()));

    }





}//@

