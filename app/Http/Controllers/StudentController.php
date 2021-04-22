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
use Session;
use Carbon\Carbon;

class StudentController extends Controller
{
   	public function index()
    {        
        return view('students.register_phone');
    }

    public function mark_attendance(Request $request, $id)
    {        
    	$request->validate([
            'number' => 'required'
        ]);
        $details = array();
        $details = Student::where("number", $request->number)->first();
        return view('students.mark_attendance', ['details'=>$details, "number"=>$request->number, "batch_id"=>$id]);
    }

    public function insert(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'number' => 'required'
        ]);

        if($id)
        {
        	$batch_id = base64_decode($id);

        	$batch = Plan::find($batch_id);

        	$check_date = Batch_date::where("plan_id", $batch_id)->get();
            $dates = array();
            foreach($check_date as $row)
            {
                array_push($dates, $row->start_date);
            }
            $today = Carbon::now()->format('Y-m-d');
            if(in_array($today, $dates))
            {
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
		        	$check_date = Batch_date::where("plan_id", $batch_id)->where("start_date", $today)->first();
		        	$attendance_details = Student_attendance::where("batch_date_id", $check_date['id'])
		        	->where("student_id", $student_id)
		        	->first();
		        	if(!$attendance_details)
		        	{
				        $student_attendance = new Student_attendance;
				        $student_attendance->batch_date_id = $check_date['id'];
				        $student_attendance->student_id = $student_id;
				        $student_attendance->company_id = $batch->company_id;
				        $student_attendance->trainer_id = $batch->trainer_id;
				        $student_attendance->batch_id = $batch_id;
				        $student_attendance->created_at = Carbon::now();
				        $student_attendance->save();
			    	}
			        return view('students.register_thank');
			    }

                echo "<script>alert('Something went wrong, please try again.');</script>";
                return redirect()->back();
            }
            else
            {
                echo "<script>alert('Date is not correct.');</script>";
                return redirect()->back();
            }  
        }
        else
        {
			echo "<script>alert('Something went wrong, please try again.');</script>";
        	return redirect()->back(); 
        }
    }

    public function check_feedback()
    {        
        return view('students.feedback_phone');
    }

    public function feedback(Request $request, $id)
    {        
        $request->validate([
            'number' => 'required'
        ]);
        $b_id = base64_decode($id);
        $details = array();
        $details = Student::where("number", $request->number)->first();
        if(!$details)
        {
        	Session::flash('message', 'You are not a registered participant. Please contact batch trainer.');
            return redirect()->back();
        }
        $batch = Plan::find($b_id);
        return view('students.mark_feedback', ['details'=>$details, "number"=>$request->number, "batch_id"=>$id, "batch"=>$batch]);
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

        	$check_date = Batch_date::where("plan_id", $batch_id)->get();
            $dates = array();
            foreach($check_date as $row)
            {
                array_push($dates, $row->start_date);
            }
            $today = Carbon::now()->format('Y-m-d');
            if(in_array($today, $dates))
            {
            	$check_date = Batch_date::where("plan_id", $batch_id)->where("start_date", $today)->first();
	        	$attendance_details = Student_attendance::where("batch_date_id", $check_date['id'])
	        	->where("student_id", $student_id)
	        	->first();

            	if($attendance_details)
            	{
            		$check_option = Student_feedback::where("batch_date_id", $check_date['id'])
            		->where("student_id", $student_id)
            		->where("option_id", 1)
            		->first();
					if(!$check_option)
					{
						$student_feedback = new Student_feedback;
				        $student_feedback->batch_id = $batch_id;
				        $student_feedback->batch_date_id = $check_date['id'];
				        $student_feedback->student_id = $student_id;
				        $student_feedback->company_id = $batch->company_id;
				        $student_feedback->trainer_id = $batch->trainer_id;
				        $student_feedback->option_id = 1;
				        $student_feedback->rating = $request->option1;
				        $student_feedback->created_at = Carbon::now();
				        $student_feedback->save();
					}

			        $check_option1 = Student_feedback::where("batch_date_id", $check_date['id'])
			        ->where("student_id", $student_id)
			        ->where("option_id", 2)
			        ->first();
					if(!$check_option1)
					{
				        $student_feedback1 = new Student_feedback;
				        $student_feedback1->batch_id = $batch_id;
				        $student_feedback1->batch_date_id = $check_date['id'];
				        $student_feedback1->student_id = $student_id;
				        $student_feedback1->company_id = $batch->company_id;
				        $student_feedback1->trainer_id = $batch->trainer_id;
				        $student_feedback1->option_id = 2;
				        $student_feedback1->rating = $request->option2;
				        $student_feedback1->created_at = Carbon::now();
				        $student_feedback1->save();						
					}

			        $check_option2 = Student_feedback::where("batch_date_id", $check_date['id'])
			        ->where("student_id", $student_id)
			        ->where("option_id", 3)
			        ->first();
					if(!$check_option2)
					{
				        $student_feedback2 = new Student_feedback;
				        $student_feedback2->batch_id = $batch_id;
				        $student_feedback2->batch_date_id = $check_date['id'];
				        $student_feedback2->student_id = $student_id;
				        $student_feedback2->company_id = $batch->company_id;
				        $student_feedback2->trainer_id = $batch->trainer_id;
				        $student_feedback2->option_id = 3;
				        $student_feedback2->rating = $request->option3;
				        $student_feedback2->created_at = Carbon::now();
				        $student_feedback2->save();						
					}

			        $check_option3 = Student_feedback::where("batch_date_id", $check_date['id'])
			        ->where("student_id", $student_id)
			        ->where("option_id", 4)
			        ->first();
					if(!$check_option3)
					{
				        $student_feedback3 = new Student_feedback;
				        $student_feedback3->batch_id = $batch_id;
				        $student_feedback3->batch_date_id = $check_date['id'];
				        $student_feedback3->student_id = $student_id;
				        $student_feedback3->company_id = $batch->company_id;
				        $student_feedback3->trainer_id = $batch->trainer_id;
				        $student_feedback3->option_id = 4;
				        $student_feedback3->rating = $request->option4;
				        $student_feedback3->created_at = Carbon::now();
				        $student_feedback3->save();
					}

			        $check_option4 = Student_feedback::where("batch_date_id", $check_date['id'])
			        ->where("student_id", $student_id)
			        ->where("option_id", 5)
			        ->first();
					if(!$check_option4)
					{
				        $student_feedback4 = new Student_feedback;
				        $student_feedback4->batch_id = $batch_id;
				        $student_feedback4->batch_date_id = $check_date['id'];
				        $student_feedback4->student_id = $student_id;
				        $student_feedback4->company_id = $batch->company_id;
				        $student_feedback4->trainer_id = $batch->trainer_id;
				        $student_feedback4->option_id = 5;
				        $student_feedback4->rating = $request->option5;
				        $student_feedback4->created_at = Carbon::now();
				        $student_feedback4->save();
					}

			        $check_option5 = Student_feedback::where("batch_date_id", $check_date['id'])
			        ->where("student_id", $student_id)
			        ->where("option_id", 6)
			        ->first();
					if(!$check_option5)
					{
				        $student_feedback5 = new Student_feedback;
				        $student_feedback5->batch_id = $batch_id;
				        $student_feedback5->batch_date_id = $check_date['id'];
				        $student_feedback5->student_id = $student_id;
				        $student_feedback5->company_id = $batch->company_id;
				        $student_feedback5->trainer_id = $batch->trainer_id;
				        $student_feedback5->option_id = 6;
				        $student_feedback5->rating = $request->option6;
				        $student_feedback5->created_at = Carbon::now();
				        $student_feedback5->save();
					}

			        $check_option6 = Student_feedback::where("batch_date_id", $check_date['id'])
			        ->where("student_id", $student_id)
			        ->where("option_id", 7)
			        ->first();
					if(!$check_option6)
					{
				        $student_feedback6 = new Student_feedback;
				        $student_feedback6->batch_id = $batch_id;
				        $student_feedback6->batch_date_id = $check_date['id'];
				        $student_feedback6->student_id = $student_id;
				        $student_feedback6->company_id = $batch->company_id;
				        $student_feedback6->trainer_id = $batch->trainer_id;
				        $student_feedback6->option_id = 7;
				        $student_feedback6->rating = $request->option7;
				        $student_feedback6->created_at = Carbon::now();
				        $student_feedback6->save();
					}

			        $check_option7 = Student_feedback::where("batch_date_id", $check_date['id'])->where("student_id", $student_id)->where("option_id", 8)->first();
					if(!$check_option7)
					{
				        $student_feedback7 = new Student_feedback;
				        $student_feedback7->batch_id = $batch_id;
				        $student_feedback7->batch_date_id = $check_date['id'];
				        $student_feedback7->student_id = $student_id;
				        $student_feedback7->company_id = $batch->company_id;
				        $student_feedback7->trainer_id = $batch->trainer_id;
				        $student_feedback7->option_id = 8;
				        $student_feedback7->description = $request->option8;
				        $student_feedback7->created_at = Carbon::now();
				        $student_feedback7->save();						
					}
			        $check_option8 = Student_feedback::where("batch_date_id", $check_date['id'])
			        ->where("student_id", $student_id)
			        ->where("option_id", 9)
			        ->first();
					if(!$check_option8)
					{
				        $student_feedback8 = new Student_feedback;
				        $student_feedback8->batch_id = $batch_id;
				        $student_feedback8->batch_date_id = $check_date['id'];
				        $student_feedback8->student_id = $student_id;
				        $student_feedback8->company_id = $batch->company_id;
				        $student_feedback8->trainer_id = $batch->trainer_id;
				        $student_feedback8->option_id = 9;
				        $student_feedback8->description = $request->option9;
				        $student_feedback8->created_at = Carbon::now();
				        $student_feedback8->save();
					}

			        return view('students.feedback_thank');
            	}
            	else
            	{
	                Session::flash('message', 'You are not present in this class.');
	                return redirect()->back();
            	}
            }
            else
            {
                Session::flash('message', 'Date is not correct.');
                return redirect()->back();
            }  
        }
        else
        {
            Session::flash('message', 'Something went wrong, please try again.');
        	return redirect()->back(); 
        }
    }
    
}
