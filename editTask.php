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
if( $_SESSION['type'] != 2 ){
    header('Location:'.$uri.'/homepage.php');
    die;
}
$con = new mysqli('localhost' , 'root' , '' , 'cmpe321');
if( isset($_POST['id']) && isset($_POST['name']) && isset($_POST['start'] ) && isset($_POST['finish'] ) ){
    list($day,$month,$year) = explode("/" , $_POST['start']);
    $start=strtotime($month.'/'.$day.'/'.$year );
    list($day,$month,$year) = explode("/" , $_POST['finish']);
    $finish=strtotime($month.'/'.$day.'/'.$year );
    $sql="UPDATE Task SET name='".$_POST['name']."',start='".$start."',finish='".$finish."' WHERE id=".$_POST['id'];
    $con->query($sql);
    $con->close();
    header('Location:'.$uri.'/editTask.php?id='.$_POST['id'] );
    die;
}
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
    <title>Update Task</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<div>
    <div class="col-25">
        <h2 align="center">Task</h2>
        <div>
            <form action="" method="post">
                <div class="row">
                    <div class="col-25">
                        Id:
                    </div>
                    <div class="col-75">
                        <input type="text" readonly name="id" placeholder=<?= $res['id'] ?>  />
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
                        Start Date:
                    </div>
                    <div class="col-75">
                        <input type="text" required="required" name="start" placeholder=<?php echo date('d/m/Y', $res['start']); ?> />
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        Finish Date:
                    </div>
                    <div class="col-75">
                        <input type="text" required="required" name="finish" placeholder=<?php echo date('d/m/Y', $res['finish']); ?> />
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
        <h2>Assign Employee To The Project</h2>
        <div>
            <form action="createAssoc.php" method="post">
                <div class="row">
                    <div class="col-25">
                        Employee id:
                    </div>
                    <div class="col-75">
                        <input type="text" required="required" name="employeeid" placeholder="employee id" />
                    </div>
                </div>
                <input type="hidden" id="taskid" name="taskid" value=<?= $res['id'] ?>>
                <input type="hidden" id="from" name="from" value="task">
                <div class="row">
                    <input type="submit" value="Add Employee!"/>
                </div>
            </form>
        </div>
    </div>
</div>
<div>
    <div class="myButton">
        <form action="homepage.php">
            <input type="submit" value="Back" />
        </form>
    </div>
</div>
