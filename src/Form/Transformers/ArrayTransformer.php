<?php

namespace App\Form\Transformers;

use Symfony\Component\Form\DataTransformerInterface;

class ArrayTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        // Wandelt das Array in einen Zeichenkettenwert um
        if ($value === null) {
            return '';
        }

        return implode(',', $value);
    }

    public function reverseTransform($value)
    {
        // Wandelt den Zeichenkettenwert in ein Array um
        if ($value === null) {
            return [];
        }

        $roles = explode(',', $value);

        return array_map('trim', $roles);
    }
}
