<div class="w-full max-w-5xl lg:max-w-full mx-auto mt-2">
    <!-- Título -->
    <h2 class="text-2xl font-bold mb-6 flex items-center gap-2 text-gray-800 dark:text-gray-100">
        <svg class="h-8 w-8 text-gray-400 dark:text-gray-300" fill="none" viewBox="0 0 32 32" stroke="currentColor">
            <circle cx="16" cy="16" r="14" stroke-width="2.5"/>
            <path d="M16 10v7" stroke-width="2.5" stroke-linecap="round"/>
            <circle cx="16" cy="22" r="1.5" fill="currentColor"/>
        </svg>
        Registro de alertas
    </h2>

    <!-- Filtros -->
    <div class="flex flex-wrap gap-4 mb-6 bg-white/80 dark:bg-gray-900/80 rounded-xl shadow p-4 ring-1 ring-gray-200 dark:ring-gray-700 items-end">
        <div class="flex-1 min-w-[200px]">
            <x-beartropy-ui::input
                wire:model.live="search"
                placeholder="Buscar..."
                icon-start="magnifying-glass"
            />
        </div>
        <div class="w-40">
            <x-beartropy-ui::select
                wire:model.live="status"
                :options="$statuses"
                placeholder="Todos los estados"
            />
        </div>
        <div class="w-40">
            <x-beartropy-ui::select
                wire:model.live="type"
                :options="$types"
                placeholder="Todos los tipos"
            />
        </div>
        <div class="w-40">
            <x-beartropy-ui::select
                wire:model.live="channel"
                :options="$channels"
                placeholder="Todos los canales"
            />
        </div>
    </div>

    <!-- Tabla desktop -->
    <div class="hidden md:block">
        <div class="rounded-xl shadow border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
            <div class="overflow-x-auto beartropy-thin-scrollbar">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 font-medium border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-4 py-3">Tipo</th>
                            <th class="px-4 py-3">Canal</th>
                            <th class="px-4 py-3">Destino</th>
                            <th class="px-4 py-3">Bot</th>
                            <th class="px-4 py-3">Estado</th>
                            <th class="px-4 py-3">Enviado</th>
                            <th class="px-4 py-3 text-center">#</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($logs as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $log->type }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $log->channel }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $log->address }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $log->bot ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1.5 font-semibold text-xs px-2.5 py-0.5 rounded-full
                                        {{ $log->status === 'success' 
                                            ? 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400' 
                                            : 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400' }}">
                                        @if ($log->status === 'success')
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        @else
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        @endif
                                        {{ ucfirst($log->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $log->sent_at->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <x-beartropy-ui::button
                                        sm
                                        icon-start="eye"
                                        color="gray"
                                        variant="ghost"
                                        wire:click="showDetails({{ $log->id }})"
                                        class="!p-1.5 rounded-full"
                                    />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-6 text-gray-500 dark:text-gray-400">No hay alertas registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="pt-4">
            {{ $logs->links() }}
        </div>
    </div>

    <!-- Cards mobile -->
    <div class="md:hidden flex flex-col gap-4 mt-2">
        @forelse ($logs as $log)
            <div class="rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-4 shadow-sm flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $log->type }}</span>
                    <x-beartropy-ui::button
                        sm
                        icon-start="eye"
                        color="gray"
                        variant="ghost" 
                        wire:click="showDetails({{ $log->id }})"
                        class="!p-1.5 rounded-full"
                    />
                </div>
                <div class="grid grid-cols-2 gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500 uppercase">Canal</span>
                        <span class="text-gray-800 dark:text-gray-200">{{ $log->channel }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500 uppercase">Destino</span>
                        <span class="text-gray-800 dark:text-gray-200 truncate">{{ $log->address }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500 uppercase">Bot</span>
                        <span class="text-gray-800 dark:text-gray-200">{{ $log->bot ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500 uppercase">Enviado</span>
                        <span class="text-gray-800 dark:text-gray-200">{{ $log->sent_at->format('m-d H:i') }}</span>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100 dark:border-gray-800 mt-1">
                    <span class="inline-flex items-center gap-1.5 font-medium text-xs
                        {{ $log->status === 'success' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        @if ($log->status === 'success')
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        @else
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        @endif
                        {{ ucfirst($log->status) }}
                    </span>
                </div>
            </div>
        @empty
            <div class="text-gray-400 dark:text-gray-500 text-center py-4">No hay alertas.</div>
        @endforelse
        <div class="pt-4">
            {{ $logs->links() }}
        </div>
    </div>

    <!-- Modal Detalles -->
    <x-beartropy-ui::modal
        wire:model="selectedLog"
        max-width="2xl"
        styled
        title="Detalle de alerta"
    >
        @if ($selectedLog)
            <div class="space-y-3 text-sm">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase">Tipo</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $selectedLog->type }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase">Canal</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $selectedLog->channel }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase">Destino</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $selectedLog->address }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase">Estado</p>
                        <p class="font-medium {{ $selectedLog->status === 'success' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ ucfirst($selectedLog->status) }}
                        </p>
                    </div>
                </div>

                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase">Asunto</p>
                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $selectedLog->subject }}</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase mb-1">Mensaje</p>
                    <p class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap font-mono text-xs">{{ $selectedLog->message }}</p>
                </div>

                @if($selectedLog->error_message)
                    <div class="bg-red-50 dark:bg-red-900/20 p-3 rounded-lg border border-red-100 dark:border-red-800/30">
                        <p class="text-red-500 dark:text-red-400 text-xs uppercase mb-1">Error</p>
                        <p class="text-red-800 dark:text-red-300 font-mono text-xs">{{ $selectedLog->error_message }}</p>
                    </div>
                @endif

                @if(!empty($selectedLog->details))
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase mb-1">Detalles Adicionales</p>
                        <ul class="text-gray-800 dark:text-gray-200 list-disc ml-5 space-y-1">
                            @foreach($selectedLog->details as $k => $v)
                                <li>
                                    <span class="font-medium">{{ $k }}:</span> 
                                    <span class="text-gray-600 dark:text-gray-400">{{ is_string($v) ? $v : json_encode($v) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        @else
            <!-- Loading state or empty -->
            <div class="flex justify-center p-4">
               <svg class="animate-spin h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                   <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                   <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
               </svg>
            </div>
        @endif

        <x-slot name="footer">
            <x-beartropy-ui::button
                wire:click="$set('selectedLog', null)"
                color="gray"
                variant="outline"
            >
                Cerrar
            </x-beartropy-ui::button>
        </x-slot>
    </x-beartropy-ui::modal>
</div>
