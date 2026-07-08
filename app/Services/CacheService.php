<?php

namespace App\Services;
use Illuminate\Support\Facades\Cache;
use App\Models\Mail;
use App\Models\User;
use App\Models\Sign;
use App\Models\Role;
use App\Models\MailPrivacy;
use App\Models\Department;
use App\Models\UserStatus;
  
class CacheService  
{
public static function bootCache(){Cache::tags(['privacies','departments','signs','roles', 'usersStatuses'])->flush(); Cache::tags('privacies')->rememberForever('privacies', fn() => MailPrivacy::all()->toArray()); 
Cache::tags('usersStatuses')->rememberForever('usersStatuses', fn() => UserStatus::all()->toArray()); Cache::tags('departments')->rememberForever('departments', fn() => Department::all()->toArray());
Cache::tags('signs')->rememberForever('signs', fn() => Sign::all()->toArray()); Cache::tags('roles')->rememberForever('roles', fn() => Role::all()->toArray());}

public static function resetCache(string $type, $model = null){Cache::tags($type)->flush(); Cache::tags($type)->rememberForever($type, fn() => $model::all()->toArray());}

public static function flushAllCache(string $type){Cache::tags(['privacies','departments','signs','roles', 'usersStatuses'])->flush();}

public static function getCache(string $type, $model = null){$data = Cache::tags($type)->get($type); if (!$data) return collect(); return $model ? $model::hydrate($data) : collect($data);}
}
  