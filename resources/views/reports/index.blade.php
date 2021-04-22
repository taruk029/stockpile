@extends('layouts.app')
@section('title', ' Synopsis Report')
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
                                            Synopsis Report      
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
    <form class="kt-form kt-form--label-right" method="post" action="{{url('synopsis')}}">
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
               Synopsis Report
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
                    <th>Trainer</th>
                    <th>Region</th>
                    <th>Distributor Code</th>
                    <th>Distributor Name</th>
                    <th>DBSR Code</th>
                    <th>DBSR Name</th>
                    <th>Range Compliance Score</th>
                    <th>MSL Score</th>
                    <th>No of outlets in the beat</th>
                    <th>No of Outlets Covered</th>
                    <th>No of Productive outlets</th>
                    <th>No of Unproductive Outlets</th>
                    <th>Reasons for unproductivity</th>
                </tr>
            </thead>

            <tbody>
                <?php $i = 1; ?>
                @foreach($plans as $row )
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ Carbon\Carbon::parse($row->date)->format('d-m-y') }}</td>
                    <td>{{ $row->trainer_name  }}</td>
                    <td>{{ $row->region_name  }}</td>
                    <td>{{ $row->distributor_code  }}</td>
                    <td>{{ $row->distributor_name  }}</td>
                    <td>{{ $row->dbsr_code  }}</td>
                    <td>{{ $row->dbsr_name  }}</td>
                    <td>
                        <input type="text" maxlength="2" placeholder="0-99%" data-id="{{$row->plan_id}}" id ="range_com{{$row->plan_id}}" onkeypress="return NumbersOnly(event,this)" onblur="javascript:save_range(this,1)" style=" height: 41px; width: 100px; padding: 10px;" value="{{ $row->range_compliance?$row->range_compliance:''}}">
                        <?php /* @if($row->range_compliance!="")
                            {{$row->range_compliance}}
                        @else
                            
                        @endif*/ ?>
                    </td>
                    <td>
                        <input type="text" maxlength="2" placeholder="0-99%" data-id="{{$row->plan_id}}" id ="msl{{$row->plan_id}}" onkeypress="return NumbersOnly(event,this)" onblur="javascript:save_range(this,2)" style=" height: 41px; width: 100px; padding: 10px;" value="{{ $row->msl?$row->msl:''}}">
                        <?php /* @if($row->range_compliance!="")
                            {{$row->range_compliance}}
                        @else
                            
                        @endif*/ ?>
                    </td>
                    <td>
                        {{ App\Helpers\Helper::get_total_beats($row->plan_id)?App\Helpers\Helper::get_total_beats($row->plan_id):"NA" }}</td>
                    <td>
                        {{ App\Helpers\Helper::get_covered_outlets($row->plan_id)?App\Helpers\Helper::get_covered_outlets($row->plan_id):"NA" }}
                    </td>
                    <td>
                        {{ App\Helpers\Helper::get_productive_outlets($row->plan_id)?App\Helpers\Helper::get_productive_outlets($row->plan_id):"NA" }}
                    </td>
                    <td>
                        {{ App\Helpers\Helper::get_unproductive_outlets($row->plan_id)?App\Helpers\Helper::get_unproductive_outlets($row->plan_id):"NA" }}
                    </td>
                    <td>
                        <?php echo App\Helpers\Helper::get_unproductive_reason($row->plan_id)?App\Helpers\Helper::get_unproductive_reason($row->plan_id):"NA" ?>
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
<script src="{{ asset('datatables/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('datatables/assets/js/demo8/pages/crud/datatables/basic/paginations.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/demo8/pages/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/dataTables.buttons.min.js" type="text/javascript"></script>
<!--end::Page Scripts -->
@endpush

<script type="text/javascript">
    
    function save_range(id,type)
    {
        var compliance = id.value;
        var data_id = $(id).attr('data-id');
        /*$("#check"+data_id).css("display", "none");*/
        var datas = {compliance:compliance , plan_id:data_id, type:type};
        if(compliance!="")
        {
            $.blockUI({ message: "<i class='fa fa-2x fa-spinner fa-spin' aria-hidden='true' ></i> &nbsp; <h6>Loading... a moment please.</h6>" });
            $.ajax({
                url: "{{ url('save_range_compliance') }}",
                type: 'GET',
                data: datas,            
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success:function(data){
                    if(data==0)
                    {
                        alert("Something went wrong, please try again.");
                    }
                    /*else
                    {
                        $("#range_com"+data_id).css("display", "block");
                    }*/
                    $.unblockUI();
                }
            });
        }
    }


</script>

<script type="text/javascript">
    
    function s_excel()
    {
        var date = "";
        date = $("#kt_datepicker_1").val();
        window.open("{{ url('synopsis_excel/?date=') }}"+date);
    }


</script>
