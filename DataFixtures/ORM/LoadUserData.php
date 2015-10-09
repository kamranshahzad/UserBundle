<?php

namespace Kamran\UserBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Kamran\UserBundle\Entity\User;
use Kamran\UserBundle\Entity\Role;



class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    public $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        $adminAccount = array(
            'username' => 'kamran',
            'password' => '12345678',
            'email' => 'your_email',
            'firstname' => 'Kamran',
            'lastname' => 'Shahzad',
            'role' => 'ROLE_ADMIN'
        );

        $userPassword  = $adminAccount['password'];
        $user = new User();
        $user->setUsername($adminAccount['username']);
        $user->setPassword($userPassword);
        $user->setFirstName($adminAccount['firstname']);
        $user->setLastName($adminAccount['lastname']);
        $user->setEmail($adminAccount['email']);
        $user->setIsActive(1);

        $factory = $this->container->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);

        $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
        $user->setPassword($password);
        $manager->persist($user);

        $role = $manager->getRepository("Kamran\UserBundle\Entity\Role")->findOneBy(array("name"=>$adminAccount['role']));
        if(!$role){
            throw new \Exception("Role " . $adminAccount['role'] . " not found.");
        }
        $user->addRole($role);
        $manager->flush();



    }

    public function getOrder()
    {
        return 2;
    }
}