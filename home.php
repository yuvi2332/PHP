<?php

session_start();

include_once 'fb_sdk_config.php';
include 'download.php';


$response = $fb->get('/me?fields=albums{id,name,picture{url}}', $_SESSION['access_token']);   //,name, photos{images{source}} //

$graphnode = $response->getGraphNode();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Fb Gallery</title>

    <!-- Bootstrap Core CSS -->
    <link href="lib/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="lib/font-awesome-4.6.3/css/font-awesome.min.css">

    <!-- Custom CSS -->
    <link href="lib/css/thumbnail-gallery.css" rel="stylesheet">


</head>

<body style="overflow:auto">

<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#" style="font-size:30px;font-weight:400">Facebook Gallery</a>

        </div>

        <div style="font-weight: 300;font-size: 10px;height: 10px;color: #ffffff;margin-left: 900px;width:400px"><h3><?php echo $_SESSION['username'];?> </h3></div>



    </div>
    <!-- /.container -->
</nav>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <div class="col-lg-12">
            <h1 class="page-header" style="font-size:25px;">Thumbnail Gallery</h1>
        </div>


        <?php

        $data = json_decode($graphnode, true);
        
        foreach ($data['albums'] as $item) {

            echo '<div class="col-lg-3 col-md-4 col-xs-6 thumb">';
            echo '<a class="thumbnail" href="slideshow.php?albumid=' . $item['id'] . '">';
            echo '<img class="img-responsive" src="' . $item['picture']['url'] . '" alt="" style="height:200px;width:250px">';
            echo '<h4 style="margin-left:3px">' . $item['name'] . '</h4>';
            echo '</a>';

            echo '<a  style="margin-left:3px" class="btn btn-danger" href="home.php?albumid=' . $item['id'] . '&name=' . $item['name'] . '" ><span class="glyphicon glyphicon-download-alt"></span></a>';
            echo '<button style="margin-left:20px" class="btn btn-success"><i class="fa fa-google-plus" aria-hidden="true"></i></button>';
            echo '<button style="margin-left:20px" class="btn btn-primary"><span class="glyphicon glyphicon-play"></span></button>';
            echo '<span id="span1" style="font-size: 20px;margin-left: 5px;margin-top: 10px;visibility:hidden" class="glyphicon glyphicon-file"><a>Download</a></span>';
            echo '</div>';
        }
        
        ?>

        <input type="button" name="btndownloadall" value="Download All" class="homebutton" id="btnHome" onClick="<?php header("Location: /download.php"); ?>" />

    </div>

    <hr>


    <!-- Footer -->
    <footer>
        <div class="row">
            <div class="col-lg-12">
                <p>Developed By: Umang Patel</p>
            </div>
        </div>
    </footer>

</div>
<!-- /.container -->

<!-- jQuery -->
<script src="lib/js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="lib/js/bootstrap.min.js"></script>


</body>

</html>
