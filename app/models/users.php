<?php
class Users extends Model{
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
	 * FUNCTION: selectDataById
	 * This function gets the users by their ID
	 * @param int $user_id
	 * @param string $is_active - default null, active or inactive
	 */
	public function selectDataByID($user_id, $is_active = 'active'){
        $optActive = "";
        if(isset($is_active) && $is_active != null){if($is_active == 'active'){$optActive = "AND t1.is_active = 1";}else{$optActive = "AND t1.is_active = 0";}}else{$optActive = "";}
		$sql = "SELECT t1.*, t2.id as payment_id, t2.type as payment_type, t2.last_payment, t2.remaining_credits, t2.active_until, t2.stripe_cus_id, t2.stripe_sub_id
				FROM users t1
					LEFT JOIN payments t2 ON t1.id = t2.user_id
				WHERE t1.id = :id ".$optActive."
				GROUP BY t1.id
				";
									
		return $this->_db->select($sql, array(':id' => $user_id));
	}
	
	/**
	 * FUNCTION: getUserByEmail
	 * This function gets the users by their email address
	 * @param int $email
	 * @param string $is_active - default null, active or inactive
	 */
	public function getUserByEmail($email, $is_active = 'active'){
        $optActive = "";
        if(isset($is_active) && $is_active != null){if($is_active == 'active'){$optActive = "AND t1.is_active = 1";}else{$optActive = "AND t1.is_active = 0";}}else{$optActive = "";}
		$sql = "SELECT t1.*
				FROM users t1
				WHERE t1.email = :email ".$optActive."
				";
									
		return $this->_db->select($sql, array(':email' => $email));
	}


	/**
	 * FUNCTION: createUserReport
	 * This function creates a user_reports
	 * @param int $user_id, int $report_id
	 */
	public function createUserReport($user_id, $report_id){
		$dbTable = 'user_reports';
        $postData = array(
            'user_id' => $user_id,
            'report_id' => $report_id
        );

        $this->_db->insert($dbTable, $postData);
        return $lastInsertID = $this->_db->lastInsertId('id');
	}
	
//	/**
//	 * FUNCTION: selectUserByID
//	 * This function gets user information for backoffice
//	 * @param int $user_id
//	 * @param int $is_active - default yes
//	 */
//	public function selectUserByID($user_id){
//		$sql = "SELECT t1.id, t1.firstname, t1.surname, t1.company, t1.gender, DATE_FORMAT(t1.dob, '%Y-%m-%d') AS dob, t1.email, t1.email_verified, t1.password, t1.salt, t1.phone, t1.mobile, t1.default_currency, t1.where_you_live, t1.town, t1.describe_yourself, t1.image, t1.country_id, t1.region_id, t1.is_active
//				FROM users t1
//				WHERE t1.id = :id";
//
//		return $this->_db->select($sql, array(':id' => $user_id));
//	}
	
	/**
	 * FUNCTION: getAllData
	 * This function returns the details for All Users
     * @param int $limit, string $keywords
	 * @param string $is_active - default null, active or inactive
	 */
	public function getAllData($limit = false, $keywords = false, $is_active = 'active'){
		$optLimit = $limit != false ? " LIMIT $limit" : "";
		$optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t1.id),' ',CONCAT(LOWER(t1.id),' ')),IF(isnull(t1.firstname),' ',CONCAT(LOWER(t1.firstname),'  ')),IF(isnull(t1.surname),'',CONCAT(LOWER(t1.surname),' ')),IF(isnull(t1.email),' ',CONCAT(LOWER(t1.email),' '))) LIKE '%$keywords%'" : "";
        $optActive = "";
        if(isset($is_active) && $is_active != null){if($is_active == 'active'){$optActive = "AND t1.is_active = 1";}else{$optActive = "AND t1.is_active = 0";}}else{$optActive = "";}

		$sql = "SELECT t1.id, t1.firstname, t1.surname, t1.email, t1.email_verified, t1.is_active
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
     * @param string $keywords
	 * @param string $is_active - default null, active or inactive
	 */
	public function countAllData($keywords = false, $is_active = 'active'){
		$optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t1.id),' ',CONCAT(LOWER(t1.id),' ')),IF(isnull(t1.firstname),' ',CONCAT(LOWER(t1.firstname),'  ')),IF(isnull(t1.surname),'',CONCAT(LOWER(t1.surname),' ')),IF(isnull(t1.email),' ',CONCAT(LOWER(t1.email),' '))) LIKE '%$keywords%'" : "";
        $optActive = "";
        if(isset($is_active) && $is_active != null){if($is_active == 'active'){$optActive = "AND t1.is_active = 1";}else{$optActive = "AND t1.is_active = 0";}}else{$optActive = "";}

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
     * @param string $is_active - default null, active or inactive
	 */
	public function checkUserLogin($email, $is_active = 'active'){
        $optActive = "";
        if(isset($is_active) && $is_active != null){if($is_active == 'active'){$optActive = "AND t1.is_active = 1";}else{$optActive = "AND t1.is_active = 0";}}else{$optActive = "";}
		$sql = "SELECT t1.id, t1.firstname, t1.surname, t1.email, t1.email_verified, t1.is_active, t1.salt, t1.password
				FROM users t1
				WHERE t1.email = :email ".$optActive."
				";
		return $this->_db->select($sql, array(':email' => $email));	
	}
	
	/**
	 * FUNCTION: checkUserIsVerified
	 * This function checks the user is verified
	 * @param string $id
	 */
	public function checkUserIsVerified($id){
		$sql = "SELECT t1.email_verified
				FROM users t1
				WHERE t1.id = :id";
				
		return $this->_db->select($sql, array(':id' => $id));	
	}
	
	/**
	 * FUNCTION: verifyEmailAddress
	 * This function updates the email verified field for the user
	 * @param mixed $data An array of data passed from the Controller
	 */
	public function verifyEmailAddress($data){
		$dbTable = 'users';
        $postData = array(
			'email_verified' => $data['email_verified']
        );
		$where = "`id` = {$data['id']}";
        
        $this->_db->update($dbTable, $postData, $where);
        return true;
	}
	
	/**
	 * FUNCTION: insertPasswordRecovery
	 * This function inserts the password reset key for the User
	 * This is requested from the forgot password page in the Login Controller
	 * @param mixed $data An array of data passed from the Controller
	 */
	public function insertPasswordRecovery($data){
		$dbTable = 'users_pw_recovery';
        $postData = array(
            'users_id' => $data['user_id'],
			'security_key' => $data['security_key'],
			'exp_date' => $data['exp_date']
        );      
        $this->_db->insert($dbTable, $postData);
		
		return $this->_db->lastInsertID();
	}
	
	/**
	 * FUNCTION: updatePasswordRecovery
	 * This function updates the password reset key for the User
	 * This is requested from the forgot password page in the Login Controller
	 * @param mixed $data An array of data passed from the Controller
	 */
	public function updatePasswordRecovery($data){
		$dbTable = 'users_pw_recovery';
        $postData = array(
            'security_key' => $data['security_key'],
			'exp_date' => $data['exp_date']
        );
		$where = "`id` = {$data['id']}";
        
        $this->_db->update($dbTable, $postData, $where);
	}
	
	/**
	 * FUNCTION: deletePasswordRecovery
	 * This function delete password receovery for a user
	 * @param int $users_id
	 */
	public function deletePasswordRecovery($users_id){

		$dbTable = 'users_pw_recovery';
        $where = "`users_id` = $users_id";
        $this->_db->delete($dbTable, $where);
		return true;
	}
	
	/**
	 * FUNCTION: validatePasswordRecovery
	 * @param int $user_id
	 * @param string $security_key
	 * This function returns the Password Recovery Key for the requested user
	 * This key is then verified in the Controller
	 */
	public function validatePasswordRecovery($user_id, $security_key){
		$sql = "SELECT t1.id
				FROM users_pw_recovery t1
				WHERE t1.users_id = :user_id
				AND t1.security_key = :security_key
				AND CURDATE() < t1.exp_date";
				
		return $this->_db->select($sql, array(':user_id' => $user_id, ':security_key' => $security_key));	
	}
	
	/**
	 * FUNCTION: createUserByEmail
	 * This function adds a new User to the Database from frontend signup
	 * @param mixed $data Array of User Data
	 */
	// public function createUserByEmail($data){	
	// 	$dbTable = 'users';
 //        $postData = array(
	//         'type_id' => $data['type_id'],
 //            'firstname' => $data['firstname'],
	// 		'surname' => $data['surname'],
	// 		'email' => $data['email'],
	// 		'dob' => $data['dob'],
	// 		'password' => $data['password'],
	// 		'salt' => $data['salt']
 //        );
        
 //        $this->_db->insert($dbTable, $postData);
		
	// 	// Gets Last Insert ID
	// 	return $lastInsertID = $this->_db->lastInsertId('id');
	// }

	/**
	 * FUNCTION: createDataSystem
	 * This function adds a new User to the Database from backoffice
	 * @param mixed $data Array of User Data
	 */
	public function createDataSystem($data){
        $dbTable = 'users';
        $postData = array(
        	'type' => $data['type'],
            'firstname' => $data['firstname'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'password' => $data['password'],
            'salt' => $data['salt'],
            'contact_num' => $data['contact_num']
        );

        $this->_db->insert($dbTable, $postData);
        // Gets Last Insert ID
        return $lastInsertID = $this->_db->lastInsertId('id');
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
        }else{
            $dbTable = 'users';
            $postData = array(
                'firstname' => $data['firstname'],
                'surname' => $data['surname'],
                'email' => $data['email'],
                'password' => $data['password'],
                'salt' => $data['salt']
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
	public function updateUser($data){
        $data = $this->validation($data, 'edit');
        if(isset($data['error']) && $data['error'] != null) {
            return $data;
        }else {
            $dbTable = 'users';
            $postData = array(
                'firstname' => $data['firstname'],
                'surname' => $data['surname'],
                'email' => $data['email'],
                'password' => $data['password'],
                'salt' => $data['salt'],
                'is_active' => $data['is_active'],

            );
            $where = "`id` = {$data['id']}";
            $this->_db->update($dbTable, $postData, $where);
            return true;
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
	 * FUNCTION: updateUserPassword
	 * This function updates user password
	 * @param mixed $data An array of data passed from the Controller
	 */
	public function updateUserPassword($data){
		$dbTable = 'users';
        $postData = array(
	        'password' => $data['password'],
			'salt' => $data['salt']
        );
		$where = "`id` = {$data['id']}";

        $this->_db->update($dbTable, $postData, $where);
        return true;
	}

    /**
	 * FUNCTION: selectDataByEmail
	 * This function gets user information based on email
	 * @param string $email
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

	/**
	 * FUNCTION: checkEmail
	 * This function checks if an email exists
	 * @param string $email
	 */
	public function checkEmail($email){
		$sql = "SELECT t1.id
				FROM users t1
				WHERE t1.email = :email";		
		return $this->_db->select($sql, array(':email' => $email));	
	}

	/**
	 * FUNCTION: createUserByEmail
	 * This function adds a new User to the Database from frontend signup
	 * @param mixed $data Array of User Data
	 */
	public function createUserByEmail($data){	
		$dbTable = 'users';
        $postData = array(
	        'type' => $data['type'],
            'firstname' => $data['firstname'],
			'surname' => $data['surname'],
			'email' => $data['email'],
			'password' => $data['password'],
			'salt' => $data['salt'],
			'contact_num' => $data['contact_num'],
			'logo_image' => $data['logo_image'][0],
			'email_verified' => 1
        );
        
        $this->_db->insert($dbTable, $postData);
		
		// Gets Last Insert ID
		return $lastInsertID = $this->_db->lastInsertId('id');
	}

	/**
	 * FUNCTION: updateUserReportCheckOut
	 * This function updates user_reports with new checkout info
	 * @param mixed $data An array of data passed from the Controller
	 */
	public function updateUserReportCheckOut($data){
		$dbTable = 'user_reports';
        $postData = array(
	        'check_out_signature' => $data['check_out_signature'],
			'check_out_time' => $data['check_out_time']
        );
		$where = "`user_id` = {$data['user_id']} AND `report_id` = {$data['report_id']}";

        $this->_db->update($dbTable, $postData, $where);
        return true;
	}

	/**
	 * FUNCTION: updateUserReportCheckIn
	 * This function updates user_reports with new checkout info
	 * @param mixed $data An array of data passed from the Controller
	 */
	public function updateUserReportCheckIn($data){
		$dbTable = 'user_reports';
        $postData = array(
	        'check_in_signature' => $data['check_in_signature'],
			'check_in_time' => $data['check_in_time']
        );
		$where = "`user_id` = {$data['user_id']} AND `report_id` = {$data['report_id']}";

        $this->_db->update($dbTable, $postData, $where);
        return true;
	}

	/**
	 * FUNCTION: getAllByArray
	 * This function returns the details for All Users in an array
     * @param array $users, int $report_id
	 */
	public function getAllByArray($users, $report_id){
		$sql = "SELECT t1.*, t2.*
				FROM users t1
					LEFT JOIN user_reports t2 ON t1.id = t2.user_id AND t2.report_id = $report_id
				WHERE t1.id IN (".$users.")
				GROUP BY t1.id";
				
		return $this->_db->select($sql);	
	}

	/**
	 * FUNCTION: getUserReport
	 * This function returns a user report of a specific user based on user_id and report_id
     * @param int $report_id int $user_id
	 */
	public function getUserReport($report_id, $user_id){
		$sql = "SELECT t1.*, t2.firstname, t2.surname, t2.email
				FROM user_reports t1
					LEFT JOIN users t2 ON t1.user_id = t2.id
				WHERE t1.report_id = :report_id AND t1.user_id = :user_id
				GROUP BY t1.user_id
				";
		return $this->_db->select($sql, array(':report_id' => $report_id, ':user_id' => $user_id));
	}
}?>