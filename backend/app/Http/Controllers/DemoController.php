<?php

namespace App\Http\Controllers\Admin;

use App\Models\Demo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DemoController extends Controller {

    public function __construct() {
        $this->middleware('role:superadmin|admin')->except('demoIndex', 'demoShow');
    }

    public function demoIndex() {
        $demo = Demo::with('children')->where('parent_id')->get();

        return response()->json($demo);
    }

    public function demoShow(Request $request, $name) {
        $demo = Demo::where('name', $name)->first();

        return response()->json($demo);
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
        $demo = Demo::with('children')->where('parent_id')->get();
        return response()->json($demo);
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
        $demo = Demo::where('name', $name)->first();

        return response()->json($demo);
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
    public function update(Request $request, $name) {

        $demo = Demo::where('name', $name)->first();

        if($request->type === 'category') {

            $validate = request()->validate([
                'name' => 'required|string|max:255|unique:demo,name,'.$demo->id
            ]);

            $demo->name = $request->name;

            $demo->parent_id = null;
            $demo->description = null;
            $demo->access = null;

            $demo->save();

            $demo = Demo::where('id', $demo->id)->with('children')->where('parent_id')->first();

            return response()->json(["message" => "$request->name has been updated", "demo" => $demo]);
        } else if ($request->type === 'demo') {

            $validate = request()->validate([
                'name' => 'required|string|max:255|unique:demo,name,'.$demo->id,
                'demo.parent' => 'required|integer',
                'demo.description' => 'required|string',
                'demo.access' => 'required|string'
            ]);

            $demoArr = $request->demo;

            $demo->name = $request->name;
            $demo->parent_id = intval($demoArr['parent']);
            $demo->description = $demoArr['description'];
            $demo->access = $demoArr['access'];
            
            $demo->save();
            return response()->json(["message" => "$request->name has been updated", "demo" => $demo]);
        } else {
            return response()->json('Something went seriously wrong, contact system admin', 400);
        }
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
        $type = $request->type;

        if($type === 'category') {
            $demo = request()->validate([
                'name' => 'required|string|max:255|unique:demo'
            ]);

            $demo = new Demo;

            $demo->name = $request->name;
            $demo->save();
            $demo = Demo::where('id', $demo->id)->with('children')->where('parent_id')->first();

            return response()->json(["message" => "$request->name has been created", "demo" => $demo]);
        } else if ($type === 'demo') {
            $demo = request()->validate([
                'name' => 'required|string|max:255|unique:demo',
                'demo.parent' => 'required|integer',
                'demo.description' => 'required|string',
                'demo.access' => 'required|string'
            ]);

            $demoArr = $request->demo;

            $demo = new Demo;

            $demo->name = $request->name;
            $demo->parent_id = intval($demoArr['parent']);
            $demo->description = $demoArr['description'];
            $demo->access = $demoArr['access'];

            $demo->save();

            return response()->json(["message" => "$request->name has been created", "demo" => $demo]);
        } else {
            return response()->json('Something went wrong', 400);
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
        $demo = Demo::findOrFail($id);

        if($demo->parent_id === null) {
            $count = count($demo->children()->get());
            if($count > 0) {
                return response()->json("$demo->name is assigned to $count demo(s) and cannot be deleted", 400);
            } else {
                $demo->delete();

                return response()->json('The demo category has been deleted');
            }
        } else if($demo->parent_id !== null) {
            $demo->delete();

            return response()->json('The demo item has been deleted');
        }
    }
}
