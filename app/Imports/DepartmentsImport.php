<?php

namespace App\Imports;

use App\Models\Department;
use OpenSpout\Reader\XLSX\Reader;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class DepartmentsImport
{
    public function import($filePath)
    {
        $reader = new Reader();
        $reader->open($filePath);

        $isHeadingRow = true;
        $headings = [];
        $seenNames = [];
        $seenCodes = [];

        DB::beginTransaction();

        try {
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    // استخدام toArray() وهي الطريقة القياسية في OpenSpout 4
                    $rowData = $row->toArray();

                    if ($isHeadingRow) {
                        $headings = $rowData;
                        $isHeadingRow = false;
                        continue;
                    }

                    // دمج البيانات مع العناوين (يجب التأكد أن ملف الإكسيل مطابق للترتيب)
                    $data = array_combine($headings, $rowData);

                    // 1. التحقق من التكرار داخل ملف الإكسيل نفسه
                    if (in_array($data['name'], $seenNames) || in_array($data['code'], $seenCodes)) {
                        throw new \Exception("يوجد بيانات مكررة داخل ملف الإكسيل: " . $data['name'] . " أو " . $data['code']);
                    }

                    // 2. التحقق من القواعد وقاعدة البيانات
                    $this->validateRow($data);

                    $seenNames[] = $data['name'];
                    $seenCodes[] = $data['code'];

                    Department::create([
                        'name' => $data['name'],
                        'code' => $data['code'],
                    ]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } finally {
            $reader->close(); // ضمان الإغلاق دائماً
        }
    }

    protected function validateRow(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|unique:departments,name',
            'code' => 'required|regex:/^[0-9]+$/|unique:departments,code',
        ], [
            'name.required' => 'يجب ملأ حقل الاسم',
            'name.unique' => 'الاسم موجود بالفعل في قاعدة البيانات',
            'code.unique' => 'الكود موجود بالفعل في قاعدة البيانات',
            'code.regex' => 'يجب أن يتكوّن الكود من أرقام فقط',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}