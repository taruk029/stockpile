@extends('layouts.app')
@section('title', 'Piles')
@push('styles')
<link href="{{ asset('public/datatables/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style type="text/css">
    .kt-portlet--mobile
{
    overflow-x:scroll !important;
}
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
            Dashboard
        </h3>

        <span class="kt-subheader__separator kt-hidden"></span>
        <div class="kt-subheader__breadcrumbs">
            <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a href="#" class="kt-subheader__breadcrumbs-link">
                Piles List                    
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
      <div class="row">
     <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="fa fa-search"></i>
            </span>
            <h3 class="kt-portlet__head-title">
              Search
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
    <div class="kt-portlet__head-actions">       
    </div>  
</div>      
</div>
    </div>
    <form class="kt-form kt-form--label-right" method="post" action="{{url('piles')}}">
    {{ csrf_field() }}
    <div class="kt-portlet__body">
    <div class="col-md-12">    
        <div class="col-md-4">           
            <div class="form-group">
                <label>Pile Code</label>
                    <input class="form-control" type="text" name="pile" value="<?php if(isset($_POST['pile'])) echo $_POST['pile'] ?>" id="pile" placeholder="Enter Pile Code" autocomplete="off">
            </div>        
        </div>
        <div class="col-md-4">           
            <div class="form-group">
                <label>Location</label>
                <select class="form-control pull-right" id="location" name="location" onchange="javascript:get_sites()">
                    <option value="">Select Location</option> 
                    @foreach($location as $row)
                            <option <?php if(isset($_POST['location']) && $_POST['location']==$row->id ) echo "selected" ?> value="{{ $row->id }}">
                                {{ $row->name }}
                            </option>
                    @endforeach                       
                </select>
            </div>        
        </div>
        <div class="col-md-4">           
            <div class="form-group">
                <label>Site</label>
                <select class="form-control pull-right" id="site" name="sites">
                    <option value="">Select Site</option>  
                    @foreach($sites as $row)
                            <option <?php if(isset($_POST['sites']) && $_POST['sites']==$row->id ) echo "selected" ?> value="{{ $row->id }}">
                                {{ $row->name }}
                            </option>
                    @endforeach                                              
                </select>
            </div>        
        </div>
        <div class="clearfix"></div>
        <div class="col-md-2">           
            <div class="form-group">
                <label>From Date</label>
                <input class="form-control datepicker" type="text" name="from_date" value="<?php if(isset($_POST['from_date'])) echo $_POST['from_date'] ?>" id="kt_datepicker_1" placeholder="From Date" autocomplete="off">
            </div>        
        </div>
        <div class="col-md-2">           
            <div class="form-group">
                <label>To Date</label>
                <input class="form-control datepicker" type="text" name="to_date" value="<?php if(isset($_POST['to_date'])) echo $_POST['to_date'] ?>" id="kt_datepicker_1" placeholder="To Date" autocomplete="off">
            </div>        
        </div>
    </div>     
    </div>      
    <div class="kt-portlet__foot">
        <div class="kt-form__actions">
            <div class="row">
                <div class="col-10">
                    <button type="submit" class="btn btn-success">Search</button>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>
</div>
<!--Begin::Section-->
<div class="row">
     <div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand fa fa-building"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                Piles List
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
    <div class="kt-portlet__head-actions">
       &nbsp;
       @if($role==2)
        <a href="{{ url('add_pile')}}" class="btn btn-brand btn-elevate btn-icon-sm">
            <i class="la la-plus"></i>
            Add New Pile
        </a>
        <a href="{{ url('add_bulk_pile')}}" class="btn btn-brand btn-elevate btn-icon-sm">
            <i class="la la-plus"></i>
            Add Bulk Piles
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
                    <th>Piles Name/Code</th>
                    <th>Site</th>
                    <th>Location</th>
                    <th>Pile type </th>
                    <th>ERP Code  </th>
                    <th>Bulk material</th>
                    <th>Additional; Site Info </th>
                    <th>Value Per Ton </th>
                    <th>Date/Time </th>
                    <th>Derived SAP stock  </th>
                    @if($role==2)
                        <th>Actions</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @foreach($piles as $row )
                <tr>
                    <td>{{ $row->pile_name  }}/{{ $row->pile_reference_id  }}</td>
                    <td>{{ $row->site_name  }}</td>
                    <td>{{ $row->location_name  }}</td>
                    <td>{{ $row->pile_type  }}</td>
                    <td>{{ $row->erp_code  }}</td>
                    <td>{{ $row->material_name  }} / {{ $row->material_code  }}</td>
                    <td>{{ $row->additional_info  }}</td>
                    <td>{{ $row->value_per_ton  }}</td>
                    <td><?php  $exd = date_create($row->date); echo date_format($exd,'d-m-Y');?> / <?php echo $row->time; ?></td>
                    <td>{{ $row->derived_sap  }}</td>
                    @if($role==2)
                    <td>
                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="{{url('edit_pile/'.$row->id)}}" title="Edit Pile" >
                            <i class="fa fa-edit"></i>
                        </a>
                        @if($row->is_active==1)
                        <a href="{{url('change_pile_status/'.$row->id.'/0')}}" title="Deactivate Pile" onclick="return confirm('This pile will be deactivated. Are you sure to proceed?');">
                                <i class="fa fa-times"></i>
                              </a>
                        @else
                        <a href="{{url('change_pile_status/'.$row->id.'/1')}}" title="Activate Pile">
                                <i class="fa fa-check"></i>
                              </a>
                        @endif
                    </td>
                     @endif
                </tr>
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
<script src="{{ asset('public/assets/js/demo8/pages/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<!--end::Page Scripts -->
<script type="text/javascript">
    function get_sites()
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
            $("#site").val("");
        }
    }
</script>
@endpush
