<?php
namespace Kamran\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Kamran\UserBundle\Entity\User;
use Kamran\UserBundle\Entity\Role;


/**
 *  cogi:user:create 'username' 'password' 'role' 'email'
*/
class UserCreateCommand extends ContainerAwareCommand
{
    /**
     * Configures the command and its help.
     */
    protected function configure(){
        parent::configure();
        $this->setName('kamran:user:create')
             ->addArgument('username', InputArgument::REQUIRED, 'Username')
             ->addArgument('password', InputArgument::REQUIRED, 'Password')
             ->addArgument('role', InputArgument::REQUIRED, 'Roles')
             ->addArgument('email', InputArgument::REQUIRED, 'Email')
        ;
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return integer 0 if everything went fine, or an error code
     *
     * @throws \LogicException When this abstract class is not implemented
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $options = $input->getArguments();
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        try {

            $userPassword  = $options['password'];
            $user = new User();
            $user->setUsername($options['username']);
            $user->setPassword($userPassword);
            $user->setEmail($options['email']);
            $user->setIsActive(1);

            $factory = $this->getContainer()->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);

            $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($password);
            $em->persist($user);

            $role = $em->getRepository("Kamran\UserBundle\Entity\Role")->findOneBy(array("role"=>$options['role']));
            if(!$role){
                throw new \Exception("Role " . $options['role'] . " not found.");
            }
            $user->addRole($role);
            $em->flush();

            $output->write('User: ' . $options['username'] . ' created with password: ' . $userPassword  . "\n");
        } catch (\Exception $e) {
            $output->write('User ' . $options['username'] . ' was NOT created.' . "\n\n" . $e->getMessage());
            echo "\n\n";
            echo $e->getTraceAsString();
        }
    }
}
