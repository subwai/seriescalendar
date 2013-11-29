<?php
require_once "./Application/Areas/ApplicationController.php";
require_once "./Application/Areas/Model/View/CalendarView.php";
require_once "./Application/Areas/Model/Database/Series.php";
require_once "./Application/Areas/Model/Database/CustomSeries.php";

class Home extends ApplicationController {

    function Index() {
        $model = new CalendarViewModel();
        $model->Calendar = array(
            1 => new CalendarDay("Monday"),
            2 => new CalendarDay("Tuesday"),
            3 => new CalendarDay("Wednesday"),
            4 => new CalendarDay("Thursday"),
            5 => new CalendarDay("Friday"),
            6 => new CalendarDay("Saturday"),
            0 => new CalendarDay("Sunday"),
        );
        $model->Upcoming = array();
        $model->Released = array();

        $now = new DateTime("now");
        $tomorrow = new DateTime("tomorrow");

        if (FacebookManager::FacebookUser()) {

            $stmt = $this->DatabaseMgr->Execute(@"SELECT *, date_format(Airs_Time, '%H:%i') as Airs_Time
                                                FROM series INNER JOIN subscriptions ON series = id
                                                WHERE facebook = ".FacebookManager::FacebookUser()." ORDER BY Airs_DayOfWeek, Airs_Time ASC", MAIN);
            $stmt->setFetchMode(PDO::FETCH_CLASS, "SeriesModel");
            $series = $stmt->fetchAll();

            $stmt = $this->DatabaseMgr->Execute(@"SELECT *, date_format(Airs_Time, '%H:%i') as Airs_Time
                                                FROM custom_series
                                                WHERE facebook = ".FacebookManager::FacebookUser()." ORDER BY Airs_DayOfWeek, Airs_Time ASC", MAIN);
            $stmt->setFetchMode(PDO::FETCH_CLASS, "CustomSeriesModel");
            while($res = $stmt->fetch()) {
                $series[] = $res;
            }
            
            foreach ($series as $entry) {
                $model->Calendar[$entry->Airs_DayOfWeek]->addSerie($entry);

                $next = new DateTime($entry->Airs_Time);
                $next->setISODate($now->format("Y"), $now->format("W"), $entry->Airs_DayOfWeek);

                $upcoming = new DateTime();
                $upcoming->add(new DateInterval("PT12H"));
                $released = new DateTime();
                $released->sub(new DateInterval("PT24H"));

                if ($now < $next && $next < $upcoming) {
                    $model->Upcoming[] = $entry;
                }
                if ($released < $next && $next < $now) {
                    $model->Released[] = $entry;
                }
            }

            $model->Count = count($series);
            $model->Calendar[$now->format("w")]->active = "active";
            $model->Calendar[$now->format("w")]->class = "today";
            $model->Calendar[$tomorrow->format("w")]->class = "tomorrow";
        }

        return $this->View($model);
    }
}

?>
