<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use App\Models\Role;
use App\Models\UserStatus;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use App\Services\UserService;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\StoreUserRequest;

class RegisteredUserController extends Controller
{

    protected $service;   
    protected $request;

    public function __construct(UserService $service, Request $request){$this->service = $service; $this->request = $request;}



    public function index(){$data = $this->service->index(); $users = $data['users']; $roles = $data['roles']; $departments = $data['departments'];
    $statuss = $data['statuss']; return view('users.index', compact(['users','roles','departments','statuss']));}


    public function store(Request $request): RedirectResponse {$request->validate(['name' => ['required', 'string', 'max:255'], 'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class], 'password' => ['required', 'confirmed', Rules\Password::defaults()],]);   
    $user = User::create(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password),]); event(new Registered($user)); Auth::login($user); return redirect(route('dashboard', absolute: false));}

    
    public function add(StoreUserRequest $request): RedirectResponse {$data = $request->validated(); $data['password'] = Hash::make($data['password']); $this->service->store($data); return redirect()->route('user.index');}



    public function update(UpdateUserRequest $request) {$this->service->update($request->validated()['id'], $request->validated()); return redirect()->route('user.index');}          



    public function destroy($id) {$this->service->destroy($id); return redirect()->route('user.index');}



    public function archive($id) {$this->service->archive($id); return redirect()->route('user.index');}



    public function destroyAll(Request $request) {$this->service->deleteMultiple($request->ids); return redirect()->route('user.index');}



    public function archiveAll(Request $request) {$this->service->archiveMultiple($request->ids); return redirect()->route('user.index');}

}
