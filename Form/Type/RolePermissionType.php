<?php

namespace Kamran\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;
use Symfony\Component\Form\Extension\Core\View\ChoiceView;

use Kamran\UserBundle\Form\DataTransformer\AccessOptionsTransformer;


class RolePermissionType extends AbstractType
{

    private $aceString;

    public function __construct($_aceString){
        $this->aceString = $_aceString;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $aclOptionsArray = array(
            'users' => array(
                'full' => 'Full Access',
                'read' => 'View Users',
                'write' => 'Create new users',
                'modify' => 'Modify current users',
                'remove' => 'Remove current users'
            ),
            'contacts' => array(
                'full' => 'Full Access',
                'read' => 'View contact',
                'write' => 'Create new contact',
                'modify' => 'Modify current contact',
                'remove' => 'Remove current contact'
            )
        );

        foreach($aclOptionsArray as $bundle => $permissions){
            $defaultOptions = array( 'multiple'=>true , 'expanded'=>true , 'choices'=>$permissions);
            $builder->add( $bundle , 'choice' , $defaultOptions);

        }

        $transformer = new AccessOptionsTransformer( $this->aceString );
        $builder->addModelTransformer($transformer);

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver){
        $resolver->setDefaults(array(
        ));
    }

    public function getName()
    {
        return 'rolepermissions';
    }

}



