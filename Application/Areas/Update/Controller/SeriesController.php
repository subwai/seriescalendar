<?php
namespace Controller;

require_once "Application/Areas/ApplicationController.php";
require_once "Application/Service/SeriesUpdaterService.php";

class SeriesController extends ApplicationController {

    function Index() {

        $stmt = $this->DatabaseMgr->Execute("SELECT * FROM thetvdb", MAIN);
        $thetvdb = $stmt->fetch(PDO::FETCH_OBJ);
        $toUpdate = new \SimpleXMLElement(file_get_contents(sprintf("http://thetvdb.com/api/Updates.php?type=series&time=%u", $thetvdb->previous)));

        if (!empty($toUpdate->Series)) {
            $inStr = implode((array) $toUpdate->Series, ",");
            $stmt = $this->DatabaseMgr->Execute("SELECT id FROM series WHERE id IN ($inStr)", MAIN);
            $series = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

            $updater = new \SeriesUpdaterService($this->DatabaseMgr, true, $thetvdb->apikey);
            $updater->UpdateList($series);

            $this->DatabaseMgr->Execute(sprintf("UPDATE thetvdb SET previous = %d", $toUpdate->Time), MAIN);
        }        

        echo "Done!";
    }

    function Specific() {
        $id = $_GET["id"];

        $updater = new \SeriesUpdaterService($this->DatabaseMgr, true);
        $updater->UpdateByID($id);
    }

    function Blanks() {
        $stmt = $this->DatabaseMgr->Execute("SELECT id FROM series WHERE SeriesName IS NULL AND updatestatus = 'None' LIMIT 100", MAIN);
        $series = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

        $updater = new \SeriesUpdaterService($this->DatabaseMgr, true);
        $updater->UpdateList($series);

        echo "Done!";
    }

    function RetryErrors() {
        $stmt = $this->DatabaseMgr->Execute("SELECT * FROM thetvdb_errors", MAIN);
        $series = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

        $updater = new \SeriesUpdaterService($this->DatabaseMgr, true);
        $updater->UpdateList($series);

        echo "Done!";
    }
}

?>
