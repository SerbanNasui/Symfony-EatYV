<?php
namespace App\Form;

use App\Entity\FoodCategory;
use App\Entity\Recipe;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class RecipeFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Article|null $article */
        $recipe = $options['data'] ?? null;
        $isEdit = $recipe && $recipe->getRecipeId();

        $imageConstraints = [
            new Image([
                'maxSize' => '5M'
            ])
        ];
        if (!$isEdit || !$article->getImages()) {
            $imageConstraints[] = new NotNull([
                'message' => 'Please upload an image',
            ]);
        }

        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'constraints' => [new NotBlank()],
                    'attr' => ['class' => 'form-control']
                ]
            )
            ->add('foodCategory', EntityType::class, [
                'class' => 'App\Entity\FoodCategory',
                
            ])
            ->add(
                'dateStart',
                DateTimeType::class,
                [
                    'widget' => 'single_text',
                    'constraints' => [new NotBlank()],
                    
                ]
            )
            ->add(
                'dateEnd',
                DateTimeType::class,
                [
                    'widget' => 'single_text',
                    'constraints' => [new NotBlank()],
                    
                ]
            )
            ->add(
                'price',
                MoneyType::class,
                [
                    'constraints' => [new NotBlank()],
                    'attr' => [
                        'min' => 1,
                        'class' => 'form-control']
                ]
            )
            ->add(
                'maxNrPersons',
                IntegerType::class,
                [
                    'constraints' => [new NotBlank()],
                    'attr' => [
                        'min' => 1,
                        'class' => 'form-control'],
                ]
            )
            ->add(
                'address',
                TextType::class,
                [
                    'constraints' => [new NotBlank()],
                    'attr' => ['class' => 'form-control']
                ]
            )
            ->add(
                'city',
                TextType::class,
                [
                    'constraints' => [new NotBlank()],
                    'attr' => ['class' => 'form-control']
                ]
            )
            ->add(
                'country',
                CountryType::class,
                [
                    'constraints' => [new NotBlank()],
                    'attr' => ['class' => 'form-control']
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'constraints' => [new NotBlank()],
                    'attr' => ['class' => 'form-control']
                ]
            )
            ->add(
                'image', 
                FileType::class, 
                [
                'mapped' => false,
                'required' => false,
                'constraints' => $imageConstraints
                ]
            )
            ->add(
                'create',
                SubmitType::class,
                [
                    'attr' => ['class' => 'form-control btn-orange pull-right'],
                    'label' => 'Create!'
                ]
            );
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

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_recipe';
    }
}