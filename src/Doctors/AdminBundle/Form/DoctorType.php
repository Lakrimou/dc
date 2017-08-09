<?php

namespace Doctors\AdminBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\EntityRepository;

class DoctorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nameDoctor')
            ->add('nationality')
            ->add('phoneNumberDoctor')
            ->add('speciality', EntityType::class, array(
                'class'=>'Doctors\AdminBundle\Entity\Speciality',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->where('s.isActiveSpeciality =:param')
                        ->setParameter('param', 1)
                        ;
                },
                'choice_label'=>'nameSpeciality'
            ))
            ->add('countryDoctor', ChoiceType::class, array(
                'choices' => array(
                    'المملكة العربية السعودية'=>'المملكة العربية السعودية',
                    'الجزائر' => 'الجزائر',
                    'البحرين'=>'البحرين',
                    'مصر'=>'مصر',
                    'الامارات العربية المتحدة'=>'الامارات العربية المتحدة',
                    'العراق'=>'العراق',
                    'الاردن'=>'الاردن',
                    'الكويت'=>'الكويت',
                    'لبنان'=>'لبنان',
                    'ليبيا'=>'ليبيا',
                    'المغرب'=>'المغرب',
                    'موريطانيا'=>'موريطانيا',
                    'عمان'=>'عمان',
                    'فلسطين'=>'فلسطين',
                    'قطر'=>'قطر',
                    'السودان'=>'السودان',
                    'السودان الجنوبية'=>'السودان الجنوبية',
                    'سوريا'=>'سوريا',
                    'تونس'=>'تونس',
                    'اليمن'=>'اليمن',
                    'البحرين'=>'البحرين',
                ),
            ))
            ->add('townDoctor')
            ->add('cityDoctor')
            ->add('workplace')
            ->add('workplaceName')
            ->add('startTimeWork')
            ->add('endTimeWork')
            ->add('emailDoctor')
            ->add('passwordDoctor',RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'الرجاء ادخال نفس كلمة العبور',
                'options' => array('attr' => array('class' => 'password-field form-control')),
                'required' => true,
                'first_options'  => array('label' => 'كلمة العبور'),
                'second_options' => array('label' => 'اعادة كلمة العبور'),
            ))
            ->add('photoDoctor', FileType::class, array('data_class'=>null));
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
