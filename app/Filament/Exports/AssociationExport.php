<?php

namespace App\Filament\Exports;

use App\Models\Association;

use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class AssociationExport extends Exporter
{
    protected static ?string $model = Association::class;



    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label(__('ID')),
            ExportColumn::make('name')->label(__('Name')),
            ExportColumn::make('contact_person')->label(__('Contact person')),
            ExportColumn::make('email')->label(__('Email')),
            ExportColumn::make('mobile')->label(__('Mobile')),
            ExportColumn::make('created_at')->label(__('Created At')),
        ];
    }
    public static function getCompletedNotificationBody(Export $export): string
    {


        return "✅ تم الانتهاء من تصدير الجمعيات. [اضغط هنا لتحميل الملف]";
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
        return 'associations-export-' . now()->format('Y-m-d_H-i-s') . '.xlsx';
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
        \Log::error('❌ فشل تصدير الجمعيات:', [
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
