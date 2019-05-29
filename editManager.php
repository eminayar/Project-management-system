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
if( isset($_POST['username']) && isset($_POST['pass']) ){
    $con=new mysqli('localhost' , 'root' , '' , 'cmpe321');
    $sql="UPDATE User SET password='".$_POST['pass']."' WHERE username='".$_POST['username']."'";
    $con->query($sql);
    $con->close();
    header('Location:'.$uri.'/editManager.php?uname='.$_GET['uname']);
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
    <title>Update Manager</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<div>
    <div class="col-25">
        <h2 align="center">Manager</h2>
        <div>
            <form action="" method="post">
                <div class="row">
                    <div class="col-25">
                        Username:
                    </div>
                    <div class="col-75">
                        <input type="text" readonly name="uname" placeholder=<?= $res['username'] ?> />
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        Password:
                    </div>
                    <div class="col-75">
                        <input type="text" required="required" name="pass" placeholder=<?= $res['password'] ?> />
                    </div>
                </div>
                <input type="hidden" id="username" name="username" value=<?= $res['username'] ?>>
                <div class="row">
                    <input type="submit" value="update!"/>
                </div>
            </form>
        </div>
    </div>
    <div class="col-75">
        <h2 align="center">Manager's Projects</h2>
        <table id="table">
            <tr>
                <th>#</th>
                <th>Project Name</th>
                <th>Operation</th>
                <?php
                $counter=0;
                while ($row = $projects->fetch_assoc() ){
                $counter=$counter+1;
                ?>
            <tr>
                <td><?php echo $counter; ?></td>
                <td><?php echo $row['pname']; ?></td>
                <td align="center" width="20%">
                    <a href="deleteAssoc.php?uname=<?php echo $res['username']; ?>&pname=<?php echo $row['pname']; ?>&from=manager" >Delete Association</a>
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
                        Project Name:
                    </div>
                    <div class="col-75">
                        <input type="text" required="required" name="pname" placeholder="project name" />
                    </div>
                </div>
                <input type="hidden" id="username" name="username" value=<?= $res['username'] ?>>
                <input type="hidden" id="from" name="from" value="manager">
                <div class="row">
                    <input type="submit" value="Add Project!"/>
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