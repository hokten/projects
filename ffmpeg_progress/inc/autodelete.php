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

// TO USE THIS, ADD THIS FILE TO THE CRONJOB-DATABASE

// Delete Log-Files after 10 Days
$files = glob(dirname(__FILE__).'/../log/*.log');
foreach($files as $file) {
if(filemtime($file) < time() - 60*60*24*10) unlink($file);
}  

// Delete FFMPEG-Log Files after 2 Days
$files = glob(dirname(__FILE__).'/../log/*.ffmpeg');
foreach($files as $file) {
if(filemtime($file) < time() - 60*60*24*2) unlink($file);
}  
$files = glob(dirname(__FILE__).'/../log/*.file');
foreach($files as $file) {
if(filemtime($file) < time() - 60*60*24*2) unlink($file);
}  

?>