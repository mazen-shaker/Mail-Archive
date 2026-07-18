<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use App\Models\UserStatus;
use App\Models\Mail;
use Illuminate\Notifications\DatabaseNotification;
use App\Enums\MailStatusEnum;


class UserService extends BaseService
{
    protected $model;

    public function __construct(User $model){$this->model = $model;}

    public function dashboard(){

        $mails = Mail::get();

        $resultCount = $mails->count(); // عدد الجوابات في النظام

        $sharedCount = $mails->where('mail_status_id', MailStatusEnum::PUBLISHED->value)->count(); // عدد الجوابات المنشوره

        $sharedRatio = collect(range(1, 12))->map(function ($month) use ($mails) {return $mails->where('mail_status_id', MailStatusEnum::PUBLISHED->value)->filter(fn ($mail) => $mail->created_at->month == $month)->count();})->values()->toArray();

        $archivedCount = Mail::onlyTrashed()->count(); // عدد الجوابات المؤرشفه

        $signedCount = $mails->whereNotNull('sign')->count();


        return ['resultCount'=>$resultCount, 'sharedCount'=>$sharedCount, 'sharedRatio'=>$sharedRatio, 'archivedCount'=>$archivedCount,'signedCount'=>$signedCount,];}


  public function destroyNotification($id)
{
    // البحث عن الإشعار والتأكد أنه يخص المستخدم الحالي
    $notification = auth()->user()->notifications()->find($id);

    // إذا لم يوجد الإشعار، نرجع خطأ 404
    if (!$notification) {
        return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
    }

    // حذف الإشعار
    $notification->delete();

    return response()->json(['success' => true, 'message' => 'Notification deleted successfully']);
}

    public function index(){$users = $this->model->paginate(10); $roles = CacheService::getCache('roles', Role::class); $departments = CacheService::getCache('departments', Department::class); $statuss = CacheService::getCache('usersStatuses', UserStatus::class); return ['users' => $users,'roles' => $roles, 'departments' => $departments, 'statuss' => $statuss,];}

    public function search($search){$columns = ['name','email','role.name','department.name','status.name']; $relations = ['role','department','status']; return $this->model->search($search,$columns,$relations);}
}

