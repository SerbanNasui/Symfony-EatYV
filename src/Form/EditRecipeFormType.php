<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class EditRecipeFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('title', TextType::class, array('attr' => array('class' => 'form-control')))
      ->add('dateStart', DateTimeType::class, array('widget' => 'single_text'))
      ->add('dateEnd', DateTimeType::class, array('widget' => 'single_text'))
      ->add('price', MoneyType::class, array('attr' => array('min' => 1,'class' => 'form-control')))
      ->add('maxNrPersons', IntegerType::class, array('attr' => array('min' => 1,'class' => 'form-control')))
      ->add('address', TextType::class, array('attr' => array('class' => 'form-control')))
      ->add('city', TextType::class, array('attr' => array('class' => 'form-control')))
      ->add('country', CountryType::class, array('attr' => array('class' => 'form-control')))
      ->add('description', TextareaType::class, array('required' => false, 'attr' => array('class' => 'form-control')))
      ->add('image',FileType::class,['label' => 'Please upload images','mapped' => false,'required' => false])
      ->add('save', SubmitType::class, array(
        'label' => 'Update',
        'attr' => array('class' => 'btn btn-orange mt-3')
      ));
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
