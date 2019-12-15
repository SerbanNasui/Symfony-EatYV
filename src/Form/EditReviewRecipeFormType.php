<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;


class EditReviewRecipeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('grade', ChoiceType::class, array('choices' => array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' =>'5'), 'attr' => array('min' => 1,
        'max' => 5,'class' => 'form-control')))
        ->add('comment', TextareaType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control')
        ))
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
            'data_class' => 'App\Entity\RecipeReview'
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'userAuthor_form';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_recipe_review';
    }
}
