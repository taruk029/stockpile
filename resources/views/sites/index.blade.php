@extends('layouts.app') 
@section('title', 'Sites')
@push('styles')
<link href="{{ asset('public/datatables/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">

        <h3 class="kt-subheader__title">
            Dashboard
        </h3>

        <span class="kt-subheader__separator kt-hidden"></span>
        <div class="kt-subheader__breadcrumbs">
            <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a href="#" class="kt-subheader__breadcrumbs-link">
                Sites List                    
            </a>
             
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
     <div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand fa fa-building"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                Sites List
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
    <div class="kt-portlet__head-actions">
       &nbsp;
       @if($role==2)
        <a href="{{ url('add_site')}}" class="btn btn-brand btn-elevate btn-icon-sm">
            <i class="la la-plus"></i>
            Add New Sites
        </a>
        @endif
    </div>  
</div>      </div>
    </div>

    <div class="kt-portlet__body">
        <!--begin: Datatable -->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Sites</th>
                    <th>Site Type</th>
                    <th>Location</th>
                    @if($role==2)
                        <th>Users</th>
                        <th>Actions</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                <?php $i = 1; ?>
                @foreach($sites as $row )
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $row->name  }}</td>                    
                    <td>{{ $row->site_type?$row->site_type:'NA'  }}</td>
                    <td>{{ $row->location_name  }}</td>
                    @if($role==2)
                        <td><?php echo App\Helpers\Helper::get_sites_mapped_user($loggedin_user, $row->id)?App\Helpers\Helper::get_sites_mapped_user($loggedin_user, $row->id):"NA" ?></td>
                        <td>
                            <a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="{{url('edit_site/'.$row->id)}}" title="Edit Site" >
                                <i class="fa fa-edit"></i>
                            </a>
                            @if($row->is_active==1)
                            <a href="{{url('change_site_status/'.$row->id.'/0')}}" title="Deactivate Site" onclick="return confirm('This site will be deactivated. Are you sure to proceed?');">
                                    <i class="fa fa-times"></i>
                                  </a>
                            @else
                            <a href="{{url('change_site_status/'.$row->id.'/1')}}" title="Activate Site">
                                    <i class="fa fa-check"></i>
                                  </a>
                            @endif
                        </td>
                    @endif
                </tr>
              <?php $i++; ?>  
            @endforeach                
            </tbody>
        </table>
        <!--end: Datatable -->
    </div>
</div>
</div>
<!--End::Section-->
<!--End::Dashboard 8--> 
</div>
    <!-- end:: Content -->
@endsection
@push('scripts')
<script src="{{ asset('public/datatables/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/datatables/assets/js/demo8/pages/crud/datatables/basic/paginations.js') }}" type="text/javascript"></script>
<!--end::Page Scripts -->
@endpush
