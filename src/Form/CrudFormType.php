<?php

namespace App\Form;

use App\Entity\Crud;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CrudFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['data_class' => null])
            ->add('description', TextType::class, ['data_class' => null])
            ->add('image', FileType::class, [
                'required' => false,
                'constraints' => new File([
                    'maxSize' => '1024k',
                    'mimeTypesMessage' => 'Please upload a valid image document'
                ]),
                'data_class' => null
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Crud::class,
            'csrf_field_name' => '_token',
            'c'
        ]);
    }
}
