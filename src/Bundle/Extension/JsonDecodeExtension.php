<?php

namespace App\Bundle\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class JsonDecodeExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('json_decode', [$this, 'json_']),
        ];
    }

    public function json_($array)
    {
        return json_decode($array);
    }
}
