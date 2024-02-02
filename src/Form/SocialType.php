<?php

namespace App\Form;

use App\Entity\Social;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SocialType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, $this->getConfiguration('Nom', 'Nom du réseau'))
            ->add('link', TextType::class, $this->getConfiguration('Lien', 'Lien du réseau'))
            ->add('picture', MediaType::class, ['label' => 'Logo'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Social::class,
        ]);
    }
}
