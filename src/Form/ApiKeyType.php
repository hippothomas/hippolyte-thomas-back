<?php

namespace App\Form;

use App\Entity\ApiKey;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApiKeyType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, $this->getConfiguration('Nom', '', ['required' => false]))
            ->add('expirationDate', DateTimeType::class, $this->getConfiguration("Date d'expiration", '', [
                'widget' => 'single_text',
                'html5' => false,
                'input_format' => 'dd/MM/yyyy',
                'format' => 'dd/MM/yyyy',
                'attr' => [
                    'class' => 'datepicker',
                ],
                'required' => false,
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ApiKey::class,
            'sanitize_html' => true,
        ]);
    }
}
