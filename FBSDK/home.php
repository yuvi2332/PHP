<?php

session_start();

include_once 'fb_sdk_config.php';

// $response = $fb->get('/me/albums?fields=id,name,cover_photo', $_SESSION['access_token']);   //

$response = $fb->get('/me?fields=albums{id,name,picture{url}}', $_SESSION['access_token']);   //,name, photos{images{source}} //

//$graphnode = $response->getGraphEdge();

$graphnode = $response->getGraphNode();

//echo $graphnode;


// the below Code Will Download the Images from the Albums whose id is received in Argument

$arrContextOptions = array(                     // Parameters From File_get_content function
    "ssl" => array(
        "verify_peer" => false,
        "verify_peer_name" => false,
    ),
);

$count = 0;
$imgurl = array();

if (isset($_REQUEST["albumid"])) {

    $responseimage = $fb->get('/' . $_REQUEST['albumid'] . '/photos?fields=images', $_SESSION['access_token']);
    $photos = $responseimage->getGraphEdge();

    if (!file_exists("./lib/downloads/" . $_REQUEST['name'])) {

        mkdir("./lib/downloads/" . $_REQUEST['name']);

        foreach ($photos as $photoitem) {
            $count++;
            $imgurl = $photoitem['images'][0]['source'];
            $data = file_get_contents($imgurl, false, stream_context_create($arrContextOptions));
            $fp = fopen('./lib/downloads/' . $_REQUEST['name'] . '/image' . $count . '.jpg', 'w');
            fwrite($fp, $data);
            fclose($fp);
        }


        $rootPath = realpath('./lib/downloads');

// Initialize archive object
        $zip = new ZipArchive();
        $zip->open("./lib/zipfiles/".$_REQUEST['name'].".zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);

// Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file)
        {
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

// Zip archive will be created only after closing object
        $zip->close();

        $path='./lib/downloads/'.$_REQUEST['name'];

        Delete($path);


    }
}


?>

<?php

function Delete($path)
{
    if (is_dir($path) === true)
    {
        $files = array_diff(scandir($path), array('.', '..'));

        foreach ($files as $file)
        {
            Delete(realpath($path) . '/' . $file);
        }

        return rmdir($path);
    }

    else if (is_file($path) === true)
    {
        return unlink($path);
    }

    return false;
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

    <title>Fb Gallery</title>

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="lib/font-awesome-4.6.3/css/font-awesome.min.css">

    <!-- Custom CSS -->
    <link href="lib/css/thumbnail-gallery.css" rel="stylesheet">

</head>

<body>

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
        <!-- Collect the nav links, forms, and other content for toggling -->

        <!-- /.navbar-collapse -->
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
            // echo $item['id']." ".$item['name'];

            // echo "<img  src='".$item['picture']['url']."'/>";


            echo '<div class="col-lg-3 col-md-4 col-xs-6 thumb">';
            echo '<a class="thumbnail" href="slideshow.php?albumid=' . $item['id'] . '">';
            echo '<img class="img-responsive" src="' . $item['picture']['url'] . '" alt="" style="height:200px;width:250px">';
            echo '<h4 style="margin-left:3px">' . $item['name'] . '</h4>';
            echo '</a>';

            echo '<a  style="margin-left:3px" class="btn btn-danger" href="home.php?albumid=' . $item['id'] . '&name=' . $item['name'] . '" ><span class="glyphicon glyphicon-download-alt"></span></a>';
            echo '<button style="margin-left:20px" class="btn btn-success"><i class="fa fa-google-plus" aria-hidden="true"></i></button>';
            echo '<button style="margin-left:20px" class="btn btn-primary"><span class="glyphicon glyphicon-play"></span></button>';

            echo '</div>';

        }

        /*  Below Coding is working

                foreach ($graphnode as $item) {
                  //  echo $item['id'] . " " . $item['name'] . " " . $item['cover_photo']['id'];
                    echo "<br>";

                    $response = $fb->get('/' . $item['id'] . '/photos?fields=images', $_SESSION['access_token']); // api request from all album images
                    $photos = $response->getGraphEdge();

                    //  echo $photos;
                    foreach ($photos as $photoitem) {
                        $imageurl = $photoitem['images'][0]['source'];  // Fetching URL for Each Album Photoes
                        echo "<img id='albumphotoes' src='" . $imageurl . "'>";
                    }
                }
        */
        ?>


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
	