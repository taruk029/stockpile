@extends('layouts.app')

@section('title', 'Welcome to Dashboard')

@push('styles')
<style>
    .datepicker{
        width: fit-content !important;
    }
    .error{
        color:red;
    }
.dash_img {
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

.dash_img:hover {opacity: 0.7;}

/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 11; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (image) */
.modal-content {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
}

/* Caption of Modal Image */
#caption {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
    text-align: center;
    color: #ccc;
    padding: 10px 0;
    height: 150px;
}

/* Add Animation */
.modal-content, #caption {    
    -webkit-animation-name: zoom;
    -webkit-animation-duration: 0.6s;
    animation-name: zoom;
    animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
    from {-webkit-transform:scale(0)} 
    to {-webkit-transform:scale(1)}
}

@keyframes zoom {
    from {transform:scale(0)} 
    to {transform:scale(1)}
}

/* The Close Button */
.close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
}

.close:hover,
.close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
    .modal-content {
        width: 100%;
    }
}
</style>
<link href="{{ asset('datatables/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style type="text/css">
.dash_img {
  display: block;
  max-width:500px;
  max-height:200px;
  width: auto;
  height: auto;
}
</style>
@endpush
@section('content')

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">
            Advance Pile Report
        </h3>
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
<div class="row">
<div class="kt-portlet">
        <div class="kt-portlet__body  kt-portlet__body--fit">
        <div class="row row-col-separator-xl">
        <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="kt-portlet__body">
            <form class="" method="post" action="{{url('home')}}">
            {{ csrf_field() }}
            <div class="form-group row">
                <div class="col-2">
                   <input class="form-control" type="text" name="pile" value="<?php if(isset($_POST['pile'])) echo $_POST['pile'] ?>" id="pile" placeholder="Enter Pile Code" autocomplete="off">
                </div>
                <div class="col-2">
                   <select class="form-control pull-right" id="location" name="location"  onchange="javascript:get_sites()">
                        <option value="">Select Location</option> 
                        @foreach($location as $row)
                                <option <?php if(isset($_POST['location']) && $_POST['location']==$row->id ) echo "selected" ?> value="{{ $row->id }}">
                                    {{ $row->name }}
                                </option>
                        @endforeach                       
                    </select>
                </div>
                <div class="col-2">
                   <select class="form-control pull-right" id="site" name="sites">
                        <option value="">Select Site</option> 
                        @foreach($sites as $row)
                                <option <?php if(isset($_POST['sites']) && $_POST['sites']==$row->id ) echo "selected" ?> value="{{ $row->id }}">
                                    {{ $row->name }}
                                </option>
                        @endforeach                       
                    </select>
                </div>
                <div class="col-2">
                   <input class="form-control datepicker" type="text" name="from_date" value="<?php if(isset($_POST['sites'])) echo $_POST['from_date'] ?>" id="kt_datepicker_1" placeholder="From Date" autocomplete="off">
                </div>
                <div class="col-2">
                   <input class="form-control datepicker" type="text" name="to_date" value="<?php if(isset($_POST['sites'])) echo $_POST['to_date'] ?>" id="kt_datepicker_1" placeholder="To Date" autocomplete="off">
                </div>
                <div class="col-1">
                    <button type="submit" class="btn btn-success pull-right">Filter</button>
                </div>
            </div>
            </form>
        <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
            <thead>
                <tr>
                    <th>Pile Code</th>
                    <th>Location</th>
                    <th>Site</th>
                    <th>Pile type </th>
                    <th>Pile Name </th>
                    <th>Additional Info </th>
                    <th>Start Time  </th>
                    <th>End Time  </th>
                    <th>Volume</th>
                    <th>Tonnage </th>
                    <th> Date  </th>
                    <th> Report  </th>
                </tr>
            </thead>

            <tbody>
                @foreach($piles as $row )
                <tr>
                    <td>{{ $row->pile_reference_id  }}</td>
                    <td>{{ $row->location_name  }}</td>
                    <td>{{ $row->site_name  }}</td>
                    <td>{{ $row->pile_type  }}</td>
                    <td>{{ $row->pile_name  }}</td>
                    <td>{{ $row->additional_info  }}</td>
                    <td><?php echo $row->start_time; ?></td>
                    <td><?php  echo $row->end_time; ?></td>
                    <td>{{ $row->volume  }}</td>
                    <?php 
                    $tonnage ="BD/Moisture not entered";
                    if($row->volume!="" && $row->moisture && $row->bulk_density) 
                    {
                        $tonnage = (($row->volume*$row->bulk_density)*(1-$row->moisture));
                    }?>
                    <td>{{$tonnage}}</td>                   
                    <td><?php  $exd = date_create($row->date_of_survey); echo date_format($exd,'d-m-Y'); ?></td>
                    <td>
                    <a class="btn btn-sm btn-clean" href="{{ url('view_advance_report/'.base64_encode($row->date_metron_id)) }}" title="View Advance Report" target="_new" >
                        Advance Report
                    </a>
                    </td>
                </tr>  
            @endforeach                
            </tbody>
        </table>
    </div>
    </div></div></div></div>
</div>

<!-- The Modal -->
<div id="myModal" class="modal">
  <span class="close" style="display: block !important;">&times;</span>
  <img class="modal-content" id="img01">
  <div id="caption"></div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Request Enquiry</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>      
        <form action="{{url('send_mail')}}" method="post">            
        @csrf
      <div class="modal-body">        
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Pile Code:</label>
            <input type="text" class="form-control" id="pile_code" name="pile_code" placeholder="Pile Code" required  readonly="readonly">
          
            <label for="recipient-name" class="col-form-label">Your Email:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Your Email" required>
          
            <label for="recipient-name" class="col-form-label">Message:</label>
            <textarea class="form-control" rows="3" name="message" id="message" placeholder="Message" required></textarea>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="javascript:close_modal()">Close</button>
        <button type="submit" class="btn btn-primary">Send Mail</button>
      </div>      
        </form>
    </div>
  </div>
</div>
</div>

    <!-- end:: Content -->

@endsection

@push('scripts')

<!--begin::Page Vendors(used by this page) -->

<script src="{{ asset('assets/js/demo8/pages/dashboard.js') }}" type="text/javascript"></script>

<script src="{{ asset('datatables/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

<script src="{{ asset('datatables/assets/js/demo8/pages/crud/datatables/basic/paginations.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/demo8/pages/bootstrap-datepicker.js') }}" type="text/javascript"></script>


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
<script>
    var table;
    $(document).ready(function()
    {
        table = $('#kt_table_1').DataTable();
    });
</script>

<script type="text/javascript">    
    function s_excel()
    {
        /*var month = "";
        var year = "";
        month = $("#month").val();
        year = $("#year").val();
        var query_str = "month="+month+"&year="+year;
        window.open("{{ url('dashboard_excel/?') }}"+query_str);*/
        window.open("{{ url('pile_excel') }}");
    }
</script>
<script type="text/javascript">
function load_images(id)
{
    if(id)
    {
        $.blockUI({ message: "<i class='fa fa-2x fa-spinner fa-spin' aria-hidden='true' ></i> &nbsp; <h6>Loading... a moment please.</h6>" });         
        $.ajax({
            url: "{{ url('load_images') }}",
            type: 'GET',
            data: {id:id},            
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:function(data){
                var datas = $.parseJSON(data);
                if(datas)
                {
                    $('#image_one').attr('src',datas.image_one);
                    $('#image_two').attr('src',datas.image_two);
                }
            }
        });
        $.unblockUI();
    }
}
</script>
<script>
    function open_modal(id, pile)
    {
        $("#date_metron_id").val(id);
        $("#pile_code").val(pile);
        $("#exampleModal").show();
        $("#exampleModal").removeClass('fade');
    } 
    function close_modal(id)
    {
        $("#exampleModal").hide();
        $("#exampleModal").addClass('fade');
    }    
</script>
<script>
    // Get the modal
var modal = document.getElementById('myModal');

// Get the image and insert it inside the modal - use its "alt" text as a caption
var img = document.getElementById('myImg');
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");
image_one.onclick = function(){
    modal.style.display = "block";
    modalImg.src = this.src;
    captionText.innerHTML = this.alt;
}

image_two.onclick = function(){
    modal.style.display = "block";
    modalImg.src = this.src;
    captionText.innerHTML = this.alt;
}

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() { 
    modal.style.display = "none";
}
</script>
@endpush



