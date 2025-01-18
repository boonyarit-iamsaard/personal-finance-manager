<?php

namespace App\Filament\Finance\Resources\TransactionResource\Pages;

use App\Filament\Finance\Resources\TransactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTransactions extends ManageRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['user_id'] = auth()->user()->id;
                    $data['amount'] *= 100;

                    return $data;
                })
                ->createAnother(false)
                ->modalWidth('md'),
        ];
    }
}
