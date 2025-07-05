<?php

namespace App\Filament\Exports;


use App\Models\Movement;
use App\Models\Point;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class PointExport extends Exporter
{
    protected static ?string $model = Point::class;



    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label(__('ID')),
            ExportColumn::make('name')->label(__('Name')),
            ExportColumn::make('range.name')->label(__('Range')),
            ExportColumn::make('association.name')->label(__('Association')),
            ExportColumn::make('url')->label(__('URL')),
            ExportColumn::make('storage_capacity')->label(__('Storage Capacity')),
            ExportColumn::make('lat')->label(__('Latitude')),
            ExportColumn::make('long')->label(__('Longitude')),
            ExportColumn::make('created_at')->label(__('Created At')),
        ];
    }
    public static function getCompletedNotificationBody(Export $export): string
    {


        return "✅ تم الانتهاء من تصدير مراكز التوزيع. [اضغط هنا لتحميل الملف]";
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
        return 'points-export-' . now()->format('Y-m-d_H-i-s') . '.xlsx';
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
        \Log::error('❌ فشل تصدير مراكز التوزيع:', [
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
