<?php

namespace App\Form;

use App\Entity\Project;
use App\Form\TechnologyType;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProjectType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, $this->getConfiguration("Nom", "Nom du projet"))
            ->add('slug', TextType::class, $this->getConfiguration("Slug", "Chaine URL du projet (auto)", [ 'required' => false ]))
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction", "Description courte du projet (1 phrase)"))
            ->add('description', TextareaType::class, $this->getConfiguration("Description", "Description détaillée du projet"))
            ->add('technologies', CollectionType::class, [
                'label' => 'Technologies utilisées',
                'entry_type' => TechnologyType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('pictures', CollectionType::class, [
                'label' => 'Galerie Photos',
                'entry_type' => MediaType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
