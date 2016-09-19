<?php
class Reports extends Model{
	/** __construct */
	public function __construct(){
		parent::__construct();
	}

    /**
     * FUNCTION: validation
     * This function validates post data, and should be called for any update or create model calls.
     * @param mixed array $data, $type can define different standards depending on if edit for example.
     */
    public function validation($data, $type){
        $return = $data;
        foreach($data as $key => $input){
            $temp = null;
            $input = is_array($input) ? FormInput::trimArray($input) : FormInput::checkInput($input);
            $return[$key] = $input;

            //user_name
            // if($key == 'firstname'){
            //     //Max length
            //     $temp = Form::MaxLength($input, 20);
            //     if($temp[0] != true){
            //         $return['error'][$key] = 'Firstname should not exceed 20 characters';
            //     }

            //     //Alphabetic
            //     $temp = Form::ValidateAlphabetic($input);
            //     if($temp != true){
            //         $return['error'][$key] = 'Firstname should contain only alphabetic characters';
            //     }

            //     //Required
            //     if(empty($input) || $input == null){
            //         $return['error'][$key] = 'Firstname cannot be empty';
            //     }
            // }
        }
        return $return;
    }



    /**
	 * FUNCTION: createData
	 * This function adds a new User to the Database from backoffice
	 * @param mixed $data Array of User Data
	 */
	public function createData($data){
        $data = $this->validation($data, 'add');
        if(isset($data['error']) && $data['error'] != null) {
            return $data;
       }else {
            $dbTable = 'reports';
            $postData = array(
                'lord_id' => $data['lord_id'],
                'lead_tenant_id' => $data['lead_tenant_id'],
                'status' => $data['status'],
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
                'meter_type' => $data['meter_type'],
                'meter_reading' => $data['meter_reading'],
                'meter_measurement' => $data['meter_measurement'],
                'oil_level' => $data['oil_level'],
                'keys_acquired' => $data['keys_acquired']
            );
            $this->_db->insert($dbTable, $postData);
            // Gets Last Insert ID
            return $lastInsertID = $this->_db->lastInsertId('id');
       }
	}

    /**
     * FUNCTION: startReport
     * This function starts a new report
     * @param mixed $data Array of User Data
     */
    public function startReport($data){
        $data = $this->validation($data, 'add');
        if(isset($data['error']) && $data['error'] != null) {
            return $data;
       }else {
            $dbTable = 'reports';
            $postData = array(
                'lord_id' => $data['lord_id'],
                'lead_tenant_id' => $data['lead_tenant_id'],
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
                'property_id' => $data['property_id']
            );
            $this->_db->insert($dbTable, $postData);
            // Gets Last Insert ID
            return $lastInsertID = $this->_db->lastInsertId('id');
       }
    }

    public function updateData($data){
        $data = $this->validation($data, 'edit');
        if(isset($data['error']) && $data['error'] != null){
            return $data;
        }else {
            $dbTable = 'reports';
            $postData = array(
                'status' => $data['status'],
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
                'meter_type' => $data['meter_type'],
                'meter_reading' => $data['meter_reading'],
                'meter_measurement' => $data['meter_measurement'],
                'oil_level' => $data['oil_level'],
                'keys_acquired' => $data['keys_acquired']
            );
            $where = "`id` = {$data['id']}";

            $this->_db->update($dbTable, $postData, $where);
            return true;
        }
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