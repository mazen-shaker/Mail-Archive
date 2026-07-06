<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Services\DepartmentService;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{  
    protected $service;
    protected $request;

    public function __construct(DepartmentService $service, Request $request){$this->service = $service; $this->request = $request;}

    public function index() {$departments $this->service->index(); return view('departments.index', compact('departments'));}

    public function store(StoreDepartmentRequest $request){$this->service->store($request->validated()); return redirect()->route('department.index');}

    public function update(UpdateDepartmentRequest $request){$this->service->update($request->id, $request->validated()); return redirect()->route('department.index');}

    public function destroy($id){$this->service->destroy($id); return redirect()->route('department.index');}

    public function archive($id){$this->service->archive($id); return redirect()->route('department.index');}
     
    public function destroyAll(){$this->service->deleteMultiple($this->request->ids); return redirect()->route('department.index');}

    public function archiveAll(){$this->service->archiveMultiple($this->request->ids); return redirect()->route('department.index');}

    public function import(){$this->service->import($this->request()); return redirect()->route('department.index');}

}  