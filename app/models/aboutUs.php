<?php
class AboutUs extends Model{
	/** __construct */
	public function __construct(){
		parent::__construct();
	}


    /**
	 * FUNCTION: getAllData
	 * This function returns the details for All about_us
	 * @param int $limit, $keywords
	 */
	public function getAllData($limit = false, $keywords = false){
        $optLimit = $limit != false ? " LIMIT $limit" : "";
        $optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t1.text),' ',CONCAT(LOWER(t1.text),' ')),IF(isnull(t1.title),' ',CONCAT(LOWER(t1.title),' '))) LIKE '%$keywords%'" : "";

		$sql = "SELECT t1.*
				FROM about_us t1
				WHERE 1 = 1
				".$optKeywords."
				ORDER BY t1.id DESC
				".$optLimit;

		return $this->_db->select($sql);
	}

}?>