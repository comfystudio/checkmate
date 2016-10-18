<?php
class Templates extends Model{
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
            if($key != 'items'){
                $input = is_array($input) ? FormInput::trimArray($input) : FormInput::checkInput($input);
            }
            $return[$key] = $input;

            //title
            if($key == 'title'){
                //Max length
                $temp = Form::MaxLength($input, 100);
                if($temp[0] != true){
                    $return['error'][$key] = 'Title should not exceed 100 characters';
                }

                //Required
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Title cannot be empty';
                }
            }

            //description
            if($key == 'description'){
                //Description
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Description cannot be empty';
                }
            }
        }
        return $return;
    }

    /**
	 * FUNCTION: selectDataByID
	 * This function gets templates information for backoffice
	 * @param int $id
	 */
	public function selectDataByID($id){
		$sql = "SELECT t1.*, t2.firstname, t2.surname, GROUP_CONCAT(t4.id) as room_ids
				FROM templates t1
                    LEFT JOIN users t2 ON t1.created_by = t2.id
                    LEFT JOIN template_rooms t3 ON t1.id = t3.template_id
                        LEFT JOIN rooms t4 ON t3.room_id = t4.id
				WHERE t1.id = :id";

		return $this->_db->select($sql, array(':id' => $id));
	}

    /**
	 * FUNCTION: getAllData
	 * This function returns the details for All templates
	 * @param int $limit, $keywords
	 */
	public function getAllData($limit = false, $keywords = false, $active = false){
        $optLimit = $limit != false ? " LIMIT $limit" : "";
        $optActive = $active != false ? " AND t1.is_active = 1" : "";
        $optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t1.id),' ',CONCAT(LOWER(t1.id),' ')),IF(isnull(t1.title),' ',CONCAT(LOWER(t1.title),'  ')),IF(isnull(t1.description),' ',CONCAT(LOWER(t1.description),'  ')),IF(isnull(t2.firstname),' ',CONCAT(LOWER(t2.firstname),'  ')),IF(isnull(t2.surname),' ',CONCAT(LOWER(t2.surname),'  '))) LIKE '%$keywords%'" : "";

        $sql = "SELECT t1.*, t2.firstname, t2.surname
				FROM templates t1
                     LEFT JOIN users t2 ON t1.created_by = t2.id
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
	 * This function returns the count for All templates
	 * @param int $keywords
	 */
	public function countAllData($keywords = false, $active = false){
        $optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t1.id),' ',CONCAT(LOWER(t1.id),' ')),IF(isnull(t1.title),' ',CONCAT(LOWER(t1.title),'  ')),IF(isnull(t1.description),' ',CONCAT(LOWER(t1.description),'  ')),IF(isnull(t2.firstname),' ',CONCAT(LOWER(t2.firstname),'  ')),IF(isnull(t2.surname),' ',CONCAT(LOWER(t2.surname),'  '))) LIKE '%$keywords%'" : "";
        $optActive = $active != false ? " AND t1.is_active = 1" : "";

		$sql = "SELECT COUNT(t1.id) AS total
				FROM templates t1
                    LEFT JOIN users t2 ON t1.created_by = t2.id
				WHERE 1 = 1
				".$optActive."
				".$optKeywords."
                GROUP BY t1.id";
		return $this->_db->select($sql);
	}

    /**
	 * FUNCTION: createData
	 * This function adds a new templates to the Database from backoffice
	 * @param mixed $data Array of templates Data
	 */
	public function createData($data){
        $data = $this->validation($data, 'add');
        if(isset($data['error']) && $data['error'] != null){
            return $data;
        }else {
            $dbTable = 'templates';
            $postData = array(
                'title' => $data['title'],
                'description' => $data['description'],
                'created_by' => $data['created_by']
            );

            $this->_db->insert($dbTable, $postData);

            // Gets Last Insert ID
            return $lastInsertID = $this->_db->lastInsertId('id');
        }
	}

    /**
	 * FUNCTION: updateData
	 * This function updates templates details for backoffice
	 * @param mixed $data An array of data passed from the Controller
	 */
	public function updateData($data){
        $data = $this->validation($data, 'edit');
        if(isset($data['error']) && $data['error'] != null){
            return $data;
        }else {
            $dbTable = 'templates';
            $postData = array(
                'title' => $data['title'],
                'description' => $data['description']
            );
            $where = "`id` = {$data['id']}";

            $this->_db->update($dbTable, $postData, $where);
            return true;
        }
	}

    /**
	 * FUNCTION: deleteData
	 * This function deletes an templates
	 * @param Int $id of an templates
	 */
    public function deleteData($id){
        $dbTable = 'templates';
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
				FROM templates t1
				WHERE t1.id = :id";

        return $this->_db->select($sql, array(':id' => $id));
    }


    /**
     * FUNCTION: createTemplateRoom
     * This function creates template_rooms
     * @param int $template_id, $room_id
     */
    public function createTemplateRoom($template_id, $room_id){
        $dbTable = 'template_rooms';
        $postData = array(
            'template_id' => $template_id,
            'room_id' => $room_id
        );

        $this->_db->insert($dbTable, $postData);

        // Gets Last Insert ID
        return $lastInsertID = $this->_db->lastInsertId('id');
    }


    /**
     * FUNCTION: deleteTemplateRoomsByTemplateId
     * This function deletes template_rooms based on template id
     * @param int $template_id
     */
    public function deleteTemplateRoomsByTemplateId($template_id){
        $dbTable = 'template_rooms';
        $where = "`template_id` = $template_id";
        $this->_db->delete($dbTable, $where);
        return true;
    }

    /**
     * FUNCTION: getAllDataByUserId
     * This function returns the details for All templates belonging to a user
     * @param int $user_id
     */
    public function getAllDataByUserId($user_id){

        $sql = "SELECT t1.*
                FROM templates t1
                WHERE t1.created_by = :user_id OR t1.created_by IS NULL
                GROUP BY t1.id
                ORDER BY t1.id DESC
                ";

        return $this->_db->select($sql, array(':user_id' => $user_id));
    }

}?>