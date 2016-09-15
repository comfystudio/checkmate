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
                WHERE t1.user_id = :user_id AND t1.read = 0
                GROUP BY t1.id
                ORDER BY t1.created DESC
                ";

        return $this->_db->select($sql, array(':user_id' => $user_id));
    }

}?>