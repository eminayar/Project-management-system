<?php
session_start();
if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
    $uri = 'https://';
} else {
    $uri = 'http://';
}
$uri .= $_SERVER['HTTP_HOST'];
if( !isset($_SESSION['username'] ) ){
    header('Location:'.$uri.'/login.php');
    die;
}
?>
<div>
    <div class="userRow" > Welcome <?php echo $_SESSION['username']; ?></div>
    <div class="userRow"> <a href='logout.php'>logout</a> </div>
</div>
<?php
$servername='localhost';
$username='root';
$dbname='cmpe321';
$con = new mysqli($servername,$username,"",$dbname);
if( $con->connect_error ){
    die("connection error: ".$con->connect_error );
}
if( $_SESSION['type'] == 1 ){
?>
    <head>
        <title>Admin Home Page</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body>
        <h1> Managers </h1>
    </body>
<?php
    $cmd="SELECT * FROM User WHERE type= 2 ";
    $res = $con->query($cmd);
?>
    <table id="table" >
        <tr>
            <th>#</th>
            <th>Username</th>
            <th>Password</th>
            <th>Operations</th>
<?php
    $counter=0;
    while( $row = $res->fetch_assoc() ){
        $counter=$counter+1;
?>
        <tr>
            <td><?php echo $counter; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['password']; ?></td>
            <td align="center" width="20%">
                <a href="deleteManager.php?uname=<?php echo $row['username']; ?>" >Delete</a>
                <a href="editManager.php?uname=<?php echo $row['username']; ?>" >Edit</a>
                <a href="showManager.php?uname=<?php echo $row['username']; ?>" >Details</a>
            </td>
        </tr>
<?php } ?>
    </table>
    <div>
        <div class="myButton">
            <form action="addManager.php">
                <input type="submit" value="Add Manager" />
            </form>
        </div>
    </div>
    <h1> Projects </h1>
    <table id="table" >
        <tr>
            <th>#</th>
            <th>Project Name</th>
            <th>Start Date</th>
            <th>Finish Date</th>
            <th>Operations</th>
<?php
    $cmd="SELECT * FROM Project";
    $res = $con->query($cmd);
    $counter = 0;
    while( $row = $res->fetch_assoc() ){
        $counter=$counter+1;
?>
        <tr>
            <td><?php echo $counter; ?> </td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo date('d/m/Y', $row['start']); ?></td>
            <td><?php echo date('d/m/Y', $row['finish']); ?></td>
            <td align="center" width="20%">
                <a href="deleteProject.php?pname=<?php echo $row['name']; ?>" >Delete</a>
                <a href="editProject.php?pname=<?php echo $row['name']; ?>" >Edit</a>
                <a href="showProject.php?pname=<?php echo $row['name']; ?>" >Details</a>
            </td>
        </tr>
<?php    } ?>
    </table>
    <div>
        <div class="myButton">
            <form action="addProject.php">
                <input type="submit" value="Add Project" />
            </form>
        </div>
    </div>
    <h1> Employees </h1>
    <table id="table" >
        <tr>
            <th>#</th>
            <th>Employee id</th>
            <th>Name</th>
            <th>Surname</th>
            <th>Operations</th>
<?php
    $cmd="SELECT * FROM Employee";
    $res = $con->query($cmd);
    $counter=0;
    while( $row = $res->fetch_assoc() ){
        $counter=$counter+1;
?>
        <tr>
            <td><?php echo $counter; ?> </td>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['surname']; ?></td>
            <td align="center" width="20%">
                <a href="deleteEmployee.php?id=<?php echo $row['id']; ?>" >Delete</a>
                <a href="editEmployee.php?id=<?php echo $row['id']; ?>" >Edit</a>
                <a href="showEmployee.php?id=<?php echo $row['id']; ?>" >Details</a>
            </td>
        </tr>
<?php } ?>
    </table>
    <div>
        <div class="myButton">
            <form action="addEmployee.php">
                <input type="submit" value="Add Employee" />
            </form>
        </div>
    </div>
    <div>
        <form action="SP.php?" method="get">
            <div class="row">
                <div class="col-25">
                    Input:
                </div>
                <div class="col-75">
                    <input type="text" required="required" name="query" />
                </div>
            </div>
            <input type="submit" value="Show completed and incompleted projects!"/>
        </form>
    </div>
<?php
}else{
    ?>
    <head>
        <title>Manager Home Page</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <h1>Employees</h1>
    <table id="table" >
        <tr>
            <th>#</th>
            <th>Employee id</th>
            <th>Name</th>
            <th>Surname</th>
            <th>Details</th>
            <?php
            $cmd="SELECT * FROM Employee";
            $res = $con->query($cmd);
            $counter=0;
            while( $row = $res->fetch_assoc() ){
            $counter=$counter+1;
            ?>
        <tr>
            <td><?php echo $counter; ?> </td>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['surname']; ?></td>
            <td align="center" width="20%">
                <a href="showEmployee.php?id=<?php echo $row['id']; ?>" >Details</a>
            </td>
        </tr>
        <?php } ?>
    </table>
    <h1>My Projects</h1>
    <table id="table" >
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Start Date</th>
            <th>Finish Date</th>
            <th>Details</th>
            <?php
            $cmd="SELECT * FROM Project WHERE name IN( SELECT pname FROM Manager_Project WHERE managername='".$_SESSION['username']."')";
            $res = $con->query($cmd);
            $counter=0;
            $anyProject=$res->num_rows;
            while( $row = $res->fetch_assoc() ){
            $counter=$counter+1;
            ?>
        <tr>
            <td><?php echo $counter; ?> </td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo date('d/m/Y', $row['start']); ?></td>
            <td><?php echo date('d/m/Y', $row['finish']); ?></td>
            <td align="center" width="20%">
                <a href="showProject.php?pname=<?php echo $row['name']; ?>" >Details</a>
            </td>
        </tr>
        <?php } ?>
    </table>
    <h1>Tasks</h1>
    <table id="table" >
        <tr>
            <th>#</th>
            <th>Project</th>
            <th>id</th>
            <th>Name</th>
            <th>Start Date</th>
            <th>Finish Date</th>
            <th>Operations</th>
            <?php
            $cmd="SELECT * FROM Task WHERE id IN( SELECT taskid FROM Project_Task WHERE pname IN( SELECT pname FROM Manager_Project WHERE managername='".$_SESSION['username']."' ) )";
            $res = $con->query($cmd);
            $counter=0;

            while( $row = $res->fetch_assoc() ){
            $counter=$counter+1;
            ?>
        <tr>
            <td><?php echo $counter; ?> </td>
            <?php
            $cmd="SELECT pname FROM Project_Task WHERE taskid=".$row['id'];
            $pname=$con->query($cmd);
            $pname=$pname->fetch_assoc()['pname'];
            ?>
            <td><?php echo $pname; ?></td>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo date('d/m/Y', $row['start']); ?></td>
            <td><?php echo date('d/m/Y', $row['finish']); ?></td>
            <td align="center" width="20%">
                <a href="deleteTask.php?id=<?php echo $row['id']; ?>" >Delete</a>
                <a href="editTask.php?id=<?php echo $row['id']; ?>" >Edit</a>
                <a href="showTask.php?id=<?php echo $row['id']; ?>" >Details</a>
            </td>
        </tr>
        <?php } ?>
    </table>
    <?php
    if( $anyProject ) {
        ?>
        <div>
            <div class="myButton">
                <form action="createTask.php">
                    <input type="submit" value="Create Task"/>
                </form>
            </div>
        </div>
        <?php
    }
    ?>
    <div align="center">
        <form action="SP.php?" method="get">
            <div class="row">
                <div class="col-25">
                    Input:
                </div>
                <div class="col-75">
                    <input type="text" readonly name="query" placeholder=<?= $_SESSION['username']?>>
                </div>
            </div>
            <input type="hidden" name="query" id="query" value=<?= $_SESSION['username']?> >
            <input type="submit" value="Show completed and incompleted projects!"/>
        </form>
    </div>
<?php
}
?>
