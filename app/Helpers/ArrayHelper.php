<?php

namespace App\Helpers;

class ArrayHelper
{
    public function reduceArrayMultidimensional($data, $allowed)
    {
        $result = [];

        foreach ($data as $key => $value) {
            foreach ($allowed as $keyA => $valueA) {
                if (array_key_exists($valueA, $value)) {
                    $result[$key][$valueA] = $value[$valueA];
                }
            }
        }

        return $result;
    }

}
