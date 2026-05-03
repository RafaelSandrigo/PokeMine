<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class PokemonService
{
    /**
     * Retorna todos os tipos válidos de Pokémon
     */
    public function getTypes(): Collection
    {
        $response = Http::get('https://pokeapi.co/api/v2/type/');

        if (!$response->successful()) {
            throw new \Exception('Erro ao buscar tipos da PokéAPI');
        }

        return collect($response->json()['results'])
            ->pluck('name')
            ->filter(fn ($type) => !in_array($type, ['shadow', 'unknown']))
            ->values();
    }
}