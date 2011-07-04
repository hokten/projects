<?
include("include/session.php");
include("dbcon.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
      <title>Ajax Rating System: Create Simple Ajax Rating System using jQuery AJAX and PHP : 99Points.Info</title>
      <script type="text/javascript" src="jquery-1.2.6.min.js"></script>
      <link href="99points.css" rel="stylesheet" type="text/css" />
      <script type="text/javascript">
         // <![CDATA[	
         $(document).ready(function(){	
               $('#loader').hide(); 
               $('#inner').children().click(function(){
                     var a = $(this).attr("id");
                     $.post("rating.php?value="+a, {
                        }, function(response){
                           $('#inner').fadeOut();
                           $('#inner').html(unescape(response));
                           $('#inner').fadeIn();
                           setTimeout("hideMesg();", 2000);
                     });
               });
               $('#commentsubmit').click(function(){
                     var comment = $('#comment').val();
                     var lastcommentid = $('#comments div:last').attr('id');
                     var videoid = $('#videoid').val();
                     if(lastcommentid == 'nocomment') {
                           lastcomment = -2;
                     }
                     else {
                           lastcomment = lastcommentid.substr(7);
                     }
                     $.post("comments.php", {'videoid':videoid,'lastcommentid':lastcomment,'comment':comment},
                     function(data){
                           if(data.message=="") {
                                 add = $(data.addedcomments).hide();
                                 add.appendTo('#comments').slideDown('slow');
                           }
                           else {

                                 $('#commentmessage').html(unescape(data.message));
                           }
                           console.log(data.message);
                           console.log(data.addedcomments);

                     },'json');
               });
         });	

         function hideMesg(){

               $('.rating_message').fadeOut();
               $.post("rating.php?show=1", {
                  }, function(response){
                     $('#inner').html(unescape(response));
                     $('#inner').fadeIn('slow');
               });
         }	
         // ]]>
      </script>
   </head>
   <body>
      <?php

         $result=mysql_query("select sum(rating) as ratings from ratings");
         $row=mysql_fetch_array($result);

         $rating=$row['ratings'];

         $quer = mysql_query("select rating from ratings");
         $all_result = mysql_fetch_assoc($quer);
         $rows_num = mysql_num_rows($quer);

         if($rows_num > 0){
            $get_rating = floor($rating/$rows_num);
            $rem =  5 - $get_rating;
         }
         else
         {
            $rem = 5;
         }?>
         <h1>Ajax Rating System: Create Simple Ajax Rating System using jQuery AJAX and PHP.</h1>

         <div align="center" style="margin:30px;">
            <div id="container">
               <?php
                  $gelen=$database->getVideo();
                  echo html_entity_decode($gelen["embed_code"]);
               ?>
               <div id="outer">
                  <div id="inner">
                     <?php
                        for($k=1;$k<=$get_rating;$k++){?>
                        <div class="rating_enb" id="<?php echo $k?>">&nbsp;</div>
                        <?php
                        }?>
                        <?php
                           for($i=$rem;$i>=1;$i--){?>
                           <div class="rating_dis" id="<?php echo $k?>">&nbsp;</div>
                           <?php
                              $k++;
                           }?>	
                           <div class="rating_value"><?php echo ((@$get_rating) ? @$get_rating : '0')?> / 5</div>
                           <div class="user_message"><?php echo $rows_num?> times rated</div>
                        </div>
                     </div>
                     <div id="commentmessage"></div>

                     <div style="margin-top:25px" id="comments">
                        <?php
                           echo $database->getComments($gelen["id"]);
                        ?>
                     </div>
                     <div>
                        <form action="comments.php">
                           <input type="hidden" id="videoid" name="videoid" value="<?php echo $gelen["id"] ?>">
                           <textarea cols="65" rows="6" name="comment" id="comment"></textarea>	
                           <input type="button" value="Send" id="commentsubmit" name="commentsubmit">
                        </form>
                     </div>
                  </div>
                  <br clear="all" /><br clear="all" /><br clear="all" />
                  <a id="heading" href="http://www.99points.info/">99Points.info</a>
                  <div style="border:solid #000000 1px; background:#CC3333; color:#FFFFFF" align="center">
                     <a style=" text-decoration:none; font-size:18px;color:#FFFFFF" href="http://99Points.info"> Codeigniter , JQuery PHP Helping Demos on 99Points.info</a>

                  </body>
               </html>
