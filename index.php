<?php
if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
    $uri = 'https://';
} else {
    $uri = 'http://';
}
$uri .= $_SERVER['HTTP_HOST'];
session_start();

if( !isset( $_SESSION['username'] ) ){
    header('Location: '.$uri.'/login.php');
}else {
    header('Location: '.$uri.'/homepage.php');
}
?>
