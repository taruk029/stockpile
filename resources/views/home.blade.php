@extends('layouts.app')
@section('title', 'Dashboard')
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
        <!--Begin::Dashboard 8-->

<!--Begin::Section-->
<div class="row">
    <div class="kt-portlet">
    <div class="kt-portlet__body  kt-portlet__body--fit">
        <div class="row row-no-padding row-col-separator-xl">
            
            <div class="col-md-12 col-lg-4 col-xl-4">
                <!--begin::Total Profit-->
                <div class="kt-widget24">
                    <div class="kt-widget24__details">
                        <div class="kt-widget24__info">
                            <h4 class="kt-widget24__title">
                                Functional Skills
                            </h4>
                            @foreach($functional_skills as $frow)
                            <span class="kt-widget24__desc">
                                {{ $frow->skill}}
                            </span><br>
                            @endforeach
                        </div>
                        <span class="kt-widget24__stats kt-font-brand">
                            {{ $fn_user }}
                        </span>  
                    </div> 

                    <div class="progress progress--sm">
                        <div class="progress-bar kt-bg-brand" role="progressbar" style="width:  {{ $fn_user }}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <div class="kt-widget24__action">
                        <span class="kt-widget24__change">
                             <!-- Change -->
                        </span>
                        <span class="kt-widget24__number">
                             {{ $fn_user }}%
                        </span>
                    </div> 
                    <a href="javascript:void(0)" title="Get Report" class="btn btn-brand btn-sm">
                        <i class="fa fa-file-alt"></i> Get Report</a>                              
                </div>
                <!--end::Total Profit-->
            </div>

            <div class="col-md-12 col-lg-4 col-xl-4">
                <!--begin::New Feedbacks-->
                <div class="kt-widget24">
                    <div class="kt-widget24__details">
                        <div class="kt-widget24__info">
                            <h4 class="kt-widget24__title">
                                Software Skills
                            </h4>
                           @foreach($software_skills as $softrow)
                            <span class="kt-widget24__desc">
                                {{ $softrow->skill}}
                            </span><br>
                            @endforeach
                        </div>

                        <span class="kt-widget24__stats kt-font-warning">
                           {{ $software_user }}%
                        </span>  
                    </div> 

                    <div class="progress progress--sm">
                        <div class="progress-bar kt-bg-warning" role="progressbar" style="width: {{ $software_user }}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <div class="kt-widget24__action">
                        <span class="kt-widget24__change">
                            <!-- Change -->
                        </span>
                        <span class="kt-widget24__number">
                            {{ $software_user }}%
                        </span>
                    </div>  
                    <a href="javascript:void(0)" title="Get Report" class="btn btn-warning  btn-sm">
                        <i class="fa fa-file-alt"></i> Get Report</a>                               
                </div>              
                <!--end::New Feedbacks--> 
            </div>

            <div class="col-md-12 col-lg-4 col-xl-4">
                <!--begin::New Orders-->
                <div class="kt-widget24">
                    <div class="kt-widget24__details">
                        <div class="kt-widget24__info">
                            <h4 class="kt-widget24__title">
                                Soft Skills
                            </h4>
                            @foreach($soft_skills as $srow)
                            <span class="kt-widget24__desc">
                                {{ $srow->skill}}
                            </span><br>
                            @endforeach
                        </div>

                        <span class="kt-widget24__stats kt-font-danger">
                           {{ $soft_user }}
                        </span>  
                    </div> 

                    <div class="progress progress--sm">
                        <div class="progress-bar kt-bg-danger" role="progressbar" style="width: {{ $soft_user }}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <div class="kt-widget24__action">
                        <span class="kt-widget24__change">
                           <!--  Change -->
                        </span>
                        <span class="kt-widget24__number">
                            {{ $soft_user }}%
                        </span>
                    </div> 
                    <a href="javascript:void(0)" title="Get Report" class="btn btn-danger  btn-sm">
                        <i class="fa fa-file-alt"></i> Get Report</a>                                
                </div>              
                <!--end::New Orders--> 
            </div>
        </div>
    </div>
</div>
</div>
<!--End::Section-->


<!--End::Dashboard 8--> </div>
    <!-- end:: Content -->
@endsection
@push('scripts')
<!--begin::Page Vendors(used by this page) -->
<script src="{{ asset('assets/vendors/custom/fullcalendar/fullcalendar.bundle.js') }}" type="text/javascript"></script>
<script src="http://maps.google.com/maps/api/js?key=AIzaSyBTGnKT7dt597vo9QgeQ7BFhvSRP4eiMSM" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/custom/gmaps/gmaps.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/demo8/pages/dashboard.js') }}" type="text/javascript"></script>
<!--end::Page Scripts -->
@endpush

