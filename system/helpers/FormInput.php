<?php
/** FormInput */
class FormInput {
	/**
	 * ACTION: Check Form Input (remove HTML, XML, and PHP tags)
	 * This method sanatizes the form input fields
	 */
	public static function checkInput($data){
		$data = trim($data);
		$data = strip_tags($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
	/**
	 * ACTION: Check Form Input (keep HTML, XML, and PHP tags)
	 * This method sanatizes the form input fields
	 */
	public static function checkInputwithTags($data){
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
	/**
	 * ACTION: Remove blank fields from array
	 */
	public static function trimArray($array){
		foreach($array as $key => $value){
			if(empty($value)){
				unset($array[$key]);
			}else{
				$array[$key] = trim($value);
			}
		}
		return $array;
	}

    /**
	 * Sanatizes keywords for search fields
     * @param string $data the string of inputted search terms
	 */
	public static function checkKeywords($data){
		$data = trim(strtolower($data));
        if (!get_magic_quotes_gpc()) {
            $data = addslashes($data);
        }
		return $data;
	}
}?>