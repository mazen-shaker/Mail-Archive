<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use App\Models\UserStatus;

class UserService extends BaseService
{
    protected $model;
    
    public function __construct(User $model){$this->model = $model;} 
    
    public function index(){$users = $this->model->paginate(10); $roles = CacheService::getCache('roles', Role::class); $departments = CacheService::getCache('departments', Department::class); $statuss = CacheService::getCache('usersStatuses', UserStatus::class); return ['users' => $users,'roles' => $roles, 'departments' => $departments, 'statuss' => $statuss,];}

    public function search($search){$columns = ['name','email','role.name','department.name','status.name']; $relations = ['role','department','status']; return $this->model->search($search,$columns,$relations);}
}
        