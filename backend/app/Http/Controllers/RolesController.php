<?php

namespace App\Http\Controllers\Admin;

use App\Role;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Rules\HexColor;

class RolesController extends Controller {

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
		$roles = Role::whereNotIn('name', array('superadmin'))->get();

        return response()->json($roles);
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
        // Not needed currently
    }

    // POST BR(E)AD
    public function update(Request $request, $id) {
        $role = request()->validate([
            'name' => 'required|alpha|max:255|unique:roles,name,'. $id,
            'display_name' => 'required|string|max:255|',
            'description' => 'required|string|max:255',
            'color' => [new HexColor]
        ]);

        $request->name = strtolower($request->name);

        $role = Role::find($id);

        if($request->name) {
            $role->name = $request->name;
        }
        if($request->display_name) {
            $role->display_name = $request->display_name;
        }
        if($request->description) {
            $role->description = $request->description;
        }
        if($request->color) {
            $role->color = $request->color;
        }

        $role->save();

        return response()->json(["message" => "$request->name has been updated", "role" => $role]);
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
        $role = request()->validate([
            'name' => 'required|alpha|max:255|unique:roles',
            'display_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'color' => [new HexColor]
        ]);

        $role = new Role;

        $role->name = strtolower($request->name);
        $role->display_name = $request->display_name;
        $role->description = $request->description;
        $role->color = $request->color;
        $role->save();

        return response()->json(["message" => "$request->name has been created", "role" => $role]);
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
        $role = Role::findOrFail($id);
        if(count($role->users) > 0) {
        	$users = count($role->users);
        	return response()->json("$role->display_name is assigned to $users user(s) and cannot be deleted", 400);
        } else {
        	$role->delete();
        	return response()->json('The role has been deleted');
        }
    }
}
