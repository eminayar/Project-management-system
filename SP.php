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
if( !isset($_GET['query']) || ($_SESSION['type'] == 2 && $_GET['query'] != $_SESSION['username'] ) ){
    header('Location:'.$uri.'/homepage.php');
    die;
}

$query=$_GET['query'];
$con = new mysqli('localhost' , 'root' , '' , 'cmpe321');
$sql = "CALL getNonFinished('".$_GET['query']."')";
$nonfinished = $con->query($sql);
$con->close();
$con = new mysqli('localhost' , 'root' , '' , 'cmpe321');
$sql = "CALL getFinished('".$_GET['query']."')";
$finished = $con->query($sql);
$con->close();
?>
<head>
    <title>Stored Procedure</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<?php if( $finished ) { ?>
    <h2 align="center">Finished Projects</h2>
    <table id="table">
        <tr>
            <th>#</th>
            <th>Project Name</th>
            <th>Start Date</th>
            <th>Finish Date</th>
            <?php
            $counter=0;
            while ($row = $finished->fetch_assoc() ){
            $counter=$counter+1;
            ?>
        <tr>
            <td><?php echo $counter; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo date( "d/m/Y" , $row['start']); ?></td>
            <td><?php echo date( "d/m/Y" , $row['finish']); ?></td>
        </tr>
        <?php
    }
}
?>
    </table>
<?php
if( $nonfinished ){ ?>
    <h2 align="center">Non-finished Projects</h2>
    <table id="table">
    <tr>
        <th>#</th>
        <th>Project Name</th>
        <th>Start Date</th>
        <th>Finish Date</th>
        <?php
        $counter = 0;
        while ($row = $nonfinished->fetch_assoc()){
        $counter = $counter + 1;
        ?>
    <tr>
        <td><?php echo $counter; ?></td>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo date("d/m/Y", $row['start']); ?></td>
        <td><?php echo date("d/m/Y", $row['finish']); ?></td>
    </tr>
    <?php
    }
}
    ?>
    </table>


