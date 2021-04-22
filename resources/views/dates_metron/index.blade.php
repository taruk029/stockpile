@extends('layouts.app')
@section('title', 'Date Metron')
@push('styles')
<link href="{{ asset('public/datatables/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style type="text/css">
    .kt-portlet--mobile
{
    overflow-x:scroll !important;
}
</style>
@endpush
@section('content')
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">

        <h3 class="kt-subheader__title">
            Dashboard
        </h3>

        <span class="kt-subheader__separator kt-hidden"></span>
        <div class="kt-subheader__breadcrumbs">
            <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a href="#" class="kt-subheader__breadcrumbs-link">
                Date Metron List                    
            </a>
             
    </div>
        </div>

    <div class="kt-subheader__toolbar">
        <div class="kt-subheader__wrapper">
            <a href="#" class="btn kt-subheader__btn-daterange" id="kt_dashboard_daterangepicker" data-toggle="kt-tooltip" title="Today is <?php echo date("d-m-Y"); ?>" data-placement="left">
                <span class="kt-subheader__btn-daterange-title" id="kt_dashboard_daterangepicker_title">Today</span>&nbsp;
                <span class="kt-subheader__btn-daterange-date" id="kt_dashboard_daterangepicker_date"><?php echo date("M d"); ?></span>
            </a>
        </div>
    </div>
</div>
<!-- end:: Subheader -->
                            
    <!-- begin:: Content -->
    <div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
        <!--Begin::Dashboard 8-->

<!--Begin::Section-->
<div class="row">
     <div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand fa fa-building"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                Date Metron List
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
    <div class="kt-portlet__head-actions">
       &nbsp;
        <a href="{{ url('add_date_metron')}}" class="btn btn-brand btn-elevate btn-icon-sm">
            <i class="la la-plus"></i>
            Add New Date Metron
        </a>
        <a href="{{ url('add_bulk_date_metron')}}" class="btn btn-brand btn-elevate btn-icon-sm">
            <i class="la la-plus"></i>
            Add Bulk Date Metron
        </a>
    </div>  
</div>      </div>
    </div>

    <div class="kt-portlet__body">
        <!--begin: Datatable -->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Piles Name/Code</th>
                    <th>Pile type</th>
                    <th>Date of Survey </th>
                    <th>Start Time </th>
                    <th>End Time </th>
                    <th>Method </th>
                    <th>Volume </th>
                    <th>Toe Confidence </th>
                    <th>Surface Confidence </th>
                    <th>Combined Piles </th>
                    <th>Standing Water </th>
                    <th>Debris </th>
                    <th>Equipment Obstruction</th>
                    <th>Vegetation</th>
                    <th>Highwall</th>
                    <th>Lighting Issue</th>
                    <th>Burried Base </th>
                    <th>OGL </th>
                    <th>Piles covered with Tarpolin </th>
                    <th>Comments</th>
                    <th>Image 1</th>
                    <th>Image 2</th>
                    <th>3d Model Link</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php $i = 1; ?>
                @foreach($dates_metron as $row )
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $row->pile_name  }}/{{ $row->pile_reference_id  }}</td>
                    <td>{{ $row->pile_type  }}</td>
                    <td><?php  $exd = date_create($row->date_of_survey); echo date_format($exd,'d-m-Y');?> </td>
                    <td><?php  echo $row->start_time; ?></td>
                    <td><?php  echo $row->end_time; ?></td>
                    <td>{{ $row->method  }}</td>
                    <td>{{ $row->volume  }}</td>
                    <td>{{ $row->toe_confidence  }}</td>
                    <td>{{ $row->surface_confidence  }}</td>
                    <td>{{ $row->combined_piles  }}</td>
                    <td>{{ $row->standing_water  }}</td>
                    <td>{{ $row->debris  }}</td>
                    <td>{{ $row->equipment_obstruction  }}</td>
                    <td>{{ $row->vegetation  }}</td>
                    <td>{{ $row->highwall  }}</td>
                    <td>{{ $row->lighting_issue  }}</td>
                    <td>{{ $row->burried_base  }}</td>
                    <td>{{ $row->ogl  }}</td>
                    <td>{{ $row->piles_covered_with_tarpolin  }}</td>
                    <td>{{ $row->comments  }}</td>
                    <td>@if(!empty($row->image_one))
                            @if(file_exists(base_path().'/public/date_metron/'.$row->image_one))  
                                <img src="{{ $row->image_one_url }}"  width="100" height="100" >
                            @else
                                NA
                            @endif
                        @endif
                    </td>
                    <td>@if(!empty($row->image_two))
                            @if(file_exists(base_path().'/public/date_metron/'.$row->image_two))  
                                <img src="{{ $row->image_two_url }}" width="100" height="100" >
                            @else
                                NA
                            @endif
                        @endif
                    </td>
                    <td>@if(!empty($row->three_dmodel))
                        <a href="{{ $row->three_dmodel }}" target="_new">Link to 3d Model</a>
                        @else
                            NA
                        @endif
                    </td>
                    <td>
                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="{{url('edit_date_metron/'.$row->id)}}" title="Edit Date Metron" >
                            <i class="fa fa-edit"></i>
                        </a>
                    </td>
                </tr>
              <?php $i++; ?>  
            @endforeach                
            </tbody>
        </table>
        <!--end: Datatable -->
    </div>
</div>
</div>
<!--End::Section-->
<!--End::Dashboard 8--> 
</div>
    <!-- end:: Content -->
@endsection
@push('scripts')
<script src="{{ asset('public/datatables/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/datatables/assets/js/demo8/pages/crud/datatables/basic/paginations.js') }}" type="text/javascript"></script>
<!--end::Page Scripts -->
@endpush
