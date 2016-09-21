<?php

require_once 'lib/gplus-lib/vendor/autoload.php';

const CLIENT_ID='461906695843-i0ljf69b1d7q1g3cc47d37aundurum23.apps.googleusercontent.com';
const CLIENT_SECRET='LaYlj-v6n0Pa9ZJuQu7Yvx9K';
const REDIRECT_URI='http://fbgallery.freevar.com/home.php';
session_start();

$client = new Google_Client();
$client->setClientId(CLIENT_ID);
$client->setClientSecret(CLIENT_SECRET);
$client->setRedirectUri(REDIRECT_URI);
$client->setScopes('email');

$plus= new Google_Service_Plus($client);

if(isset($_GET['code']))
{
    $client->authenticate($_GET['code']);
    $_SESSION['gplus_access_token']=$client->getAccessToken();
    $redirect='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    header('Location:'.filter_var($redirect,FILTER_SANITIZE_URL));
}

if(isset($_SESSION['gplus_access_token']))
{
    $client->setAccessToken($_SESSION['gplus_access_token']);
    $me= $plus->people->get('me');

    $id= $me['id'];
    $name= $me['displayName'];
    $email= $me['emails'][0]['value'];

    $profileImage_URL = $me['image']['url'];
    $profile_URL = $me['url'];
}
else
{
   $authURL = $client->createAuthUrl();
}

?>