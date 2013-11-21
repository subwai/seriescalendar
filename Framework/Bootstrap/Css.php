<?php
chdir('../../');

require_once "./Framework/Router.php";
require_once "./Framework/Function/Compiler/LessCompile.php";

$filePath = "Application/".substr($_GET["uri"], 0, strrpos($_GET["uri"], "/"));
$fileName = strstr(strrchr($_GET["uri"], "/"), ".css", true);

$cacheRoot = $filePath."/Cache";
$cssFile = $filePath.$fileName.".less";
$cacheFile = $cacheRoot.$fileName.".cache";

if (file_exists($cssFile)) {

	if (!file_exists($cacheRoot)) {
	    mkdir($cacheRoot);
	}

	if (file_exists($cacheFile)) {
	    $cache = unserialize(file_get_contents($cacheFile));
	} else {
	    $cache = $cssFile;
	}

	$less = new lessc;
	$less->setFormatter("compressed");
	$newCache = $less->cachedCompile($cache);
	if (!is_array($cache) || $newCache["updated"] > $cache["updated"]) {
	    file_put_contents($cacheFile, serialize($newCache));
	}

	$fileStats = stat($cacheFile);
	$Etag = sprintf("\"%x-%x-%016x\"", $fileStats['ino'], $fileStats['size'], $fileStats['mtime']);
	if (isset($_SERVER["HTTP_IF_NONE_MATCH"]) && $_SERVER["HTTP_IF_NONE_MATCH"] == $Etag) {
	    header('HTTP/1.0 304 Not Modified');
	}

	header('Content-Type: text/css');
	header(sprintf('Etag: %s', $Etag));
	header('Cache-Control: max-age=2592001');

	echo $newCache['compiled'];
} else {
	Router::Error(404, "Css file", $_GET["uri"]);
}
?>
