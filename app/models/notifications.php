<?php
class Notifications extends Model{
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
        }
        return $return;
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
     * This function returns the details for notifiocations based on user_id
     * @param int $limit, $keywords
     */
    public function getAlldataByUserId($user_id){
        $sql = "SELECT t1.*
                FROM notifications t1
                WHERE t1.user_id = :user_id
                GROUP BY t1.id
                ORDER BY t1.created DESC
                ";

        return $this->_db->select($sql, array(':user_id' => $user_id));
    }

    /**
     * FUNCTION: selectDataByID
     * This function gets notifications information
     * @param int $id
     */
    public function selectDataByID($id){
        $sql = "SELECT t1.*
                FROM notifications t1
                WHERE t1.id = :id
                GROUP BY t1.id";

        return $this->_db->select($sql, array(':id' => $id));
    }

    /**
     * FUNCTION: setReadById
     * This function sets the read status of a notification to 1 based on $id
     * @param int $id
     */
    public function setReadById($id){
        $dbTable = 'notifications';
            $postData = array(
                'read' => 1
            );
            $where = "`id` = {$id}";

            $this->_db->update($dbTable, $postData, $where);
    }


    /**
     * FUNCTION: deleteOld
     * This function deletes notifications that are more than a year old.
     */
    public function deleteOld(){
        $dbTable = 'notifications';
        $where = " DATE_ADD(notifications.created, INTERVAL 1 YEAR) < NOW()";
        $this->_db->delete($dbTable, $where);
        return true;
    }

    /**
     * FUNCTION: deleteData
     * This function deletes a notification
     * @param Int $id of an prices
     */
    public function deleteData($id){
        $dbTable = 'notifications';
        $where = "`id` = $id";
        $this->_db->delete($dbTable, $where);
        return true;
    }

}?>