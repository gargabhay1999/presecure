<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>Presecure</title>
    <!-- Bootstrap Core CSS -->
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->

    <link href="css/lib/calendar2/semantic.ui.min.css" rel="stylesheet">
    <link href="css/lib/calendar2/pignose.calendar.min.css" rel="stylesheet">
    <link href="css/lib/owl.carousel.min.css" rel="stylesheet" />
    <link href="css/lib/owl.theme.default.min.css" rel="stylesheet" />
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!--  FInding Latitude and Longitude By Given Address Value      -->
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <title>Google Maps Multiple Markers</title>
    <script src="https://maps.google.com/maps/api/js?key=AIzaSyAAxXbF0r42T9gKEK5hvM8drCo2rvTEoSI&sensor=false" type="text/javascript"></script>
    <script src="js/ajaxjquery.min.js" type="text/javascript"></script>
    <script type="text/javascript">

        function show_map_current(){
            var flag=1;
            $.ajax({
                type: 'post',
                url: 'https://presecure.000webhostapp.com/show_map_current.php',
                data: {'variable': flag},
                success: function(result)
                {
                    location_details = JSON.parse(result);
                    //alert(location_details);
                    //num = location_details.length;
                    num = 520;
                    var center_lat=0, center_lng=0;
                    for (i = 0; i < num; i++){
                        center_lat += parseFloat(location_details[i]['latitude']);
                        center_lng += parseFloat(location_details[i]['longitude']);
                    }
                    center_lat = center_lat/num;
                    center_lng = center_lng/num;

                    var map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 10,
                        center: new google.maps.LatLng(center_lat, center_lng),
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    });

                    var infowindow = new google.maps.InfoWindow();

                    var marker, i;

                    var contentString = new Array();
                    for (i = 0; i < num; i++) {  
                        marker = new google.maps.Marker({
                            position: new google.maps.LatLng(parseFloat(location_details[i]['latitude']), parseFloat(location_details[i]['longitude'])),
                            map: map,
                            icon: {
                                path: google.maps.SymbolPath.CIRCLE,
                                scale: 3,
                                strokeColor:"#B40404"
                            }
                        });

                        contentString[i] = '<div>'+
                        '<div>'+
                        '</div>'+
                        '<h3>Details</h3>'+
                        '<div>'+
                        '<table>'+
                        '<tr>'+
                        '<td> <b>'+location_details[i]['address']+
                        '</b></td>'+
                        '</tr>'+
                        
                        '<tr>'+
                        '<td>Latitude'+
                        '</td>'+
                        '<td>'+location_details[i]['latitude']+
                        '</td>'+
                        '</tr>'+
                        '<tr>'+
                        '<td>Longitude'+
                        '</td>'+
                        '</td>'+
                        '<td>'+location_details[i]['longitude']+
                        '</td>'+
                        '</tr>'+
                        '</table>'+
                        '</div>'+
                        '</div>';


                        google.maps.event.addListener(marker, 'click', (function(marker, i) {
                            return function() {
                                infowindow.setContent(contentString[i]);
                                infowindow.open(map, marker);
                            }
                        })(marker, i));
                    }
                }
            });
        }

        function cal_latlong_house_add(){
            var addresses = document.getElementById("house_add").value;
            $.ajax({
                type: 'post',
                url: 'https://presecure.000webhostapp.com/locate.php',
                data: {'variable': addresses},
                success: function(result)
                {
                    location_details = JSON.parse(result);
                    lat = location_details[0][0];
                    long = location_details[0][1];
                    document.getElementById("house_lat").value = lat;
                    document.getElementById("house_long").value = long;
                }
            });
        }

        function show_map_predicted(){
            var search_add=document.getElementById('house_add').value;
            $.ajax({
                type: 'post',
                url: 'https://presecure.000webhostapp.com/show_map_current.php',
                data: {'variable': search_add},
                success: function(result)
                {
                    location_details = JSON.parse(result);
                    //num = location_details.length;
                    num=520;
                    var center_lat=0, center_lng=0;
                    for (i = 0; i < num; i++){
                        center_lat += parseFloat(location_details[i]['latitude']);
                        center_lng += parseFloat(location_details[i]['longitude']);
                    }
                    center_lat = document.getElementById('house_lat').value;
                    center_lng = document.getElementById('house_long').value;
                    var map = new google.maps.Map(document.getElementById('map_copy'), {
                        zoom: 7,
                        center: new google.maps.LatLng(center_lat, center_lng),
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    });

                    var infowindow = new google.maps.InfoWindow();

                    var marker, i;

                    var contentString = new Array();
                    for (i = 0; i < num+1; i++) {
                        if(i!=num)
                        {
                            curr_lat = parseFloat(location_details[i]['latitude']);
                            curr_long = parseFloat(location_details[i]['longitude']);
                            curr_add = location_details[i]['address'];
                            color = "#B40404";
                            scale_value=3;
                        }
                        if(i==num){
                            curr_lat = document.getElementById('house_lat').value;
                            curr_long = document.getElementById('house_long').value;
                            curr_add = document.getElementById('house_add').value;
                            color="#008000";
                            scale_value=5;
                        }
                        marker = new google.maps.Marker({
                            position: new google.maps.LatLng(curr_lat, curr_long),
                            map: map,
                            icon: {
                                path: google.maps.SymbolPath.CIRCLE,
                                scale: scale_value,
                                strokeColor:color
                            }
                        });

                        contentString[i] = '<div>'+
                        '<div>'+
                        '</div>'+
                        '<h3>Details</h3>'+
                        '<div>'+
                        '<table>'+
                        '<tr>'+
                        '<td> <b>'+curr_add+
                        '</b></td>'+
                        '</tr>'+
                        
                        '<tr>'+
                        '<td>Latitude'+
                        '</td>'+
                        '<td>'+curr_lat+
                        '</td>'+
                        '</tr>'+
                        '<tr>'+
                        '<td>Longitude'+
                        '</td>'+
                        '</td>'+
                        '<td>'+curr_long+
                        '</td>'+
                        '</tr>'+
                        '</table>'+
                        '</div>'+
                        '</div>';


                        google.maps.event.addListener(marker, 'click', (function(marker, i) {
                            return function() {
                                infowindow.setContent(contentString[i]);
                                infowindow.open(map, marker);
                            }
                        })(marker, i));
                    }
                }
            });
        }

        function predict_virus(){
            var lat = document.getElementById("latitudepred").value;
            var long = document.getElementById("longitudepred").value;
            var add_acc = document.getElementById("addressaccuracypred").value;
            var num_mosq = document.getElementById("nummosquitospred").value;
            var input_data = new Array();
            input_data[0] = lat;
            input_data[1] = long;
            input_data[2] = add_acc;
            input_data[3] = num_mosq;
            alert("Please wait while our intelligent system predicts your result :)");
            $.ajax({
                type: 'post',
                url: 'predict_virus.php',
                data: {'variable': input_data},
                success: function(result)
                {
                    pred_output = JSON.parse(result);
                    probOfzero = pred_output[0];
                    probOfone = pred_output[1];
                    predictedLabel = pred_output[2];
                    document.getElementById("probofno").value = probOfzero;
                    document.getElementById("probofyes").value = probOfone;
                    document.getElementById("problabel").value = predictedLabel;
                    barplt(probOfone,probOfzero);
                }
            });
        }

        function find_location_details()
        {
            var addresses = document.getElementById("address").value;
            $.ajax({
                type: 'post',
                url: 'https://presecure.000webhostapp.com/locate.php',
                data: {'variable': addresses},
                success: function(result)
                {
                    location_details = JSON.parse(result);
                    lat = location_details[0][0];
                    long = location_details[0][1];
                    document.getElementById("latitude").value = lat;
                    document.getElementById("longitude").value = long;
                }
            });
        }


         function find_location_details_pred()
        {
            var addresses = document.getElementById("addresspred").value;
            $.ajax({
                type: 'post',
                url: 'https://presecure.000webhostapp.com/locate.php',
                data: {'variable': addresses},
                success: function(result)
                {
                    location_details = JSON.parse(result);
                    lat = location_details[0][0];
                    long = location_details[0][1];
                    document.getElementById("latitudepred").value = lat;
                    document.getElementById("longitudepred").value = long;
                }
            });
        }


    </script>


    <style type="text/css">
    @import 'https://fonts.googleapis.com/css?family=Open+Sans';

    html,body {
        width:100%;
        height:100%;
    }
    #myChart {
        width:100%;
        height:100%;
        min-height:250px;
    }
    .zc-ref {
        display: none;
    }
    </style>



    <script src= "https://cdn.zingchart.com/zingchart.min.js"></script>
    <script>
        zingchart.MODULESDIR = "https://cdn.zingchart.com/modules/";
        ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9","ee6b7db5b51705a13dc2339db3edaf6d"];
    </script>

    <script>
        function barplt(yes,no) {
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,

                title:{
                    text:"Probability of Disease present in your locality"
                },
                axisX:{
                    interval: 1
                },
                axisY2:{
                    interlacedColor: "rgba(1,77,101,.2)",
                    gridColor: "rgba(1,77,101,.1)",
                    title: "Probabilty"
                },
                data: [{
                    type: "bar",
                    name: "companies",
                    axisYType: "secondary",
                    color: "#014D65",
                    dataPoints: [
                    { y: yes*100, label: "Yes" },
                    { y: no*100, label: "No" }

                    ]
                }]
            });
            chart.render();

        }
    </script>



    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:** -->
    <!--[if lt IE 9]>
    <script src="https:**oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https:**oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body >
    <!-- Preloader - style you can find in spinners.css -->
  <!--   <div >
        <svg class="circular" viewBox="25 25 50 50">
			<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div> -->
    <!-- Main wrapper  -->
    <div id="main-wrapper">
        <!-- header header  -->
        <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- Logo -->
                <div class="navbar-header">
                    <a class="navbar-brand" >
                        <!-- Logo icon -->
                        <b>PreSecure</b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <!-- <span><img src="images/logo-text.png" alt="homepage" class="dark-logo" /></span> -->
                    </a>
                </div>
                <!-- End Logo -->
                <div class="navbar-collapse">
                    <!-- toggle and nav items -->
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted  " href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                        <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted  " href="javascript:void(0)"><i></i></a> </li>
                        <!-- Messages -->
                        <li> <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i></i></a>
                            <div class="dropdown-menu animated zoomIn">
                                <ul class="mega-dropdown-menu row">


                                    <li class="col-lg-3  m-b-30">
                                        <h4 class="m-b-20">CONTACT US</h4>
                                        <!-- Contact -->
                                        <form>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="exampleInputname1" placeholder="Enter Name"> </div>
                                            <div class="form-group">
                                                <input type="email" class="form-control" placeholder="Enter email"> </div>
                                            <div class="form-group">
                                                <textarea class="form-control" id="exampleTextarea" rows="3" placeholder="Message"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-info">Submit</button>
                                        </form>
                                    </li>
                                    <li class="col-lg-3 col-xlg-3 m-b-30">
                                        <h4 class="m-b-20">List style</h4>
                                        <!-- List style -->
                                        <ul class="list-style-none">
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                        </ul>
                                    </li>
                                    <li class="col-lg-3 col-xlg-3 m-b-30">
                                        <h4 class="m-b-20">List style</h4>
                                        <!-- List style -->
                                        <ul class="list-style-none">
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                        </ul>
                                    </li>
                                    <li class="col-lg-3 col-xlg-3 m-b-30">
                                        <h4 class="m-b-20">List style</h4>
                                        <!-- List style -->
                                        <ul class="list-style-none">
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> This Is Another Link</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- End Messages -->
                    </ul>
                    <!-- User profile and search -->
                    <ul class="navbar-nav my-lg-0">

                        <!-- Search -->
                        <li class="nav-item hidden-sm-down search-box"> <a class="nav-link hidden-sm-down text-muted  " href="javascript:void(0)"><i class="ti-search"></i></a>
                            <form class="app-search">
                                <input type="text" class="form-control" placeholder="Search here"> <a class="srh-btn"><i class="ti-close"></i></a> </form>
                        </li>
                        <!-- Comment -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-bell"></i>
								<div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
							</a>
                            <div class="dropdown-menu dropdown-menu-right mailbox animated zoomIn">
                                <ul>
                                    <li>
                                        <div class="drop-title">Notifications</div>
                                    </li>
                                    <li>
                                        <div class="message-center">
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="btn btn-danger btn-circle m-r-10"><i class="fa fa-link"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>This is title</h5> <span class="mail-desc">Just see the my new admin!</span> <span class="time">9:30 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="btn btn-success btn-circle m-r-10"><i class="ti-calendar"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>This is another title</h5> <span class="mail-desc">Just a reminder that you have event</span> <span class="time">9:10 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="btn btn-info btn-circle m-r-10"><i class="ti-settings"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>This is title</h5> <span class="mail-desc">You can customize this template as you want</span> <span class="time">9:08 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="btn btn-primary btn-circle m-r-10"><i class="ti-user"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>This is another title</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- End Comment -->
                        <!-- Messages -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted  " href="#" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-envelope"></i>
								<div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
							</a>
                            <div class="dropdown-menu dropdown-menu-right mailbox animated zoomIn" aria-labelledby="2">
                                <ul>
                                    <li>
                                        <div class="drop-title">You have 4 new messages</div>
                                    </li>
                                    <li>
                                        <div class="message-center">
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="user-img"> <img src="images/users/5.jpg" alt="user" class="img-circle"> <span class="profile-status online pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Michael Qin</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:30 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="user-img"> <img src="images/users/2.jpg" alt="user" class="img-circle"> <span class="profile-status busy pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>John Doe</h5> <span class="mail-desc">I've sung a song! See you at</span> <span class="time">9:10 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="user-img"> <img src="images/users/3.jpg" alt="user" class="img-circle"> <span class="profile-status away pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Mr. John</h5> <span class="mail-desc">I am a singer!</span> <span class="time">9:08 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="user-img"> <img src="images/users/4.jpg" alt="user" class="img-circle"> <span class="profile-status offline pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Michael Qin</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center" href="javascript:void(0);"> <strong>See all e-Mails</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- End Messages -->
                        <!-- Profile -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="images/users/5.jpg" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                                <ul class="dropdown-user">
                                    <li><a href="#"><i class="ti-user"></i> Profile</a></li>
                                    <li><a href="#"><i class="ti-wallet"></i> Balance</a></li>
                                    <li><a href="#"><i class="ti-email"></i> Inbox</a></li>
                                    <li><a href="#"><i class="ti-settings"></i> Setting</a></li>
                                    <li><a href="#"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- End header header -->
        <!-- End Left Sidebar  -->
        <!-- Page wrapper  -->
        <div>
            <!-- Bread crumb -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Dashboard</h3> </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
            <!-- End Bread crumb -->
            <!-- Container fluid  -->
            <div class="container-fluid">
                <!-- Start Page Content -->
                <div class='row'>
                    <div class="col-md-6">
                        <div class="card p-30">
                            <div class="card-title">
                                <h4>Enter The Encountered Disease In Your Area..</h4>
                                <div class="card-title-right-icon">
                                    <ul>
                                    </ul>
                                </div>
                            </div>
                            <div class="media">
                               <div class="form-validation">
                                    <form class="form-valid"  method="post" action="https://presecure.000webhostapp.com/viruslocation/latlong.php">
                                        <div class="form-group row">
                                            <label class="col-lg-6 col-form-label"  for="val-username">Address <span class="text-danger">*</span></label>
                                            <div class="col-lg-6">
                                                <input type="text" name='address' id='address' class="form-control" placeholder="Enter Address.." onfocusout="find_location_details()">
                                            </div>

                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-6 col-form-label"  for="val-username">Latitude</label>
                                            <div class="col-lg-6">
                                                <input readonly="readonly" name='latitude' id='latitude' class="form-control">
                                            </div>

                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-6 col-form-label"  for="val-username">Longitude</label>
                                            <div class="col-lg-6">
                                                <input readonly="readonly" name='longitude' id='longitude' class="form-control">
                                            </div>

                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-6 col-form-label" for="val-email">Type of Disease Present <span class="text-danger">*</span></label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" id="species" name="species" placeholder="Type of disease..">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-6 col-form-label" for="val-password">Number of Mosquitoes <span class="text-danger">*</span></label>
                                            <div class="col-lg-6">
                                                <input type="Number" min="1" max="10" class="form-control" id="num_mosq" name="num_mosq" placeholder="On scale of 1 to 10..">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-6 col-form-label" for="val-confirm-password">Address Accuracy <span class="text-danger">*</span></label>
                                            <div class="col-lg-6">
                                                <input type="Number" min="1" max="10" class="form-control" id="add_acc" name="add_acc" placeholder="On scale of 1 to 10">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-6 col-form-label" for="val-suggestions">Date <span class="text-danger">*</span></label>
                                            <div class="col-lg-6">
                                                <input type='Date' class="form-control" id="date" name="date" rows="5" placeholder="">
                                            </div>
                                        </div>



                                        <div class="form-group row">
                                            <label class="col-lg-6 col-form-label"><a data-toggle="modal" data-target="#modal-terms" href="#">Terms &amp; Conditions</a> <span class="text-danger">*</span></label>
                                            <div class="col-lg-8">
                                                <label class="css-control css-control-primary css-checkbox" for="val-terms">
                                                    <input type="checkbox" class="css-control-input" id="val-terms" name="val-terms" value="1">
                                                    <span class="css-control-indicator"></span> I agree to the terms
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-6 ml-auto">
                                                <input type="submit" class="btn btn-primary" value="Submit">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-title">
                                <h4>Virus Affected Area </h4>
                            </div>
                            <div class="card-content">
                                <div id="map" style="width: px; height: 600px; margin-top: px;">
                                    <script>
                                        show_map_current(); 
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>



                <div class="row">

                    <div class="col-md-6">

                        <div class="card p-30">
                            <div class="card-title">
                                <h4>See If Your Area Is In Danger..</h4>
                                <div class="card-title-right-icon">
                                    <ul>

                                    </ul>
                                </div>
                            </div>
                            <div class="media">
                               <div class="form-validation">
                                    <form class="form-valid">
                                        <div class="form-group row">
                                            <label class="col-lg-6 col-form-label"  for="val-username">Address <span class="text-danger">*</span></label>
                                            <div class="col-lg-6">
                                                <input type="text" name='addresspred' id='addresspred' class="form-control" placeholder="Enter Address.." onfocusout="find_location_details_pred()">
                                            </div>

                                        </div>


                                        <div class="form-group row">
                                            <label class="col-lg-6 col-form-label"  for="val-username">Latitude</label>
                                            <div class="col-lg-6">
                                                <input readonly="readonly" name='latitudepred' id='latitudepred' class="form-control">
                                            </div>

                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-6 col-form-label"  for="val-username">Longitude</label>
                                            <div class="col-lg-6">
                                                <input readonly="readonly" name='longitudepred' id='longitudepred' class="form-control">
                                            </div>

                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-6 col-form-label" for="val-password">Number of Mosquitoes <span class="text-danger">*</span></label>
                                            <div class="col-lg-6">
                                                <input type="Number" min="1" max="10" class="form-control" id="nummosquitospred" name="nummosquitospred" placeholder="On scale of 1 to 10..">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-6 col-form-label" for="val-confirm-password">Address Accuracy <span class="text-danger">*</span></label>
                                            <div class="col-lg-6">
                                                <input type="number" min="1" max="10" class="form-control" id="addressaccuracypred" name="addressaccuracypred" placeholder="On scale of 1 to 10">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-6 col-form-label"><a data-toggle="modal" data-target="#modal-terms" href="#">Terms &amp; Conditions</a> <span class="text-danger">*</span></label>
                                            <div class="col-lg-8">
                                                <label class="css-control css-control-primary css-checkbox" for="val-terms">
                                                    <input type="checkbox" class="css-control-input" id="val-terms" name="val-terms" value="1">
                                                    <span class="css-control-indicator"></span> I agree to the terms
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-lg-6">
                                                <input id="probofno" name="probofno" value="" hidden>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-lg-6">
                                                <input id="probofyes" name="probofyes" value="" hidden>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-lg-6">
                                                <input id="problabel" name="problabel" value="" hidden>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-lg-6 ml-auto">
                                                <input type='button' value="Predict" onclick="predict_virus();" class="btn btn-primary">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="col-lg-6">    
                    <div class="col-lg-14">
                        <div class="card">
                            <div class="card-title">
                                <h4>Echart-presecure</h4>
                                <h6>Enter details for area prediction to see graph.</h6>
                            </div>
                            <div id="chartContainer" style="height: 300px; width: 100%;"></div>
                            <script src="js/canvasjs.min.js"></script>

                        </div>
                    </div>

                    <div class="col-lg-14">
                        <!-- <div class="card">
                            <div class="card-title">
                                <h4>Check If Your Family Is Safe</h4>
                            </div>
                            <div id='myChart'><a class="zc-ref" href="https://www.zingchart.com/">Charts by ZingChart</a></div>

                        </div> -->

                        <div class="card">
                            <div class="card-title">
                                <h4>Check If Your Family Is Safe </h4>
                                <form class="form-valid">
                                        <div class="form-group row">
                                            <label class="col-lg-6 col-form-label"  for="val-username">Address <span class="text-danger">*</span></label>
                                            <div class="col-lg-6">
                                                <input type="text" name='house_add' id='house_add' class="form-control" placeholder="Enter Address.." onfocusout="cal_latlong_house_add();">
                                                <input id="house_lat" hidden>
                                                <input id="house_long" hidden>

                                            </div>
                                        </div>
                                         <div class="form-group row">
                                            <label class="col-lg-6 col-form-label"><a data-toggle="modal" data-target="#modal-terms" href="#">Terms &amp; Conditions</a> <span class="text-danger">*</span></label>
                                            <div class="col-lg-8">
                                                <label class="css-control css-control-primary css-checkbox" for="val-terms">
                                                    <input type="checkbox" class="css-control-input" id="val-terms" name="val-terms" value="1">
                                                    <span class="css-control-indicator"></span> I agree to the terms
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-6 ml-auto">
                                                <input type='button' value="See the Result" onclick="show_map_predicted();" class="btn btn-primary">
                                            </div>
                                        </div>
                                </form>
                            <!-- </div> -->
                            <div class="card-content">
                                <div id="map_copy" style="width: px; height: 200px; margin-top: px;">
                                    <script>
                                        show_map_current(); 
                                    </script>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
                <!-- End PAge Content -->
            </div>
            <!-- End Container fluid  -->
            <!-- footer -->
            <footer class="footer"> © 2018 All rights reserved. Template designed by <a href='#'>KNIGHT_RIDERS</a> </footer>
            <!-- End footer -->
        </div>
        <!-- End Page wrapper  -->
    </div>
    <!-- End Wrapper -->
    <!-- All Jquery -->




    <script src="js/lib/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Menu sidebar -->
    <script src="js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <!--Custom JavaScript -->


    <!-- Echart -->
    <script src="js/lib/echart/echarts.js"></script>
    <script src="js/lib/echart/echarts-init.js"></script>
    <!--Custom JavaScript -->
    <script src="js/custom.min.js"></script>


    <!-- Amchart -->
     <!-- <script src="js/lib/morris-chart/raphael-min.js"></script>
    <script src="js/lib/morris-chart/morris.js"></script>
    <script src="js/lib/morris-chart/dashboard1-init.js"></script> -->


	<script src="js/lib/calendar-2/moment.latest.min.js"></script>
    <!-- scripit init-->
    <script src="js/lib/calendar-2/semantic.ui.min.js"></script>
    <!-- scripit init-->
    <script src="js/lib/calendar-2/prism.min.js"></script>
    <!-- scripit init-->
    <script src="js/lib/calendar-2/pignose.calendar.min.js"></script>
    <!-- scripit init-->
    <script src="js/lib/calendar-2/pignose.init.js"></script>

    <script src="js/lib/owl-carousel/owl.carousel.min.js"></script>
    <script src="js/lib/owl-carousel/owl.carousel-init.js"></script>
    <script src="js/scripts.js"></script>
    <!-- scripit init-->

   <!--  <script src="js/lib/gmap/gmapApi.js"></script>
    <script src="js/lib/gmap/gmaps.js"></script>
    <script src="js/lib/gmap/gmap.init.js"></script> -->
    <script src="js/lib/echart/echarts.js"></script>
    <script src="js/lib/echart/echarts-init.js"></script>

    <script src="js/custom.min.js"></script>






</body>

</html>
