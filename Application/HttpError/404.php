<?php
header('HTTP/1.0 404 Not Found');

$type = isset($_GET["HttpErrorType"]) ? $_GET["HttpErrorType"] : "File";
$name = isset($_GET["HttpErrorName"]) ? $_GET["HttpErrorName"] : $_GET["uri"];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Error - 404</title>
    <link rel="stylesheet" type="text/css" href="/Content/Css/leaguestats.css" />
<style>
html { 
    background:url("/Content/Background/404.jpg") no-repeat top center;
    background-color:black;color:#000;
} 
#fourohfour-wrapper {
    margin:300px auto 0;
    width:650px;
    height:160px;
    color:#fff;
    overflow: auto;
}
.error-msg {
    float: right;
    width:370px;
    height:100%;
    box-sizing:border-box;
    padding:15px;
    padding-top:65px;
    word-wrap: break-word;
    text-align: center;
}
.text-wrapper {
    display: table;
    height: 45px;
    width: 100%;
    #position: relative;
    overflow: hidden;
}
.text-wrapper .vertical-align {
    #position: absolute;
    #top: 50%;
    display: table-cell;
    vertical-align: middle;
}
button { 
    color:black;
    width:150px;
    height:30px;
}
</style>
</head>
<body>
    <div id="fourohfour-wrapper">
        <div class="error-msg">
            <div class="text-wrapper">
                <div class="vertical-align">
                    <p><?php printf("%s: %s, does not exist!", $type, $name); ?></p>
                </div>
            </div>
            <button class="login_button" style="" onclick="javascript:history.back();">GO BACK</button>
        </div>
    </div>
</body>
</html>
