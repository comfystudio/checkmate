<?php
/** Notifications Controller */

class NotificationsController extends BaseController {

	/** __construct */
	public function __construct(){
		parent::__construct();
		// Load the User Model ($modelName, $area)
		$this->_model = $this->loadModel('notifications');
	}

    /**
	 * PAGE: Notifications view
	 * GET: /backoffice/notifications/view:id
	 * This method handles the view awards page
	 *@param $int id
	 **/
	public function view($id = null){
        Auth::checkUserLogin();

        if(!isset($id) || $id == null){
        	$this->_view->flash[] = "No ID was provided";
	        Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
			Url::redirect('users/');
        }

		$selectDataByID = $this->_model->selectDataByID($id);

		if(!isset($selectDataByID) || empty($selectDataByID)){
			$this->_view->flash[] = "No data matches this ID";
	        Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
			Url::redirect('users/');
		}

		if($selectDataByID[0]['user_id'] != $_SESSION['UserCurrentUserID']){
			$this->_view->flash[] = "This notification does not belong to you.";
	        Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
			Url::redirect('users/');
		}

		$this->_view->data = $selectDataByID;


		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Notifications', 'Notifications');
		// Set Page Description
		$this->_view->pageDescription = 'Checkmate Notifications';
		// Set Page Section
		$this->_view->pageSection = 'Pages';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'Notifications';

		// Need to set the read to 1 now.
		$this->_model->setReadById($selectDataByID[0]['id']);


		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('notifications/view', 'layout');
	}
}
?>