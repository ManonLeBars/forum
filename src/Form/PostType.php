<?php

namespace App\Form;

use App\Entity\Post;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class, [
            'label' => 'Titre du post',
            'constraints' => 
                new NotBlank(),
        ])
        ->add('body', TextareaType::class, [
            'label' => 'Contenu du post',
            'constraints' => 
                new NotBlank(),
        ])
        ->add('picture', TextType::class, [
            'label' => 'photo',
            'required' => false,
        ])
        ->add('is_active', IntegerType::class, [
            'label' => 'Afficher',
            'required' => true,
        ])
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
