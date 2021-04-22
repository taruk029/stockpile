@extends('layouts.app')
@section('title', 'Dates Metron')
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
                Dates Metron
            </h3>

            <span class="kt-subheader__separator kt-hidden"></span>
            <div class="kt-subheader__breadcrumbs">
                <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="#" class="kt-subheader__breadcrumbs-link">
                    Edit Dates Metron                    
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
                       Edit Dates Metron
                    </h3>
                </div>
            </div>
            <!--begin::Form-->
                <form class="kt-form kt-form--label-right" id="my_form" method="post" action="{{url('update_date_metron')}}"  enctype="multipart/form-data" >
                     {{ csrf_field() }}
              <input type="hidden" name="date_metron_id" value="{{ $date_metron['id'] }}">
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
                                        <option <?php if($date_metron['location_id']==$row->id) echo "selected"; ?> value="{{ $row->id }}" >{{ $row->name }}</option>
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
                            <select class="form-control" id="site" name="site" required="required" onchange="javascript:piles()">
                                <option value="" >Select Site</option>
                                @foreach($site as $row)
                                <option <?php if($date_metron['site_id']==$row->id) echo "selected"; ?> value="{{ $row->id }}" >{{ $row->name }}</option>   
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
                        <label for="example-text-input" class="col-2 col-form-label">Pile <span style="color:red">*</span></label>
                        <div class="col-10">
                            <select class="form-control" id="pile" name="pile" required="required">
                                <option value="" >Select Pile</option>
                                @foreach($piles as $row)
                                        <option <?php if($date_metron['pile_id']==$row->id) echo "selected"; ?> value="{{ $row->id }}" >{{ $row->pile_name }} - {{ $row->pile_reference_id }}</option>
                                @endforeach
                            </select>
                             <span class="form-text text-muted">
                                @if ($errors->has('pile'))
                                    <strong>{{ $errors->first('pile') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Pile Type</label>
                        <div class="col-10">
                            <div class="kt-radio-inline">
                                <label class="kt-radio">
                                    <input type="radio" name="pile_type" <?php if($date_metron['pile_type']=='indoor') echo "checked"; ?> value="indoor"> Indoor
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input type="radio" name="pile_type" <?php if($date_metron['pile_type']=='outdoor') echo "checked"; ?> value="outdoor"> Outdoor
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Date of Survey <span style="color:red">*</span></label>
                        <div class="col-2">
                            <input class="form-control datepicker" type="text" name="date" style="width: 162px;" value="<?php  $exd = date_create($date_metron['date_of_survey']); echo date_format($exd,'m/d/Y'); ?>" id="kt_datepicker_1" placeholder="Date" required autocomplete="off">
                             <span class="form-text" style="color:red">
                                @if ($errors->has('date'))
                                    <strong>{{ $errors->first('date') }}</strong>
                                @endif
                            </span>
                        </div>

                        <label for="example-text-input" class="col-1 col-form-label">Start Time <span style="color:red">*</span></label>
                        <div class="col-3">
                            <input class="form-control" id="kt_timepicker_1" name="start_time" required readonly="" placeholder="Select time" type="text" autocomplete="off" value="<?php  echo $date_metron['start_time']; ?>">
                             <span class="form-text" style="color:red">
                                @if ($errors->has('start_time'))
                                    <strong>{{ $errors->first('start_time') }}</strong>
                                @endif
                            </span>
                        </div>
                        <label for="example-text-input" class="col-1 col-form-label">End Time <span style="color:red">*</span></label>
                        <div class="col-3">
                            <input class="form-control" id="kt_timepicker_1" name="end_time" required readonly="" placeholder="Select time" type="text" autocomplete="off" value="<?php  echo $date_metron['end_time']; ?>">
                             <span class="form-text" style="color:red">
                                @if ($errors->has('end_time'))
                                    <strong>{{ $errors->first('end_time') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Method <span style="color:red">*</span></label>
                        <div class="col-10">
                            <select class="form-control" id="method" name="method" required="required">
                                <option value="">Select Method</option>
                                <option <?php if($date_metron['method']=="drone") echo "selected"; ?> value="drone">Drone</option>
                                <option <?php if($date_metron['method']=="dgps") echo "selected"; ?> value="dgps">DGPS</option>
                                <option <?php if($date_metron['method']=="total_station") echo "selected"; ?> value="total_station">Total Station</option>
                                <option <?php if($date_metron['method']=="lidar") echo "selected"; ?> value="lidar">Lidar</option>
                                <option <?php if($date_metron['method']=="mobile_phone") echo "selected"; ?> value="mobile_phone ">Mobile Phone </option>
                            </select>
                            <span class="form-text" style="color:red">
                                @if ($errors->has('method'))
                                    <strong>{{ $errors->first('method') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Volume</label>
                        <div class="col-10">
                            <input class="form-control" id="volume" placeholder="Volume" type="text" name="volume" onkeypress="return NumbersOnly(event,this)" maxlength="10"  value="{{ $date_metron['volume'] }}">
                             <span class="form-text" style="color:red">
                                @if ($errors->has('volume'))
                                    <strong>{{ $errors->first('volume') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-2 col-form-label">Toe Confidence</label>
                        <div class="col-10">
                            <div class="kt-radio-inline">
                                <label class="kt-radio">
                                    <input type="radio" name="toe_confidence" checked="checked" <?php if($date_metron['toe_confidence']=='low') echo "checked"; ?> value="low"> Low
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input type="radio" name="toe_confidence" <?php if($date_metron['toe_confidence']=='medium') echo "checked"; ?> value="medium"> Medium
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input type="radio" name="toe_confidence" <?php if($date_metron['toe_confidence']=='high') echo "checked"; ?> value="high"> High
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Surface Confidence</label>
                        <div class="col-10">
                            <div class="kt-radio-inline">
                                <label class="kt-radio">
                                    <input type="radio" name="surface_confidence" <?php if($date_metron['surface_confidence']=='low') echo "checked"; ?> checked="checked"  value="low"> Low
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input type="radio" name="surface_confidence" <?php if($date_metron['surface_confidence']=='medium') echo "checked"; ?> value="medium"> Medium
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input type="radio" name="surface_confidence" <?php if($date_metron['surface_confidence']=='high') echo "checked"; ?> value="high"> High
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Combined Piles</label>
                        <div class="col-10">
                            <div class="kt-radio-inline">
                                <label class="kt-radio">
                                    <input type="radio" name="combined_piles" <?php if($date_metron['combined_piles']=='yes') echo "checked"; ?>  value="yes"> Yes
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input type="radio" name="combined_piles" <?php if($date_metron['combined_piles']=='no') echo "checked"; ?> value="no"> No
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Standing Water</label>
                        <div class="col-10">
                            <div class="kt-radio-inline">
                                <label class="kt-radio">
                                    <input type="radio" name="standing_water" <?php if($date_metron['standing_water']=='yes') echo "checked"; ?>  value="yes"> Yes
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input type="radio" name="standing_water" <?php if($date_metron['standing_water']=='no') echo "checked"; ?> value="no"> No
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Debris</label>
                        <div class="col-10">
                            <div class="kt-radio-inline">
                                <label class="kt-radio">
                                    <input type="radio" name="debris" <?php if($date_metron['debris']=='yes') echo "checked"; ?> value="yes"> Yes
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input type="radio" name="debris" <?php if($date_metron['debris']=='no') echo "checked"; ?> value="no"> No
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Equipment Obstruction</label>
                        <div class="col-10">
                            <div class="kt-radio-inline">
                                <label class="kt-radio">
                                    <input type="radio" name="equipment_obstruction" <?php if($date_metron['equipment_obstruction']=='yes') echo "checked"; ?> value="yes"> Yes
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input type="radio" name="equipment_obstruction" <?php if($date_metron['equipment_obstruction']=='no') echo "checked"; ?> value="no"> No
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Vegetation</label>
                        <div class="col-10">
                            <div class="kt-radio-inline">
                                <label class="kt-radio">
                                    <input type="radio" name="vegetation" <?php if($date_metron['vegetation']=='yes') echo "checked"; ?> value="yes"> Yes
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input type="radio" name="vegetation" <?php if($date_metron['vegetation']=='no') echo "checked"; ?> value="no"> No
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Highwall</label>
                        <div class="col-10">
                            <div class="kt-radio-inline">
                                <label class="kt-radio">
                                    <input type="radio" name="highwall" <?php if($date_metron['highwall']=='yes') echo "checked"; ?> value="yes"> Yes
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input type="radio" name="highwall" <?php if($date_metron['highwall']=='no') echo "checked"; ?> value="no"> No
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Lighting Issue</label>
                        <div class="col-10">
                            <div class="kt-radio-inline">
                                <label class="kt-radio">
                                    <input type="radio" name="lighting_issue" <?php if($date_metron['lighting_issue']=='yes') echo "checked"; ?> value="yes"> Yes
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input type="radio" name="lighting_issue" <?php if($date_metron['lighting_issue']=='no') echo "checked"; ?> value="no"> No
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Burried Base</label>
                        <div class="col-10">
                            <div class="kt-radio-inline">
                                <label class="kt-radio">
                                    <input type="radio" name="burried_base" <?php if($date_metron['burried_base']=='yes') echo "checked"; ?> value="yes"> Yes
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input type="radio" name="burried_base" <?php if($date_metron['burried_base']=='no') echo "checked"; ?> value="no"> No
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">OGL</label>
                        <div class="col-10">
                            <div class="kt-radio-inline">
                                <label class="kt-radio">
                                    <input type="radio" name="ogl" <?php if($date_metron['ogl']=='yes') echo "checked"; ?> value="yes"> Yes
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input type="radio" name="ogl" <?php if($date_metron['ogl']=='no') echo "checked"; ?> value="no"> No
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Piles covered with Tarpolin</label>
                        <div class="col-10">
                            <div class="kt-radio-inline">
                                <label class="kt-radio">
                                    <input type="radio" name="piles_covered_with_tarpolin" <?php if($date_metron['piles_covered_with_tarpolin']=='yes') echo "checked"; ?> value="yes"> Yes
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input type="radio" name="piles_covered_with_tarpolin" <?php if($date_metron['piles_covered_with_tarpolin']=='no') echo "checked"; ?> value="no"> No
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Comments </label>
                        <div class="col-10">
                            <textarea class="form-control" rows="3" name="comments" id="comments" placeholder="Comments" >{{ $date_metron['comments'] }}</textarea>
                            <span class="form-text" style="color:red">
                                @if ($errors->has('comments'))
                                    <strong>{{ $errors->first('comments') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Image 1</label>
                        <div class="col-5">
                            <input class="form-control" type="file" name="image_one">
                            <small>Only jpg, jpeg, png are allowed.</small>
                             <span class="form-text" style="color:red">
                                @if ($errors->has('image_one'))
                                    <strong>{{ $errors->first('image_one') }}</strong>
                                @endif
                            </span>
                        </div>
                        <div class="col-5">
                            @if(!empty($date_metron['image_one']))
                                @if(file_exists(base_path().'/public/date_metron/'.$date_metron['image_one']))  
                                    <img src="{{ $date_metron['image_one_url'] }}"  width="50" height="50" >
                                @else
                                    "NA"
                                @endif
                            @endif
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Image 2</label>
                        <div class="col-5">
                            <input class="form-control" type="file" name="image_two">
                            <small>Only jpg, jpeg, png are allowed.</small>
                             <span class="form-text" style="color:red">
                                @if ($errors->has('image_two'))
                                    <strong>{{ $errors->first('image_two') }}</strong>
                                @endif
                            </span>
                        </div>
                        <div class="col-5">
                            @if(!empty($date_metron['image_two']))
                                @if(file_exists(base_path().'/public/date_metron/'.$date_metron['image_two']))  
                                    <img src="{{ $date_metron['image_two_url'] }}" width="50" height="50" >
                                @else
                                    "NA"
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Link to 3d Model</label>
                        <div class="col-10">
                            <input class="form-control" type="text" name="three_dmodel" value="{{ $date_metron['three_dmodel'] }}" id="three_dmodel" placeholder="Link to 3d Model">
                             <span class="form-text" style="color:red">
                                @if ($errors->has('three_dmodel'))
                                    <strong>{{ $errors->first('three_dmodel') }}</strong>
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
<script type="text/javascript">           
$("#my_form").validate({
    rules: {
        three_dmodel: {
            url: true
        },
        image_one: {
            extension: "jpg|jpeg|png"
        },
        image_two: {
            extension: "jpg|jpeg|png"
        },
    },
    messages: {
        three_dmodel: "Please provide the correct link."
    }
});
</script>
@endpush
<script type="text/javascript">
    function sites()
    {
        var location = $("#location").val();
        if(location!="")
        {
            $.blockUI({ message: "<i class='fa fa-2x fa-spinner fa-spin' aria-hidden='true' ></i> &nbsp; <h6>Loading... a moment please.</h6>" });
            $.ajax({
                url: "{{ url('get_staff_sites') }}",
                type: 'GET',
                data: {id:location},            
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success:function(data){
                   
                    data = JSON.parse(data);
                    var count = Object.keys(data).length;
                    var all = '<option value="">Select Site</option>';
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
            var all_site = '<option value="">Select Site</option>';
            $("#site").html(all_site);
            var all_pile = '<option value="">Select Pile</option>';
            $("#pile").html(all_pile);
        }
    }
</script>
<script type="text/javascript">
    function piles()
    {
        var site = $("#site").val();
        if(site!="")
        {
            $.blockUI({ message: "<i class='fa fa-2x fa-spinner fa-spin' aria-hidden='true' ></i> &nbsp; <h6>Loading... a moment please.</h6>" });
            $.ajax({
                url: "{{ url('get_staff_piles') }}",
                type: 'GET',
                data: {id:site},            
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success:function(data){
                   
                    data = JSON.parse(data);
                    var count = Object.keys(data).length;
                    var all = '<option value="">Select Pile</option>';
                    for (var i = 0; i < count; i++) { 
                        if(data[i].id!=9)
                        {
                            all += '<option value="'+ data[i].id +'">'+ data[i].name +'</option>'; 
                        }
                    }
                   
                    $("#pile").html(all);
                    $.unblockUI();
                }
            });
        }
        else
        {
            var all_pile = '<option value="">Select Pile</option>';
            $("#pile").html(all_pile);
        }
    }
</script>
