<?php
class UserBackoffice extends Model{
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
            if($key == 'firstname'){
                //Max length
                $temp = Form::MaxLength($input, 20);
                if($temp[0] != true){
                    $return['error'][$key] = 'Firstname should not exceed 20 characters';
                }

                //Alphabetic
                $temp = Form::ValidateAlphabetic($input);
                if($temp != true){
                    $return['error'][$key] = 'Firstname should contain only alphabetic characters';
                }

                //Required
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Firstname cannot be empty';
                }
            }

            //display_name
            if($key == 'surname'){
                //Max length
                $temp = Form::MaxLength($input, 20);
                if($temp[0] != true){
                    $return['error'][$key] = 'Surname should not exceed 20 characters';
                }

                //Alphabetic
                $temp = Form::ValidateAlphabetic($input);
                if($temp != true){
                    $return['error'][$key] = 'Surname should contain only alphabetic characters';
                }

                //Required
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Surname cannot be empty';
                }
            }

            //user_email
            if($key == 'email'){
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

            //contact_num
            if($key == 'contact_num'){
                //Required
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Contact Number cannot be empty';
                }
            }

            //type
            if($key == 'type'){
                //required
                if($input < 0 || $input > 2){
                    $return['error'][$key] = 'User Type cannot be empty';
                }
            }

            //password
            if($key == 'password'){
                //Required
                if($type != 'edit' && (empty($input) || $input == null)){
                    $return['error'][$key] = 'Password cannot be empty';
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
	 * FUNCTION: getDataByID
	 * This function gets the users by their ID
	 * @param int $user_id
	 * @param string $is_active - default null, active or inactive
	 */
	public function getDataByID($user_id, $is_active = null){
        $optActive = "";
        if(isset($is_active) && $is_active != null){if($is_active == 'active'){$optActive = "AND t1.is_active = 1";}else{$optActive = "AND t1.is_active = 0";}}else{$optActive = "";}
		$sql = "SELECT t1.id, t1.firstname, t1.surname, t1.email, t1.is_active, t1.email_verified, t1.type, t1.contact_num, t1.logo_image
				FROM users  t1
				WHERE t1.id = :id ".$optActive."
				";
									
		return $this->_db->select($sql, array(':id' => $user_id));
	}
	
	/**
	 * FUNCTION: getDataByEmail
	 * This function gets the users by their email address
	 * @param int $email
	 * @param string $is_active - default null, active or inactive
	 */
	public function getDataByEmail($email, $is_active = null){
        $optActive = "";
        if(isset($is_active) && $is_active != null){if($is_active == 'active'){$optActive = "AND t1.is_active = 1";}else{$optActive = "AND t1.is_active = 0";}}else{$optActive = "";}
		$sql = "SELECT t1.id, t1.type_id, t1.firstname, t1.surname, t1.company, t1.gender, DATE_FORMAT(t1.dob, '%d/%m/%y') AS dob, t1.email, t1.email_verified, t1.phone, t1.mobile, t1.default_currency, t1.where_you_live, t1.town, t1.describe_yourself, t1.image, t1.country, t1.region_id, t1.is_active, t1.type, t1.contact_num, t1.logo_image
				FROM users t1
				WHERE t1.email = :email ".$is_active."
				";
									
		return $this->_db->select($sql, array(':email' => $email, ':is_active' => $is_active));	
	}
	
	/**
	 * FUNCTION: selectDataByID
	 * This function gets user information for backoffice
	 * @param int $user_id
	 * @param string $is_active - default null, active or inactive
	 */
	public function selectDataByID($user_id, $is_active = null){
        $optActive = "";
        if(isset($is_active) && $is_active != null){if($is_active == 'active'){$optActive = "AND t1.is_active = 1";}else{$optActive = "AND t1.is_active = 0";}}else{$optActive = "";}
		$sql = "SELECT t1.id, t1.firstname, t1.surname, t1.email,  t1.password, t1.salt, t1.is_active, t1.type, t1.contact_num, t1.logo_image
				FROM users t1
				WHERE t1.id = :id ".$optActive."
				";
									
		return $this->_db->select($sql, array(':id' => $user_id));
	}
	
	/**
	 * FUNCTION: getAllData
	 * This function returns the details for All Users
	 * @param string $is_active - default null, active or inactive
	 */
	public function getAllData($limit = false, $keywords = false, $is_active = null){
        $optActive = "";
        if(isset($is_active) && $is_active != null){if($is_active == 'active'){$optActive = "AND t1.is_active = 1";}else{$optActive = "AND t1.is_active = 0";}}else{$optActive = "";}
		$optLimit = $limit != false ? " LIMIT $limit" : "";
        $optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t1.id),' ',CONCAT(LOWER(t1.id),' ')),IF(isnull(t1.firstname),' ',CONCAT(LOWER(t1.firstname),'  ')),IF(isnull(t1.surname),'',CONCAT(LOWER(t1.surname),' ')),IF(isnull(t1.email),' ',CONCAT(LOWER(t1.email),' '))) LIKE '%$keywords%'" : "";
		$sql = "SELECT t1.id, t1.firstname, t1.surname, t1.email, t1.email_verified, t1.is_active, t1.type, t1.contact_num, t1.logo_image
				FROM users t1
				WHERE 1 = 1
				".$optKeywords."
				".$optActive."
				ORDER BY t1.id DESC
				".$optLimit;
		return $this->_db->select($sql);
	}

	/**
	 * FUNCTION: countAllData
	 * This function returns the count for All Users
	 * @param string $is_active - default null, active or inactive
	 */
	public function countAllData($keywords = false, $is_active = null){
        $optActive = "";
        if(isset($is_active) && $is_active != null){if($is_active == 'active'){$optActive = "AND t1.is_active = 1";}else{$optActive = "AND t1.is_active = 0";}}else{$optActive = "";}
        $optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t1.id),' ',CONCAT(LOWER(t1.id),' ')),IF(isnull(t1.firstname),' ',CONCAT(LOWER(t1.firstname),'  ')),IF(isnull(t1.surname),'',CONCAT(LOWER(t1.surname),' ')),IF(isnull(t1.email),' ',CONCAT(LOWER(t1.email),' '))) LIKE '%$keywords%'" : "";
		$sql = "SELECT COUNT(t1.id) AS total
				FROM users t1
				WHERE 1=1 ".$optActive."
				".$optKeywords;

		return $this->_db->select($sql);
	}
	
	/**
	 * FUNCTION: checkUserLogin
	 * This function checks the user on login
	 * @param string $email User's Email Address
	 */
	public function checkUserLogin($email){
		$sql = "SELECT t1.id, t1.salt, t1.password
				FROM users t1
				WHERE t1.email = :email";	
		return $this->_db->select($sql, array(':email' => $email));	
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
            $dbTable = 'users';
            $postData = array(
                'firstname' => $data['firstname'],
                'surname' => $data['surname'],
                'email' => $data['email'],
                'email_verified' => 1,
                'password' => $data['password'],
                'salt' => $data['salt'],
                'type' => $data['type'],
                'contact_num' => $data['contact_num'],
                'logo_image' => $data['image'][0]
            );


            $this->_db->insert($dbTable, $postData);

            // Gets Last Insert ID
            return $lastInsertID = $this->_db->lastInsertId('id');
        }
	}
	
	/**
	 * FUNCTION: updateUser
	 * This function updates user details for backoffice
	 * @param mixed $data An array of data passed from the Controller
	 */
	public function updateData($data){
        $data = $this->validation($data, 'edit');
        if(isset($data['error']) && $data['error'] != null) {
            return $data;
        }else{
            $dbTable = 'users';
            $postData = array(
                'firstname' => $data['firstname'],
                'surname' => $data['surname'],
                'email' => $data['email'],
                'email_verified' => $data['email_verified'],
                'password' => $data['password'],
                'salt' => $data['salt'],
                'is_active' => $data['is_active'],
                'type' => $data['type'],
                'contact_num' => $data['contact_num'],
                'logo_image' => $data['image'][0]
            );
            $where = "`id` = {$data['id']}";

            $this->_db->update($dbTable, $postData, $where);
            return true;
        }
	}

    /**
	 * FUNCTION: deleteData
	 * This function deletes a frontend user
	 * @param Int $id of an Admin User
	 */
    public function deleteData($id){
        $dbTable = 'users';
        $where = "`id` = $id";
        $this->_db->delete($dbTable, $where);
		return true;
    }

    /**
	 * FUNCTION: selectDataByEmail
	 * This function gets user information for backoffice based on email
	 * @param int $email
	 */
	public function selectDataByEmail($email, $is_active = null){
        $optActive = "";
        if(isset($is_active) && $is_active != null){if($is_active == 'active'){$optActive = "AND t1.is_active = 1";}else{$optActive = "AND t1.is_active = 0";}}else{$optActive = "";}
		$sql = "SELECT t1.id
				FROM users t1
				WHERE t1.email = :email ".$optActive."
				";
		return $this->_db->select($sql, array(':email' => $email));
	}

	
}?>