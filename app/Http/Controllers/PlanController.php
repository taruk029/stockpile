<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Skill;
use App\Kra_skill_mapping;
use App\Employee_skill_rating;
use App\Location;
use App\User;
use App\User_Region;
use App\Outlet_form_plan;
use App\Plan;
use App\Day;
use App\Batch_date;
use App\Student_attendance;
use App\Student_feedback;
use DB;
use App\Helpers\Helper;
use Auth;
use Session;
use Carbon\Carbon;
use DateTime;
use App\Student;
use Maatwebsite\Excel\Facades\Excel;

class PlanController extends Controller
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
        $role = Auth::user()->role;
        $company = array();
        $location = Location::orderBy("name")->get();
        if($role==0)
        {
            $company = User::where("role", 2)->get();
            $results = DB::table('batch_dates')
            ->leftjoin("plans", "batch_dates.plan_id", "=", "plans.id")
            ->leftjoin("users as company", "plans.company_id", "=", "company.id")
            ->leftjoin("users", "plans.trainer_id", "=", "users.id")
            ->leftjoin("locations", "plans.trainer_region_id", "=", "locations.id")
            ->select("users.name as trainer_name", "locations.name as region_name","plans.*","plans.id as plan_id","batch_dates.start_date","batch_dates.id as batch_date_id","company.name as company_name")
            ->where("users.role", 1)
            ->orderBy("plans.id");

            if($request->company)
            {
                $results->where('plans.company_id', $request->company);
            }    
            if($request->location)
            {
                $results->where('plans.trainer_region_id', $request->location);
            }         
            $plans = $results->get();
            return view('plans.index', ['plans' => $plans, 'company' => $company, 'location' => $location]);
        }
        if($role==2)
        {
            $results = DB::table('batch_dates')
            ->leftjoin("plans", "batch_dates.plan_id", "=", "plans.id")
            ->leftjoin("users as company", "plans.company_id", "=", "company.id")
            ->leftjoin("users", "plans.trainer_id", "=", "users.id")
            ->leftjoin("locations", "plans.trainer_region_id", "=", "locations.id")
            ->select("users.name as trainer_name", "locations.name as region_name","plans.*","plans.id as plan_id","batch_dates.start_date","batch_dates.id as batch_date_id","company.name as company_name")
            ->where("users.role", 1)
            ->where("plans.company_id", Auth::user()->id)
            ->orderBy("plans.id");

            if($request->location)
            {
                $results->where('plans.trainer_region_id', $request->location);
            }            
            $plans = $results->get();
	        return view('plans.index', ['plans' => $plans, 'company' => $company, 'location' => $location ]);
	    }
        else
        {
            flash('You are not authorised to take this action.')->error();
            return redirect()->back();
            /*return redirect('logout');*/
        }
    }

    public function batch_excel(Request $request)
    {        
        $role = 1;        
        $role = Auth::user()->role;
        if($role==0)
        {
            $results = DB::table('batch_dates')
            ->leftjoin("plans", "batch_dates.plan_id", "=", "plans.id")
            ->leftjoin("users as company", "plans.company_id", "=", "company.id")
            ->leftjoin("users", "plans.trainer_id", "=", "users.id")
            ->leftjoin("locations", "plans.trainer_region_id", "=", "locations.id")
            ->select("users.name as trainer_name", "locations.name as region_name","plans.*","plans.id as plan_id","batch_dates.start_date","batch_dates.id as batch_date_id","company.name as company_name")
            ->where("users.role", 1)
            ->orderBy("plans.id");

            if($request->company)
            {
                $results->where('plans.company_id', $request->company);
            }    
            if($request->location)
            {
                $results->where('plans.trainer_region_id', $request->location);
            }         
            $plans = $results->get();

            $data = array();
            foreach($plans as $row)
            {
                $arr = array(
                    "Company Name"=>$row->company_name,
                    "Batch Name"=>$row->batch_name,
                    "Date"=>$row->start_date,
                    "Trainer Name"=>$row->trainer_name,
                    "Region"=>$row->region_name,
                );
                array_push($data, $arr);
            }

            Excel::create('batch-'.date('Y-m-d'), function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
            })->download('xlsx');
        }
        if($role==2)
        {
            $results = DB::table('batch_dates')
            ->leftjoin("plans", "batch_dates.plan_id", "=", "plans.id")
            ->leftjoin("users as company", "plans.company_id", "=", "company.id")
            ->leftjoin("users", "plans.trainer_id", "=", "users.id")
            ->leftjoin("locations", "plans.trainer_region_id", "=", "locations.id")
            ->select("users.name as trainer_name", "locations.name as region_name","plans.*","plans.id as plan_id","batch_dates.start_date","batch_dates.id as batch_date_id","company.name as company_name")
            ->where("users.role", 1)
            ->where("plans.company_id", Auth::user()->id)
            ->orderBy("plans.id");

            if($request->location)
            {
                $results->where('plans.trainer_region_id', $request->location);
            }            
            $plans = $results->get();

            $data = array();
            foreach($plans as $row)
            {
                $arr = array(
                    "Batch Name"=>$row->batch_name,
                    "Date"=>$row->start_date,
                    "Trainer Name"=>$row->trainer_name,
                    "Region"=>$row->region_name,
                );
                array_push($data, $arr);
            }
            Excel::create('batch-'.date('Y-m-d'), function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
            })->download('xlsx');
        }
        else
        {
            flash('You are not authorised to take this action.')->error();
            return redirect()->back();
            /*return redirect('logout');*/
        }
    }


    public function add()
    {        
        $role = 1;        
        $role = Auth::user()->role;

        if($role==2)
        {
        	$users = User::select("users.name as trainer_name", 
			"users.id as user_id",
			"users.phone")
	        ->where("users.role", 1)
            ->where("users.company_id", Auth::user()->id)
			->get();

            $days = Day::get();

	        return view('plans.add', ['users' => $users, 'days' => $days]);
	    }
        else
            return redirect('logout');
    }

    public function get_user_regions(Request $request)
    {
        $user_regions = User_Region::where('user_regions.user_id', $request->id)
            ->where('user_regions.is_active', 1)
            ->leftjoin("locations", "user_regions.region_id", "=", "locations.id") 
            ->select('locations.id', 'locations.name')
            ->get();

        $data = array();

        for($i=0;$i<count($user_regions);$i++)
        {
           $data[] = array('id'=>$user_regions[$i]->id,'name'=>$user_regions[$i]->name);
        }
        $output  = $data;
        echo json_encode($output);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'trainer_id' => 'required',
            'plan_date' => 'required',
            'user_region' => 'required'
        ]);
        $plan_id = array();
        $check_plan = Plan::where("trainer_id", $request->trainer_id)->select("id")->get();
        if($check_plan)
        {
            foreach($check_plan as $row_plan)
            {
                array_push($plan_id, $row_plan->id);
            }
            if($plan_id)
            {
                foreach($request->plan_date as $row)
                {
                    $date = Carbon::parse($row)->format('Y-m-d');
                    $check_date = Batch_date::whereIn("plan_id", $plan_id)->where("start_date", $date)->first();
                    if($check_date)
                    {
                        flash("Trainer is already assigned for the ".$date." date in any other batch.")->error();
                        return redirect()->back();
                    }
                }
            }
        }
        $plan = new Plan;
        $plan->company_id = Auth::user()->id;
        $plan->trainer_id = $request->trainer_id;
        $plan->batch_name = $request->batch_name;
        $plan->trainer_region_id = $request->user_region;
        $plan->venue = $request->batch_venue;
        $plan->circle = $request->batch_circle;
        $plan->is_active = 1;
        $plan->created_at = Carbon::now();
        if($plan->save())
        {
            $planid = $plan->id;
            if($request->count_fields>1)
            {
                for($i = 0; $i <= $request->count_fields-1; $i++)
                {
                    $date1 =  Carbon::parse($request->plan_date[$i])->format('Y-m-d');
                    $date2 =  Carbon::parse($request->plan_end_date[$i])->format('Y-m-d');
                    $batch_date = new Batch_date; 
                    $batch_date->plan_id = $planid; 
                    $batch_date->start_date = $date1; 
                    $batch_date->end_date = $date2; 
                    $batch_date->save();
                }
            }
            else
            {
                if($request->plan_date[0]!=$request->plan_end_date[0] && $request->plan_date[0]<$request->plan_end_date[0])
                {
                    $date1 = new DateTime($request->plan_date[0]);
                    $date2 = new DateTime($request->plan_end_date[0]);
                    $diff = date_diff($date1, $date2);
                    $date_diff = $diff->format('%d');
                    if($date_diff)
                    {
                        $s_date = $request->plan_date[0];
                        for($j = 0; $j <= $date_diff; $j++)
                        {
                            if($j!=0)
                            {
                                $s_date =  date('Y-m-d', strtotime($s_date. ' +1 day'));
                            }
                            else
                                $s_date =  Carbon::parse($s_date)->format('Y-m-d');
                            
                            $batch_date = new Batch_date; 
                            $batch_date->plan_id = $planid; 
                            $batch_date->start_date = $s_date; 
                            $batch_date->end_date = $s_date; 
                            $batch_date->save();
                        }
                    }
                }
                else
                {
                    $date1 =  Carbon::parse($request->plan_date[0])->format('Y-m-d');
                    $date2 =  Carbon::parse($request->plan_end_date[0])->format('Y-m-d');
                    $batch_date = new Batch_date; 
                    $batch_date->plan_id = $planid; 
                    $batch_date->start_date = $date1; 
                    $batch_date->end_date = $date2; 
                    $batch_date->save();
                }

            }

            flash('Batch has been added successfully.')->success();
            return redirect('batches'); 
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
            $users = User::select("users.name as trainer_name", 
            "users.id as user_id",
            "users.phone")
            ->where("users.role", 1)
            ->get();

            $plans = Plan::leftjoin("users", "plans.trainer_id", "=", "users.id")
            ->leftjoin("locations", "plans.trainer_region_id", "=", "locations.id")
            ->select("users.name as trainer_name", "locations.name as region_name","plans.*","plans.id as plan_id")
            ->where("users.role", 1)
            ->where("plans.id", $id)
            ->first();

            $user_regions = User_Region::where('user_regions.user_id', $plans['trainer_id'])
            ->where('user_regions.is_active', 1)
            ->leftjoin("locations", "user_regions.region_id", "=", "locations.id") 
            ->select('locations.id', 'locations.name')
            ->get();

            $batch_dates = Batch_date::where("plan_id", $id)->get();

            return view('plans.edit', ['plans' => $plans, 'batch_dates' => $batch_dates, 'users' => $users, 'user_regions' => $user_regions]);
        }
        else
        {
            flash('Something went wrong.')->error();
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'trainer_id' => 'required',
            'user_region' => 'required'
        ]);
        if($request->id)
        {  
            $plan_id = array();
            $check_plan = Plan::where("trainer_id", $request->trainer_id)->select("id")->get();
            if($check_plan)
            {
                foreach($check_plan as $row_plan)
                {
                    if($row_plan->id != $request->id)
                        array_push($plan_id, $row_plan->id);
                }
                if($plan_id)
                {
                    foreach($request->plan_date as $row)
                    {
                        //$date = Carbon::parse($row)->format('Y-m-d');
                        $date = Carbon::createFromFormat('d/m/Y', $row)->format('Y-m-d');
                        $check_date = Batch_date::whereIn("plan_id", $plan_id)->where("start_date", $date)->first();
                        if($check_date)
                        {
                            flash("Trainer is already assigned for the ".$date." date in any other batch.")->error();
                            return redirect()->back();
                        }
                    }
                }
            }

            $plan = Plan::find($request->id);
            $plan->trainer_id = $request->trainer_id;
            $plan->batch_name = $request->batch_name;
            $plan->trainer_region_id = $request->user_region;
            $plan->venue = $request->batch_venue;
            $plan->circle = $request->batch_circle;
            $plan->updated_at = Carbon::now();
            $plan->save();

            $old_dates = array();
            $diff_dates = array();

            $check_date = Batch_date::select("start_date")->where("plan_id", $request->id)->get();
            foreach($check_date as $rows_dt)
            {
                array_push($old_dates, $rows_dt->start_date);
            }

            $diff_dates = array_diff($old_dates, $request->plan_date);

            foreach($diff_dates as $row_diff)
            {
                Batch_date::where('plan_id', $request->id)
                ->where('start_date', $row_diff)
                ->delete();
            }

            foreach($request->plan_date as $row)
            {
                $dates = Carbon::createFromFormat('d/m/Y', $row)->format('Y-m-d');
                $check_date = Batch_date::where("plan_id", $request->id)->where("start_date", $dates)->first();
                if(!$check_date)
                {
                    $batch_date = new Batch_date; 
                    $batch_date->plan_id = $request->id; 
                    $batch_date->start_date = $dates; 
                    $batch_date->end_date = $dates; 
                    $batch_date->save();
                }
            }
        
            flash('Plan has been updated successfully.')->success();
            return redirect('batches');             
        }
        else
        {
            flash('Please fill the form correctly.')->error();
            return redirect()->back();
        }
    }


    public function view_attendance($id)
    {
        if($id)
        {
            $student_attendance = array();
            $batch_id = base64_decode($id);
            $batch_date = Batch_date::where("id", $batch_id)->get();
            
            $today = Carbon::now()->format('Y-m-d');

            if($batch_date)
            {
                $student_attendance = Student_attendance::leftjoin("students", "students.id", "=", "student_attendances.student_id")
                ->where("batch_date_id",$batch_id)
                ->select("student_attendances.is_approved", "student_attendances.id", "students.name", "students.number", "students.emp_id", "students.tsm_name", "students.email")
                ->orderBy("students.name")
                ->get();
            }
            return view('plans.view_attendance', ['batch_date'=>$batch_date, 'student_attendance'=>$student_attendance, 'today'=>$today]);
        }
        else
        {
            flash('Something went wrong.')->error();
            return redirect()->back();
        }
    }

    public function view_attendance_excel($id)
    {
        if($id)
        {
            $student_attendance = array();
            $batch_id = base64_decode($id);
            $batch_date = Batch_date::where("id", $batch_id)->first();            
            $batch = Plan::find($batch_date['plan_id']);
            
            $today = Carbon::now()->format('Y-m-d');

            if($batch_date)
            {
                $data = Student_attendance::leftjoin("students", "students.id", "=", "student_attendances.student_id")
                ->where("batch_date_id",$batch_id)
                ->select("students.name", "students.number", "students.email", "students.emp_id", "students.tsm_name")
                ->orderBy("students.name")
                ->get();
            }
            Excel::create($batch['batch_name'].'-'.$batch_date['start_date'].'-attendance', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
            })->download('xlsx');
        }
    }

    public function feedback(Request $request, $id)
    {
        $avg_feedback = 0;
        $count = 0;
        $batch_date = array();
        $batch_id = base64_decode($id);
        $batch_date = Batch_date::where("plan_id", $batch_id)->get();
        $plans = Plan::leftjoin("users", "plans.trainer_id", "=", "users.id")
            ->select("users.name as trainer_name")
            ->where("plans.id",$batch_id)
            ->first();

        if($request->date_batch)
        {
            $count = DB::table('student_feedbacks')->where('batch_date_id', $request->date_batch)
            ->distinct('student_id')
            ->count('student_id');
            if($count)
            {  
                $avg_feedback = DB::table('student_feedbacks')
                ->where('batch_date_id', $request->date_batch)
                ->where('option_id', '<=', 6)
                ->sum('rating');
            }
        }
        return view('plans.view_feedback', ['plans'=>$plans, 'count'=>$count, 'avg_feedback'=>$avg_feedback, 'batch_date'=>$batch_date]);
    }

    public function trainer_average(Request $request)
    {        
        $role = 1;        
        $role = Auth::user()->role;
        $company = array();
        $location = Location::orderBy("name")->get();
        if($role==0)
        {
            $company = User::where("role", 2)->get();
            $emp_rating = Batch_date::select(DB::raw("MAX(batch_dates.id) as batch_date_id"), "plans.batch_name", "users.name as trainer_name", "company.name as company_name", "locations.name as loc_name")
            ->leftjoin("plans", "batch_dates.plan_id", "=", "plans.id")
            ->leftjoin("users", "plans.trainer_id", "=", "users.id")
            ->leftjoin("users as company", "plans.company_id", "=", "company.id")
            ->leftjoin("user_regions", "plans.trainer_id", "=", "user_regions.user_id")
            ->leftjoin("locations", "user_regions.region_id", "=", "locations.id")
            ->groupBy("batch_dates.plan_id")
            ->get();

            if($request->company && $request->location)
            {
                $emp_rating = Batch_date::select(DB::raw("MAX(batch_dates.id) as batch_date_id"), "plans.batch_name", "users.name as trainer_name", "company.name as company_name", "locations.name as loc_name")
                ->leftjoin("plans", "batch_dates.plan_id", "=", "plans.id")
                ->leftjoin("users", "plans.trainer_id", "=", "users.id")
                ->leftjoin("users as company", "plans.company_id", "=", "company.id")
                ->leftjoin("user_regions", "plans.trainer_id", "=", "user_regions.user_id")
                ->leftjoin("locations", "user_regions.region_id", "=", "locations.id")
                ->where("plans.company_id", $request->company)
                ->where("user_regions.region_id", $request->location)
                ->groupBy("batch_dates.plan_id")
                ->get();
            }
            else
            {
                if($request->company)
                {
                    $emp_rating = Batch_date::select(DB::raw("MAX(batch_dates.id) as batch_date_id"), "plans.batch_name", "users.name as trainer_name", "company.name as company_name", "locations.name as loc_name")
                    ->leftjoin("plans", "batch_dates.plan_id", "=", "plans.id")
                    ->leftjoin("users", "plans.trainer_id", "=", "users.id")
                    ->leftjoin("users as company", "plans.company_id", "=", "company.id")
                    ->leftjoin("user_regions", "plans.trainer_id", "=", "user_regions.user_id")
                    ->leftjoin("locations", "user_regions.region_id", "=", "locations.id")
                    ->where("plans.company_id", $request->company)
                    ->groupBy("batch_dates.plan_id")
                    ->get();
                }    
                if($request->location)
                {
                    $emp_rating = Batch_date::select(DB::raw("MAX(batch_dates.id) as batch_date_id"), "plans.batch_name", "users.name as trainer_name", "company.name as company_name", "locations.name as loc_name")
                    ->leftjoin("plans", "batch_dates.plan_id", "=", "plans.id")
                    ->leftjoin("users", "plans.trainer_id", "=", "users.id")
                    ->leftjoin("users as company", "plans.company_id", "=", "company.id")
                    ->leftjoin("user_regions", "plans.trainer_id", "=", "user_regions.user_id")
                    ->leftjoin("locations", "user_regions.region_id", "=", "locations.id")
                    ->where("user_regions.region_id", $request->location)
                    ->groupBy("batch_dates.plan_id")
                    ->get();
                }
            }
                    
            return view('plans.trainer_feedback', ['emp_rating' => $emp_rating, 'company' => $company, 'location' => $location]);
        }
        if($role==2)
        {
            $emp_rating = Batch_date::select(DB::raw("MAX(batch_dates.id) as batch_date_id"), "plans.batch_name", "users.name as trainer_name", "company.name as company_name", "locations.name as loc_name")
                    ->leftjoin("plans", "batch_dates.plan_id", "=", "plans.id")
                    ->leftjoin("users", "plans.trainer_id", "=", "users.id")
                    ->leftjoin("users as company", "plans.company_id", "=", "company.id")
                    ->leftjoin("user_regions", "plans.trainer_id", "=", "user_regions.user_id")
                    ->leftjoin("locations", "user_regions.region_id", "=", "locations.id")
                    ->where("plans.company_id", Auth::user()->id)
                    ->groupBy("batch_dates.plan_id")
                    ->get();
            if($request->location)
            {
                $emp_rating = Batch_date::select(DB::raw("MAX(batch_dates.id) as batch_date_id"), "plans.batch_name", "users.name as trainer_name", "company.name as company_name", "locations.name as loc_name")
                    ->leftjoin("plans", "batch_dates.plan_id", "=", "plans.id")
                    ->leftjoin("users", "plans.trainer_id", "=", "users.id")
                    ->leftjoin("users as company", "plans.company_id", "=", "company.id")
                    ->leftjoin("user_regions", "plans.trainer_id", "=", "user_regions.user_id")
                    ->leftjoin("locations", "user_regions.region_id", "=", "locations.id")
                    ->where("plans.company_id", Auth::user()->id)
                    ->where("user_regions.region_id", $request->location)
                    ->groupBy("batch_dates.plan_id")
                    ->get();
            }            
            return view('plans.trainer_feedback', ['emp_rating' => $emp_rating, 'company' => $company, 'location' => $location ]);
        }
        else
        {
            flash('You are not authorised to take this action.')->error();
            return redirect()->back();
        }
    }

    public function average_excel(Request $request)
    {        
        $role = 1;        
        $role = Auth::user()->role;
        if($role==0)
        {
            $results = DB::table('batch_dates')
            ->select(DB::raw("MAX(batch_dates.id) as batch_date_id"), "plans.batch_name", "users.name as trainer_name", "company.name as company_name", "locations.name as loc_name")
            ->leftjoin("plans", "batch_dates.plan_id", "=", "plans.id")
            ->leftjoin("users", "plans.trainer_id", "=", "users.id")
            ->leftjoin("users as company", "plans.company_id", "=", "company.id")
            ->leftjoin("user_regions", "plans.trainer_id", "=", "user_regions.user_id")
            ->leftjoin("locations", "user_regions.region_id", "=", "locations.id")
            ->groupBy("batch_dates.plan_id");

            if($request->company)
            {
                $results->where('plans.company_id', $request->company);
            }    
            if($request->location)
            {
                $results->where("user_regions.region_id", $request->location);
            }         
            $plans = $results->get();

            $data = array();
            foreach($plans as $row)
            {
                $avrg = 0;
                $avrg = Helper::get_average($row->batch_date_id); 
                if($avrg)
                {
                    $arr = array(
                        "Company Name"=>$row->company_name,
                        "Batch Name"=>$row->batch_name,
                        "Trainer Name"=>$row->trainer_name,
                        "Location"=>$row->loc_name,
                        "Average"=>$avrg
                    );
                    array_push($data, $arr);
                }
            }

            Excel::create('average-'.date('Y-m-d'), function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
            })->download('xlsx');
        }
        if($role==2)
        {
            $results = DB::table('batch_dates')
            ->select(DB::raw("MAX(batch_dates.id) as batch_date_id"), "plans.batch_name", "users.name as trainer_name", "company.name as company_name", "locations.name as loc_name")
            ->leftjoin("plans", "batch_dates.plan_id", "=", "plans.id")
            ->leftjoin("users", "plans.trainer_id", "=", "users.id")
            ->leftjoin("users as company", "plans.company_id", "=", "company.id")
            ->leftjoin("user_regions", "plans.trainer_id", "=", "user_regions.user_id")
            ->leftjoin("locations", "user_regions.region_id", "=", "locations.id")
            ->groupBy("batch_dates.plan_id")
            ->where("plans.company_id", Auth::user()->id);

            if($request->location)
            {
                $results->where("user_regions.region_id", $request->location);
            }            
            $plans = $results->get();

            $data = array();
            foreach($plans as $row)
            {
                $avrg = 0;
                $avrg = Helper::get_average($row->batch_date_id); 
                if($avrg)
                {
                    $arr = array(
                        "Company Name"=>$row->company_name,
                        "Batch Name"=>$row->batch_name,
                        "Trainer Name"=>$row->trainer_name,
                        "Location"=>$row->loc_name,
                        "Average"=>$avrg
                    );
                    array_push($data, $arr);
                }
            }
            Excel::create('batch-'.date('Y-m-d'), function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
            })->download('xlsx');
        }
        else
        {
            flash('You are not authorised to take this action.')->error();
            return redirect()->back();
            /*return redirect('logout');*/
        }
    }

    public function mark_company_attendance(Request $request, $id)
    {        
        return view('plans.register_phone');
    }

    public function mark_attendance(Request $request, $id)
    {        
        $request->validate([
            'number' => 'required'
        ]);
        $batch_date = Batch_date::where("plan_id", base64_decode($id))->get();
        $details = array();
        $details = Student::where("number", $request->number)->first();
        return view('plans.mark_attendance', ['batch_date'=>$batch_date, 'details'=>$details, "number"=>$request->number, "batch_id"=>$id]);
    }

    public function add_company_attendance(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'number' => 'required',
            'batch_date' => 'required'
        ]);

        if($id)
        {
            $batch_id = base64_decode($id);

            $batch = Plan::find($batch_id);

            $student_id = 0;
            $details = Student::where("number", $request->number)->first();
            if(!$details)
            {
                $student = new Student;
                $student->name = $request->name;
                $student->number = $request->number;
                $student->emp_id = $request->emp_id;
                $student->tsm_name = $request->tsm_name;
                $student->email = $request->email;
                $student->is_active = 1;
                $student->created_at = Carbon::now();
                $student->save();
                $student_id = $student->id;
            }
            else
            {
                $student_id = $details['id'];
            }
            if($student_id)
            {
                /*$check_date = Batch_date::where("plan_id", $batch_id)->first();*/
                $attendance_details = Student_attendance::where("batch_date_id", $request->batch_date)
                ->where("student_id", $student_id)
                ->first();
                if(!$attendance_details)
                {
                    $student_attendance = new Student_attendance;
                    $student_attendance->batch_date_id = $request->batch_date;
                    $student_attendance->student_id = $student_id;
                    $student_attendance->company_id = $batch->company_id;
                    $student_attendance->trainer_id = $batch->trainer_id;
                    $student_attendance->batch_id = $batch_id;
                    $student_attendance->created_at = Carbon::now();
                    $student_attendance->save();
                }
                return view('students.register_thank');
            }
            Session::flash('message', 'Something went wrong, please try again.');
            return redirect()->back();
        }
        else
        {
            Session::flash('message', 'Something went wrong, please try again.');
            return redirect()->back(); 
        }
    }


    


    public function mark_company_feedback(Request $request, $id)
    {        
        return view('plans.feedback_phone');
    }

    public function company_feedback_reg(Request $request, $id)
    {        
        $request->validate([
            'number' => 'required'
        ]);
        $b_id = base64_decode($id);
        $details = array();
        $batch_date = Batch_date::where("plan_id", base64_decode($id))->get();
        $details = Student::where("number", $request->number)->first();
        if(!$details)
        {
            Session::flash('message', 'You are not a registered participant. Please contact batch trainer.');
            return redirect()->back();
        }
        $batch = Plan::find($b_id);
        return view('plans.mark_feedback', ['batch_date'=>$batch_date, 'details'=>$details, "number"=>$request->number, "batch_id"=>$id, "batch"=>$batch]);
    }

    public function submit_feedback(Request $request, $id)
    {
        $request->validate([
            'option1' => 'required',
            'option2' => 'required',
            'option3' => 'required',
            'option4' => 'required',
            'option5' => 'required',
            'option6' => 'required',
            'option7' => 'required',
            'option8' => 'required',
            'option9' => 'required'
        ]);

        if($id)
        {
            $batch_id = base64_decode($id);

            $batch = Plan::find($batch_id);

            $student_id = $request->student_id;
            
            /*$check_date = Batch_date::where("plan_id", $batch_id)->first();*/
            $attendance_details = Student_attendance::where("batch_date_id",  $request->batch_date)
            ->where("student_id", $student_id)
            ->first();

            if($attendance_details)
            {
                $check_option = Student_feedback::where("batch_date_id",  $request->batch_date)
                ->where("student_id", $student_id)
                ->where("option_id", 1)
                ->first();
                if(!$check_option)
                {
                    $student_feedback = new Student_feedback;
                    $student_feedback->batch_id = $batch_id;
                    $student_feedback->batch_date_id = $request->batch_date;
                    $student_feedback->student_id = $student_id;
                    $student_feedback->company_id = $batch->company_id;
                    $student_feedback->trainer_id = $batch->trainer_id;
                    $student_feedback->option_id = 1;
                    $student_feedback->rating = $request->option1;
                    $student_feedback->created_at = Carbon::now();
                    $student_feedback->save();
                }

                $check_option1 = Student_feedback::where("batch_date_id",  $request->batch_date)
                ->where("student_id", $student_id)
                ->where("option_id", 2)
                ->first();
                if(!$check_option1)
                {
                    $student_feedback1 = new Student_feedback;
                    $student_feedback1->batch_id = $batch_id;
                    $student_feedback1->batch_date_id = $request->batch_date;
                    $student_feedback1->student_id = $student_id;
                    $student_feedback1->company_id = $batch->company_id;
                    $student_feedback1->trainer_id = $batch->trainer_id;
                    $student_feedback1->option_id = 2;
                    $student_feedback1->rating = $request->option2;
                    $student_feedback1->created_at = Carbon::now();
                    $student_feedback1->save();                     
                }

                $check_option2 = Student_feedback::where("batch_date_id",  $request->batch_date)
                ->where("student_id", $student_id)
                ->where("option_id", 3)
                ->first();
                if(!$check_option2)
                {
                    $student_feedback2 = new Student_feedback;
                    $student_feedback2->batch_id = $batch_id;
                    $student_feedback2->batch_date_id = $request->batch_date;
                    $student_feedback2->student_id = $student_id;
                    $student_feedback2->company_id = $batch->company_id;
                    $student_feedback2->trainer_id = $batch->trainer_id;
                    $student_feedback2->option_id = 3;
                    $student_feedback2->rating = $request->option3;
                    $student_feedback2->created_at = Carbon::now();
                    $student_feedback2->save();                     
                }

                $check_option3 = Student_feedback::where("batch_date_id", $request->batch_date)
                ->where("student_id", $student_id)
                ->where("option_id", 4)
                ->first();
                if(!$check_option3)
                {
                    $student_feedback3 = new Student_feedback;
                    $student_feedback3->batch_id = $batch_id;
                    $student_feedback3->batch_date_id = $request->batch_date;
                    $student_feedback3->student_id = $student_id;
                    $student_feedback3->company_id = $batch->company_id;
                    $student_feedback3->trainer_id = $batch->trainer_id;
                    $student_feedback3->option_id = 4;
                    $student_feedback3->rating = $request->option4;
                    $student_feedback3->created_at = Carbon::now();
                    $student_feedback3->save();
                }

                $check_option4 = Student_feedback::where("batch_date_id", $request->batch_date)
                ->where("student_id", $student_id)
                ->where("option_id", 5)
                ->first();
                if(!$check_option4)
                {
                    $student_feedback4 = new Student_feedback;
                    $student_feedback4->batch_id = $batch_id;
                    $student_feedback4->batch_date_id = $request->batch_date;
                    $student_feedback4->student_id = $student_id;
                    $student_feedback4->company_id = $batch->company_id;
                    $student_feedback4->trainer_id = $batch->trainer_id;
                    $student_feedback4->option_id = 5;
                    $student_feedback4->rating = $request->option5;
                    $student_feedback4->created_at = Carbon::now();
                    $student_feedback4->save();
                }

                $check_option5 = Student_feedback::where("batch_date_id", $request->batch_date)
                ->where("student_id", $student_id)
                ->where("option_id", 6)
                ->first();
                if(!$check_option5)
                {
                    $student_feedback5 = new Student_feedback;
                    $student_feedback5->batch_id = $batch_id;
                    $student_feedback5->batch_date_id = $request->batch_date;
                    $student_feedback5->student_id = $student_id;
                    $student_feedback5->company_id = $batch->company_id;
                    $student_feedback5->trainer_id = $batch->trainer_id;
                    $student_feedback5->option_id = 6;
                    $student_feedback5->rating = $request->option6;
                    $student_feedback5->created_at = Carbon::now();
                    $student_feedback5->save();
                }

                $check_option6 = Student_feedback::where("batch_date_id", $request->batch_date)
                ->where("student_id", $student_id)
                ->where("option_id", 7)
                ->first();
                if(!$check_option6)
                {
                    $student_feedback6 = new Student_feedback;
                    $student_feedback6->batch_id = $batch_id;
                    $student_feedback6->batch_date_id = $request->batch_date;
                    $student_feedback6->student_id = $student_id;
                    $student_feedback6->company_id = $batch->company_id;
                    $student_feedback6->trainer_id = $batch->trainer_id;
                    $student_feedback6->option_id = 7;
                    $student_feedback6->rating = $request->option7;
                    $student_feedback6->created_at = Carbon::now();
                    $student_feedback6->save();
                }

                $check_option7 = Student_feedback::where("batch_date_id", $request->batch_date)->where("student_id", $student_id)->where("option_id", 8)->first();
                if(!$check_option7)
                {
                    $student_feedback7 = new Student_feedback;
                    $student_feedback7->batch_id = $batch_id;
                    $student_feedback7->batch_date_id = $request->batch_date;
                    $student_feedback7->student_id = $student_id;
                    $student_feedback7->company_id = $batch->company_id;
                    $student_feedback7->trainer_id = $batch->trainer_id;
                    $student_feedback7->option_id = 8;
                    $student_feedback7->description = $request->option8;
                    $student_feedback7->created_at = Carbon::now();
                    $student_feedback7->save();                     
                }
                $check_option8 = Student_feedback::where("batch_date_id", $request->batch_date)
                ->where("student_id", $student_id)
                ->where("option_id", 9)
                ->first();
                if(!$check_option8)
                {
                    $student_feedback8 = new Student_feedback;
                    $student_feedback8->batch_id = $batch_id;
                    $student_feedback8->batch_date_id = $request->batch_date;
                    $student_feedback8->student_id = $student_id;
                    $student_feedback8->company_id = $batch->company_id;
                    $student_feedback8->trainer_id = $batch->trainer_id;
                    $student_feedback8->option_id = 9;
                    $student_feedback8->description = $request->option9;
                    $student_feedback8->created_at = Carbon::now();
                    $student_feedback8->save();
                }

                return view('plans.feedback_thank');
            }
            else
            {
                Session::flash('message', 'You are not present in this class.');
                return redirect()->back();
            } 
        }
        else
        {
            Session::flash('message', 'Something went wrong, please try again.');
            return redirect()->back(); 
        }
    }

    public function delete($id)
    {
        if($id)
        {
            DB::table('batch_dates')->where('plan_id', $id)->delete();
            DB::table('student_attendances')->where('batch_id', $id)->delete();
            DB::table('student_feedbacks')->where('batch_id', $id)->delete();
            DB::table('plans')->where('id', $id)->delete();
            flash('Batch has been deleted successfully.')->success();
            return redirect()->back();
        }
        else
        {
            flash('Something went wrong.')->error();
            return redirect()->back();
        }
    }
}
