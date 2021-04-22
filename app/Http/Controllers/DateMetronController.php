<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pile;
use App\Site;
use App\Location;
use App\Date_metron;
use App\Bulk_material;
use DB;
use App\Helpers\Helper;
use Auth;
use Excel;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use App\Imports\DateMetronImport;

class DateMetronController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {        
        $loggedin_user = Helper::get_logged_in_user();
        $dates_metron = Date_metron::leftjoin("piles", "date_metrons.pile_id", "=", "piles.id")
        ->select("date_metrons.*", "piles.pile_name", "piles.pile_reference_id")
        ->where('date_metrons.user_id', $loggedin_user)
        ->get();
        return view('dates_metron.index', ['dates_metron'=>$dates_metron, 'loggedin_user'=>$loggedin_user]);
    }

    public function add()
    {
        $loggedin_user = Helper::get_logged_in_user();

        $location = Location::select('id', 'name')
        ->select('locations.*', 'countries.name as country_name')
        ->leftjoin("countries", "locations.country", "=", "countries.id")
        ->where('locations.is_active', 1)
        ->orderBy('name', 'ASC')
        ->get();

        /*$piles = Pile::select('piles.id', 'piles.pile_name', 'piles.pile_reference_id')
        ->where('piles.company_id', $loggedin_user)
        ->orderBy('piles.pile_name', 'ASC')
        ->get();*/

        return view('dates_metron.add', ['location'=>$location, 'loggedin_user'=>$loggedin_user]);
    }

    public function get_staff_sites(Request $request)
    {
        $site = Site::where("location_id", $request->id)
            ->where("is_active", 1)
            ->select('id', 'name')
            ->orderBy('name', 'ASC')
            ->get();
            $data = array();
        for($i=0;$i<count($site);$i++){
           $data[] = array('id'=>$site[$i]->id,'name'=>$site[$i]->name);
        }
        $output  = $data;
        echo json_encode($output);
    }

    public function get_staff_piles(Request $request)
    {
        $pile = Pile::where("site_id", $request->id)
            ->where("is_active", 1)
            ->select('id', 'pile_name', 'pile_reference_id')
            ->orderBy('pile_name', 'ASC')
            ->get();
        $data = array();
        for($i=0;$i<count($pile);$i++){
           $data[] = array('id'=>$pile[$i]->id,'name'=>$pile[$i]->pile_name,'pile_reference_id'=>$pile[$i]->pile_reference_id);
        }
        $output  = $data;
        echo json_encode($output);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'pile' => 'required|numeric',
            'date' => 'required|string',            
            'method' => 'required|string'
        ]);

        $image_one_url = "";
        $images_one_fileName = "";
        if($request->hasFile('image_one'))
        {
            $images = $request->file('image_one');
            $images_one_fileName = rand(99,9999).date('Ymdhis').'.'.$images->getClientOriginalExtension();
            $images->move(base_path().'/public/date_metron/', $images_one_fileName);
            $image_one_url = url('/')."/public/date_metron/".$images_one_fileName;
        }

        $image_two_url = "";
        $images_two_fileName = "";
        if($request->hasFile('image_two'))
        {
            $images2 = $request->file('image_two');
            $images_two_fileName = rand(99,9999).date('Ymdhis').'.'.$images2->getClientOriginalExtension();
            $images2->move(base_path().'/public/date_metron/', $images_two_fileName);
            $image_two_url = url('/')."/public/date_metron/".$images_two_fileName;
        }
            $pile = Pile::find($request->pile);
            $exd = date_create($request->date);
            $date_metron = new Date_metron;            
            $date_metron->user_id = Auth::user()->id;
            $date_metron->company_id = $pile['company_id'];
            $date_metron->location_id = $request->location;
            $date_metron->site_id = $request->site;
            $date_metron->pile_id = $request->pile;
            $date_metron->pile_type = $request->pile_type;
            $date_metron->date_of_survey = date_format($exd,'Y-m-d');
            $date_metron->start_time = $request->start_time;
            $date_metron->end_time = $request->end_time;
            $date_metron->method = $request->method;
            $date_metron->volume = $request->volume;
            $date_metron->toe_confidence = $request->toe_confidence;
            $date_metron->surface_confidence = $request->surface_confidence;
            $date_metron->combined_piles = $request->combined_piles;
            $date_metron->standing_water = $request->standing_water;
            $date_metron->debris = $request->debris;
            $date_metron->equipment_obstruction = $request->equipment_obstruction;
            $date_metron->vegetation = $request->vegetation;
            $date_metron->highwall = $request->highwall;
            $date_metron->lighting_issue = $request->lighting_issue;
            $date_metron->burried_base = $request->burried_base;
            $date_metron->ogl = $request->ogl;
            $date_metron->piles_covered_with_tarpolin = $request->piles_covered_with_tarpolin;
            $date_metron->comments = $request->comments;
            $date_metron->image_one = $images_one_fileName;
            $date_metron->image_one_url = $image_one_url;
            $date_metron->image_two = $images_two_fileName;
            $date_metron->image_two_url = $image_two_url;
            $date_metron->three_dmodel = $request->three_dmodel;
            $date_metron->save();
            flash('Date Metron has been added successfully.')->success();
            return redirect('dates_metron'); 
    }

    public function edit($id)
    {
        $loggedin_user = Helper::get_logged_in_user();

        $date_metron = Date_metron::where('id', $id)->first();
        
        $location = Location::select('id', 'name')
        ->select('locations.*', 'countries.name as country_name')
        ->leftjoin("countries", "locations.country", "=", "countries.id")
        ->where('locations.is_active', 1)
        ->orderBy('name', 'ASC')
        ->get();

        $site = Site::where("location_id", $date_metron['location_id'])
            ->where("is_active", 1)
            ->select('id', 'name')
            ->orderBy('name', 'ASC')
            ->get();

        $piles = Pile::where("site_id", $date_metron['site_id'])
            ->where("is_active", 1)
            ->select('id', 'pile_name', 'pile_reference_id')
            ->orderBy('pile_name', 'ASC')
            ->get();

        return view('dates_metron.edit', ['date_metron'=>$date_metron, 'location'=>$location, 'site'=>$site, 'piles'=>$piles, 'loggedin_user'=>$loggedin_user]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'pile' => 'required|numeric',
            'date' => 'required|string',            
            'method' => 'required|string'
        ]);

        $image_one_url = "";
        $images_one_fileName = "";
        if($request->hasFile('image_one'))
        {
            $old_pic_one = Date_metron::select('image_one')->where('id', $request->date_metron_id)->first();
            if($old_pic_one)
            {
                if(!empty($old_pic_one->image_one))
                {
                    $old_file = base_path().'/public/date_metron/'.$old_pic_one->image_one;
                    if(file_exists($old_file))
                    {
                        unlink($old_file);
                    }
                }
                $images = $request->file('image_one');
                $images_one_fileName = rand(99,9999).date('Ymdhis').'.'.$images->getClientOriginalExtension();
                $images->move(base_path().'/public/date_metron/', $images_one_fileName);
                $image_one_url = url('/')."/public/date_metron/".$images_one_fileName;
            }
        }

        $image_two_url = "";
        $images_two_fileName = "";
        if($request->hasFile('image_two'))
        {
            $old_pic_two = Date_metron::select('image_two')->where('id', $request->date_metron_id)->first();
            if($old_pic_two)
            {
                if(!empty($old_pic_two->image_two))
                {
                    $old_file2 = base_path().'/public/date_metron/'.$old_pic_two->image_two;
                    if(file_exists($old_file2))
                    {
                        unlink($old_file2);
                    }
                }
                $images2 = $request->file('image_two');
                $images_two_fileName = rand(99,9999).date('Ymdhis').'.'.$images2->getClientOriginalExtension();
                $images2->move(base_path().'/public/date_metron/', $images_two_fileName);
                $image_two_url = url('/')."/public/date_metron/".$images_two_fileName;
            }
        }

        $pile = Pile::find($request->pile);
        $exd = date_create($request->date);
        $date_metron = Date_metron::find($request->date_metron_id);;
        $date_metron->company_id = $pile['company_id'];
        $date_metron->location_id = $request->location;
        $date_metron->site_id = $request->site;
        $date_metron->pile_id = $request->pile;
        $date_metron->pile_type = $request->pile_type;
        $date_metron->date_of_survey = date_format($exd,'Y-m-d');
        $date_metron->start_time = $request->start_time;
        $date_metron->end_time = $request->end_time;
        $date_metron->method = $request->method;
        $date_metron->volume = $request->volume;
        $date_metron->toe_confidence = $request->toe_confidence;
        $date_metron->surface_confidence = $request->surface_confidence;
        $date_metron->combined_piles = $request->combined_piles;
        $date_metron->standing_water = $request->standing_water;
        $date_metron->debris = $request->debris;
        $date_metron->equipment_obstruction = $request->equipment_obstruction;
        $date_metron->vegetation = $request->vegetation;
        $date_metron->highwall = $request->highwall;
        $date_metron->lighting_issue = $request->lighting_issue;
        $date_metron->burried_base = $request->burried_base;
        $date_metron->ogl = $request->ogl;
        $date_metron->piles_covered_with_tarpolin = $request->piles_covered_with_tarpolin;
        $date_metron->comments = $request->comments;
        if($request->hasFile('image_one'))
        {
            $date_metron->image_one = $images_one_fileName;
            $date_metron->image_one_url = $image_one_url;
        }
        if($request->hasFile('image_two'))
        {
            $date_metron->image_two = $images_two_fileName;
            $date_metron->image_two_url = $image_two_url;
        }
        $date_metron->three_dmodel = $request->three_dmodel;
        $date_metron->save();
        flash('Date Metron has been updated successfully.')->success();
        return redirect('dates_metron'); 
    }

    public function add_bulk_date_metron()
    {
        $loggedin_user = Helper::get_logged_in_user();

        $location = Location::select('id', 'name')
        ->select('locations.*', 'countries.name as country_name')
        ->leftjoin("countries", "locations.country", "=", "countries.id")
        ->where('locations.is_active', 1)
        ->orderBy('name', 'ASC')
        ->get();
        return view('dates_metron.add_bulk_pile', ['location'=>$location, 'loggedin_user'=>$loggedin_user]);
    }

    public function upload_bulk_date_metron(Request $request) 
    {
        $request->validate([
            'bulk_excel' => 'required|mimes:xlsx,xls'
        ]);
       $file_name = pathinfo($request->bulk_excel->getClientOriginalName(), PATHINFO_FILENAME); 
       $extension = pathinfo($request->bulk_excel->getClientOriginalName(), PATHINFO_EXTENSION);
       
       if($extension == "xlsx" || $extension == "xls")
       {
            $log = "";
            $error = "true";
            $import = new DateMetronImport();
            $data = Excel::toArray($import, $request->bulk_excel);

            $pile_log = "";
            $pile_log = "datemetron_".date("Ymdhis").".log";                    

            $plog = new Logger($pile_log);
            $plog->pushHandler(new StreamHandler(storage_path('logs/'.$pile_log)), Logger::INFO);

            $row_count = 0;
            /*echo"<pre>";
            print_r($data[0]);die;*/
            foreach ($data[0] as $row) 
            {
                $UNIX_DATE="";
                if($row_count!=0)
                {
                    if(!array_key_exists(0,$row))
                    {
                        flash('Pile Code column is not present or not in correct format in your excel')->error();
                        $error = "false";
                        break;
                    }
                    if(!array_key_exists(1,$row))
                    {
                        flash('Pile Type is not present or not in correct format in your excel')->error();
                        $error = "false";
                        break;
                    }
                    if(!array_key_exists(2,$row))
                    {
                        flash('Date of Survey is not present or not in correct format in your excel')->error();
                        $error = "false";
                        break;
                    }
                    
                    if($row[0]=="")
                    {
                        $log = "' Row # ".$row_count."' could not be added because pile code is empty.";
                        continue;
                        //$plog->addError("error-".$log);
                    }
                    $pile_type = "indoor";
                    if($row[1]!="indoor")
                    {
                        $pile_type = "outdoor";
                    }
                    $pile_id = Helper::get_pile_by_code($row[0]);
                    if($pile_id)
                    {
                        
                        $pile = Pile::find($pile_id);
                        $check_metron = Date_metron::where("pile_id", $pile_id)
                        ->where("company_id", $request->company_id)
                        ->where("location_id", $pile['location_id'])
                        ->where("site_id", $pile['site_id'])
                        ->get();                

                        if(!count($check_metron))
                        {
                            $exd = date_create($row['2']);

                            /*$Start_UNIX_DATE = ($row[3] - 25569) * 86400;
                            $start_time = gmdate("H:i:s", $Start_UNIX_DATE);

                            $End_UNIX_DATE = ($row[4] - 25569) * 86400;
                            $end_time = gmdate("H:i:s", $End_UNIX_DATE);*/
                            
                            $start_time = date_format(date_create($row[3]), 'H:i A');
                            $end_time = date_format(date_create($row[4]), 'H:i A');

                            $date_metron = new Date_metron;            
                            $date_metron->user_id = Auth::user()->id;
                            $date_metron->company_id = $pile['company_id'];
                            $date_metron->location_id = $pile['location_id'];
                            $date_metron->site_id = $pile['site_id'];
                            $date_metron->pile_id = $pile_id;
                            $date_metron->pile_type = $pile_type;
                            $date_metron->date_of_survey = date_format($exd,'Y-m-d');
                            $date_metron->start_time = $start_time;
                            $date_metron->end_time = $end_time;
                            $date_metron->method = $row[5];
                            $date_metron->volume = $row[6];
                            $date_metron->toe_confidence = $row[7];
                            $date_metron->surface_confidence = $row[8];
                            $date_metron->combined_piles = $row[9];
                            $date_metron->standing_water = $row[10];
                            $date_metron->debris = $row[11];
                            $date_metron->equipment_obstruction = $row[12];
                            $date_metron->vegetation = $row[13];
                            $date_metron->highwall = $row[14];
                            $date_metron->lighting_issue = $row[15];
                            $date_metron->burried_base = $row[16];
                            $date_metron->ogl = $row[17];
                            $date_metron->piles_covered_with_tarpolin = $row[18];
                            $date_metron->comments = $row[19];
                            $date_metron->image_one = $row[20];
                            $date_metron->image_one_url = url('/')."/public/date_metron/".$row[20];
                            $date_metron->image_two = $row[21];
                            $date_metron->image_two_url = url('/')."/public/date_metron/".$row[21];
                            $date_metron->three_dmodel = $row[22];
                            $date_metron->save();
                            $row_count = $row_count+1;
                        }
                    }
                }   
                $row_count++;             
            }
            flash('Date Metron has been added successfully.')->success();
            return redirect('dates_metron');  
       }
       else
       {
            flash('Please choose files with "xls" or "xlxs" extentions only.')->error();
            return redirect()->back();
       }
    }
}