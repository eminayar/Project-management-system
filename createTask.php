<head>
    <title>Create Task</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

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
$cmd="SELECT name FROM Project WHERE name IN( SELECT pname FROM Manager_Project WHERE managername='".$_SESSION['username']."')";
$res = $con->query($cmd);

if( isset($_POST['id'] ) && isset($_POST['name'] ) && isset($_POST['start'] ) && isset($_POST['finish'] ) && isset($_POST['pname'] )){
    list($day,$month,$year) = explode("/" , $_POST['start']);
    $start=strtotime($month.'/'.$day.'/'.$year );
    list($day,$month,$year) = explode("/" , $_POST['finish']);
    $finish=strtotime($month.'/'.$day.'/'.$year );
    $sql="INSERT INTO Task VALUES('".$_POST['id']."','".$_POST['name']."','".$start."','".$finish."')";
    $con->query($sql);
    $sql="INSERT INTO Project_Task VALUES('".$_POST['pname']."','".$_POST['id']."')";
    $con->query($sql);
    $con->close();
    header('Location:'.$uri.'/homepage.php');
    die;
}

?>
<form action="" method="post">
    <div class="row">
        <div class="col-25">
            Task id:
        </div>
        <div class="col-75">
            <input type="text" name="id" placeholder="123" />
        </div>
    </div>
    <div class="row">
        <div class="col-25">
            Task Name:
        </div>
        <div class="col-75">
            <input type="text" required="required" name="name" placeholder="task name" />
        </div>
    </div>
    <div class="row">
        <div class="col-25">
            Start Date:
        </div>
        <div class="col-75">
            <input type="text" required="required" name="start" placeholder="DD/MM/YYYY" />
        </div>
    </div>
    <div class="row">
        <div class="col-25">
            Finish Date:
        </div>
        <div class="col-75">
            <input type="text" required="required" name="finish" placeholder="DD/MM/YYYY" />
        </div>
    </div>
    <select name="pname" required="required">
        <?php
        while( $row = $res->fetch_assoc() ){
            ?>
            <option value="<?= $row['name'] ?>"><?php echo $row['name']; ?> </option>
            <?php
        }
        ?>
    </select>
    <div class="row">
        <input type="submit" value="create!"/>
    </div>
</form>
