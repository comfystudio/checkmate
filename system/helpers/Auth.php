<?php
/** Auth */
class Auth{
	/**
	 * FUNCTION: checkAdminLogin
	 * This method checks if the current user is Admin
	 * If False Session is destroyed
	 * User is redirected to login as they don't have permission
	 */
	public static function checkAdminLogin(){
		if(Session::get('AdminLoggedIn') == false){
			Session::destroy('AdminLoggedIn');
			header('Location: /backoffice/login');
			exit();
		}
	}
	
	/**
	 * FUNCTION: checkUserLogin
	 * This method checks if the current user is logged in
	 * If False destroy the session
	 * Redirected to login
	 */
	public static function checkUserLogin(){
		if(Session::get('UserLoggedIn') == false){
			if(empty($SESSION['RefererController'])){
				// Get Referer
				Referer::getReferer();
			}
			
			Session::destroy('UserLoggedIn');
			header('Location: /login');
			exit();
		}
	}

}
?>