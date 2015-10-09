<?php

namespace Kamran\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Kamran\UserBundle\Entity\Role;


class LoadRoleData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $roleAdmin = new Role();
        $roleAdmin
            ->setName('ROLE_ADMIN')
            ->setLabel('Admin');

        $this->setReference('ROLE_ADMIN', $roleAdmin);

        $roleUser = new Role();
        $roleUser
            ->setName('ROLE_USER')
            ->setLabel('User');

        $this->setReference('ROLE_USER', $roleUser);


        $manager->persist($roleAdmin);
        $manager->persist($roleUser);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}