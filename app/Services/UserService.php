<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use App\Models\Status;
  
class UserService extends BaseService
{
    protected $model;
    
    public function __construct(User $model){$this->model = $model;} 
    
    public function index(){$users = $this->model->paginate(10); $roles = Role::all(); $departments = Department::all(); $statuss = Status::all(); return ['users' => $users,'roles' => $roles, 'departments' => $departments, 'statuss' => $statuss,];}
}
        