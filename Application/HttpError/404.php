<?php
header('HTTP/1.0 404 Not Found');

$type = isset($_GET["HttpErrorType"]) ? $_GET["HttpErrorType"] : "File";
$name = isset($_GET["HttpErrorName"]) ? $_GET["HttpErrorName"] : $_GET["uri"];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Error - 404</title>
</head>
<body>
    <h1>404</h1>
    <p><?php printf("%s: %s, does not exist!", $type, $name); ?></p>
</body>
</html>
