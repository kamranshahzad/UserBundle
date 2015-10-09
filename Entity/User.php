<?php

namespace Kamran\UserBundle\Entity;


use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Kamran\OrganizationBundle\Entity\Organization;


/**
 * Kamran\UserBundle\Entity\User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Kamran\UserBundle\Entity\Repository\UserRepository")
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="username", type="string", length=100, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(name="password", type="string", length=100)
     */
    private $password;

    /**
     * @ORM\Column(name="firstname", type="string", length=50, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(name="lastname", type="string", length=50, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(name="is_individual",type="string", columnDefinition="ENUM('y', 'n')")
     */
    private $isIndividual;

    /**
     * @ORM\Column(name="organization", type="string", length=50, nullable=true)
     */
    private $organization;

    /**
     * @ORM\Column(name="salt", type="string", length=100)
     */
    private $salt;

    /**
     * @ORM\Column(name="email", type="string", length=100, unique=true)
     * @Assert\Email(message="{{ value }} is not a valid email address.", checkMX=true)
     */
    private $email;


    /**
     * @var string $avatar
     * @Assert\File( maxSize = "1024k", mimeTypesMessage = "Please upload a valid Image")
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $avatarFile;



    /**
     * @ORM\ManyToMany(targetEntity="Role")
     * @ORM\JoinTable(name="user_role",
     * joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    protected $roles;

    /**
     * @var boolean $isActive
     *
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=true)
     */
    private $createdDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_login_date", type="datetime", nullable=true)
     */
    private $lastLoginDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_passwordchange_date", type="datetime", nullable=true)
     */
    private $lastPasswordChangeDate;

    /**
     * @var boolean $isConfirmed
     *
     * @ORM\Column(name="is_confirmed", type="boolean" , nullable=true )
     */
    private $isConfirmed;

    /**
     * @ORM\OneToMany(targetEntity="Kamran\OrganizationBundle\Entity\Organization", mappedBy="user", cascade={"persist"})
     * @var type
     */
    private $organizations;


    public function __construct(){
        $this->salt = uniqid(mt_rand(), true);
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->organizations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdDate = new \DateTime('now');
    }

    public function getId(){
        return $this->id;
    }

    public function getUsername(){
        return $this->username;
    }

    public function setUsername($username){
        $this->username = $username;
    }

    public function getPassword(){
        return $this->password;
    }

    public function setPassword($password){
        $this->password = $password;
    }

    public function getSalt(){
        return $this->salt;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setEmail($email){
        $this->email = $email;
    }



    public function eraseCredentials(){}

    /**
     * Add Role.
     *
     * @return BigfootUser
     */
    public function addRole(Role $role)
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }

        return $this;
    }

    public function getRoles()
    {
        $roles = array();
        foreach ($this->roles as $role) {
            $roles[] = $role->getName();
        }
        return $roles;
        //$roles[] = static::ROLE_DEFAULT;
        //return array_unique($roles);
    }



    public function getFormRoles()
    {
        return $this->roles;
    }

    /**
     * Remove a User Role.
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $roles
     * @return BigfootUser
     */
    public function removeRole($role)
    {
        $this->roles->removeElement($role);

        return $this;
    }

    public function setIsConfirmed($isConfirmed){
        $this->isConfirmed = $isConfirmed;
        return $this;
    }

    public function getIsConformed(){
        return $this->isConfirmed;
    }

    public function setFirstName($firstname){
        $this->firstName = $firstname;
        return $this;
    }

    public function getFirstName(){
        return $this->firstName;
    }

    public function setLastName($lastname){
        $this->lastName = $lastname;
        return $this;
    }

    public function getLastName(){
        return $this->lastName;
    }

    public function setOrganization($organization){
        $this->organization = $organization;
        return $this;
    }

    public function getOrganization(){
        return $this->organization;
    }

    public function setIsIndividual($isIndividual){
        $this->isIndividual = $isIndividual;
        return $this;
    }

    public function getIsIndividual(){
        return $this->isIndividual;
    }


    public function setIsActive($isactive)
    {
        $this->isActive = $isactive;
        return $this;
    }
    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }



    /**
     * Set Avatar
     *
     * @param string $avatar
     *
     * @return user
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * Get Avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatarFile(UploadedFile $file = null){
        $this->avatarFile = $file;
        return $this;
    }

    public function getAvatarFile(){
        return $this->avatarFile;
    }

    public function uploadAvatarFile( $crudMode , $prevoiusImage ){
        if($this->getAvatarFile()){
            if ($this->getAvatarFile() instanceof UploadedFile) {
                $filename = sha1(uniqid(mt_rand(), true));
                $filenameext = $filename.'.'.pathinfo($this->getAvatarFile()->getClientOriginalName(), PATHINFO_EXTENSION);
                $this->getAvatarFile()->move( $this->getUploadRootDir() , $filenameext);
                if('' != $prevoiusImage){
                    $oldImgFile = $this->getUploadRootDir().'/'.$prevoiusImage;
                    if(file_exists($oldImgFile)) {unlink($oldImgFile);}
                }
                $this->setAvatar($filenameext);
            }
        }
        // check old image exists , just remove icon
        if(empty($this->getAvatar()) && $crudMode === 'edit'){
            if('' != $prevoiusImage){
                $oldImgFile = $this->getUploadRootDir().'/'.$prevoiusImage;
                if(file_exists($oldImgFile)) {unlink($oldImgFile);}
            }
        }
    }

    protected function getUploadRootDir(){
        return __DIR__.'/../../../../web/uploads';
    }



    /**
     * Set created date
     *
     * @param \DateTime $createddate
     *
     * @return createDate
     */
    public function setCreatedDate($createddate){
        $this->createdDate = $createddate;
        return $this;
    }

    /**
     * Get created date
     *
     * @return \DateTime
     */
    public function getCreatedDate(){
        return $this->createdDate;
    }


    /**
     * Set last login date
     *
     * @param \DateTime $last_login_date
     *
     * @return createDate
     */
    public function setLastLoginDate($last_login_date){
        $this->lastLoginDate = $last_login_date;
        return $this;
    }

    /**
     * Get last login date
     *
     * @return \DateTime
     */
    public function getLastLogindDate(){
        return $this->lastLoginDate;
    }

    /**
     * Set last password changed date
     *
     * @param \DateTime $last_passwordchange_date
     *
     * @return createDate
     */
    public function setLastPasswordChangeDate($last_passwordchange_date){
        $this->lastPasswordChangeDate = $last_passwordchange_date;
        return $this;
    }

    /**
     * Get last login date
     *
     * @return \DateTime
     */
    public function getLastPasswordChangeDate(){
        return $this->lastPasswordChangeDate;
    }


    /**
     * Serializes the content of the current User object
     * @return string
     */
    public function serialize()
    {
        return \json_encode(
            array($this->username, $this->password, $this->salt,
                $this->roles, $this->id));
    }

    /**
     * Unserializes the given string in the current User object
     * @param serialized
     */
    public function unserialize($serialized)
    {
        list($this->username, $this->password, $this->salt,
            $this->roles, $this->id) = \json_decode(
            $serialized);
    }


}//@
