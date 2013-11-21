<?php
require_once "./Application/Controller/ApplicationController.php";
require_once "./Application/Model/Database/SeriesModel.php";

class Edit_calendarController extends ApplicationController {

    function Index() {
        $calendar = array();

        if (FacebookManager::FacebookUser()) {

            $stmt = $this->DatabaseMgr->Execute(@"SELECT `series`.`name`, date_format(`series`.`release_time`, '%H:%i') as `release_time`, `series`.`release_week_day`
                                                FROM `series` INNER JOIN `facebook_calendar` ON `facebook_calendar`.`series` = `series`.`id`
                                                WHERE `facebook_calendar`.`facebook` = ".FacebookManager::FacebookUser(), MAIN_DB);
            $stmt->setFetchMode(PDO::FETCH_CLASS, "SeriesModel");
            $calendar = $stmt->fetchAll();
        }

        return $this->View($calendar);
    }

    function Search() {

        $results = array();
        if (isset($_POST["search_text"])) {
            $stmt = $this->DatabaseMgr->Prepare(@"SELECT name FROM series WHERE
                                                MATCH (name,description) AGAINST
                                                (:search IN BOOLEAN MODE)", MAIN_DB);
            $stmt->execute(array("search" => $_POST["search_text"]));
            $results = $stmt->fetchAll();
        }

        return $this->Json($results);
    }
}

?>
