<?php
/** Partial */
class Partial{
    /** @var null The url */
	private $_url = null;
	
    /** @var null The URL for the controller */
	private $_urlForController = null;

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
	

	function __construct($url){
		// Sets the protected $_url
		$this->_GetUrl($url);
		
		// Check for an area
		$areaPath = 'app/areas/' . $this->_url[0];
			
        // Check if an Area exists with this url
		if (file_exists($areaPath)){			
        	// Set the Area Name
			$this->_areaName = $this->_url[0];
			
        	// Check if a Controller exists with this url
			if(!empty($this->_url[1])){
				// Set the Controller Name
				$this->_controllerName = $this->_urlForController[1];
			}
			else{
				$this->_Error();
			}
			
			// Set File path to Controller
			$controllerPath = 'app/areas/' . $this->_areaName .'/controllers/' . $this->_controllerName . 'Controller.php';
			
			// Check if an Action exists for this url
			if(!empty($this->_url[2])){
				// Set the Action Name and remove hyphens
				$this->_actionName = str_replace('-', '', $this->_url[2]);
			}
			else{
				$this->_Error();
			}
			
			// Get Parameters
			$this->_parameter1 = (empty($this->_url[3]) ? false : $this->_url[3]);
			$this->_parameter2 = (empty($this->_url[4]) ? false : $this->_url[4]);
			$this->_parameter3 = (empty($this->_url[5]) ? false : $this->_url[5]);
			$this->_parameter4 = (empty($this->_url[6]) ? false : $this->_url[6]);
		}
		else{			
        	// Check if a Controller exists with this url
			if(!empty($this->_url[0])){
				// Set the Controller Name
				$this->_controllerName = $this->_urlForController[0];
			}
			else{
				$this->_Error();
			}
			
			// Set File path to Controller
			$controllerPath = 'app/controllers/' . $this->_controllerName . 'Controller.php';

			// Check if an Action exists for this url
			if(!empty($this->_url[1])){
				// Set the Action Name and remove hyphens
				$this->_actionName = str_replace('-', '', $this->_url[1]);
			}
			else{
				$this->_Error();
			}
			
			// Get Parameters
			$this->_parameter1 = (empty($this->_url[2]) ? false : $this->_url[2]);
			$this->_parameter2 = (empty($this->_url[3]) ? false : $this->_url[3]);
			$this->_parameter3 = (empty($this->_url[4]) ? false : $this->_url[4]);
			$this->_parameter4 = (empty($this->_url[5]) ? false : $this->_url[5]);
		}
			
		// If the file exists
		if (file_exists($controllerPath)){			
			// Require the Controller
			require_once ($controllerPath);
			
			// Set the Controller Name by appending it with 'Controller'
			$theController = $this->_controllerName.'Controller';
			
			// Instantiate the Controller
			$this->_controller = new $theController();
			
			// if the action exists within the controller
			if (method_exists($this->_controller, $this->_actionName)){
				// Load the action and pass through parameters
			    $this->_controller->{$this->_actionName}($this->_parameter1,$this->_parameter2,$this->_parameter3,$this->_parameter4);
			}
			else{
				$this->_Error();
			}
		}
		else{
			$this->_Error();
		}
    }	
	
    /**
	 * _GetUrl
	 *
	 * Right Trim the URL to remove trailing slash
	 * Removes all illegal URL characters from the string
	 * Creates an Array from the URL  
	 * Converts First Letter of each in array to Uppercase
	 * Set the url
	 *
	 * Check URL for spaces or hyphens and set First Character afetr these to Uppercase
	 * Remove any hyphens
	 * Set the urlForController
	 */
	private function _GetUrl($url){
		$url = rtrim($url, '/');
		$url = filter_var($url, FILTER_SANITIZE_URL);
		$url = explode('/', $url);
		$this->_url = $url;
		
		$urlForController = preg_replace_callback('/(?<=( |-|_))./',
		    function ($m)
			{
				return strtoupper($m[0]);
			},
            $url);
		$urlForController = str_replace('-', '', $urlForController);
		$this->_urlForController = $urlForController;
	}
	
    /** _Error */
	private function _Error(){
		// Require the Controller
		require_once ('app/controllers/errorController.php');
		
		// Instantiate the Controller
		$controller = new ErrorController();
		
		// Call the Method
		$controller->PartialError();
		
		// End the function
		return false;
	}
}
?>