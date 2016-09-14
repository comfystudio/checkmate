<?php
/** Debug */
class Debug {
	/**
	 * Function: printr
	 * $data mixed  An array of data
	 */
	public static function printr($data){
    	echo '<pre>';
    	print_r($data);
    	echo '</pre>';
	}
}?>