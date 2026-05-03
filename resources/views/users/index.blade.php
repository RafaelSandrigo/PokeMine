<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Jogadores e Tipos
        </h2>
    </x-slot>

    <div class="p-6">

        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Tipo Sorteado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->type)
                                @php
                                    $type = $user->type->type_name;
                                    $config = pokemonType($type);
                                @endphp

                                <span
                                    style="
            background-color: {{ $config['color'] }};
            color: #fff;
            padding: 5px 10px;
            border-radius: 6px;
        ">
                                    {{ $config['label'] }}
                                </span>
                            @else
                                <span style="color: gray;">Não sorteado</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">Nenhum usuário encontrado</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</x-app-layout>
