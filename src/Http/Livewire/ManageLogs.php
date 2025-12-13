<?php

namespace Beartropy\AlertSystem\Http\Livewire;


use Beartropy\Tables\YATBaseTable;
use Beartropy\Tables\Classes\Columns\Column;
use Beartropy\AlertSystem\Models\AlertLog;

class ManageLogs extends YATBaseTable
{
    public $statusFilter = '';
    public $typeFilter = '';
    public $channelFilter = '';
    public $search = ''; // Overrides or implements Search trait property if needed, mostly handled by YAT

    public $selectedLog = null;

    // Lists for the select filters
    public $statuses = [
        ['id' => '', 'label' => 'Todos los estados'],
        ['id' => 'success', 'label' => 'Success'],
        ['id' => 'failure', 'label' => 'Failure'],
    ];
    public $types = [];
    public $channels = [];

    public function mount()
    {
        $this->types = array_merge([['id' => '', 'label' => 'Todos los tipos']], AlertLog::select('type')->distinct()->pluck('type')->map(fn($t) => ['id' => $t, 'label' => $t])->toArray());
        $this->channels = array_merge([['id' => '', 'label' => 'Todos los canales']], AlertLog::select('channel')->distinct()->pluck('channel')->map(fn($c) => ['id' => $c, 'label' => $c])->toArray());

        // Initialize YAT
        parent::mount();
        
        // Settings
        $this->setCustomHeader(view('alert-system::livewire.alert-system.parts.header', [
            'statuses' => $this->statuses,
            'types' => $this->types,
            'channels' => $this->channels
        ])->render());
        
        $this->setModalsView('alert-system::livewire.alert-system.parts.modals');
    }

    public function data()
    {
        return AlertLog::query()
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('type', 'like', "%{$this->search}%")
                        ->orWhere('channel', 'like', "%{$this->search}%")
                        ->orWhere('address', 'like', "%{$this->search}%")
                        ->orWhere('status', 'like', "%{$this->search}%")
                        ->orWhere('subject', 'like', "%{$this->search}%")
                        ->orWhere('message', 'like', "%{$this->search}%");
                });
            })
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->typeFilter, fn($q) => $q->where('type', $this->typeFilter))
            ->when($this->channelFilter, fn($q) => $q->where('channel', $this->channelFilter))
            ->latest()
            ->get();
    }

    public function columns(): array
    {
        return [
            Column::make('Tipo', 'type')->sortColumnBy('type'),
            Column::make('Canal', 'channel')->sortColumnBy('channel'),
            Column::make('Destino', 'address')->sortColumnBy('address'),
            Column::make('Bot', 'bot'),
            
            Column::make('Estado', 'status')
                ->view('alert-system::livewire.alert-system.columns.status')
                ->sortColumnBy('status'),

            Column::make('Enviado', 'sent_at')
                ->customData(fn($row) => $row->sent_at ? $row->sent_at->format('Y-m-d H:i') : '')
                ->sortColumnBy('sent_at'),

            Column::make('Acciones', 'actions')
                ->view('alert-system::livewire.alert-system.columns.actions')
                ->index('id') // Utilize ID for actions
        ];
    }

    public function showDetails($logId)
    {
        $this->selectedLog = AlertLog::findOrFail($logId);
    }

    // Override render to apply layout if needed, though YATBaseTable::render returns the view.
    // We need to wrap it with the layout.
    public function render()
    {
        // Re-render header to keep filters reactive (e.g. if we wanted lists to change)
        // But mainly to pass current values if header needed them. 
        // Our header uses wire:model to this component's properties, so strictly only lists need to be passed if dynamic.
        // Static lists passed in mount are fine.
        
        return parent::render()->layout(config('alert-system.layout'));
    }
}
