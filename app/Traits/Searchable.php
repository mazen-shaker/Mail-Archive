<?php

namespace App\Traits;

trait Searchable
{


public function search($search, array $columns, array $relations = [])
{
    return $this->query()
        ->with($relations)
        ->where(function ($q) use ($search, $columns, $relations) {

            foreach ($columns as $column) {

                if (str_contains($column, '.')) {

                    [$relation, $columnName] = explode('.', $column);

                    $q->orWhereHas($relation, function ($q) use ($search, $columnName) {
                        $q->where($columnName, 'like', "%{$search}%");
                    });

                } else {

                    $q->orWhere($column, 'like', "%{$search}%");

                }
            }
        })
        ->get();
}}
