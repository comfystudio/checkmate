<?php
// Start Output Buffering
ob_start();
// Get App Config
require_once('app/app_start/appConfig.php');
// Get Bundle Config
require_once('app/app_start/bundleConfig.php');

// Autoload Core Library and Helpers
spl_autoload_register('AppAutoloader::coreLoader');
spl_autoload_register('AppAutoloader::helperLoader');

class AppAutoloader{
	/**
	 * Core Loader
     * This method loads the Core Library Classes
     */
    public static function coreLoader($class){
		$filename = "system/core/" . $class . ".php";
		if(file_exists($filename)){
			require $filename;
		}
    }

	/**
	 * Helper Loader
     * This method loads the Helper Library Classes
     */
    public static function helperLoader($class){
		$filename = "system/helpers/" . $class. ".php";
		if(file_exists($filename)){
            require $filename;
        }
    }
}

// Create new instance of the app
$app = new Bootstrap();

// End Output Buffering
ob_flush();
?>