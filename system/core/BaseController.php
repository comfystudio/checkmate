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

        //checking sessions flash so we can always pass message along pages
        if(!empty($_SESSION['backofficeFlash'])){
			$this->_view->flash = $_SESSION['backofficeFlash'];
			//Session::destroy('backofficeFlash');
		}

		//define('CLEAN', array(0 => 'Very Clean', 1 => 'Moderately Clean', 2 => 'Unclean'));

		//Need the news information for nearly every stage of the site.
		$this->_newsModel = $this->loadModel('news');
		$this->_view->footerNews = $this->_newsModel->getFooterNews();
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
}
?>