<?php
class AdminUsersBackoffice extends Model{
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
            if($key == 'user_name'){
                //Max length
                $temp = Form::MaxLength($input, 12);
                if($temp[0] != true){
                    $return['error'][$key] = 'Username should not exceed 12 characters';
                }

                //Alphabetic
                $temp = Form::ValidateAlphabetic($input);
                if($temp != true){
                    $return['error'][$key] = 'Username should contain only alphabetic characters';
                }

                //Required
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Username cannot be empty';
                }
            }

            //display_name
            if($key == 'display_name'){
                //Max length
                $temp = Form::MaxLength($input, 12);
                if($temp[0] != true){
                    $return['error'][$key] = 'Display Name should not exceed 12 characters';
                }

                //Alphabetic
                $temp = Form::ValidateAlphabetic($input);
                if($temp != true){
                    $return['error'][$key] = 'Display Name should contain only alphabetic characters';
                }

                //Required
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Display Name cannot be empty';
                }
            }

            //user_email
            if($key == 'user_email'){
                //Is Email
                $temp = Form::ValidateEmail($input);
                if($temp[0] != true){
                    $return['error'][$key] = "Email should be in a valid email format";
                }

                //Required
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Email cannot be empty';
                }

                //Unique
                if($type != 'edit') {
                    $temp = $this->selectDataByEmail($input);
                    if (!empty($temp) || $temp != null) {
                        $return['error'][$key] = "This Email address already exists";
                    }
                }else{
                    //if the edited email is different than their previous email, check its unique.
                    if($input != $return['stored_user_email']){
                        $temp = $this->selectDataByEmail($input);
                        if (!empty($temp) || $temp != null) {
                            $return['error'][$key] = "This Email address already exists";
                        }
                    }
                }
            }

            //password
            if($key == 'password'){
                //Required
                if($type != 'edit' && (empty($input) || $input == null)){
                    $return['error'][$key] = 'Password cannot be empty';
                    //if password and confirm password have been inputted
                }elseif(!empty($input) && !empty($data['password_again'])) {
                    //passwords Match
                    if ($input != $data['password_again']) {
                        $return['error'][$key] = 'The passwords don\'t match';
                    }else{
                        $passwordStrength = Password::password_strength($input);
                        //password strength
                        if($passwordStrength[0] == true){
                            $hash = Password::password_hash($input);
                            if($hash[0] == true) {
                                $return['password'] = $hash[1];
                                $return['salt'] = $hash[2];
                            }else{
                               $return['error'][$key] = $hash[1];
                            }
                        }else{
                            $return['error'][$key] = $passwordStrength[1];
                        }
                    }
                }else{
                    //if password is entered but not confirm password
                    if(!empty($input) && empty($data['password_again'])){
                        $return['error']['password_again'] = "Please enter a confirm Password";
                    }
                    //Set pass back to previous password if there is one.
                    if(isset($data['user_pass']) && !empty($data['user_pass'])){
                        $return['password'] = $data['user_pass'];
                    }
                }
            }

            //password_again
            if($key == 'password_again'){
                //Required
                if((!empty($input)) && empty($data['password'])){
                    $return['error']['password'] = 'Password cannot be empty';
                }elseif(!empty($data['password']) && empty($input)){
                    $return['error'][$key] = 'Confirm Password cannot be empty';
                }
            }

            //if salt has been changed
            if($key == 'salt' && isset($hash[2]) && !empty($hash[2])){
                $return['salt'] = $hash[2];
            }

        }
        return $return;
    }

    /**
	 * FUNCTION: selectDataByID
	 * This function gets admin user information for backoffice
	 * @param int $user_id
	 */
	public function selectDataByID($user_id){
		$sql = "SELECT t1.id, t1.user_name, t1.display_name, t1.user_name, t1.user_pass, t1.salt, t1.is_super, t1.user_email
				FROM backend_users t1
				WHERE t1.id = :id";

		return $this->_db->select($sql, array(':id' => $user_id));
	}

    /**
	 * FUNCTION: getAllData
	 * This function returns the details for All Admin Users
	 * @param int $limit, $keywords
	 */
	public function getAllData($limit = false, $keywords = false){
        $optLimit = $limit != false ? " LIMIT $limit" : "";
        $optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t1.id),' ',CONCAT(LOWER(t1.id),' ')),IF(isnull(t1.user_name),' ',CONCAT(LOWER(t1.user_name),'  ')),IF(isnull(t1.display_name),'',CONCAT(LOWER(t1.display_name),' ')),IF(isnull(t1.user_email),' ',CONCAT(LOWER(t1.user_email),' '))) LIKE '%$keywords%'" : "";

        $sql = "SELECT t1.id, t1.user_name, t1.display_name, t1.is_super, t1.created, t1.modified, t1.user_email
				FROM backend_users t1
				WHERE 1 = 1
				".$optKeywords."
				ORDER BY t1.id DESC
				".$optLimit;

		return $this->_db->select($sql);
	}

    /**
	 * FUNCTION: countAllData
	 * This function returns the count for All Admin Users
	 * @param int $keywords
	 */
	public function countAllData($keywords = false){
        $optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t1.id),' ',CONCAT(LOWER(t1.id),' ')),IF(isnull(t1.user_name),' ',CONCAT(LOWER(t1.user_name),'  ')),IF(isnull(t1.display_name),'',CONCAT(LOWER(t1.display_name),' ')),IF(isnull(t1.user_email),' ',CONCAT(LOWER(t1.user_email),' '))) LIKE '%$keywords%'" : "";

		$sql = "SELECT COUNT(t1.id) AS total
				FROM backend_users t1
				WHERE 1 = 1
				".$optKeywords;
		return $this->_db->select($sql);
	}

    /**
	 * FUNCTION: createData
	 * This function adds a new Admin User to the Database from backoffice
	 * @param mixed $data Array of Admin User Data
	 */
	public function createData($data){
        $data = $this->validation($data, 'add');
        if(isset($data['error']) && $data['error'] != null){
            return $data;
        }else {
            $dbTable = 'backend_users';
            $postData = array(
                'user_name' => $data['user_name'],
                'display_name' => $data['display_name'],
                'user_email' => $data['user_email'],
                'user_pass' => $data['password'],
                'salt' => $data['salt'],
                'created' => date('Y-m-d H:i:s', time()),
                'is_super' => $data['is_super']
            );

            $this->_db->insert($dbTable, $postData);

            // Gets Last Insert ID
            return $lastInsertID = $this->_db->lastInsertId('id');
        }
	}

    /**
	 * FUNCTION: updateData
	 * This function updates admin user details for backoffice
	 * @param mixed $data An array of data passed from the Controller
	 */
	public function updateData($data){
        $data = $this->validation($data, 'edit');
        if(isset($data['error']) && $data['error'] != null){
            return $data;
        }else {
            $dbTable = 'backend_users';
            $postData = array(
                'user_name' => $data['user_name'],
                'display_name' => $data['display_name'],
                'user_email' => $data['user_email'],
                'user_pass' => $data['password'],
                'salt' => $data['salt'],
                'is_super' => $data['is_super']
            );
            $where = "`id` = {$data['id']}";

            $this->_db->update($dbTable, $postData, $where);
            return true;
        }
	}

    /**
	 * FUNCTION: deleteData
	 * This function deletes an admin user
	 * @param Int $id of an Admin User
	 */
    public function deleteData($id){
        $dbTable = 'backend_users';
        $where = "`id` = $id";
        $this->_db->delete($dbTable, $where);
		return true;
    }

    /**
	 * FUNCTION: selectDataByEmail
	 * This function gets admin user information for backoffice based on email
	 * @param int $email
	 */
	public function selectDataByEmail($email){
		$sql = "SELECT t1.id
				FROM backend_users t1
				WHERE t1.user_email = :email";

		return $this->_db->select($sql, array(':email' => $email));
	}

    /**
	 * FUNCTION: checkUserLogin
	 * This function checks the user on login
	 * @param string $email User's Email Address
	 */
	public function checkAdminLogin($username){
		$sql = "SELECT t1.id, t1.salt, t1.user_pass
				FROM backend_users t1
				WHERE t1.user_name = :username";
		return $this->_db->select($sql, array(':username' => $username));
	}

    /**
	 * FUNCTION: getDataByID
	 * This function gets the user by their ID
	 * @param int $user_id
	 */
	public function getDataByID($user_id){
        $sql = "SELECT t1.id, t1.user_name, t1.display_name, t1.is_super
				FROM backend_users t1
				WHERE t1.id = :id";

        return $this->_db->select($sql, array(':id' => $user_id));
    }

}?>