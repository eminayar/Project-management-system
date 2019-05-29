<?php
session_start();
if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
    $uri = 'https://';
} else {
    $uri = 'http://';
}
$uri .= $_SERVER['HTTP_HOST'];
if( !isset($_SESSION['username'] ) ) {
    header('Location:' . $uri . '/login.php');
    die;
}
if( $_SESSION['type'] != 1 || !isset($_GET['uname']) || !isset($_GET['pname']) || !isset($_GET['from']) ){
    header('Location:'.$uri.'/homepage.php');
    die;
}
$con = new mysqli('localhost' , 'root' , '' , 'cmpe321');
$sql = "DELETE FROM Manager_Project WHERE managername='".$_GET['uname']."' AND pname='".$_GET['pname']."'";
$con->query($sql);
$con->close();

if( $_GET['from'] == 'manager' ){
    header('Location:'.$uri.'/editManager.php?uname='.$_GET['uname']);
    die;
}
if( $_GET['from'] == "project" ){
    header('Location:'.$uri.'/editProject.php?pname='.$_GET['pname']);
    die;
}

?>