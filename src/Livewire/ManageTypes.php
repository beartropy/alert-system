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

class ManageTypes extends YATBaseTable
{

    public $tableName;
    public $model = AlertType::class;

    public ?AlertType $selectedType;
    public $openEditTypeModal = false;
    public $typeName;

    public function settings(): void {
        $this->tableName = trans('alert-system::messages.alert_types');
        $this->setTitle(trans('alert-system::messages.alert_types'));
        $this->setLayout(config('alert-system.layout'));
        $this->setModalsView('alert-system::livewire.alert-system.modals');
        $this->showCounter(false);  
    }

    public function columns(): array {
        return [
            Column::make(trans('alert-system::messages.columns.id'),'id')
                ->thStyling('justify-start')
                ->styling('justify-start'),
            Column::make(trans('alert-system::messages.columns.name'),'name')->thStyling('w-full')->styling('w-full'),
            Column::make('#')->customData(function ($row, $value) {
                return '<span wire:click="editType('.$row->id.')">
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

    public function filters(): array {
        return [];
    }

    protected function rules() {
        return [
            'typeName' => 'required|string|max:255',
            'selectedType.name' => 'required|string|max:255',
        ];
    }

    public function editType($id) {
        $this->selectedType = AlertType::find($id);
        $this->typeName = $this->selectedType->name;
        $this->openEditTypeModal = true;
    }

    public function updateType() {
        $this->validate();
        $this->selectedType->name = $this->typeName;
        $this->selectedType->save();
        $this->openEditTypeModal = false;
    }

    public function options(): array {
        return [
            "export_selected" => ["label"=>trans('alert-system::messages.actions.export_selected'), "icon"=> "☑️"],
            "export_filtered" => ["label"=>trans('alert-system::messages.actions.export_filtered'), "icon"=> "🔍"],
            "export_all" => ["label"=>trans('alert-system::messages.actions.export_all'), "icon"=> "📗"]
        ];
    }

    public function export_all() {
        $allData = $this->getAllData();
        return \Maatwebsite\Excel\Facades\Excel::download(new GenericExport($allData,$strip_tags = true), $this->tableName.'.xlsx');
    }

    public function export_filtered() {
        $filteredData = $this->getAfterFiltersData();
        return \Maatwebsite\Excel\Facades\Excel::download(new GenericExport($filteredData, $strip_tags = true), $this->tableName.'.xlsx');
    }

    public function export_selected() {
        $selected_rows = $this->getSelectedData();
        if ($selected_rows) {
            return \Maatwebsite\Excel\Facades\Excel::download(new GenericExport($selected_rows, $strip_tags = true), $this->tableName.'.xlsx');
        }
    }

}