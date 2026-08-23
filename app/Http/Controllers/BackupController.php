<?php

namespace App\Http\Controllers;

use App\Models\BackUp;
use App\Services\Backup\BackupService;
use App\Http\Requests\StoreBackUpRequestRequest;
use App\Http\Requests\UpdateBackUpRequestRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Notifications\ImportPendingNotification;

class BackupController extends Controller
{
protected $service;
protected $request;

public function __construct(BackUpService $service, Request $request) {$this->service = $service; $this->request = $request;}

public function index(){ return view('backup.index');}

public function sittings(){ return view('backup.sittings');}

public function pack(){ $this->service->pack(); return redirect()->back();}

public function archiveIndex() {$backups = $this->service->archiveIndex(); return view('archive.backups-index', compact('backups'));}

public function restore($id) {$this->service->restore($id); return redirect()->back();}

public function store(StoreBackUpRequest $request){$this->service->store($request->validated()); return redirect()->back();}

public function update(UpdateBackUpRequest $request){$this->service->update($request->id, $request->validated()); return redirect()->back();}

public function destroy($id){$this->service->destroy($id); return redirect()->back();}

public function archive($id){$this->service->archive($id); return redirect()->back();}

public function destroyAll(){$this->service->deleteMultiple($this->request->ids); return redirect()->back();}

public function archiveAll(){$this->service->archiveMultiple($this->request->ids); return redirect()->back();}

public function restoreAll(){$this->service->restoreMultiple($this->request->ids); return redirect()->back();}

public function import() {$this->service->import($this->request); return redirect()->back();}

public function search($archive = null){$results = $this->service->search($this->request->search, $archive); return response()->json($results);}}
