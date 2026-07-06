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

    public function __construct(EntityService $service, Request $request)
    {   
        $this->service = $service;   
    
        $this->request = $request;  

    }

 
    
    public function index()
    {

    $entities = Entity::hydrate($this->service->getCachedData('entities'));
    return view('entities.index', compact('entities'));

    }

    public function store(StoreEntityRequest $request)
    {
        $this->service->store($request->validated()); 
        Cache::tags(['entities'])->flush();
        return redirect()->route('entity.index');
    }

    public function update(UpdateEntityRequest $request)
    {
        $this->service->update($request->id, $request->validated());
        Cache::tags(['entities'])->flush();
        return redirect()->route('entity.index');
    }

    public function destroy($id)
    {
        $this->service->destroy($id);
        Cache::tags(['entities'])->flush();
        return redirect()->route('entity.index');
    }

    public function archive($id)
    {
        $this->service->archive($id);
        Cache::tags(['entities'])->flush();
        return redirect()->route('entity.index');
    }

    public function destroyAll()
    {
        $this->service->deleteMultiple($this->request->ids);
        Cache::tags(['entities'])->flush();
        return redirect()->route('entity.index');
    }

    public function archiveAll()
    {
        $this->service->archiveMultiple($this->request->ids);
        Cache::tags(['entities'])->flush();
        return redirect()->route('entity.index');
    }

    public function import()
    {

        $this->service->import($this->request);
        Cache::tags(['entities'])->flush();
        return redirect()->route('entity.index');
    }

    public function search()
    {
        $cachedData = Entity::hydrate($this->service->getCachedData('entities'));
        $columns = ['name'];
        $search = $this->request->search;
        $results = $this->service->search($search,$columns,$cachedData);
        return response()->json($results);
    }
}