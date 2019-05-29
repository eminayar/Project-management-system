<?php
    $con = new mysqli('localhost' , 'root' , '' , 'cmpe321');
    $sql="
    CREATE TRIGGER `trig2` AFTER INSERT ON `Project`
        FOR EACH ROW BEGIN
            SELECT username INTO @uname FROM NumProjects ORDER BY num LIMIT 1;
            INSERT INTO Manager_Project VALUES(@uname,new.name);
            UPDATE NumProjects SET num=num+1 WHERE username=@uname;
        END;
    ";
    $con->query($sql);
    $sql="
    CREATE TRIGGER `trig1` AFTER DELETE ON `Employee`
        FOR EACH ROW DELETE FROM Task_Employee WHERE employeeid=old.id
    ";
    $con->query($sql);
    $sql="
    CREATE TRIGGER `project counter decrease` AFTER DELETE ON `Manager_Project`
        FOR EACH ROW UPDATE NumProjects SET num=num-1 WHERE username=old.managername
    ";
    $con->query($sql);
?>