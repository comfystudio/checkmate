<?php
class News extends Model{
	/** __construct */
	public function __construct(){
		parent::__construct();
	}
    /**
     * FUNCTION: getFooterNews
     * This function returns some news for the footer page
     */
    public function getFooterNews(){
        $sql = "SELECT t1.*
                FROM news t1
                WHERE t1.is_active = 1 AND t1.date < NOW()          
                ORDER BY t1.date DESC
                LIMIT 2
                ";
        return $this->_db->select($sql);
    }

    /**
     * FUNCTION: selectDataBySlug
     * This function gets data based on title
     * @param string $slug
     */
    public function selectDataBySlug($slug){
        $sql = "SELECT t1.*
                FROM news t1
                WHERE t1.slug = :slug AND t1.is_active = 1";

        return $this->_db->select($sql, array(':slug' => $slug));
    }

}?>