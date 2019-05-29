<head>
    <title> Login </title>
</head>

<?php
if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
    $uri = 'https://';
} else {
    $uri = 'http://';
}
$uri .= $_SERVER['HTTP_HOST'];
session_start();
if( isset($_SESSION['username'] ) ){
    header('Location:'.$uri.'/homepage.php');
    die;
}
if( !isset($_POST['uname'] ) ){
    ?>
    <div>
        <link rel="stylesheet" type="text/css" href="styles.css">
        <div class="login">
            <form action="login.php" method="post">
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
                        <input type="password" required="required" name="pass" />
                    </div>
                </div>
                <div class="row">
                    <input type="submit" />
                </div>
            </form>
        </div>
    </div>
    <?php
    if( isset($_GET['error']) ){
        echo "Wrong username or password!";
    }
    die;
}
else{
    $servername='localhost';
    $username='root';
    $dbname='cmpe321';

    $con = new mysqli($servername,$username,"",$dbname);
    if( $con->connect_error ){
        die("connection failed: ".$con->connect_error );
    }
    $uname=$_POST['uname'];
    $pass=$_POST['pass'];
    echo $uname;
    echo $pass;
    $stmt = $con->prepare('SELECT * FROM User WHERE username = ? AND password = ?' );
    $stmt->bind_param('ss',$uname , $pass );
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();
    $con->close();
    if( $res->num_rows != 1 ){
        header('Location: '.$uri.'/login.php?error=1');
        die;
    }else{
        unset($_SESSION['error']);
        $row = $res->fetch_assoc();
        $_SESSION['username']=$row['username'];
        $_SESSION['type']=$row['type'];
        header( 'Location:'.$uri.'/homepage.php');
    }
}
?>