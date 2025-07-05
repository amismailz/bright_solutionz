<?php

namespace App\Filament\Exports;

use App\Models\Evaluation;
use App\Models\Movement;
use App\Models\Point;
use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class EvaluationExport  extends Exporter
{
    protected static ?string $model = Evaluation::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label(__('ID')),
            ExportColumn::make('user.name')->label(__('User')),
            ExportColumn::make('point.name')->label(__('Point')),
            ExportColumn::make('range.name')->label(__('Range')),
            ExportColumn::make('average_rating')->label(__('Average Rating')),
           // ExportColumn::make('note')->label('Note'),
            ExportColumn::make('evaluation_standards')
                ->label(__('Standards'))
                ->getStateUsing(function ($record) {
                    return $record->evaluationStandards
                        ->filter(fn($standard) => $standard->standard) // ensure related standard exists
                        ->map(function ($standard) {
                            return $standard->standard->getTranslation('name', app()->getLocale()) . ': ' . __('Rate') . ': ' . $standard->rate;
                        })
                        ->implode(' | ');
                }),

            ExportColumn::make('evaluation_standards_description')
                ->label(__('Standards Description'))
                ->getStateUsing(function ($record) {
                    return $record->evaluationStandards
                        ->filter(fn($standard) => $standard->standard)
                        ->map(function ($standard) {
                            return $standard->standard->getTranslation('name', app()->getLocale()) . ': ' . $standard->description;
                        })
                        ->implode(' | ');
                }),
            ExportColumn::make('created_at')->label(__('Created At')),

        ];
    }


    public static function getCompletedNotificationBody(Export $export): string
    {
        return "✅ تم الانتهاء من تصدير تقيمات الزوار. [اضغط هنا لتحميل الملف]";
    }

    public static function getDiskName(): string
    {
        return 'public';
    }

    public static function getDefaultFileName(): string
    {
        return 'evaluations-export-' . now()->format('Y-m-d_H-i-s') . '.xlsx';
    }

    public static function onCompleted(Export $export): void
    {
        $url = Storage::disk($export->file_disk)->url($export->file_path);

        Notification::make()
            ->title('📁 تم الانتهاء من التصدير')
            ->success()
            ->sendToDatabase($export->user);
    }

    public static function onFailure(Export $export, \Throwable $exception): void
    {
        \Log::error('❌ فشل تصدير تقيمات الزوار:', [
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
