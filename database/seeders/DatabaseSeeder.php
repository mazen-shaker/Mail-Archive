<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\MailPrivacy;
use App\Models\MailStatus;
use App\Models\User;
use App\Models\UserStatus;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

    Role::insert([
      ['id' => 1, 'name' => 'مشرف' ,'description' => 'هذا الدور لديه الصلاحيه في القرائه و الكتابه على جميع اعدادات التطبيق'],
      ['id' => 2 'name' => 'مستخدم' ,'description' => 'هذا الدور لديه الصلاحيه في قرائه الجوابات الخاصه به فقط او الجوابات ذات الخصوصيه العامه و ارشفتخا و حذفها لديه هو فقط'],
      ]);

    UserStatus::insert([
      ['id' => 1, 'name' => 'نشط'],
      ['id' => 2, 'name' => 'معطل'],
    ]);

    User::insert([
      ['id' => 1, 'name' => 'admin' ,'email' => 'admin@admin.com','password'=>bcrypt('123456789'),'role_id'=>'1','department_id'=>null,'user_status_id'=>'1'],
      ['id' => 2, 'name' => 'user' ,'email' => 'user@user.com','password'=>bcrypt('123456789'),'role_id'=>'2','department_id'=>null,'user_status_id'=>'1'],
    ]);

      MailStatus::insert([
      ['id' => 1, 'name' => 'منشور'],
      ['id' => 2, 'name' => 'معلق'],
      ['id' => 3, 'name' => 'غير منشور'],
    ]);


      MailPrivacy::insert([
      ['id' => 1, 'name' => 'خاص'],
      ['id' => 2, 'name' => 'عام'],
    ]);


    }

}
