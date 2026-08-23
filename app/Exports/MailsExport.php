<?php

namespace App\Exports;

use App\Models\Mail;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Cell;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MailsExport
{
    public function export(array $ids): BinaryFileResponse
    {
        if (empty($ids)) {
            abort(400, 'لم يتم تحديد أي بيانات للتصدير.');
        }

        $mails = Mail::with([
            'entity:id,name',
            'status:id,name',
        ])
            ->whereIn('id', $ids)
            ->get();

        if ($mails->isEmpty()) {
            abort(404, 'لا توجد بيانات للتصدير.');
        }

        $directory = storage_path('app/temp');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $fileName = 'mails_' . now()->format('Ymd_His') . '.xlsx';
        $filePath = $directory . '/' . $fileName;

        $writer = new Writer();
        $writer->openToFile($filePath);

        // رؤوس الأعمدة
        $writer->addRow(new Row([
            Cell::fromValue('العنوان'),
            Cell::fromValue('التوقيع'),
            Cell::fromValue('الوصف'),
            Cell::fromValue('نوع الملف'),
            Cell::fromValue('الجهة'),
            Cell::fromValue('الحالة'),
            Cell::fromValue('تاريخ الإنشاء'),
        ]));

        // البيانات
        foreach ($mails as $mail) {
            $writer->addRow(new Row([
                Cell::fromValue($mail->title),
                Cell::fromValue($mail->sign ?? ''),
                Cell::fromValue($mail->description ?? ''),
                Cell::fromValue($mail->file_type),
                Cell::fromValue($mail->entity?->name ?? ''),
                Cell::fromValue($mail->status?->name ?? ''),
                Cell::fromValue(
                    $mail->created_at?->format('Y-m-d H:i:s') ?? ''
                ),
            ]));
        }

        $writer->close();

        return response()
            ->download($filePath, $fileName)
            ->deleteFileAfterSend(true);
    }
}
