@extends('layouts.app')
@section('title', 'Piles')
@push('styles')
<style>
    .datepicker{
        width: fit-content !important;
    }
</style>
@endpush
@section('content')
<!-- begin:: Subheader -->
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">

            <h3 class="kt-subheader__title">
                Piles
            </h3>

            <span class="kt-subheader__separator kt-hidden"></span>
            <div class="kt-subheader__breadcrumbs">
                <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="#" class="kt-subheader__breadcrumbs-link">
                    Add    Pile                 
                </a>
                <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
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
     <!--begin::Portlet-->
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                       Add New Pile
                    </h3>
                </div>
            </div>
            <!--begin::Form-->
                <form class="kt-form kt-form--label-right" method="post" action="{{url('add_bulk_pile')}}"  enctype="multipart/form-data">
                     {{ csrf_field() }}
                      <input type="hidden" name="company_id" value="{{$loggedin_user}}">
                <div class="kt-portlet__body">
                    <div class="form-group form-group-last">
                        <div class="alert alert-secondary" role="alert">
                            <div class="alert-icon"><i class="flaticon-alert kt-font-brand"></i></div>
                            <div class="alert-text">
                                Fields marked with <span style="color:red">*</span> are required.
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Location <span style="color:red">*</span></label>
                        <div class="col-10">
                            <select class="form-control" id="location" name="location" required="required" onchange="javascript:sites()">
                                <option value="" >Select Location</option>
                                @foreach($location as $row)
                                        <option value="{{ $row->id }}" >{{ $row->name }}</option>
                                @endforeach
                            </select>
                             <span class="form-text text-muted">
                                @if ($errors->has('location'))
                                    <strong>{{ $errors->first('location') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Sites <span style="color:red">*</span></label>
                        <div class="col-10">
                            <select class="form-control" id="site" name="site" required="required">
                                <option value="" >Select Site</option>                                
                            </select>
                             <span class="form-text text-muted">
                                @if ($errors->has('site'))
                                    <strong>{{ $errors->first('site') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Upload File <span style="color:red">*</span></label>
                        <div class="col-10">
                            <input type="file" class="form-control" name="bulk_excel" required="required" accept=".xlsx,.xls">
                            <small>Only .xlsx and .xls filetypes are allowed</small>
                        </div>
                    </div>         
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <div class="row">
                            <div class="col-2">
                            </div>
                            <div class="col-10">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <button type="reset" class="btn btn-warning">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!--end::Portlet-->
</div>
<!--End::Section-->
<!--End::Dashboard 8--> 
</div>
    <!-- end:: Content -->
@endsection
@push('scripts')
<script src="{{ asset('assets/js/demo8/pages/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/demo8/pages/bootstrap-timepicker.js') }}" type="text/javascript"></script>
@endpush
<script type="text/javascript">
    function sites()
    {
        var location = $("#location").val();
        if(location!="")
        {
            $.blockUI({ message: "<i class='fa fa-2x fa-spinner fa-spin' aria-hidden='true' ></i> &nbsp; <h6>Loading... a moment please.</h6>" });
            $.ajax({
                url: "{{ url('get_sites') }}",
                type: 'GET',
                data: {id:location},            
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success:function(data){
                   
                    data = JSON.parse(data);
                    var count = Object.keys(data).length;
                    var all = '<option value="">Select Sites</option>';
                    for (var i = 0; i < count; i++) { 
                        if(data[i].id!=9)
                        {
                            all += '<option value="'+ data[i].id +'">'+ data[i].name +'</option>'; 
                        }
                    }
                   
                    $("#site").html(all);
                    $.unblockUI();
                }
            });
        }
        else
        {
            $("#site").val("");
        }
    }
</script>