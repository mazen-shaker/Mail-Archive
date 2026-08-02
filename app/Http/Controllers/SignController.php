<?php

namespace App\Http\Controllers;

use App\Models\Sign;
use App\Models\User;
use App\Services\SignService;
use App\Http\Requests\StoreSignRequest;
use App\Http\Requests\UpdateSignRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Services\CacheService;

class SignController extends Controller
{
    protected $service;
    protected $request;

    public function __construct(SignService $service,  Request $request){$this->service = $service; $this->request = $request;}


    public function index(){$data = $this->service->index();
    $signs = $data['signs']; $users = $data['users']; return view('signs.index', compact(['signs','users']));}

    public function archiveIndex() {$signs = $this->service->archiveIndex(); return view('archive.signs-index', compact('signs'));}


    public function restore($id) {$this->service->restore($id); return redirect()->back();}


    public function store(StoreSignRequest $request){$this->service->prosessFile($request); CacheService::resetCache('signs', Sign::class); return redirect()->back();}


    public function update(UpdateSignRequest $request){$this->service->update($request->id, $request->validated()); CacheService::resetCache('signs', Sign::class); return redirect()->back();}


    public function destroy($id){$this->service->destroy($id); CacheService::resetCache('signs', Sign::class); return redirect()->back();}


    public function archive($id){$this->service->archive($id); CacheService::resetCache('signs', Sign::class); return redirect()->back();}


    public function destroyAll(Request $request){$this->service->deleteMultiple($request->ids); CacheService::resetCache('signs', Sign::class); return redirect()->back();}


    public function archiveAll(Request $request){$this->service->archiveMultiple($request->ids); CacheService::resetCache('signs', Sign::class); return redirect()->back();}


    public function restoreAll(){$this->service->restoreMultiple($this->request->ids);  CacheService::resetCache('signs', Sign::class);  return redirect()->back();}


    public function preView($id){return $this->service->preViewFile($id);}


    public function search($archive = null){$results = $this->service->search($this->request->search,$archive); return response()->json($results);}
}
