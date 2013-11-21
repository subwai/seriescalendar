<?php

class InfoManager
{
    protected $InfoList;
    protected $Info;
    protected $InfoID;

    public function SetInfo($id) {
        if (!$this->HasInfo()) {
            if (isset($this->InfoList[$id])) {
                $this->Info = $this->InfoList[$id];
                $this->InfoID = $id;
            } else {
                $this->Info = new InfoModel("Undefined info: ".$id, 3, false);
            }
        }
    }

    public function GetInfo() {
        return $this->Info;
    }

    public function GetInfoID() {
        return $this->InfoID;
    }

    public function HasInfo() {
        if (isset($this->Info)) {
            return true;
        } else {
            return false;
        }
    }

    public function SetPartialData($data) {
        if ($this->HasInfo()) {
            $this->Info->PartialData = $data;
        }
    }
}

class InfoModel {

    public $Text;
    public $InfoLevel;
    public $AutoClose;
    public $Partial;
    public $PartialData;

    public function __construct($text, $infoLevel = 2, $autoClose = true, $partial = null) {
        $this->Text = $text;
        $this->InfoLevel = $infoLevel;
        $this->AutoClose = $autoClose;
        $this->Partial = $partial;
    }
}

?>