<?php

/**
 * Created by PhpStorm.
 * User: TechnoRoots
 * Date: 13-09-2016
 * Time: 04:40 PM
 */

#1. start the session
    session_start();
#2. Include Autoload Files
    include('lib/FBSDK/vendor/autoload.php');
#3. Create Facebook Object
    $fb= new \Facebook\Facebook([
        'app_id'=>'1073810259354691',
        'app_secret'=>'73220aef7a010363f9f8dd10852d14b8',
        'default_graph_version'=>'v2.5',
    ]);

    $redirect="http://localhost:8080/Facebook_New/loginconfirm.php";

# Base Code

    $helper= $fb->getRedirectLoginHelper();


    try{
        $access_token = $helper->getAccessToken();
    }catch(Facebook\Exceptions\FacebookResponseException $e){

        echo 'Graph Returned an Error:'.$e->getMessage();

    }catch(Facebook\Exceptions\FacebookSDKException $e){

        echo 'Facebook SDK returned Error:'.$e->getMessage();
    }

    if(!isset($access_token)){

        $permission=['email','user_photos'];
        $loginurl=$helper->getLoginUrl($redirect,$permission);

        echo "<script>window.location='".$loginurl."'</script>";
    }


?>