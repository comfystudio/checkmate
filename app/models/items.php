<?php
class Items extends Model{
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

            //name
            if($key == 'name'){
                //Max length
                $temp = Form::MaxLength($input, 100);
                if($temp[0] != true){
                    $return['error'][$key] = 'Name should not exceed 100 characters';
                }

                //Required
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Name cannot be empty';
                }
            }
        }
        return $return;
    }

    /**
	 * FUNCTION: selectDataByID
	 * This function gets items information for backoffice
	 * @param int $id
	 */
	public function selectDataByID($id){
		$sql = "SELECT t1.id, t1.name, t1.is_active
				FROM items t1
				WHERE t1.id = :id";

		return $this->_db->select($sql, array(':id' => $id));
	}

    /**
	 * FUNCTION: getAllData
	 * This function returns the details for All items
	 * @param int $limit, $keywords
	 */
	public function getAllData($limit = false, $keywords = false, $active = false){
        $optLimit = $limit != false ? " LIMIT $limit" : "";
        $optActive = $active != false ? " AND t1.is_active = 1" : "";
        $optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t1.id),' ',CONCAT(LOWER(t1.id),' ')),IF(isnull(t1.name),' ',CONCAT(LOWER(t1.name),'  '))) LIKE '%$keywords%'" : "";

        $sql = "SELECT t1.id, t1.name, t1.is_active
				FROM items t1
				WHERE 1 = 1
				".$optKeywords."
				".$optActive."
				ORDER BY t1.id DESC
				".$optLimit;

		return $this->_db->select($sql);
	}

    /**
	 * FUNCTION: countAllData
	 * This function returns the count for All items
	 * @param int $keywords
	 */
	public function countAllData($keywords = false, $active = false){
        $optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t1.id),' ',CONCAT(LOWER(t1.id),' ')),IF(isnull(t1.name),' ',CONCAT(LOWER(t1.name),'  '))) LIKE '%$keywords%'" : "";
        $optActive = $active != false ? " AND t1.is_active = 1" : "";

		$sql = "SELECT COUNT(t1.id) AS total
				FROM items t1
				WHERE 1 = 1
				".$optActive."
				".$optKeywords;
		return $this->_db->select($sql);
	}

    /**
	 * FUNCTION: createData
	 * This function adds a new items to the Database from backoffice
	 * @param mixed $data Array of items Data
	 */
	public function createData($data){
        $data = $this->validation($data, 'add');
        if(isset($data['error']) && $data['error'] != null){
            return $data;
        }else {
            $dbTable = 'items';
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
	 * This function updates items details for backoffice
	 * @param mixed $data An array of data passed from the Controller
	 */
	public function updateData($data){
        $data = $this->validation($data, 'edit');
        if(isset($data['error']) && $data['error'] != null){
            return $data;
        }else {
            $dbTable = 'items';
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
	 * This function deletes an items
	 * @param Int $id of an items
	 */
    public function deleteData($id){
        $dbTable = 'items';
        $where = "`id` = $id";
        $this->_db->delete($dbTable, $where);
		return true;
    }

    /**
	 * FUNCTION: getDataByID
	 * This function gets the user by their ID
	 * @param int $id
	 */
	public function getDataByID($id){
        $sql = "SELECT t1.id, t1.name, t1.is_active
				FROM items t1
				WHERE t1.id = :id";

        return $this->_db->select($sql, array(':id' => $id));
    }

    /**
     * FUNCTION: selectCheckInItemsByID
     * This function gets the checked In items by id
     * @param int $check_in_item_id
     */
    public function selectCheckInItemsByID($check_in_item_id){
        $sql = "SELECT t1.*, t2.name
                FROM check_in_items t1
                    LEFT JOIN items t2 ON t1.item_id = t2.id
                WHERE t1.id = :check_in_item_id
                GROUP BY t1.id";
        return $this->_db->select($sql, array(':check_in_item_id' => $check_in_item_id));
    }

    /**
     * FUNCTION: createCheckInItem
     * This function creates a check in item
     * @param int $report_rooms_id, $item_id
     */
    public function createCheckInItem($report_rooms_id, $item_id){
        $dbTable = 'check_in_items';
        $postData = array(
            'report_rooms_id' => $report_rooms_id,
            'item_id' => $item_id
        );

        $this->_db->insert($dbTable, $postData);
    }

    /**
     * FUNCTION: createCheckOutItem
     * This function creates a check out Item
     * @param int $report_rooms_id, $item_id
     */
    public function createCheckOutItem($report_rooms_id, $item_id){
        $dbTable = 'check_out_items';
        $postData = array(
            'report_rooms_id' => $report_rooms_id,
            'item_id' => $item_id
        );

        $this->_db->insert($dbTable, $postData);
    }

    /**
     * FUNCTION: createCheckInItemTenant
     * This function creates a check in item based on tenant criteria
     * @param array $data
     */
    public function createCheckInItemTenant($data){
        $dbTable = 'check_in_items';
        $postData = array(
            'item_id' => $data['item_id'],
            'report_rooms_id' => $data['report_rooms_id'],
            'tenant_comment' => $data['tenant_comment'],
            'tenant_approved' => $data['tenant_approved'],
            'image' => $data['image'][0],
        );

        $this->_db->insert($dbTable, $postData);
        return $lastInsertID = $this->_db->lastInsertId('id');
    }

    /**
     * FUNCTION: selectCheckOutItemsByID
     * This function gets the checked out items by id
     * @param int $check_out_item_id
     */
    public function selectCheckOutItemsByID($check_out_item_id){
        $sql = "SELECT t1.*, t2.name
                FROM check_out_items t1
                    LEFT JOIN items t2 ON t1.item_id = t2.id
                WHERE t1.id = :check_out_item_id
                GROUP BY t1.id";
        return $this->_db->select($sql, array(':check_out_item_id' => $check_out_item_id));
    }


    /**
     * FUNCTION: updateCheckInItem
     * This function updates check in items 
     * @param mixed $data An array of data passed from the Controller
     */
    public function updateCheckInItem($data){
        $dbTable = 'check_in_items';
        $postData = array(
            'tenant_comment' => $data['tenant_comment'],
            'lord_comment' => $data['lord_comment'],
            'status' => $data['status'],
            'tenant_approved' => $data['tenant_approved'],
            'lord_approved' => $data['lord_approved'],
            'image' => $data['image'][0],
            'lord_image' => $data['lord_image'][0]
        );
        $where = "`id` = {$data['id']}";

        $this->_db->update($dbTable, $postData, $where);
        return true;
    }

    /**
     * FUNCTION: updateCheckOutItem
     * This function updates check out items 
     * @param mixed $data An array of data passed from the Controller
     */
    public function updateCheckOutItem($data){
        $dbTable = 'check_out_items';
        $postData = array(
            'tenant_comment' => $data['tenant_comment'],
            'lord_comment' => $data['lord_comment'],
            'status' => $data['status'],
            'tenant_approved' => $data['tenant_approved'],
            'lord_approved' => $data['lord_approved'],
            'image' => $data['image'][0],
            'lord_image' => $data['lord_image'][0]
        );
        $where = "`id` = {$data['id']}";

        $this->_db->update($dbTable, $postData, $where);
        return true;
    }

}?>