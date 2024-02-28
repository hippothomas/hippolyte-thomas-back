<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration('Titre', "Titre de l'article"))
            ->add('slug', TextType::class, $this->getConfiguration('Slug', "Chaine URL de l'article (auto)", [
                'required' => false,
            ]))
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'tinymce content',
                ],
            ])
            ->add('featureImage', MediaType::class, [
                'label' => 'Image à la une',
                'required' => false,
            ])
            ->add('primaryTag', EntityType::class, [
                'label' => 'Tag principal',
                'class' => Tag::class,
                'choice_label' => 'name',
            ])
            ->add('tags', EntityType::class, [
                'label' => 'Tags',
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
            ])
            ->add('published', DateTimeType::class, $this->getConfiguration('Date de publication', '', [
                'widget' => 'single_text',
                'html5' => false,
                'input_format' => 'dd/MM/yyyy HH:mm:ss',
                'format' => 'dd/MM/yyyy HH:mm:ss',
                'attr' => [
                    'class' => 'datepicker',
                ],
                'required' => false,
            ]))
            ->add('featured', CheckboxType::class, [
                'label' => 'Mettre en avant ?',
                'required' => false,
            ])
            ->add('uuid', TextType::class, [
                'attr' => [
                    'readonly' => true,
                    'tabindex' => '-1',
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}
