<?php
namespace Kamran\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class RegisterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options){

        $builder->add('firstname','text', array(
            'attr' => array(
                'class' => 'form-control',
                'required' => false,
            ),
        ));

        $builder->add('lastname','text', array(
            'attr' => array(
                'class' => 'form-control',
                'required' => false,
            ),
        ));

        $builder->add('email','text', array(
            'attr' => array(
                'class' => 'form-control',
                'required' => false,
            ),
        ));

        $builder->add('isindividual','choice', array(
            'attr' => array(
                //'class' => 'form-control',
                'required' => false,
            ),
            'choices' => array('y'=>'Individual','n'=>'Organization'),
            'multiple' => false,
            'expanded' => true,
            'data' => 'y'
        ));

        $builder->add('organization','text', array(
            'attr' => array(
                'class' => 'form-control',
                'required' => false,
            ),
            'required' => false,
        ));

        $builder->add('username','text', array(
            'attr' => array(
                'class' => 'form-control',
                'required' => false,
            ),
        ));

        $builder->add('password', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'The password fields must match.',
            'options' => array('attr' => array('class' => 'password-field')),
            'required' => true,
            'first_options'  => array('label' => 'Password','attr' => array(
                'class' => 'form-control',
                'autocomplete' => 'off',
                'required' => true,
            )),
            'second_options' => array('label' => 'Repeat Password','attr' => array(
                'class' => 'form-control',
                'required' => true,
            )),
            'error_bubbling' => true
        ));

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Kamran\UserBundle\Entity\User',
        ));
    }

    public function getName()
    {
        return 'register_form';
    }


} //@
