@extends('layouts.app')
@section('title', 'Companies')
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
                Client
            </h3>

            <span class="kt-subheader__separator kt-hidden"></span>
            <div class="kt-subheader__breadcrumbs">
                <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="#" class="kt-subheader__breadcrumbs-link">
                    Add Client                    
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
                       Add New Client
                    </h3>
                </div>
            </div>
            <!--begin::Form-->
                <form class="kt-form kt-form--label-right" method="post" action="{{url('add_client')}}">
                     {{ csrf_field() }}
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
                        <label for="example-text-input" class="col-2 col-form-label">Company Name <span style="color:red">*</span></label>
                        <div class="col-10">
                            <input class="form-control" type="text" name="company_name" value="{{ old('company_name') }}" id="company_name" placeholder="Company Name" required maxlength="191">
                            <span class="form-text" style="color:red">
                                @if ($errors->has('company_name'))
                                    <strong>{{ $errors->first('company_name') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>                    
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Address <span style="color:red">*</span></label>
                        <div class="col-10">
                            <input class="form-control" type="text" name="address" value="{{ old('address') }}" id="address" placeholder="Address"  maxlength="200">
                             <span class="form-text text-muted">
                                @if ($errors->has('address'))
                                    <strong>{{ $errors->first('address') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">State <span style="color:red">*</span></label>
                        <div class="col-10">
                            <input class="form-control" type="text" name="state" value="{{ old('state') }}" id="state" placeholder="State"  maxlength="50">
                             <span class="form-text text-muted">
                                @if ($errors->has('state'))
                                    <strong>{{ $errors->first('state') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Zip Code<span style="color:red">*</span></label>
                        <div class="col-10">
                            <input class="form-control" type="text" name="zip" value="{{ old('zip') }}" id="zip" placeholder="Zip Code" required onkeypress="return NumbersOnly(event,this)"  maxlength="6">
                             <span class="form-text" style="color:red">
                                @if ($errors->has('zip'))
                                    <strong>{{ $errors->first('zip') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Country <span style="color:red">*</span></label>
                        <div class="col-10">
                            <select class="form-control" id="country" name="country">
                                <option value="" >Select Country</option>
                                @foreach($country as $row)
                                        <option value="{{ $row->id }}" >{{ $row->name }}</option>
                                @endforeach
                            </select>
                             <span class="form-text text-muted">
                                @if ($errors->has('country'))
                                    <strong>{{ $errors->first('country') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Email <span style="color:red">*</span></label>
                        <div class="col-10">
                            <input class="form-control" type="email" name="email" value="{{ old('email') }}" id="email" placeholder="Company Email" required  maxlength="191">
                             <span class="form-text" style="color:red">
                                @if ($errors->has('email'))
                                    <strong>{{ $errors->first('email') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>                    
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Password <span style="color:red">*</span></label>
                        <div class="col-10">
                            <input class="form-control" type="password" name="password" value="" id="password" placeholder="Password" required maxlength="100">
                             <span class="form-text" style="color:red">
                                @if ($errors->has('password'))
                                    <strong>{{ $errors->first('password') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Phone Number <span style="color:red">*</span></label>
                        <div class="col-10">
                            <input class="form-control" type="text" name="phone" value="{{ old('phone') }}" id="phone" placeholder="Mobile" onkeypress="return NumbersOnly(event,this)" maxlength="10" required>
                             <span class="form-text" style="color:red">
                                @if ($errors->has('phone'))
                                    <strong>{{ $errors->first('phone') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Expiry Date <span style="color:red">*</span></label>
                        <div class="col-10">
                            <input class="form-control datepicker" type="text" name="expiry_date" value="{{ old('expiry_date') }}" id="kt_datepicker_1" placeholder="Expiry Date" onkeypress="return NumbersOnly(event,this)" maxlength="10" required autocomplete="off">
                             <span class="form-text" style="color:red">
                                @if ($errors->has('expiry_date'))
                                    <strong>{{ $errors->first('expiry_date') }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Active Status</label>
                        <div class="col-10">
                            <div class="kt-radio-inline">
                                <label class="kt-radio">
                                    <input type="radio" checked="checked" name="is_active" value="1"> Active
                                    <span></span>
                                </label>
                                <label class="kt-radio">
                                    <input type="radio" name="is_active" value="0"> Inactive
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
<!--end::Page Scripts -->
@endpush