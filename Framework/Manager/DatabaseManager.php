<?php

class DatabaseManager {

    private $ErrorMgr;

    private $ConnectionList = array();
    private $ConnectionReferenceList = array();
    private $OpenConnections = array();

    private $totalRequests;

    function __construct($ini, $errorMgr) {
        $this->ErrorMgr = $errorMgr;

        foreach ($ini as $section => $settings) {
            if (strpos($section, "db_") === 0) {
                $this->ConnectionList[] = new Connection(array(
                    "Name"      => $section,
                    "Hostname"  => $settings["hostname"],
                    "Username"  => $settings["username"],
                    "Password"  => $settings["password"],
                    "Databases" => $settings["database"]
                ));
            }
        }

        foreach ($this->ConnectionList as $connectionID => $connection) {
            foreach ($connection->getDatabases() as $key => $value) {
                $this->ConnectionReferenceList[$key] = $connectionID;
                define($key, $key);
            }
        }
    }

    private function StartConnection(&$database) {
        $this->FetchDatabaseAndSchema($database, $schema);
        if (!isset($this->OpenConnections[$database])) {
            try {
                $conn = $this->OpenConnections[$database] = $this->ConnectionList[$this->ConnectionReferenceList[$database]]->Connect($schema);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                // $this->ErrorMgr->SetError(100);
                $this->ErrorMgr->SetCustomError(utf8_encode($e->getMessage()));
                return false;
            }   
        }
        return $this->OpenConnections[$database];
    }

    private function FetchDatabaseAndSchema(&$database, &$schema = "") {
        if (is_a($database, "MysqlDatabase")) {
            $schema = $database->getName();
            $database = $database->getReference()."_".$database->getName();
        } else {
            $databaseList = $this->ConnectionList[$this->ConnectionReferenceList[$database]]->getDatabases();
            $schema = $databaseList[$database];
        }
    }

    public function Execute($query, $database) {
        if ($conn = $this->StartConnection($database)) {
            try {
                $stmt = $conn->query($query);
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $this->totalRequests++;
                return $stmt;
            } catch(PDOException $e) {
                $this->ErrorMgr->SetCustomError(sprintf("<%s:%s> - Error while executing query: <br>%s",
                        $this->ConnectionList[$this->ConnectionReferenceList[$database]]->getName(), $database, $e->getMessage()), 5, false);
            }
        }
    }

    public function Prepare($query, $database) {
        if ($conn = $this->StartConnection($database)) {
            $conn->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('PDOStatementWrapper', array($conn, $this->ErrorMgr, $this->ConnectionList[$this->ConnectionReferenceList[$database]]->getName(), $database)));
            $stmt = $conn->prepare($query);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $this->totalRequests++;
            return $stmt;
        }
    }

    public function GetConnection($database) {
        $this->FetchDatabaseAndSchema($database, $schema);
        return $this->OpenConnections[$database];
    }

    public function EndAllDBSession() {
        foreach ($this->OpenConnections as $connection) {
            $connection->close();
        }
    }

    public function GetTotalRequests() {
        return $this->totalRequests;
    }
}

?>
