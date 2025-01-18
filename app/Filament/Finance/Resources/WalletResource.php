<?php

namespace App\Filament\Finance\Resources;

use App\Filament\Finance\Resources\WalletResource\Pages\ManageWallets;
use App\Models\Wallet;
use Exception;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Exceptions\Halt;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Str;

class WalletResource extends Resource
{
    protected static ?string $model = Wallet::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->minLength(3)
                    ->maxLength(50),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('icon')
                    ->icon(fn (Wallet $record): string => $record->icon),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['slug'] = Str::slug($data['name']);

                        return $data;
                    })
                    ->using(function (EditAction $action, array $data, Wallet $record) {
                        return self::updateWallet($action, $data, $record);
                    }),
                DeleteAction::make()
                    ->modalHeading('All associated transactions will be deleted')
                    ->modalDescription(
                        'If you delete this wallet, all transactions linked to it will be permanently removed. This action cannot be undone.'
                    )
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('user_id', auth()->id());
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageWallets::route('/'),
        ];
    }

    /**
     * @param array{
     *     name: string,
     *     slug: string,
     * } $data
     * @return Wallet|void
     *
     * @throws Halt
     */
    private static function updateWallet(EditAction $action, array $data, Wallet $record)
    {
        try {
            $record->update($data);

            return $record;
        } catch (Exception $e) {
            $message = 'An unknown error occurred, please try again later';

            if ($e instanceof UniqueConstraintViolationException) {
                $message = 'Wallet with this name already exists';
            }

            Notification::make()
                ->title('Unable to update wallet')
                ->body($message)
                ->danger()
                ->send();

            $action->halt();
        }
    }
}
