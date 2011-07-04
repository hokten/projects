<?php
   include("include/session.php");
   $time  = date('Y-m-d H:i:s');
   $username = $session->username;
   $videoid = $_REQUEST["videoid"];
   $comment = $_REQUEST["comment"];
   $lastcommentid = $_REQUEST["lastcommentid"];
   $sql = "SELECT * FROM comments WHERE video_id = $videoid";
   $results = mysql_query($sql);
   if(mysql_numrows($results) >= 5) {
      $res["message"] = "Comments Closed";
      $res["addedcomments"]="";
   }
   else {
      $sql = "INSERT INTO comments (video_id, username, comment, time) VALUES($videoid, '$username', '$comment','$time')";
      mysql_query($sql);

      if($lastcommentid == -2) {
         $res["message"]="";
         $res["addedcomments"]=$database->getComments($videoid);
      }
      else {
         $res["message"]="";
         $res["addedcomments"]=$database->getComments($videoid,$lastcommentid);
      }
   }
   echo json_encode($res);



