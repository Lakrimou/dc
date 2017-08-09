<?php

namespace Doctors\AdminBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\FormTypeInterface;
class EditPictureDoctorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('photoDoctor', FileType::class, array('data_class'=>null))
        /*->add('passwordDoctor',RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'الرجاء ادخال نفس كلمة العبور',
                'options' => array('attr' => array('class' => 'password-field form-control')),
                'required' => true,
                'first_options'  => array('label' => 'كلمة العبور'),
                'second_options' => array('label' => 'اعادة كلمة العبور'),
            ))*/;
        /*->add('nameDoctor', HiddenType::class)
        ->add('nationality', HiddenType::class)
        ->add('phoneNumberDoctor', HiddenType::class)
        ->add('speciality', EntityType::class, array(
            'class'=>'Doctors\AdminBundle\Entity\Speciality',
            'choice_label'=>'nameSpeciality'
        ))
        ->add('countryDoctor', HiddenType::class)
        ->add('townDoctor', HiddenType::class)
        ->add('cityDoctor', HiddenType::class)
        ->add('workplace', HiddenType::class)
        ->add('workplaceName', HiddenType::class)
        ->add('emailDoctor', HiddenType::class)
        ->add('passwordDoctor', RepeatedType::class, array(
            'type' => PasswordType::class,
            'invalid_message' => 'الرجاء ادخال نفس كلمة العبور',
            'options' => array('attr' => array('class' => 'password-field form-control')),
            'required' => true,
            'first_options'  => array('label' => 'كلمة العبور'),
            'second_options' => array('label' => 'اعادة كلمة العبور'),
        ));*/
        /*array('data_class' => 'Symfony\Component\HttpFoundation\File\File')*/
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Doctors\AdminBundle\Entity\Doctor'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'doctors_adminbundle_doctor';
    }


}
