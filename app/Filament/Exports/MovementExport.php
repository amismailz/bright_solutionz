<?php

namespace App\Filament\Exports;


use App\Models\Movement;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class MovementExport extends Exporter
{
    protected static ?string $model = Movement::class;



    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label(__('ID')),
            ExportColumn::make('user.name')->label(__('User')),
            ExportColumn::make('association.name')->label(__('Association')),
            ExportColumn::make('point.name')->label(__('Point')),
            ExportColumn::make('type')->label(__('Type')),
            ExportColumn::make('quantity')->label(__('Quantity')),
            ExportColumn::make('congestion_level')->label(__('Congestion Level')),
            ExportColumn::make('lat')->label(__('Lat')),
            ExportColumn::make('long')->label(__('Long')),
            ExportColumn::make('created_at')
                ->label(__('Created At')),
        ];
    }
    public static function getCompletedNotificationBody(Export $export): string
    {
  

        return "✅ تم الانتهاء من تصدير الحركات. [اضغط هنا لتحميل الملف]";
    }
    public static function getDiskName(): string
    {
        return 'public';
    }

    public static function getDefaultExportOptions(): array
    {
        return [
            'file_disk' => 'public',
        ];
    }

    public static function getDefaultFileName(): string
    {
        return 'movements-export-' . now()->format('Y-m-d_H-i-s') . '.xlsx';
    }

    public static function onCompleted(Export $export): void
    {
        $url = Storage::disk($export->file_disk)->url($export->file_path);

        Notification::make()
            ->title('📁 تم الانتهاء من التصدير')
            //->body("✅ اضغط [هنا لتحميل الملف]($url)")
            ->success()
            ->sendToDatabase($export->user);
    }

    public static function onFailure(Export $export, \Throwable $exception): void
    {
        \Log::error('❌ فشل تصدير الحركات:', [
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
