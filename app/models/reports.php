<?php
class Reports extends Model{
	/** __construct */
	public function __construct(){
		parent::__construct();
	}



    /**
	 * FUNCTION: createData
	 * This function adds a new User to the Database from backoffice
	 * @param mixed $data Array of User Data
	 */
	public function createData($data){
        //$data = $this->validation($data, 'add');
        //if(isset($data['error']) && $data['error'] != null) {
            //return $data;
       // }else {
            $dbTable = 'notifications';
            $postData = array(
                'user_id' => $data['user_id'],
                'text' => $data['text'],
            );
            $this->_db->insert($dbTable, $postData);
            // Gets Last Insert ID
            return $lastInsertID = $this->_db->lastInsertId('id');
       // }
	}

    /**
     * FUNCTION: getAlldataByTenantId
     * This function returns the details for reports based on user_id = tenant_id
     * @param int $user_id
     */
    public function getAlldataByTenantId($user_id){
        $sql = "SELECT t1.*, t2.title, t2.image
                FROM reports t1
                    LEFT JOIN properties t2 ON t1.property_id = t2.id
                WHERE t1.lead_tenant_id = 10 AND NOW() BETWEEN (t1.check_in - INTERVAL 7 DAY) AND (t1.check_out + INTERVAL 4 DAY)                
                GROUP BY t1.id
                ORDER BY t1.id
                ";
        return $this->_db->select($sql, array(':user_id' => $user_id));
    }

    /**
     * FUNCTION: getReportsByUserIdAndDate
     * This function returns reports that have $user_id and between $date between check_in and out
     * @param int $user_id, string $date
     */
    public function getReportsByPropertyIdAndDate($property_id, $date){
        $sql = "SELECT t1.id
                FROM reports t1
                WHERE t1.property_id = :property_id AND :date BETWEEN t1.check_in AND t1.check_out
                GROUP BY t1.id";

        return $this->_db->select($sql, array(':property_id' => $property_id, ':date' => $date));
    }

}?>