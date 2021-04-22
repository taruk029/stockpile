<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Pile;
use App\Date_metron;
use App\Exports\DashboardPile;
use DB;
use App\Helpers\Helper;
use Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use DateTime;
use App\Site_user;
use App\Location;
use App\Site;
use Mail;
use Artisan;

class HomeController extends Controller
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
    public function index(Request $request)
    {        
        $role = 1; 
        $loggedin_user = Helper::get_logged_in_user();
        if(!$loggedin_user) 
        {      
            $role = Auth::user()->role;
            $is_active = Auth::user()->is_active;
        }
        else
        {
            $user = User::find($loggedin_user);
            $role = $user['role']; 
            $is_active = 1;
        }
        $today = Carbon::now()->format('Y-m-d');

        if($role==0)
        {
            return view('admin.home');
        }
        elseif ($role==2) 
        {
            if($is_active==1)
            {
                $results = DB::table('date_metrons')
                ->leftjoin("piles", "date_metrons.pile_id", "=", "piles.id")
                ->leftjoin("locations", "locations.id", "=", "piles.location_id")
                ->leftjoin("sites", "sites.id", "=", "piles.site_id")
                ->select('date_metrons.id as date_metron_id', 'piles.pile_reference_id', 'piles.pile_name', 'piles.id as pileId', 'piles.bulk_density', 'piles.moisture', 'piles.additional_info', 'locations.name as location_name', 'sites.name as site_name', 'date_metrons.pile_type','date_metrons.start_time','date_metrons.end_time','date_metrons.volume','date_metrons.three_dmodel','date_metrons.date_of_survey')
                ->where("date_metrons.company_id", $loggedin_user)
                ->where("date_metrons.volume", "!=", "")
                ->orderBy("piles.pile_reference_id", "desc");

                $sites = array();

                if($request->pile)
                {
                    $results->where('piles.pile_reference_id', $request->pile);
                } 
                if($request->sites)
                {
                    $results->where('date_metrons.site_id', $request->sites);
                } 
                if($request->location)
                {
                    $results->where('date_metrons.location_id', $request->location);

                    $sites = Site::where('sites.company_id', $loggedin_user)
                    ->where('sites.location_id', $request->location)
                    ->orderBy('sites.name', 'ASC')
                    ->get();
                } 
                if(!empty($request->from_date) && !empty($request->to_date))
                {
                    $start = date_create($request->from_date);
                    $start_date = date_format($start,'Y-m-d');
                    
                    $end = date_create($request->to_date);
                    $end_date = date_format($end,'Y-m-d');
                    $results->whereBetween('date_metrons.date_of_survey', [$start_date, $end_date]);
                } 
                $piles =  $results->get();

                $piles_dd = Pile::leftjoin("date_metrons", "date_metrons.pile_id", "=", "piles.id")
                ->select('piles.pile_reference_id', 'piles.pile_name', 'piles.id as pileId')
                ->where("date_metrons.company_id", $loggedin_user)
                ->orderBy("piles.pile_reference_id", "desc")
                ->get();

                $location = Location::where('company_id', $loggedin_user)
                ->orderBy('name', 'ASC')
                ->get();

                return view('companies.home', ['piles'=>$piles, 'piles_dd'=>$piles_dd, 'location'=>$location, 'sites'=>$sites]);
            }
            else
            {
                flash('You have been deactivated. Please contact the system admin.')->error();
                Auth::logout();
                return redirect('login');
            }            
        }
        elseif ($role==4) 
        {
            if($is_active==1)
                return view('staff_login.home');
            else
            {
                flash('You have been deactivated. Please contact the system admin.')->error();
                Auth::logout();
                return redirect('login');
            }            
        }
        elseif ($role==3) 
        {
            if($is_active==1)
            {
                $sites = Site_user::select('site_id')->where('user_id',$loggedin_user)->where('is_active',1)->get();
                $site_array = array();
                if($sites)
                {
                    foreach($sites as $row)
                    {
                        array_push($site_array, $row->site_id);
                    }
                }
                $results = DB::table('date_metrons')
                ->leftjoin("piles", "date_metrons.pile_id", "=", "piles.id")
                ->leftjoin("locations", "locations.id", "=", "piles.location_id")
                ->leftjoin("sites", "sites.id", "=", "piles.site_id")
                ->select('date_metrons.id as date_metron_id', 'piles.pile_reference_id', 'piles.pile_name', 'piles.id as pileId', 'piles.bulk_density', 'piles.moisture', 'piles.additional_info', 'locations.name as location_name', 'sites.name as site_name', 'date_metrons.pile_type','date_metrons.start_time','date_metrons.end_time','date_metrons.volume','date_metrons.three_dmodel','date_metrons.date_of_survey')
                ->whereIn('piles.site_id', $site_array)
                ->where("date_metrons.volume", "!=", "")
                ->orderBy("piles.pile_reference_id", "desc");

                $sites = array();

                if($request->pile)
                {
                    $results->where('piles.pile_reference_id', $request->pile);
                } 
                if($request->sites)
                {
                    $results->where('date_metrons.site_id', $request->sites);
                } 
                if($request->location)
                {
                    $results->where('date_metrons.location_id', $request->location);

                    $sites = Site::whereIn('sites.id', $site_array)
                    ->where('sites.location_id', $request->location)
                    ->orderBy('sites.name', 'ASC')
                    ->get();
                } 
                if(!empty($request->from_date) && !empty($request->to_date))
                {
                    $start = date_create($request->from_date);
                    $start_date = date_format($start,'Y-m-d');
                    
                    $end = date_create($request->to_date);
                    $end_date = date_format($end,'Y-m-d');
                    $results->whereBetween('date_metrons.date_of_survey', [$start_date, $end_date]);
                } 
                $piles =  $results->get();

                $piles_dd = Pile::leftjoin("date_metrons", "date_metrons.pile_id", "=", "piles.id")
                ->select('piles.pile_reference_id', 'piles.pile_name', 'piles.id as pileId')
                ->whereIn("date_metrons.site_id", $site_array)
                ->orderBy("piles.pile_reference_id", "desc")
                ->get();
                
                
                $location = Location::leftjoin("location_users", "location_users.location_id", "=", "locations.id")
                ->select('locations.id','locations.name')
                ->where('location_users.user_id', $loggedin_user)
                ->orderBy('name', 'ASC')
                ->get();

                return view('company_user.home', ['piles'=>$piles, 'piles_dd'=>$piles_dd, 'location'=>$location, 'sites'=>$sites]);
            }
            else
            {
                flash('You have been deactivated. Please contact the system admin.')->error();
                Auth::logout();
                return redirect('login');
            }            
        }
        else
        {
            return view('trainers.home');
        }
    }

    public function dummy()
    {
        return view('companies.dummy');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('login');
    }


    public function save_date()
    {
        $date = Temp::get();
        foreach ($date as $key) 
        {
            /*echo $key->plan_id;*/
            $plan = Plan::find($key->plan_id);
            $plan->date = $key->date;
            $plan->save();
        }
    }

    public function dashboard_excel(Request $request)
    {
        $role = 1;        
        $role = Auth::user()->role;
        $loggedin_user = Helper::get_logged_in_user();
        if($role==2 || $role==3)
        {
            return Excel::download(new DashboardPile(), 'Pile_report'.date('His').'.xlsx');
        }
    } 
    
    public function send_mail(Request $request)
    {
        /*Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('optimize:clear');
        die;*/
        
        /*if(!empty($request->js_email))
        {*/
            $email_data['to'] =  'support@stockpilevolume.com';
            $email_data['pile'] =  $request->pile_code;
            $email_data['comment'] =  $request->message; 
            $email_data['from'] =  $request->email; 
            
            Mail::send('emails.send_message', $email_data, function($message) use ($email_data) 
            {
                $message->to($email_data['to'], 'Support StockPile Volume')
                ->subject("Support StockPile Volume");
                $message->from('support@stockpilevolume.com','StockPile Volume'); 
            });
        flash('Enquiry has been sent successfully.')->success();
		return redirect('/'); 
       /* }*/
    }
}
