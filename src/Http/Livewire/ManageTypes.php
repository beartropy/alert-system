<?php

namespace Beartropy\AlertSystem\Http\Livewire;

use Beartropy\AlertSystem\Models\AlertType;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class ManageTypes extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search = '';
    public $name, $editingId = null;
    public $showModal = false;

    protected $rules = [
        'name' => 'required|string|max:255',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Abrir modal para nuevo
    public function showCreate()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    // Abrir modal para editar
    public function showEdit($id)
    {
        $type = AlertType::findOrFail($id);
        $this->editingId = $type->id;
        $this->name = $type->name;
        $this->showModal = true;
        $this->resetErrorBag();
    }

    // Guardar o actualizar
    public function save()
    {
        $this->validate();

        AlertType::updateOrCreate(
            ['id' => $this->editingId],
            ['name' => $this->name]
        );

        $this->resetForm();
        $this->showModal = false;
    }

    // Eliminar
    public function delete($id)
    {
        AlertType::findOrFail($id)->delete();
    }

    // Cerrar modal y limpiar
    public function cancelEdit()
    {
        $this->resetForm();
        $this->showModal = false;
    }

    protected function resetForm()
    {
        $this->reset(['name', 'editingId']);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        $query = AlertType::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $types = $query->orderBy('name')->paginate(10);

        return view('alert-system::livewire.alert-system.manage-types', [
            'types' => $types,
        ])->layout(config('alert-system.layout'));
    }
}
