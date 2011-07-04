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
   date_default_timezone_set('Europe/Istanbul');
   function sec2min($sekunden)
   {
      $stunden = floor($sekunden / 3600);
      $minuten = floor(($sekunden - ($stunden * 3600)) / 60);
      $sekunden = round($sekunden - ($stunden * 3600) - ($minuten * 60), 0);

      if ($stunden <= 9) {
         $strStunden = "0" . $stunden;
      } else {
         $strStunden = $stunden;
      }

      if ($minuten <= 9) {
         $strMinuten = "0" . $minuten;
      } else {
         $strMinuten = $minuten;
      }

      if ($sekunden <= 9) {
         $strSekunden = "0" . $sekunden;
      } else {
         $strSekunden = $sekunden;
      }

      return "$strStunden:$strMinuten:$strSekunden";
   }
   function hms2sec ($hms) {
      list($h, $m, $s) = explode (":", $hms);
      $seconds = 0;
      $seconds += (intval($h) * 3600);
      $seconds += (intval($m) * 60);
      $seconds += (intval($s));
      return $seconds;
   }


   class FFMPEGProgressBar
   {
      public $total_time;
      function logError($string, $file=''){
         if(empty($file)){
            $filename=dirname(__FILE__)."/../log/".date("d-m-y").".log";
         }else{
            $filename=dirname(__FILE__)."/../log/".$file;
         }
         $handler = fOpen($filename , "a+");
         $loge="[".date("d.m.y H:i:s", mktime())."] ".$_SERVER["REMOTE_ADDR"]." ".$string."\n";
         fWrite($handler , $loge);
         fClose($handler);
      }

      function execFFMPEG($i='', $o='', $p='', $pkey=''){
         if($pkey==''){
            $pkey=rand();
         }
         $fpath=FFMPEG_PATH;
         if(empty($fpath)){
            $this->logError('ffmpeg-progressbar: missing ffmpeg path',date("d-m-y").'.error.log');
            exit('ffmpeg-progressbar: missing ffmpeg path');
         }else{
            if(!file_exists($fpath)){
               $this->logError('ffmpeg-progressbar: wrong ffmpeg path \''.FFMPEG_PATH.'\'',date("d-m-y").'.error.log');
               exit('ffmpeg-progressbar: wrong ffmpeg path \''.FFMPEG_PATH.'\'');
            }
         }
         if(empty($i)){
            $this->logError('ffmpeg: missing argument for option \'i\'',date("d-m-y").'.error.log');
            exit('ffmpeg: missing argument for option \'i\'');
         }elseif(!file_exists($i)){
            $this->logError($i.':  no such file or directory',date("d-m-y").'.error.log');
            exit($i.':  no such file or directory');
         }elseif(empty($o)){
            $this->logError('ffmpeg: At least one output file must be specified',date("d-m-y").'.error.log');
            exit('ffmpeg: At least one output file must be specified');
         }elseif(file_exists($o)){
            $this->logError('ffmpeg: File \''.$o.'\' already exists.',date("d-m-y").'.error.log');
            exit('ffmpeg: File \''.$o.'\' already exists.');
         }elseif(empty($p)){
            $this->logError('ffmpeg: No Param has been specified... use default settings for converting...',date("d-m-y").'.warn.log');
         }else{
            //Executing FFMPEG
            $handler = fOpen(dirname(__FILE__).'/../log/'.$pkey.'.ffmpeg.file' , "w");
            fWrite($handler , $i."\n".$o."\n".$p."\n");
            fClose($handler); 
            $this->logError("Sending FFMPEG exec command to ".$_SERVER["HTTP_HOST"]."...");
            $curdir=getcwd();
            $cmd=" -i '".$i."' ".$p." '".$o."' 2> ".$curdir."/log/".$pkey.".ffmpeg";
            $postdata = "cmd=".$cmd."&ffmpegpw=".FFMPEG_PW;
            $fp = fsockopen($_SERVER["HTTP_HOST"], 80, $errno, $errstr, 30);
            fputs($fp, "POST ".dirname($_SERVER["SCRIPT_NAME"])."/inc/execFFMPEG.php HTTP/1.0\n");
            fputs($fp, "Host: ".$_SERVER["HTTP_HOST"]."\n");
            fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
            fputs($fp, "Content-length: ".strlen($postdata)."\n");
            // Faking User-Agent to Microsoft Internet Explorer 7
            fputs($fp, "User-agent: Mozilla/4.0 (compatible: MSIE 7.0; Windows NT 6.0)\n");
            fputs($fp, "Connection: close\n\n");
            fputs($fp, $postdata);
            fclose($fp);

         }
      }

      function Show($pkey){
         $this->Output("js-template", $pkey,  'file');
         $this->Output("<span id=\"status\" style=\"display:none\"><span class=\"progressbar\" id=\"progressbar\">0%</span><br /><span class=\"text\" id=\"time_cur\">?</span> {lang[LANG][5]} <span id=\"time_total\">?</span> {lang[LANG][6]}.</span></span>", $pkey, 'html');
   }

   function parse_lang($string){
      global $lang;
      $c=count($lang[LANG]);
      for($i = 0; $i < $c+1; $i++){
         $string=str_replace("{lang[LANG][$i]}", $lang[LANG][$i], $string);
      }
      return $string;
   }

   function Output($string, $pkey, $typ){
      if($typ=='html'){
         $d=$this->parse_lang($string);
         echo $d;
      }elseif($typ=='file'){
         $d=@file_get_contents($string);
         $d=str_replace('{JS_PATH}', JS_PATH, $d);
         $d=str_replace('{RE_FORM}', READY_FILE, $d);
         $d=str_replace('{UPD_RATE}', UPD_RATE, $d);
         $d=str_replace('{rand}', $pkey, $d);
         $d=$this->parse_lang($d);
         echo $d;
      }else{
         echo htmlspecialchars($string);
      }
   }

   function checkFFMPEG($pkey){
      if(!file_exists(dirname(__FILE__).'/../log/'.$pkey.'.ffmpeg')){
         return false;
      }else{
         return true;
      }
   }



   function GetTotalTime($pkey){
      if(!file_exists(dirname(__FILE__).'/../log/'.$pkey.'.ffmpeg.file')){
         $this->logError('ffmpeg-progressbar: can\'t open \'./log/'.$pkey.'.ffmpeg.file\'',date("d-m-y").'.error.log');
         exit('ffmpeg-progressbar: can\'t open \'./log/'.$pkey.'.ffmpeg.file\'');
      }else{
         include(dirname(__FILE__).'/getid3/getid3.php');
         $getID3 = new getID3;
         $dateihandle = fopen(dirname(__FILE__).'/../log/'.$pkey.'.ffmpeg.file',"r");
         $file_location = trim(fgets($dateihandle, 4096));
         $fileinfo = $getID3->analyze($file_location);
         getid3_lib::CopyTagsToComments($fileinfo);
         $play_time_sec= $fileinfo['playtime_seconds'];
         $this->total_time = round($play_time_sec);
         return $play_time_sec; 
      }
   }


   function GetEncodedTime($pkey){
      if(!file_exists(dirname(__FILE__).'/../log/'.$pkey.'.ffmpeg')){
         $this->logError('ffmpeg-progressbar: can\'t open FFMPEG-Log \'./log/'.$pkey.'.ffmpeg\'',date("d-m-y").'.error.log');
         exit('ffmpeg-progressbar: can\'t open FFMPEG-Log \'./log/'.$pkey.'.ffmpeg\'');
      }else{
         $FFMPEGLog=@file_get_contents(dirname(__FILE__).'/../log/'.$pkey.'.ffmpeg');
         if(preg_match("/Lsize=/i",$FFMPEGLog)) {
            return sec2min($this->total_time);

         }
         else {
            $times=explode('time=', $FFMPEGLog);
            $ctime=count($times)-1;
            $timed=explode(' bitrate=', $times[$ctime]);
            $tt=$timed[0];
            $final=explode('.',$tt);
            return $final[0];
         }

      }
   }




}
?>
