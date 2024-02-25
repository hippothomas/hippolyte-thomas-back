<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Technology;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, $this->getConfiguration('Nom', 'Nom du projet'))
            ->add('slug', TextType::class, $this->getConfiguration('Slug', 'Chaine URL du projet (auto)', ['required' => false]))
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
            ->add('introduction', TextType::class, $this->getConfiguration('Introduction', 'Description courte du projet (1 phrase)'))
            ->add('description', TextareaType::class, $this->getConfiguration('Description', 'Description détaillée du projet', [
                'attr' => [
                    'class' => 'tinymce',
                ],
            ]))
            ->add('technologies', EntityType::class, [
                'label' => 'Technologies',
                'class' => Technology::class,
                'choice_label' => 'name',
                'multiple' => true,
            ])
            ->add('pictures', CollectionType::class, [
                'label' => 'Galerie Photos',
                'entry_type' => MediaType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
            'sanitize_html' => true,
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}
