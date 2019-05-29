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
if( (!isset($_POST['username']) || !isset($_POST['pname'])) && (!isset($_POST['taskid']) || !isset($_POST['employeeid'])) ){
    header('Location:'.$uri.'/homepage.php');
    die;
}
$con = new mysqli('localhost', 'root', '', 'cmpe321');
if( isset($_POST['username']) && isset($_POST['pname']) ) {
    $sql = "SELECT * FROM Project WHERE name='" . $_POST['pname'] . "'";
    $res = $con->query($sql);
    $sql = "SELECT * FROM User WHERE username='" . $_POST['username'] . "' AND type=2";
    $res2 = $con->query($sql);
    if ($res->num_rows > 0 && $res2->num_rows > 0) {
        $sql = "SELECT * FROM Manager_Project WHERE managername='" . $_POST['username'] . "' AND pname='" . $_POST['pname'] . "'";
        $res = $con->query($sql);
        if ($res->num_rows == 0) {
            $sql = "INSERT INTO Manager_Project VALUES('" . $_POST['username'] . "','" . $_POST['pname'] . "')";
            $res = $con->query($sql);
            $sql = "UPDATE NumProjects SET num=num+1 WHERE username='".$_POST['username']."'";
            $res = $con->query($sql);
        }
    }
    $con->close();
    if ($_POST['from'] == "manager") {
        header('Location:' . $uri . '/editManager.php?uname=' . $_POST['username']);
        die;
    }
    if ($_POST['from'] == "project") {
        header('Location:' . $uri . '/editProject.php?pname=' . $_POST['pname']);
        die;
    }
    die;
}
if( isset($_POST['employeeid']) && isset($_POST['taskid']) ){
    $sql = "SELECT * FROM Employee WHERE id=" . $_POST['employeeid'];
    $theEmployee = $con->query($sql);
    $sql = "SELECT * FROM Task WHERE id=" . $_POST['taskid'];
    $theTask = $con->query($sql);
    if( $theTask->num_rows > 0 && $theEmployee->num_rows > 0 ) {
        $sql="SELECT * FROM Task WHERE id IN( SELECT taskid FROM Task_Employee WHERE employeeid=".$_POST['employeeid'].") ORDER BY Task.start";
        $res = $con->query($sql);
        $isOK=1;
        $theTask=$theTask->fetch_assoc();
        while( $row = $res->fetch_assoc() ){
            if ( !( ($row['finish'] < $theTask['start']) || ($row['start'] > $theTask['finish']) ) ){
                $isOK=0;
            }
        }
        if( $isOK ){
            $sql = "INSERT INTO Task_Employee VALUES('".$_POST['taskid']."','".$_POST['employeeid']."')";
            $con->query($sql);
        }
    }
    $con->close();
    if ($_POST['from'] == "task") {
        header('Location:' . $uri . '/editTask.php?id=' . $_POST['taskid']);
        die;
    }
    die;
}
?>