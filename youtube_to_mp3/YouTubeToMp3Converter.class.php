<?php

	// Conversion Class
	class YouTubeToMp3Converter
	{
		// Private Fields
		private $_songFileName = '';
		private $_flvUrl = '';
		private $_audioQualities = array(64, 128, 320);
		private $_tempVidFileName;
		private $_vidSrcTypes = array('source_code', 'url');

		// Constants
		const _TEMPVIDDIR = 'videos/';
		const _SONGFILEDIR = 'mp3/';
		const _FFMPEG = '/usr/bin/ffmpeg';

		#region Public Methods
		function __construct()
		{
		}

		function DownloadVideo($youTubeUrl)
		{
     
$ch = curl_init();
$timeout = 0;
curl_setopt ($ch, CURLOPT_URL, $youTubeUrl);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$file_contents = curl_exec($ch);
curl_close($ch);


			if ($file_contents)
			{
				$this->SetSongFileName($file_contents);
				$this->SetFlvUrl($file_contents);
				if ($this->GetSongFileName() != '' && $this->GetFlvUrl() != '')
				{
					return $this->SaveVideo($this->GetFlvUrl());
				}
			}
			return false;
		}

		function GenerateMP3($audioQuality)
		{
			$qualities = $this->GetAudioQualities();
			$quality = (in_array($audioQuality, $qualities)) ? $audioQuality : $qualities[1];
			$exec_string = self::_FFMPEG.' -i '.$this->GetTempVidFileName().' -y -acodec libmp3lame -ab '.$quality.'k '.$this->GetSongFileName();
			exec($exec_string);
			$this->DeleteTempVid();
 			return is_file($this->GetSongFileName());
		}

		function ExtractSongTrackName($vidSrc, $srcType)
		{
			$name = '';
			$vidSrcTypes = $this->GetVidSrcTypes();
			if (in_array($srcType, $vidSrcTypes))
			{
				$vidSrc = ($srcType == $vidSrcTypes[1]) ? file_get_contents($vidSrc) : $vidSrc;
				if ($vidSrc !== false && eregi('eow-title',$vidSrc))
				{
					$name = end(explode('eow-title',$vidSrc));
					$name = current(explode('">',$name));
					$name = ereg_replace('[^-_a-zA-Z,"\' :0-9]',"",end(explode('title="',$name)));
				}
			}
			return $name;
		}
		#endregion

		#region Private "Helper" Methods
		private function SaveVideo($url)
		{
			$this->SetTempVidFileName(time());
			$file = fopen($this->GetTempVidFileName(), 'w');
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_FILE, $file);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIE);
			curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIE);
			curl_exec($ch);
			curl_close($ch);
			fclose($file);
			return is_file($this->GetTempVidFileName());
		}

		private function DeleteTempVid()
		{
			if (is_file($this->GetTempVidFileName()))
			{
				unlink($this->GetTempVidFileName());
			}
		}
		#endregion

		#region Properties
		public function GetSongFileName()
		{
			return $this->_songFileName;
		}
		private function SetSongFileName($file_contents)
		{
			$vidSrcTypes = $this->GetVidSrcTypes();
			$trackName = $this->ExtractSongTrackName($file_contents, $vidSrcTypes[0]);
			$this->_songFileName = (!empty($trackName)) ? self::_SONGFILEDIR . preg_replace('/_{2,}/','_',preg_replace('/ /','_',preg_replace('/[^A-Za-z0-9 _-]/','',$trackName))) . '.mp3' : '';
		}

		private function GetFlvUrl()
		{
			return $this->_flvUrl;
		}
		public function SetFlvUrl($file_contents)
		{
			$vidUrl = '';
			if (preg_match("#fmt_url_map#i",$file_contents))
			{
				$vidUrl = end(explode('fmt_url_map=',$file_contents));
				$vidUrl = current(explode('&',$vidUrl));
				$vidUrl = current(explode('%2C',$vidUrl));
				$vidUrl = urldecode(end(explode('%7C',$vidUrl)));
			}
			$this->_flvUrl = $vidUrl;
		}

		public function GetAudioQualities()
		{
			return $this->_audioQualities;
		}

		private function GetTempVidFileName()
		{
			return $this->_tempVidFileName;
		}
		private function SetTempVidFileName($timestamp)
		{
			$this->_tempVidFileName = self::_TEMPVIDDIR . $timestamp .'.flv';
		}

		public function GetVidSrcTypes()
		{
			return $this->_vidSrcTypes;
      }
		public function GetMp3Name()
		{
         return "http://hokten.dyndns.org/youtube_to_mp3/".$this->_songFileName;
		}
	
		#endregion
    }


?>
