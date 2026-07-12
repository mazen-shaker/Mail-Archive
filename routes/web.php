<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailController;
use App\Http\Controllers\SignController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/', function () {
    return view('auth.login');
});


Route::middleware(['auth','throttle:60,1','active'])->group(function () {

Route::get('/mail/index', [MailController::class, 'index'])->name('mail.index');
Route::post('/mail/store', [MailController::class, 'store'])->name('mail.store');
Route::put('/mail/update', [MailController::class, 'update'])->name('mail.update');
Route::get('/mail/destroy/{id}', [MailController::class, 'destroy'])->name('mail.destroy');
Route::get('/mail/archive/{id}', [MailController::class, 'archive'])->name('mail.archive');
Route::post('/mail/destroy/all', [MailController::class, 'destroyAll'])->name('mail.destroy.all');
Route::post('/mail/archive/all', [MailController::class, 'archiveAll'])->name('mail.archive.all');
Route::post('/mail/search', [MailController::class, 'search'])->name('mail.search');
Route::get('/mail/sign/{id}', [MailController::class, 'sign'])->name('mail.sign');
Route::post('/mail/sign/save/{id}', [MailController::class, 'saveEditor'])->name('mail.sign.save');
Route::get('/mail/preview/{id}', [MailController::class, 'preView'])->name('mail.preview');
Route::put('/mail/share', [MailController::class, 'share'])->name('mail.share');
Route::get('/mail/report/index', [MailController::class, 'reportIndex'])->name('mail.report.index');

Route::get('/sign/index', [SignController::class, 'index'])->name('sign.index');
Route::post('/sign/store', [SignController::class, 'store'])->name('sign.store');
Route::put('/sign/update', [SignController::class, 'update'])->name('sign.update');
Route::get('/sign/destroy/{id}', [SignController::class, 'destroy'])->name('sign.destroy');
Route::get('/sign/archive/{id}', [SignController::class, 'archive'])->name('sign.archive');
Route::post('/sign/destroy/all', [SignController::class, 'destroyAll'])->name('sign.destroy.all');
Route::post('/sign/archive/all', [SignController::class, 'archiveAll'])->name('sign.archive.all');
Route::post('/sign/search', [SignController::class, 'search'])->name('sign.search');
Route::get('/sign/preview/{id}', [SignController::class, 'preView'])->name('sign.preview');


Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


Route::middleware('admin')->group(function () {

Route::get('/department/index', [DepartmentController::class, 'index'])->name('department.index');
Route::post('/department/store', [DepartmentController::class, 'store'])->name('department.store');
Route::post('/department/import', [DepartmentController::class, 'import'])->name('department.import');
Route::put('/department/update', [DepartmentController::class, 'update'])->name('department.update');
Route::get('/department/destroy/{id}', [DepartmentController::class, 'destroy'])->name('department.destroy');
Route::get('/department/archive/{id}', [DepartmentController::class, 'archive'])->name('department.archive');
Route::post('/department/destroy/all', [DepartmentController::class, 'destroyAll'])->name('department.destroy.all');
Route::post('/department/archive/all', [DepartmentController::class, 'archiveAll'])->name('department.archive.all');
Route::post('/department/search', [DepartmentController::class, 'search'])->name('department.search');

Route::get('/entity/index', [EntityController::class, 'index'])->name('entity.index');
Route::post('/entity/store', [EntityController::class, 'store'])->name('entity.store');
Route::post('/entity/import', [EntityController::class, 'import'])->name('entity.import');
Route::put('/entity/update', [EntityController::class, 'update'])->name('entity.update');
Route::get('/entity/destroy/{id}', [EntityController::class, 'destroy'])->name('entity.destroy');
Route::get('/entity/archive/{id}', [EntityController::class, 'archive'])->name('entity.archive');
Route::post('/entity/destroy/all', [EntityController::class, 'destroyAll'])->name('entity.destroy.all');
Route::post('/entity/archive/all', [EntityController::class, 'archiveAll'])->name('entity.archive.all');
Route::post('/entity/search', [EntityController::class, 'search'])->name('entity.search');

Route::get('/user/index', [RegisteredUserController::class, 'index'])->name('user.index');
Route::post('/user/store', [RegisteredUserController::class, 'store'])->name('user.store');
Route::post('/user/add', [RegisteredUserController::class, 'add'])->name('user.add');
Route::put('/user/update', [RegisteredUserController::class, 'update'])->name('user.update');
Route::get('/user/destroy/{id}', [RegisteredUserController::class, 'destroy'])->name('user.destroy');
Route::get('/user/archive/{id}', [RegisteredUserController::class, 'archive'])->name('user.archive');
Route::post('/user/destroy/all', [RegisteredUserController::class, 'destroyAll'])->name('user.destroy.all');
Route::post('/user/archive/all', [RegisteredUserController::class, 'archiveAll'])->name('user.archive.all');
Route::post('/user/search', [RegisteredUserController::class, 'search'])->name('user.search');
});
});


require __DIR__.'/auth.php';
