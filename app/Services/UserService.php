<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class UserService extends BaseService
{
    protected $model;
    public function __construct(User $model)    
    {
  
      $this->model = $model;

    } 
    public function search($search, array $columns, $cachedData)
{


    if (!$cachedData) {  
        return $this->searchDB($search, $columns);
    }

    $searchTerm = mb_strtolower($search);

    $results = $cachedData->filter(function ($user) use ($searchTerm, $columns) {

        foreach ($columns as $col) {

            $value = $this->resolveColumnValue($user, $col);

            if ($value !== null && str_contains(mb_strtolower($value), $searchTerm)) {
                return true;
            }
        }

        return false;
    })->values();

    if ($results->isNotEmpty()) {
        
        $results->load(['status','role','department']);
        
        return $results;
    }

        Log::info('my new results', [
        'results' => $results,
    ]);
    return $this->searchDB($search, $columns);
}

private function resolveColumnValue($user, $column)
    {
        $segments = explode('.', $column);
        $currentEntity = $user;

        foreach ($segments as $index => $segment) {
            if (is_null($currentEntity)) {
                return null;
            }

            if ($index < count($segments) - 1) {

                if (!$currentEntity->relationLoaded($segment)) {
                    return null; 
                }
            }

            $currentEntity = $currentEntity->{$segment} ?? null;
        }

        return is_scalar($currentEntity) ? $currentEntity : null;
    }
public function searchDB($search, array $columns)
{
    return $this->model::query()
        ->with(['status'])      
        ->with(['role'])      
        ->with(['department'])      
        ->where(function ($q) use ($search, $columns) {
            
            foreach ($columns as $column) {
                if (!str_contains($column, '.')) {
                    $q->orWhere($column, 'like', "%{$search}%");
                }
            }
            
            $q->orWhereHas('status', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
            $q->orWhereHas('role', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
            $q->orWhereHas('department', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        })
        ->get();
}
  
}
  