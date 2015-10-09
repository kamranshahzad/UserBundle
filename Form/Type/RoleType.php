<?php
namespace Kamran\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class RoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name','text', array(
            'attr' => array(
                'class' => 'form-control',
                'required' => true,
            ),
        ));

        $builder->add('label','text', array(
            'attr' => array(
                'class' => 'form-control',
                'required' => true,
            ),
        ));

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Kamran\UserBundle\Entity\Role',
        ));
    }

    public function getName()
    {
        return 'role_form';
    }


} //@
