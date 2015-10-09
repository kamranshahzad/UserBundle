<?php
namespace Kamran\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table(name="role_permissions")
 * @ORM\Entity()
 */
class RolePermissions
{


    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="role_id", type="integer")
     */
    private $roleId;

    /**
     * @ORM\Column(name="list", type="text")
     */
    private $list;


    public function getId(){
        return $this->id;
    }

    public function setList($list){
        $this->list = $list;
        return $this;
    }

    public function getList(){
        return $this->list;
    }

    public function setRoleId($rid){
        $this->roleId = $rid;
        return $this;
    }

    public function getRoleId(){
        return $this->roleId;
    }


    /**
     * To string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->list;
    }



}//@