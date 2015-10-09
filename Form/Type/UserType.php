<?php

namespace Kamran\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username','text', array(
            'attr' => array(
                'class' => 'form-control',
                'required' => true,
            ),
        ));

        $builder->add('password', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'The password fields must match.',
            'options' => array('attr' => array('class' => 'password-field')),
            'required' => true,
            'first_options'  => array('label' => 'Password','attr' => array(
                'class' => 'form-control',
                'required' => true,
            )),
            'second_options' => array('label' => 'Repeat Password','attr' => array(
                'class' => 'form-control',
                'required' => true,
            )),
            'error_bubbling' => true
        ));

        $builder->add('email','text', array(
            'attr' => array(
                'class' => 'form-control',
                'required' => true,
            ),
        ));


        $builder->add('roles', 'entity', array(
            'class' => 'KamranUserBundle:Role',
            'property' => 'label',
            //'choice_list' => new SimpleChoiceList($optionArray),
            'multiple' => true,
            'expanded' => true,
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

        $builder->add('isactive', 'checkbox', array(
            'label'     => 'Is user active?',
            'required'  => false,
            'data' => true
        ));

        $builder->add('sendmail', 'checkbox', array(
            'label'     => 'Send user info on his email?',
            'required'  => false,
            'data' => true,
            'mapped' => false
        ));


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Kamran\UserBundle\Entity\User',
        ));
    }

    public function getName()
    {
        return 'user_form';
    }


} //@
