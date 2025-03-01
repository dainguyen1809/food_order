<?php

if (! function_exists('nestedArrayParser')) {
    function nestedArrayParser($arr, $prefix = '')
    {
        $result = [];

        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, nestedArrayParser($value, $prefix.$key.'.'));
            } else {
                $result[$prefix.$key] = $value;
            }
        }

        return $result;
    }
}
