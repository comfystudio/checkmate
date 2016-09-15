<?php
/** Home Controller */
class HomeController extends BaseController {
	/** __construct */
	public function __construct(){
		parent::__construct();

	}

	/**
	 * PAGE: Index
	 * GET: /home/index
	 * This method handles the sites home page
	 */
	public function index(){
		if(!isset($_SESSION['AdminLoggedIn']) || empty($_SESSION['AdminLoggedIn'])){
			Url::redirect('home/holding');
		}

		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array();
		// Set Page Description
		$this->_view->pageDescription = '';
		// Set Page Section
		$this->_view->pageSection = 'Home';
		// Set Page Sub Section
		$this->_view->pageSubSection = '';

		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('home/index');

	}

	/**
	 * PAGE: Index
	 * GET: /home/index
	 * This method handles the sites home page
	 */
	public function holding(){
		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array();
		// Set Page Description
		$this->_view->pageDescription = '';
		// Set Page Section
		$this->_view->pageSection = 'Holding';
		// Set Page Sub Section
		$this->_view->pageSubSection = '';

		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('/home/holding');
	}

}
?>