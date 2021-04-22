@extends('layouts.app')
@section('title', 'Welcome to Dashboard')
@push('styles')
<link href="{{ asset('datatables/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style type="text/css">
.dash_img {
  display: block;
  max-width:500px;
  max-height:200px;
  width: auto;
  height: auto;
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
        <div class="kt-portlet">
            <div class="kt-portlet__body  kt-portlet__body--fit">
                <div class="row row-col-separator-xl">
                    <div class="col-md-12 col-lg-12 col-xl-12"><br><br>
                    <center><h3>Information</h3><br>
                    <h4>You are not authorized to view this page.<br><br>
                    Please contact: info@datesmetron.com </h4><br><br><br>
                </center>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- end:: Content -->
@endsection
@push('scripts')
<!--begin::Page Vendors(used by this page) -->
<script src="{{ asset('assets/js/demo8/pages/dashboard.js') }}" type="text/javascript"></script>
<script src="{{ asset('datatables/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('datatables/assets/js/demo8/pages/crud/datatables/basic/paginations.js') }}" type="text/javascript"></script>

<script>
    function open_modal(id)
    {
        $("#date_metron_id").val(id);
        $("#exampleModal").show();
        $("#exampleModal").removeClass('fade');
    } 
    function close_modal(id)
    {
        $("#exampleModal").hide();
        $("#exampleModal").addClass('fade');
    }    
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
function load_images(id)
{
    if(id)
    {
        $.blockUI({ message: "<i class='fa fa-2x fa-spinner fa-spin' aria-hidden='true' ></i> &nbsp; <h6>Loading... a moment please.</h6>" });         
        $.ajax({
            url: "{{ url('load_images') }}",
            type: 'GET',
            data: {id:id},            
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:function(data){
                var datas = $.parseJSON(data);
                if(datas)
                {
                    $('#image_one').attr('src',datas.image_one);
                    $('#image_two').attr('src',datas.image_two);
                }
            }
        });
        $.unblockUI();
    }
}
</script>
@endpush

