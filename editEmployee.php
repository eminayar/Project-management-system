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
if( $_SESSION['type'] != 1 || ( !isset($_GET['id']) && !isset($_POST['id'])) ){
    header('Location:'.$uri.'/homepage.php');
    die;
}
if( isset($_POST['id']) && isset($_POST['name']) && isset($_POST['surname'] ) ){
    $con=new mysqli('localhost' , 'root' , '' , 'cmpe321');
    $sql="UPDATE Employee SET name='".$_POST['name']."',surname='".$_POST['surname']."' WHERE id=".$_POST['id'];
    $con->query($sql);
    header('Location:'.$uri.'/editEmployee.php?id='.$_GET['id'] );
    die;
}
$con = new mysqli('localhost' , 'root' , '' , 'cmpe321');
$sql = "SELECT * FROM Employee WHERE id="."'".$_GET['id']."'";
$res = $con->query($sql);
if($res->num_rows == 0 ){
    $con->close();
    header('Location:'.$uri.'/homepage.php');
    die;
}
$res = $res->fetch_assoc();
$sql = "SELECT * FROM Task WHERE id IN( SELECT taskid FROM Task_Employee WHERE employeeid='".$res['id']."')";
$tasks = $con->query( $sql );
?>

<head>
    <title>Update Employee</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<div>
    <div class="col-25">
        <h2 align="center">Employee</h2>
        <div>
            <form action="" method="post">
                <div class="row">
                    <div class="col-25">
                        Employee id:
                    </div>
                    <div class="col-75">
                        <input type="text" readonly name="id" placeholder=<?= $res['id'] ?> />
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        Name:
                    </div>
                    <div class="col-75">
                        <input type="text" required="required" name="name" placeholder=<?= $res['name'] ?> />
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        Surname:
                    </div>
                    <div class="col-75">
                        <input type="text" required="required" name="surname" placeholder=<?= $res['surname'] ?> />
                    </div>
                </div>
                <input type="hidden" id="id" name="id" value=<?= $res['id'] ?>>
                <div class="row">
                    <input type="submit" value="update!"/>
                </div>
            </form>
        </div>
    </div>
    <div class="col-75">
        <h2 align="center">Tasks That Are Assigned To This Employee</h2>
        <table id="table">
            <tr>
                <th>#</th>
                <th>Task Id</th>
                <th>Task Name</th>
                <th>Start Date</th>
                <th>Finish Date</th>
                <?php
                $counter=0;
                while ($row = $tasks->fetch_assoc() ){
                $counter=$counter+1;
                ?>
            <tr>
                <td><?php echo $counter; ?></td>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo date('d/m/Y' , $row['start'] ); ?></td>
                <td><?php echo date('d/m/Y' , $row['finish'] ); ?></td>
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
