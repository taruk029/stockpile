@extends('layouts.app')
@section('title', 'Piles')
@push('styles')
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
                Piles
            </h3>

            <span class="kt-subheader__separator kt-hidden"></span>
            <div class="kt-subheader__breadcrumbs">
                <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="#" class="kt-subheader__breadcrumbs-link">
                    Edit Pile                    
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
                       Edit Pile
                    </h3>
                </div>
            </div>
            <!--begin::Form-->
                <form class="kt-form kt-form--label-right" method="post" action="{{url('update_pile')}}">
                     {{ csrf_field() }}
                      <input type="hidden" name="pile_id" value="{{ $pile['id'] }}">
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
                            <select class="form-control" id="location" name="location" required="required">
                                <option value="" >Select Location</option>
                                @foreach($location as $row)
                                        <option <?php if($pile['location_id']==$row->id) echo "selected"; ?> value="{{ $row->id }}" >{{ $row->name }}</option>
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
                                @foreach($site as $row)
                                <option <?php if($pile['site_id']==$row->id) echo "selected"; ?> value="{{ $row->id }}" >{{ $row->name }}</option>   
                                @endforeach                             
                            </select>
                             <span class="form-text text-muted">
                                @if ($errors->has('site'))
                                    <strong>{{ $errors->first('site') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Pile Name <span style="color:red">*</span></label>
                        <div class="col-10">
                            <input class="form-control" type="text" name="pile_name" value="{{ $pile['pile_name'] }}" id="pile_name" placeholder="Pile Name" required maxlength="191">
                            <span class="form-text" style="color:red">
                                @if ($errors->has('pile_name'))
                                    <strong>{{ $errors->first('pile_name') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Pile Type</label>
                        <div class="col-10">
                            <div class="kt-radio-inline">
                                <label class="kt-radio">
                                    <input type="radio" name="pile_type" checked="checked" <?php if($pile['pile_type']=='static') echo "checked"; ?> value="static"> Static
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input type="radio" name="pile_type" <?php if($pile['pile_type']=='dynamic') echo "checked"; ?> value="dynamic"> Dynamic
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">ERP Code </label>
                        <div class="col-10">
                            <input class="form-control" type="text" name="erp_code" value="{{ $pile['erp_code'] }}" id="erp_code" placeholder="ERP Code" required maxlength="191">
                            <span class="form-text" style="color:red">
                                @if ($errors->has('erp_code'))
                                    <strong>{{ $errors->first('erp_code') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Type of Bulk Material <span style="color:red">*</span></label>
                        <div class="col-10">
                            <select class="form-control" id="type_of_bulk" name="type_of_bulk" required="required">
                                <option value="" >Select Bulk Material</option>
                                @foreach($bulk_material as $row)
                                        <option value="{{ $row->id }}" <?php if($pile['type_of_bulk']==$row->id) echo "selected"; ?> >{{ $row->material_name }} - {{ $row->material_code }}</option>
                                @endforeach
                            </select>
                            <span class="form-text" style="color:red">
                                @if ($errors->has('type_of_bulk'))
                                    <strong>{{ $errors->first('type_of_bulk') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Additional Site Info </label>
                        <div class="col-10">
                            <textarea class="form-control" id="exampleTextarea" rows="3" name="additional_info" id="additional_info" placeholder="Additional Site Info" >{{ $pile['additional_info'] }}</textarea>
                            <span class="form-text" style="color:red">
                                @if ($errors->has('additional_info'))
                                    <strong>{{ $errors->first('additional_info') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Date <span style="color:red">*</span></label>
                        <div class="col-4">
                            <input class="form-control datepicker" type="text" name="date" value="<?php  $exd = date_create($pile['date']); echo date_format($exd,'m/d/Y');?>" id="kt_datepicker_1" placeholder="Date" required autocomplete="off">
                             <span class="form-text" style="color:red">
                                @if ($errors->has('date'))
                                    <strong>{{ $errors->first('date') }}</strong>
                                @endif
                            </span>
                        </div>
                        <label for="example-text-input" class="col-2 col-form-label">Time <span style="color:red">*</span></label>
                        <div class="col-4">
                            <input class="form-control" id="kt_timepicker_1" name="time" readonly="" placeholder="Select time" type="text" autocomplete="off" value="<?php  $exd = date_create($pile['time']); echo date_format($exd,'h:i:s'); ?>">
                             <span class="form-text" style="color:red">
                                @if ($errors->has('time'))
                                    <strong>{{ $errors->first('time') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Value per ton</label>
                        <div class="col-10">
                            <input class="form-control" id="value_per_ton" placeholder="Value per ton" type="text" name="value_per_ton" onkeypress="return isDecimal(event,this)" maxlength="10"  value="{{ $pile['value_per_ton'] }}">
                             <span class="form-text" style="color:red">
                                @if ($errors->has('value_per_ton'))
                                    <strong>{{ $errors->first('value_per_ton') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Opening Balance ERP</label>
                        <div class="col-10">
                            <input class="form-control" id="opening_balance_erp" placeholder="Opening Balance ERP" type="text" name="opening_balance_erp" onkeypress="return isDecimal(event,this)" maxlength="10"  value="{{ $pile['opening_balance_erp'] }}">
                             <span class="form-text" style="color:red">
                                @if ($errors->has('opening_balance_erp'))
                                    <strong>{{ $errors->first('opening_balance_erp') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">GRN Pending</label>
                        <div class="col-10">
                            <input class="form-control" id="grn_pending" placeholder="GRN Pending" type="text" name="grn_pending" onkeypress="return isDecimal(event,this)" maxlength="10"  value="{{ $pile['grn_pending'] }}">
                             <span class="form-text" style="color:red">
                                @if ($errors->has('grn_pending'))
                                    <strong>{{ $errors->first('grn_pending') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Consumption Booking Pending</label>
                        <div class="col-10">
                            <input class="form-control" id="consuption_booking_pending" placeholder="Consumption Booking Pending" type="text" name="consuption_booking_pending" onkeypress="return isDecimal(event,this)"  value="{{ $pile['consuption_booking_pending'] }}" maxlength="10">
                             <span class="form-text" style="color:red">
                                @if ($errors->has('consuption_booking_pending'))
                                    <strong>{{ $errors->first('consuption_booking_pending') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Bunker Bin</label>
                        <div class="col-10">
                            <input class="form-control" id="bunker_bin" placeholder="Bunker Bin" type="text" name="bunker_bin" onkeypress="return isDecimal(event,this)" maxlength="10"  value="{{ $pile['bunker_bin'] }}">
                             <span class="form-text" style="color:red">
                                @if ($errors->has('bunker_bin'))
                                    <strong>{{ $errors->first('bunker_bin') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Bulk Density</label>
                        <div class="col-10">
                            <input class="form-control" id="bulk_density" placeholder="Bulk Density" type="text" name="bulk_density" onkeypress="return isDecimal(event,this)" maxlength="10"  value="{{ $pile['bulk_density'] }}">
                             <span class="form-text" style="color:red">
                                @if ($errors->has('bulk_density'))
                                    <strong>{{ $errors->first('bulk_density') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div> 
                     <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Moisture</label>
                        <div class="col-9">
                            <input class="form-control" id="moisture" placeholder="Moisture" type="text" name="moisture" onkeypress="return isDecimal(event,this)" maxlength="10"  value="{{ $pile['moisture'] }}">
                             <span class="form-text" style="color:red">
                                @if ($errors->has('moisture'))
                                    <strong>{{ $errors->first('moisture') }}</strong>
                                @endif
                            </span>
                        </div>
                        <div class="col-1">
                            <strong> %</strong>
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
<script src="{{ asset('public/assets/js/demo8/pages/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/demo8/pages/bootstrap-timepicker.js') }}" type="text/javascript"></script>
@endpush
<script type="text/javascript">
function get_users()
    {
        var user_list = [];
        var kra_value = [];
        $.each($("#user_list option:selected"), function(){            
            user_list.push('<span>' + $(this).text() + '&nbsp;<i class="fa fa-times-circle" onClick="unselectUsers('+$(this).val()+')" title="Remove User" /></span>');
        });
        var list = user_list.join(", ");
        $("#display_users").html(list);
    }
    function unselectUsers(id)
    {
        var wanted_option = $('#user_list option[value="'+ id +'"]');
        wanted_option.prop('selected', false);
        get_users();
    }
</script>