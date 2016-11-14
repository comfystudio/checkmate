<?php
/** Templates Controller */

class TemplatesController extends BaseController {

	/** __construct */
	public function __construct(){
		parent::__construct();
		// Load the User Model ($modelName, $area)
		$this->_model = $this->loadModel('templates');
	}

    /**
	 * PAGE: Templates Index
	 * GET: templates/index
	 * This method handles the view awards page
	 */
	public function index(){
        Auth::checkAdminLogin();

		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Template');
		// Set Page Description
		$this->_view->pageDescription = 'Template Index';
		// Set Page Section
		$this->_view->pageSection = 'Rooms';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'Templates';

		###### PAGINATION ######
        //sanitise or set keywords to false
        if(isset($_GET['keywords']) && !empty($_GET['keywords'])){
            $_GET['keywords'] = FormInput::checkKeywords($_GET['keywords']);
        }else{
            $_GET['keywords'] = false;
        }

        $totalTemplates = $this->_model->countAllData($_GET['keywords']);
        if(!isset($totalItems) || empty($totalItems)){
            $totalItems = 0;
        }
        $pages = new Pagination(20,'keywords='.$_GET['keywords'].'&page', $totalTemplates[0]['total']);
        $this->_view->getAllData = $this->_model->getAllData($pages->get_limit(), $_GET['keywords']);

		// Create the pagination nav menu
		$this->_view->page_links = $pages->page_links();

		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('templates/index', 'layout');
	}

    /**
	 * PAGE: Templates Edit
	 * GET: templates/edit:id
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
                $this->_view->flash[] = "No Templates matches this id";
                Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
				Url::redirect('templates/index');
			}
		}else{
            $this->_view->flash[] = "No ID provided for Templates";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
			Url::redirect('templates/index');
		}

		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Template', 'Template');
		// Set Page Description
		$this->_view->pageDescription = 'Template edit';
		// Set Page Section
		$this->_view->pageSection = 'Rooms';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'Templates';

		// Set default variables
		$this->_view->error = array();

		$this->_roomsModel = $this->loadModel('roomsBackoffice', 'backoffice');
		$this->_view->rooms = $this->_roomsModel->getAllData();
        
		foreach($this->_view->rooms as $key => $room){
			$rooms[$room['id']] = $room;
		}
		$this->_view->rooms = $rooms;

        // If Form has been submitted process it
		if(!empty($_POST['save'])){
            $_POST['id'] = $id;

            // Update Templates details
            $updateData = $this->_model->updateData($_POST);

            if(isset($updateData['error']) && $updateData['error'] != null){
                foreach($updateData['error'] as $key => $error){
                    $this->_view->error[$key] = $error;
                }
            } else {
            	// We need to remove old template_rooms and create new ones
            	$this->_model->deleteTemplateRoomsByTemplateId($id);

            	// We need to populate template_rooms
        		if(isset($_POST['rooms']) && !empty($_POST['rooms'])){
    				foreach($_POST['rooms'] as $key => $room){
    					$this->_model->createTemplateRoom($id, $room);
    				}
        		}
                $this->_view->flash[] = "Templates updated successfully.";
                Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                Url::redirect('templates/index');
            }
		}

		if(!empty($_POST['cancel'])){
			Url::redirect('templates/index');
		}

		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('templates/add', 'layout');
	}

    /**
     * PAGE: Templates Delete
     * GET: templates/delete/:id
     * This method handles the deletion of Templates
     * @param string $id The unique id for the Templates
     */
    public function delete($id){
        Auth::checkAdminLogin();
        // Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Template', 'Template');
		// Set Page Description
		$this->_view->pageDescription = 'Template Delete';
		// Set Page Section
		$this->_view->pageSection = 'Rooms';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'Templates';

        //Check we got ID
        if(!empty($id)){
			$selectDataByID = $this->_model->selectDataByID($id);
            $this->_view->selectedData = $selectDataByID;

            //Check ID returns an Templates
			if(isset($selectDataByID[0]['id']) && !empty($selectDataByID[0]['id'])){
                if(isset($_POST) && !empty($_POST)) {
                    if (!empty($_POST['delete'])) {
                        $deleteAttempt = $this->_model->deleteData($id);
                        //Check we have deleted Templates
                        if (!empty($deleteAttempt)) {

                            // Redirect to next page
                            $this->_view->flash[] = "Templates deleted successfully.";
                            Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                            Url::redirect('templates/index');
                        } else {
                            $this->_view->error[] = 'A problem has occurred when trying to delete this award.';
                        }
                    } elseif (!empty($_POST['cancel'])) {
                        Url::redirect('templates/index');
                    }
                }
			}else{
                $this->_view->flash[] = "No Templates matches this id";
                Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
				Url::redirect('templates/index');
			}
		}else{
            $this->_view->flash[] = "No ID provided for Templates";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
			Url::redirect('templates/index');
		}
        // Render the view ($renderBody, $layout, $area)
		$this->_view->render('templates/delete', 'layout');
    }


    /**
     * PAGE: Templates Add
     * GET: templates/add/:id
     * This method handles the adding of templates
     */
	public function add(){
        Auth::checkUserLogin();
		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Template', 'Template');
		// Set Page Description
		$this->_view->pageDescription = 'Checkmate Template Add';
		// Set Page Section
		$this->_view->pageSection = 'Rooms';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'Templates';

        $this->_view->error = array();

		$this->_roomsModel = $this->loadModel('rooms');
        $this->_itemsModel = $this->loadModel('items');
		$this->_view->rooms = $this->_roomsModel->getAllData();

        // If Form has been submitted process it
		if(!empty($_POST)){
            //if user selected cancel
            if(!empty($_POST['cancel'])){
			    Url::redirect('users/dashboard');
		    }

            $_POST['created_by'] = $_SESSION['UserCurrentUserID'];

            // Create new Templates
            $createData = $this->_model->createData($_POST);
            if(isset($createData['error']) && $createData['error'] != null){
                foreach($createData['error'] as $key => $error){
                    $this->_view->error[$key] = $error;
                }
            }else{
                if(isset($_POST['items']) && !empty($_POST['items'])){
                    foreach($_POST['items'] as $key => $item){
                        if(isset($item) && !empty($item)){
                            $itemArray = array();
                            foreach ($item as $key2 => $itemName) {
                                // Need to create new item.
                                $data['name'] = $itemName;
                                $data['is_active'] = 1;
                                $itemArray[] = $this->_itemsModel->createData($data);
                            }
                        }
                        if(isset($itemArray) && !empty($itemArray)){
                            if(isset($_POST['rooms'][$key]) && !empty($_POST['rooms'][$key])){
                                $room = $this->_roomsModel->selectDataByID($_POST['rooms'][$key]);
                                $roomData['name'] = $room[0]['name'];
                                $roomData['is_active'] = 1;

                                // Creating new room
                                $roomID = $this->_roomsModel->createData($roomData);
                                $roomIDs[$key] = $roomID;
                                // Need to create room_items for new rooms / items
                                $itemIds = explode(',', $room[0]['items']);
                                foreach ($itemArray as $key3 => $id) {
                                    $itemIds[] = $id;
                                }
                                if(isset($itemIds) && !empty($itemIds) && is_array($itemIds)){
                                    foreach ($itemIds as $key4 => $itemid) {
                                        // creating new room_item
                                        $this->_roomsModel->createRoomItems($roomID, $itemid);
                                    }
                                }
                            }
                        }
                    }
                }

                foreach($_POST['rooms'] as $key => $room){
                    // creating template_rooms
                    if(isset($_POST['items'][$key]) && !empty($_POST['items'][$key])){
                        $this->_model->createTemplateRoom($createData, $roomIDs[$key]);
                    }else{
                        $this->_model->createTemplateRoom($createData, $room);
                    }
                }


                $this->_view->flash[] = "Template added successfully.";
                Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                Url::redirect('users/dashboard');
            }
		}
		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('templates/add', 'layout');
	}

    /**
     * PAGE: Templates getRooms
     * GET: /templates/getRooms
     * This method handles getting rooms for admin 
     */
    public function getRooms(){
        if(!empty($_POST['current'])){
            // Fetch an array of all the regions
            $this->_view->current = $_POST['current'];
            $this->_roomsModel = $this->loadModel('rooms');
            $this->_view->rooms = $this->_roomsModel->getAllData();

            // Render the view ($renderBody, $layout, $area)
            $this->_view->renderPartial('templates/_rooms');
        }
    }

}
?>