<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Skill;
use App\Kra_skill_mapping;
use App\Employee_skill_rating;
use App\Location;
use App\User;
use App\User_Region;
use App\Gate_meeting;
use DB;
use App\Helpers\Helper;
use Auth;

class TrainerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {        
        $role = 1;        
        $role = Auth::user()->role; 

        if($role==0)
        {
            $users = User::select("users.name as trainer_name", 
            "users.id as user_id",
            "users.phone",
            "users.is_active"
            )
            ->where("users.role", 1)
            ->get();
            return view('trainer_user.index', ['users' => $users]);
        }
        if($role==2)
        {
            $users = User::select("users.name as trainer_name", 
            "users.id as user_id",
            "users.phone",
            "users.is_active"
            )
            ->where("users.role", 1)
            ->where("users.company_id", Auth::user()->id)
            ->get();
            return view('trainer_user.index', ['users' => $users]);
        }
        else
            return redirect('logout');
    }


    public function add()
    {        
        $role = 1;        
        $role = Helper::get_role(Auth::user());

        if($role==0)
        {
			$regions = Location::select('id', 'name')
            ->orderBy('name', 'ASC')
            ->get();
	        return view('trainer_user.add', ['regions' => $regions]);
	    }
        else
            return redirect('logout');
    }


    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
            'phone' => 'required|numeric|unique:users'
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->role = 1;
        $user->company_id = Auth::user()->id;
        $user->password = bcrypt($request->password);
        if($user->save())
        {
            if(!empty($request->regions_list))
            {
                if(count($request->regions_list))
                {
                    foreach ($request->regions_list as $row) 
                    {
                        $user_region = new User_Region;
                        $user_region->user_id = $user->id;
                        $user_region->region_id = $row;
                        $user_region->is_active = 1;
                        $user_region->save();
                    }
                }
            }
            flash('Trainer has been added successfully.')->success();
            return redirect('trainers');             
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
            $user = User::select("users.name as trainer_name", 
            "users.id as user_id", 
            "users.*")                       
            ->where("users.id", $id)
            ->first();

            $user_regions = User_Region::where('user_regions.user_id', $id)
            ->where('user_regions.is_active', 1)
            ->leftjoin("locations", "user_regions.region_id", "=", "locations.id") 
            ->select('locations.id', 'locations.name')
            ->get();

            $trainer_region = array();
            if(count($user_regions))
            {
                foreach ($user_regions as $value) 
                {
                    array_push($trainer_region , $value->id);
                }
            }

            $regions = Location::select('id', 'name')
            ->orderBy('name', 'ASC')
            ->get();

            return view('trainer_user.edit', ['user' => $user,'regions' => $regions,'user_regions' => $user_regions,'trainer_region' => $trainer_region]);
        }
    }


    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|numeric'
        ]);

        $user = User::find($request->user_id);
        $user->name = $request->name;
        $user->phone = $request->phone;
        if(!empty($request->password))
        	$user->password = bcrypt($request->password);
        if($user->save())
        {
        	$user_regions = User_Region::where('user_regions.user_id', $request->user_id)
        	->where('user_regions.is_active', 1)
            ->leftjoin("locations", "user_regions.region_id", "=", "locations.id") 
            ->select('locations.id', 'locations.name')
            ->get();

            $trainer_region = array();
            if(count($user_regions))
            {
                foreach ($user_regions as $value) 
                {
                    array_push($trainer_region , $value->id);
                }
            }
            $region_diff = array_diff($trainer_region,$request->regions_list);

            if(!empty($region_diff))
            {
                if(count($region_diff))
                {
                    foreach ($region_diff as $rowdel) 
                    {
                        $user_region_delete = User_Region::where("user_id", $request->user_id)
                        ->where("region_id", $rowdel)
                        ->where('user_regions.is_active', 1)
                        ->update([
				           'is_active' => 0
				        ]);
                    }
                }
            }

            if(!empty($request->regions_list))
            {
                if(count($request->regions_list))
                {
                    foreach ($request->regions_list as $row) 
                    {
                    	$check_user_region = User_Region::where("user_id", $request->user_id)
                        ->where("region_id", $row)
                        ->first();
                        if(!$check_user_region)
                        {
	                        $user_region = new User_Region;
	                        $user_region->user_id = $user->id;
	                        $user_region->region_id = $row;
	                        $user_region->is_active = 1;
	                        $user_region->save();
                    	}
                    	else
                    	{
                    		if($check_user_region['is_active']==0)
                    		{
                    			$user_region = User_Region::find($check_user_region['id']);
		                        $user_region->is_active = 1;
		                        $user_region->save();
                    		}
                    	}
                    }
                }
            }
            flash('Trainer has been updated successfully.')->success();
            return redirect('trainers');             
        }
        else
        {
            flash('Please fill the form correctly.')->error();
            return redirect()->back();
        }
    }

    public function gate_meeting()
    {        
        $role = Auth::user()->role;
        
        if($role==0)
        {
            $meeting = Gate_meeting::leftjoin("users", "users.id", "=", "gate_meetings.trainer_id")
            ->get();
            return view('gate_meetings.index', ['meeting'=>$meeting]);
        }
        else
        {
            return redirect('logout');
        }
    }


    public function status($id, $status)
    {
        if($id )
        {
            $user = User::find($id);
            $user->is_active=$status;
            $user->save();
            flash('Trainer status has been updated successfully.')->success();
            return redirect('trainers');    
        }
    }



}
