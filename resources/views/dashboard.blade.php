<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="p-6">

        {{-- mensagens --}}
        @if (session('success'))
            <div style="color: green;">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div style="color: red;">
                {{ session('error') }}
            </div>
        @endif

        {{-- resultado --}}
        @if (auth()->user()->type)
            @php
                $type = auth()->user()->type->type_name;
                $config = pokemonType($type);
            @endphp

            <div
                style="background-color: {{ $config['color'] }};color: #fff;padding: 10px;border-radius: 8px;display: inline-block;margin-top: 10px;">
                Seu tipo: <strong>{{ $config['label'] }}</strong>
            </div>
        @endif

        @if (!auth()->user()->type)
            <div style="margin-top:20px;">
                <div id="roulette"
                    style="
        font-size: 24px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #ccc;
        border-radius: 10px;
        margin-bottom: 10px;
    ">
                    ?
                </div>

                <button onclick="spinRoulette()">
                    🎰 Rodar Roleta
                </button>
            </div>
        @endif

        <div style="margin-top:20px;">
            <a href="{{ route('users.index') }}">
                👥 Ver todos os jogadores
            </a>
        </div>
        @if (auth()->user()->id === 1)
            <form method="POST" action="{{ route('roulette.reset') }}" style="margin-top:20px;">
                @csrf
                <button type="submit" onclick="return confirm('Tem certeza que deseja resetar a roleta?')">
                    🔄 Resetar Roleta
                </button>
            </form>
        @endif

    </div>
    <script>
        async function spinRoulette() {
            const roulette = document.getElementById('roulette');

            const types = @json(array_keys(config('pokemon.types')));

            let interval;
            let speed = 100;
            let index = 0;

            // animação inicial (rápida)
            interval = setInterval(() => {
                const type = types[index % types.length];
                const config = @json(config('pokemon.types'));

                roulette.innerText = config[type]?.label ?? type;
                roulette.style.backgroundColor = config[type]?.color ?? '#999';
                roulette.style.color = '#fff';
                index++;
            }, speed);

            try {
                const response = await fetch("{{ route('roulette.spin') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.error) {
                    clearInterval(interval);
                    alert(data.error);
                    return;
                }

                const finalType = data.type;

                // desacelerar
                let slowdown = setInterval(() => {
                    clearInterval(interval);

                    speed += 50;

                    interval = setInterval(() => {
                        const type = types[index % types.length];
                        const config = @json(config('pokemon.types'));

                        roulette.innerText = config[type]?.label ?? type;
                        roulette.style.backgroundColor = config[type]?.color ?? '#999';
                        roulette.style.color = '#fff';
                        index++;
                    }, speed);

                    if (speed > 400) {
                        clearInterval(interval);
                        clearInterval(slowdown);

                        roulette.innerText = finalType;

                        // reload para atualizar estado
                        setTimeout(() => location.reload(), 1500);
                    }

                }, 300);

            } catch (e) {
                clearInterval(interval);
                alert('Erro ao rodar roleta');
            }
        }
    </script>
</x-app-layout>
