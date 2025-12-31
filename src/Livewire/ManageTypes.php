<?php

namespace Beartropy\AlertSystem\Livewire;

use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use Beartropy\Tables\YATBaseTable;
use Illuminate\Support\Facades\Auth;
use Beartropy\Tables\Exports\GenericExport;
use Beartropy\Tables\Classes\Columns\Column;
use Beartropy\Tables\Classes\Columns\BoolColumn;
use Beartropy\Tables\Classes\Columns\LinkColumn;
use Beartropy\Tables\Classes\Filters\FilterBool;
use Beartropy\Tables\Classes\Filters\FilterString;
use Beartropy\Tables\Classes\Filters\FilterDateRange;
use Beartropy\Tables\Classes\Filters\FilterSelectMagic;
use Beartropy\AlertSystem\Models\AlertType;

/**
 * Livewire component to manage Alert Types.
 *
 * This component provides a table view of available alert types with editing capabilities.
 * It uses the Beartropy Tables package for rendering and filtering.
 *
 * @property string $tableName The name of the table displayed
 * @property string $model The model class associated with the table
 * @property \Beartropy\AlertSystem\Models\AlertType|null $selectedType The currently selected alert type for editing
 * @property bool $openEditTypeModal Whether the edit modal is open
 * @property string $typeName The name of the alert type being edited
 */
class ManageTypes extends YATBaseTable
{

    public $tableName;
    public $model = AlertType::class;

    public ?AlertType $selectedType;
    public $openEditTypeModal = false;
    public $typeName;

    /**
     * Configure the table settings.
     *
     * @return void
     */
    public function settings(): void
    {
        $this->tableName = trans('alert-system::messages.alert_types');
        $this->setTitle(trans('alert-system::messages.alert_types'));
        $this->setLayout(config('alert-system.layout'));
        $this->setModalsView('alert-system::livewire.alert-system.modals');
        $this->showCounter(false);
    }

    /**
     * Define the columns for the table.
     *
     * @return array<int, \Beartropy\Tables\Classes\Columns\Column>
     */
    public function columns(): array
    {
        return [
            Column::make(trans('alert-system::messages.columns.id'), 'id')
                ->thStyling('justify-start')
                ->styling('justify-start'),
            Column::make(trans('alert-system::messages.columns.name'), 'name')->thStyling('w-full')->styling('w-full'),
            Column::make('#')->customData(function ($row, $value) {
                return '<span wire:click="editType(' . $row->id . ')">
                    <svg class="w-5 h-5 text-blue-400 dark:text-blue-600 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </span>';
            })
                ->toHtml()
                ->thStyling('flex justify-end')
                ->styling('flex justify-end')
        ];
    }

    /**
     * Define the filters for the table.
     *
     * @return array
     */
    public function filters(): array
    {
        return [];
    }

    /**
     * Define validation rules.
     *
     * @return array<string, string>
     */
    protected function rules()
    {
        return [
            'typeName' => 'required|string|max:255',
            'selectedType.name' => 'required|string|max:255',
        ];
    }

    /**
     * Open the modal to edit an alert type.
     *
     * @param int|string $id The ID of the alert type to edit
     * @return void
     */
    public function editType($id)
    {
        $this->selectedType = AlertType::find($id);
        $this->typeName = $this->selectedType->name;
        $this->openEditTypeModal = true;
    }

    /**
     * Update the selected alert type.
     *
     * @return void
     */
    public function updateType()
    {
        $this->validate();
        $this->selectedType->name = $this->typeName;
        $this->selectedType->save();
        $this->openEditTypeModal = false;
    }

    /**
     * Define the bulk actions available for the table.
     *
     * @return array<string, array<string, string>>
     */
    public function options(): array
    {
        return [
            "export_selected" => ["label" => trans('alert-system::messages.actions.export_selected'), "icon" => "☑️"],
            "export_filtered" => ["label" => trans('alert-system::messages.actions.export_filtered'), "icon" => "🔍"],
            "export_all" => ["label" => trans('alert-system::messages.actions.export_all'), "icon" => "📗"]
        ];
    }

    /**
     * Export all data to Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export_all()
    {
        $allData = $this->getAllData();
        return \Maatwebsite\Excel\Facades\Excel::download(new GenericExport($allData, $strip_tags = true), $this->tableName . '.xlsx');
    }

    /**
     * Export filtered data to Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export_filtered()
    {
        $filteredData = $this->getAfterFiltersData();
        return \Maatwebsite\Excel\Facades\Excel::download(new GenericExport($filteredData, $strip_tags = true), $this->tableName . '.xlsx');
    }

    /**
     * Export selected rows to Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|void
     */
    public function export_selected()
    {
        $selected_rows = $this->getSelectedData();
        if ($selected_rows) {
            return \Maatwebsite\Excel\Facades\Excel::download(new GenericExport($selected_rows, $strip_tags = true), $this->tableName . '.xlsx');
        }
    }
}
