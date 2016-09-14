<?php
/** Referer */
class Referer{
	/**
	 * FUNCTION: getReferer
	 * Can be used for redirections after login signup, etc.
	 */
	public static function getReferer(){
		if (CURRENT_URL){
		    $refererController = CURRENT_URL;
			// Set Session variables for Requested Page
			Session::set('RefererController', $refererController);
	    }
	}
	
	/**
	 * FUNCTION: destroyReferer
	 * This method clears the Referer
	 */
	public static function destroyReferer(){
		// Destroy Session variables for Referer
		Session::destroy('RefererController');
	}
}