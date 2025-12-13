<?php

namespace Beartropy\AlertSystem\Livewire;

use Beartropy\Tables\YATBaseTable;
use Beartropy\AlertSystem\Models\AlertType;
use Beartropy\Tables\Exports\GenericExport;
use Beartropy\Tables\Classes\Columns\Column;
use Beartropy\AlertSystem\Models\AlertChannel;
use Beartropy\AlertSystem\Models\AlertRecipient;
use Beartropy\Tables\Classes\Columns\BoolColumn;


class ManageRecipients extends YATBaseTable
{
    public $tableName = 'Destinatarios';
    public $model = AlertRecipient::class;

    public ?AlertRecipient $selectedRecipient;
    public $createRecipient = false;
    public $openEditRecipientModal = false;
    public $recipientType;
    public $recipientChannel;
    public $recipientAddress;
    public $recipientBot;
    public $recipientIsActive;
    public $types;
    public $channels;

    public function settings(): void {
        $this->setTitle('Destinatarios');
        $this->setLayout(config('alert-system.layout'));
        $this->setModalsView('alert-system::livewire.alert-system.modals');
        $this->showCounter(false);
        $this->addButtons([
            [
                'label' => 'Crear destinatario',
                'color' => 'emerald',
                'action' => 'openNewRecipientModal',
            ],
        ]);

        $this->types = AlertType::all();
        $this->channels = AlertChannel::all();
    }

    public function columns(): array {
        return [
            Column::make('Id','id')
                ->isVisible(false)
                ->collapseOnMobile(true),
            Column::make('Tipo','type.name'),
            Column::make('Canal','channel.name'),
            Column::make('Direccion','address'),
            Column::make('Bot','bot'),
            BoolColumn::make('Activo','is_active'),
            Column::make('#')->customData(function ($row, $value) {
                return '
                <span wire:click="editRecipient('.$row->id.')">
                    <svg class="w-5 h-5 text-blue-400 dark:text-blue-600 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </span>
                <span wire:click="confirmDeleteRecipient('.$row->id.')" class="ml-1">
                    <svg class="w-5 h-5 text-red-400 dark:text-red-600 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
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
            'recipientType' => 'required|exists:alert_types,id',
            'recipientChannel' => 'required|exists:alert_channels,id',
            'recipientAddress' => 'required|string|max:255',
            'recipientBot' => 'nullable|string|max:255',
            'recipientIsActive' => 'required|boolean',
        ];
    }

    public function openNewRecipientModal() {
        $this->reset(['selectedRecipient', 'recipientAddress', 'recipientBot', 'recipientType', 'recipientChannel']);
        $this->recipientIsActive = true;
        $this->createRecipient = true;
        $this->openEditRecipientModal = true;
    }

    public function storeRecipient() {
        $this->validate();
        $recipient = AlertRecipient::create([
            'alert_type_id' => $this->recipientType,
            'alert_channel_id' => $this->recipientChannel,
            'address' => $this->recipientAddress,
            'bot' => $this->recipientBot,
            'is_active' => $this->recipientIsActive,
        ]);
        
        $this->addRowToTable($recipient->toArray()); // Assuming YATBaseTable has this or refreshes
        // If addRowToTable expects specific format, we might need to load relations.
        // Actually, easiest to just refresh purely or add correctly.
        // AlertRecipient::create returns model.
        // Ideally we fetching fresh data for row.
        
        $this->openEditRecipientModal = false;
        $this->refresh(); // Simple refresh to ensure data consistency
    }

    public function editRecipient($id) {
        $this->createRecipient = false;
        $this->selectedRecipient = AlertRecipient::find($id);
        $this->recipientType = $this->selectedRecipient->type->id; // Careful here if relation missing
        $this->recipientChannel = $this->selectedRecipient->channel->id;
        $this->recipientAddress = $this->selectedRecipient->address;
        $this->recipientBot = $this->selectedRecipient->bot;
        $this->recipientIsActive = $this->selectedRecipient->is_active ? true : false;
        
        $this->openEditRecipientModal = true;
    }

    public function updateRecipient() {
        $this->validate();  
        $this->selectedRecipient->alert_type_id  = $this->recipientType;
        $this->selectedRecipient->alert_channel_id = $this->recipientChannel;
        $this->selectedRecipient->address = $this->recipientAddress;
        $this->selectedRecipient->bot = $this->recipientBot;
        $this->selectedRecipient->is_active = $this->recipientIsActive;
        $this->selectedRecipient->save();

        $this->openEditRecipientModal = false;
        $this->refresh();
    }

    public $openDeleteConfirmationModal = false;
    public $recipientToDeleteId;

    public function confirmDeleteRecipient($id) {
        $this->recipientToDeleteId = $id;
        $this->openDeleteConfirmationModal = true;
    }

    public function deleteRecipient() {
        if ($this->recipientToDeleteId) {
            $recipient = AlertRecipient::find($this->recipientToDeleteId);
            if ($recipient) {
                 $recipient->delete();
                 $this->removeRowFromTable($this->recipientToDeleteId);
            }
        }
        $this->openDeleteConfirmationModal = false;
        $this->recipientToDeleteId = null;
    }

    public function options(): array {
        return [
            "export_selected" => ["label"=>"Export selected rows", "icon"=> "☑️"],
            "export_filtered" => ["label"=>"Export filtered rows", "icon"=> "🔍"],
            "export_all" => ["label"=>"Export all rows", "icon"=> "📗"]
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