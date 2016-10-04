<?php
class ReportsBackoffice extends Model{
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
            if ($key != 'text') {
                $input = is_array($input) ? FormInput::trimArray($input) : FormInput::checkInput($input);
            }
            $return[$key] = $input;
        }
        return $return;
    }

    /**
	 * FUNCTION: selectDataByID
	 * This function gets reports information for backoffice
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
	 * FUNCTION: getAllData
	 * This function returns the details for All reports
	 * @param int $limit, $keywords
	 */
	public function getAllData($limit = false, $keywords = false, $active = false){
        $optLimit = $limit != false ? " LIMIT $limit" : "";
        $optActive = $active != false ? " AND t1.is_active = 1" : "";
        $optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t2.title),' ',CONCAT(LOWER(t2.title),' ')),IF(isnull(t3.firstname),' ',CONCAT(LOWER(t3.firstname),' ')),IF(isnull(t3.firstname),' ',CONCAT(LOWER(t3.firstname),' ')),IF(isnull(t3.surname),' ',CONCAT(LOWER(t3.surname),' ')),IF(isnull(t4.firstname),' ',CONCAT(LOWER(t4.firstname),' ')),IF(isnull(t4.surname),' ',CONCAT(LOWER(t4.surname),' '))) LIKE '%$keywords%'" : "";

        $sql = "SELECT t1.*, t2.title as property_title, t2.id as property_id, t3.firstname as lord_firstname, t3.surname as lord_surname, t3.id as lord_id, t4.firstname as tenant_firstname, t4.surname as tenant_surname, t4.id as tenant_id
				FROM reports t1
                    LEFT JOIN properties t2 ON t1.property_id = t2.id
                    LEFT JOIN users t3 ON t1.lord_id = t3.id
                    LEFT JOIN users t4 ON t1.lead_tenant_id = t4.id
				WHERE 1 = 1
				".$optKeywords."
				".$optActive."
                GROUP BY t1.id
				ORDER BY t1.id DESC
				".$optLimit;

		return $this->_db->select($sql);
	}

    /**
	 * FUNCTION: countAllData
	 * This function returns the count for All reports
	 * @param int $keywords
	 */
	public function countAllData($keywords = false, $active = false){
        $optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t2.title),' ',CONCAT(LOWER(t2.title),' ')),IF(isnull(t3.firstname),' ',CONCAT(LOWER(t3.firstname),' ')),IF(isnull(t3.firstname),' ',CONCAT(LOWER(t3.firstname),' ')),IF(isnull(t3.surname),' ',CONCAT(LOWER(t3.surname),' ')),IF(isnull(t4.firstname),' ',CONCAT(LOWER(t4.firstname),' ')),IF(isnull(t4.surname),' ',CONCAT(LOWER(t4.surname),' '))) LIKE '%$keywords%'" : "";
        $optActive = $active != false ? " AND t1.is_active = 1" : "";

		$sql = "SELECT COUNT(t1.id) AS total
				FROM reports t1
                    LEFT JOIN properties t2 ON t1.property_id = t2.id
                    LEFT JOIN users t3 ON t1.lord_id = t3.id
                    LEFT JOIN users t4 ON t1.lead_tenant_id = t4.id
				WHERE 1 = 1
				".$optActive."
				".$optKeywords."
                GROUP BY t1.id";
		return $this->_db->select($sql);
	}

    /**
	 * FUNCTION: deleteData
	 * This function deletes an reports
	 * @param Int $id of an reports
	 */
    public function deleteData($id){
        $dbTable = 'reports';
        $where = "`id` = $id";
        $this->_db->delete($dbTable, $where);
		return true;
    }

    /**
	 * FUNCTION: updateData
	 * This function updates reports details for backoffice
	 * @param mixed $data An array of data passed from the Controller
	 */
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
                'keys_acquired' => $data['keys_acquired'],
                'fire_extin' => $data['fire_extin'],
                'fire_blanket' => $data['fire_blanket'],
                'smoke_alarm' => $data['smoke_alarm']
            );
            $where = "`id` = {$data['id']}";

            $this->_db->update($dbTable, $postData, $where);
            return true;
        }
	}


    /**
     * FUNCTION: deleteReportTemplatesByReportId
     * This function deletes report_templates by report_id
     * @param int $report_id 
     */
    public function deleteReportTemplatesByReportId($report_id){
        $dbTable = 'report_templates';
        $where = "`report_id` = $report_id";
        $this->_db->delete($dbTable, $where);
        return true;
    }


    /**
     * FUNCTION: createReportTemplates
     * This function creates report_templates by report_id and template_id
     * @param int $report_id, $template_id
     */
    public function createReportTemplates($report_id, $template_id){
        $dbTable = 'report_templates';
        $postData = array(
            'report_id' => $report_id,
            'template_id' => $template_id,
        );

        $this->_db->insert($dbTable, $postData);

        // Gets Last Insert ID
        return $lastInsertID = $this->_db->lastInsertId('id');
    }

    /**
     * FUNCTION: getReportsByUserIdAndDate
     * This function returns reports that have $user_id and between $date between check_in and out
     * @param int $user_id, string $date
     */
    public function getReportsByUserIdAndDate($user_id, $date){
        $sql = "SELECT t1.id
                FROM reports t1
                WHERE t1.lord_id = :user_id OR t1.lead_tenant_id = :user_id AND :date BETWEEN t1.check_in AND t1.check_out
                GROUP BY t1.id";

        return $this->_db->select($sql, array(':user_id' => $user_id, ':date' => $date));
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
     * FUNCTION: getReportsByIdAndDate
     * This function returns reports that have $report_id and between $date between check_in and out
     * @param int $report_id, string $date
     */
    public function getReportsByIdAndDate($report_id, $date){
        $sql = "SELECT t1.id
                FROM reports t1
                WHERE t1.id = :report_id AND :date BETWEEN t1.check_in AND t1.check_out
                GROUP BY t1.id";

        return $this->_db->select($sql, array(':report_id' => $report_id, ':date' => $date));
    }       

}?>