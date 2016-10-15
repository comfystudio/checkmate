<?php
/** BaseController */
class BaseController{
	protected $_view;
	protected $USERS = array(0 => 'Tenant', 1 => 'Landlord', 2 => 'Letting Agent');

	
    /** __construct */
    public function __construct(){
		$this->_view = new View();
		
		// Load the Site Model ($modelName, $area)
		$this->_siteModel = $this->loadModel('site');
        $this->_contactModel = $this->loadModel('contactUs');

        //checking sessions flash so we can always pass message along pages
        if(!empty($_SESSION['backofficeFlash'])){
			$this->_view->flash = $_SESSION['backofficeFlash'];
			Session::destroy('backofficeFlash');
		}

		//Need the news information for nearly every stage of the site.
		$this->_newsModel = $this->loadModel('news');
		$this->_view->footerNews = $this->_newsModel->getFooterNews();

        //Getting contact info to use for header footer etc
        $this->_view->contactInfo = $this->_contactModel->getAllData();
    }
	
    /**
     * loadModel - Loads a Model
	 * 
     * @param string $modelName - The name of the Model
	 * @param string $area - The Area where the Model is located
     * @return ..\app\$area\models
     */
	public function loadModel($modelName, $area = false){
		// Create the modelPath
		$modelPath = 'app/';
		
		// Check if an Area has been defined
		if ($area){
			// Create the modelPath
		    $modelPath .= 'areas/' . $area . '/';
		}
		
		// Create the modelPath
		$modelPath .= 'models/'. $modelName . '.php';
		
		// Check if the Model Path Exists
		if (file_exists($modelPath)){
			// Require the Model
			require_once ($modelPath);
			
			// Instantiate the Model
			return new $modelName();
		}
	}


    /*
     * checkMembership
     * Used to work out if the current user has an active membership or not.
     */
    public function checkMembership(){
        $user_id = $_SESSION['UserCurrentUserID'];
        $this->_users = $this->loadModel('users');
        $user = $this->_users->selectDataByID($user_id);
        // if there is no user then false
        if(!isset($user) || empty($user)){
            return false;
        }

        // if there is no payment type they false
        if(!isset($user[0]['payment_type']) || empty($user[0]['payment_type'])){
            return false;
        }

        //Need to check active until date so we know they're still active payees
        $active_until = strtotime($user[0]['active_until']);
        $now = strtotime('now');
        if($now > $active_until){
            return false;
        }else{
            return true;
        }
    }
}
?>