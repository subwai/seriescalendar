<?php
namespace Model;

class Series {
    public $id;
    public $SeriesName;
    public $Airs_Time;
    public $Airs_DayOfWeek;
    public $Overview;
    public $Actors;
    public $Genre;
    public $FirstAired;
    public $IMDB_ID;
    public $Rating;
    public $Status;
    public $banner;
    public $fanart;
    public $poster;

    function __construct() {
        $now = new \DateTime();
        $airs = new \DateTime($this->Airs_Time, new \DateTimeZone("America/Chicago"));
        $airs->setISODate($now->format("Y"), $now->format("W"), $this->Airs_DayOfWeek);
        $airs->setTimezone(new \DateTimeZone("Europe/Stockholm"));
        $this->Airs_Time = $airs->format("H:i");
        $this->Airs_DayOfWeek = $airs->format("w");
    }
}

?>