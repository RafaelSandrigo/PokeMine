<?php

namespace App\Http\Controllers;

use App\Models\UserType;
use App\Services\PokemonService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class RouletteController extends Controller
{
    /**
     * Executa a roleta para o usuário autenticado
     */
    public function spin(PokemonService $service)
    {
        $user = auth()->user();

        if ($user->type) {
            return response()->json([
                'error' => 'Você já rodou a roleta!'
            ], 400);
        }

        try {
            $type = DB::transaction(function () use ($user, $service) {

                $allTypes = $service->getTypes();
                $usedTypes = UserType::pluck('type_name');

                $availableTypes = $allTypes->diff($usedTypes)->values();

                if ($availableTypes->isEmpty()) {
                    throw new \Exception('Todos os tipos já foram sorteados!');
                }

                $selectedType = $availableTypes->random();

                UserType::create([
                    'user_id' => $user->id,
                    'type_name' => $selectedType,
                ]);

                return $selectedType;
            });

            return response()->json([
                'type' => $type
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function reset(): RedirectResponse
    {
        $user = auth()->user();

        // regra simples de admin
        if ($user->id !== 1) {
            abort(403, 'Apenas o admin pode resetar a roleta.');
        }

        UserType::truncate();

        return back()->with('success', 'Roleta resetada com sucesso!');
    }
}
