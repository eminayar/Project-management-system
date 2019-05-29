<head>
    <title>Add Manager</title>
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
if( !isset($_POST['uname'] ) ) {
?>
    <div align="center">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <div class="login">
            <form action="" method="post">
                <div class="row">
                    <div class="col-25">
                        Username:
                    </div>
                    <div class="col-75">
                        <input type="text" required="required" name="uname" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        Password:
                    </div>
                    <div class="col-75">
                        <input type="text" required="required" name="pass" />
                    </div>
                </div>
                <div class="row">
                    <input type="submit" value="create!"/>
                </div>
            </form>
        </div>
    </div>
<?php
    if( isset($_GET['error']) ){
        echo "User already exists!!";
    }
    die;
}else {
    $servername='localhost';
    $username='root';
    $dbname='cmpe321';

    $con = new mysqli($servername,$username,"",$dbname);
    $stmt = $con->prepare('SELECT * FROM User WHERE username =?' );
    $stmt->bind_param( 's' , $_POST['uname'] );
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();

    if( $res->num_rows > 0 ){
        header( 'Location:'.$uri.'/addManager.php?error=1');
        die;
    }

    $stmt = $con->prepare( 'INSERT INTO User VALUES(?,?,2)' );
    $stmt->bind_param( 'ss' , $_POST['uname'] , $_POST['pass']);
    $stmt->execute();
    $stmt->get_result();
    $stmt->close();
    $stmt = $con->prepare("INSERT INTO NumProjects VALUES (?,0)");
    $stmt->bind_param( 's' , $_POST['uname'] );
    $stmt->execute();
    $stmt->get_result();
    $stmt->close();
    $con->close();
    header('Location:'.$uri.'/homepage.php');
}
?>

CREATE TRIGGER `project counter` AFTER INSERT ON `Manager_Project`
FOR EACH ROW UPDATE NumProjects SET num=num+1 WHERE username=new.managername
