<div class="w-full max-w-5xl lg:max-w-full mx-auto mt-2">
    <!-- Título -->
    <h2 class="text-2xl font-bold mb-6 flex items-center gap-2 text-gray-800 dark:text-gray-100">
        <svg class="h-8 w-8 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 32 32" stroke="currentColor">
            <rect x="6" y="10" width="20" height="12" rx="6" stroke-width="2.2"/>
            <path d="M8 14h16M8 18h10" stroke-width="2"/>
        </svg>
        Recipients
    </h2>

    <!-- Búsqueda + botón NUEVO -->
    <div class="flex justify-between items-center mb-4 gap-2">
        <!-- Buscador -->
        <div class="relative w-full md:w-80">
            <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
            </span>
            <input
                type="text"
                wire:model.live.debounce.400ms="search"
                placeholder="Buscar destinatario..."
                class="pl-10 pr-10 py-2 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring focus:border-gray-400 dark:focus:border-gray-500 transition"
            >
            @if($search)
                <button
                    type="button"
                    class="absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 focus:outline-none"
                    wire:click="$set('search', '')"
                    tabindex="-1"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            @endif
        </div>
        <!-- Botón nuevo (desktop) -->
        <button
            type="button"
            wire:click="showCreate"
            class="hidden md:flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100 font-semibold text-base shadow transition focus:outline-none focus:ring-2 focus:ring-gray-300"
        >
            <svg class="h-5 w-5 text-gray-600 dark:text-gray-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo destinatario
        </button>
    </div>

    <!-- TABLA desktop -->
    <div class="hidden md:block">
        <div class="overflow-x-auto rounded-xl shadow border border-gray-200 dark:border-gray-800 bg-white/90 dark:bg-gray-900/90">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800 text-sm">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-800">
                        <th class="p-3 font-semibold text-left text-gray-700 dark:text-gray-100">Tipo</th>
                        <th class="p-3 font-semibold text-left text-gray-700 dark:text-gray-100">Canal</th>
                        <th class="p-3 font-semibold text-left text-gray-700 dark:text-gray-100">Dirección</th>
                        <th class="p-3 font-semibold text-left text-gray-700 dark:text-gray-100">Bot</th>
                        <th class="p-3 font-semibold text-center text-gray-700 dark:text-gray-100">Activo</th>
                        <th class="p-3 font-semibold text-right text-gray-700 dark:text-gray-100 w-36">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recipients as $recipient)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition border-b border-gray-100 dark:border-gray-800">
                            <td class="p-3">{{ $recipient->type->name }}</td>
                            <td class="p-3">{{ $recipient->channel->name }}</td>
                            <td class="p-3">{{ $recipient->address }}</td>
                            <td class="p-3">{{ $recipient->bot ?? '—' }}</td>
                            <td class="p-3 text-center">
                                @if ($recipient->is_active)
                                    <span class="inline-flex items-center gap-1 font-bold text-green-500">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 font-bold text-red-500">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 text-right">
                                <button wire:click="showEdit({{ $recipient->id }})"
                                    class="inline-flex items-center justify-center text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 rounded p-2 transition mx-1"
                                    title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M15.232 5.232l3.536 3.536M9 13.5V17h3.5L20.232 9.768a2 2 0 000-2.828l-2.172-2.172a2 2 0 00-2.828 0L9 13.5z" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $recipient->id }})"
                                    class="inline-flex items-center justify-center text-gray-400 hover:text-red-500 dark:text-gray-500 dark:hover:text-red-400 rounded p-2 transition mx-1"
                                    onclick="return confirm('¿Eliminar destinatario?')" title="Eliminar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center p-6 text-gray-400 dark:text-gray-500">Sin destinatarios</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pt-4">
            {{ $recipients->links('pagination::tailwind') }}
        </div>
    </div>

    <!-- CARDS mobile -->
    <div class="md:hidden flex flex-col gap-4 mt-2">
        @forelse ($recipients as $recipient)
            <div class="rounded-xl bg-white/90 dark:bg-gray-900/90 border border-gray-200 dark:border-gray-800 shadow-none px-4 py-3 flex flex-col gap-2">
                <div class="flex items-center justify-between mb-1">
                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $recipient->address }}</span>
                    <div class="flex gap-1">
                        <button wire:click="showEdit({{ $recipient->id }})"
                            class="p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800 transition" title="Editar">
                            <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M15.232 5.232l3.536 3.536M9 13.5V17h3.5L20.232 9.768a2 2 0 000-2.828l-2.172-2.172a2 2 0 00-2.828 0L9 13.5z" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <button wire:click="delete({{ $recipient->id }})"
                            class="p-2 rounded hover:bg-red-100 dark:hover:bg-red-900/20 transition" onclick="return confirm('¿Eliminar destinatario?')" title="Eliminar">
                            <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 text-gray-800 dark:text-gray-200 text-xs">
                    <span><strong>Tipo:</strong> {{ $recipient->type->name }}</span>
                    <span><strong>Canal:</strong> {{ $recipient->channel->name }}</span>
                    <span><strong>Bot:</strong> {{ $recipient->bot ?? '—' }}</span>
                    <span><strong>Activo:</strong>
                        @if ($recipient->is_active)
                            <span class="text-green-500 font-bold">✓</span>
                        @else
                            <span class="text-red-500 font-bold">✗</span>
                        @endif
                    </span>
                </div>
            </div>
        @empty
            <div class="text-gray-400 dark:text-gray-500 text-center">Sin destinatarios</div>
        @endforelse
        <div class="pt-4">
            {{ $recipients->links('pagination::tailwind') }}
        </div>
    </div>

    <!-- FAB Botón agregar (solo mobile) -->
    <div class="fixed bottom-8 right-8 z-50 md:hidden">
        <button
            type="button"
            wire:click="showCreate"
            class="flex items-center justify-center w-14 h-14 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700 shadow-lg text-2xl transition"
            title="Nuevo destinatario"
        >
            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
        </button>
    </div>

    <!-- MODAL Crear/Editar Destinatario -->
    @if($showModal)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl w-full max-w-md mx-auto p-6 relative animate-fade-in-up"
                @click.away="$wire.cancelEdit()"
                @keydown.escape.window="$wire.cancelEdit()"
            >
                <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <svg class="h-6 w-6 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 32 32" stroke="currentColor">
                        <rect x="6" y="10" width="20" height="12" rx="6" stroke-width="2.2"/>
                        <path d="M8 14h16M8 18h10" stroke-width="2"/>
                    </svg>
                    {{ $editingId ? 'Editar destinatario' : 'Nuevo destinatario' }}
                </h3>
                <form wire:submit.prevent="save">
                    <div class="mb-3">
                        <label class="block mb-1 text-gray-700 dark:text-gray-200 font-medium">Tipo *</label>
                        <select wire:model.defer="type_id"
                            class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 font-normal focus:outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-500 transition">
                            <option value="">-- Selecciona un tipo --</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @error('type_id') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="block mb-1 text-gray-700 dark:text-gray-200 font-medium">Canal *</label>
                        <select wire:model.defer="channel_id"
                            class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 font-normal focus:outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-500 transition">
                            <option value="">-- Selecciona un canal --</option>
                            @foreach ($channels as $channel)
                                <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                            @endforeach
                        </select>
                        @error('channel_id') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="block mb-1 text-gray-700 dark:text-gray-200 font-medium">Dirección *</label>
                        <input type="text" wire:model.defer="address"
                            class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 font-normal focus:outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-500 transition"
                            placeholder="user@example.com o @telegramChatId">
                        @error('address') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="block mb-1 text-gray-700 dark:text-gray-200 font-medium">Bot Token Key</label>
                        <input type="text" wire:model.defer="bot"
                            class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 font-normal focus:outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-500 transition"
                            placeholder="(opcional)">
                        @error('bot') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center gap-2 mb-3">
                        <input type="checkbox" wire:model.defer="is_active" id="is_active"
                            class="rounded text-gray-600 dark:bg-gray-900 dark:border-gray-700 focus:ring-gray-500 transition">
                        <label for="is_active" class="text-gray-700 dark:text-gray-200 select-none cursor-pointer">Activo</label>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="cancelEdit"
                            class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-300 dark:hover:bg-gray-600 font-semibold transition"
                        >Cancelar</button>
                        <button type="submit"
                            class="px-5 py-2 rounded-lg bg-gray-700 hover:bg-gray-900 text-white font-semibold transition focus:outline-none focus:ring-2 focus:ring-gray-300 flex items-center justify-center gap-2"
                            wire:loading.attr="disabled"
                            wire:target="save"
                        >
                            <svg wire:loading wire:target="save" class="animate-spin h-5 w-5 mr-1 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span>
                                {{ $editingId ? 'Actualizar' : 'Agregar' }}
                            </span>
                        </button>
                    </div>
                </form>
                <button
                    wire:click="cancelEdit"
                    class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-2xl"
                    title="Cerrar"
                >&times;</button>
            </div>
        </div>
    @endif
</div>
