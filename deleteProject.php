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
if( $_SESSION['type'] != 1 || ( !isset($_GET['pname']) && !isset($_POST['pname'])) ){
    header('Location:'.$uri.'/homepage.php');
    die;
}
if( isset($_POST['pname']) && isset($_POST['isDelete']) ){
    $con=new mysqli('localhost' , 'root' , '' , 'cmpe321');
    $sql="DELETE FROM Project WHERE name='".$_POST['pname']."'";
    $con->query($sql);
    $sql="DELETE FROM Manager_Project WHERE pname='".$_POST['pname']."'";
    $con->query($sql);
    $sql="DELETE FROM Project_Task WHERE pname='".$_POST['pname']."'";
    $con->query($sql);
    $con->close();
    header('Location:'.$uri.'/editProject.php?pname='.$_GET['pname']);
    die;
}
$con = new mysqli('localhost' , 'root' , '' , 'cmpe321');
$sql = "SELECT * FROM Project WHERE name="."'".$_GET['pname']."'";
$res = $con->query($sql);
if($res->num_rows == 0 ){
    $con->close();
    header('Location:'.$uri.'/homepage.php');
    die;
}
$res = $res->fetch_assoc();
$sql = "SELECT managername FROM Manager_Project WHERE pname='".$res['name']."'";
$managers = $con->query( $sql );
?>

<head>
    <title>Delete Project</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<div>
    <div class="col-25">
        <h2 align="center">Project</h2>
        <div class="userRow" >Project Name: <?php echo $res['name']; ?></div>
        <div class="userRow" >Start Date: <?php echo date('d/m/Y', $res['start']); ?></div>
        <div class="userRow" >Finish Date: <?php echo date('d/m/Y', $res['finish']); ?></div>
    </div>
    <div class="col-75">
        <h2 align="center">Manager That Are Assigned To This Project</h2>
        <table id="table">
            <tr>
                <th>#</th>
                <th>Manager Name</th>
                <?php
                $counter=0;
                while ($row = $managers->fetch_assoc() ){
                $counter=$counter+1;
                ?>
            <tr>
                <td><?php echo $counter; ?></td>
                <td><?php echo $row['managername']; ?></td>
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
                <input id="pname" name="pname" type="hidden" value=<?= $res['name'] ?> >
                <input type="submit" value="Delete!" />
            </form>
        </div>
    </div>
</div>