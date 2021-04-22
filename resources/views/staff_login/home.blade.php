@extends('layouts.app')
@section('title', 'Welcome to Dashboard')
@push('styles')
<link href="{{ asset('datatables/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">

        <h3 class="kt-subheader__title">
            Dashboard
        </h3>

        <!--<span class="kt-subheader__separator kt-hidden"></span>
        <div class="kt-subheader__breadcrumbs">
            <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a href="#" class="kt-subheader__breadcrumbs-link">
                Application                    
            </a>
             <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> 
    </div>-->
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
    <div class="row">
</div>
</div>
    <!-- end:: Content -->
@endsection
@push('scripts')
<!--begin::Page Vendors(used by this page) -->
<script src="{{ asset('assets/vendors/custom/fullcalendar/fullcalendar.bundle.js') }}" type="text/javascript"></script>
<script src="http://maps.google.com/maps/api/js?key=AIzaSyBTGnKT7dt597vo9QgeQ7BFhvSRP4eiMSM" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/custom/gmaps/gmaps.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/demo8/pages/dashboard.js') }}" type="text/javascript"></script>
<script src="{{ asset('datatables/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('datatables/assets/js/demo8/pages/crud/datatables/basic/paginations.js') }}" type="text/javascript"></script>
<script src="{{ asset('datatables/assets/js/demo8/pages/crud/datatables/basic/bootstrap-daterangepicker.js') }}" type="text/javascript"></script>
<!--end::Page Scripts -->
<script>
    var table;
    $(document).ready(function()
    {
        table = $('#kt_table_1').DataTable();
    });
</script>
<script type="text/javascript">
    
    function s_excel()
    {
        var month = "";
        var year = "";
        month = $("#month").val();
        year = $("#year").val();
        var query_str = "month="+month+"&year="+year;
        window.open("{{ url('dashboard_excel/?') }}"+query_str);
    }


</script>
<script type="text/javascript">
    function get_productive_data()
    {
        var month = $("#month").val();
        var year = $("#year").val();
        if(month!="" && year!="")
        {
            $.blockUI({ message: "<i class='fa fa-2x fa-spinner fa-spin' aria-hidden='true' ></i> &nbsp; <h6>Loading... a moment please.</h6>" });         
            $.ajax({
                url: "{{ url('get_dashboard_values') }}",
                type: 'GET',
                data: {month:month, year:year},            
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success:function(data){
                    var datas = $.parseJSON(data);
                    $("#all_outlets").text(datas['all_outlets']);
                    $("#beats").text(datas['beats']);
                    $("#productive_outlets").text(datas['productive_outlets']);
                    $("#unproductive_outlets").text(datas['unproductive_outlets']);
                    $("#dbsr").text(datas['dbsr']);
                    $("#distributor").text(datas['distributor']);
                    $("#man_days").text(datas['man_days']);
                    var per = datas['man_days']/datas['dbsr'];
                    var vals =0;
                    if(!isNaN(per))
                    {                        
                        var vals = Math.round(per * 100) / 100;
                    }
                    $("#average").text(vals);
                }
            });
            $.unblockUI();
        }
        else
        {
            if(month=="")
            {
                alert("Please select month");
                $("#month").focus();
            }
            if(year=="")
            {
                alert("Please select year");
                $("#year").focus();
            }
        }
    }

    function get_trainer_productive_data()
    {
        var month = $("#per_month").val();
        var year = $("#per_year").val();
        if(month!="" && year!="")
        {
            $.blockUI({ message: "<i class='fa fa-2x fa-spinner fa-spin' aria-hidden='true' ></i> &nbsp; <h6>Loading... a moment please.</h6>" });         
            $.ajax({
                url: "{{ url('get_trainer_productive_data') }}",
                type: 'GET',
                data: {month:month, year:year},            
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success:function(data){
                    var datas = $.parseJSON(data);
                    var count = Object.keys(datas).length;
                    var trHTML = '';
                    table.clear().draw();
                    for (var i = 0; i < count; i++) 
                    { 
                        var per = 0;
                        if(datas[i].trainer_productivepro!=0 && datas[i].count_day!=0)
                        {
                            per = (datas[i].trainer_productivepro/datas[i].outlet_count)*100;
                            per = per.toFixed(2)
                        }
                        table.row.add([datas[i].trainer_name, datas[i].trainer_productivepro, datas[i].count_day, datas[i].outlet_count, per+" %"])
                        .draw(false);
                    }   
                }
            });
            $.unblockUI();
        }
        else
        {
            if(month=="")
            {
                alert("Please select month");
                $("#month").focus();
            }
            if(year=="")
            {
                alert("Please select year");
                $("#year").focus();
            }
        }
    }
</script>
@endpush

