<?php

if (!function_exists('pokemonType')) {

    function pokemonType(string $type): array
    {
        return config("pokemon.types.$type") ?? [
            'color' => '#999',
            'label' => ucfirst($type),
        ];
    }
}