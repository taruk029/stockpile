@extends('layouts.app')
@section('title', 'Locations')
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
                Locations
            </h3>

            <span class="kt-subheader__separator kt-hidden"></span>
            <div class="kt-subheader__breadcrumbs">
                <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="#" class="kt-subheader__breadcrumbs-link">
                    Edit Location                    
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
                       Edit Location
                    </h3>
                </div>
            </div>
            <!--begin::Form-->
                <form class="kt-form kt-form--label-right" method="post" action="{{url('update_site')}}">
                     {{ csrf_field() }}
                      <input type="hidden" name="site_id" value="{{ $site['id'] }}">
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
                                        <option <?php if($site['location_id']==$row->id) echo "selected"; ?> value="{{ $row->id }}" >{{ $row->name }}</option>
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
                        <label for="example-text-input" class="col-2 col-form-label">Site Name <span style="color:red">*</span></label>
                        <div class="col-10">
                            <input class="form-control" type="text" name="site_name" value="{{ $site['name'] }}" id="site_name" placeholder="Site Name" required maxlength="191">
                            <span class="form-text" style="color:red">
                                @if ($errors->has('site_name'))
                                    <strong>{{ $errors->first('site_name') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>  
                    
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Site Type</label>
                        <div class="col-10">
                            <div class="kt-radio-inline">
                                <label class="kt-radio">
                                    <input type="radio" name="site_type" <?php if($site['site_type']=='indoor') echo "checked"; ?> value="indoor"> Indoor
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input type="radio" name="site_type" <?php if($site['site_type']=='outdoor') echo "checked"; ?> value="outdoor"> Outdoor
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Map Users <span style="color:red">*</span></label>
                        <div class="col-10">
                            <select multiple class="form-control" id="user_list" required="required" name="user_list[]" onclick="javascript:get_users()">
                                @foreach($users as $row)
                                    <option <?php if(in_array($row->user_id, $map_users)) echo "selected"; ?> value="{{ $row->user_id }}" >{{ $row->user_name }} - User Type {{$row->user_type}}</option>
                                @endforeach
                            </select>
                            <small>Press "Ctrl" + Click to select multiple users.</small>
                            <h5 id="display_users" class="kt-font-danger" style="margin-top: 10px;">
                                @if($mapped_users)
                                    @foreach($mapped_users as $val)
                                        <span>{{$val->user_name}} - User Type {{$val->user_type}}&nbsp;<i class="fa fa-times-circle" onclick="unselectUsers({{$val->user_id}})" title="Remove User"></i></span>
                                    @endforeach
                                @endif
                            </h5>
                            <span class="form-text text" style="color:red">
                                @if ($errors->has('user_list'))
                                    <strong>{{ $errors->first('user_list') }}</strong>
                                @endif
                            </span>
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