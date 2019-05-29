<head>
    <title>Add Project </title>
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
if( $_SESSION['type'] != 1 ){
    echo "You don't have the permission!!";
    header('Location:'.$uri.'/homepage.php');
    die;
}
if( !isset($_POST['name'] ) ) {
?>
    <div align="center">
        <div class="login">
            <form action="" method="post">
                <div class="row">
                    <div class="col-25">
                        Project Name:
                    </div>
                    <div class="col-75">
                        <input type="text" required="required" name="name" placeholder="new awesome project" />
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
                        Estimated Finish Date:
                    </div>
                    <div class="col-75">
                        <input type="text" required="required" name="finish" placeholder="DD/MM/YYYY" />
                    </div>
                </div>
                <div class="row">
                    <input type="submit" value="create!"/>
                </div>
            </form>
        </div>
    </div>
<?php
    if( isset($_GET['error'] ) ){
        echo $_GET['error'];
    }
    die;
}
else{
    $servername='localhost';
    $username='root';
    $dbname='cmpe321';

    list($day,$month,$year) = explode("/" , $_POST['start']);
    $start=strtotime($month.'/'.$day.'/'.$year );
    list($day,$month,$year) = explode("/" , $_POST['finish']);
    $finish=strtotime($month.'/'.$day.'/'.$year );

    if( !$start || !$finish ){
        header( 'Location:'.$uri.'/addProject.php?error='."'Wrong date!'");
        die;
    }

    $con = new mysqli($servername,$username,"",$dbname);
    $stmt = $con->prepare('SELECT * FROM Project WHERE name = ?');
    $stmt->bind_param( 's' , $_POST['name'] );
    $stmt->execute();
    $res = $stmt->get_result();
    if( $res->num_rows > 0 ){
        header( 'Location:'.$uri.'/addProject.php?error='."'project already exists!'");
        die;
    }
    $stmt->close();
    $stmt= $con->prepare( 'INSERT INTO Project VALUES(?,?,?)' );
    $stmt->bind_param( 'sdd' , $_POST['name'] , $start , $finish );
    $stmt->execute();
    $stmt->close();
    $con->close();

    header('Location:'.$uri.'/homepage.php');

}
?>