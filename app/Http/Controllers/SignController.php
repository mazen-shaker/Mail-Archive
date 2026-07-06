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
class SignController extends Controller
{   
    protected $service;
    protected $request;
    
    public function __construct(SignService $service,  Request $request){$this->service = $service; $this->request = $request;}
    

    public function index(){$data = $this->service->index();
    $signs = $data['signs']; $users = $data['users']; return view('signs.index', compact(['signs','users']));}


    public function store(StoreSignRequest $request){$this->service->prosessFile($request); return redirect()->route('sign.index');}


    public function update(UpdateSignRequest $request){$this->service->update($request->id, $request->validated()); return redirect()->route('sign.index');}


    public function destroy($id){$this->service->destroy($id); return redirect()->route('sign.index');}


    public function archive($id){$this->service->archive($id); return redirect()->route('sign.index');}


    public function destroyAll(Request $request){$this->service->deleteMultiple($request->ids); return redirect()->route('sign.index');}


    public function archiveAll(Request $request){$this->service->archiveMultiple($request->ids); return redirect()->route('sign.index');}


    public function preView($id){return $this->service->preViewFile($id);}
}