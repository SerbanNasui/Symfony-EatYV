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

class UserProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add(
            'firstName',
            TextType::class,
            [
                'constraints' => [new NotBlank()],
                'attr' => ['class' => 'form-control']
            ]
        )
        ->add(
            'secondName',
            TextType::class,
            [
                'constraints' => [new NotBlank()],
                'attr' => ['class' => 'form-control']
            ]
        )
        ->add(
            'biography',
            TextareaType::class,
            [
                'constraints' => [new NotBlank()],
                'attr' => ['class' => 'form-control']
            ]
        )
        ->add(
            'profileImage', 
            FileType::class, 
            [
            'mapped' => false,
            'required' => false,
            'constraints' => new NotNull([
                'message' => 'Please upload an image'
            ])
            ]
        )
        ->add(
            'create',
            SubmitType::class,
            [
                'attr' => ['class' => 'form-control btn-orange pull-right'],
                'label' => 'Create!'
            ]
        )
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

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_userprofile';
    }
}