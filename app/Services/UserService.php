<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
  
class UserService extends BaseService
{
    protected $model;
    
    public function __construct(User $model){$this->model = $model;} 
    
    public function index(){$users = $this->model->paginate(10); $roles = CacheService::getCache('roles', 'Role'); $departments = CacheService::getCache('departments', 'Department'); $statuss = CacheService::getCache('usersStatuses', 'UserStatus'); return ['users' => $users,'roles' => $roles, 'departments' => $departments, 'statuss' => $statuss,];}
}
        