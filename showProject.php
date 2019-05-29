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
if( ( !isset($_GET['pname']) && !isset($_POST['pname'])) ){
    header('Location:'.$uri.'/homepage.php');
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
$sql = "SELECT * FROM Task WHERE id IN( SELECT taskid FROM Project_Task WHERE pname='".$_GET['pname']."')";
$tasks = $con->query($sql);
?>

<head>
    <title>Project Details</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<div>
    <div>
        <h2 align="center">Project</h2>
        <div class="userRow" >Project Name: <?php echo $res['name']; ?></div>
        <div class="userRow" >Start Date: <?php echo date('d/m/Y', $res['start']); ?></div>
        <div class="userRow" >Finish Date: <?php echo date('d/m/Y', $res['finish']); ?></div>
    </div>
    <?php if( $_SESSION['type'] == 1 ){
        ?>
        <div>
            <h2 align="center">Managers That Are Assigned To This Project</h2>
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
    <?php } ?>
    <div>
        <h2 align="center">Tasks of this project</h2>
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