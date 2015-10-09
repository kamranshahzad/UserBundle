<?php
namespace Kamran\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ForgetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username','text', array(
            'attr' => array(
                'class' => 'form-control',
                'required' => true,
            ),
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            //'data_class' => 'Kamran\UserBundle\Entity\User',
        ));
    }

    public function getName()
    {
        return 'resetpassword_form';
    }


} //@