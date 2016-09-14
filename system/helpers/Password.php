<?php
header('Content-Type: text/html; charset=utf-8');
class Password {

    /**
     * Hash the password using the specified algorithm
     *
     * @param string $password The password to hash
     *
     * @return string The hashed password
     */
    static public function password_hash($password) {
		$result[0] = false;
        if (!is_string($password)) {
			$result[1] = "Password must be a string";
        }else{
			$salt = time();
			$result[0] = true;
			$result[1] = hash('sha256', $password.$salt);
			$result[2] = $salt;
		}
        return $result;
    }

	/**
     * Check the strength of the password
	 * Must be at least 8 characters
	 * Must contain at least one letter
	 * Must contain at least one number
	 * Must contain at least one lowercase letter
	 * Must contain at least one uppercase letter
     *
     * @param string $password The password to check
     *
     * @return true|string The error message on false or true if pass all requirements.
     */
	static public function password_strength($password){
		$result[0] = false;
        if(strlen($password) < 8){
            $result[1] = "Password must be at least 8 characters";
        }elseif(is_numeric($password)){
            $result[1] = "Password must contain at least one letter";
		}elseif(!preg_match("/([0-9])/ ", $password)){
			$result[1] = "Password must contain at least one number";
		}elseif(!preg_match("/([a-z])/", $password)){
			$result[1] = "Password must contain at least one lowercase letter";
		}elseif(!preg_match("/([A-Z])/", $password)){
			$result[1] = "Password must contain at least one uppercase letter";
		}else{
			$result[0] = true;
		}
		return $result;
    }

    /**
     * Verify a password
     *
     * @param string $storedPassword	The password to verify
	 * @param int $storedSalt 			The salt to verify
     * @param string $storedSalt     	The hash to verify against
     *
     * @return boolean If the password matches the hash
     */
    static public function password_verify($storedPassword, $storedSalt, $password) {
		$result[0] = false;
        if(hash('sha256', $password.$storedSalt) != $storedPassword) {
			$result[1] = "Invalid password. Why not try our forgot password option?";
		}else{
			$result[0] = true;
		}
		return $result;
    }

    static public function strong_random_password($length = 10, $available_sets = 'luds'){
		$sets = array();
		if(strpos($available_sets, 'l') !== false)
			$sets[] = 'abcdefghjkmnpqrstuvwxyz';
		if(strpos($available_sets, 'u') !== false)
			$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
		if(strpos($available_sets, 'd') !== false)
			$sets[] = '123456789';
		if(strpos($available_sets, 's') !== false)
			$sets[] = '!$%^&*()@#?=+~';
		$all = '';
		$password = '';
		foreach($sets as $set){
			$password .= $set[array_rand(str_split($set))];
			$all .= $set;
		}
		$all = str_split($all);
		for($i = 0; $i < $length - count($sets); $i++)
			$password .= $all[array_rand($all)];
		$password = str_shuffle($password);
		return $password;
	}
}