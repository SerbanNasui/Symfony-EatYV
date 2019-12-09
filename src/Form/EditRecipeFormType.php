<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class EditRecipeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('description', TextareaType::class, array(
          'required' => false,
          'attr' => array('class' => 'form-control')
        ))
        ->add( 
          'image', 
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
            'data_class' => 'App\Entity\Recipe'
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
