<?php
/** Bootstrap */
class Bootstrap{
    /** @var null The url */
	private $_url = null;

    /** @var null The URL for the controller */
	private $_urlForController = null;

    /** @var null The Area */
    private $_area = null;

    /** @var null The Area Name */
    private $_areaName = null;

    /** @var null The Controller */
    private $_controller = null;

    /** @var null The Controller Name */
    private $_controllerName = null;

    /** @var null The Action Name */
    private $_actionName = null;

    /** @var null The Parameter 1 */
    private $_parameter1 = null;

    /** @var null The Parameter 2 */
    private $_parameter2 = null;

    /** @var null The Parameter 3 */
    private $_parameter3 = null;

	/** @var null The Parameter 4 */
    private $_parameter4 = null;

	function __construct(){
		// Start the session class
        Session::init();

		// Sets the protected $_url
		$this->_GetUrl();

        // If URL is empty Load Default Controller
/*
		if (empty($this->_url[0])){
		    $this->_LoadDefaultController();
			return false;
		}
*/

        // Set match
        $match = false;

        if(file_exists('app/app_start/routeConfig.php')) {
            // Load Route File
            require_once('app/app_start/routeConfig.php');

            $compressedUrl = implode('/',$this->_urlForController);

            // Loop  ($RouteName, $RouteUrl, $RouteArea, $RouteController, $RouteAction)
            foreach ($routeArray as $key => $value) {

	            // If :params in route value
	            if(strpos($value->routeUrl, ":params") !== false){
		           	// Default for current url matching route url
		           	$array_match = true;
		            // Check if url doesn't have parameters after the controller
					if($compressedUrl == str_replace('/:params', '', $value->routeUrl)){
						// Set match to false as page does not contain parameters after the controller in url
						$array_match = false;
					}else{
			            // Explode current url
						$explodedUrl = explode("/", $compressedUrl);
			             // Explode route url
						$routeExplodedUrl = explode("/", str_replace('/:params', '', $value->routeUrl));
						// Loop route url array
						$i = 0;
						foreach($routeExplodedUrl AS $item){
							// If route url postition doesn't match current url position set match to false
							if($item != $explodedUrl[$i]){
								$array_match = false;
							}
						++$i;}
					}
		        }

				// Checks if url contains routeUrl before :params OR url = route url
	            if((!empty($value->routeUrl) && !empty($array_match) && $array_match == true) || $compressedUrl == $value->routeUrl){
		            // Check if routeArea and routeController are populated
		            if (!empty($value->routeArea) || !empty($value->routeController)) {

			            // If routeArea populated
                        if(!empty($value->routeArea)){
	                        // Check area exists
	                        $areaPath = 'app/areas/' . $value->routeArea;
	                        if (file_exists($areaPath)) {
	                            // Set the Area Name
	                            $this->_areaName = $value->routeArea;
	                            // Set the Controller Name
								$this->_controllerName = $value->routeController;
	                            // Set File path to Controller
								$controllerPath = 'app/areas/' . $this->_areaName . '/controllers/' . $this->_controllerName . 'Controller.php';
								// Set the Action Name
								$this->_actionName = $value->routeAction;
								// If RouteUrl contains parameters
								if(strpos($compressedUrl, str_replace(':params', '', $value->routeUrl)) !== false){
									$url_parts = explode('/', $value->routeUrl);
									// First Parameter URL position : /area/controller/:param
									$param_start = 2;
								}else{
									// First Parameter URL position : /area/controller/action/:param
									$param_start = 3;
								}
							}else{
								// RouteArea doesn't exist
								//$this->_Error();
							}
						}
						// If routeController populated
						elseif(!empty($value->routeController)){

							// Set the Controller Name
							$this->_controllerName = $value->routeController;
	                        // Set File path to Controller
							$controllerPath = 'app/controllers/' . $this->_controllerName . 'Controller.php';
							// Set the Action Name
							$this->_actionName = $value->routeAction;
							// If RouteUrl contains parameters
							if(!empty($value->routeUrl) && !empty($array_match) && $array_match == true){
								// First Parameter URL position : /controller/:param
								$url_parts = explode('/', $value->routeUrl);
								$param_start = count($url_parts) - 1;
							}else{
								// First Parameter URL position : /controller/action/:param
								$param_start = 2;
							}
						}

						 // Set parameters if they exist
                        $i = 1;
                        $a = $param_start;
                        while($a < ($param_start + 4)){
	                        if(!empty($this->_url[$a])){
		                        $par = '_parameter'.$i;
                        		$this->{$par} = $this->_url[$a];
                        	}
                        ++$i; ++$a;}

                        if(file_exists($controllerPath)){

	                        $match = true;

                            // Require the Controller
                            require_once($controllerPath);

                            // Set the Controller Name by appending it with 'Controller'
                            $theController = $this->_controllerName . 'Controller';

                            // Instantiate the Controller
                            $this->_controller = new $theController();

                            // if the action exists within the controller
                            if (method_exists($this->_controller, $this->_actionName)) {

                                // Load the action and pass through parameters
                                $this->_controller->{$this->_actionName}($this->_parameter1, $this->_parameter2, $this->_parameter3, $this->_parameter4);
                            }else{
                                $match = false;
                            }
                        } else {
                            //$this->_Error();
                        }
                    }
                }
            }// foreach
        }

        if($match == false) {
            // If URL is empty Load Default Controller
            if (empty($this->_url[0])){
                $this->_LoadDefaultController();
                return false;
            }

            // Check for an area
            $areaPath = 'app/areas/' . $this->_urlForController[0];
            if (file_exists($areaPath)) {
                // Load the Area Registration File
                require_once('app/areas/' . $this->_urlForController[0] . '/' . $this->_urlForController[0] . 'AreaRegistration.php');

                // Set the Area Name
                $this->_areaName = $this->_urlForController[0];

                // Set the theArea by appending it with 'Area'
                $theArea = $this->_areaName . 'Area';

                // Instantiate the Area
                $this->_area = new $theArea();

                // if the action exists
                if (method_exists($this->_area, 'registerArea')) {
                    // Load the action
                    $areaDetails = $this->_area->{'registerArea'}();
                    // Get the Default Area Controller
                    $areaController = $areaDetails['areaController'];
                    // Get the Default Area Action
                    $areaAction = $areaDetails['areaAction'];
                } else {
                    $this->_Error();
                }

                // Set the Controller Name
                $this->_controllerName = (empty($this->_urlForController[1]) ? $areaController : $this->_urlForController[1]);

                // Set File path to Controller
                $controllerPath = 'app/areas/' . $this->_areaName . '/controllers/' . $this->_controllerName . 'Controller.php';

                // Set the Action Name and remove hyphens
                $this->_actionName = (empty($this->_url[2]) ? $areaAction : str_replace('-', '', $this->_url[2]));

                // Get Parameters
                $this->_parameter1 = (empty($this->_url[3]) ? false : $this->_url[3]);
                $this->_parameter2 = (empty($this->_url[4]) ? false : $this->_url[4]);
                $this->_parameter3 = (empty($this->_url[5]) ? false : $this->_url[5]);
                $this->_parameter4 = (empty($this->_url[6]) ? false : $this->_url[6]);

            } else {

                // Get the Controller Name
                $this->_controllerName = $this->_urlForController[0];

                // Set File path to Controller
                $controllerPath = 'app/controllers/' . $this->_controllerName . 'Controller.php';

                // Get the Action Name and remove hyphens
                $this->_actionName = (empty($this->_url[1]) ? 'index' : str_replace('-', '', $this->_url[1]));

                // Get Parameters
                $this->_parameter1 = (empty($this->_url[2]) ? false : $this->_url[2]);
                $this->_parameter2 = (empty($this->_url[3]) ? false : $this->_url[3]);
                $this->_parameter3 = (empty($this->_url[4]) ? false : $this->_url[4]);
                $this->_parameter4 = (empty($this->_url[5]) ? false : $this->_url[5]);
            }

            // If the file exists
            if (file_exists($controllerPath)) {

                // Require the Controller
                require_once($controllerPath);

                // Set the Controller Name by appending it with 'Controller'
                $theController = $this->_controllerName . 'Controller';

                // Instantiate the Controller
                $this->_controller = new $theController();

                // if the action exists within the controller
                if (method_exists($this->_controller, $this->_actionName)) {

                    // Load the action and pass through parameters
                    $this->_controller->{$this->_actionName}($this->_parameter1, $this->_parameter2, $this->_parameter3, $this->_parameter4);

                }else{
                    $this->_Error();
                }
            }
            else
            {
                $this->_Error();
            }
        }

    }

    /**
	 * _GetUrl
	 *
	 * If the URL is set Get URL Else URL is null
	 * Right Trim the URL to remove trailing slash
	 * Removes all illegal URL characters from the string
	 * Creates an Array from the URL
	 * Set the url
	 *
	 * Check URL for spaces or hyphens and set First Character after these to Uppercase
	 * Remove any hyphens
	 * Set the urlForController
	 */
	private function _GetUrl(){
		$url = isset($_GET['url']) ? $_GET['url'] : null;
		$url = rtrim($url, '/');
		$url = filter_var($url, FILTER_SANITIZE_URL);
		$url = explode('/', $url);
		$this->_url = $url;
		$urlForController = preg_replace_callback('/(?<=( |-|_))./',
			function ($m){
				return strtoupper($m[0]);
			},
			$url);
		$urlForController = str_replace('-', '', $urlForController);
		$this->_urlForController = $urlForController;

		// End the function
		return false;
	}

    /** _LoadDefaultController */
	private function _LoadDefaultController(){
		// Require the Controller
		require 'app/controllers/homeController.php';

		// Instantiate the Controller
		$this->_controller = new HomeController();

		// Call the Method
		$this->_controller->index();

		// End the function
		return false;
	}

    /** _Error */
	private function _Error(){
		// Require the Controller
		require_once ('app/controllers/errorController.php');

		// Instantiate the Controller
		$this->_controller = new ErrorController();

		// Call the Method
		$this->_controller->index();

		// End the function
		return false;
	}

}
?>