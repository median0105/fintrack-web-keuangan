<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Date;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $label = 'Data Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->required()
                    ->relationship('category','namae'),
                Forms\Components\DatePicker::make('date_trans')
                    ->label('Tanggal')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('Harga')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('note')
                    ->label('Catatan')
                    ->maxLength(255)
                    ->required(),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        Carbon::setLocale('id');
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('category.image')
                    ->label('Kategori'),
                Tables\Columns\TextColumn::make('category.namae')
                    ->description(fn (Transaction $record)   : string => $record->name)
                    ->label('Transaksi')
                    ->searchable(),
                Tables\Columns\IconColumn::make('category.is_expense')
                    ->label('Tipe')
                    ->searchable()
                    ->boolean()
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->trueIcon('heroicon-o-arrow-up')
                    ->falseIcon('heroicon-o-arrow-down'),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Harga')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('note')
                    ->label('Catatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_trans')
                    ->label('Tanggal')
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        return $state ? Carbon::parse($state)->translatedFormat('d F Y') : 'N/A'; 
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
            
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }    
}
