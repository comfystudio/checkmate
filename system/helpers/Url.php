<?php
/**  Url */
class Url {
	/**
	 * FUNCTION: redirect
	 * @param string $url  The given URL
	 * This method redirects to the requested location
	 */
	public static function redirect($url = null){
		header('location: /' . $url);
		exit;
	}
}
?>