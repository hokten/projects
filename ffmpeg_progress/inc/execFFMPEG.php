<?php 
/*
######################################
# FFMPEG-Progressbar in PHP 0.1 Beta #
#    (C) 2010 by David-Kurz.de       #
#     http://lab.david-kurz.de/      #
######################################

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
*/

include_once dirname(__FILE__).'/config.inc.php';
if($_POST["cmd"] && $_POST["cmd"]!="" && $_POST["ffmpegpw"] && $_POST["ffmpegpw"] != ""){

if($_POST["ffmpegpw"]==FFMPEG_PW){
$cmd=stripslashes($_POST["cmd"]);
// Executing FFMPEG-Command
$handler = fOpen(dirname(__FILE__).'/../log/'.date('d-m-y').'.log' , "a+");
$loge="[".date("d.m.y H:i:s", mktime())."] ".$_SERVER["REMOTE_ADDR"]." ffmpeg: execute command (".FFMPEG_PATH." ".$cmd.")...\n";
fWrite($handler , $loge);
fClose($handler);
// Executing FFMPEG-Command
exec(FFMPEG_PATH.' '.$cmd);
}else{
$handler = fOpen(dirname(__FILE__).'/../log/'.date('d-m-y').'.warn.log' , "a+");
$loge="[".date("d.m.y H:i:s", mktime())."] ".$_SERVER["REMOTE_ADDR"]." Somebody with IP ".$_SERVER["REMOTE_ADDR"]." try to hack your Security Password (".$_POST["ffmpegpw"].")!\n";
fWrite($handler , $loge);
fClose($handler);
header("HTTP/1.1 401 Unauthorized");
exit('401 Unauthorized');
}
}else{
header("HTTP/1.1 401 Unauthorized");
exit('401 Unauthorized');
}

?>