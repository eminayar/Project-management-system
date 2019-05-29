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
if( isset($_POST['pname']) ){
    $con=new mysqli('localhost' , 'root' , '' , 'cmpe321');
    list($day,$month,$year) = explode("/" , $_POST['start']);
    $start=strtotime($month.'/'.$day.'/'.$year );
    list($day,$month,$year) = explode("/" , $_POST['finish']);
    $finish=strtotime($month.'/'.$day.'/'.$year );
    $sql="UPDATE Project SET start=".$start.",finish=".$finish." WHERE name='".$_POST['pname']."'";
    $res = $con->query($sql);
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
    <title>Update Project</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<div>
    <div class="col-25">
        <h2 align="center">Project</h2>
        <div>
            <form action="" method="post">
                <div class="row">
                    <div class="col-25">
                        Project Name:
                    </div>
                    <div class="col-75">
                        <input type="text" readonly name="pname" placeholder=<?= $res['name'] ?> />
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        Start Date:
                    </div>
                    <div class="col-75">
                        <input type="text" name="start" placeholder=<?php echo date('d/m/Y', $res['start']); ?> />
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        Finish Date:
                    </div>
                    <div class="col-75">
                        <input type="text" name="finish" placeholder=<?php echo date('d/m/Y', $res['finish']); ?> />
                    </div>
                </div>
                <input type="hidden" id="pname" name="pname" value=<?= $res['name'] ?>>
                <div class="row">
                    <input type="submit" value="update!"/>
                </div>
            </form>
        </div>
    </div>
    <div class="col-75">
        <h2 align="center">Manager That Are Assigned To This Project</h2>
        <table id="table">
            <tr>
                <th>#</th>
                <th>Manager Name</th>
                <th>Operation</th>
                <?php
                $counter=0;
                while ($row = $managers->fetch_assoc() ){
                $counter=$counter+1;
                ?>
            <tr>
                <td><?php echo $counter; ?></td>
                <td><?php echo $row['managername']; ?></td>
                <td align="center" width="20%">
                    <a href="deleteAssoc.php?uname=<?php echo $row['managername']; ?>&pname=<?php echo $res['name']; ?>&from=project" >Delete Association</a>
                </td>
            </tr>
            <?php
            }
            ?>
        </table>
        <div>
            <form action="createAssoc.php" method="post">
                <div class="row">
                    <div class="col-25">
                        Manager Name:
                    </div>
                    <div class="col-75">
                        <input type="text" required="required" name="username" placeholder="manager name" />
                    </div>
                </div>
                <input type="hidden" id="pname" name="pname" value=<?= $res['name'] ?>>
                <input type="hidden" id="from" name="from" value="project">
                <div class="row">
                    <input type="submit" value="Add Manager!"/>
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