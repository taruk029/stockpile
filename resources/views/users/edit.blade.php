@extends('layouts.app')
@section('title', 'Edit User')
@push('styles')
<link href="{{ asset('datatables/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
<!-- begin:: Subheader -->
                            <div class="kt-subheader   kt-grid__item" id="kt_subheader">
                                <div class="kt-subheader__main">

                                    <h3 class="kt-subheader__title">
                                        Users
                                    </h3>

                                    <span class="kt-subheader__separator kt-hidden"></span>
                                    <div class="kt-subheader__breadcrumbs">
                                        <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                                        <span class="kt-subheader__breadcrumbs-separator"></span>
                                        <a href="#" class="kt-subheader__breadcrumbs-link">
                                            Edit User                    
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
                       Edit User
                    </h3>
                </div>
            </div>
            <!--begin::Form-->
                <form class="kt-form kt-form--label-right" method="post" enctype="multipart/form-data" action="{{url('update_user')}}">
                     {{ csrf_field() }}
                <input class="form-control" type="hidden" name="user_id" value="{{ $user->user_id }}" id="user_id">
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
                        <label for="example-text-input" class="col-2 col-form-label">First Name <span style="color:red">*</span></label>
                        <?php $name = explode(" ", $user->user_name);
                            $first_name = $name[0];
                            $last_name = $name[1];
                            ?>
                        <div class="col-10">
                            <input class="form-control" type="text" name="first_name" value="{{ $first_name }}" id="first_name" placeholder="First Name" required>
                            <span class="form-text" style="color:red">
                                @if ($errors->has('first_name'))
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Last Name <span style="color:red">*</span></label>
                        <div class="col-10">
                            <input class="form-control" type="text" name="last_name" value="{{ $last_name  }}" id="last_name" placeholder="Last Name" required>
                            <span class="form-text" style="color:red">
                                @if ($errors->has('last_name'))
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">User Email <span style="color:red">*</span></label>
                        <div class="col-10">
                            <input class="form-control" type="email" name="email" value="{{ $user->email }}" id="email" placeholder="User Email"  disabled="disabled">
                             <span class="form-text" style="color:red">
                                @if ($errors->has('email'))
                                    <strong>{{ $errors->first('email') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Mobile <span style="color:red">*</span></label>
                        <div class="col-10">
                            <input class="form-control" type="text" name="phone" value="{{ $user->phone }}" id="phone" placeholder="Mobile" onkeypress="return NumbersOnly(event,this)" maxlength="10" disabled="disabled">
                             <span class="form-text" style="color:red">
                                @if ($errors->has('phone'))
                                    <strong>{{ $errors->first('phone') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div> 

                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">User Password <span style="color:red">*</span></label>
                        <div class="col-10">
                            <input class="form-control" type="password" name="password" value="" id="password" placeholder="Password">
                             <span class="form-text" style="color:red">
                                @if ($errors->has('password'))
                                    <strong>{{ $errors->first('password') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">User Rights <span style="color:red">*</span></label>
                        <div class="col-10">
                            <div class="kt-radio-list">
                            <label class="kt-radio kt-radio--solid kt-radio--success">
                                <input type="radio" name="user_type" value="1" required="required" <?php if($user->user_type==1) echo "checked"; ?> > Type 1 <small> ( Add & Update - Sites/ Location/ Piles/ Bulk density / Moisture/ ERP stock / Notes/ Raise query about pile / Export/ View Advanced Report/ View Volume / View 3D/ View Images )</small>
                                <span></span>
                            </label>
                            <label class="kt-radio kt-radio--solid kt-radio--brand">
                                <input type="radio" name="user_type" value="2" required="required" <?php if($user->user_type==2) echo "checked"; ?>> Type 2 <small> ( Add & Update - Sites/ Location/ Piles/ Bulk density / Moisture/ ERP stock / Notes/ Raise query about pile / Export/ View Volume / View 3D/ View Images )</small>
                                <span></span>
                            </label>
                            <label class="kt-radio kt-radio--solid kt-radio--warning">
                                <input type="radio" name="user_type" value="3" required="required" <?php if($user->user_type==3) echo "checked"; ?>> Type 3 <small>( View Volume / View 3D/ View Images )</small>
                                <span></span>
                            </label>
                            <label class="kt-radio kt-radio--solid kt-radio--danger">
                                <input type="radio" name="user_type" value="4" <?php if($user->user_type==4) echo "checked";  ?> required="required"> Type 4 <small>( View Advanced Report/ View Volume / View 3D/ View Images )</small>
                                <span></span>
                            </label>
                        </div>
                        </div>
                    </div> 
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <div class="row">
                            <div class="col-2">
                            </div>
                            <div class="col-10">
                                <button type="submit" class="btn btn-success">Update</button>
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
<script src="{{ asset('datatables/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('datatables/assets/js/demo8/pages/crud/datatables/basic/paginations.js') }}" type="text/javascript"></script>
<!--end::Page Scripts -->
@endpush
<script>
    function get_states(){
        $("#new_country_div").css("display","none");
        $("#state_div").css("display","block");
        var countryid = $("#country").val();
        if(countryid!=32)
        {
            $.blockUI({ message: "<i class='fa fa-2x fa-spinner fa-spin' aria-hidden='true' ></i> &nbsp; <h6>Loading... a moment please.</h6>" });
            $.ajax({
                url: "{{ url('get_states') }}",
                type: 'GET',
                data: {id:countryid},            
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success:function(data){
                   
                    data = JSON.parse(data);
                    var count = Object.keys(data).length;
                    var all = '<option value="">Select State</option>';
                    for (var i = 0; i < count; i++) { 
                        all += '<option value="'+ data[i].id +'">'+ data[i].name +'</option>'; 
                    }
                   
                    $("#state").html(all);
                    $.unblockUI();
                }
            });
        }
        else
        {
            $("#new_country_div").css("display","block");
            $("#state_div").css("display","none");
        }
    }

    function new_department(){
        var departmentid = $("#department").val();

            $.blockUI({ message: "<i class='fa fa-2x fa-spinner fa-spin' aria-hidden='true' ></i> &nbsp; <h6>Loading... a moment please.</h6>" });
            $.ajax({
                url: "{{ url('get_subdepartments') }}",
                type: 'GET',
                data: {id:departmentid},            
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success:function(data){
                   
                    data = JSON.parse(data);
                    var count = Object.keys(data).length;
                    var all = '<option value="">Select Sub-Department</option>';
                    for (var i = 0; i < count; i++) { 
                        if(data[i].id!=10)
                        {
                            all += '<option value="'+ data[i].id +'">'+ data[i].name +'</option>'; 
                        }
                        }
                   
                    $("#subdepartment").html(all);
                    $.unblockUI();
                }
            });
        }

    function kras(){
        var subdepartmentid = $("#subdepartment").val();

            $.blockUI({ message: "<i class='fa fa-2x fa-spinner fa-spin' aria-hidden='true' ></i> &nbsp; <h6>Loading... a moment please.</h6>" });
            $.ajax({
                url: "{{ url('get_department_kra') }}",
                type: 'GET',
                data: {id:subdepartmentid},            
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success:function(data){
                   
                    data = JSON.parse(data);
                    var count = Object.keys(data).length;
                    var all = '';
                    for (var i = 0; i < count; i++) { 
                        if(data[i].id!=10)
                        {
                            all += '<option value="'+ data[i].id +'">'+ data[i].name +'</option>'; 
                        }
                        }
                   
                    $("#kra_list").html(all);
                    $.unblockUI();
                }
            });
        }

    function get_kras()
    {
        var kra_list = [];
        var kra_value = [];
        $.each($("#kra_list option:selected"), function(){            
            kra_list.push('<span>' + $(this).text() + '<i class="fa fa-times-circle" onClick="unselectKRA('+$(this).val()+')" title="Remove KRA" /></span>');
        });
        var list = kra_list.join(", ");
        $("#display_kra").html(list);
    }
    function unselectKRA(id)
    {
        var wanted_option = $('#kra_list option[value="'+ id +'"]');
        wanted_option.prop('selected', false);
        get_kras();
    }

    function get_name()
    {
        $("#display_emp_name").html("");
        var reporting_to = $("#reporting_to").val();
        if(reporting_to!="")
        {
            $.blockUI({ message: "<i class='fa fa-2x fa-spinner fa-spin' aria-hidden='true' ></i> &nbsp; <h6>Loading... a moment please.</h6>" });
            $.ajax({
                url: "{{ url('get_employee_from_code') }}",
                type: 'GET',
                data: {id:reporting_to},            
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success:function(data){

                    if(data!="")
                    {
                        $("#display_emp_name").html(data);
                    }
                    $.unblockUI();
                }
            });
        }
    }

</script>