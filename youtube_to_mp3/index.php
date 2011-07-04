<?php ob_start();
   
   echo '<?xml version="1.1" encoding="iso-8859-1"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>YouTube-To-Mp3 Converter</title>
	<style type="text/css">
		body
		{
			text-align:center;
			font:13px Verdana,Arial;
			margin-top:50px;
		}

		p
		{
			margin:15px 0;
			font-weight:bold;
		}

		form
		{
			width:450px;
			margin:0 auto;
			padding:15px;
			border:1px solid #ccc;
		}

		form input[type="text"]
		{
			width:385px;
		}

		form p
		{
			margin:10px 0;
			font-weight:normal;
		}
	</style>
</head>
<body>
	<h2>YouTube-To-Mp3 Converter</h2>
	<?php
		// Execution settings
		ini_set('max_execution_time',0);
		ini_set('display_errors',0);

		// On form submission...
		if ($_POST['submit'])
		{
			// Instantiate converter class
			include 'YouTubeToMp3Converter.class.php';
			$converter = new YouTubeToMp3Converter();

			// Print "please wait" message and preview image
			$vidID = $vidTitle = '';
			$urlQueryStr = parse_url(trim($_POST['youtubeURL']), PHP_URL_QUERY);
			if ($urlQueryStr !== false && !empty($urlQueryStr))
			{
				$kvPairs = explode('&', $urlQueryStr);
				foreach ($kvPairs as $v)
				{
					$kvPair = explode('=', $v);
					if ($kvPair[0] == 'v')
					{
						$vidID = $kvPair[1];
						break;
					}
				}

				echo '<div id="preview" style="display:block"><p>...Please wait while I try to convert:</p>';
               echo '<p><img src="http://img.youtube.com/vi/'.$vidID.'/1.jpg" alt="preview image" /></p>';
               ob_end_flush();
				flush();
				echo '<p>'.$converter->ExtractSongTrackName(trim($_POST['youtubeURL']), 'url').'</p></div>';
			}

			// Main Program Execution
			if ($converter->DownloadVideo(trim($_POST['youtubeURL'])))
			{
            if($converter->GenerateMP3($_POST['quality'])) {
               echo '<a href="'.$converter->GetMp3Name().'">Download here</a>';
               echo  '<p>Success!</p>';
            }
            else {
               echo '<p>Error generating MP3 file!</p>';
            }
			}
			else
			{
				echo '<p>Error downloading video!</p>';
			}
		}
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<p>Enter a valid YouTube.com video URL:</p>
		<p><input type="text" name="youtubeURL" /></p>
		<p><i>(i.e., "<span style="color:red">http://www.youtube.com/watch?v=HMpmI2F2cMs</span>")</i></p>
		<p style="margin-top:20px">Choose the audio quality (better quality results in larger files):</p>
		<p style="margin-bottom:25px"><input type="radio" value="64" name="quality" />Low &nbsp; <input type="radio" value="128" name="quality" checked="checked" />Medium &nbsp; <input type="radio" value="320" name="quality" />High</p>
		<p><input type="submit" name="submit" value="Create MP3 File" /></p>
	</form>
	<script type="text/javascript">
		window.onload = function()
		{
			if (document.getElementById('preview'))
			{
				document.getElementById('preview').style.display = 'none';
			}
		};
	</script>
</body>
</html>
