<?php
require_once "./Application/Extra/MultiRequest/Handler.php";

class SeriesUpdaterService {

    private $DatabaseMgr;
    private $APIKey;
    private $Debug;

    public function __construct($databaseMgr, $Debug = false, $APIKey = null) {
        $this->DatabaseMgr = $databaseMgr;
        $this->APIKey = $APIKey ?: $this->fetchAPIKey();
        $this->Debug = $Debug;
    }

    private function fetchAPIKey() {
        $stmt = $this->DatabaseMgr->Execute("SELECT * FROM thetvdb", MAIN);
        $thetvdb = $stmt->fetch(PDO::FETCH_OBJ);
        return $thetvdb->apikey;
    }

    private function Debug($str) {
        if ($this->Debug) {
            echo $str;
        }
    }

    public function UpdateList($list) {
        $mrHandler = new MultiRequest_Handler();
        $mrHandler->setConnectionsLimit(8);

        $this->Debug("<style>#sloader { background:blue;height:100%; }</style>");
        $this->Debug("<div style='height:20px; width:100%;'><div id='sloader' style='width:0%;'></div></div>");
        $this->Debug("<script>window.count = 0;var max = ".count($list).";setInterval(function(){document.getElementById('sloader').setAttribute('style','width:'+(window.count/max*100)+'%')},30)</script>");
        ob_end_flush();
        ob_implicit_flush(true);

        foreach ($list as $id) {
            $request = new MultiRequest_Request(sprintf("http://thetvdb.com/api/%s/series/%s/en.xml", $this->APIKey, $id));
            $request->onComplete(function(MultiRequest_Request $request, MultiRequest_Handler $handler) {
                $this->UpdateByXML($request->getContent());
                $this->Debug(sprintf("[%u] ", $request->getId()));
                $this->Debug("<script>window.count++</script>");
            });
            $mrHandler->pushRequestToQueue($request);
        }
        $mrHandler->start();
        ob_start();
        ob_implicit_flush(false);
    }

    public function UpdateByID($id) {
        return $this->UpdateByXML(file_get_contents(sprintf("http://thetvdb.com/api/%s/series/%s/en.xml", $this->APIKey, $id)));
    }

    public function UpdateByXML($xml) {
        set_time_limit(30);
        $data = new SimpleXMLElement($xml);
        $this->DatabaseMgr->Execute(sprintf("DELETE FROM series WHERE id = %s", $data->Series->id), MAIN);
        $stmt = $this->DatabaseMgr->Prepare(@"INSERT INTO `series` (`id`,`SeriesName`,`Airs_Time`,`Airs_DayOfWeek`,`Overview`,`Actors`,`Genre`,
                                                                    `FirstAired`,`IMDB_ID`,`Rating`,`Status`,`banner`,`fanart`,`poster`,`updatestatus`)
                                            VALUES (:id, :SeriesName, :Airs_Time, :Airs_DayOfWeek, :Overview, :Actors, :Genre, 
                                                    :FirstAired, :IMDB_ID, :Rating, :Status, :banner, :fanart, :poster, :updatestatus)", MAIN);
         if ($stmt->execute(array(
            "id" => $data->Series->id,
            "SeriesName" => $data->Series->SeriesName,
            "Airs_Time" => date("H:i", strtotime($data->Series->Airs_Time)),
            "Airs_DayOfWeek" => date("w", strtotime($data->Series->Airs_DayOfWeek)),
            "Overview" => $data->Series->Overview,
            "Actors" => $data->Series->Actors,
            "Genre" => $data->Series->Genre,
            "FirstAired" => empty($data->Series->FirstAired) ? null : $data->Series->FirstAired,
            "IMDB_ID" => $data->Series->IMDB_ID,
            "Rating" => empty($data->Series->Rating) ? null : $data->Series->Rating,
            "Status" => $data->Series->Status,
            "banner" => $data->Series->banner,
            "fanart" => $data->Series->fanart,
            "poster" => $data->Series->poster,
            "updatestatus" => "Success",
        ))) {
            $this->Debug(sprintf("Success: %s<br>", $data->Series->SeriesName));
            $this->DatabaseMgr->Execute(sprintf("DELETE FROM thetvdb_errors WHERE id = %u", $data->Series->id), MAIN);
        } else {
            $this->Debug(sprintf("Error: <%u> %s - %s<br>", $data->Series->id, $data->Series->SeriesName, $stmt->errorInfo()[2]));
            $this->DatabaseMgr->Execute(sprintf("DELETE FROM thetvdb_errors WHERE id = %u", $data->Series->id), MAIN);
            $this->DatabaseMgr->Execute(sprintf("INSERT INTO thetvdb_errors VALUES (%u)", $data->Series->id), MAIN);
        }
    }
}