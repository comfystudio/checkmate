<?php
class rooms extends Model{
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

            //name
            if($key == 'name'){
                //required
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Room Name cannot be empty';
                }else{
                    // Creating slug based off formatted title.
     //                $temp = Formatting::removeAccents($input);
					// $temp = FormatUrl($temp);
     //                $return['slug'] = $temp;
                }
            }
        }
        return $return;
    }

    /**
	 * FUNCTION: selectDataByID
	 * This function gets news information for backoffice
	 * @param int $id
	 */
	public function selectDataByID($id){
		$sql = "SELECT t1.id, t1.name, t1.is_active, GROUP_CONCAT(DISTINCT t3.id) AS items
				FROM rooms t1
				    LEFT JOIN room_items t2 ON t1.id = t2.room_id
				        LEFT JOIN items t3 ON t2.item_id = t3.id AND t3.is_active = 1
				WHERE t1.id = :id
                GROUP BY t1.id
                ";

		return $this->_db->select($sql, array(':id' => $id));
	}

    /**
	 * FUNCTION: getAllData
	 * This function returns the details for All rooms
	 * @param int $limit, $keywords
	 */
	public function getAllData($limit = false, $keywords = false){
        $optLimit = $limit != false ? " LIMIT $limit" : "";
        $optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t1.name),' ',CONCAT(LOWER(t1.name),' '))) LIKE '%$keywords%'" : "";

		$sql = "SELECT t1.id, t1.name, t1.is_active, GROUP_CONCAT(DISTINCT t3.name SEPARATOR ', ') AS items
				FROM rooms t1
                    LEFT JOIN room_items t2 ON t1.id = t2.room_id
                        LEFT JOIN items t3 ON t2.item_id = t3.id AND t3.is_active = 1
				WHERE 1 = 1
				".$optKeywords."
                GROUP BY t1.id
				ORDER BY t1.id DESC
				".$optLimit
                ;

		return $this->_db->select($sql);
	}

    /**
	 * FUNCTION: countAllData
	 * This function returns the count for All rooms
	 * @param int $keywords
	 */
	public function countAllData($keywords = false){
        $optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t1.name),' ',CONCAT(LOWER(t1.name),' '))) LIKE '%$keywords%'" : "";

		$sql = "SELECT COUNT(t1.id) AS total
				FROM rooms t1
				WHERE 1 = 1
				".$optKeywords;
		return $this->_db->select($sql);
	}

    /**
	 * FUNCTION: createData
	 * This function adds a new rooms to the Database from backoffice
	 * @param mixed $data Array of news Data
	 */
	public function createData($data){
        $data = $this->validation($data, 'add');
        if(isset($data['error']) && $data['error'] != null){
            return $data;
        }else {
            $dbTable = 'rooms';
            $postData = array(
                'name' => $data['name'],                
                'is_active' => $data['is_active']

            );

            $this->_db->insert($dbTable, $postData);

            // Gets Last Insert ID
            return $lastInsertID = $this->_db->lastInsertId('id');
        }
	}

    /**
	 * FUNCTION: updateData
	 * This function updates news details for backoffice
	 * @param mixed $data An array of data passed from the Controller
	 */
	public function updateData($data){
        $data = $this->validation($data, 'edit');
        if(isset($data['error']) && $data['error'] != null){
            return $data;
        }else {
            $dbTable = 'rooms';
            $postData = array(
                'name' => $data['name'],
                'is_active' => $data['is_active']
            );
            $where = "`id` = {$data['id']}";

            $this->_db->update($dbTable, $postData, $where);
            return true;
        }
	}

    /**
	 * FUNCTION: deleteData
	 * This function deletes an news
	 * @param Int $id of an news
	 */
    public function deleteData($id){
        $dbTable = 'rooms';
        $where = "`id` = $id";
        $this->_db->delete($dbTable, $where);
		return true;
    }

    /**
	 * FUNCTION: createRoomItems
	 * This function adds a new room item
	 * @param int $room_id, int $item_id
	 */
    public function createRoomItems($room_id, $item_id){
        $dbTable = 'room_items';
        $postData = array(
            'room_id' => $room_id,
            'item_id' => $item_id
        );

        $this->_db->insert($dbTable, $postData);
    }

    /**
	 * FUNCTION: deleteRoomItemsById
	 * This function deletes all room Items by room_id
	 * @param int $news_id
	 */
    public function deleteRoomItemsById($room_id){
        $dbTable = 'room_items';
        $where = "`room_id` = $room_id";
        $this->_db->delete($dbTable, $where);
		return true;
    }


    /**
     * FUNCTION: selectCheckInRoomsByID
     * This function get checkinrooms by id
     * @param int $check_in_room_id
     */
    public function selectCheckInRoomsByID($check_in_room_id){
        $sql = "SELECT t1.id, t1.room_id, t1.clean, t1.tenant_comment, t1.lord_comment, t2.name,  GROUP_CONCAT(t3.id) AS check_in_item_ids
                FROM check_in_rooms t1
                    LEFT JOIN rooms t2 ON t1.room_id = t2.id
                    LEFT JOIN check_in_items t3 ON t1.id = t3.report_rooms_id
                WHERE t1.id = :check_in_room_id
                GROUP BY t1.id
                ";

        return $this->_db->select($sql, array(':check_in_room_id' => $check_in_room_id));
    }

    /**
     * FUNCTION: createCheckInRoom
     * This function adds a new check_in_room
     * @param int $report_id, int $room_id
     */
    public function createCheckInRoom($report_id, $room_id){
        $dbTable = 'check_in_rooms';
        $postData = array(
            'report_id' => $report_id,
            'room_id' => $room_id
        );

        $this->_db->insert($dbTable, $postData);
        // Gets Last Insert ID
        return $lastInsertID = $this->_db->lastInsertId('id');
    }

    /**
     * FUNCTION: createCheckOutRoom
     * This function adds a new check_out_room
     * @param int $report_id, int $room_id
     */
    public function createCheckOutRoom($report_id, $room_id){
        $dbTable = 'check_out_rooms';
        $postData = array(
            'report_id' => $report_id,
            'room_id' => $room_id
        );

        $this->_db->insert($dbTable, $postData);
        // Gets Last Insert ID
        return $lastInsertID = $this->_db->lastInsertId('id');
    }

    /**
     * FUNCTION: selectCheckOutRoomsByID
     * This function get checkoutrooms by id
     * @param int $check_out_room_id
     */
    public function selectCheckOutRoomsByID($check_out_room_id){
        $sql = "SELECT t1.id, t1.room_id, t1.clean, t1.tenant_comment, t1.lord_comment, t2.name, GROUP_CONCAT(t3.id) AS check_out_item_ids
                FROM check_out_rooms t1
                    LEFT JOIN rooms t2 ON t1.room_id = t2.id
                    LEFT JOIN check_out_items t3 ON t1.id = t3.report_rooms_id
                WHERE t1.id = :check_out_room_id
                GROUP BY t1.id
                ";

        return $this->_db->select($sql, array(':check_out_room_id' => $check_out_room_id));
    }

    /**
     * FUNCTION: updateCheckInRoom
     * This function updates check_in_rooms
     * @param mixed $data An array of data passed from the Controller
     */
    public function updateCheckInRoom($data){
        $dbTable = 'check_in_rooms';
        $postData = array(
            'clean' => $data['clean'],
            'tenant_comment' => $data['tenant_comment'],
            'lord_comment' => $data['lord_comment']
        );
        $where = "`id` = {$data['id']}";

        $this->_db->update($dbTable, $postData, $where);
        return true;
    }

    /**
     * FUNCTION: updateCheckOutRoom
     * This function updates check_out_rooms
     * @param mixed $data An array of data passed from the Controller
     */
    public function updateCheckOutRoom($data){
        $dbTable = 'check_out_rooms';
        $postData = array(
            'clean' => $data['clean'],
            'tenant_comment' => $data['tenant_comment'],
            'lord_comment' => $data['lord_comment']
        );
        $where = "`id` = {$data['id']}";

        $this->_db->update($dbTable, $postData, $where);
        return true;
    }


    /**
     * FUNCTION: deleteCheckOutRoomsByReportId
     * This function deletes all check out rooms by report_id
     * @param int $report_id
     */
    public function deleteCheckOutRoomsByReportId($report_id){
        $dbTable = 'check_out_rooms';
        $where = "`report_id` = $report_id";
        $this->_db->delete($dbTable, $where);
        return true;
    }

}?>