<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Pile;
use App\User;
use App\Site_user;
use App\Date_metron;
use App\Helpers\Helper;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class DashboardPile implements FromCollection,WithHeadings,WithHeadingRow,WithEvents,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct()
    {        
    }

    public function collection()
    {    	
    	$loggedin_user = Helper::get_logged_in_user();
    	if($loggedin_user)
    	{
    	    $role = User::find($loggedin_user);
    	    if($role['role']==2)
    	    {
    	    $piles = Pile::leftjoin("date_metrons", "date_metrons.pile_id", "=", "piles.id")
            ->leftjoin("locations", "locations.id", "=", "piles.location_id")
            ->leftjoin("sites", "sites.id", "=", "piles.site_id")
            ->select('date_metrons.id as date_metron_id', 'piles.pile_reference_id', 'piles.pile_name', 'piles.id as pileId', 'piles.bulk_density', 'piles.moisture', 'piles.additional_info', 'locations.name as location_name', 'sites.name as site_name', 'date_metrons.pile_type','date_metrons.start_time','date_metrons.end_time','date_metrons.volume','date_metrons.date_of_survey')
            ->where("date_metrons.company_id", $loggedin_user)
            ->where("date_metrons.volume", "!=", "") 
            ->orderBy("piles.id", "desc")
            ->get();
    	    }
    	    else
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
                $piles = Pile::leftjoin("date_metrons", "date_metrons.pile_id", "=", "piles.id")
                ->leftjoin("locations", "locations.id", "=", "piles.location_id")
                ->leftjoin("sites", "sites.id", "=", "piles.site_id")
                ->select('date_metrons.id as date_metron_id', 'piles.pile_reference_id', 'piles.pile_name', 'piles.id as pileId', 'piles.bulk_density', 'piles.moisture', 'piles.additional_info', 'locations.name as location_name', 'sites.name as site_name', 'date_metrons.pile_type','date_metrons.start_time','date_metrons.end_time','date_metrons.volume','date_metrons.date_of_survey')
                ->whereIn('piles.site_id', $site_array)
                ->where("date_metrons.volume", "!=", "") 
                ->orderBy("piles.id", "desc")
                ->get();
    	    }

         	$data = array();
            foreach($piles as $row)
            {
                $start_time = "";
                $end_time = "";
                $tonnage ="BD/Moisture not entered";
                if($row->volume!="" && $row->moisture && $row->bulk_density) 
                {
                    $tonnage = (($row->volume*$row->bulk_density)*(1-$row->moisture));
                }
                $start_time = date_create($row->start_time);
                $end_time = date_create($row->end_time);
                $date_of_survey = date_create($row->date_of_survey);
                $data[] = array("pile_code"=>$row->pile_reference_id, 
                        "location"=>$row->location_name, 
                        "site"=>$row->site_name, 
                        "pile_type"=>$row->pile_type, 
                        "pile_name"=>$row->pile_name,
                        "additional_info"=>$row->additional_info,
                        "start_time"=>date_format($start_time,'h:i:s'),
                        "end_time"=>date_format($end_time,'h:i:s'),
                        "volume"=>$row->volume,
                        "tonnage"=>$tonnage,
                        "view_3d"=>date_format($date_of_survey,'d-m-Y'),
                );
            }
            return collect([ $data ]);
    	}

    }
   /* public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/assets/media/logos/logo-8.png'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');

        return $drawing;
    }
*/
    public function registerEvents(): array {
        $styleArray=['font'=>['bold'=>true,'size'=>12,],
               'borders' => [
               'allborders' => [
               'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
               'color' => ['argb' => 'FF000000'],],],];
        
        $styleArray1=['font'=>['bold'=>true,'size'=>20,],
           'borders' => [
           'allborders' => [
           'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
           'color' => ['argb' => 'FF000000'],],],];


       return [
           AfterSheet::class=> function(AfterSheet $event) use ($styleArray,$styleArray1) {

             $event->sheet->insertNewRowBefore(1, 3);
             $event->sheet->mergeCells('A1:K1');
             $event->sheet->mergeCells('A2:K2');
             $event->sheet->mergeCells('A3:K3');
             $event->sheet->setCellValue('A1','STOCKPILE VOLUME');
             $event->sheet->setCellValue('A2','Measure Any Pile');
             $event->sheet->getStyle('A1')->applyFromArray($styleArray1);
             $event->sheet->getStyle('A2')->applyFromArray($styleArray);
             $event->sheet->getStyle('A1:K1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
             $event->sheet->getStyle('A1:K1')->getFill()->getStartColor()->setRGB('febd16');
             $event->sheet->getStyle('A2:K2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
             $event->sheet->getStyle('A2:K2')->getFill()->getStartColor()->setRGB('febd16');
             $event->sheet->getStyle('A3:K3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
             $event->sheet->getStyle('A3:K3')->getFill()->getStartColor()->setRGB('000000');
             $event->sheet->getStyle('A4:K4')->applyFromArray($styleArray);
             $event->sheet->getStyle('A4:K4')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
             $event->sheet->getRowDimension('1')->setRowHeight(30);
             $event->sheet->getRowDimension('1')->setOutlineLevel(1);
             $event->sheet->getStyle('A4:K4')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
             $event->sheet->getStyle('A4:K4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

             /*$event->sheet->getStyle('A2:G8')->applyFromArray($styleArray);*/
                        
          	},
       ];
   }

    public function headings(): array
    {
        return [
           'Pile Code',
		   'Location',
		   'Site',
		   'Pile type',
		   'Pile Name',
		   'Additional Info',
		   'Start Time',
		   'End Time',
		   'Volume',
		   'Tonnage',
		   'Date of Survey',
        ];
    }
}