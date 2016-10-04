<?php
/** AboutUs Controller */
class AboutUsController extends BaseController {
	/** __construct */
	public function __construct(){
		parent::__construct();

        $this->_model = $this->loadModel('aboutUs');
	}

	/**
	 * PAGE: index
	 * GET: /about-us/index
	 * This method handles the sites index AboutUs page
	 */
	public function index(){
		$this->_view->data = $this->_model->getAllData();
		//Debug::printr($this->_view->data);

		if(!isset($this->_view->data) || empty($this->_view->data)){
			Url::redirect('users/index');
		}

		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('AboutUs');
		// Set Page Description
		$this->_view->pageDescription = 'Checkmate About Us';
		// Set Page Section
		$this->_view->pageSection = 'AboutUs';
		// Set Page Sub Section
		$this->_view->pageSubSection = '';


		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('about-us/index');
	}

}
?>