<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Company;
use App\Location;
use App\User_profile;
use Validator;
use Auth;
use DB;
use App\Helpers\Helper;

class StaffController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $loggedin_user = Helper::get_logged_in_user();
		$users = User::select("users.name as user_name", 
			"users.id as user_id", 
			"users.phone", 
			"users.email", 
			"users.is_active", 
			"users.phone")
        ->where("users.role", 4)
		->get();
        return view('staff.index', ['users' => $users]);
    }

    public function add()
    {
        return view('staff.add');
    }

    public function insert(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'password' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'phone' => 'required|numeric|unique:users'
        ]);

        $user = new User;
        $user->name = $request->first_name." ".$request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->role = 4;
        $user->password = bcrypt($request->password);
        $user->is_active = 1;
        if($user->save())
        {
        	flash('Staff has been added successfully.')->success();
            return redirect('staff');
        }
        else
        {
            flash('Please fill the form correctly.')->error();
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        if($id)
        {
            $user = User::select("users.name as user_name", 
            "users.id as user_id", 
            "users.*")          
            ->where("users.id", $id)
            ->where("users.role", 4)
            ->first();
            return view('staff.edit', ['user' => $user]);
        }
    }


    public function update(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string'
        ]);

        $user = User::find($request->user_id);
        $user->name = $request->first_name." ".$request->last_name;
        if(!empty($request->password))
            $user->password = bcrypt($request->password);
        if($user->save())
        {
            flash('Staff has been updated successfully.')->success();
            return redirect('staff');             
        }
        else
        {
            flash('Please fill the form correctly.')->error();
            return redirect()->back();
        }
    }

    public function change_status($id, $status)
    {
        $user = User::find($id);
        $user->is_active = $status;
        $user->save();

        if($status==0)
            flash('Staff has been deactivated successfully.')->success();
        else
            flash('Staff has been activated successfully.')->success();
            
        return redirect('staff'); 
    }
}
