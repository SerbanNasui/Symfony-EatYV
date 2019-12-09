<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\NotNull;

class EditUserProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('firstName', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('secondName', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('biography', TextareaType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control')
        ))
        ->add( 
            'profileImage', 
            FileType::class, 
            [
            'label' => 'Please upload images',
            'mapped' => false,
            'attr' => ['class' => 'form-control'],
            'required' => false
            ]
          )
        ->add('save', SubmitType::class, array(
            'label' => 'Update',
            'attr' => array('class' => 'btn btn-orange mt-3')
        ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\UserProfile'
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'userProfileAuthor_form';
    }
}
