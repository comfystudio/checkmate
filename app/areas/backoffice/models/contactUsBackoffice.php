<?php
class ContactUsBackoffice extends Model{
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
            if($key != 'location' && $key != 'text') {
                $input = is_array($input) ? FormInput::trimArray($input) : FormInput::checkInput($input);
            }
            $return[$key] = $input;

            //facebook
            if($key == 'facebook'){
                //required
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Facebook link cannot be empty';
                }
            }

            //instagram
            if($key == 'instagram'){
                //required
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Instagram cannot be empty';
                }
            }

            //twitter
            if($key == 'twitter'){
                //required
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Twitter cannot be empty';
                }
            }

            //location
            if($key == 'location'){
                //Required
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Location cannot be empty';
                }
            }

            //phone
            if($key == 'phone'){
                //Required
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Phone cannot be empty';
                }
            }

            //phone 2
            if($key == 'phone_2'){
                //Required
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Phone Two cannot be empty';
                }
            }


        }
        return $return;
    }

    /**
     * FUNCTION: selectDataByID
     * This function gets about_us information for backoffice
     * @param int $id
     */
    public function selectDataByID($id){
        $sql = "SELECT t1.*
				FROM contact_us t1
				WHERE t1.id = :id
				GROUP BY t1.id";

        return $this->_db->select($sql, array(':id' => $id));
    }

    /**
     * FUNCTION: getAllData
     * This function returns the details for All contacts
     * @param int $limit, $keywords
     */
    public function getAllData($limit = false, $keywords = false){
        $optLimit = $limit != false ? " LIMIT $limit" : "";
        $optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t1.facebook),' ',CONCAT(LOWER(t1.facebook),' ')), IF(isnull(t1.instagram),' ',CONCAT(LOWER(t1.instagram),' ')), IF(isnull(t1.email),' ',CONCAT(LOWER(t1.email),' '))) LIKE '%$keywords%'" : "";

        $sql = "SELECT t1.*
				FROM contact_us t1
				WHERE 1 = 1
				".$optKeywords."
				GROUP BY t1.id
				ORDER BY t1.id DESC
				".$optLimit;

        return $this->_db->select($sql);
    }

    /**
     * FUNCTION: countAllData
     * This function returns the count for All contacts
     * @param int $keywords
     */
    public function countAllData($keywords = false){
        $optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t1.facebook),' ',CONCAT(LOWER(t1.facebook),' ')), IF(isnull(t1.instagram),' ',CONCAT(LOWER(t1.instagram),' ')), IF(isnull(t1.email),' ',CONCAT(LOWER(t1.email),' '))) LIKE '%$keywords%'" : "";

        $sql = "SELECT COUNT(t1.id) AS total
				FROM contact_us t1
				WHERE 1 = 1
				".$optKeywords;
        return $this->_db->select($sql);
    }

    /**
     * FUNCTION: createData
     * This function adds a new contacts to the Database from backoffice
     * @param mixed $data Array of contacts Data
     */
    public function createData($data){
        $data = $this->validation($data, 'add');
        if(isset($data['error']) && $data['error'] != null){
            return $data;
        }else {
            $dbTable = 'contact_us';
            $postData = array(
                'facebook' => $data['facebook'],
                'instagram' => $data['instagram'],
                'phone' => $data['phone'],
                'phone_2' => $data['phone_2'],
                'location' => $data['location'],
                'text' => $data['text'],
                'twitter' => $data['twitter'],
                'image' => $data['image'][0],
            );

            $this->_db->insert($dbTable, $postData);

            // Gets Last Insert ID
            return $lastInsertID = $this->_db->lastInsertId('id');
        }
    }

    /**
     * FUNCTION: updateData
     * This function updates contacts details for backoffice
     * @param mixed $data An array of data passed from the Controller
     */
    public function updateData($data){
        $data = $this->validation($data, 'edit');
        if(isset($data['error']) && $data['error'] != null){
            return $data;
        }else {
            $dbTable = 'contact_us';
            $postData = array(
                'facebook' => $data['facebook'],
                'instagram' => $data['instagram'],
                'phone' => $data['phone'],
                'phone_2' => $data['phone_2'],
                'location' => $data['location'],
                'text' => $data['text'],
                'twitter' => $data['twitter'],
                'image' => $data['image'][0],
            );
            $where = "`id` = {$data['id']}";

            $this->_db->update($dbTable, $postData, $where);
            return true;
        }
    }

    /**
     * FUNCTION: deleteData
     * This function deletes an contacts
     * @param Int $id of an contacts
     */
    public function deleteData($id)
    {
        $dbTable = 'contact_us';
        $where = "`id` = $id";
        $this->_db->delete($dbTable, $where);
        return true;
    }

}?>