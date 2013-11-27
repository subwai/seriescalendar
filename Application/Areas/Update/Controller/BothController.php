<?php
require_once "./Application/Areas/ApplicationController.php";
require_once "./Application/Service/SeriesUpdaterService.php";
require_once "./Application/Service/EpisodeUpdaterService.php";

class BothController extends ApplicationController {

    function Index() {

        $stmt = $this->DatabaseMgr->Execute("SELECT * FROM thetvdb", MAIN);
        $thetvdb = $stmt->fetch(PDO::FETCH_OBJ);
        $toUpdate = new SimpleXMLElement(file_get_contents(sprintf("http://thetvdb.com/api/Updates.php?type=all&time=%u", $thetvdb->previous)));

        $seriesUp = new SeriesUpdaterService($this->DatabaseMgr, true, $thetvdb->apikey);
        $seriesUp->UpdateList($toUpdate->Series);
        $episodesUp = new SeriesUpdaterService($this->DatabaseMgr, true, $thetvdb->apikey);
        $episodesUp->UpdateList($toUpdate->Episodes);

        $this->DatabaseMgr->Execute(sprintf("UPDATE thetvdb SET previous = %d", $toUpdate->Time), MAIN);

        echo "Done!";
    }
}

?>
