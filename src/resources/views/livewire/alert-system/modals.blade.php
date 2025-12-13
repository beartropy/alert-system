@if($selectedLog ?? false)
    <x-beartropy-ui::modal wire:model="openInfoLogModal" styled>
        <x-slot name="title">
            {{ $selectedLog->type }}
        </x-slot>
        <div class="space-y-3 text-sm">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase">{{ __('alert-system::messages.columns.type') }}</p>
                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $selectedLog->type }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase">{{ __('alert-system::messages.columns.channel') }}</p>
                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $selectedLog->channel }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase">{{ __('alert-system::messages.columns.destination') }}</p>
                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $selectedLog->address }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase">{{ __('alert-system::messages.columns.status') }}</p>
                    <p class="font-medium {{ $selectedLog->status === 'success' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ ucfirst($selectedLog->status) }}
                    </p>
                </div>
            </div>

            <div>
                <p class="text-gray-500 dark:text-gray-400 text-xs uppercase">{{ __('alert-system::messages.columns.subject') }}</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $selectedLog->subject }}</p>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                <p class="text-gray-500 dark:text-gray-400 text-xs uppercase mb-1">{{ __('alert-system::messages.columns.message') }}</p>
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
                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase mb-1">{{ __('alert-system::messages.columns.additional_details') }}</p>
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
    </x-beartropy-ui::modal>
@endif


@if($selectedType ?? false)
    <x-beartropy-ui::modal wire:model="openEditTypeModal" styled>
        <x-slot name="title">
            {{ $typeName}}
        </x-slot>
        <x-beartropy-ui::input label="{{ __('alert-system::messages.columns.name') }}" wire:model="typeName" />
        <x-slot:footer>
            <x-beartropy-ui::button glass gray @click="close()" label="{{ __('alert-system::messages.actions.cancel') }}" />
            <x-beartropy-ui::button outline wire:click="updateType" label="{{ __('alert-system::messages.actions.update') }}" class="ml-2" />
        </x-slot:footer>
    </x-beartropy-ui::modal>
@endif

@if($selectedChannel ?? false)
    <x-beartropy-ui::modal wire:model="openEditChannelModal" styled>
        <x-slot name="title">
            {{ $channelName}}
        </x-slot>
        <x-beartropy-ui::input label="{{ __('alert-system::messages.columns.name') }}" wire:model="channelName" />
        <x-slot:footer>
            <x-beartropy-ui::button glass gray @click="close()" label="{{ __('alert-system::messages.actions.cancel') }}" />
            <x-beartropy-ui::button outline wire:click="updateChannel" label="{{ __('alert-system::messages.actions.update') }}" class="ml-2" />
        </x-slot:footer>
    </x-beartropy-ui::modal>
@endif

@if($selectedRecipient ?? $createRecipient ?? false)
    <x-beartropy-ui::modal wire:model="openEditRecipientModal" :showCloseButton="false">
        <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700">
            <div class="text-lg font-semibold">{{ $recipientAddress }}</div>
            <div><x-beartropy-ui::toggle label-position="left" label="{{ __('alert-system::messages.columns.active') }}" wire:model="recipientIsActive" /></div>
        </div>
        <div class="space-y-3 p-3">
            <x-beartropy-ui::input label="{{ __('alert-system::messages.columns.name') }}" wire:model="recipientAddress" />
            <x-beartropy-ui::select label="{{ __('alert-system::messages.columns.type') }}" wire:model="recipientType" :options="$types" :clearable="false" />
            <x-beartropy-ui::select label="{{ __('alert-system::messages.columns.channel') }}" wire:model="recipientChannel" :options="$channels" :clearable="false" />
            <x-beartropy-ui::input label="{{ __('alert-system::messages.columns.bot') }}" wire:model="recipientBot" hint="{{ __('alert-system::messages.messages.telegram_bot_hint') }}" />
        </div>
        <div class="flex justify-end gap-2 border-t border-gray-200 dark:border-gray-700">
            <x-beartropy-ui::button glass gray @click="close()" label="{{ __('alert-system::messages.actions.cancel') }}" class="mt-3" />
            @if($createRecipient)
                <x-beartropy-ui::button outline wire:click="storeRecipient" label="{{ __('alert-system::messages.actions.create') }}" class="mt-3" />
            @else
                <x-beartropy-ui::button outline wire:click="updateRecipient" label="{{ __('alert-system::messages.actions.update') }}" class="mt-3" />
            @endif
        </div>
    </x-beartropy-ui::modal>
@endif

@if($openDeleteConfirmationModal ?? false)
    <x-beartropy-ui::modal wire:model="openDeleteConfirmationModal" maxWidth="sm" :showCloseButton="false">
        <div class="p-4 text-center">
             <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 mb-4">
                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('alert-system::messages.actions.delete_recipient') }}</h3>
            <div class="mt-2">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('alert-system::messages.messages.confirm_delete_recipient') }}
                </p>
            </div>
            <div class="mt-5 sm:mt-6 flex justify-center gap-3">
                 <x-beartropy-ui::button glass gray @click="close()" label="{{ __('alert-system::messages.actions.cancel') }}" />
                 <x-beartropy-ui::button color="red" wire:click="deleteRecipient" label="{{ __('alert-system::messages.actions.delete') }}" />
            </div>
        </div>
    </x-beartropy-ui::modal>
@endif

