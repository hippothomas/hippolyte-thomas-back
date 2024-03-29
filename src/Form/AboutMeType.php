<?php

namespace App\Form;

use App\Entity\AboutMe;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AboutMeType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, $this->getConfiguration('Nom', 'Prénom Nom'))
            ->add('job', TextType::class, $this->getConfiguration('Poste', 'Poste à afficher'))
            ->add('description', TextareaType::class, $this->getConfiguration('Description', 'Texte de présentation', ['attr' => ['rows' => '7']]))
            ->add('picture', MediaType::class, ['label' => 'Photo'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AboutMe::class,
            'sanitize_html' => true,
        ]);
    }
}
