<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Skill;
use App\Kra_skill_mapping;
use App\Employee_skill_rating;
use App\Location;
use App\User;
use App\User_Region;
use App\Start_day_plan;
use App\Plan;
use App\Day;
use App\End_day_plan;
use App\Outlet_form_plan;
use App\Unproductive_reason_list;
use DB;
use App\Helpers\Helper;
use App\Exports\BladeExport;
use Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {        
        $role = 1;        
        $role = Auth::user()->role;

        if($role==0)
        {
            $today = Carbon::now()->format('Y-m-d');;
            $plans = array();
            $results = DB::table('plans')
            ->leftjoin("users", "plans.trainer_id", "=", "users.id")
            ->leftjoin("days", "plans.weekly_off", "=", "days.id")
            ->leftjoin("locations", "plans.trainer_region_id", "=", "locations.id")
            ->select("users.name as trainer_name", "days.name as weekly_off_day", "locations.name as region_name","plans.*","plans.id as plan_id")
            ->where("users.role", 1);

            if($request->date!="")
            { 
                $date = Carbon::parse($request->date)->format('Y-m-d');;
                $results->where('plans.date', $date);
            }
            else
                $results->where("plans.date", $today);
                 
            $plans = $results->get();
           /* echo "<pre>";
            print_r($plans);die;*/
            return view('reports.index', ['plans' => $plans]);
        }
        else
            return redirect('logout');
    }

    public function synopsis_excel(Request $request)
    {
        $role = 1;        
        $role = Auth::user()->role;

        if($role==0)
        {
            $today = Carbon::now()->format('Y-m-d');;
            $plans = array();
            $results = DB::table('plans')
            ->leftjoin("users", "plans.trainer_id", "=", "users.id")
            ->leftjoin("days", "plans.weekly_off", "=", "days.id")
            ->leftjoin("locations", "plans.trainer_region_id", "=", "locations.id")
            ->select("users.name as trainer_name", "days.name as weekly_off_day", "locations.name as region_name","plans.*","plans.id as plan_id")
            ->where("users.role", 1);

            $date = $today;
            if($request->date!="")
            { 
                $date = Carbon::parse($request->date)->format('Y-m-d');;
                $results->where('plans.date', $date);
            }
            else
                $results->where("plans.date", $today);
                 
            $plans = $results->get();
            $datas = array();
            $data = array();
            $i = 1;
            foreach($plans as $row)
            {
                $datas = array(
                    "Sr. No."=>$i,
                    "Date"=>$date,
                    "Trainer"=>$row->trainer_name,
                    "Region"=>$row->region_name,
                    "Distributor Code"=>$row->distributor_code,
                    "Distributor Name"=>$row->distributor_name,
                    "DBSR Code"=>$row->dbsr_code,
                    "DBSR Name"=>$row->dbsr_name,
                    "Range Compliance"=>$row->range_compliance?$row->range_compliance:'',
                    "MSL"=>$row->msl?$row->msl:'',
                    "No of outlets in the beat"=>\App\Helpers\Helper::get_total_beats($row->plan_id)?\App\Helpers\Helper::get_total_beats($row->plan_id):"NA",
                    "No of Outlets Covered"=>\App\Helpers\Helper::get_covered_outlets($row->plan_id)?\App\Helpers\Helper::get_covered_outlets($row->plan_id):"NA",
                    "No of Productive outlets"=>\App\Helpers\Helper::get_productive_outlets($row->plan_id)?\App\Helpers\Helper::get_productive_outlets($row->plan_id):"NA",
                    "No of Unproductive Outlets"=> \App\Helpers\Helper::get_unproductive_outlets($row->plan_id)?\App\Helpers\Helper::get_unproductive_outlets($row->plan_id):"NA",
                    "Reasons for unproductivity"=>\App\Helpers\Helper::get_unproductive_reason_excel($row->plan_id)?\App\Helpers\Helper::get_unproductive_reason_excel($row->plan_id):"NA"
                );
                array_push($data, $datas);
                $i++;
            }
            Excel::create('synopsis-'.$date, function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
            })->download('xlsx');
        }
    }


    public function start_day(Request $request)
    {        
        $role = 1;        
        $role = Auth::user()->role;

        if($role==0)
        {
            $today = Carbon::now()->format('Y-m-d');;
            $plans = array();
            $results = DB::table('start_day_plans')
            ->leftjoin("plans", "start_day_plans.plan_id", "=", "plans.id")
            ->leftjoin("users", "plans.trainer_id", "=", "users.id")
            ->select("users.name as trainer_name", "start_day_plans.*", "plans.*")
            ->where("users.role", 1);


            if($request->date!="")
            { 
                $date = Carbon::parse($request->date)->format('Y-m-d');;
                $results->where('plans.date', $date);
            }
            else
                $results->where("plans.date", $today);
                 
            $plans = $results->get();
           /* echo "<pre>";
            print_r($plans);die;*/
            return view('reports.start_day', ['plans' => $plans]);
        }
        else
            return redirect('logout');
    }


    public function start_excel(Request $request)
    {
        $role = 1;        
        $role = Auth::user()->role;

        if($role==0)
        {
            $today = Carbon::now()->format('Y-m-d');;
            $plans = array();
            $results = DB::table('start_day_plans')
            ->leftjoin("plans", "start_day_plans.plan_id", "=", "plans.id")
            ->leftjoin("users", "plans.trainer_id", "=", "users.id")
            ->select("users.name as trainer_name", "start_day_plans.*", "plans.*")
            ->where("users.role", 1);

            $date = $today;
            if($request->date!="")
            { 
                $date = Carbon::parse($request->date)->format('Y-m-d');
                $results->where('plans.date', $date);
            }
            else
                $results->where("plans.date", $today);
                 
            $plans = $results->get();
            $datas = array();
            $data = array();
            $i = 1;
            foreach($plans as $row)
            {
                $datas = array(
                    "Sr. No."=>$i,
                    "Date"=>$date,
                    "DB Code"=>$row->distributor_code,
                    "DB Name"=>$row->distributor_name,
                    "DBSR Code"=>$row->dbsr_code,
                    "DBSR Name"=>$row->dbsr_name,
                    "Trainer"=>$row->trainer_name,
                    "Outlets in the beat"=>$row->start_beats,
                    "Start Range Compliance"=>$row->start_range_compliance,
                    "Meeting Time"=>$row->meeting_time,
                    "Town"=>$row->town,
                    "Market"=>$row->market,
                    "Route Code"=>$row->route_code,
                    "DBSR First Shop"=>$row->dbsr_first_shop
                );
                array_push($data, $datas);
                $i++;
            }
            Excel::create('start_day_report-'.$date, function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
            })->download('xlsx');
        }
    }

    public function end_day(Request $request)
    {        
        $role = 1;        
        $role = Auth::user()->role;

        if($role==0)
        {
            $today = Carbon::now()->format('Y-m-d');
            $plans = array();
            $results = DB::table('end_day_plans')
            ->leftjoin("plans", "end_day_plans.plan_id", "=", "plans.id")
            ->leftjoin("users", "plans.trainer_id", "=", "users.id")
            ->select("users.name as trainer_name", "end_day_plans.*", "plans.*")
            ->where("users.role", 1);

            if($request->date!="")
            { 
                $date = Carbon::parse($request->date)->format('Y-m-d');
                $results->where('plans.date', $date);
            }
            else
                $results->where("plans.date", $today);
                 
            $plans = $results->get();
           /* echo "<pre>";
            print_r($plans);die;*/
            return view('reports.end_day', ['plans' => $plans]);
        }
        else
            return redirect('logout');
    }

    public function end_excel(Request $request)
    {
        $role = 1;        
        $role = Auth::user()->role;

        if($role==0)
        {
            $today = Carbon::now()->format('Y-m-d');
            $plans = array();
            $results = DB::table('end_day_plans')
            ->leftjoin("plans", "end_day_plans.plan_id", "=", "plans.id")
            ->leftjoin("users", "plans.trainer_id", "=", "users.id")
            ->select("users.name as trainer_name", "end_day_plans.*", "plans.*")
            ->where("users.role", 1);

            $date = $today;
            if($request->date!="")
            { 
                $date = Carbon::parse($request->date)->format('Y-m-d');
                $results->where('plans.date', $date);
            }
            else
                $results->where("plans.date", $today);
                 
            $plans = $results->get();
            $datas = array();
            $data = array();
            $i = 1;
            foreach($plans as $row)
            {
                $datas = array(
                    "Sr. No."=>$i,
                    "Date"=>$date,
                    "DB Code"=>$row->distributor_code,
                    "DB Name"=>$row->distributor_name,
                    "DBSR Code"=>$row->dbsr_code,
                    "DBSR Name"=>$row->dbsr_name,
                    "Trainer"=>$row->trainer_name,
                    "End Range Compliance"=>$row->end_range_compliance,
                    "Range Compliance MTD"=>$row->range_compliance_mtd,
                    "Coaching Feedback"=>$row->coaching_feedback,
                    "Action Plan"=>$row->action_plan,
                    "Briefing Taken From"=>$row->briefing_taken_from,
                    "Briefing Taken To"=>$row->briefing_taken_to,
                    "DBSR Last Shop Meeting"=>$row->dbsr_last_shop_meeting,
                    "Sign Off Time"=>$row->exit_time
                );
                array_push($data, $datas);
                $i++;
            }
            Excel::create('end_day_report-'.$date, function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
            })->download('xlsx');
        }
    }


    public function dbsr_report(Request $request)
    {        
        $role = 1;        
        $role = Auth::user()->role;
        if($role==0)
        {
            $today = Carbon::now()->format('Y-m-d');
            $plans = array();
            $results = DB::table('plans')
            ->leftjoin("users", "plans.trainer_id", "=", "users.id")
            ->leftjoin("locations", "plans.trainer_region_id", "=", "locations.id")
            ->select("users.name as trainer_name", "locations.name as region_name","plans.*","plans.id as plan_id", DB::raw('count(*) as counts'))
            ->where("users.role", 1);

            if($request->date!="")
            { 
                $range = explode("-", $request->date);
                $start_date = Carbon::parse($range[0])->format('Y-m-d');
                $end_date = Carbon::parse($range[1])->format('Y-m-d');
                $results->whereBetween('plans.date', array($start_date, $end_date));
            }

            $results->groupBy("plans.dbsr_code");
            $plans = $results->get();

            return view('reports.dbsr', ['plans' => $plans]);
        }
        else
            return redirect('logout');
    }

public function dbsr_excel(Request $request)
    {
        $role = 1;        
        $role = Auth::user()->role;

        if($role==0)
        {
            $today = Carbon::now()->format('Y-m-d');
            $plans = array();
            $results = DB::table('plans')
            ->leftjoin("users", "plans.trainer_id", "=", "users.id")
            ->leftjoin("locations", "plans.trainer_region_id", "=", "locations.id")
            ->select("users.name as trainer_name", "locations.name as region_name","plans.*","plans.id as plan_id", DB::raw('count(*) as counts'))
            ->where("users.role", 1);

            if($request->date!="")
            { 
                $range = explode("-", $request->date);
                $start_date = Carbon::parse($range[0])->format('Y-m-d');
                $end_date = Carbon::parse($range[1])->format('Y-m-d');
                $results->whereBetween('plans.date', array($start_date, $end_date));
            }
            $results->groupBy("plans.dbsr_code");
            $plans = $results->get();
            $datas = array();
            $data = array();
            $i = 1;
            foreach($plans as $row)
            {
                $datas = array(
                    "Sr. No."=>$i,
                    "Count"=>$row->counts,
                    "DBSR Code"=>$row->dbsr_code,
                    "DBSR Name"=>$row->dbsr_name,
                    "DB Code"=>$row->distributor_code,
                    "DB Name"=>$row->distributor_name,
                    "Trainer"=>$row->trainer_name
                );
                array_push($data, $datas);
                $i++;
            }
            /*echo "<pre>";
            print_r($data);die;*/
            Excel::create('dbsr_report', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
            })->download('xlsx');
        }
    }


    public function outlet(Request $request)
    {        
        $role = 1;        
        $role = Auth::user()->role;

        if($role==0)
        {

            $trainers = User::select("users.name as employee_name","users.id")
            ->where("users.role", 1)
            ->get();

            $today = Carbon::now()->format('Y-m-d');;
            $plans = array();
            $results = DB::table('outlet_form_plans')
            ->leftjoin("plans", "outlet_form_plans.plan_id", "=", "plans.id")
            ->leftjoin("users", "plans.trainer_id", "=", "users.id")
            ->leftjoin("unproductive_reason_lists", "outlet_form_plans.non_productivity_reason", "=", "unproductive_reason_lists.id")
            ->select("users.name as trainer_name", "outlet_form_plans.*", "plans.*", "unproductive_reason_lists.*")
            ->where("users.role", 1);

            if($request->trainer!="")
            { 
                $results->where('plans.trainer_id', $request->trainer);
            }

            if($request->date!="")
            { 
                $date = Carbon::parse($request->date)->format('Y-m-d');
                $results->where('plans.date', $date);
            }
            else
                $results->where("plans.date", $today);
                 
            $plans = $results->get();
           /* echo "<pre>";
            print_r($plans);die;*/
            return view('reports.outlet', ['plans' => $plans, 'trainers' => $trainers]);
        }
        else
            return redirect('logout');
    }

    public function outlet_excel(Request $request)
    {
        $role = 1;        
        $role = Auth::user()->role;

        if($role==0)
        {
            $today = Carbon::now()->format('Y-m-d');
            $plans = array();
            $results = DB::table('outlet_form_plans')
            ->leftjoin("plans", "outlet_form_plans.plan_id", "=", "plans.id")
            ->leftjoin("users", "plans.trainer_id", "=", "users.id")
            ->leftjoin("unproductive_reason_lists", "outlet_form_plans.non_productivity_reason", "=", "unproductive_reason_lists.id")
            ->select("users.name as trainer_name", "outlet_form_plans.*", "plans.*", "unproductive_reason_lists.*")
            ->where("users.role", 1);

            if($request->trainer!="")
            { 
                $results->where('plans.trainer_id', $request->trainer);
            }

            $date = $today;
            if($request->date!="")
            { 
                $date = Carbon::parse($request->date)->format('Y-m-d');;
                $results->where('plans.date', $date);
            }
            else
                $results->where("plans.date", $today);
                 
            $plans = $results->get();
            $datas = array();
            $data = array();
            $i = 1;
            foreach($plans as $row)
            {
                $productivity = "";
                $reason = "";
                if($row->outlet_productivity==1)
                    $productivity = "Yes";
                else
                    $productivity = "No";

                if($row->outlet_productivity==0)
                    $reason = $row->reason;
                else
                    $reason = "NA";
                $datas = array(
                    "Sr. No."=>$i,
                    "Date"=>$date,
                    "DBSR Code"=>$row->dbsr_code,
                    "DBSR Name"=>$row->dbsr_name,
                    "DB Code"=>$row->distributor_code,
                    "DB Name"=>$row->distributor_name,
                    "Trainer"=>$row->trainer_name,
                    "Outlet Code"=>$row->outlet_code,
                    "Outlet Name"=>$row->outlet_name,
                    "Outlet Productivity"=>$productivity ,
                    "Non Productivity Reason"=>$reason
                );
                array_push($data, $datas);
                $i++;
            }
            /*echo "<pre>";
            print_r($data);die;*/
            Excel::create('outlet_report', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
            })->download('xlsx');
        }
    }

}
