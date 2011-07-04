<?PHP
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
include_once dirname(__FILE__).'/ffmpegprogressbar.class.php';



// DONT CHANGE!!!
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 01 Jan 1970 00:00:00 GMT');
//header('Content-type: application/json');
//header("Content-Disposition: attachment; filename=\"progress.json\"");
flush();
// DONT CHANGE!!!
$FFMPEGProgressBar=new FFMPEGProgressBar();

// Output Minutes and Seconds...


 if($FFMPEGProgressBar->checkFFMPEG($_GET["pkey"])){

$to_time=$FFMPEGProgressBar->GetTotalTime($_GET["pkey"]);
$en_time=$FFMPEGProgressBar->GetEncodedTime($_GET["pkey"]);
// Output
?>
{"time_encoded":"<?=hms2sec($en_time)?>","time_total":"<?=floor($to_time)?>","time_encoded_min":"<?=$en_time?>","time_total_min":"<?=sec2min($to_time)?>"}
<? }else{ 
   $FFMPEGProgressBar->logError('ffmpeg-progressbar: can\'t open FFMPEG-Log \'./log/'.$pkey.'.ffmpeg\'',date("d-m-y").'.error.log');
} ?>
