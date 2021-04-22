<?php

namespace App\Helpers;


use App\Location;
use App\User_profile;
use App\User;
use App\Location_user;
use App\Site_user;
use DB;
use Carbon\Carbon;
use Auth;
use Session;
use App\Pile;

class Helper {


    public static function get_logged_in_user() 
    {
      if(isset(Auth::user()->id))
          return Auth::user()->id;
      else
        return Session::get('loggedin_user');
    }

    public static function get_logged_in_user_role($id) 
    {
      if($id)
      {
          $user = User::find($id);
          if($user)
          {
            return $user['role'];
          }
          else
            return "NA";
      }
      else
        return "NA";      
    }

    public static function get_logged_in_user_company($id) 
    {
      if($id)
      {
          $user = User::find($id);
          if($user)
          {
            return $user['company_id'];
          }
          else
            return "NA";
      }
      else
        return "NA";      
    }

    public static function get_logged_in_user_type($id) 
    {
      if($id)
      {
          $user = User_profile::where('user_id', $id)->first();
          if($user)
          {
            return $user['user_type'];
          }
          else
            return "NA";
      }
      else
        return "NA";      
    }

    public static function get_mapped_user($company_id, $location_id) 
    {
      $map = "";
      $users = Location_user::select("users.name as user_name", "user_profiles.user_type")
        ->leftjoin("users", "location_users.user_id", "=", "users.id")
        ->leftjoin("user_profiles", "users.id", "=", "user_profiles.user_id")
        ->where("location_users.is_active", 1)
        ->where("location_users.company_id", $company_id)
        ->where("location_users.location_id", $location_id)
        ->get();
        if($users)
        {
          $user_arr = array(); 
          foreach($users as $row)
          {
            array_push($user_arr, $row->user_name."- User Type ".$row->user_type);
          }
          if($user_arr)
          {
            $map = implode("<br>", $user_arr);
          }
        }
        return $map;
    }

    public static function get_sites_mapped_user($company_id, $site_id) 
    {
      $map = "";
      $users = Site_user::select("users.name as user_name", "user_profiles.user_type")
        ->leftjoin("users", "site_users.user_id", "=", "users.id")
        ->leftjoin("user_profiles", "users.id", "=", "user_profiles.user_id")
        ->where("site_users.is_active", 1)
        ->where("site_users.company_id", $company_id)
        ->where("site_users.site_id", $site_id)
        ->get();
        if($users)
        {
          $user_arr = array(); 
          foreach($users as $row)
          {
            array_push($user_arr, $row->user_name."- User Type ".$row->user_type);
          }
          if($user_arr)
          {
            $map = implode("<br>", $user_arr);
          }
        }
        return $map;
    }

    public static function get_location_name($id) 
    {
      $name = Location::select('name')->where('id', $id)->first();
      return $name['name'];
    }

    public static function get_role($id) 
    {
      $role = User::select('role')->where('id', $id)->first();
      return $role['role'];
    }

    public static function get_pile_by_code($code) 
    {
      $pile = Pile::select('id')->where('pile_reference_id', $code)->first();
      if($pile)
        return $pile['id'];
      else
        return 0;
    }


    
}
