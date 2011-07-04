<html>
<body>
<?php
exec("./gitpull.sh 2>&1",$out,$ret);
echo "<pre>";
print_r($out);
echo "</pre>";
?>
</body>
</html>
