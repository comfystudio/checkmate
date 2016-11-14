<?php
class PropertiesBackoffice extends Model{
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

            //title
            if($key == 'title'){
            	//Max length
                $temp = Form::MaxLength($input, 100);
                if($temp[0] != true){
                    $return['error'][$key] = 'Title should not exceed 100 characters';
                }
                //required
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Title cannot be empty';
                }else{

                }
            }

            //house_number
            if($key == 'house_number'){
            	//Max length
                $temp = Form::MaxLength($input, 10);
                if($temp[0] != true){
                    $return['error'][$key] = 'House Number should not exceed 10 characters';
                }
                //required
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'House Number cannot be empty';
                }else{

                }
            }

            //Address 1
            if($key == 'address_1'){
            	//Max length
                $temp = Form::MaxLength($input, 100);
                if($temp[0] != true){
                    $return['error'][$key] = 'Address Line 1 should not exceed 100 characters';
                }
                //required
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Address Line 1 cannot be empty';
                }else{

                }
            }

            //postcode
            if($key == 'postcode'){
            	//Max length
                $temp = Form::MaxLength($input, 20);
                if($temp[0] != true){
                    $return['error'][$key] = 'Postcode hould not exceed 20 characters';
                }
                //required
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Postcode cannot be empty';
                }else{

                }
            }
        }
        return $return;
    }

    /**
	 * FUNCTION: selectDataByID
	 * This function gets properties information for backoffice
	 * @param int $id
	 */
	public function selectDataByID($id){
        $sql = "SELECT t1.*, t2.firstname, t2.surname, t2.email, t3.template_id, t4.title as template_title, t4.description, GROUP_CONCAT(t6.id separator ', ') as room_ids
				FROM properties t1
					LEFT JOIN users t2 ON t1.created_by = t2.id
					LEFT JOIN property_templates t3 ON t1.id = t3.property_id
						LEFT JOIN templates t4 ON t3.template_id = t4.id
							LEFT JOIN template_rooms t5 ON t4.id = t5.template_id
								LEFT JOIN rooms t6 ON t5.room_id = t6.id
                WHERE t1.id = :id
				GROUP BY t1.id";

		return $this->_db->select($sql, array(':id' => $id));
	}

    /**
	 * FUNCTION: getAllData
	 * This function returns the details for All properties
	 * @param int $limit, $keywords
	 */
	public function getAllData($limit = false, $keywords = false, $active = false){
        $optLimit = $limit != false ? " LIMIT $limit" : "";
        $optActive = $active != false ? " AND t1.is_active = 1" : "";
        $optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t1.address_1),' ',CONCAT(LOWER(t1.address_1),' ')),IF(isnull(t1.postcode),' ',CONCAT(LOWER(t1.postcode),' '))) LIKE '%$keywords%'" : "";

        $sql = "SELECT t1.*
				FROM properties t1
				WHERE 1 = 1
				".$optKeywords."
				".$optActive."
				ORDER BY t1.id DESC
				".$optLimit;

		return $this->_db->select($sql);
	}

    /**
	 * FUNCTION: countAllData
	 * This function returns the count for All properties
	 * @param int $keywords
	 */
	public function countAllData($keywords = false, $active = false){
        $optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t1.address_1),' ',CONCAT(LOWER(t1.address_1),' ')),IF(isnull(t1.postcode),' ',CONCAT(LOWER(t1.postcode),' '))) LIKE '%$keywords%'" : "";
        $optActive = $active != false ? " AND t1.is_active = 1" : "";

		$sql = "SELECT COUNT(t1.id) AS total
				FROM properties t1
				WHERE 1 = 1
				".$optActive."
				".$optKeywords;
		return $this->_db->select($sql);
	}

    /**
	 * FUNCTION: deleteData
	 * This function deletes an properties
	 * @param Int $id of an properties
	 */
    public function deleteData($id){
        $dbTable = 'properties';
        $where = "`id` = $id";
        $this->_db->delete($dbTable, $where);
		return true;
    }

    /**
	 * FUNCTION: updateData
	 * This function updates properties details for backoffice
	 * @param mixed $data An array of data passed from the Controller
	 */
	public function updateData($data){
        $data = $this->validation($data, 'edit');
        if(isset($data['error']) && $data['error'] != null){
            return $data;
        }else {
            $dbTable = 'properties';
            $postData = array(
//                'title' => $data['title'],
                'image' => $data['image'][0],
                'house_number' => $data['house_number'],
                'address_1' => $data['address_1'],
                'address_2' => $data['address_2'],
                'address_3' => $data['address_3'],
//                'address_4' => $data['address_4'],
                'postcode' => $data['postcode']
            );
            $where = "`id` = {$data['id']}";

            $this->_db->update($dbTable, $postData, $where);
            return true;
        }
	}


    /**
     * FUNCTION: deletePropertyTemplatesByPropertyId
     * This function deletes property_templates by property_id
     * @param int $property_id 
     */
    public function deletePropertyTemplatesByPropertyId($property_id){
        $dbTable = 'property_templates';
        $where = "`property_id` = $property_id";
        $this->_db->delete($dbTable, $where);
        return true;
    }


    /**
     * FUNCTION: createPropertyTemplates
     * This function creates property_templates by property_id and template_id
     * @param int $property_id, $template_id
     */
    public function createPropertyTemplates($property_id, $template_id){
        $dbTable = 'property_templates';
        $postData = array(
            'property_id' => $property_id,
            'template_id' => $template_id,
        );

        $this->_db->insert($dbTable, $postData);

        // Gets Last Insert ID
        return $lastInsertID = $this->_db->lastInsertId('id');
    }

}?>