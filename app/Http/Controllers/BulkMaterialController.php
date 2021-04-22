<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bulk_material;
use DB;
use App\Helpers\Helper;
use Auth;

class BulkMaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {        
        $loggedin_user = Helper::get_logged_in_user();
        if($loggedin_user)
        {
            $role = Helper::get_logged_in_user_role($loggedin_user);
            if($role==3)
            { 
                $company_id = Helper::get_logged_in_user_company($loggedin_user);
                $bulk_material = Bulk_material::select('id', 'material_name', 'material_code', 'is_active')
                ->where('company_id', $company_id)
                ->orderBy('material_name', 'ASC')
                ->get();
                return view('bulk_material.index', ['bulk_material'=>$bulk_material, 'loggedin_user'=>$loggedin_user, 'role'=>$role]);
            }
            else
            {
            	$bulk_material = Bulk_material::select('id', 'material_name', 'material_code', 'is_active')
                ->where('company_id', $loggedin_user)
                ->orderBy('material_name', 'ASC')
                ->get();
                return view('bulk_material.index', ['bulk_material'=>$bulk_material, 'loggedin_user'=>$loggedin_user, 'role'=>$role]);
            }
        }
    }

    public function add()
    {
        $loggedin_user = Helper::get_logged_in_user();
        return view('bulk_material.add', ['loggedin_user'=>$loggedin_user]);
    }

    public function insert(Request $request)
    {
    	$request->validate([
            'material_name' => 'required|string',
            'material_code' => 'required|string'
        ]);

        	$bulk_material = new Bulk_material;
			$bulk_material->company_id = $request->company_id;
			$bulk_material->material_name = $request->material_name;
            $bulk_material->material_code = $request->material_code;
            $bulk_material->is_active = 1;
            $bulk_material->save();
			flash('Bulk Material has been added successfully.')->success();
    		return redirect('bulk_material'); 
    }

    public function edit($id)
    {
        $loggedin_user = Helper::get_logged_in_user();

        $bulk_material = Bulk_material::where('bulk_materials.id', $id)
        ->first();
   
        return view('bulk_material.edit', ['bulk_material'=>$bulk_material, 'loggedin_user'=>$loggedin_user]);
    }

    public function update(Request $request)
    {
    	$request->validate([
            'material_name' => 'required|string',
            'material_code' => 'required|string'
        ]);

    	$bulk_material = Bulk_material::find($request->material_id);
		$bulk_material->material_name = $request->material_name;
        $bulk_material->material_code = $request->material_code;
        $bulk_material->save();
		flash('Bulk Material has been updated successfully.')->success();
		return redirect('bulk_material'); 
    }

    public function change_status($id, $status)
    {
        $location = Bulk_material::find($id);
        $location->is_active = $status;
        $location->save();

        if($status==0)
            flash('Bulk Material has been deactivated successfully.')->success();
        else
            flash('Bulk Material has been activated successfully.')->success();
            
        return redirect('bulk_material'); 
    }
}
