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

include_once 'inc/config.inc.php';
include_once 'inc/ffmpegprogressbar.class.php';
ob_flush();
?>
<html>
<head>
<title>ffmpegprogressbar demo</title>
<?php
   $FFMPEGInput='/var/www/localhost/htdocs/projects/ffmpeg_progress/rrr.flv';

// Specifie Outputfile for FFMPEG
$FFMPEGOutput='/var/www/localhost/htdocs/projects/ffmpeg_progress/aaassa.avi';

// Specifie (optional) Parameters for FFMPEG
$FFMPEGParams='-ar 44100 -b 900k -s 640x480 -ab 128k -ac 2';



if(!$_GET["pkey"]){
$pkey=rand();
}elseif(file_exists('log/'.$_GET["pkey"].'.ffmpeg')){
$pkey=$_GET["pkey"];
}else{
$pkey=rand();
}

// initializing and create ProgressBar
flush();
$FFMPEGProgressBar=new FFMPEGProgressBar();
flush();
// Show Progressbar
$FFMPEGProgressBar->Show($pkey);

if(!$_GET["pkey"] || !file_exists('log/'.$_GET["pkey"].'.ffmpeg')){
flush();
$FFMPEGProgressBar=new FFMPEGProgressBar();
flush();
@$FFMPEGProgressBar->execFFMPEG($FFMPEGInput, $FFMPEGOutput, $FFMPEGParams, $pkey);
}

// show Progressbar
?>
