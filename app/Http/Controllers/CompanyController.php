<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Company;
use App\Location;
use App\Country;
use App\User_profile;
use Validator;
use Auth;
use DB;
use App\Helpers\Helper;
use Carbon\Carbon;

class CompanyController extends Controller
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
        $role = Auth::user()->role;
        if($role==0)
        {
    		$companies = Company::select("users.id as com_id", "users.name as company_name", "users.email", "users.phone", "users.is_active", "countries.name as country_name", "users.created_at", "companies.*")
    		->leftjoin("countries", "companies.country", "=", "countries.id")
            ->leftjoin("users", "companies.user_id", "=", "users.id")
    		->get();
            return view('companies.index', ['companies' => $companies]);
        }
        else
            return redirect('logout');
    }

    public function add()
    {
    	 $country = Country::where('is_active', 1)
            ->select('id', 'name')
            ->orderBy('name', 'ASC')
            ->get();
        return view('companies.add', ['country' => $country]);
    }

	public function get_states(Request $request)
    {
    	 $states = Location::where('parent_id',  $request->id)
            ->select('id', 'name')
            ->orderBy('name', 'ASC')
            ->get();
            $data = array();
        for($i=0;$i<count($states);$i++){
           $data[] = array('id'=>$states[$i]->id,'name'=>$states[$i]->name);
        }
        $output  = $data;
        echo json_encode($output);
    }

    public function insert_company(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string',
            'address' => 'required|string',
            'state' => 'required|string',
            'password' => 'required|string',
            'zip' => 'required|numeric',
            'country' => 'required',
            'email' => 'required|string|email|unique:users',
            'phone' => 'required|numeric|unique:users'
        ]);
        $country_name = "";
        $state_name = "";

        $user = new User;
        $user->name = $request->company_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->role = 2;
        $user->password = bcrypt($request->password);
        $user->is_active = $request->is_active;
        if($user->save())
        {        	
        	$company = new Company;
        	$company->company_id = $this->get_new_order_id();
            $company->user_id = $user->id;
        	$company->address = $request->address;
        	$company->state = $request->state;
        	$company->zip = $request->zip;
        	$company->country = $request->country;
            $company->expiry_date = Carbon::parse($request->expiry_date)->format('Y-m-d');
        	$company->save();

    		flash('Client has been added successfully.')->success();
    		return redirect('clients'); 
        }
        else
        {
        	flash('Please fill the form correctly.')->error();
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $companies = Company::select("users.id as company_id", "users.name as company_name", "users.email", "users.phone", "users.is_active", "companies.address", "companies.state", "companies.zip", "companies.country", "companies.expiry_date", "users.created_at")
        ->leftjoin("users", "companies.user_id", "=", "users.id")
        ->where("users.id", $id)
        ->first();
        
        $country = Country::where('is_active', 1)
            ->select('id', 'name')
            ->orderBy('name', 'ASC')
            ->get();

        return view('companies.edit', ['companies' => $companies, 'country' => $country]);
    }


    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string',
            'address' => 'required|string',
            'state' => 'required|string',
            'zip' => 'required|numeric',
            'country' => 'required'
        ]);

        $user = User::find($request->company_id);
        $user->name = $request->company_name;
        $user->is_active = $request->is_active;
        if(!empty($request->password))
        {
            $user->password = bcrypt($request->password);
        }
        if($user->save())
        {
            $com_id = Company::where("user_id", $request->company_id)->first();
            $company = Company::find($com_id['id']);
            $company->user_id = $user->id;
            $company->address = $request->address;
            $company->state = $request->state;
            $company->zip = $request->zip;
            $company->country = $request->country;
            $company->expiry_date = Carbon::parse($request->expiry_date)->format('Y-m-d');
            $company->save();

            flash('Client has been updated successfully.')->success();
            return redirect('clients'); 
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
            flash('Client has been deactivated successfully.')->success();
        else
            flash('Client has been activated successfully.')->success();
            
        return redirect('clients'); 
    }

    public function get_new_order_id()
    {
      $count = Company::count();
      $count = $count+1;
      $company_id = "SV".date("is").$count;
      $check_order = Company::where("company_id", $company_id)->first();
      if($check_order)
      {
        self::get_new_order_id();
      }
      return $company_id;
    }
}
