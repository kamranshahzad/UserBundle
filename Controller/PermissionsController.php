<?php

namespace Kamran\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use Kamran\UserBundle\Form\Type\RolePermissionType;


/**
 * Permission controller
 * @Route("/user")
 */
class PermissionsController extends Controller
{
    /**
     * @Route("/permissions", name="permission_index")
     * @Template()
     */
    public function indexAction(Request $request){



        $form = $this->createForm(new RolePermissionType());

        if ($request->getMethod() === 'POST'){
            $form->handleRequest($request);

            if ($form->isValid()) {
                echo "Data Updated successfully";
            }

        }

        return array(
            'form'   => $form->createView()
        );
    }

    /**
     * @Route("/role/{id}/permissions", name="permission_role_view")
     * @Template()
     */
    public function viewAction(Request $request,$id){

        $em = $this->getDoctrine()->getManager();

        $role = $em->getRepository('KamranUserBundle:Role')->find($id);
        $ace = $em->getRepository('KamranUserBundle:RolePermissions')->findOneBy(array("roleId"=>$id));


        $form = $this->createForm(new RolePermissionType($ace->getList()));

        if ($request->getMethod() === 'POST'){
            $form->handleRequest($request);
            if ($form->isValid()) {
                $ace->setList($form->getData());
                $em->persist($ace);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Role permissions implemented successfully.');
            }

        }

        return array(
            'form'   => $form->createView(),
            'id' => $id,
            'role_label' => $role->getLabel()
        );

    }

}//@


