@extends('layouts.app')
@section('title', ' Start Day Report')
@push('styles')
<link href="{{ asset('datatables/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style type="text/css">    
.dataTables_wrapper .dataTable 
{
    font-size: 12px !important;
}  
.kt-portlet--mobile
{
    overflow-x:auto !important;
}
 ol
{
    
padding-inline-start: 0px;
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
                 Start Day Report      
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
                <i class="fa fa-search"></i>
            </span>
            <h3 class="kt-portlet__head-title">
              Search
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
    <div class="kt-portlet__head-actions">       
    </div>  
</div>      
</div>
    </div>
    <form class="kt-form kt-form--label-right" method="post" action="{{url('start_day_report')}}">
    {{ csrf_field() }}
    <div class="kt-portlet__body">
    <div class="col-md-12">    
        <div class="col-md-3">           
            <div class="form-group">
                <label>Date</label>
                <div class='input-group'>
                        <input type="text" class="form-control datepicker" id="kt_datepicker_1" name="date" value="<?php if(isset($_POST['date'])) echo $_POST['date']; ?>"  readonly="" placeholder="Select date">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="la la-calendar-check-o"></i></span>
                        </div>
                    </div>
            </div>        
        </div>
    </div>     
    </div>      
    <div class="kt-portlet__foot">
        <div class="kt-form__actions">
            <div class="row">
                <div class="col-10">
                    <button type="submit" class="btn btn-success">Search</button>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>
</div>
<!--End::Section-->
<!--Begin::Section-->
<div class="row">
     <div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand fa fa-building"></i>
            </span>
            <h3 class="kt-portlet__head-title">
               Start Day Report
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="javascript:void(0)" onclick="javascript:s_excel()" class="btn btn-success">Export To Excel</a>
                </div>  
            </div>  
        </div>
    </div>

    <div class="kt-portlet__body">
        <!--begin: Datatable -->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Date</th>
                    <th>DB Code</th>
                    <th>DB Name</th>
                    <th>DBSR Code</th>
                    <th>DBSR Name</th>
                    <th>Trainer</th>
                    <th>Outlets in the beat</th>
                    <th>Start Range Compliance</th>
                    <th>Meeting Time</th>
                    <th>Town</th>
                    <th>Market</th>
                    <th>Route Code</th>
                    <th>DBSR First Shop</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach($plans as $row )
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ Carbon\Carbon::parse($row->date)->format('d-m-y') }}</td>
                    <td>{{ $row->distributor_code  }}</td>
                    <td>{{ $row->distributor_name  }}</td>
                    <td>{{ $row->dbsr_code  }}</td>
                    <td>{{ $row->dbsr_name  }}</td>
                    <td>{{ $row->trainer_name  }}</td>
                    <td>{{ $row->start_beats  }}</td>
                    <td>{{ $row->start_range_compliance  }}</td>
                    <td>{{ $row->meeting_time  }}</td>
                    <td>{{ $row->town  }}</td>
                    <td>{{ $row->market  }}</td>
                    <td>{{ $row->route_code  }}</td>
                    <td>{{ $row->dbsr_first_shop  }}</td>
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
<script src="{{ asset('datatables/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('datatables/assets/js/demo8/pages/crud/datatables/basic/paginations.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/demo8/pages/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<!--end::Page Scripts -->
@endpush
<script type="text/javascript">
    
    function s_excel()
    {
        var date = "";
        date = $("#kt_datepicker_1").val();
        window.open("{{ url('start_excel/?date=') }}"+date);
    }


</script>
