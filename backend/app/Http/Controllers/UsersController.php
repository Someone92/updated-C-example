<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Role;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller {

    public function __construct() {
        $this->middleware('role:superadmin|admin');
    }

	//***************************************
    //               ____
    //              |  _ \
    //              | |_) |
    //              |  _ <
    //              | |_) |
    //              |____/
    //
    //      Browse our Data Type (B)READ
    //
    //****************************************

	public function index() {
		$users = $this->getUsers();

        return response()->json($users);
	}

    //***************************************
    //                _____
    //               |  __ \
    //               | |__) |
    //               |  _  /
    //               | | \ \
    //               |_|  \_\
    //
    //  Read an item of our Data Type B(R)EAD
    //
    //****************************************

    public function show(Request $request, $id) {

    }

    //***************************************
    //                ______
    //               |  ____|
    //               | |__
    //               |  __|
    //               | |____
    //               |______|
    //
    //  Edit an item of our Data Type BR(E)AD
    //
    //****************************************

    public function edit($id) {
    }

    // POST BR(E)AD
    public function update(Request $request, $id) {
        $user = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'. $id,
            'password' => 'sometimes|nullable|string|min:6',
            'role' => 'required'
        ]);

        $user = User::find($id);

        if($request->name) {
            $user->name = $request->name;
        }
        if($request->email) {
            $user->email = $request->email;
        }
        if($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->detachRoles($user->roles);

        $role = Role::where('name', '=', $request->role)->first();

        $user->attachRole($role);

        $user->save();
        return response()->json(["message" => "$request->name has been created", "user" => $this->getUser($user->id)]);
    }

    //***************************************
    //
    //                   /\
    //                  /  \
    //                 / /\ \
    //                / ____ \
    //               /_/    \_\
    //
    //
    // Add a new item of our Data Type BRE(A)D
    //
    //****************************************

    public function create() {
        // Not needed currently
    }

    // POST BRE(A)D
    public function store(Request $request) {
        $user = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required'
        ]);

        if($request->role === 'superadmin') {
            return response()->json('You can not create a user with this role', 400);
        } else {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $role = $request->role;

            $user->save();

            $user->attachRole(Role::where('name', '=', $role)->first());

            return response()->json(["message" => "$request->name has been created", "user" => $this->getUser($user->id)]);
        }
    }

    //***************************************
    //                _____
    //               |  __ \
    //               | |  | |
    //               | |  | |
    //               | |__| |
    //               |_____/
    //
    //         Delete an item BREA(D)
    //
    //****************************************

    public function destroy($id) {
        if($user = User::findOrFail($id)) {
            if($user->roles->first()->name !== 'superadmin') {
                $user->delete();

                return Response()->json("$user->name has been deleted");
            }
        
        return response()->json('You do not have permission to delete this user', 400);
    }



    private function getUser($id) {
        $user = DB::table('users')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->select('users.id', 'users.name', 'users.email','users.created_at', 'users.updated_at', 'roles.name as role', 'roles.color as color')->where('users.id', $id)->get();

        return $user;
    }

	private function getUsers() {
        $users = DB::table('users')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->select('users.id', 'users.name', 'users.email','users.created_at', 'users.updated_at', 'roles.name as role', 'roles.color as color')->whereNotIn('roles.name', ['superadmin'])->get();

        return $users;
	}

}
