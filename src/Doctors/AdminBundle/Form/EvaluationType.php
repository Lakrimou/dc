<?php

namespace Doctors\AdminBundle\Form;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctors\AdminBundle\Form\AppointmentType;

class EvaluationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('evaluation', ChoiceType::class, array(
            'choices'  => array(
                'ممتاز' => 'ممتاز',
                'جيد' => 'جيد',
                'سيء' => 'سيء',
            ),))
            ->add('feedback');
            /*->add('appointment', new AppointmentType())*/
            /*->add('doctor', EntityType::class, array('class'=>'DoctorsAdminBundle:Doctor','choice_label'=>'nameDoctor'))
            ->add('user', EntityType::class, array('class'=>'DoctorsAdminBundle:User','choice_label'=>'name'))*/
            /*->add('appointment', EntityType::class, array(
                'class'=>'DoctorsAdminBundle:Appointment',
                'query_builder'=>function(EntityRepository $er){
                    return $er->createQueryBuilder('a')->where('a.status = :status')->setParameter('status', 'مؤكد')->join('a.doctor', 'doc')->addSelect('doc')->join('a.user', 'user')->addSelect('doc', 'user');
                },
                'choice_label'=>'appointment'));*/
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Doctors\AdminBundle\Entity\Evaluation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'doctors_adminbundle_evaluation';
    }

}
