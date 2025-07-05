<?php

/**
 * @param $data
 * @param array $attributes
 * @return string
 * @throws Exception
 * @throws \Illuminate\Contracts\Container\BindingResolutionException
 */

if (!function_exists('translateAttr')) {
    function translateAttr($data, $attributes = []) {
        $attrs = collect($attributes)->map(function ($value) {
            return __($value);
        });
        return __($data, $attrs->toArray());
    }
}
