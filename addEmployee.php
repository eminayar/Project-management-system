<head>
    <title>Add Employee </title>
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
if( !isset($_POST['id'] ) ) {
?>
    <div align="center">
        <div class="login">
            <form action="" method="post">
                <div class="row">
                    <div class="col-25">
                        ID:
                    </div>
                    <div class="col-75">
                        <input type="text" required="required" name="id" placeholder="2015400216" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        Name:
                    </div>
                    <div class="col-75">
                        <input type="text" required="required" name="name" placeholder="Ali" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        Surname:
                    </div>
                    <div class="col-75">
                        <input type="text" required="required" name="surname" placeholder="Veli" />
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

    $con = new mysqli($servername,$username,"",$dbname);
    $stmt = $con->prepare('SELECT * FROM Employee WHERE id = ?');
    $stmt->bind_param('d', intval($_POST['id']) );
    $stmt->execute();
    $res = $stmt->get_result();
    if( $res->num_rows > 0 ){
        header('Location:'.$uri.'/addEmployee.php?error='."'Employee id exists!'");
        die;
    }
    $stmt->close();
    $stmt = $con->prepare('INSERT INTO Employee VALUES(?,?,?)');
    $stmt->bind_param( 'dss' ,intval($_POST['id']) , $_POST['name'] , $_POST['surname'] );
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();
    $con->close();
    header('Location:'.$uri.'/homepage.php');
}
?>