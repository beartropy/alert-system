<?php

namespace Beartropy\AlertSystem\Http\Livewire;

use Beartropy\AlertSystem\Models\AlertRecipient;
use Beartropy\AlertSystem\Models\AlertType;
use Beartropy\AlertSystem\Models\AlertChannel;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class ManageRecipients extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search = '';
    public $type_id, $channel_id, $address, $bot, $is_active = true;
    public $editingId = null;
    public $showModal = false;
    public $types, $channels;

    protected $rules = [
        'type_id' => 'required|exists:alert_types,id',
        'channel_id' => 'required|exists:alert_channels,id',
        'address' => 'required|string|max:255',
        'bot' => 'nullable|string|max:255',
        'is_active' => 'boolean',
    ];

    protected $queryString = ['search'];

    public function mount()
    {
        $this->types = AlertType::all();
        $this->channels = AlertChannel::all();
    }

    // NUEVO: abrir modal para crear
    public function showCreate()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    // NUEVO: abrir modal para editar
    public function showEdit($id)
    {
        $this->resetErrorBag();
        $recipient = AlertRecipient::findOrFail($id);
        $this->editingId = $recipient->id;
        $this->type_id = $recipient->alert_type_id;
        $this->channel_id = $recipient->alert_channel_id;
        $this->address = $recipient->address;
        $this->bot = $recipient->bot;
        $this->is_active = $recipient->is_active;
        $this->showModal = true;
    }

    // NUEVO: cerrar modal y resetear
    public function cancelEdit()
    {
        $this->resetForm();
        $this->showModal = false;
    }

    // Nuevo: guardar (crear o actualizar)
    public function save()
    {
        $this->validate();

        AlertRecipient::updateOrCreate(
            ['id' => $this->editingId],
            [
                'alert_type_id' => $this->type_id,
                'alert_channel_id' => $this->channel_id,
                'address' => $this->address,
                'bot' => $this->bot,
                'is_active' => $this->is_active,
            ]
        );

        $this->resetForm();
        $this->showModal = false;
    }

    public function delete($id)
    {
        AlertRecipient::findOrFail($id)->delete();
    }

    // Nuevo: resetear formulario y errores
    protected function resetForm()
    {
        $this->reset(['type_id', 'channel_id', 'address', 'bot', 'is_active', 'editingId']);
        $this->is_active = true;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = AlertRecipient::with('type', 'channel');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('address', 'like', '%' . $this->search . '%')
                  ->orWhereHas('type', fn($t) => $t->where('name', 'like', '%' . $this->search . '%'))
                  ->orWhereHas('channel', fn($c) => $c->where('name', 'like', '%' . $this->search . '%'));
            });
        }

        $recipients = $query->orderByDesc('id')->paginate(10);

        return view('alert-system::livewire.alert-system.manage-recipients', [
            'recipients' => $recipients,
            'types' => $this->types,
            'channels' => $this->channels,
        ])->layout(config('alert-system.layout'));
    }
}
