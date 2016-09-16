<?php
/** Prices Controller */

class PricesController extends BaseController {

	/** __construct */
	public function __construct(){
		parent::__construct();
		// Load the User Model ($modelName, $area)
		$this->_model = $this->loadModel('prices');
	}

    /**
	 * PAGE: Prices Index
	 * GET: /backoffice/prices/index
	 * This method handles the view awards page
	 **/
	public function index(){
		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Prices', 'Prices');
		// Set Page Description
		$this->_view->pageDescription = 'Checkmate Prices';
		// Set Page Section
		$this->_view->pageSection = 'Pages';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'Prices';

        $this->_view->data = $this->_model->getAllData();

		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('prices/index', 'layout');
	}
}
?>