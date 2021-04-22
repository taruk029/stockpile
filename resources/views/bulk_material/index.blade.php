@extends('layouts.app')
@section('title', 'Bulk Material') 
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
            Dashboard
        </h3>

        <span class="kt-subheader__separator kt-hidden"></span>
        <div class="kt-subheader__breadcrumbs">
            <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a href="#" class="kt-subheader__breadcrumbs-link">
                Bulk Material List                    
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
                Bulk Material List
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
    <div class="kt-portlet__head-actions">
       &nbsp;
       @if($role==2)
        <a href="{{ url('add_bulk_material')}}" class="btn btn-brand btn-elevate btn-icon-sm">
            <i class="la la-plus"></i>
            Add New Bulk Material
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
                    <th>Bulk Material Id</th>
                    <th>Bulk Material</th>
                    <th>Bulk Material Code</th>
                    @if($role==2)
                        <th>Actions</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                <?php $i = 1; ?>
                @foreach($bulk_material as $row )
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $row->id  }}</td>
                    <td>{{ $row->material_name  }}</td>
                    <td>{{ $row->material_code  }}</td>
                    @if($role==2)
                    <td>
                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="{{url('edit_bulk_material/'.$row->id)}}" title="Edit Bulk Material" >
                            <i class="fa fa-edit"></i>
                        </a>
                        @if($row->is_active==1)
                        <a href="{{url('change_bulk_material_status/'.$row->id.'/0')}}" title="Deactivate Bulk Material" onclick="return confirm('This bulk material will be deactivated. Are you sure to proceed?');">
                                <i class="fa fa-times"></i>
                              </a>
                        @else
                        <a href="{{url('change_bulk_material_status/'.$row->id.'/1')}}" title="Activate Bulk Material">
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
