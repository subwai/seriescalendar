<?php

class PaginationHelper {

	private $list;
	private $limit;
	private $offset;
	private $total;
	private $baseUrl;

	public function __construct($list = array(), $limit = 1, $offset = 0, $total = 0, $baseUrl = "") {
		$keys = count($list) > 0 ? array_fill($offset, count($list), null) : array();
		$this->list = count($list) > 0 ? array_combine(array_keys($keys), $list) : array();
		$this->limit = $limit;
		$this->offset = $offset;
		$this->total = $total;
		$this->baseUrl = $baseUrl;
	}

	public function getPage($page) {
		$items = array();
		$offset = ($page-1)*$this->limit;

		for ($i=$offset; $i < $offset+$this->limit; $i++) { 
			if (isset($this->list[$i])) {
				$items[] = $this->list[$i];
			}
		}
		return $items;
	}

	public function getLastPageNr() {
		return ceil((float)$this->total/(float)$this->limit);
	}

	public function getPagination($page) {

		$low = $page-2;
		$high = $page+2;
		$last = $this->getLastPageNr();
		$pagination = array();
		for ($i=1; $i <= $last; $i++) {
			if ($i < $low) {
				if ($i == 1) {
					$pagination[] = array("page" => "First", "link" => $this->baseUrl."/1");
					$pagination[] = array("page" => "...", "link" => false);
					$i = $low-1;
				}
				continue;
			}

			if ($i > $high) {
				if ($i == $last) {
					$pagination[] = array("page" => "...", "link" => false);
					$pagination[] = array("page" => "Last", "link" => $this->baseUrl."/".$last);
				}
				continue;
			}	
			$pagination[] = array("page" => $i, "link" => $this->baseUrl."/".$i);
		}

		return $pagination;
	}
}

?>
