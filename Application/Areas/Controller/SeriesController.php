<?php
require_once "./Application/Areas/ApplicationController.php";
require_once "./Application/Areas/Model/View/SeriesViewModel.php";

class SeriesController extends ApplicationController {

    function Index() {

        $model = (object) array(
            "days" => array(
                1 => "Monday",
                2 => "Tuesday",
                3 => "Wednesday",
                4 => "Thursday",
                5 => "Friday",
                6 => "Saturday",
                0 => "Sunday",
            ),
            "series" => array(),
        );

        $stmt = $this->DatabaseMgr->Execute("SELECT *, date_format(Airs_Time, '%H:%i') as Airs_Time FROM custom_series WHERE facebook = ".FacebookManager::FacebookUser(), MAIN);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "CustomSeriesModel");
        $model->series = $stmt->fetchAll();

        return $this->View($model);
    }

    function Subscribe() {
        $stmt = $this->DatabaseMgr->Prepare("INSERT INTO subscriptions (series, facebook) VALUES (:series, :facebook)", MAIN);
        $stmt->execute(array("series" => $_POST["id"], "facebook" => FacebookManager::FacebookUser()));
        return $this->Json();
    }

    function Create() {
        $model = new SeriesViewModel();
        $model->Series = new CustomSeriesModel();
        $model->Series->Airs_DayOfWeek = 1;

        if (isset($_POST["submit"])) {
            try {
                if (isset($_POST["SeriesName"])) { $model->Series->SeriesName = $_POST["SeriesName"]; }
                if (isset($_POST["Overview"])) { $model->Series->Overview = $_POST["Overview"]; }
                if (isset($_POST["Airs_Time"])) { $model->Series->Airs_Time = $_POST["Airs_Time"]; }
                if (isset($_POST["Airs_DayOfWeek"])) { $model->Series->Airs_DayOfWeek = $_POST["Airs_DayOfWeek"]; }

                if (!$model->Series->SeriesName) { throw new Exception("Title can not be empty."); }
                if (!$model->Series->Overview) { throw new Exception("Description can not be empty."); }
                if (!$model->Series->Airs_Time) { throw new Exception("Release time can not be empty."); }
                if (!$model->Series->Airs_DayOfWeek) { throw new Exception("Release day can not be empty."); }

                if (!preg_match("/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/", $model->Series->Airs_Time)) { throw new Exception("Wrong format for release time, correct format is HH:mm"); }

                $model->Series->facebook = FacebookManager::FacebookUser();

                $stmt = $this->DatabaseMgr->Prepare(@"INSERT INTO custom_series (id, facebook, SeriesName, Airs_Time, Airs_DayOfWeek, Overview)
                                                    VALUES (:id, :facebook, :SeriesName, :Airs_Time, :Airs_DayOfWeek, :Overview)", MAIN);
                $stmt->execute((array) $model->Series);
                return $this->Redirect("Index");

            } catch (Exception $e) {
                $model->Error = $e->getMessage();
                return $this->View($model);
            }
        }

        return $this->View($model);
    }

    function Edit() {

        $stmt = $this->DatabaseMgr->Prepare("SELECT *, date_format(Airs_Time, '%H:%i') as Airs_Time FROM custom_series WHERE id = :id", MAIN);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "CustomSeriesModel");
        $stmt->execute(array("id" => $_GET["id"]));
        $series = $stmt->fetch();

        if ($series->facebook != FacebookManager::FacebookUser()) {
            throw new Exception("You do not have the authority to view this entry.");
            return $this->Redirect("Index");
        }

        return $this->View($series);
    }

    function Save() {
        $series = new CustomSeriesModel();

        try {
            if (isset($_POST["SeriesName"])) { $series->SeriesName = $_POST["SeriesName"]; }
            if (isset($_POST["Overview"])) { $series->Overview = $_POST["Overview"]; }
            if (isset($_POST["Airs_Time"])) { $series->Airs_Time = $_POST["Airs_Time"]; }
            if (isset($_POST["Airs_DayOfWeek"])) { $series->Airs_DayOfWeek = $_POST["Airs_DayOfWeek"]; }

            if (!$series->SeriesName) { throw new Exception("Title can not be empty."); }
            if (!$series->Overview) { throw new Exception("Description can not be empty."); }
            if (!$series->Airs_Time) { throw new Exception("Release time can not be empty."); }
            if (!$series->Airs_DayOfWeek) { throw new Exception("Release day can not be empty."); }

            if (!preg_match("/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/", $series->Airs_Time)) { throw new Exception("Wrong format for release time, correct format is HH:mm"); }

            $series->id = $_GET["id"];
            $series->facebook = FacebookManager::FacebookUser();

            $stmt = $this->DatabaseMgr->Prepare(@"UPDATE custom_series SET SeriesName = :SeriesName, Airs_Time = :Airs_Time,
                                                Airs_DayOfWeek = :Airs_DayOfWeek, Overview = :Overview WHERE id = :id AND facebook = :facebook", MAIN);
            $stmt->execute((array) $series);
            return $this->Json((array) $series);

        } catch (Exception $e) {
            return $this->Json($e->getMessage(), false);
        } 
    }
}

?>
