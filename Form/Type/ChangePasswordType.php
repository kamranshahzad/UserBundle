<?php
namespace Kamran\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('curr_password','password', array(
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



    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            //'data_class' => 'Kamran\UserBundle\Entity\User',
        ));
    }

    public function getName(){
        return 'changepassword_form';
    }


} //@