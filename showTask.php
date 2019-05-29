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
$con = new mysqli('localhost' , 'root' , '' , 'cmpe321');
$sql = "SELECT * FROM Task WHERE id="."'".$_GET['id']."'";
$res = $con->query($sql);
if($res->num_rows == 0 ){
    $con->close();
    header('Location:'.$uri.'/homepage.php');
    die;
}
$res = $res->fetch_assoc();
$sql = "SELECT * FROM Employee WHERE id IN (SELECT employeeid FROM Task_Employee WHERE taskid='".$res['id']."')";
$employees = $con->query( $sql );
?>

<head>
    <title>Task Details</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<div>
    <div class="col-25">
        <h2 align="center">Task</h2>
        <div class="userRow" >Task id: <?php echo $res['id']; ?></div>
        <div class="userRow" >Name: <?php echo $res['name']; ?></div>
        <div class="userRow" >Start Date: <?php echo date('d/m/Y', $res['start']); ?></div>
        <div class="userRow" >Finish Date: <?php echo date('d/m/Y', $res['finish']); ?></div>
    </div>
    <div class="col-75">
        <h2 align="center">Employees That Are Assigned To This Task</h2>
        <table id="table">
            <tr>
                <th>#</th>
                <th>Employee Id</th>
                <th>Name</th>
                <th>Surname</th>
                <?php
                $counter=0;
                while ($row = $employees->fetch_assoc() ){
                $counter=$counter+1;
                ?>
            <tr>
                <td><?php echo $counter; ?></td>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['surname']; ?></td>
            </tr>
            <?php
            }
            ?>
        </table>
    </div>
</div>
<div>
    <div class="myButton">
        <form action="homepage.php">
            <input type="submit" value="Back" />
        </form>
    </div>
</div>
