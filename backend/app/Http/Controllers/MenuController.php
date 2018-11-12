<?php

namespace App\Http\Controllers\Admin;

use App\Permission;
use App\Models\Menu;
use App\Models\MenuItems;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller {

	public function __construct() {
		$this->middleware('role:superadmin|admin')->except('index');
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
	public function index(Request $request) {
		$user = $request->user();

		if($user->can('access_admin')) {
			$perm = Permission::where('name', 'access_admin')->first();
			$menu = Menu::with('children')->where('permission', $perm->id)->orWhereNull('permission')->get();
		} else {
			$menu = Menu::with(['children' => function($query) {
				$query->where('hidden', 'no');
			}])->where('hidden', 'no')->whereNull('permission')->get();

		}
		return response()->json($menu);
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
	public function show($id) {
		$menu = Menu::where('id', $id)->first();

		return response()->json($menu);
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

		$menu = request()->validate([
			'name' => 'required|string|max:255',
			'hidden' => 'required|string'
		]);

		$menu = Menu::findOrFail($id);


		$menu->name = $request->name;
		$menu->hidden = $request->hidden;
		$menu->save();

		return response()->json(["message" => "$request->name has been updated", "menu" => $menu]);
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
		$menu = request()->validate([
			'name' => 'required|string|max:255|unique:menu',
			'hidden' => 'required|string'
		]);

		$menu = new Menu;
		$menu->name = $request->name;
		$menu->hidden = $request->hidden;
		$menu->save();

        return response()->json(["message" => "$request->name has been created", "menu" => $menu]);
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
		$menu = Menu::findOrFail($id);

		if(count($menu->children()->get())) {
			$children = count($menu->children()->get());
			return response()->json("$menu->name is assigned to $children menu(s) and cannot be deleted", 400);
		} else {
			$data = $menu;
			$menu->delete();

			return response()->json(["message" => "The menu has been deleted", "data" => $data]);
		}
	}
}
