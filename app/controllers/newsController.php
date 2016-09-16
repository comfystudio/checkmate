<?php
/** News Controller */
class NewsController extends BaseController {
	/** __construct */
	public function __construct(){
		parent::__construct();

        $this->_model = $this->loadModel('news');
	}

	/**
	 * PAGE: view
	 * GET: /news/view:slug
	 * This method handles the sites view news page
	 *@param string $slug
	 */
	public function view($slug = false){
		$this->_view->data = $this->_model->selectDataBySlug($slug);
		//Debug::printr($this->_view->data);

		if(!isset($this->_view->data) || empty($this->_view->data)){
			Url::redirect('users/index');
		}

		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('News', $slug);
		// Set Page Description
		$this->_view->pageDescription = 'Checkmate '.$slug;
		// Set Page Section
		$this->_view->pageSection = 'News';
		// Set Page Sub Section
		$this->_view->pageSubSection = '';


		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('news/view');
	}

}
?>