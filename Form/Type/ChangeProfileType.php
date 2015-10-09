<?php
namespace Kamran\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ChangeProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){

        $builder->add('avatar', 'hidden');

        $builder->add('email','text', array(
            'attr' => array(
                'class' => 'form-control',
                'required' => true,
            ),
        ));

        $builder->add('firstname','text', array(
            'attr' => array(
                'class' => 'form-control',
                'required' => true,
            ),
        ));

        $builder->add('lastname','text', array(
            'attr' => array(
                'class' => 'form-control',
                'required' => true,
            ),
        ));

        $builder->add('lastname','text', array(
            'attr' => array(
                'class' => 'form-control',
                'required' => true,
            ),
        ));

        $builder->add('avatarFile','file', array(
            'attr' => array(
                'required' => false,
            ),
        ));


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Kamran\UserBundle\Entity\User',
        ));
    }

    public function getName(){
        return 'changeprofile_form';
    }


} //@
