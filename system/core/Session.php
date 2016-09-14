<?php
/** Session */
class Session{
	private static $_sessionStarted = false;
	
    /** init */
	public static function init(){
		if(self::$_sessionStarted == false){
			session_start();
			self::$_sessionStarted = true;
		}
	}

    /**
     * set
     * @param string $key Session Key
     * @param string $value Value for key
     */
	public static function set($key, $value){
		$_SESSION[$key] = $value;
	}

    /**
     * get
     * @param string $key Session Key
     */
	public static function get($key){
		if(isset($_SESSION[$key]))
		return $_SESSION[$key];
	}
	
    /**
     * display
     * @return string The Session
     */
	public static function display(){
		return $_SESSION;
	}	
	
    /**
     * destroy
     * @param string $key Session Key
     */
	public static function destroy($key){
		if(isset($_SESSION[$key]))
		unset($_SESSION[$key]);
	}

    /**
     * destroyAll
     */
	public static function destroyAll(){
		if(self::$_sessionStarted == true){
			session_unset();
			session_destroy();
		}
	}	
}
?>