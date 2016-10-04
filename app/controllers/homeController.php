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

	/**
	 * PAGE: cronjob
	 * GET: /home/cronjob
	 * This method should handle all the checks once per day to send emails / notifications regarding checkins / checkouts and payments
	 */
	public function cronjob(){
		//Debug::printr('yo');die;
		$this->_reportsModel = $this->loadModel('reports');
		$this->_usersModel = $this->loadModel('users');
		$this->_notificationsModel = $this->loadModel('notifications');

		//First we're going to check our checkins and send a reminder
		//$check_ins = $this->_reportsModel->getUpcomingCheckIns();
		if(isset($check_ins) && !empty($check_ins)){
			foreach ($check_ins as $key => $checkin) {
				if(isset($checkin['user_ids']) && !empty($checkin['user_ids'])){
					$user_ids = explode(',', $checkin['user_ids']);
					foreach ($user_ids as $key2 => $user_id) {
						$user = $this->_usersModel->getUserReport($checkin['id'], $user_id);
						//if the user still has not checkedin then hit them with a reminder email and notification
						if(isset($user) && !empty($user) && empty($user[0]['check_in_signature'])){
							//notification creation
							$data['text'] = 'A check in process requires you\'re approval. Please review check in at the following link. <a href = "'.SITE_URL.'reports/checkin/'.$checkin['property_id'].'">Link</a>';
		                    $data['user_id'] = $user_id;
		                    $this->_notificationsModel->createData($data);

		                    //Email setup
		                    $this->_view->data['name'] = $user[0]['firstname'].' '.$user[0]['surname'];
		                    $this->_view->data['message'] = 'A check in process requires you\'re approval. Please review check in at the following link.';
		                    $this->_view->data['button_link'] = SITE_URL.'reports/checkin/'.$checkin['property_id'];
		                    $this->_view->data['button_text'] = 'Review Check In';

		                    // Need to create email
		                    $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
		                    Html::sendEmail($user[0]['email'], 'Checkmate - A Check in needs your approval.', SITE_EMAIL, $message);
						}

					}
				}
			}
		}

		//Now we're going to check our check outs and send a reminder
		//$check_outs = $this->_reportsModel->getUpcomingCheckOuts();
		if(isset($check_outs) && !empty($check_outs)){
			foreach ($check_outs as $key => $checkout) {
				if(isset($checkout['user_ids']) && !empty($checkout['user_ids'])){
					$user_ids = explode(',', $checkout['user_ids']);
					foreach ($user_ids as $key2 => $user_id) {
						$user = $this->_usersModel->getUserReport($checkout['id'], $user_id);
						//if the user still has not checkedin then hit them with a reminder email and notification
						if(isset($user) && !empty($user) && empty($user[0]['check_out_signature'])){
							//notification creation
							$data['text'] = 'A check out process requires you\'re approval. Please review check out at the following link. <a href = "'.SITE_URL.'reports/checkout/'.$checkout['property_id'].'">Link</a>';
		                    $data['user_id'] = $user_id;
		                    $this->_notificationsModel->createData($data);

		                    //Email setup
		                    $this->_view->data['name'] = $user[0]['firstname'].' '.$user[0]['surname'];
		                    $this->_view->data['message'] = 'A check out process requires you\'re approval. Please review check out at the following link.';
		                    $this->_view->data['button_link'] = SITE_URL.'reports/checkout/'.$checkout['property_id'];
		                    $this->_view->data['button_text'] = 'Review Check Out';

		                    // Need to create email
		                    $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
		                    Html::sendEmail($user[0]['email'], 'Checkmate - A Check out needs your approval.', SITE_EMAIL, $message);
						}

					}
				}
			}
		}

		//Now we're gonna remove old notifications
		$this->_notificationsModel->deleteOld();

		//If a check in has just passed its cut off day and no agreement has been made then email the peoples!
		$reports = $this->_reportsModel->getExpiredCheckIns();
		if(isset($reports) && !empty($reports)){
			foreach ($reports as $key => $report) {
				if(isset($report['user_ids']) && !empty($report['user_ids'])){
					$user_ids = explode(',', $report['user_ids']);
					foreach ($user_ids as $key2 => $user_id) {
						$user = $this->_usersModel->getUserReport($report['id'], $user_id);
						if(isset($user) && !empty($user)){
							//notification creation
							$data['text'] = 'Agreement regarding a check in could not be reached. Checkmate is independently reviewing the check in and will make a decision based on the information provided within 24/48 hours.';
		                    $data['user_id'] = $user_id;
		                    $this->_notificationsModel->createData($data);

		                    //Email setup
		                    $this->_view->data['name'] = $user[0]['firstname'].' '.$user[0]['surname'];
		                    $this->_view->data['message'] = 'Agreement regarding a check in could not be reached. Checkmate is independently reviewing the check in and will make a decision based on the information provided within 24/48 hours.';
		                    $this->_view->data['button_link'] = SITE_URL.'users/dashboard/';
		                    $this->_view->data['button_text'] = 'Checkmate Site.';

		                    // Need to create email
		                    $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
		                    Html::sendEmail($user[0]['email'], 'Checkmate - Agreement regarding a check in could not be reached.', SITE_EMAIL, $message);
		                    
						}
					}
					//Need to email site admin now so they know to review the disagreement.
                 	$this->_view->data['name'] = 'Site Admin';
                    $this->_view->data['message'] = 'Agreement regarding a check in could not be reached. Please review and make a decision.';
                    $this->_view->data['button_link'] = SITE_URL.'backoffice/reports/edit/'.$report['id'];
                    $this->_view->data['button_text'] = 'Review Check In';

                    // Need to create email
                    $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
                    Html::sendEmail(SITE_EMAIL, 'Checkmate - Agreement regarding a check in could not be reached.', SITE_EMAIL, $message);
				}
			}
		}

		//If a check out has just passed its cut off day and no agreement has been made then email the peoples!
		$reports = $this->_reportsModel->getExpiredCheckOuts();
		if(isset($reports) && !empty($reports)){
			foreach ($reports as $key => $report) {
				if(isset($report['user_ids']) && !empty($report['user_ids'])){
					$user_ids = explode(',', $report['user_ids']);
					foreach ($user_ids as $key2 => $user_id) {
						$user = $this->_usersModel->getUserReport($report['id'], $user_id);
						if(isset($user) && !empty($user)){
							//notification creation
							$data['text'] = 'Agreement regarding a check out could not be reached. Checkmate is independently reviewing the check out and will make a decision based on the information provided within 24/48 hours.';
		                    $data['user_id'] = $user_id;
		                    $this->_notificationsModel->createData($data);

		                    //Email setup
		                    $this->_view->data['name'] = $user[0]['firstname'].' '.$user[0]['surname'];
		                    $this->_view->data['message'] = 'Agreement regarding a check out could not be reached. Checkmate is independently reviewing the check out and will make a decision based on the information provided within 24/48 hours.';
		                    $this->_view->data['button_link'] = SITE_URL.'users/dashboard/';
		                    $this->_view->data['button_text'] = 'Checkmate Site.';

		                    // Need to create email
		                    $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
		                    Html::sendEmail($user[0]['email'], 'Checkmate - Agreement regarding a check out could not be reached.', SITE_EMAIL, $message);
		                    
						}
					}
					//Need to email site admin now so they know to review the disagreement.
                 	$this->_view->data['name'] = 'Site Admin';
                    $this->_view->data['message'] = 'Agreement regarding a check out could not be reached. Please review and make a decision.';
                    $this->_view->data['button_link'] = SITE_URL.'backoffice/reports/edit/'.$report['id'];
                    $this->_view->data['button_text'] = 'Review Check Out';

                    // Need to create email
                    $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
                    Html::sendEmail(SITE_EMAIL, 'Checkmate - Agreement regarding a check out could not be reached.', SITE_EMAIL, $message);
				}
			}
		}
	}

}
?>