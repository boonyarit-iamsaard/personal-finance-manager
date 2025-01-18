<?php

namespace App\Filament\Finance\Resources\WalletResource\Pages;

use App\Filament\Finance\Resources\WalletResource;
use App\Models\Wallet;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Str;

class ManageWallets extends ManageRecords
{
    protected static string $resource = WalletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->createAnother(false)
                ->mutateFormDataUsing(function (array $data): array {
                    $data['icon'] = 'tabler-pig-money';
                    $data['slug'] = Str::slug($data['name']);
                    $data['user_id'] = auth()->id();

                    return $data;
                })
                ->using(function (Action $action, array $data): Wallet {
                    return $this->createWallet($action, $data);
                }),
        ];
    }

    /**
     * @param array{
     *     name: string,
     *     slug: string,
     *     user_id: int,
     * } $data
     * @return Wallet|void
     *
     * @throws Halt
     */
    private function createWallet(Action $action, array $data)
    {
        try {
            return Wallet::create($data);
        } catch (Exception $e) {
            $message = 'An unknown error occurred, please try again later';

            if ($e instanceof UniqueConstraintViolationException) {
                $message = 'Wallet with this name already exists';
            }

            Notification::make()
                ->title('Unable to create wallet')
                ->body($message)
                ->danger()
                ->send();

            $action->halt();
        }
    }
}
