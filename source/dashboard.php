<?php 
if(empty($USER->username)){
	header( "refresh: 0; url=../../login/index.php" );		//redirect to http://localhost/moodle/login/index.php
	exit(0);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Progressive Report</title>

    <!-- Bootstrap Core CSS -->
    <link href="./source/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="./source/css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="./source/css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="./source/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link href="./source/css/style.css" rel="stylesheet" type="text/css">

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="./">Progressive Report</a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> <b class="caret"></b></a>
                    <ul class="dropdown-menu message-dropdown">
                        <li class="message-preview">
                            <a href="#">
                                <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object" src="http://placehold.it/50x50" alt="">
                                    </span>
                                    <div class="media-body">
                                        <h5 class="media-heading"><strong><?php echo $USER->username;?></strong>
                                        </h5>
                                        <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur...</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="message-preview">
                            <a href="#">
                                <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object" src="http://placehold.it/50x50" alt="">
                                    </span>
                                    <div class="media-body">
                                        <h5 class="media-heading"><strong>John Smith</strong>
                                        </h5>
                                        <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur...</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="message-preview">
                            <a href="#">
                                <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object" src="http://placehold.it/50x50" alt="">
                                    </span>
                                    <div class="media-body">
                                        <h5 class="media-heading"><strong>John Smith</strong>
                                        </h5>
                                        <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur...</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="message-footer">
                            <a href="#">Read All New Messages</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <b class="caret"></b></a>
                    <ul class="dropdown-menu alert-dropdown">
                        <li>
                            <a href="#">Alert Name <span class="label label-default">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-primary">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-success">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-info">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-warning">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-danger">Alert Badge</span></a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">View All</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $USER->username;?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#"><i class="fa fa-fw fa-user"></i> Profile</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                	<li class="menu-control active">
		            	<a href="javascript:void(0);" id="assignment-btn"><i class="fa fa-fw fa-edit"></i> Assignment <?php if(is_siteadmin() || user_has_role_assignment($USER->id,3)){echo "<span class='badge' id='asssignment-badge'></span>";}?></a>
		            </li>
                    <li class="menu-control">
		                <a href="javascript:void(0);" id="progressive-btn"><i class="fa fa-fw fa-graduation-cap"></i> Progressive</a>
		            </li>
                    <?php  
                        if(is_siteadmin() || user_has_role_assignment($USER->id,3))     //if user is admin or teacher then add this menu
                        {   //start if
                    ?>
		            <li class="menu-control">
		                <a href="javascript:void(0);" id="rating-btn"><i class="fa fa-fw fa-trophy"></i> Rating</a>
		            </li>
		            <li class="menu-control">
		                <a href="javascript:void(0);"><i class="fa fa-fw fa-table"></i> Item Difficulty</a>
		            </li>
		            <li class="menu-control">
		                <a href="javascript:void(0);"><i class="fa fa-fw fa-flask"></i> Experiment Logs</a>
		            </li>
		            <li class="menu-control">
		                <a href="javascript:void(0);"><i class="fa fa-fw fa-users"></i> Login Logs</a>
		            </li>
                    <?php 
                        }   //end if
                    ?>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">
    
                <div class="row">
                    <div class="col-lg-12">
                    	<!--content load form ajax-->
                        <div id="content"></div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="./source/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="./source/js/bootstrap.min.js"></script>

    <!-- Morris Charts JavaScript -->
	<script src="./source/js/plugins/morris/morris.mins.js"></script>

    <!--UX core Javascript-->
    <script src="./source/js/dashboard.js"></script>

</body>

</html>
