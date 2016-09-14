<?php
/** Error Controller */
class ErrorController extends BaseController {
	/** __construct */
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * PAGE: Index
	 * GET: /error/index
	 * This method handles the Error page
	 */
	public function index(){
		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Error');
		// Set Page Description
		$this->_view->pageDescription = 'An Error has occurred';
		// Set Page Section 
		$this->_view->pageSection = 'Error';

		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('error/index', 'error-layout');
	}
	
	/**
	 * PARTIAL: _PartialError
	 * GET: /error/_partial_error
	 * This method handles the partial view for errors
	 */
	public function partialError(){
		// Render the Partial View ($renderPartial, $area)
		$this->_view->renderPartial('error/_partial-error');
	}

}
?>