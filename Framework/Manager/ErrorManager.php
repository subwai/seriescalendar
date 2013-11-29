<?php

abstract class ErrorManager {
    protected $ErrorList;
    protected $Error;
    protected $ErrorID;

    public function SetError($id) {
        if (!$this->HasError()) {
            if (isset($this->ErrorList[$id])) {
                $this->Error = $this->ErrorList[$id];
                $this->ErrorId = $id;
            } else {
                $this->Error = new ErrorModel("Undefined error: ".$id, 3, false);
            }
        }
    }

    public function SetCustomError($error, $errorLevel = 2, $autoClose = true) {
        $this->Error = new ErrorModel($error, $errorLevel, $autoClose);
        $this->ErrorID = 0;
    }

    public function GetError() {
        return $this->Error;
    }

    public function GetErrorID() {
        return $this->ErrorID;
    }

    public function HasError() {
        if (isset($this->Error)) {
            return true;
        } else {
            return false;
        }
    }

    public function SetPartialData($data) {
        if ($this->HasError()) {
            $this->Error->PartialData = $data;
        }
    }
}

class ErrorModel {

    public $Text;
    public $ErrorLevel;
    public $AutoClose;
    public $Partial;
    public $PartialData;

    public function __construct($text, $errorLevel = 2, $autoClose = true) {
        $this->Text = $text;
        $this->ErrorLevel = $errorLevel;
        $this->AutoClose = $autoClose;
    }
}

?>
