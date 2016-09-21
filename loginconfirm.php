<?php

/**
 * Created by PhpStorm.
 * User: TechnoRoots
 * Date: 13-09-2016
 * Time: 04:40 PM
 */



session_start();

include_once 'fb_sdk_config.php';



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


    if($_GET['error']=="access_denied")
    {
        echo "<script>alert('please grant the permission');document.location='index.php'</script>";
    }


}
else
{
    $fb->setDefaultAccessToken($access_token);
    $response= $fb->get('/me?fields=email,name');

    $usernode = $response->getGraphUser();



   // echo 'Name: '.$usernode->getName().'</br>';
   // echo 'User Id: '.$usernode->getId().'</br>';
   // echo 'Email: '.$usernode->getEmail().'</br>';

    $name=$usernode->getName();
    $userid=$usernode->getId();
    $email=$usernode->getEmail();

    $_SESSION['username']=$name;
    $_SESSION['userid']=$userid;
    $_SESSION['email']=$email;
    $_SESSION['access_token']=(string)$access_token;





    if(isset($_SESSION['username'],$_SESSION['userid'],$_SESSION['email']))
        {
            echo "<script>document.location='home.php'</script>";
        }

}

?>

