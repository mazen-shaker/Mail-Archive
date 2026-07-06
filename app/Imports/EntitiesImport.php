<?php

namespace App\Imports;

use App\Models\Entity;
use OpenSpout\Reader\XLSX\Reader; // استخدام المسار الصحيح
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class EntitiesImport
{
    public function import($filePath)
    {
        // 1. إنشاء القارئ بالطريقة الحديثة
        $reader = new Reader();
        $reader->open($filePath);

        $isHeadingRow = true;
        $headings = [];
        $seenNames = [];

        DB::beginTransaction();

        try {
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    // 2. الحصول على الخلايا كـ مصفوفة قيم مباشرة (أحدث طريقة)
                    $rowData = $row->toArray();

                    if ($isHeadingRow) {
                        $headings = $rowData;
                        $isHeadingRow = false;
                        continue;
                    }

                    $data = array_combine($headings, $rowData);

                    // التحقق من الاسم
                    if (isset($data['name']) && in_array($data['name'], $seenNames)) {
                        throw new \Exception("الاسم '{$data['name']}' مكرر داخل ملف الإكسيل.");
                    }

                    $this->validateRow($data);

                    $seenNames[] = $data['name'];

                    Entity::create([
                        'name' => $data['name'],
                    ]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } finally {
            $reader->close(); // استخدام finally لضمان الإغلاق حتى عند حدوث خطأ
        }
    }

    protected function validateRow(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|unique:entities,name',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}