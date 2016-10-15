<?php
class ContactUs extends Model{
    /** __construct */
    public function __construct(){
        parent::__construct();
    }


    /**
     * FUNCTION: selectDataByID
     * This function gets about_us information for backoffice
     * @param int $id
     */
    public function selectDataByID($id){
        $sql = "SELECT t1.*
				FROM contact_us t1
				WHERE t1.id = :id
				GROUP BY t1.id";

        return $this->_db->select($sql, array(':id' => $id));
    }

    /**
     * FUNCTION: getAllData
     * This function returns the details for All contacts
     * @param int $limit, $keywords
     */
    public function getAllData($limit = false, $keywords = false){
        $optLimit = $limit != false ? " LIMIT $limit" : "";
        $optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t1.facebook),' ',CONCAT(LOWER(t1.facebook),' ')), IF(isnull(t1.instagram),' ',CONCAT(LOWER(t1.instagram),' ')), IF(isnull(t1.email),' ',CONCAT(LOWER(t1.email),' '))) LIKE '%$keywords%'" : "";

        $sql = "SELECT t1.*
				FROM contact_us t1
				WHERE 1 = 1
				".$optKeywords."
				GROUP BY t1.id
				ORDER BY t1.id DESC
				".$optLimit;

        return $this->_db->select($sql);
    }

    /**
     * FUNCTION: countAllData
     * This function returns the count for All contacts
     * @param int $keywords
     */
    public function countAllData($keywords = false){
        $optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t1.facebook),' ',CONCAT(LOWER(t1.facebook),' ')), IF(isnull(t1.instagram),' ',CONCAT(LOWER(t1.instagram),' ')), IF(isnull(t1.email),' ',CONCAT(LOWER(t1.email),' '))) LIKE '%$keywords%'" : "";

        $sql = "SELECT COUNT(t1.id) AS total
				FROM contact_us t1
				WHERE 1 = 1
				".$optKeywords;
        return $this->_db->select($sql);
    }

}?>