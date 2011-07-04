<?php
/*
######################################
# FFMPEG-Progressbar in PHP 0.1 Beta #
#    (C) 2010 by David-Kurz.de       #
#     http://lab.david-kurz.de/      #
######################################
*/
?>
<html>
<head>
<title>demopage for ffmpeg-progressbar</title>
</head>
<body>
<center><strong>Converting with ProgressKey <?=$_GET["pkey"]?> Successfully!</strong><br />Informations:<br /><?=nl2br(file_get_contents('log/'.$_GET["pkey"].'.ffmpeg.file'))?></center>
</body>
</html>