<?php
include("include/session.php");
include("dbcon.php");

if($_REQUEST['value'])
{
    if($session->logged_in)
    {
        $username = $session->username ;
        $id = $_REQUEST['value'];
        $result = mysql_query("select username from ratings where username='$username'");
        $num = mysql_num_rows($result);

        if($num==0)
        {
            $query = "insert into ratings (rating,username) values ('$id','$username')";
            mysql_query( $query);

            $result=mysql_query("select sum(rating) as rating from ratings");
            $row=mysql_fetch_array($result);

            $rating=$row['rating'];

            $quer = mysql_query("select rating from ratings");
            $all_result = mysql_fetch_assoc($quer);
            $rows_num = mysql_num_rows($quer);
            if($rows_num > 0){
                $get_rating = floor($rating/$rows_num);
                $rem =  5 - $get_rating;
            }
            for($k=1;$k<=$get_rating;$k++){
                echo "<div class='rating_enb' id='$k'>&nbsp;</div>";
            }
            for($i=$rem;$i>=1;$i--){
                echo "<div class='rating_dis' id='$k'>&nbsp;</div>";
                $k++;
            }
            echo "<div class='rating_value'>";
            echo ((@$get_rating) ? @$get_rating : '0')." / 5";
            echo "</div>";
            echo "<div class='user_message'>$rows_num times rated</div>";
        }
        else
        {
            echo '<div class="rating_message">You already did it !</div>';
        }
    }
    else
    {
         echo '<div class="rating_message">You must login!</div>';
    }
}
if(@$_REQUEST['show'])
{
    $result=mysql_query("select sum(rating) as rating from ratings");
    $row=mysql_fetch_array($result);


        $rating=$row['rating'];

        $quer = mysql_query("select rating from ratings");
        $all_result = mysql_fetch_assoc($quer);
        $rows_num = mysql_num_rows($quer);
        if($rows_num > 0){
            $get_rating = floor($rating/$rows_num);
            $rem =  5 - $get_rating;
        }?>
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
<?php
    }

?>
