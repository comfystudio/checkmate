<?php
/** Rooms Controller */

class RoomsController extends BaseController {

	/** __construct */
	public function __construct(){
		parent::__construct();
		// Load the User Model ($modelName, $area)
		$this->_model = $this->loadModel('roomsBackoffice', 'backoffice');
	}

    /**
	 * PAGE: Room Index
	 * GET: /backoffice/rooms/index
	 * This method handles the view awards page
	 */
	public function index(){
        Auth::checkAdminLogin();

		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Rooms', 'Rooms');
		// Set Page Description
		$this->_view->pageDescription = 'Rooms Index';
		// Set Page Section
		$this->_view->pageSection = 'Rooms';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'Rooms';

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
        $this->_view->countData = $this->_model->countAllData();

		// Create the pagination nav menu
		$this->_view->page_links = $pages->page_links();

		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('rooms/index', 'layout', 'backoffice');

	}

    /**
	 * PAGE: Rooms Edit
	 * GET: /backoffice/rooms/edit:id
     * @param string $id The unique id for the award user
	 * This method handles the edit award user page
	 */
	public function edit($id = false){
        Auth::checkAdminLogin();
		if(!empty($id)){
			$selectDataByID = $this->_model->selectDataByID($id);
            if(isset($selectDataByID[0]['id']) && !empty($selectDataByID[0]['id'])){
                $this->_view->stored_data = $selectDataByID[0];
                $this->_view->stored_data['items'] = explode(',', $this->_view->stored_data['items']);

			}else{
                $this->_view->flash[] = "No Rooms matches this id";
                Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
				Url::redirect('backoffice/rooms/index');
			}
		}else{
            $this->_view->flash[] = "No ID provided for Rooms";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
			Url::redirect('backoffice/rooms/index');
		}

		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Rooms', 'Rooms');
		// Set Page Description
		$this->_view->pageDescription = 'Rooms Edit';
		// Set Page Section
		$this->_view->pageSection = 'Rooms';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'Rooms';

		// Set Page Specific CSS
		//$this->_view->pageCss = $pageCss;

		// Set Page Specific Javascript
		//$this->_view->pageJs = $pageJs;

		// Set default variables
		$this->_view->error = array();

        // Need to get items for select
        $this->_itemsModel = $this->loadModel('itemsBackoffice', 'backoffice');
        $this->_view->items = $this->_itemsModel->getAllData(false, false, 1);

        // If Form has been submitted process it
		if(!empty($_POST['save'])){
            $_POST['id'] = $id;

            // Update Rooms details
            $updateData = $this->_model->updateData($_POST);

            if(isset($updateData['error']) && $updateData['error'] != null){
                foreach($updateData['error'] as $key => $error){
                    $this->_view->error[$key] = $error;
                }
            } else {
                if(isset($_POST['items']) && !empty($_POST['items'])){
                    //Remove Previous Rooms Categories
                    $this->_model->deleteRoomItemsById($id);

                    foreach($_POST['items'] as $item){
                        $this->_model->createRoomItems($id, $item);
                    }
                }

                $this->_view->flash[] = "Rooms updated successfully.";
                Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                Url::redirect('backoffice/rooms/index');
            }
		}

		if(!empty($_POST['cancel'])){
			Url::redirect('backoffice/rooms/index');
		}

		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('rooms/add', 'layout', 'backoffice');
	}

    /**
     * PAGE: Rooms Delete
     * GET: /backoffice/rooms/delete/:id
     * This method handles the deletion of Rooms
     * @param string $id The unique id for the Rooms
     */
    public function delete($id){
        Auth::checkAdminLogin();
        // Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Rooms', 'Rooms');
		// Set Page Description
		$this->_view->pageDescription = 'Rooms Delete';
		// Set Page Section
		$this->_view->pageSection = 'Rooms';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'Rooms';

        //Check we got ID
        if(!empty($id)){
			$selectDataByID = $this->_model->selectDataByID($id);
            $this->_view->selectedData = $selectDataByID;

            //Check ID returns an Rooms
			if(isset($selectDataByID[0]['id']) && !empty($selectDataByID[0]['id'])){
                if(isset($_POST) && !empty($_POST)) {
                    if (!empty($_POST['delete'])) {
                        $deleteAttempt = $this->_model->deleteData($id);
                        //Check we have deleted Rooms
                        if (!empty($deleteAttempt)) {

                            // Redirect to next page
                            $this->_view->flash[] = "Rooms deleted successfully.";
                            Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                            Url::redirect('backoffice/rooms/');
                        } else {
                            $this->_view->error[] = 'A problem has occurred when trying to delete this award.';
                        }
                    } elseif (!empty($_POST['cancel'])) {
                        Url::redirect('backoffice/rooms/');
                    }
                }
			}else{
                $this->_view->flash[] = "No Rooms matches this id";
                Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
				Url::redirect('backoffice/rooms/');
			}
		}else{
            $this->_view->flash[] = "No ID provided for Rooms";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
			Url::redirect('backoffice/rooms/');
		}
        // Render the view ($renderBody, $layout, $area)
		$this->_view->render('rooms/delete', 'layout', 'backoffice');
    }


    /**
     * PAGE: Rooms Add
     * GET: /backoffice/rooms/add/:id
     * This method handles the adding of rooms
     */
	public function add(){
        Auth::checkAdminLogin();
		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Rooms', 'Rooms');
		// Set Page Description
		$this->_view->pageDescription = 'Rooms Add';
		// Set Page Section
		$this->_view->pageSection = 'Rooms';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'Rooms';

        $this->_view->error = array();

        // Need to get items for select
        $this->_itemsModel = $this->loadModel('itemsBackoffice', 'backoffice');
        $this->_view->items = $this->_itemsModel->getAllData(false, false, 1);

        // If Form has been submitted process it
		if(!empty($_POST)){
            //if user selected cancel
            if(!empty($_POST['cancel'])){
			    Url::redirect('backoffice/rooms/index');
		    }

            // Create new Rooms
            $createData = $this->_model->createData($_POST);
            if(isset($createData['error']) && $createData['error'] != null){
                foreach($createData['error'] as $key => $error){
                    $this->_view->error[$key] = $error;
                }
            }else{
                if(isset($_POST['items']) && !empty($_POST['items'])){
                    foreach($_POST['items'] as $item){
                        $this->_model->createRoomItems($createData, $item);
                    }
                }

                $this->_view->flash[] = "Rooms added successfully.";
                Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                Url::redirect('backoffice/rooms/index');
            }
		}
		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('rooms/add', 'layout', 'backoffice');
	}

    /**
     * PAGE: Rooms getRooms
     * GET: /backoffice/rooms/getRooms
     * This method handles getting rooms for admin 
     */
    public function getRooms(){
        if(!empty($_POST['current'])){
            // Fetch an array of all the regions
            $this->_view->current = $_POST['current'];
            $this->_view->rooms = $this->_model->getAllData();

            // Render the view ($renderBody, $layout, $area)
            $this->_view->renderPartial('rooms/_rooms', 'backoffice');
        }
    }

}
?>