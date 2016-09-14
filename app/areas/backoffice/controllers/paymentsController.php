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
}
?>