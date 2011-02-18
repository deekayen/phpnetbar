<html>
<head><title>phpnetbar</title></head>

<body>

<?php
include 'netload.inc';

printf("Current bandwidth utilization %6.2f %s\n", $utilization, $unit_name);

?><br>
<img src="phpnetbar.php">

</body>
</html>
