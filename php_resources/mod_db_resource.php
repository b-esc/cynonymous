<?PHP include("utilities.php");?>

<?PHP
            

            $tarea = $_POST['tarea'];
            $name = $_POST['name'];
            $board = $_POST['bdnum'];
            $date = date('Y/m/d H:i:s');

            $DBH = connect_DB();

            
            try{
                $query = "INSERT INTO ".$posttable." (board,name,content) VALUES (:board, :name, :tarea )";
                $STH = $DBH->prepare($query);
                $STH -> execute(["board" => $board, "name" => $name, "tarea" => $tarea]);
            }
            catch(PDOException $e) {
                echo $e->getMessage();
            }
            
            $DBH = null;
            header('location:'.$_SERVER['HTTP_REFERER']);
        ?>