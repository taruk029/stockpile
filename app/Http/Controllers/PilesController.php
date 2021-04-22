<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location;
use App\Site;
use App\User;
use App\Country;
use App\Location_user;
use App\Site_user;
use App\Pile;
use App\Bulk_material;
use App\Bulk_and_moisture;
use App\Date_metron;
use DB;
use App\Helpers\Helper;
use Auth;
use Excel;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use App\Imports\PileImport;
use Mail;

class PilesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
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
                $piles = Pile::select('piles.*', 'locations.name as location_name', 'sites.name as site_name', 'bulk_materials.material_name', 'bulk_materials.material_code')
                ->leftjoin("sites", "piles.site_id", "=", "sites.id")
                ->leftjoin("locations", "piles.location_id", "=", "locations.id")
                ->leftjoin("bulk_materials",function($join){
                    $join->on("piles.type_of_bulk", "=", "bulk_materials.id")
                    ->on("piles.company_id", "=", "bulk_materials.company_id");
                })
                ->whereIn('piles.site_id', $site_array)
                ->orderBy('piles.id', 'desc')
                ->get();
                
                $sites = array();
                $user = User::find($loggedin_user);
                $location = Location::where('company_id', $user['company_id'])
                ->orderBy('name', 'ASC')
                ->get();
                
                return view('piles.index', ['piles'=>$piles, 'loggedin_user'=>$loggedin_user, 'role'=>$role, 'location'=>$location, 'sites'=>$sites]);
            }
            else
            {
            	$sites = array();

                $results = DB::table('piles')
                ->select('piles.*', 'locations.name as location_name', 'sites.name as site_name', 'bulk_materials.material_name', 'bulk_materials.material_code')
                ->leftjoin("sites", "piles.site_id", "=", "sites.id")
                ->leftjoin("locations", "piles.location_id", "=", "locations.id")
                ->leftjoin("bulk_materials",function($join){
                    $join->on("piles.type_of_bulk", "=", "bulk_materials.id")
                    ->on("piles.company_id", "=", "bulk_materials.company_id");
                })
                ->where('piles.company_id', $loggedin_user)                
                ->orderBy('piles.id', 'desc');

                if($request->pile)
                {
                    $results->where('piles.pile_reference_id', $request->pile);
                } 
                if($request->sites)
                {
                    $results->where('piles.site_id', $request->sites);
                } 
                if($request->location)
                {
                    $results->where('piles.location_id', $request->location);

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
                    $results->whereBetween('piles.date', [$start_date, $end_date]);
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
                return view('piles.index', ['piles'=>$piles, 'piles_dd'=>$piles_dd, 'location'=>$location, 'sites'=>$sites, 'loggedin_user'=>$loggedin_user, 'role'=>$role]);
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

        $bulk_material = Bulk_material::select('id', 'material_name', 'material_code')
        ->where('company_id', $loggedin_user)
       ->where('is_active', 1)
        ->orderBy('material_name', 'ASC')
        ->get();
        return view('piles.add', ['location'=>$location, 'bulk_material'=>$bulk_material, 'loggedin_user'=>$loggedin_user]);
    }
    public function get_sites(Request $request)
    {
    	$loggedin_user = Helper::get_logged_in_user();
    	$role = Helper::get_logged_in_user_role($loggedin_user);
        if($role==3)
        { 
            $user = User::find($loggedin_user);
            $site = Site::where('company_id', $user['company_id'])
            ->where("location_id", $request->id)
            ->where("is_active", 1)
            ->select('id', 'name')
            ->orderBy('name', 'ASC')
            ->get();
        }
        else
        {
            $site = Site::where('company_id', $loggedin_user)
            ->where("location_id", $request->id)
            ->where("is_active", 1)
            ->select('id', 'name')
            ->orderBy('name', 'ASC')
            ->get();
        }
        
            $data = array();
        for($i=0;$i<count($site);$i++){
           $data[] = array('id'=>$site[$i]->id,'name'=>$site[$i]->name);
        }
        $output  = $data;
        echo json_encode($output);
    }

    public function insert(Request $request)
    {
    	$request->validate([
            'location' => 'required',
            'site' => 'required',
            'pile_name' => 'required|string',
            'type_of_bulk' => 'required|numeric'
        ]);
    		$opening_balance_erp = 0;
    		$grn_pending = 0;
    		$consuption_booking_pending = 0;
    		$bunker_bin = 0;
    		$derived_sap = 0;

    		if(!empty($request->opening_balance_erp))
    			$opening_balance_erp = $request->opening_balance_erp;

    		if(!empty($request->grn_pending))
    			$grn_pending = $request->grn_pending;

    		if(!empty($request->consuption_booking_pending))
    			$consuption_booking_pending = $request->consuption_booking_pending;

    		if(!empty($request->bunker_bin))
    			$bunker_bin = $request->bunker_bin;

    		$total_1 = $opening_balance_erp+$grn_pending;
    		$total_2 = $consuption_booking_pending+$bunker_bin;

    		$derived_sap = $total_1-$total_2;
    		$exd = date_create($request->date);
        	$pile = new Pile;
			$pile->company_id = $request->company_id;
            $pile->location_id = $request->location;
            $pile->site_id = $request->site;
            $pile->pile_reference_id = $this->get_new_order_id();
            $pile->pile_type = $request->pile_type;
            $pile->pile_name = $request->pile_name;
            $pile->erp_code = $request->erp_code;
            $pile->type_of_bulk = $request->type_of_bulk;
            $pile->additional_info = $request->additional_info;
            $pile->value_per_ton = $request->value_per_ton;
            $pile->date = date_format($exd,'Y-m-d');
            $pile->time = $request->time;
            $pile->opening_balance_erp = $request->opening_balance_erp;
            $pile->grn_pending = $request->grn_pending;
            $pile->consuption_booking_pending = $request->consuption_booking_pending;
            $pile->bunker_bin = $request->bunker_bin;
            $pile->derived_sap = $derived_sap;
            $pile->bulk_density = $request->bulk_density;
            $pile->moisture = $request->moisture;
            $pile->is_active = 1;
            $pile->save();
            $pile_id = $pile->id;

            if($request->bulk_density!="" || $request->moisture!="")
            {
                $bulk_and_moisture = new Bulk_and_moisture;
                $bulk_and_moisture->pile_id = $pile_id;
                $bulk_and_moisture->bulk_density = $request->bulk_density;
                $bulk_and_moisture->moisture = $request->moisture;
                $bulk_and_moisture->save();
            }
			flash('Pile has been added successfully.')->success();
    		return redirect('piles'); 
    }

    public function edit($id)
    {
        $loggedin_user = Helper::get_logged_in_user();

        $location = Location::select('id', 'name')
        ->select('locations.*', 'countries.name as country_name')
        ->leftjoin("countries", "locations.country", "=", "countries.id")
        ->where('locations.company_id', $loggedin_user)
        ->where('locations.is_active', 1)
        ->orderBy('name', 'ASC')
        ->get();

        $bulk_material = Bulk_material::select('id', 'material_name', 'material_code')
        ->where('company_id', $loggedin_user)
       ->where('is_active', 1)
        ->orderBy('material_name', 'ASC')
        ->get();

        $pile = Pile::find($id);

        $site = Site::where('company_id', $loggedin_user)
            ->where("location_id", $pile['location_id'])
            ->where("is_active", 1)
            ->select('id', 'name')
            ->orderBy('name', 'ASC')
            ->get();

        return view('piles.edit', ['pile'=>$pile, 'location'=>$location, 'site'=>$site, 'bulk_material'=>$bulk_material , 'loggedin_user'=>$loggedin_user ]);
    }

    public function update(Request $request)
    {
    	$request->validate([
            'location' => 'required',
            'site' => 'required',
            'pile_name' => 'required|string',
            'type_of_bulk' => 'required|numeric'
        ]);
    		$opening_balance_erp = 0;
    		$grn_pending = 0;
    		$consuption_booking_pending = 0;
    		$bunker_bin = 0;
    		$derived_sap = 0;

    		if(!empty($request->opening_balance_erp))
    			$opening_balance_erp = $request->opening_balance_erp;

    		if(!empty($request->grn_pending))
    			$grn_pending = $request->grn_pending;

    		if(!empty($request->consuption_booking_pending))
    			$consuption_booking_pending = $request->consuption_booking_pending;

    		if(!empty($request->bunker_bin))
    			$bunker_bin = $request->bunker_bin;

    		$total_1 = $opening_balance_erp+$grn_pending;
    		$total_2 = $consuption_booking_pending+$bunker_bin;

    		$derived_sap = $total_1-$total_2;
    		$exd = date_create($request->date);
        	$pile = Pile::find($request->pile_id);
            $pile->location_id = $request->location;
            $pile->site_id = $request->site;
            $pile->pile_type = $request->pile_type;
            $pile->pile_name = $request->pile_name;
            $pile->erp_code = $request->erp_code;
            $pile->type_of_bulk = $request->type_of_bulk;
            $pile->additional_info = $request->additional_info;
            $pile->value_per_ton = $request->value_per_ton;
            $pile->date = date_format($exd,'Y-m-d');
            $pile->time = $request->time;
            $pile->opening_balance_erp = $request->opening_balance_erp;
            $pile->grn_pending = $request->grn_pending;
            $pile->consuption_booking_pending = $request->consuption_booking_pending;
            $pile->bunker_bin = $request->bunker_bin;
            $pile->derived_sap = $derived_sap;
            $pile->bulk_density = $request->bulk_density;
            $pile->moisture = $request->moisture;
            $pile->save();

            if($request->bulk_density!="" || $request->moisture!="")
            {
                $bulk_and_moisture = new Bulk_and_moisture;
                $bulk_and_moisture->pile_id = $request->pile_id;
                $bulk_and_moisture->bulk_density = $request->bulk_density;
                $bulk_and_moisture->moisture = $request->moisture;
                $bulk_and_moisture->save();
            }
			flash('Pile has been updated successfully.')->success();
    		return redirect('piles'); 
    }

    public function get_new_order_id()
    {
		$loggedin_user = Helper::get_logged_in_user();
		$count = Pile::count();
		$count = $count+1;
		$pile_id = "PL".$loggedin_user.date("is").$count;
		$check_order = Pile::where("pile_reference_id", $pile_id)->first();
		if($check_order)
		{
			self::get_new_order_id();
		}
		return $pile_id;
    }

   	public function change_status($id, $status)
    {
        $location = Pile::find($id);
        $location->is_active = $status;
        $location->save();

        if($status==0)
            flash('Pile has been deactivated successfully.')->success();
        else
            flash('Pile has been activated successfully.')->success();
            
        return redirect('piles'); 
    }

    public function add_bulk_pile()
    {
        $loggedin_user = Helper::get_logged_in_user();

        $location = Location::select('id', 'name')
        ->select('locations.*', 'countries.name as country_name')
        ->leftjoin("countries", "locations.country", "=", "countries.id")
        ->where('locations.company_id', $loggedin_user)
        ->where('locations.is_active', 1)
        ->orderBy('name', 'ASC')
        ->get();

        $bulk_material = Bulk_material::select('id', 'material_name', 'material_code')
        ->where('company_id', $loggedin_user)
       ->where('is_active', 1)
        ->orderBy('material_name', 'ASC')
        ->get();
        return view('piles.add_bulk_pile', ['location'=>$location, 'bulk_material'=>$bulk_material, 'loggedin_user'=>$loggedin_user]);
    }

    public function upload_bulk_piles(Request $request) 
    {
        $request->validate([
            'location' => 'required',
            'site' => 'required',
            'bulk_excel' => 'required|mimes:xlsx,xls'
        ]);
       $file_name = pathinfo($request->bulk_excel->getClientOriginalName(), PATHINFO_FILENAME); 
       $extension = pathinfo($request->bulk_excel->getClientOriginalName(), PATHINFO_EXTENSION);
       
       if($extension == "xlsx" || $extension == "xls")
       {
            $log = "";
            $error = "true";
            $import = new PileImport();
            $data = Excel::toArray($import, $request->bulk_excel);/*Excel::import($import, request()->file('bulk_excel'));*/

            $pile_log = "";
            $pile_log = "pile_".date("Ymdhis").".log";                    

            $plog = new Logger($pile_log);
            $plog->pushHandler(new StreamHandler(storage_path('logs/'.$pile_log)), Logger::INFO);

            $row_count = 0;
            /*echo"<pre>";
            print_r($data[0]);die;*/
            foreach ($data[0] as $row) 
            {
                $UNIX_DATE="";
                /*echo"<pre>";
                print_r($row);*/
                if($row_count!=0)
                {
                    if(!array_key_exists(0,$row))
                    {
                        flash('Pile Type column is not present or not in correct format in your excel')->error();
                        $error = "false";
                        break;
                    }
                    if(!array_key_exists(1,$row))
                    {
                        flash('Pile Name is not present or not in correct format in your excel')->error();
                        $error = "false";
                        break;
                    }
                    if(!array_key_exists(5,$row))
                    {
                        flash('Date column is not present or not in correct format in your excel')->error();
                        $error = "false";
                        break;
                    }
                    if(!array_key_exists(6,$row))
                    {
                        flash(' Time column is not present or not in correct format in your excel')->error();
                        $error = "false";
                        break;
                    }
                    $pile_type = "static";
                    if($row[0]=="")
                    {
                        $log = "' Row # ".$row_count."' could not be added because pile type is empty.";
                        //$plog->addError("error-".$log);
                    }
                    else
                    {
                        if($row[0]==2)
                            $pile_type = "dynamic";
                    }

                    if(!empty($row[1]))
                    {
                        $log = "' Row # ".$row_count."' could not be added because pile name is empty.";
                        //$plog->addError("error-".$log);
                    }

                    $check_pile = Pile::where("pile_name", $row[1])
                    ->where("company_id", $request->company_id)
                    ->where("location_id", $request->location)
                    ->where("site_id", $request->site)
                    ->get();

                    if(!count($check_pile))
                    {
                        $opening_balance_erp = 0;
                        $grn_pending = 0;
                        $consuption_booking_pending = 0;
                        $bunker_bin = 0;
                        $derived_sap = 0;

                        if(!empty($row[7]))
                            $opening_balance_erp = $row[7];

                        if(!empty($row[8]))
                            $grn_pending = $row[8];

                        if(!empty($row[9]))
                            $consuption_booking_pending = $row[9];

                        if(!empty($row[10]))
                            $bunker_bin = $row[10];

                        $total_1 = $opening_balance_erp+$grn_pending;
                        $total_2 = $consuption_booking_pending+$bunker_bin;

                        $derived_sap = $total_1-$total_2;
                        $exd = date_create($row['5']);
    
                        $time = date_format(date_create($row[6]), 'H:i A');
                        /*$UNIX_DATE = ($tm - 25569) * 86400;
                        $time = gmdate("H:i:s", $UNIX_DATE);*/

                        $pile = new Pile;
                        $pile->company_id = $request->company_id;
                        $pile->location_id = $request->location;
                        $pile->site_id = $request->site;
                        $pile->pile_reference_id = $this->get_new_order_id();
                        $pile->pile_type = $pile_type;
                        $pile->pile_name = $row[1];
                        $pile->erp_code = $row[2];
                        $pile->type_of_bulk = $row[11];
                        $pile->additional_info = $row[3];
                        $pile->value_per_ton = $row[4];
                        $pile->date = date_format($exd,'Y-m-d');
                        $pile->time = $time;
                        $pile->opening_balance_erp = $opening_balance_erp;
                        $pile->grn_pending = $grn_pending ;
                        $pile->consuption_booking_pending = $consuption_booking_pending;
                        $pile->bunker_bin = $bunker_bin;
                        $pile->derived_sap = $derived_sap;
                        $pile->bulk_density = $row[12];
                        $pile->moisture = $row[13];
                        $pile->is_active = 1;
                        $pile->save();
                        $row_count = $row_count+1;
                    }
                }   
                $row_count++;             
            }
            flash('Pile has been added successfully.')->success();
            return redirect('piles'); 
       }
       else
       {
            flash('Please choose files with "xls" or "xlxs" extentions only.')->error();
            return redirect()->back();
       }
    }
    /*[0] => Pile type
    [1] => Pile Name
    [2] => ERP Code
    [3] => Additional Site Info
    [4] => Value Per Ton
    [5] => Date
    [6] => Time
    [7] => Opening Balance ERP
    [8] => GRN Pending
    [9] => Consumption Booking Pending
    [10] => Bunker/BIN
    [11] => Bulk Material Code
    [12] => Bulk Density
    [13] => moisture
*/

    public function load_images(Request $request)
    {
        $loggedin_user = Helper::get_logged_in_user();
        $pile = Date_metron::where("id", $request->id)
            ->select('image_one', 'image_one_url', 'image_two', 'image_two_url')
            ->first();
        $data = array();
        $image_one =  asset('public/assets/media/users/noimage.jpg');
        $image_two =  asset('public/assets/media/users/noimage.jpg');
        if($pile)
        {
            if(!empty($pile['image_one']))
            {
                if(file_exists(base_path().'/public/date_metron/'.$pile['image_one']))  
                    $image_one = $pile['image_one_url'];                    
            }
            if(!empty($pile['image_two']))
            {
                if(file_exists(base_path().'/public/date_metron/'.$pile['image_two']))  
                    $image_two = $pile['image_two_url'];                    
            }
            $data = array('image_one'=>$image_one,'image_two'=>$image_two);
        }
        echo json_encode($data);
    }

    public function advance_report(Request $request)
    {
        $loggedin_user = Helper::get_logged_in_user();
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

        return view('reports.advance_report', ['piles'=>$piles, 'piles_dd'=>$piles_dd, 'location'=>$location, 'sites'=>$sites]);
    }

    public function view_advance_report($id)
    {
        if($id)
        {
            $date_metron_id = base64_decode($id);

            $results = DB::table('date_metrons')
            ->leftjoin("piles", "date_metrons.pile_id", "=", "piles.id")
            ->leftjoin("locations", "locations.id", "=", "piles.location_id")
            ->leftjoin("sites", "sites.id", "=", "piles.site_id")
            ->select('date_metrons.id as date_metron_id', 'piles.pile_reference_id', 'piles.pile_name', 'piles.id as pileId', 'piles.bulk_density', 'piles.moisture', 'piles.additional_info', 'locations.name as location_name', 'sites.name as site_name', 'date_metrons.*')
            ->where("date_metrons.id", $date_metron_id);
            $pile =  $results->first();

            return view('reports.view_advance_report', ['pile'=>$pile]);
        }
    }

    public function save_share_image(Request $request)
    {
        if($request->imgdata)
        {
            $imagedata = base64_decode($request->imgdata);
            $filename = md5(uniqid(rand(), true));
            //path where you want to upload image
            $file = base_path().'/public/share_images/'.$filename.'.png';
            $imageurl  = url('/')."/public/share_images/".$filename.'.png';
            file_put_contents($file,$imagedata);
            echo $imageurl;
         }
       else
            echo 0;
    }

    public function share_advance_report(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        $email_data['to'] =  $request->email;
        $email_data['imgurl'] =  $request->imgurl; 
            
            Mail::send('emails.share_advance_report', $email_data, function($message) use ($email_data) 
            {
                $message->to($email_data['to'], $email_data['to'])
                ->subject("StockPile Volume|Advance Pile Report");
                $message->from('support@stockpilevolume.com','StockPile Volume'); 
            });
        flash('Advance pile report has been sent successfully.')->success();
        return redirect('advance_report'); 
    }
}
