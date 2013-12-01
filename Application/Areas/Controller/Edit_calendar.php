<?php
namespace Controller;

require_once "Application/Areas/ApplicationController.php";
require_once "Application/Areas/Model/Database/Series.php";
require_once "Application/Service/SeriesUpdaterService.php";

class Edit_calendar extends \ApplicationController {

    function Index() {
        $calendar = array();

        if (\FacebookManager::FacebookUser()) {
            $stmt = $this->DatabaseMgr->Execute(@"SELECT *, date_format(Airs_Time, '%H:%i') as Airs_Time
                                                FROM series INNER JOIN subscriptions ON series = id
                                                WHERE facebook = ".\FacebookManager::FacebookUser()." ORDER BY SeriesName ASC", MAIN);
            $stmt->setFetchMode(\PDO::FETCH_CLASS, "\Model\Series");
            $calendar = $stmt->fetchAll();
        }

        return $this->View($calendar);
    }

    function Save() {
        if (!isset($_POST["selected_series"])) {
            return $this->Json("You must send a series array.", false);
        }

        $stmt = $this->DatabaseMgr->Execute("SELECT series FROM subscriptions WHERE facebook = ".\FacebookManager::FacebookUser(), MAIN);
        $stmt->setFetchMode(\PDO::FETCH_COLUMN, 0);
        $subscriptions = $stmt->fetchAll();

        $added = array_diff($_POST["selected_series"], $subscriptions);
        $removed = array_diff($subscriptions, $_POST["selected_series"]);

        if (!empty($added)) {
            $addedStr = implode(array_keys(array_fill(0,count($added),0)), ",:");
            $stmt = $this->DatabaseMgr->Prepare("SELECT id FROM series WHERE id IN (:$addedStr)", MAIN);
            $stmt->execute($added);
            $old = $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);

            $new = array_diff($added, $old);

            $updateSeries = new \SeriesUpdaterService($this->DatabaseMgr);
            $updateSeries->UpdateList($new);
        }

        $stmt = $this->DatabaseMgr->Prepare("DELETE FROM subscriptions WHERE series = :series AND facebook = :facebook", MAIN);
        foreach ($removed as $id) {
            $stmt->execute(array("series" => $id, "facebook" => \FacebookManager::FacebookUser()));
        }
        
        $stmt = $this->DatabaseMgr->Prepare("INSERT INTO subscriptions VALUES (:facebook, :series)", MAIN);
        foreach ($added as $id) {
            $stmt->execute(array("series" => $id, "facebook" => \FacebookManager::FacebookUser() ));
        }

        return $this->Json();
    }

    function Search() {
        if (!isset($_POST["search_text"])) {
            return $this->Json();
        }

        $stmt = $this->DatabaseMgr->Prepare(@"SELECT id, SeriesName,
                                            @matchscore := MATCH (SeriesName) AGAINST (:search) AS matchscore,
                                            @subscriberscore := (subscriptions/@maxsubscriptions) AS subscriberscore,
                                            @matchscore+@subscriberscore AS relevance
                                            FROM series
                                            INNER JOIN (
                                                SELECT @maxsubscriptions := MAX(subscriptions) FROM series
                                                WHERE MATCH (SeriesName) AGAINST (:search)
                                            ) AS s2
                                            WHERE MATCH (SeriesName) AGAINST (:search)
                                            ORDER BY relevance DESC
                                            LIMIT 10;", MAIN);

        $stmt->execute(array("search" => $_POST["search_text"]));
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $thetvdb = new \SimpleXMLElement(file_get_contents(sprintf("http://thetvdb.com/api/GetSeries.php?seriesname=%s&language=en", urlencode($_POST["search_text"]))));
        foreach ($thetvdb as $series) {
            if (!in_array($series->id, array_column($results, "id"))) {
                $results[] = array(
                    "id" => (string) $series->id,
                    "SeriesName" => (string) $series->SeriesName,
                    "matchscore" => "0",
                    "subscriberscore" => "0",
                    "relevance" => "1"
                );
            }
        }

        return $this->Json($results);
    }
}

?>
