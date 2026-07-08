<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Services\EntityService;
use App\Http\Requests\StoreEntityRequest;
use App\Http\Requests\UpdateEntityRequest;
use Illuminate\Http\Request;   
use Illuminate\Support\Facades\Cache;
use App\Notifications\ImportPendingNotification;
class EntityController extends Controller
{    
    protected $service;   
    protected $request;

    public function __construct(EntityService $service, Request $request) {$this->service = $service; $this->request = $request;}

    public function index(){$entities = $this->service->index(); return view('entities.index', compact('entities'));}

    public function store(StoreEntityRequest $request){$this->service->store($request->validated()); return redirect()->route('entity.index');}

    public function update(UpdateEntityRequest $request){$this->service->update($request->id, $request->validated()); return redirect()->route('entity.index');}

    public function destroy($id){$this->service->destroy($id); return redirect()->route('entity.index');}

    public function archive($id){$this->service->archive($id); return redirect()->route('entity.index');}

    public function destroyAll(){$this->service->deleteMultiple($this->request->ids); return redirect()->route('entity.index');}

    public function archiveAll(){$this->service->archiveMultiple($this->request->ids); return redirect()->route('entity.index');}

    public function import() {$this->service->import($this->request); return redirect()->route('entity.index');}

    public function search(){$results = $this->service->search($this->request->search); return response()->json($results);}
}