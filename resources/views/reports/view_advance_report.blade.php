
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <style type="text/css">    
    .table_left{
        border: 1px solid black;
        border-collapse: collapse;
    } 
    .table_left th{ 
        padding: 15px;
    }
    .table_right{
        border: 1px solid white;
        border-collapse: collapse;
    }    
        th {
          padding: 10px;
          text-transform: capitalize;
        }
            /* The Modal (background) */
.dash_img {
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

.dash_img:hover {opacity: 0.7;}

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
    </style>
</head>
<body>
    <div id="capture">
    <div class="row">
        <div class="col-md-8 col-md-offset-2" style="background-color: #000000;">
                    <div class="col-md-6">
                        <img src="{{ asset('assets/media/logos/advance_logo.png') }}">
                    </div>
                    <div class="col-md-6"></div>
                    <div class="clearfix"></div>
                <div class="col-md-12" style="border: 2px solid #eeeeee; padding: 0px; margin-top: 20px;margin-bottom: 20px;">

                    <!-- ###################  LEFT #################### -->
                    <div class="col-md-4" style="background-color: #fdbc16;">
                        
                        <div class="col-md-12" style="border-bottom: 2px solid #eeeeee;">
                           <center> <h4 style="font-weight: 700; color: #000;">Volume</h4></center>
                           <center> <h4 style="font-weight: 700; color: #000; margin-top: 30px;">{{ $pile->volume}} m<sup>3</sup></h4></center>                           
                        </div>

                        <div class="col-md-12" style="border-bottom: 2px solid #eeeeee;">
                           <center> <img class="img-thumbnail" style="background-color:transparent !important;  border:none !important;" src="{{ asset('assets/media/logos/verified.png') }}"></center>
                           <h5 style="font-weight: 700; color: #000; margin-top: 30px;">Code: {{ $pile->pile_reference_id}}</h5>                        
                        </div>
                        <div class="clearfix"></div>
                        <table class="table_left" style=" width:100%; color: #000000; margin-top: 15px;" border="1">
                            <tr><th colspan="2"><center>POSSIBLE RISKS</center></th></tr>
                            <tr>
                                <th>Combined Piles</th>
                                <th>{{ $pile->combined_piles}}</th>
                            </tr>
                            <tr>
                                <th>Standing water</th>
                                <th>{{ $pile->standing_water}}</th>
                            </tr>
                            <tr>
                                <th>Debris</th>
                                <th>{{ $pile->debris}}</th>
                            </tr>
                            <tr>
                                <th>Equipment Obstruction</th>
                                <th>{{ $pile->equipment_obstruction}}</th>
                            </tr>
                            <tr>
                                <th>Vegetation</th>
                                <th>{{ $pile->vegetation}}</th>
                            </tr>
                            <tr>
                                <th>Highwall</th>
                                <th>{{ $pile->highwall}}</th>
                            </tr>
                            <tr>
                                <th>Lighting Issue</th>
                                <th>{{ $pile->lighting_issue}}</th>
                            </tr>
                            <tr>
                                <th>Buried Base</th>
                                <th>{{ $pile->burried_base}}</th>
                            </tr>
                            <tr>
                                <th>OGL</th>
                                <th>{{ $pile->ogl}}</th>
                            </tr>
                            <tr>
                                <th>Piles covered with Tarpaulin</th>
                                <th>{{ $pile->piles_covered_with_tarpolin}}</th>
                            </tr>
                        </table>
                        <br>
                    </div>
                    <!-- ###################  LEFT #################### -->

                    <!-- ###################  RIGHT #################### -->
                    <div class="col-md-8">
                        <table class="table_right" style=" width:100%; color: #ffffff; margin-top: 15px;" border="1">
                            <tr>
                                <th><h3>Pile Name</h3></th>
                                <th colspan="2"><h3>{{ $pile->pile_name}}</h3></th>
                            </tr>
                            <tr>
                                <th>Site</th>
                                <th colspan="2">{{ $pile->site_name}}</th>
                            </tr>
                            <tr>
                                <th>Location</th>
                                <th>{{ $pile->location_name}}</th>
                                <th>Date: {{ $pile->date_of_survey}}</th>
                            </tr>                            
                        </table>

                        <table class="table_right" style=" width:100%; color: #ffffff; margin-top: 15px;" border="1">
                            <tr>
                                <th>Pile type</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Method</th>
                            </tr>
                            <tr>
                                <th>{{ $pile->pile_type}}</th>
                                <th>{{ $pile->start_time}}</th>
                                <th>{{ $pile->end_time}}</th>
                                <th>{{ $pile->method}}</th>
                            </tr>                           
                        </table>

                        <div style="padding: 25px;">
                            <table class="table_right" style=" width:100%; color: #ffffff; margin-top: 15px;" border="1">
                                <tr>
                                    <th>  
                                        @if(!empty($pile->toe_confidence))
                                            @if($pile->toe_confidence=="low")  
                                                <img src="{{ asset('assets/media/logos/low.png') }}" class="img-thumbnail" >
                                            @elseif($pile->toe_confidence=="medium")
                                                <img src="{{ asset('assets/media/logos/medium.png') }}" class="img-thumbnail" >
                                            @elseif($pile->toe_confidence=="high")
                                                <img src="{{ asset('assets/media/logos/high.png') }}" class="img-thumbnail" >
                                            @else
                                                <img src="{{ asset('assets/media/logos/na.png') }}" class="img-thumbnail" >
                                            @endif
                                        @endif                          
                                    </th>
                                    <th>
                                        @if(!empty($pile->surface_confidence))
                                            @if($pile->surface_confidence=="low")  
                                                <img src="{{ asset('assets/media/logos/low.png') }}" class="img-thumbnail" >
                                            @elseif($pile->surface_confidence=="medium")
                                                <img src="{{ asset('assets/media/logos/medium.png') }}" class="img-thumbnail" >
                                            @elseif($pile->surface_confidence=="high")
                                                <img src="{{ asset('assets/media/logos/high.png') }}" class="img-thumbnail" >
                                            @else
                                                <img src="{{ asset('assets/media/logos/na.png') }}" class="img-thumbnail" >
                                            @endif
                                        @endif  
                                    </th>
                                </tr> 
                                <tr>
                                    <th>Toe Coverage Confidence</th>
                                    <th>Surface Coverage Confidence</th>
                                </tr>                          
                            </table>
                        </div>

                        <table class="table_right" style=" width:100%; color: #ffffff; margin-top: 15px;" border="1">
                            <tr>
                                <th>  
                                    @if(!empty($pile->image_two))
                                        @if(file_exists(base_path().'/public/date_metron/'.$pile->image_two))  
                                            <img src="{{ url('/').'/date_metron/'.$pile->image_two }}" class="dash_img img-thumbnail"  id="image_one">
                                        @else
                                            "NA"
                                        @endif
                                    @endif                          
                                </th>
                                <th>
                                    @if(!empty($pile->image_one))
                                        @if(file_exists(base_path().'/public/date_metron/'.$pile->image_one))  
                                            <img src="{{ url('/').'/date_metron/'.$pile->image_one }}" class="dash_img img-thumbnail"  id="image_two">
                                        @else
                                            "NA"
                                        @endif
                                    @endif 
                                </th>
                            </tr>
                            <tr>
                                <th>Digital Elevation Model</th>
                                <th>Ortho Image/Pile Image</th>
                            </tr> 
                            <tr>
                                <th colspan="2">View 3d model in Link: <a href="{{$pile->three_dmodel}}" target="_blank" >Click here for 3d Model</a></th>
                            </tr>                           
                        </table>

                        <div class="col-md-12" style="border: 1px solid white; margin-top: 10px; color: white;"><h4>Comments: {{$pile->comments}}</h4></div>
                    </div>
                    <!-- ###################  RIGHT #################### -->
                </div>
        </div>        
    </div>
    </div>
    <div class="clearfix"></div>
    <br>
    <div class="col-md-3 col-md-offset-5">
        <a class="btn btn-lg btn-danger" href="javascript:void(0)"  onclick="javascript:share()" id="share_btn">
          Share
        </a>
    </div>
    <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Share Report</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="{{ url('share_advance_report') }}">
        {{ csrf_field() }}
      <div class="modal-body"> 
            <label>Please enter the email</label>
            <input type="email" name="email" required="required" class="form-control" placeholder="Email">
            <input type="hidden" name="imgurl" id="imgurl" required="required" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="javascript:close_modal()">Close</button>
        <button type="submit" class="btn btn-primary" >Share Report</button>
      </div>
    </form>
    </div>
  </div>
</div>
<!-- The Modal -->
<div id="myModal" class="modal">
  <span class="close" id="close_my_model" style="display: block !important;">&times;</span>
  <img class="modal-content" id="img01">
  <div id="caption"></div>
</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('js/html2canvas.js') }}"></script>
    <script src="{{ asset('js/block_ui.js') }}"></script>    
    <script type="text/javascript">
        function share()
        {
            $("#share_btn").css("display", "none");
            setTimeout(function(){$.blockUI({ message: "<i class='fa fa-2x fa-spinner fa-spin' aria-hidden='true' ></i> &nbsp; <h6>Loading... a moment please.</h6>" })}, 1000);
            html2canvas(document.querySelector("body")).then(canvas => {
                    var imagedata = canvas.toDataURL('image/png');
                    var imgdata = imagedata.replace(/^data:image\/(png|jpg);base64,/, "");
                    //ajax call to save image inside folder
                    $.ajax({
                    url: "{{ url('save_share_image') }}",
                    type: 'POST',
                    data: {_token : '{{csrf_token()}}', imgdata:imgdata},            
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success:function(data){
                        if(data!=0)
                        { 
                            $.unblockUI();
                            $("#imgurl").val(data);
                            $("#exampleModal").show();
                            $("#exampleModal").removeClass('fade');
                        }
                        else
                        {
                            $.unblockUI();
                            alert("Something went wrong, please try after sometime.");
                        }
                    }
                });
                });
            $("#share_btn").css("display", "block");
        }

    </script>
    <script>
    function open_modal(id, pile)
    {
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
var span = document.getElementById("close_my_model");

// When the user clicks on <span> (x), close the modal
span.onclick = function() { 
    modal.style.display = "none";
}
</script>
</body>
</html>