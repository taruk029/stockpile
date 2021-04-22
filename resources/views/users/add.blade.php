@extends('layouts.app')
@section('title', 'Add Users')
@push('styles')
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
                                            Add User                    
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
                       Add New User
                    </h3>
                </div>
            </div>
            <!--begin::Form-->
                <form class="kt-form kt-form--label-right" method="post" action="{{url('add_user')}}">
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
                        <label for="example-text-input" class="col-2 col-form-label">First Name <span style="color:red">*</span></label>
                        <div class="col-10">
                            <input class="form-control" type="text" name="first_name" value="{{ old('first_name') }}" id="first_name" placeholder="First Name" required>
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
                            <input class="form-control" type="text" name="last_name" value="{{ old('last_name') }}" id="last_name" placeholder="Last Name" required>
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
                            <input class="form-control" type="email" name="email" value="{{ old('email') }}" id="email" placeholder="User Email" required>
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
                            <input class="form-control" type="text" name="phone" value="{{ old('phone') }}" id="phone" placeholder="Mobile" onkeypress="return NumbersOnly(event,this)" maxlength="10" required>
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
                            <input class="form-control" type="password" name="password" value="" id="password" placeholder="Password" required>
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
                                <input type="radio" name="user_type" value="1" required="required"> Type 1 <small> ( Add & Update - Sites/ Location/ Piles/ Bulk density / Moisture/ ERP stock / Notes/ Raise query about pile / Export/ View Advanced Report/ View Volume / View 3D/ View Images )</small>
                                <span></span>
                            </label>
                            <label class="kt-radio kt-radio--solid kt-radio--brand">
                                <input type="radio" name="user_type" value="2" required="required"> Type 2 <small> ( Add & Update - Sites/ Location/ Piles/ Bulk density / Moisture/ ERP stock / Notes/ Raise query about pile / Export/ View Volume / View 3D/ View Images )</small>
                                <span></span>
                            </label>
                            <label class="kt-radio kt-radio--solid kt-radio--warning">
                                <input type="radio" name="user_type" value="3" required="required"> Type 3 <small>( View Volume / View 3D/ View Images )</small>
                                <span></span>
                            </label>
                            <label class="kt-radio kt-radio--solid kt-radio--danger">
                                <input type="radio" name="user_type" value="4" checked required="required"> Type 4 <small>( View Advanced Report/ View Volume / View 3D/ View Images )</small>
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
@endpush
