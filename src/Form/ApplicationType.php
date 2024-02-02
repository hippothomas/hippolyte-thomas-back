<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType
{
    /**
     * Default configuration for the fields.
     *
     * @param array<mixed> $options
     *
     * @return array<mixed>
     */
    protected function getConfiguration(string $label, string $placeholder, array $options = []): array
    {
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder,
            ],
        ], $options);
    }
}
