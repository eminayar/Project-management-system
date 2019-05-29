<?php
$con = new mysqli('localhost','root','','cmpe321');
$sql="
    CREATE PROCEDURE getFinished
    (IN mname CHAR(30))
    BEGIN
        SELECT UNIX_TIMESTAMP() into @curtime;
        
        IF mname = 'ALL' THEN
            SELECT * FROM Project WHERE finish<@curtime;
        ELSE
            SELECT * FROM Project WHERE finish<@curtime AND name IN (SELECT pname FROM Manager_Project WHERE managername=mname);
        END IF;
    END
";
$con->query($sql);
echo $con->error;
$sql="
    CREATE PROCEDURE getNonFinished
    (IN mname CHAR(30))
    BEGIN
        SELECT UNIX_TIMESTAMP() into @curtime;
        
        IF mname = 'ALL' THEN
            SELECT * FROM Project WHERE finish>@curtime;
        ELSE
            SELECT * FROM Project WHERE finish>@curtime AND name IN (SELECT pname FROM Manager_Project WHERE managername=mname);
        END IF;
    END
";
$con->query($sql);
$olmasigerek="
    CREATE PROCEDURE getFinished
    (IN mname CHAR(30))
    BEGIN
        SELECT UNIX_TIMESTAMP() into @curtime;

        IF mname = 'ALL' THEN
            SELECT * FROM Project WHERE NOT EXISTS (
                SELECT id FROM Task WHERE Task.finish>@curtime AND EXISTS(
                    SELECT * FROM Project_Task WHERE Project_Task.pname=Project.name AND Project_Task.taskid=Task.id ));
        ELSE
            SELECT * FROM Project WHERE NOT EXISTS (
                SELECT id FROM Task WHERE Task.finish>@curtime AND EXISTS(
                    SELECT * FROM Project_Task WHERE Project_Task.pname=Project.name AND Project_Task.taskid=Task.id
                    )
                ) AND Project.name IN (SELECT pname FROM Manager_Project WHERE Manager_Project.managername=mname);
        END IF;
    END
"
?>