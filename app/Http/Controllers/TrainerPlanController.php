<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location;
use App\User;
use App\User_Region;
use App\Plan;
use App\Day;
use App\Batch_date;
use App\Student_feedback;
use App\Student;
use App\Student_attendance;
use DB;
use App\Helpers\Helper;
use Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class TrainerPlanController extends Controller
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
    	
        if($role==1)
        {
            $today = Carbon::now()->format('Y-m-d');
            $plans = Batch_date::leftjoin("plans", "batch_dates.plan_id", "=", "plans.id")
            ->leftjoin("users", "plans.trainer_id", "=", "users.id")
            ->leftjoin("locations", "plans.trainer_region_id", "=", "locations.id")
            ->select("users.name as trainer_name", "locations.name as region_name","plans.*","batch_dates.start_date","batch_dates.id as batch_date_id","plans.id as plan_id")
            ->where("users.role", 1)
            ->where("users.id", Auth::user()->id)
            ->orderBy("plans.id")
            ->get();
	        return view('trainers.plans.index', ['plans' => $plans, 'today' => $today]);
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

        if($role==1)
        {
        	$users = User::select("users.name as trainer_name", 
			"users.id as user_id",
			"users.phone")
	        ->where("users.role", 1)
            ->where("users.id", Auth::user()->id)
			->first();

            $days = Day::get();

            $user_regions = User_Region::where('user_regions.user_id', Auth::user()->id)
            ->where('user_regions.is_active', 1)
            ->leftjoin("locations", "user_regions.region_id", "=", "locations.id") 
            ->select('locations.id', 'locations.name')
            ->get();

            $today = Carbon::now()->format('Y-m-d');

	        return view('trainers.plans.add', ['users' => $users, 'days' => $days, 'user_regions' => $user_regions, 'today' => $today]);
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
    
    public function attendence_qr($id)
    {
        if($id)
        {
            $batch_id = base64_decode($id);
            $check_date = Batch_date::where("plan_id", $batch_id)->get();
            $dates = array();
            foreach($check_date as $row)
            {
                array_push($dates, $row->start_date);
            }
            $today = Carbon::now()->format('Y-m-d');

            if(in_array($today, $dates))
            {
                $register_link = url("check_register/".$id);
                return view('trainers.plans.attendence_qr', ['register_link'=>$register_link]);
            }
            else
            {
                flash('Date is not correct.')->error();
                return redirect()->back();
            }
        }
        else
        {
            flash('Something went wrong.')->error();
            return redirect()->back();
        }
    }

    public function feedback_qr($id)
    {
        if($id)
        {
            $batch_id = base64_decode($id);
            $check_date = Batch_date::where("plan_id", $batch_id)->get();
            $dates = array();
            foreach($check_date as $row)
            {
                array_push($dates, $row->start_date);
            }
            $today = Carbon::now()->format('Y-m-d');

            if(in_array($today, $dates))
            {
                $feedback_link = url("check_feedback/".$id);
                return view('trainers.plans.feedback_qr', ['feedback_link'=>$feedback_link]);
            }
            else
            {
                flash('Date is not correct.')->error();
                return redirect()->back();
            }
        }
        else
        {
            flash('Something went wrong.')->error();
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
            $dates = array();
            foreach($batch_date as $row)
            {
                array_push($dates, $row->start_date);
            }

            if($batch_date)
            {
                $student_attendance = Student_attendance::leftjoin("students", "students.id", "=", "student_attendances.student_id")
                ->where("batch_date_id",$batch_id)
                ->select("student_attendances.is_approved", "student_attendances.id", "students.name", "students.number", "students.emp_id", "students.tsm_name", "students.email")
                ->orderBy("students.name")
                ->get();
            }
            return view('trainers.plans.view_attendance', ['batch_date'=>$batch_date, 'student_attendance'=>$student_attendance, 'today'=>$today, 'dates'=>$dates]);
        }
        else
        {
            flash('Something went wrong.')->error();
            return redirect()->back();
        }
    }

    public function trainer_attendance_excel($id)
    {
        if($id)
        {
            $student_attendance = array();
            $batch_id = base64_decode($id);
            $batch_date = Batch_date::find($batch_id);
            $batch = Plan::find($batch_date->plan_id);

            if($batch_id)
            {
                $student_attendance = Student_attendance::leftjoin("students", "students.id", "=", "student_attendances.student_id")
                ->where("batch_date_id",$batch_id)
                ->select("student_attendances.is_approved", "student_attendances.id", "students.name", "students.number", "students.emp_id", "students.tsm_name", "students.email")
                ->orderBy("students.name")
                ->get();

                if($student_attendance)
                {                
                    $data = array();
                    foreach($student_attendance as $row)
                    {
                        $arr = array(
                            "Participant Name"=>$row->name,
                            "Participant Number"=>$row->number,
                            "Participant Emp Id"=>$row->emp_id,
                            "Participant Email"=>$row->email,
                            "Participant TSM Name"=>$row->tsm_name,
                        );
                        array_push($data, $arr);
                    }

                    Excel::create($batch->batch_name." ".$batch_date->start_date.' Attendance', function($excel) use ($data) {
                    $excel->sheet('mySheet', function($sheet) use ($data)
                    {
                        $sheet->fromArray($data);
                    });
                    })->download('xlsx');
                }
                else
                {
                    flash('Something went wrong.')->error();
                    return redirect()->back();
                }
            }
            else
            {
                flash('Something went wrong.')->error();
                return redirect()->back();
            }

        }
        else
        {
            flash('Something went wrong.')->error();
            return redirect()->back();
        }
    }


    public function attendance_status(Request $request)
    {
        if($request->id)
        {
            $student_attendance = array();
            $attendance_id = base64_decode($request->id);
            $details = Student_attendance::find($attendance_id);
            if($details)
            {
                $details->is_approved = $request->status;
                $details->updated_at = Carbon::now();
                $details->save();
            }
            if($request->status==1)
            {
                //flash('Attendance has been marked approved.')->success();
                echo 1;
            }
            else
            {
                //flash('Attendance has been marked rejected.')->success();
                echo 1;
            }
        }
        else
        {
            //flash('Something went wrong.')->error();
            echo 0;
        }
    }

    public function feedback(Request $request)
    {
        $feedback = 0;
        $count = 0;
        $batch_date = array();

        $batch = Plan::where("trainer_id", Auth::user()->id)
        ->orderBy("plans.id")
        ->get();

        $batch_date_id = Helper::get_max_id_plan($request->batch);
        if($batch_date_id)
        { 
            $feedback = DB::table('student_feedbacks')
            ->leftjoin("plans", "student_feedbacks.batch_id", "=", "plans.id")
            ->leftjoin("students", "student_feedbacks.student_id", "=", "students.id")
            ->leftjoin("feedback_options", "student_feedbacks.option_id", "=", "feedback_options.id")
            ->leftjoin("batch_dates", "student_feedbacks.batch_date_id", "=", "batch_dates.id")
            ->select("students.name as student_name", "feedback_options.option", "student_feedbacks.rating" , "batch_dates.start_date" )
            ->where('student_feedbacks.option_id', '<=', 6)
            ->where('student_feedbacks.batch_id', $request->batch)
            ->get();
        }
        return view('trainers.plans.view_feedback', ['batch'=>$batch, 'count'=>$count, 'feedback'=>$feedback]);
    }

    public function trainer_feedback_excel($id)
    {
        if($id)
        {
            $batch = Plan::find($id);

            $feedback = DB::table('student_feedbacks')
            ->leftjoin("plans", "student_feedbacks.batch_id", "=", "plans.id")
            ->leftjoin("students", "student_feedbacks.student_id", "=", "students.id")
            ->leftjoin("feedback_options", "student_feedbacks.option_id", "=", "feedback_options.id")
            ->leftjoin("batch_dates", "student_feedbacks.batch_date_id", "=", "batch_dates.id")
            ->select("students.name as participant_name", "batch_dates.start_date as batch_date", "feedback_options.option", "student_feedbacks.rating")
            ->where('student_feedbacks.option_id', '<=', 6)
            ->where('student_feedbacks.batch_id', $id)
            ->get();

            if($feedback)
            {                
                $data = array();
                foreach($feedback as $row)
                {
                    $arr = array(
                        "Batch Date"=>$row->batch_date,
                        "Participant Name"=>$row->participant_name,
                        "Question"=>$row->option,
                        "Rating"=>$row->rating,
                    );
                    array_push($data, $arr);
                }

                Excel::create($batch->batch_name.' Feedback-', function($excel) use ($data) {
                $excel->sheet('mySheet', function($sheet) use ($data)
                {
                    $sheet->fromArray($data);
                });
                })->download('xlsx');
            }
        }
        
    }

    public function get_batch_dates(Request $request)
    {
        $batch_date = Batch_date::where("plan_id", $request->id)->get();

        $data = array();

        for($i=0;$i<count($batch_date);$i++)
        {
           $data[] = array('id'=>$batch_date[$i]->id,'name'=>$batch_date[$i]->start_date);
        }
        $output  = $data;
        echo json_encode($output);
    }
}
