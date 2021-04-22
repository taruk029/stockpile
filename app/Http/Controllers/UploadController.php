<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use App\Location;
use App\Unproductive_reason_list;
use App\User;
use App\User_Region;
use App\Plan;
use App\Outlet_form_plan;
use App\Start_day_plan;
use App\End_day_plan;
use Carbon\Carbon;

class UploadController extends Controller
{
    public function index()
    {
       return view('admin.upload');
    }


    public function add_bulk_region(Request $request) 
    {
       $file_name = pathinfo($request->bulk_excel->getClientOriginalName(), PATHINFO_FILENAME); 
       $extension = pathinfo($request->bulk_excel->getClientOriginalName(), PATHINFO_EXTENSION);
       
       if($extension == "xlsx" || $extension == "xls")
       {
            $log = "";
            $error = "true";
            Excel::load($request->file('bulk_excel')->getRealPath(), function ($reader) use ($request, $log, $file_name, $extension, &$error)
            {
            	$row_count = 1;
                /*echo "<pre>";
                print_r($reader->toArray());
                die;*/
                foreach ($reader->toArray() as $key => $row) 
                {
                	$user_check = User::where("email", $row['email'])->first();
                	if(!$user_check)
                	{
	                	$user = new User;
				        $user->name = $row['trainer_name'];
				        $user->email = $row['email'];
				        $user->phone = $row['phone'];
				        $user->role = 1;
				        $user->password = bcrypt($row['password']);
				        $user->save();
				        $user_id = $user->id;
			    	}
			    	else
			    		$user_id = $user_check['id'];

			        $user_region = new User_Region;
                    $user_region->user_id = $user_id ;
                    $user_region->region_id = $row['region_id'];
                    $user_region->is_active = 1;
                    $user_region->save();
                }
             
            });
       }
       else
       {
            flash('Please choose files with "xls" or "xlxs" extentions only.')->error();
            return redirect()->back();
       }
    }

	public function add_bulk_plan(Request $request) 
	    {
	       $file_name = pathinfo($request->bulk_excel->getClientOriginalName(), PATHINFO_FILENAME); 
	       $extension = pathinfo($request->bulk_excel->getClientOriginalName(), PATHINFO_EXTENSION);
	       
	       if($extension == "xlsx" || $extension == "xls")
	       {
	            $log = "";
	            $error = "true";
	            Excel::load($request->file('bulk_excel')->getRealPath(), function ($reader) use ($request, $log, $file_name, $extension, &$error)
	            {
	            	$row_count = 1;
	                /*echo "<pre>";
	                print_r($reader->toArray());
	                die;*/
	                foreach ($reader->toArray() as $key => $row) 
	                {
	                	$plan = new Plan;
	                    $plan->trainer_id = \App\Helpers\Helper::get_emplyee_id_from_name($row['trainer_name']);
	                    $plan->trainer_region_id = $row['trainer_region_id'];
	                    $plan->date = Carbon::parse($row['date'])->format('d-m-Y');
	                    $plan->distributor_code = $row['distributor_code'];
	                    $plan->distributor_name = $row['distributor_name'];
	                    $plan->distributor_address = $row['distributor_address'];
	                    $plan->dbsr_code = $row['dbsr_code'];
	                    $plan->dbsr_name = $row['dbsr_name'];
	                    $plan->dbsr_mobile = $row['dbsr_mobile'];
	                    $plan->tsi_name = $row['tsi_name'];
	                    $plan->tsi_mobile = $row['tsi_mobile'];
	                    $plan->created_at = Carbon::now();
	                    $plan->save();
	                }
	             
	            });
	       }
	       else
	       {
	            flash('Please choose files with "xls" or "xlxs" extentions only.')->error();
	            return redirect()->back();
	       }
	    }

	public function add_bulk_start_plan(Request $request) 
			    {
			       $file_name = pathinfo($request->bulk_excel->getClientOriginalName(), PATHINFO_FILENAME); 
			       $extension = pathinfo($request->bulk_excel->getClientOriginalName(), PATHINFO_EXTENSION);
			       
			       if($extension == "xlsx" || $extension == "xls")
			       {
			            $log = "";
			            $error = "true";
			            Excel::load($request->file('bulk_excel')->getRealPath(), function ($reader) use ($request, $log, $file_name, $extension, &$error)
			            {
			            	$row_count = 1;
			                /*echo "<pre>";
			                print_r($reader->toArray());
			                die;*/
			                foreach ($reader->toArray() as $key => $row) 
			                {
			                	$plan = new Start_day_plan;
				                $plan->plan_id = $row['plan_id'];
				                $plan->start_beats = $row['store_planned'];
				                $plan->meeting_time = $row['meeting_time'];
				                $plan->town = $row['location'];
				                $plan->market = $row['market'];
				                $plan->route_code = $row['route_code'];
				                $plan->dbsr_first_shop = $row['dbsrs_first_shop'];
				                $plan->start_range_compliance = $row['plan_id'];
				                $plan->created_at = Carbon::now();
				                $plan->save();
			                }
			             
			            });
			       }
			       else
			       {
			            flash('Please choose files with "xls" or "xlxs" extentions only.')->error();
			            return redirect()->back();
			       }
			    }


public function add_bulk_end_plan(Request $request) 
			    {
			       $file_name = pathinfo($request->bulk_excel->getClientOriginalName(), PATHINFO_FILENAME); 
			       $extension = pathinfo($request->bulk_excel->getClientOriginalName(), PATHINFO_EXTENSION);
			       
			       if($extension == "xlsx" || $extension == "xls")
			       {
			            $log = "";
			            $error = "true";
			            Excel::load($request->file('bulk_excel')->getRealPath(), function ($reader) use ($request, $log, $file_name, $extension, &$error)
			            {
			            	$row_count = 1;
			                /*echo "<pre>";
			                print_r($reader->toArray());
			                die;*/
			                foreach ($reader->toArray() as $key => $row) 
			                {
			                	$plan = new End_day_plan;
				                $plan->plan_id = $row['plan_id'];
				                $plan->dbsr_last_shop_meeting = $row['dbsr_last_shop_meeting_time'];
				                $plan->end_range_compliance = $row['range_compliance_for_the_day'];
				                $plan->range_compliance_mtd = $row['range_compliance_mtd'];
				                $plan->coaching_feedback = $row['coaching_feedback'];
				                $plan->action_plan = $row['action_plan'];
				                $plan->exit_time = $row['sign_off_time'];
				                $plan->briefing_taken_from = $row['briefing_taken_from'];
				                $plan->briefing_taken_to = $row['briefing_taken_till'];
				                $plan->created_at = Carbon::now();
				                $plan->save();
			                }
			             
			            });
			       }
			       else
			       {
			            flash('Please choose files with "xls" or "xlxs" extentions only.')->error();
			            return redirect()->back();
			       }
			    }

public function add_bulk_outlet_plan(Request $request) 
			    {
			       $file_name = pathinfo($request->bulk_excel->getClientOriginalName(), PATHINFO_FILENAME); 
			       $extension = pathinfo($request->bulk_excel->getClientOriginalName(), PATHINFO_EXTENSION);
			       
			       if($extension == "xlsx" || $extension == "xls")
			       {
			            $log = "";
			            $error = "true";
			            Excel::load($request->file('bulk_excel')->getRealPath(), function ($reader) use ($request, $log, $file_name, $extension, &$error)
			            {
			            	/*$row_count = 1;
			                echo "<pre>";
			                print_r($reader->toArray());
			                die;*/
			                foreach ($reader->toArray() as $key => $row) 
			                {
			                	$productivity=0;
			                	if($row['outlet_performance']=="Productive")
			                		$productivity=1;

			                	$plan = new Outlet_form_plan;
					            $plan->plan_id = $row['plan_id'];
					            $plan->outlet_code = $row['outlet_code'];
					            $plan->outlet_name = $row['outlet_name'];
					            $plan->range_target = $row['outlet_range_compliance_target'];
					            $plan->range_start = $row['outlet_range_compliance_start'];
					            $plan->range_end = $row['outlet_range_compliance_end'];
					            $plan->outlet_productivity = $productivity;
					            $plan->non_productivity_reason = $row['reason_id'];
					            $plan->created_at = Carbon::now();
					            $plan->created_time = Carbon::now()->format('H:i:s');
					            $plan->save();

			                }
			             
			            });
			       }
			       else
			       {
			            flash('Please choose files with "xls" or "xlxs" extentions only.')->error();
			            return redirect()->back();
			       }
			    }


}


