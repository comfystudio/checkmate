<?php
/** Items Controller */

class ItemsController extends BaseController {

	/** __construct */
	public function __construct(){
		parent::__construct();
		// Load the User Model ($modelName, $area)
		$this->_model = $this->loadModel('itemsBackoffice', 'backoffice');
	}

    /**
	 * PAGE: Items Index
	 * GET: /backoffice/items/index
	 * This method handles the view awards page
	 */
	public function index(){
        Auth::checkAdminLogin();

		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Item');
		// Set Page Description
		$this->_view->pageDescription = 'Item Index';
		// Set Page Section
		$this->_view->pageSection = 'Rooms';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'Items';

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
		$this->_view->render('items/index', 'layout', 'backoffice');
	}

    /**
	 * PAGE: Items Edit
	 * GET: /backoffice/items/edit:id
     * @param string $id The unique id for the award user
	 * This method handles the edit award user page
	 */
	public function edit($id = false){
        Auth::checkAdminLogin();
		if(!empty($id)){
			$selectDataByID = $this->_model->selectDataByID($id);
            if(isset($selectDataByID[0]['id']) && !empty($selectDataByID[0]['id'])){
                $this->_view->stored_data = $selectDataByID[0];
			}else{
                $this->_view->flash[] = "No Items matches this id";
                Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
				Url::redirect('backoffice/items/index');
			}
		}else{
            $this->_view->flash[] = "No ID provided for Items";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
			Url::redirect('backoffice/items/index');
		}

		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Item', 'Item');
		// Set Page Description
		$this->_view->pageDescription = 'Item edit';
		// Set Page Section
		$this->_view->pageSection = 'Rooms';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'Items';

		// Set Page Specific CSS
		//$this->_view->pageCss = $pageCss;

		// Set Page Specific Javascript
		//$this->_view->pageJs = $pageJs;

		// Set default variables
		$this->_view->error = array();

        // If Form has been submitted process it
		if(!empty($_POST['save'])){
            $_POST['id'] = $id;

            // Update Items details
            $updateData = $this->_model->updateData($_POST);

            if(isset($updateData['error']) && $updateData['error'] != null){
                foreach($updateData['error'] as $key => $error){
                    $this->_view->error[$key] = $error;
                }
            } else {
                $this->_view->flash[] = "Items updated successfully.";
                Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                Url::redirect('backoffice/items/index');
            }
		}

		if(!empty($_POST['cancel'])){
			Url::redirect('backoffice/items/index');
		}

		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('items/add', 'layout', 'backoffice');
	}

    /**
     * PAGE: Items Delete
     * GET: /backoffice/admin-users/delete/:id
     * This method handles the deletion of Items
     * @param string $id The unique id for the Items
     */
    public function delete($id){
        Auth::checkAdminLogin();
        // Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Item', 'Item');
		// Set Page Description
		$this->_view->pageDescription = 'Item Delete';
		// Set Page Section
		$this->_view->pageSection = 'Rooms';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'Items';

        //Check we got ID
        if(!empty($id)){
			$selectDataByID = $this->_model->selectDataByID($id);
            $this->_view->selectedData = $selectDataByID;

            //Check ID returns an Items
			if(isset($selectDataByID[0]['id']) && !empty($selectDataByID[0]['id'])){
                if(isset($_POST) && !empty($_POST)) {
                    if (!empty($_POST['delete'])) {
                        $deleteAttempt = $this->_model->deleteData($id);
                        //Check we have deleted Items
                        if (!empty($deleteAttempt)) {

                            // Redirect to next page
                            $this->_view->flash[] = "Items deleted successfully.";
                            Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                            Url::redirect('backoffice/items/index');
                        } else {
                            $this->_view->error[] = 'A problem has occurred when trying to delete this award.';
                        }
                    } elseif (!empty($_POST['cancel'])) {
                        Url::redirect('backoffice/items/index');
                    }
                }
			}else{
                $this->_view->flash[] = "No Items matches this id";
                Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
				Url::redirect('backoffice/items/index');
			}
		}else{
            $this->_view->flash[] = "No ID provided for Items";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
			Url::redirect('backoffice/items/index');
		}
        // Render the view ($renderBody, $layout, $area)
		$this->_view->render('items/delete', 'layout', 'backoffice');
    }


    /**
     * PAGE: Items Add
     * GET: /backoffice/items/add/:id
     * This method handles the adding of items
     */
	public function add(){
        Auth::checkAdminLogin();
		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Item', 'Item');
		// Set Page Description
		$this->_view->pageDescription = 'Item Add';
		// Set Page Section
		$this->_view->pageSection = 'Rooms';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'Items';

        $this->_view->error = array();

        // If Form has been submitted process it
		if(!empty($_POST)){
            //if user selected cancel
            if(!empty($_POST['cancel'])){
			    Url::redirect('backoffice/items/index');
		    }

            // Create new Items
            $createData = $this->_model->createData($_POST);
            if(isset($createData['error']) && $createData['error'] != null){
                foreach($createData['error'] as $key => $error){
                    $this->_view->error[$key] = $error;
                }
            }else{
                $this->_view->flash[] = "Item added successfully.";
                Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                Url::redirect('backoffice/items/index');
            }
		}
		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('items/add', 'layout', 'backoffice');
	}

}
?>