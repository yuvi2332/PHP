<?php


#1. start the session


#2. Include Autoload Files
include_once 'lib/FBSDK/vendor/autoload.php';

#3. Create Facebook Object
$fb= new \Facebook\Facebook([
    'app_id'=>'1073810259354691',
    'app_secret'=>'73220aef7a010363f9f8dd10852d14b8',
    'default_graph_version'=>'v2.5',
]);




?>