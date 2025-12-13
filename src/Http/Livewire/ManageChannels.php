<?php

namespace Beartropy\AlertSystem\Http\Livewire;

use Beartropy\AlertSystem\Models\AlertChannel;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class ManageChannels extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $name, $editingId = null, $showModal = false, $search = '';
    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'name' => 'required|string|max:255',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showCreate()
    {
        $this->resetInput();
        $this->showModal = true;
    }

    public function showEdit($id)
    {
        $this->edit($id);
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        AlertChannel::updateOrCreate(
            ['id' => $this->editingId],
            ['name' => $this->name]
        );

        $this->resetInput();
        $this->showModal = false;
        session()->flash('message', 'Channel ' . ($this->editingId ? 'updated' : 'added') . ' successfully.');
    }

    public function edit($id)
    {
        $channel = AlertChannel::findOrFail($id);
        $this->editingId = $channel->id;
        $this->name = $channel->name;
    }

    public function delete($id)
    {
        AlertChannel::findOrFail($id)->delete();
        session()->flash('message', 'Channel deleted.');
    }

    public function cancelEdit()
    {
        $this->resetInput();
        $this->showModal = false;
    }

    private function resetInput()
    {
        $this->name = '';
        $this->editingId = null;
        $this->resetValidation();
    }

    public function render()
    {
        $channels = AlertChannel::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy('name')
            ->paginate(10);

        return view('alert-system::livewire.alert-system.manage-channels', [
            'channels' => $channels
        ])->layout(config('alert-system.layout'));
    }
}
