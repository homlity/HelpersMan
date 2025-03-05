<?php
namespace Codwelt\HelpersMan;
class Arr {
    public static function merge_multidimecional_array($array1, $array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => & $value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = self::merge_multidimecional_array($merged[$key], $value);
            } else if (is_numeric($key)) {
                if (!in_array($value, $merged)) {
                    $merged[] = $value;
                }
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
    public static function except(array $array, array $exceptKeys): array {
        return array_filter($array, function ($value, $key) use ($exceptKeys) {
            return !in_array($key, $exceptKeys) && !is_array($value);
        }, ARRAY_FILTER_USE_BOTH);
    }
}