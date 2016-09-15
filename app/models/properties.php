<?php
class Properties extends Model{
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
     * FUNCTION: getAlldataByUserId
     * This function returns the details for properties based on user_id
     * @param int $user_id
     */
    public function getAlldataByUserId($user_id){
        $sql = "SELECT t1.*, max(t2.check_in) as check_in, max(t2.check_out) as check_out, max(t2.id)  as report_id
                FROM properties t1
                    LEFT JOIN reports t2 ON t1.id = t2.property_id
                    -- LEFT JOIN reports t2 ON t1.id = ( SELECT reports.property_id
                    --                                      FROM reports
                    --                                      WHERE reports.property_id = t1.id
                    --                                      ORDER BY reports.id ASC
                    --                                      LIMIT 1
                    --                                    )
                WHERE t1.created_by = :user_id
                GROUP BY t1.id
                ORDER BY t2.check_in DESC
                ";

        return $this->_db->select($sql, array(':user_id' => $user_id));
    }

}?>