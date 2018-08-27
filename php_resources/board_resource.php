<?PHP
            include("utilities.php");
            $boardnum=1;
            $threadnum=1;

            $DBH=connect_DB();
            $STH;
            
            try { //Creates and prepares queries to be executed (for posts and userinfo)
                $query_for_posts = "Select post_id, title, content, creator_uid from ".$posttable." WHERE board_id=:board AND thread_id=:thread ORDER BY post_id DESC";
                $STH_POST = $DBH->prepare($query_for_posts);
                $query_for_userinfo = "Select username from ".$usertable." WHERE id=:creator_uid";
                $STH_USER = $DBH->prepare($query_for_userinfo);

                //Execute post query
                $STH_POST -> execute(["board" => $boardnum, "thread" => $threadnum]);
            }
            catch(PDOException $e) {
                echo $e->getMessage();
            }
            //tells the query how to return the data --> As an associative array.
            $STH_POST->setFetchMode(PDO::FETCH_ASSOC);
            $STH_USER->setFetchMode(PDO::FETCH_ASSOC);
            $i = 0;
            
            //fetches posts 1 row at a time
            while ($row = $STH_POST->fetch()){
                $i++;
                try { //executes query for user info
                    $STH_USER -> execute(["creator_uid" => $row['creator_uid']]);
                }
                catch(PDOException $e) {
                    echo $e->getMessage();
                }
                $row2 = $STH_USER ->fetch(); // fetches user info with query
                
                echo "<FIELDSET><LEGEND>". $row['title'] ."&nbsp - &nbsp " . $row2["username"] . "</LEGEND>";
                echo $row['content'] . "<br><br></FIELDSET>";
                
            }
            $DBH = null;

            function echo_boardnum() {
                global $boardnum;
                return $boardnum;
                exit();
            }

        ?>