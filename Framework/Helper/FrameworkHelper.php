<?php

class FrameworkHelper {

    public static function CalculateProcessingTime($start_time) {
        $end_time = microtime(true);
        $total_time = ($end_time - $start_time) * 1000;
        return substr($total_time, 0, strpos($total_time, '.') + 2);
    }

    public static function GetPhpFilesInFolder($folder) {
        $matches = array();
        $files = array_diff(scandir($folder), array('..', '.'));
        foreach ($files as $file) {
            if (preg_match("/.*php/", $file)) {
                $matches[] = $folder.$file;
            }
        }
        return $matches;
    }

    public static function IncludeFiles($folder) {
        $files = array_diff(scandir($folder), array('..', '.'));
        foreach ($files as $file) {
            if (preg_match("/.*php/", $file)) {
                include $folder."/".$file;
            }
        }
    }
}

?>