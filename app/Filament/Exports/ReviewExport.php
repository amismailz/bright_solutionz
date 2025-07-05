<?php

namespace App\Filament\Exports;


use App\Models\Movement;
use App\Models\Point;
use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class ReviewExport extends Exporter
{
    protected static ?string $model = Review::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label(__('ID')),
            ExportColumn::make('user.name')->label(__('User')),
            ExportColumn::make('point.name')->label(__('Point')),
            ExportColumn::make('range.name')->label(__('Range')),
            ExportColumn::make('latitude')->label(__('Latitude')),
            ExportColumn::make('longitude')->label(__('Longitude')),
            // ExportColumn::make('description')->label(__('Description')),
            ExportColumn::make('average_rating')->label(__('Average Rating')),

            ExportColumn::make('review_standards')
                ->label(__('Standards'))
                ->getStateUsing(function ($record) {
                    return $record->reviewerStandards
                        ->filter(fn($standard) => $standard->standard) // Ensure relation exists
                        ->map(function ($standard) {
                            return $standard->standard->name . ': ' . __('Rate') . ': ' . $standard->rate;
                        })
                        ->implode(' | ');
                }),

            ExportColumn::make('review_standards_description')
                ->label(__('Standards Description'))
                ->getStateUsing(function ($record) {
                    return $record->reviewerStandards
                        ->filter(fn($standard) => $standard->standard)
                        ->map(function ($standard) {
                            return $standard->standard->name . ': ' . $standard->description;
                        })
                        ->implode(' | ');
                }),
            ExportColumn::make('created_at')->label(__('Created At')),
        ];
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
        return 'reviews-export-' . now()->format('Y-m-d_H-i-s') . '.xlsx';
    }
    public static function getCompletedNotificationBody(Export $export): string
    {
        return '✅ تم تصدير المراجعات بنجاح. [اضغط هنا لتحميل الملف]';
    }
    public static function onCompleted(Export $export): void
    {
        $url = Storage::disk($export->file_disk)->url($export->file_path);

        Notification::make()
            ->title('✅ Export Completed')
            //->body("Click [here to download]($url)")
            ->success()
            ->sendToDatabase($export->user);
    }

    public static function onFailure(Export $export, \Throwable $exception): void
    {
        \Log::error('Review export failed:', [
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
