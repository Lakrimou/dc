<?php

namespace Doctors\AdminBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctors\AdminBundle\Form\DataTransformer;
use Doctrine\ORM\EntityRepository;

class AppointmentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder/*->add('appointment', DateTimeType::class)*/
            /*->add('appointment')*/
            ->add('status', ChoiceType::class, array(
            'choices'  => array(
                'معلق' => 'معلق',
                'مؤكد' => 'مؤكد',
                'مرفوض' => 'مرفوض',
                'منته'=>'منته',
            ),))
            /*->add('time',ChoiceType::class, array(
                'choices'=>array(
                    '00:00'=>'00:30',
                    '01:00'=>'01:00',
                    '01:30'=>'01:30'
                )
            ))*/
            ->add('user', EntityType::class, array(
                'class'=>'DoctorsAdminBundle:User',
                'placeholder'=>'-----------------اختر المستخدم--------------',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.isActive =:param')
                        ->setParameter('param', 1)
                        ->andWhere('u.verified =:verif')
                        ->setParameter('verif', 1)
                        ;
                },
                'choice_label'=>'name',


            ))
            ->add('doctor', EntityType::class, array(
                'class'=>'DoctorsAdminBundle:Doctor',
                'placeholder'=>'--------------اختر الطبيب--------------',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->where('d.isActiveDoctor =:param')
                        ->setParameter('param', 1)
                        ->andWhere('d.isCompleted =:comp')
                        ->setParameter('comp', 1)
                        ;
                },
                'choice_label'=>'nameDoctor',
                ));
            /*$builder->get('appointment')->addModelTransformer(new CallbackTransformer(
                function($appointment){
                    return $time = $appointment->format('h:i:s');
                },
                function($date, $time)
                {
                    $date = now();
                    return $appointment = date('Y-m-d H:i:s', strtotime("$date $time"));
                }
            ));*/
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Doctors\AdminBundle\Entity\Appointment'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'doctors_adminbundle_appointment';
    }

}
