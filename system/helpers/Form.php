<?php
/** Form */
class Form {
	
	/** __construct */
	public function __construct(){
	}
	
	/**
	 * ACTION: Post
	 * This method is to run $_POST
	 * This gets the field values from the form and adds them to an array
	 *
	 * @param array $postData
	 * @param array $expected
	 * @param array $required
	 * @return array The missing fields or valid post data
	 */
	static public function ValidatePost($post, $expected, $required, $missing = array(), $postData = array()){
		
		$result[0] = false;
		foreach ($post as $key => $value) {
		 	$temp = is_array($value) ? FormInput::trimArray($value) : FormInput::checkInput($value);
		 	if (empty($temp) && in_array($key, $required)) {
				$missing[] = $key;
			}
		  	elseif (in_array($key, $expected)) {
				$postData[$key] = $temp;
			}
		}
		if(!empty($missing)) {
			$result[1] = $missing;
		}else{
			$result[0] = true;
			$result[1] = $postData;
		}
		
		return $result;
	}
	
	/**
	 * ACTION: Validate Email
	 *
	 * @param string $email
	 * @return true|string The error message on false or true if pass all requirements
	 */
	static public function ValidateEmail($email){
		$result[0] = false;
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$result[1] = $email." is not a valid email address";
		}else{
			$result[0] = true;
		}
		
		return $result;
	}
	
	/**
	 * ACTION: Max Length
	 *
	 * @param string $data
	 * @param int $arg
	 * @return true|string The number of characters to remove
	 */
	static public function MaxLength($data, $arg){
		$result[0] = false;
		if(strlen($data) > $arg){
			$remove_qty = strlen($data) - $arg;
			$result[1] = $remove_qty;
		}else{
			$result[0] = true;
		}
		
		return $result;
	}
	
	/**
	 * ACTION: Validate Message
	 *
	 * @param string $message
	 * @return true|string The error message on false or true if pass all requirements
	 */
	static public function ValidateMessage($message){
		
		$warning = "LuxuryLet.com does not allow you to send email addresses, card details or phone numbers in your messages. Please remove this from your message and try again.";
		
		$result[0] = false;
		
		// check if string contains consecutive numbers
		if (preg_match("/\d{5}/u", $message)) {
			$result[1] = $warning;
		}
		// check if string contains an email address
		elseif (preg_match("/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/si", $message)){
			$result[1] = $warning;
		}else{
			$result[0] = true;
		}
		
		return $result;
	}

    /**
	 * ACTION: Validate Alphabetic
	 *
	 * @param string $input
	 * @return true|false if the input contains only alphabetic characters or not.
	 */
    static public function ValidateAlphabetic($input){
        if (ctype_alpha($input)) {
            return true;
        }else{
            return false;
        }
    }
}?>