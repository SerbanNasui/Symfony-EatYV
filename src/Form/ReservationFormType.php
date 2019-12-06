<?php

namespace App\Form;

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ReservationFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'reservationForFirstName',
                TextType::class,
                [
                    'constraints' => [new NotBlank()],
                    'attr' => ['class' => 'form-control']
                ]
            )
            ->add(
                'reservationForSecondName',
                TextType::class,
                [
                    'constraints' => [new NotBlank()],
                    'attr' => ['class' => 'form-control']
                ]
            )
            ->add(
                'message',
                TextareaType::class,
                [
                    'constraints' => [new NotBlank()],
                    'attr' => ['class' => 'form-control']
                ]
            )
            ->add(
                'userReservaionId', 
                EntityType::class, 
                array('class' => 'App:User', 'choice_label' => 'username', 
                'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px'))
            )

            ->add(
                'recipeReservaionId', 
                EntityType::class, 
                array('class' => 'App:Recipe', 'choice_label' => 'title', 
                'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px'))
            );
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Reservation'
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'userAuthor_form';
    }
}
