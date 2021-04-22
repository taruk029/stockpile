                <!-- begin:: Header -->
<div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed "  data-ktheader-minimize="on" >
    <div class="kt-header__top">
        <div class="kt-container">
            <!-- begin:: Brand -->
<div class="kt-header__brand   kt-grid__item" id="kt_header_brand">
    <div class="kt-header__brand-logo">
        <a href="index-2.html">
            <img alt="Logo" src="{{ asset('public/assets/media/logos/logo-8.png') }}" class="kt-header__brand-logo-default"/>
            <img alt="Logo" src="{{ asset('public/assets/media/logos/logo-8-inverse.png') }}" class="kt-header__brand-logo-sticky"/>          
        </a>        
    </div> 
</div>
<!-- end:: Brand -->            
<!-- begin:: Header Topbar -->
<div class="kt-header__topbar">


    <!--begin: Language bar -->
    <div class="kt-header__topbar-item kt-header__topbar-item--langs" style="display:none">
        <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,10px">
            <span class="kt-header__topbar-icon">
                <img class="" src="public/assets/media/flags/020-flag.svg" alt="" />
            </span> 
        </div>
        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim">
            <ul class="kt-nav kt-margin-t-10 kt-margin-b-10">
    <li class="kt-nav__item kt-nav__item--active">
        <a href="#" class="kt-nav__link">
            <span class="kt-nav__link-icon"><img src="public/assets/media/flags/020-flag.svg" alt="" /></span>
            <span class="kt-nav__link-text">English</span>
        </a>
    </li>
    <li class="kt-nav__item">
        <a href="#" class="kt-nav__link">
            <span class="kt-nav__link-icon"><img src="public/assets/media/flags/016-spain.svg" alt="" /></span>
            <span class="kt-nav__link-text">Spanish</span>
        </a>
    </li>
    <li class="kt-nav__item">
        <a href="#" class="kt-nav__link">
            <span class="kt-nav__link-icon"><img src="public/assets/media/flags/017-germany.svg" alt="" /></span>
            <span class="kt-nav__link-text">German</span>
        </a>
    </li>
</ul>       </div>
    </div>
    <!--end: Language bar -->
    
    <!--begin: User bar -->
    <div class="kt-header__topbar-item kt-header__topbar-item--user" >
        <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,10px">
            <span class="kt-header__topbar-welcome kt-visible-desktop">Hi,</span>
            <span class="kt-header__topbar-username kt-visible-desktop">{{ Auth::user()->name }}</span>
            <img alt="Pic" src="{{ asset('public/assets/media/users/300_21.jpg') }}"/>         
            <span class="kt-header__topbar-icon kt-bg-brand kt-font-lg kt-font-bold kt-font-light kt-hidden">S</span>
            <span class="kt-header__topbar-icon kt-hidden"><i class="flaticon2-user-outline-symbol"></i></span>         
        </div>
        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-xl">
            <!--begin: Head -->
    <div class="kt-user-card kt-user-card--skin-light kt-notification-item-padding-x">
        <div class="kt-user-card__avatar">
            <img class="kt-hidden-" alt="Pic" src="{{ asset('public/assets/media/users/300_25.jpg') }}" />
            <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
            <span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold kt-hidden">S</span>
        </div>
        <div class="kt-user-card__name">
         {{ Auth::user()->name }}
        </div>
        <div class="kt-user-card__badge">
            <a href="{{ route('logout') }}" class="btn btn-label btn-label-brand btn-sm btn-bold">Sign Out</a>
        </div>
    </div>
<!--end: Head -->
    </div>
    </div>
    <!--end: User bar -->
</div>
<!-- end:: Header Topbar -->         
</div>
    </div>
    <div class="kt-header__bottom">
        <div class="kt-container">
            <!-- begin: Header Menu -->
<button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
<div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
   <div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile ">
    <ul class="kt-menu__nav ">
        <!-- -------------------Admin Section---------------------------- -->
        @if(Auth::user()->role==0)
            <li class="kt-menu__item  kt-menu__item<?php if(Request::segment(1)=="home" || Request::segment(1)=="") echo "--active";  else echo"--rel"; ?> " aria-haspopup="true">
                <a href="{{ url('home')}}" class="kt-menu__link "><span class="kt-menu__link-text">Dashboard</span></a>
            </li>

            <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item<?php if(Request::segment(1)=="clients" || Request::segment(1)=="add_client" || Request::segment(1)=="edit_client") echo "--active";  else echo"--rel"; ?>" aria-haspopup="true">
                <a href="{{ url('clients')}}" class="kt-menu__link"><span class="kt-menu__link-text">Clients</span>
            </a>
            </li>

            <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item<?php if(Request::segment(1)=="staff" || Request::segment(1)=="add_staff" || Request::segment(1)=="edit_staff") echo "--active";  else echo"--rel"; ?>" aria-haspopup="true">
                <a href="{{ url('staff')}}" class="kt-menu__link"><span class="kt-menu__link-text">Staff</span>
            </a>
            </li>
        <!-- -------------------Trainer Section---------------------------- -->
        @elseif(Auth::user()->role==1)
            <li class="kt-menu__item  kt-menu__item<?php if(Request::segment(1)=="home") echo "--active";  else echo"--rel"; ?> " aria-haspopup="true">
                <a href="{{ url('/')}}" class="kt-menu__link "><span class="kt-menu__link-text">Dashboard</span></a>
            </li>
            <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item<?php if(Request::segment(1)=="trainer_plans" 
                || Request::segment(1)=="view_attendance"
                ) echo "--active";  else echo"--rel"; ?>" aria-haspopup="true">
                <a href="{{ url('trainer_plans')}}" class="kt-menu__link"><span class="kt-menu__link-text">Trainer Batch</span>
            </a>
            </li>
            <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item<?php if(Request::segment(1)=="trainer_feedback") echo "--active";  else echo"--rel"; ?>" aria-haspopup="true">
                <a href="{{ url('trainer_feedback')}}" class="kt-menu__link"><span class="kt-menu__link-text">Trainer Feedback</span>
            </a>
            </li>
            <!-- <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item<?php if(Request::segment(1)=="trainer_average") echo "--active";  else echo"--rel"; ?>" aria-haspopup="true">
                <a href="{{ url('trainer_average')}}" class="kt-menu__link"><span class="kt-menu__link-text">Trainer Average</span>
            </a>
            </li> -->
            @elseif(Auth::user()->role==4)
            <li class="kt-menu__item  kt-menu__item<?php if(Request::segment(1)=="home" || Request::segment(1)=="") echo "--active";  else echo"--rel"; ?> " aria-haspopup="true">
                <a href="{{ url('home')}}" class="kt-menu__link "><span class="kt-menu__link-text">Dashboard</span></a>
            </li>
            <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item<?php if(Request::segment(1)=="dates_metron" || Request::segment(1)=="add_date_metron" || Request::segment(1)=="edit_date_metron") echo "--active";  else echo"--rel"; ?>" aria-haspopup="true">
                <a href="{{ url('dates_metron')}}" class="kt-menu__link"><span class="kt-menu__link-text">Dates Metron</span>
            </a>
            </li>
            @elseif(Auth::user()->role==3)            
                <li class="kt-menu__item  kt-menu__item<?php if(Request::segment(1)=="home" || Request::segment(1)=="") echo "--active";  else echo"--rel"; ?> " aria-haspopup="true">
                    <a href="{{ url('home')}}" class="kt-menu__link "><span class="kt-menu__link-text">Dashboard</span></a>
                </li>
                <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item<?php if(Request::segment(1)=="locations"
                || Request::segment(1)=="add_location"
                || Request::segment(1)=="edit_location"
                || Request::segment(1)=="sites"
                || Request::segment(1)=="add_site"
                || Request::segment(1)=="edit_site"

                ) echo "--active";  else echo"--rel"; ?>" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
                <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-text">Company</span>
                    <i class="kt-menu__hor-arrow la la-angle-down"></i>
                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="kt-menu__submenu kt-menu__submenu--classic">
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                            <a href="{{ url('locations')}}" class="kt-menu__link">
                                <!-- <i class="kt-menu__link-icon flaticon2-architecture-and-city"></i> -->
                                <span class="kt-menu__link-text">Locations</span>
                            </a>
                        </li> 
                        <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                            <a href="{{ url('sites')}}" class="kt-menu__link">
                                <!-- <i class="kt-menu__link-icon flaticon2-architecture-and-city"></i> -->
                                <span class="kt-menu__link-text">Sites</span>
                            </a>
                        </li>                    
                    </ul>
                </div>
            </li>
            <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item<?php if(Request::segment(1)=="bulk_material"
                || Request::segment(1)=="add_bulk_material"
                || Request::segment(1)=="edit_bulk_material"
                || Request::segment(1)=="piles"
                || Request::segment(1)=="add_pile"
                || Request::segment(1)=="edit_pile"

                ) echo "--active";  else echo"--rel"; ?>" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
                <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-text">Material Dashboard</span>
                    <i class="kt-menu__hor-arrow la la-angle-down"></i>
                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="kt-menu__submenu kt-menu__submenu--classic">
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                            <a href="{{ url('bulk_material')}}" class="kt-menu__link">
                                <!-- <i class="kt-menu__link-icon flaticon2-architecture-and-city"></i> -->
                                <span class="kt-menu__link-text">Bulk Material</span>
                            </a>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                            <a href="{{ url('piles')}}" class="kt-menu__link">
                                <!-- <i class="kt-menu__link-icon flaticon2-architecture-and-city"></i> -->
                                <span class="kt-menu__link-text">Piles</span>
                            </a>
                        </li>                   
                    </ul>
                </div>
            </li>
            <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item<?php if(Request::segment(1)=="advance_report"
                || Request::segment(1)=="reconcilation"
                || Request::segment(1)=="timeline"
                ) echo "--active";  else echo"--rel"; ?>" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
                <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-text">Reports</span>
                    <i class="kt-menu__hor-arrow la la-angle-down"></i>
                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="kt-menu__submenu kt-menu__submenu--classic">
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                            <a href="{{ url('advance_report')}}" class="kt-menu__link">
                                <!-- <i class="kt-menu__link-icon flaticon2-architecture-and-city"></i> -->
                                <span class="kt-menu__link-text">Advance Reports </span>
                            </a>
                        </li>
                        <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                            <a href="{{ url('reconcilation')}}" class="kt-menu__link">
                                <!-- <i class="kt-menu__link-icon flaticon2-architecture-and-city"></i> -->
                                <span class="kt-menu__link-text">Reconcilation </span>
                            </a>
                        </li> 
                        <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                            <a href="{{ url('timeline')}}" class="kt-menu__link">
                                <!-- <i class="kt-menu__link-icon flaticon2-architecture-and-city"></i> -->
                                <span class="kt-menu__link-text">Timeline Report  </span>
                            </a>
                        </li>                   
                    </ul>
                </div>
            </li>
            <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item<?php if(Request::segment(1)=="andriod_ios") echo "--active";  else echo"--rel"; ?>" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
                <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-text">Self Survey</span>
                    <i class="kt-menu__hor-arrow la la-angle-down"></i>
                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="kt-menu__submenu kt-menu__submenu--classic">
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                            <a href="{{ url('andriod_ios')}}" class="kt-menu__link">
                                <!-- <i class="kt-menu__link-icon flaticon2-architecture-and-city"></i> -->
                                <span class="kt-menu__link-text">Android / IOS APP  </span>
                            </a>
                        </li>                   
                    </ul>
                </div>
            </li>
            <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item<?php if(Request::segment(1)=="create_project") echo "--active";  else echo"--rel"; ?>" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
                <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-text">Upload </span>
                    <i class="kt-menu__hor-arrow la la-angle-down"></i>
                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="kt-menu__submenu kt-menu__submenu--classic">
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                            <a href="{{ url('create_project')}}" class="kt-menu__link">
                                <!-- <i class="kt-menu__link-icon flaticon2-architecture-and-city"></i> -->
                                <span class="kt-menu__link-text">Create Project </span>
                            </a>
                        </li>                   
                    </ul>
                </div>
            </li>
            <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item<?php if(Request::segment(1)=="create_project_goods" || Request::segment(1)=="andriod_ios_goods" ) echo "--active";  else echo"--rel"; ?>" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
                <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                    <span class="kt-menu__link-text">Finshed Goods  </span>
                    <i class="kt-menu__hor-arrow la la-angle-down"></i>
                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="kt-menu__submenu kt-menu__submenu--classic">
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                            <a href="{{ url('create_project_goods')}}" class="kt-menu__link">
                                <!-- <i class="kt-menu__link-icon flaticon2-architecture-and-city"></i> -->
                                <span class="kt-menu__link-text">Create Project </span>
                            </a>
                        </li> 
                        <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                            <a href="{{ url('andriod_ios_goods')}}" class="kt-menu__link">
                                <!-- <i class="kt-menu__link-icon flaticon2-architecture-and-city"></i> -->
                                <span class="kt-menu__link-text">Android / IOS APP  </span>
                            </a>
                        </li>                   
                    </ul>
                </div>
            </li>
            @else
            

            <li class="kt-menu__item  kt-menu__item<?php if(Request::segment(1)=="home" || Request::segment(1)=="") echo "--active";  else echo"--rel"; ?> " aria-haspopup="true">
                <a href="{{ url('home')}}" class="kt-menu__link "><span class="kt-menu__link-text">Dashboard</span></a>
            </li>
            <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item<?php if(Request::segment(1)=="my_users"
            || Request::segment(1)=="add_user"
            || Request::segment(1)=="edit_user"
            || Request::segment(1)=="locations"
            || Request::segment(1)=="add_location"
            || Request::segment(1)=="edit_location"
            || Request::segment(1)=="sites"
            || Request::segment(1)=="add_site"
            || Request::segment(1)=="edit_site"

            ) echo "--active";  else echo"--rel"; ?>" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                <span class="kt-menu__link-text">Company</span>
                <i class="kt-menu__hor-arrow la la-angle-down"></i>
                <i class="kt-menu__ver-arrow la la-angle-right"></i>
            </a>
            <div class="kt-menu__submenu kt-menu__submenu--classic">
                <ul class="kt-menu__subnav">
                    <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                        <a href="{{ url('my_users')}}" class="kt-menu__link">
                            <!-- <i class="kt-menu__link-icon flaticon2-architecture-and-city"></i> -->
                            <span class="kt-menu__link-text">Users</span>
                        </a>
                    </li>
                    <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                        <a href="{{ url('locations')}}" class="kt-menu__link">
                            <!-- <i class="kt-menu__link-icon flaticon2-architecture-and-city"></i> -->
                            <span class="kt-menu__link-text">Locations</span>
                        </a>
                    </li> 
                    <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                        <a href="{{ url('sites')}}" class="kt-menu__link">
                            <!-- <i class="kt-menu__link-icon flaticon2-architecture-and-city"></i> -->
                            <span class="kt-menu__link-text">Sites</span>
                        </a>
                    </li>                    
                </ul>
            </div>
        </li>
        <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item<?php if(Request::segment(1)=="bulk_material"
            || Request::segment(1)=="add_bulk_material"
            || Request::segment(1)=="edit_bulk_material"
            || Request::segment(1)=="piles"
            || Request::segment(1)=="add_pile"
            || Request::segment(1)=="edit_pile"

            ) echo "--active";  else echo"--rel"; ?>" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                <span class="kt-menu__link-text">Material Dashboard</span>
                <i class="kt-menu__hor-arrow la la-angle-down"></i>
                <i class="kt-menu__ver-arrow la la-angle-right"></i>
            </a>
            <div class="kt-menu__submenu kt-menu__submenu--classic">
                <ul class="kt-menu__subnav">
                    <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                        <a href="{{ url('bulk_material')}}" class="kt-menu__link">
                            <!-- <i class="kt-menu__link-icon flaticon2-architecture-and-city"></i> -->
                            <span class="kt-menu__link-text">Bulk Material</span>
                        </a>
                    </li>
                    <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                        <a href="{{ url('piles')}}" class="kt-menu__link">
                            <!-- <i class="kt-menu__link-icon flaticon2-architecture-and-city"></i> -->
                            <span class="kt-menu__link-text">Piles</span>
                        </a>
                    </li>                   
                </ul>
            </div>
        </li>
        <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item<?php if(Request::segment(1)=="advance_report"
            || Request::segment(1)=="reconcilation"
            || Request::segment(1)=="timeline"
            ) echo "--active";  else echo"--rel"; ?>" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                <span class="kt-menu__link-text">Reports</span>
                <i class="kt-menu__hor-arrow la la-angle-down"></i>
                <i class="kt-menu__ver-arrow la la-angle-right"></i>
            </a>
            <div class="kt-menu__submenu kt-menu__submenu--classic">
                <ul class="kt-menu__subnav">
                    <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                        <a href="{{ url('advance_report')}}" class="kt-menu__link">
                            <!-- <i class="kt-menu__link-icon flaticon2-architecture-and-city"></i> -->
                            <span class="kt-menu__link-text">Advance Reports </span>
                        </a>
                    </li>
                    <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                        <a href="{{ url('reconcilation')}}" class="kt-menu__link">
                            <!-- <i class="kt-menu__link-icon flaticon2-architecture-and-city"></i> -->
                            <span class="kt-menu__link-text">Reconcilation </span>
                        </a>
                    </li> 
                    <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                        <a href="{{ url('timeline')}}" class="kt-menu__link">
                            <!-- <i class="kt-menu__link-icon flaticon2-architecture-and-city"></i> -->
                            <span class="kt-menu__link-text">Timeline Report  </span>
                        </a>
                    </li>                   
                </ul>
            </div>
        </li>
        <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item<?php if(Request::segment(1)=="andriod_ios") echo "--active";  else echo"--rel"; ?>" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                <span class="kt-menu__link-text">Self Survey</span>
                <i class="kt-menu__hor-arrow la la-angle-down"></i>
                <i class="kt-menu__ver-arrow la la-angle-right"></i>
            </a>
            <div class="kt-menu__submenu kt-menu__submenu--classic">
                <ul class="kt-menu__subnav">
                    <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                        <a href="{{ url('andriod_ios')}}" class="kt-menu__link">
                            <!-- <i class="kt-menu__link-icon flaticon2-architecture-and-city"></i> -->
                            <span class="kt-menu__link-text">Android / IOS APP  </span>
                        </a>
                    </li>                   
                </ul>
            </div>
        </li>
        <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item<?php if(Request::segment(1)=="create_project") echo "--active";  else echo"--rel"; ?>" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                <span class="kt-menu__link-text">Upload </span>
                <i class="kt-menu__hor-arrow la la-angle-down"></i>
                <i class="kt-menu__ver-arrow la la-angle-right"></i>
            </a>
            <div class="kt-menu__submenu kt-menu__submenu--classic">
                <ul class="kt-menu__subnav">
                    <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                        <a href="{{ url('create_project')}}" class="kt-menu__link">
                            <!-- <i class="kt-menu__link-icon flaticon2-architecture-and-city"></i> -->
                            <span class="kt-menu__link-text">Create Project </span>
                        </a>
                    </li>                   
                </ul>
            </div>
        </li>
        <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item<?php if(Request::segment(1)=="create_project_goods" || Request::segment(1)=="andriod_ios_goods" ) echo "--active";  else echo"--rel"; ?>" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
            <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                <span class="kt-menu__link-text">Finshed Goods  </span>
                <i class="kt-menu__hor-arrow la la-angle-down"></i>
                <i class="kt-menu__ver-arrow la la-angle-right"></i>
            </a>
            <div class="kt-menu__submenu kt-menu__submenu--classic">
                <ul class="kt-menu__subnav">
                    <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                        <a href="{{ url('create_project_goods')}}" class="kt-menu__link">
                            <!-- <i class="kt-menu__link-icon flaticon2-architecture-and-city"></i> -->
                            <span class="kt-menu__link-text">Create Project </span>
                        </a>
                    </li> 
                    <li class="kt-menu__item  kt-menu__item--submenu" data-ktmenu-submenu-toggle="hover" aria-haspopup="true">
                        <a href="{{ url('andriod_ios_goods')}}" class="kt-menu__link">
                            <!-- <i class="kt-menu__link-icon flaticon2-architecture-and-city"></i> -->
                            <span class="kt-menu__link-text">Android / IOS APP  </span>
                        </a>
                    </li>                   
                </ul>
            </div>
        </li>
        @endif
    </ul>
</div>
</div>
<!-- end: Header Menu -->       
</div>
</div>
</div>
<!-- end:: Header -->