<?php

namespace Doctors\AdminBundle\Form;

use Doctrine\DBAL\Types\BigIntType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('phoneNumber', TextType::class, array('required'=>true))
            ->add('password',RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'الرجاء ادخال نفس كلمة العبور',
                'options' => array('attr' => array('class' => 'password-field form-control')),
                'required' => true,
                'first_options'  => array('label' => 'كلمة العبور'),
                'second_options' => array('label' => 'اعادة كلمة العبور'),
            ))
            ->add('name')
            ->add('country', ChoiceType::class, array(
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
            ->add('town')
            ->add('city')
            ->add('photo', FileType::class, array('data_class' => null, 'required'=>false))
            ->add('email');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Doctors\AdminBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'doctors_adminbundle_user';
    }


}
