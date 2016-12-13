<?php
/** Payments Controller */

class PaymentsController extends BaseController {

	/** __construct */
	public function __construct(){
		parent::__construct();
		// Load the User Model ($modelName, $area)
		$this->_model = $this->loadModel('paymentsBackoffice', 'backoffice');
	}

    /**
	 * PAGE: Payments Index
	 * GET: /backoffice/payments/index
	 * This method handles the view awards page
	 */
	public function index(){
        Auth::checkAdminLogin();

		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Payments');
		// Set Page Description
		$this->_view->pageDescription = 'Payments Index';
		// Set Page Section
		$this->_view->pageSection = 'Users';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'Payments';

        $paymentTypes = explode(',', PAYMENTS);
        $this->_view->paymentTypes = $paymentTypes;

		###### PAGINATION ######
        //sanitise or set keywords to false
        if(isset($_GET['keywords']) && !empty($_GET['keywords'])){
            $_GET['keywords'] = FormInput::checkKeywords($_GET['keywords']);
        }else{
            $_GET['keywords'] = false;
        }

        $totalItems = $this->_model->countAllData($_GET['keywords']);
        if(!isset($totalItems) || empty($totalItems)){
            $totalItems = 0;
        }
        $pages = new Pagination(20,'keywords='.$_GET['keywords'].'&page', $totalItems[0]['total']);
        $this->_view->getAllData = $this->_model->getAllData($pages->get_limit(), $_GET['keywords']);

		// Create the pagination nav menu
		$this->_view->page_links = $pages->page_links();

		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('payments/index', 'layout', 'backoffice');
	}


    /**
     * PAGE: Payments View
     * GET: /backoffice/payments/view:id
     * This method handles the view payments page
     * @param int $id
     */
    public function view($id){
        Auth::checkAdminLogin();
        if(!empty($id)){
            // Set the Page Title ('pageName', 'pageSection', 'areaName')
            $this->_view->pageTitle = array('Payments');
            // Set Page Description
            $this->_view->pageDescription = 'View Payments';
            // Set Page Section
            $this->_view->pageSection = 'Users';
            // Set Page Sub Section
            $this->_view->pageSubSection = 'Payments';
            
            // Fetch array of messages for this conversation
            $this->_view->getAllData = $this->_model->selectDataByID($id);
    
            // Render the view ($renderBody, $layout, $area)
            $this->_view->render('payments/view', 'layout', 'backoffice');
        } else {
            Url::redirect('backoffice/payments/');
        }
    }

    /**
     * PAGE: Add Bonus credits
     * GET: /backoffice/payments/bonus
     * This method handles the adding of bonus credits to paymens
     */
    public function bonus($user_id = false){
        Auth::checkAdminLogin();

        if(!empty($user_id)){
            $this->_userModel = $this->loadModel('UserBackoffice', 'backoffice');
            $selectDataByID = $this->_userModel->selectDataByID($user_id);
            if(isset($selectDataByID[0]['id']) && !empty($selectDataByID[0]['id'])){
                $this->_view->stored_data = $selectDataByID[0];
            }else{
                $this->_view->flash[] = "No user matches this id";
                Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
                Url::redirect('backoffice/users/index');
            }
        }else{
            $this->_view->flash[] = "No ID provided for User";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('backoffice/users/index');
        }

        // Set the Page Title ('pageName', 'pageSection', 'areaName')
        $this->_view->pageTitle = array('Add', 'Users');
        // Set Page Description
        $this->_view->pageDescription = '';
        // Set Page Section
        $this->_view->pageSection = 'Users';
        // Set Page Sub Section
        $this->_view->pageSubSection = 'Users';

        // Set default variables
        $this->_view->error = array();

        // If Form has been submitted process it
        if(!empty($_POST)){
            //if user selected cancel
            if(!empty($_POST['cancel'])){
                Url::redirect('backoffice/users/index');
            }


            if(isset($selectDataByID[0]['payment_id']) && !empty($selectDataByID[0]['payment_id'])){
                $_POST['id'] = $selectDataByID[0]['payment_id'];
                $createData = $this->_model->updateBonus($_POST);
            }else{
                $_POST['user_id'] = $user_id;
                $_POST['stripe_cus_id'] = 'temp_'.$user_id;
                $_POST['stripe_sub_id'] = 'temp_'.$user_id;
                $createData = $this->_model->createData($_POST);
            }

            if(isset($createData['error']) && $createData['error'] != null){
                foreach($createData['error'] as $key => $error){
                    $this->_view->error[$key] = $error;
                }
            }else{
                // Send email with good news
                $this->_view->data['name'] = $selectDataByID[0]['firstname'] .' '. $selectDataByID[0]['surname'];
                $this->_view->data['message'] = 'The Administrator has gifted you '.$_POST['bonus_credits'].' bonus credits! You can now create more properties.';
                $this->_view->data['button_link'] = SITE_URL.'users/dashboard/';
                $this->_view->data['button_text'] = 'Dashboard';

                // Need to create email
                $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
                Html::sendEmail($selectDataByID[0]['email'], 'Checkmate - Bonus Property Credits', SITE_EMAIL, $message);

                $this->_view->flash[] = "Bonus added successfully.";
                Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                Url::redirect('backoffice/users/');
            }
        }
        // Render the view ($renderBody, $layout, $area)
        $this->_view->render('payments/bonus', 'layout', 'backoffice');
    }
}
?>