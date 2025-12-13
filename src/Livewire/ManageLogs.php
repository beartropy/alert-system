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
use Beartropy\AlertSystem\Models\AlertLog;

class ManageLogs extends YATBaseTable
{

    public $tableName;
    public $model = AlertLog::class;

    public $selectedLog;
    public $openInfoLogModal = false;

    public function settings(): void {
        $this->tableName = trans('alert-system::messages.logs');
        $this->setTitle(trans('alert-system::messages.logs'));
        $this->setLayout(config('alert-system.layout'));
        $this->setModalsView('alert-system::livewire.alert-system.modals');
    }

    public function columns(): array {
        return [
            Column::make(trans('alert-system::messages.columns.id'),'id')
                ->isVisible(false)
                ->collapseOnMobile(true),
            Column::make(trans('alert-system::messages.columns.type'),'type'),
            Column::make(trans('alert-system::messages.columns.channel'),'channel')
                ->collapseOnMobile(true),
            Column::make(trans('alert-system::messages.columns.address'),'address'),
            Column::make(trans('alert-system::messages.columns.bot'),'bot')
                ->collapseOnMobile(true),
            Column::make(trans('alert-system::messages.columns.status'),'status')
                ->collapseOnMobile(true),
            Column::make(trans('alert-system::messages.columns.sent_at'),'sent_at')
                ->collapseOnMobile(true),
            Column::make('#')->customData(function ($row, $value) {
                return '<span wire:click="showDetails('.$row->id.')">
                    <span class="block md:hidden text-blue-400 dark:text-blue-600 cursor-pointer">'.trans('alert-system::messages.actions.view_more').'</span>
                    <svg class="w-5 h-5 text-blue-400 dark:text-blue-600 cursor-pointer hidden md:block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M1 12S5 4 12 4s11 8 11 8-4 8-11 8S1 12 1 12Z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </span>';
            })
            ->toHtml()
            ->thStyling('flex justify-end')
            ->styling('flex justify-end')
            ->collapseOnMobile(true)
        ];
    }

    public function filters(): array {
        return [
            FilterSelectMagic::make(trans('alert-system::messages.columns.status'), 'status'),
            FilterSelectMagic::make(trans('alert-system::messages.columns.type'), 'type'),
            FilterSelectMagic::make(trans('alert-system::messages.columns.channel'), 'channel'),
            FilterDateRange::make(trans('alert-system::messages.columns.sent_at'), 'sent_at'),
        ];
    }

    public function showDetails($id) {
        $this->selectedLog = AlertLog::find($id);
        $this->openInfoLogModal = true;
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