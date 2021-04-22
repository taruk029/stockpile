<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location;
use App\User;
use App\Country;
use App\Location_user;
use DB;
use App\Helpers\Helper;
use Auth;

class LocationController extends Controller
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
                $locations = Location_user::select('location_id')->where('user_id',$loggedin_user)->get();
                $location_array = array();
                if($locations)
                {
                    foreach($locations as $row)
                    {
                        array_push($location_array, $row->location_id);
                    }
                }
                $location = Location::select('locations.*', 'countries.name as country_name')
                ->leftjoin("countries", "locations.country", "=", "countries.id")
                ->whereIn('locations.id', $location_array)
                ->orderBy('locations.name', 'ASC')
                ->get();
                return view('location.index', ['location'=>$location, 'loggedin_user'=>$loggedin_user, 'role'=>$role]);
            }
            else
            {
                $location = Location::select('locations.*', 'countries.name as country_name')
                ->leftjoin("countries", "locations.country", "=", "countries.id")
                ->where('company_id', $loggedin_user)
                ->orderBy('name', 'ASC')
                ->get();
                return view('location.index', ['location'=>$location, 'loggedin_user'=>$loggedin_user, 'role'=>$role]);
            }
        }
    }

    public function add()
    {
        $loggedin_user = Helper::get_logged_in_user();

        $country = Country::where('is_active', 1)
            ->select('id', 'name')
            ->orderBy('name', 'ASC')
            ->get();

        $users = User::select("users.name as user_name", 
            "users.id as user_id", 
            "user_profiles.user_type")
        ->leftjoin("user_profiles", "users.id", "=", "user_profiles.user_id")
        ->where("users.company_id", $loggedin_user)
        ->where("users.role", 3)
        ->get();
        return view('location.add', ['users'=>$users, 'country'=>$country, 'loggedin_user'=>$loggedin_user]);
    }

    public function insert(Request $request)
    {
    	$request->validate([
            'location_name' => 'required|string',
            'address' => 'required|string',
            'state' => 'required|string',
            'zip' => 'required|numeric',
            'country' => 'required'
        ]);

        	$location = new Location;
			$location->company_id = $request->company_id;
            $location->name = $request->location_name;
            $location->address = $request->address;
            $location->state = $request->state;
            $location->zip = $request->zip;
            $location->country = $request->country;
            $location->is_active = 1;
			if($location->save())
            {
                if(!empty($request->user_list))
            {
                if(count($request->user_list))
                {
                    foreach ($request->user_list as $row) 
                    {
                        $location_user = new Location_user;
                        $location_user->company_id = $request->company_id;
                        $location_user->user_id = $row;
                        $location_user->location_id = $location->id;
                        $location_user->is_active = 1;
                        $location_user->save();
                    }
                }
            }
            }
			flash('Location has been added successfully.')->success();
    		return redirect('locations'); 
    }

    public function edit($id)
    {
        $loggedin_user = Helper::get_logged_in_user();

        $location = Location::where('locations.id', $id)
        ->first();

        $country = Country::where('is_active', 1)
            ->select('id', 'name')
            ->orderBy('name', 'ASC')
            ->get();

        $users = User::select("users.name as user_name", 
            "users.id as user_id", 
            "user_profiles.user_type")
        ->leftjoin("user_profiles", "users.id", "=", "user_profiles.user_id")
        ->where("users.company_id", $loggedin_user)
        ->where("users.role", 3)
        ->get();    

        $map_users = array();
        $mapped_users = Location_user::leftjoin("users", "location_users.user_id", "=", "users.id")
        ->leftjoin("user_profiles", "users.id", "=", "user_profiles.user_id")
        ->select("users.name as user_name", "users.id as user_id", "user_profiles.user_type")
        ->where("location_users.is_active", 1)
        ->where("location_users.company_id", $loggedin_user)
        ->where("location_users.location_id", $id)
        ->get(); 
        if($mapped_users)
        {
            foreach ($mapped_users as $value) 
            {
                array_push($map_users, $value->user_id);
            }
        }   

        return view('location.edit', ['location'=>$location, 'country'=>$country, 'users'=>$users, 'loggedin_user'=>$loggedin_user, 'map_users'=>$map_users, 'mapped_users'=>$mapped_users ]);
    }

    public function update(Request $request)
    {
    	$request->validate([
            'location_name' => 'required|string',
            'address' => 'required|string',
            'state' => 'required|string',
            'zip' => 'required|numeric',
            'location_id' => 'required|numeric',
            'country' => 'required'
        ]);

    	$location = Location::find($request->location_id);
        $location->company_id = $request->company_id;
        $location->name = $request->location_name;
        $location->address = $request->address;
        $location->state = $request->state;
        $location->zip = $request->zip;
        $location->country = $request->country;
        if($location->save())
        {
            if(!empty($request->user_list))
            {
                if(count($request->user_list))
                {
                    DB::table('location_users')
                    ->where('company_id', $request->company_id)
                    ->where('location_id', $request->location_id)
                    ->update(array('is_active' => 0));
                    foreach ($request->user_list as $row) 
                    {
                        $mapped_users = Location_user::where("location_users.company_id", $request->company_id)
                        ->where("location_users.location_id", $request->location_id)
                        ->where("location_users.user_id", $row)
                        ->first(); 
                        if(!$mapped_users)
                        {
                            $location_user = new Location_user;
                            $location_user->company_id = $request->company_id;
                            $location_user->user_id = $row;
                            $location_user->location_id = $request->location_id;
                            $location_user->save();
                        }
                        else
                        {
                            if($mapped_users->is_active==0)
                            {
                                DB::table('location_users')
                                ->where('company_id', $request->company_id)
                                ->where('user_id', $row)
                                ->where('location_id', $request->location_id)
                                ->update(array('is_active' => 1));
                            }
                        }
                    }
                }
            }
        }
		flash('Location has been updated successfully.')->success();
		return redirect('locations'); 
    }

    public function change_status($id, $status)
    {
        $location = Location::find($id);
        $location->is_active = $status;
        $location->save();

        if($status==0)
            flash('Location has been deactivated successfully.')->success();
        else
            flash('Location has been activated successfully.')->success();
            
        return redirect('locations'); 
    }
    
}
