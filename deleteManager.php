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
if( $_SESSION['type'] != 1 || ( !isset($_GET['uname']) && !isset($_POST['username'])) ){
    header('Location:'.$uri.'/homepage.php');
    die;
}
if( isset($_POST['username']) && isset($_POST['isDelete']) ){
    $con=new mysqli('localhost' , 'root' , '' , 'cmpe321');
    $sql="DELETE FROM User WHERE username='".$_POST['username']."' AND type=2";
    $con->query($sql);
    $sql="DELETE FROM Manager_Project WHERE managername='".$_POST['username']."'";
    $con->query($sql);
    $sql="DELETE FROM NumProjects WHERE username='".$_POST['username']."'";
    $con->query($sql);
    $con->close();
    header('Location:'.$uri.'/homepage.php');
    die;
}
$con = new mysqli('localhost' , 'root' , '' , 'cmpe321');
$sql = "SELECT * FROM User WHERE type=2 AND username="."'".$_GET['uname']."'";
$res = $con->query($sql);
if($res->num_rows == 0 ){
    $con->close();
    header('Location:'.$uri.'/homepage.php');
    die;
}
$res = $res->fetch_assoc();
$sql = "SELECT pname FROM Manager_Project WHERE managername='".$res['username']."'";
$projects = $con->query( $sql );
?>

<head>
    <title>Delete Manager</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<div>
    <div class="col-25">
        <h2 align="center">Manager</h2>
        <div class="userRow" >User Name: <?php echo $res['username']; ?></div>
        <div class="userRow" >Password: <?php echo $res['password']; ?></div>
    </div>
    <div class="col-75">
        <h2 align="center">Manager's Projects</h2>
        <table id="table">
            <tr>
                <th>#</th>
                <th>Project Name</th>
            <?php
            $counter=0;
            while ($row = $projects->fetch_assoc() ){
                $counter=$counter+1;
                ?>
                <tr>
                    <td><?php echo $counter; ?></td>
                    <td><?php echo $row['pname']; ?></td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
</div>
<div>
    <div class="col-50">
        <div class="myButton">
            <form action="homepage.php">
                <input type="submit" value="Back" />
            </form>
        </div>
    </div>
    <div class="col-50">
        <div class="myButton">
            <form action="" method="post">
                <input id="isDelete" name="isDelete" type="hidden" value="1">
                <input id="username" name="username" type="hidden" value=<?= $res['username'] ?> >
                <input type="submit" value="Delete!" />
            </form>
        </div>
    </div>
</div>