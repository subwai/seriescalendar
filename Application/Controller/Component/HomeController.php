<?php
require_once "./Application/Controller/ApplicationController.php";
require_once "./Application/Model/View/CalendarViewModel.php";
require_once "./Application/Model/Database/SeriesModel.php";

class HomeController extends ApplicationController {

    function Index() {
        $model = new CalendarViewModel();
        $model->Calendar = array(
            1 => new CalendarDay("Monday"),
            2 => new CalendarDay("Tuesday"),
            3 => new CalendarDay("Wednesday"),
            4 => new CalendarDay("Thursday"),
            5 => new CalendarDay("Friday"),
            6 => new CalendarDay("Saturday"),
            7 => new CalendarDay("Sunday"),
        );

        if (FacebookManager::FacebookUser()) {

            $stmt = $this->DatabaseMgr->Execute(@"SELECT `series`.`name`, date_format(`series`.`release_time`, '%H:%i') as `release_time`, `series`.`release_week_day`
                                                FROM `series` INNER JOIN `facebook_calendar` ON `facebook_calendar`.`series` = `series`.`id`
                                                WHERE `facebook_calendar`.`facebook` = ".FacebookManager::FacebookUser(), MAIN_DB);
            $stmt->setFetchMode(PDO::FETCH_CLASS, "SeriesModel");
            while ($row = $stmt->fetch()) {
                $model->Calendar[$row->release_week_day]->addSerie($row);
            }

            $date = getdate();
            $model->Calendar[$date["wday"]]->class = "green";
            $model->Calendar[$date["wday"] == 6 ? 0 : $date["wday"]+1]->class = "yellow";
        }

        return $this->View($model);
    }
}

?>
