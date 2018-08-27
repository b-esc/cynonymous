<html>
    <head>
        <title> Here are all of the posts </title>
    </head>
    <body>
    <?PHP
        $user="phpaccess";
        $pass="309phpaccess";
        $database="mytest";
        $host = "localhost";
        $table = "mptests";
        $boardnum=$_GET["boardnum"];

        //$tarea = $_POST['tarea'];
        //$name = $_POST['name'];
//        echo phpinfo();

        try {
           
            $DBH = new PDO("mysql:host=$host;dbname=$database", $user, $pass);
        
           
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }

        

        $query = "Select id, name, content from ".$table." WHERE board=".$boardnum." ORDER BY id DESC";
        $STH = $DBH->prepare($query);
        $STH -> execute();
        //$STH = $DBH->query($query);
        $STH->setFetchMode(PDO::FETCH_ASSOC);
        $i = 0;
        while ($row = $STH->fetch()){
            $i++;
            echo "<FIELDSET><LEGEND>". $row['name'] . "</LEGEND>";
            //echo $row['name'] . "<br>";
            echo $row['content'] . "<br><br></FIELDSET>";
        }
        
       // echo $name. "<br>";
        //echo $query. "<br>";
        $DBH = null;
    ?>
    </body>
</html>