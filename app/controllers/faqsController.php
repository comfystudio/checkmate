<?php
/** Faqs Controller */

class FaqsController extends BaseController {

	/** __construct */
	public function __construct(){
		parent::__construct();
		// Load the User Model ($modelName, $area)
		$this->_model = $this->loadModel('faqs');
	}

    /**
	 * PAGE: Faqs Index
	 * GET: /backoffice/faqs/index
	 * This method handles the view awards page
	 */
	public function index(){
		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('FaqS');
		// Set Page Description
		$this->_view->pageDescription = 'Check Mate FaQs';
		// Set Page Section
		$this->_view->pageSection = 'Pages';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'Faqs';

        $this->_view->data = $this->_model->getAlldata();

		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('faqs/index', 'layout');
	}
}
?>