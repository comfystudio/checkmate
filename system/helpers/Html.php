<?php
/** Html */
class Html {
	
	/** __construct */
	public function __construct(){
	}
	
	/**
	 * FUNCTION: getSubNav
	 * @param string $url  The given URL
	 * This method includes the required navigation
	 */
	static public function getSubNav($url = false, $area = false, $pageSubSection = false){
		$theNav = 'app/';
		$theNav .= !empty($area) ? 'areas/' . $area . '/' : '';
		$theNav .= 'views/' . $url;
		$theNav .= '.php';

		require_once $theNav;
	}
	
	/**
	 * ACTION: Format Errors
	 *
	 * @param array $errors
	 * @return The errors in a formatted HTML list
	 */
	static public function formatErrors($errors = array()){
		$result = '<div class="alert"><ul>';
		foreach ($errors as $error) {
			$result .= "<li>$error</li>";
		}
		$result .= '</ul></div>';
		return $result;
	}
	
	/**
	 * ACTION: Format Backoffice Errors
	 *
	 * @param array $errors
	 * @return The errors in a formatted HTML list
	 */
	static public function formatBackofficeErrors($errors = array()){
		$result = '<ul>';
		foreach ($errors as $error) {
			$result .= "<li>$error</li>";
		}
		$result .= '</ul>';
		return $result;
	}
	
	/**
	 * ACTION: Format Success
	 *
	 * @param array $successes
	 * @return The success in a formatted HTML list
	 */
	static public function formatSuccess($successes = array()){
		$result = '<div class="success"><ul>';
		foreach ($successes as $success) {
			$result .= "<li>$success</li>";
		}
		$result .= '</ul></div>';
		return $result;
	}
	
	/**
	 * ACTION: Format Backoffice Success
	 *
	 * @param array $successes
	 * @return The success in a formatted HTML list
	 */
	static public function formatBackofficeSuccess($successes = array()){
		$result = '<ul>';
		foreach ($successes as $success) {
			$result .= "<li>$success</li>";
		}
		$result .= '</ul>';
		return $result;
	}
	
	/**
	 * ACTION: Format Warnings
	 *
	 * @param array $warnings
	 * @return The warnings in a formatted HTML list
	 */
	static public function formatWarnings($warnings = array()){
		$result = '<div class="information"><ul>';
		foreach ($warnings as $warning) {
			$result .= "<li>$warning</li>";
		}
		$result .= '</ul></div>';
		return $result;
	}
	
	/**
	 * ACTION: Send email
	 *
	 * @param array $to, $subject, $from, $message
	 * @return True or false value
	 */
	static public function sendEmail($to, $subject, $from = SITE_EMAIL, $message){
		$result[0] = false;
		// Email To User
		$headers = "From: $from\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
		
		// send mail
		$sendMail = mail($to,$subject,$message,$headers);
		if(!empty($sendMail)){
			$result[0] = true;
		}else{
			$result[1] = 'There was a problem trying to send email.';
		}
		return $result;
	}
	
	/**
	 * ACTION: Location Distance
	 *
	 * @param array $lat1, $lon1, $lat2, $lon2, $unit
	 */
	static public function locationDistance($lat1, $lon1, $lat2, $lon2, $unit){
		$theta = $lon1 - $lon2;
	  	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	  	$dist = acos($dist);
	  	$dist = rad2deg($dist);
	  	$miles = $dist * 60 * 1.1515;
	  	$unit = strtoupper($unit);
	
	  	if ($unit == "K") {
			return ($miles * 1.609344);
	  	} else if ($unit == "N") {
		  	return ($miles * 0.8684);
		} else {
			return $miles;
		}
	}
	
	/**
	 * ACTION: Generate Booking Code
	 *
	 * @return $bookingCode
	 */
	static public function generateBookingCode() {
		// Generate 4 random letters
		$char_length = 4;
	    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $letters = '';
	    for ($i = 0; $i < $char_length; $i++) {
	        $letters .= $characters[rand(0, $charactersLength - 1)];
	    }
	    
	    // Generate 6 random numbers
	    $char_length = 6;
	    $characters = '0123456789';
	    $charactersLength = strlen($characters);
	    $numbers = '';
	    for ($i = 0; $i < $char_length; $i++) {
	        $numbers .= $characters[rand(0, $charactersLength - 1)];
	    }
	    
	    return $letters.$numbers;
	}
	
	 /**
	 * ACTION: Detect Bot
	 *
	 * @param string $user_agent
	 * @return True or false value
	 */
	static public function detectBot($user_agent) {
		$bot_list = array('bot','slurp','spider','crawl','archiver');
		$is_bot = false;
		foreach ($bot_list as $bot){
			//detect the bot name from the HTTP USER AGENT
		   if (!empty($user_agent) && (stristr($user_agent, $bot) == true )){
				$is_bot = true;
				break;
			}
		}
		return $is_bot;
	}

}
?>