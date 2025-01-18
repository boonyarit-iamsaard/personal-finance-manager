<?php

namespace App\Filament\Finance\Resources;

use App\Filament\Finance\Resources\TransactionResource\Pages\ManageTransactions;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Models\TransactionType;
use App\Models\Wallet;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

// TODO: improve query performance

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                    ->schema([
                        Select::make('wallet_id')
                            ->relationship('wallet', 'name')
                            ->options(function () {
                                return self::getWalletOptions();
                            })
                            ->native(false)
                            ->required(),
                        Select::make('transaction_type_id')
                            ->label('Type')
                            ->options(function (): array {
                                return self::getTransactionTypeOptions();
                            })
                            ->native(false)
                            ->live(onBlur: true)
                            ->required(),
                        Select::make('transaction_category_id')
                            ->label('Category')
                            ->options(function (Get $get): array {
                                return self::getTransactionCategoryOptions($get);
                            })
                            ->native(false)
                            ->required(),
                        TextInput::make('amount')
                            ->required()
                            ->numeric(),
                        Textarea::make('note')
                            ->rows(3)
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.transactionType.name')
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => Str::upper($state))
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),
                TextColumn::make('wallet.name')
                    ->label('Wallet')
                    ->sortable(),
                TextColumn::make('amount')
                    ->formatStateUsing(fn ($state) => Number::format((int) $state / 100, 2))
                    ->sortable(),
                TextColumn::make('note')
                    ->limit(10)
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
                    ->mutateRecordDataUsing(function (array $data, Transaction $record): array {
                        $data['transaction_type_id'] = $record->category->transaction_type_id;
                        $data['amount'] /= 100;

                        return $data;
                    })
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['amount'] *= 100;

                        return $data;
                    })
                    ->modalWidth('md'),
                DeleteAction::make()
                    ->modalWidth('md')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTransactions::route('/'),
        ];
    }

    /**
     * @return array<int, string>
     */
    private static function getTransactionCategoryOptions(Get $get): array
    {
        $transactionType = $get('transaction_type_id');

        return TransactionCategory::where('transaction_type_id', $transactionType)
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * @return array<int, string>
     */
    private static function getTransactionTypeOptions(): array
    {
        return TransactionType::pluck('name', 'id')
            ->map(function ($item) {
                return Str::upper($item);
            })
            ->toArray();
    }

    /**
     * @return array<int, string>
     */
    private static function getWalletOptions(): array
    {
        return Wallet::where('user_id', auth()->user()->id)
            ->pluck('name', 'id')
            ->toArray();
    }
}
