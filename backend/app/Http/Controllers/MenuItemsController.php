<?php

namespace App\Http\Controllers\Admin;

use App\Permission;
use App\Models\Menu;
use App\Models\MenuItems;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuItemsController extends Controller {

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
	public function index($id) {
		$menu = MenuItems::where('menu_id', $id)->whereNull('parent_id')->with('children')->get();
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
	public function update(Request $request, $parentId, $id) {
		$menu = request()->validate([
			'name' => 'required|string|max:255',
			'hidden' => 'required|string'
		]);


		$menu = MenuItems::findOrFail($id);


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
		return response()->json('gegegeg');
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
	public function destroy($menuId, $itemId) {
		$menu = MenuItems::findOrFail($itemId);

		if($children = count($menu->children()->get())) {
			return response()->json("$menu->name is assigned to $children menu(s) and cannot be deleted", 400);
		} else {
			$menu->delete();
			return response()->json('The menu item has been deleted');
		}
	}
}
