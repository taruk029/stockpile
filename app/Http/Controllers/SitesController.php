<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location;
use App\Site;
use App\User;
use App\Country;
use App\Location_user;
use App\Site_user;
use DB;
use App\Helpers\Helper;
use Auth;

class SitesController extends Controller
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
                $sites = Site_user::select('site_id')->where('user_id',$loggedin_user)->get();
                $site_array = array();
                if($sites)
                {
                    foreach($sites as $row)
                    {
                        array_push($site_array, $row->site_id);
                    }
                }
                $sites = Site::select('sites.*', 'locations.name as location_name')
                ->leftjoin("locations", "locations.id", "=", "sites.location_id")
                ->whereIn('sites.id', $site_array)
                ->orderBy('sites.name', 'ASC')
                ->get();
                return view('sites.index', ['sites'=>$sites, 'loggedin_user'=>$loggedin_user, 'role'=>$role]);
            }
            else
            {
                $sites = Site::select('sites.*', 'locations.name as location_name')
                ->leftjoin("locations", "locations.id", "=", "sites.location_id")
                ->where('sites.company_id', $loggedin_user)
                ->orderBy('sites.name', 'ASC')
                ->get();
                return view('sites.index', ['sites'=>$sites, 'loggedin_user'=>$loggedin_user, 'role'=>$role]);
            }
        }    	
    }

    public function add()
    {
        $loggedin_user = Helper::get_logged_in_user();

        $location = Location::select('id', 'name')
        ->select('locations.*', 'countries.name as country_name')
        ->leftjoin("countries", "locations.country", "=", "countries.id")
        ->where('locations.company_id', $loggedin_user)
        ->where('locations.is_active', 1)
        ->orderBy('name', 'ASC')
        ->get();

        $users = User::select("users.name as user_name", 
            "users.id as user_id", 
            "user_profiles.user_type")
        ->leftjoin("user_profiles", "users.id", "=", "user_profiles.user_id")
        ->where("users.company_id", $loggedin_user)
        ->where("users.role", 3)
        ->get();
        return view('sites.add', ['users'=>$users, 'location'=>$location, 'loggedin_user'=>$loggedin_user]);
    }

    public function insert(Request $request)
    {
    	$request->validate([
            'site_name' => 'required|string',
            'site_type' => 'required',
            'location' => 'required|numeric'
        ]);

        	$site = new Site;
			$site->company_id = $request->company_id;
            $site->location_id = $request->location;
            $site->name = $request->site_name;
            $site->site_type = $request->site_type;
            $site->is_active = 1;
			if($site->save())
            {
                if(!empty($request->user_list))
            {
                if(count($request->user_list))
                {
                    foreach ($request->user_list as $row) 
                    {
                        $site_user = new Site_user;
                        $site_user->company_id = $request->company_id;
                        $site_user->user_id = $row;
                        $site_user->site_id = $site->id;
                        $site_user->is_active = 1;
                        $site_user->save();
                    }
                }
            }
            }
			flash('Site has been added successfully.')->success();
    		return redirect('sites'); 
    }

    public function edit($id)
    {
        $loggedin_user = Helper::get_logged_in_user();

        $site = Site::where('id', $id)
        ->first();

        $users = User::select("users.name as user_name", 
            "users.id as user_id", 
            "user_profiles.user_type")
        ->leftjoin("user_profiles", "users.id", "=", "user_profiles.user_id")
        ->where("users.company_id", $loggedin_user)
        ->where("users.role", 3)
        ->get(); 

        $location = Location::select('id', 'name')
        ->select('locations.*', 'countries.name as country_name')
        ->leftjoin("countries", "locations.country", "=", "countries.id")
        ->where('locations.company_id', $loggedin_user)
        ->where('locations.is_active', 1)
        ->orderBy('name', 'ASC')
        ->get();   

        $map_users = array();
        $mapped_users = Site_user::leftjoin("users", "site_users.user_id", "=", "users.id")
        ->leftjoin("user_profiles", "users.id", "=", "user_profiles.user_id")
        ->select("users.name as user_name", "users.id as user_id", "user_profiles.user_type")
        ->where("site_users.is_active", 1)
        ->where("site_users.company_id", $loggedin_user)
        ->where("site_users.site_id", $id)
        ->get(); 
        if($mapped_users)
        {
            foreach ($mapped_users as $value) 
            {
                array_push($map_users, $value->user_id);
            }
        }   

        return view('sites.edit', ['site'=>$site, 'users'=>$users, 'location'=>$location, 'loggedin_user'=>$loggedin_user, 'map_users'=>$map_users, 'mapped_users'=>$mapped_users ]);
    }

    public function update(Request $request)
    {
    	$request->validate([
            'site_name' => 'required|string',
            'site_type' => 'required',
            'location' => 'required|numeric'
        ]);

        $site = Site::find($request->site_id);
        $site->company_id = $request->company_id;
        $site->location_id = $request->location;
        $site->name = $request->site_name;
        $site->site_type = $request->site_type;
        if($site->save())
        {
            if(!empty($request->user_list))
            {
                if(count($request->user_list))
                {
                    DB::table('site_users')
                    ->where('company_id', $request->company_id)
                    ->where('site_id', $request->site_id)
                    ->update(array('is_active' => 0));
                    foreach ($request->user_list as $row) 
                    {
                        $mapped_users = Site_user::where("site_users.company_id", $request->company_id)
                        ->where("site_users.site_id", $request->site_id)
                        ->where("site_users.user_id", $row)
                        ->first(); 
                        if(!$mapped_users)
                        {
                            $site_user = new Site_user;
                            $site_user->company_id = $request->company_id;
                            $site_user->user_id = $row;
                            $site_user->site_id = $request->site_id;
                            $site_user->save();
                        }
                        else
                        {
                            if($mapped_users->is_active==0)
                            {
                                DB::table('site_users')
                                ->where('company_id', $request->company_id)
                                ->where('user_id', $row)
                                ->where('site_id', $request->site_id)
                                ->update(array('is_active' => 1));
                            }
                        }
                    }
                }
            }
        }
		flash('Site has been updated successfully.')->success();
		return redirect('sites'); 
    }

    public function change_status($id, $status)
    {
        $site = Site::find($id);
        $site->is_active = $status;
        $site->save();

        if($status==0)
            flash('Site has been deactivated successfully.')->success();
        else
            flash('Site has been activated successfully.')->success();
            
        return redirect('sites'); 
    }
    
}
