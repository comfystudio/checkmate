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
     * FUNCTION: selectDataByID
     * This function gets a report
     * @param int $id
     */
    public function selectDataByID($id){
        $sql = "SELECT t1.*, t2.title as property_title, t2.id as property_id, t2.house_number as property_number, t2.address_1 as property_address_1, t2.address_2 as property_address_2, t2.address_3 as property_address_3, t2.address_4 as property_address_4, t2.postcode as property_postcode, t2.image as property_image, t3.firstname as lord_firstname, t3.surname as lord_surname, t3.id as lord_id, t3.email as lord_email, t4.firstname as tenant_firstname, t4.surname as tenant_surname, t4.id as tenant_id, t4.email as tenant_email, GROUP_CONCAT(DISTINCT t5.id separator ',') as check_in_room_ids, GROUP_CONCAT(DISTINCT t6.id separator ',') as check_out_room_ids, GROUP_CONCAT(DISTINCT t7.user_id separator ',') as user_ids
                FROM reports t1
                    LEFT JOIN properties t2 ON t1.property_id = t2.id
                    LEFT JOIN users t3 ON t1.lord_id = t3.id
                    LEFT JOIN users t4 ON t1.lead_tenant_id = t4.id
                    LEFT JOIN check_in_rooms t5 ON t1.id = t5.report_id
                    LEFT JOIN check_out_rooms t6 ON t1.id = t6.report_id
                    LEFT JOIN user_reports t7 ON t1.id = t7.report_id
                WHERE t1.id = :id
                GROUP BY t1.id";

        return $this->_db->select($sql, array(':id' => $id));
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
                // 'check_in' => $data['check_in'],
                // 'check_out' => $data['check_out'],
                'meter_type' => $data['meter_type'],
                'meter_reading' => $data['meter_reading'],
                'meter_measurement' => $data['meter_measurement'],
                'meter_image' => $data['meter_image'][0],
                'tenant_agreement' => $data['tenant_agreement'][0],
                'oil_level' => $data['oil_level'],
                'keys_acquired' => $data['keys_acquired'],
                'fire_extin' => $data['fire_extin'][0],
                'fire_blanket' => $data['fire_blanket'],
                'smoke_alarm' => $data['smoke_alarm'],
                'tenant_approved_check_in' => $data['tenant_approved_check_in'],
                'lord_approved_check_in' => $data['lord_approved_check_in'],
                'tenant_approved_check_out' => $data['tenant_approved_check_out'],
                'lord_approved_check_out' => $data['lord_approved_check_out']
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
                WHERE t1.property_id = :property_id AND :date BETWEEN DATE_ADD(t1.check_in, INTERVAL -7 DAY) AND DATE_ADD(t1.check_out, INTERVAL 7 DAY)
                GROUP BY t1.id";

        return $this->_db->select($sql, array(':property_id' => $property_id, ':date' => $date));
    }

    /**
     * FUNCTION: getCheckInReportsByPropertyId
     * This function returns reports that have $user_id and between $date between check_in and out
     * @param int $property_id
     */
    public function getCheckInReportsByPropertyId($property_id){
        $sql = "SELECT t1.*, t2.title as property_title, t2.id as property_id, t3.firstname as lord_firstname, t3.surname as lord_surname, t3.id as lord_id, t3.email as lord_email, t4.firstname as tenant_firstname, t4.surname as tenant_surname, t4.email as tenant_email, t4.id as tenant_id, GROUP_CONCAT(DISTINCT t5.id separator ',') as check_in_room_ids, GROUP_CONCAT(DISTINCT t6.id separator ',') as check_out_room_ids
                FROM reports t1
                    LEFT JOIN properties t2 ON t1.property_id = t2.id
                    LEFT JOIN users t3 ON t1.lord_id = t3.id
                    LEFT JOIN users t4 ON t1.lead_tenant_id = t4.id
                    LEFT JOIN check_in_rooms t5 ON t1.id = t5.report_id
                    LEFT JOIN check_out_rooms t6 ON t1.id = t6.report_id
                WHERE t1.property_id = :property_id AND t1.check_in BETWEEN  DATE_ADD(NOW(), INTERVAL -7 DAY) AND DATE_ADD(NOW(), INTERVAL 7 DAY)
                GROUP BY t1.id";
        return $this->_db->select($sql, array(':property_id' => $property_id));
    }

    /**
     * FUNCTION: getCheckOutReportsByPropertyId
     * This function returns reports that have $user_id and between $date between check_in and out
     * @param int $property_id
     */
    public function getCheckOutReportsByPropertyId($property_id){
        $sql = "SELECT t1.*, t2.title as property_title, t2.id as property_id, t3.firstname as lord_firstname, t3.surname as lord_surname, t3.id as lord_id, t3.email as lord_email, t4.firstname as tenant_firstname, t4.surname as tenant_surname, t4.email as tenant_email, t4.id as tenant_id, GROUP_CONCAT(DISTINCT t5.id separator ',') as check_in_room_ids, GROUP_CONCAT(DISTINCT t6.id separator ',') as check_out_room_ids
                FROM reports t1
                    LEFT JOIN properties t2 ON t1.property_id = t2.id
                    LEFT JOIN users t3 ON t1.lord_id = t3.id
                    LEFT JOIN users t4 ON t1.lead_tenant_id = t4.id
                    LEFT JOIN check_in_rooms t5 ON t1.id = t5.report_id
                    LEFT JOIN check_out_rooms t6 ON t1.id = t6.report_id
                WHERE t1.property_id = :property_id AND t1.check_out BETWEEN  DATE_ADD(NOW(), INTERVAL -7 DAY) AND DATE_ADD(NOW(), INTERVAL 7 DAY)
                GROUP BY t1.id";
        return $this->_db->select($sql, array(':property_id' => $property_id));
    }

    /**
     * FUNCTION: getUserReports
     * This function returns the user reports based on report_id
     * @param int $property_id
     */
    public function getUserReports($report_id){
        $sql = "SELECT GROUP_CONCAT(t1.user_id) as user_id
                FROM user_reports t1
                WHERE t1.report_id = :report_id";
        return $this->_db->select($sql, array(':report_id' => $report_id));
    }


    /**
     * FUNCTION: getUpcomingCheckIns
     * This function returns checkins that are within the time frame and not completed
     */
    public function getUpcomingCheckIns(){
        $sql = "SELECT t1.id, t1.property_id, GROUP_CONCAT(DISTINCT t2.user_id separator ',') as user_ids, GROUP_CONCAT(t2.check_in_signature separator ',') as signatures
                FROM reports t1
                    LEFT JOIN user_reports t2 ON t1.id = t2.report_id
                WHERE  NOW() BETWEEN DATE_ADD(t1.check_in, INTERVAL -7 DAY) AND DATE_ADD(t1.check_in, INTERVAL 7 DAY)
                GROUP BY t1.id";

        return $this->_db->select($sql);
    }

    /**
     * FUNCTION: getUpcomingCheckOuts
     * This function returns checkouts that are within the time frame and not completed
     */
    public function getUpcomingCheckOuts(){
        $sql = "SELECT t1.id, t1.property_id, GROUP_CONCAT(DISTINCT t2.user_id separator ',') as user_ids, GROUP_CONCAT(t2.check_out_signature separator ',') as signatures
                FROM reports t1
                    LEFT JOIN user_reports t2 ON t1.id = t2.report_id
                WHERE  NOW() BETWEEN DATE_ADD(t1.check_out, INTERVAL -7 DAY) AND DATE_ADD(t1.check_out, INTERVAL 7 DAY)
                GROUP BY t1.id";

        return $this->_db->select($sql);
    }

    /**
     * FUNCTION: getExpiredCheckIns
     * This function returns check ins that have just expired past the editable date and have not been approved by both parties.
     */
    public function getExpiredCheckIns(){
        $sql = "SELECT t1.id, t1.property_id, GROUP_CONCAT(DISTINCT t2.user_id separator ',') as user_ids, GROUP_CONCAT(t2.check_in_signature separator ',') as signatures
                FROM reports t1
                    LEFT JOIN user_reports t2 ON t1.id = t2.report_id
                WHERE (t1.tenant_approved_check_in = 0 OR t1.lord_approved_check_in = 0) AND DATE_ADD(t1.check_in, INTERVAL 8 DAY) = CURDATE()
                GROUP BY t1.id";
        return $this->_db->select($sql);
    }

    /**
     * FUNCTION: getExpiredCheckOuts
     * This function returns check outs that have just expired past the editable date and have not been approved by both parties.
     */
    public function getExpiredCheckOuts(){
        $sql = "SELECT t1.id, t1.property_id, GROUP_CONCAT(DISTINCT t2.user_id separator ',') as user_ids, GROUP_CONCAT(t2.check_out_signature separator ',') as signatures
                FROM reports t1
                    LEFT JOIN user_reports t2 ON t1.id = t2.report_id
                WHERE (t1.tenant_approved_check_out = 0 OR t1.lord_approved_check_out = 0) AND DATE_ADD(t1.check_out, INTERVAL 8 DAY) = CURDATE()
                GROUP BY t1.id";
        return $this->_db->select($sql);
    }


    

}?>