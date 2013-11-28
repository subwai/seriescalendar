<?php
class PDOStatementWrapper extends PDOStatement {
	private $ErrorMgr;
	private $database_name;
	private $schema_name;

	private function __construct($conn, $errorMgr, $database_name, $schema_name) {
		$this->ErrorMgr = $errorMgr;
		$this->database_name = $database_name;
		$this->schema_name = $schema_name;
	}

	public function execute($args = null) {
		try {
			return parent::execute($args);
		} catch(PDOException $e) {
            $this->ErrorMgr->SetCustomError(sprintf("<%s:%s> - Error while executing statement: <br>%s", $this->database_name, $this->schema_name, $e->getMessage()), 5, false);
            return false;
        }
	}
}
?>
