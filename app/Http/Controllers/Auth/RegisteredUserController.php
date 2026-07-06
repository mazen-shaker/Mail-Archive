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

    public function __construct(UserService $service, Request $request)
    {   
        $this->service = $service;   
    
        $this->request = $request;  

    }

    /**
     * Display the registration view.   
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */


        public function index()  
    {

    $users = User::hydrate($this->service->getCachedData('users'));
    $roles = Role::hydrate($this->service->getCachedData('roles'));
    $departments = Department::hydrate($this->service->getCachedData('departments'));
    $statuss = UserStatus::hydrate($this->service->getCachedData('usersStatuses'));
    return view('users.index', compact(['users','roles','departments','statuss']));

    }
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);   

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
            
        event(new Registered($user));

        Auth::login($user);
                 
        return redirect(route('dashboard', absolute: false));
    }

        public function add(StoreUserRequest $request): RedirectResponse
    {

        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $this->service->store($data); 
        Cache::tags(['entities'])->flush();
        return redirect()->route('user.index');
    }

    public function update(UpdateUserRequest $request)
    {
        $this->service->update($request->validated()['id'], $request->validated());
        Cache::tags(['users'])->flush();
        return redirect()->route('user.index');
    }          

    public function destroy($id)
    {
        $this->service->destroy($id);
        Cache::tags(['users'])->flush();
        return redirect()->route('user.index');
    }

    public function archive($id)
    {
        $this->service->archive($id);
        Cache::tags(['users'])->flush();
        return redirect()->route('user.index');
    }

    public function destroyAll(Request $request)
    {
        $this->service->deleteMultiple($request->ids);
        Cache::tags(['users'])->flush();
        return redirect()->route('user.index');
    }

    public function archiveAll(Request $request)
    {
        $this->service->archiveMultiple($request->ids);
        Cache::tags(['users'])->flush();
        return redirect()->route('user.index');    
    }

    public function search()
    
{

$cachedData = User::hydrate($this->service->getCachedData('users'));

$columns = ['name','email','role.name','department.name','status.name'];

$search = $this->request->search;

$results = $this->service->search($search,$columns,$cachedData);

return response()->json($results);

}

    }
