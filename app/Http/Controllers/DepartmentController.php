<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Services\DepartmentService;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DepartmentController extends Controller
{  
    protected $service;
    protected $request;

    public function __construct(DepartmentService $service, Request $request)
    {
        $this->service = $service;
    
        $this->request = $request;
    }


    public function index()
    {

    $departments = Department::hydrate($this->service->getCachedData('departments'));

    return view('departments.index', compact('departments'));

    }

    public function store(StoreDepartmentRequest $request)
    {
        $this->service->store($request->validated()); 
        Cache::tags(['departments'])->flush();
        return redirect()->route('department.index');
    }

    public function update(UpdateDepartmentRequest $request)
    {
        $this->service->update($request->id, $request->validated());
        Cache::tags(['departments'])->flush();
        return redirect()->route('department.index');
    }

    public function destroy($id)
    {
        $this->service->destroy($id);
        Cache::tags(['departments'])->flush();
        return redirect()->route('department.index');
    }

    public function archive($id)
    {
        $this->service->archive($id);
        Cache::tags(['departments'])->flush();
        return redirect()->route('department.index');
    }
     
    public function destroyAll()
    {
        $this->service->deleteMultiple($this->request->ids);   
        Cache::tags(['departments'])->flush();
        return redirect()->route('department.index');
    }

    public function archiveAll()
    {
        $this->service->archiveMultiple($this->request->ids);  
        Cache::tags(['departments'])->flush();
        return redirect()->route('department.index');
    }

        public function import()
    {

        $this->service->import($this->request);
        Cache::tags(['departments'])->flush();
        return redirect()->route('department.index');
    }


    public function search()
    {
        $cachedData = Department::hydrate($this->service->getCachedData('departments'));
        $columns = ['name','code'];
        $search = $this->request->search;
        $results = $this->service->search($search,$columns,$cachedData);
        return response()->json($results);
    }
}