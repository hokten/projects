<?php
    // file: index.php
    session_start();
    $_SESSION['percent']=0;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Testing ajax progress bar</title>
    <link rel="stylesheet" href="style.css" media="screen" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

    <script type="text/javascript" src="script.js"></script>
<style>
#bardivs {
    width:400px; /* or whatever the of the porgress bar is */
	height:30px;
    position:relative;
}
#progressbar {
height:30px;
}
#progresstext {
    position:absolute;
    top:10px;
    left:200px;
}
</style>
</head>
<body>
    
    <h1><a href="#" id="start">Click</a> to go!</h1>
<div id="bardivs">
    <div id="progressbar"></div>
    <div id="progresstext"></div>
</div>
<div id="yazi"></div><br>
<input type="button" id="buton" value="Cancel">

</body>
</html>
