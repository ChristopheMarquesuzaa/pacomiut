<?php

namespace App\Bundle\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ArrayValuesExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('values', [$this, 'array_values']),
        ];
    }

    public function array_values($array)
    {
        return array_values($array);
    }
}
