<?php
/** Templates Controller */

class TemplatesController extends BaseController {

	/** __construct */
	public function __construct(){
		parent::__construct();
		// Load the User Model ($modelName, $area)
		$this->_model = $this->loadModel('templatesBackoffice', 'backoffice');
	}

    /**
	 * PAGE: Templates Index
	 * GET: /backoffice/templates/index
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
		$this->_view->render('templates/index', 'layout', 'backoffice');
	}

    /**
	 * PAGE: Templates Edit
	 * GET: /backoffice/templates/edit:id
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
				Url::redirect('backoffice/templates/index');
			}
		}else{
            $this->_view->flash[] = "No ID provided for Templates";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
			Url::redirect('backoffice/templates/index');
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
                Url::redirect('backoffice/templates/index');
            }
		}

		if(!empty($_POST['cancel'])){
			Url::redirect('backoffice/templates/index');
		}

		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('templates/add', 'layout', 'backoffice');
	}

    /**
     * PAGE: Templates Delete
     * GET: /backoffice/admin-users/delete/:id
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
                            Url::redirect('backoffice/templates/index');
                        } else {
                            $this->_view->error[] = 'A problem has occurred when trying to delete this award.';
                        }
                    } elseif (!empty($_POST['cancel'])) {
                        Url::redirect('backoffice/templates/index');
                    }
                }
			}else{
                $this->_view->flash[] = "No Templates matches this id";
                Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
				Url::redirect('backoffice/templates/index');
			}
		}else{
            $this->_view->flash[] = "No ID provided for Templates";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
			Url::redirect('backoffice/templates/index');
		}
        // Render the view ($renderBody, $layout, $area)
		$this->_view->render('templates/delete', 'layout', 'backoffice');
    }


    /**
     * PAGE: Templates Add
     * GET: /backoffice/templates/add/:id
     * This method handles the adding of templates
     */
	public function add(){
        Auth::checkAdminLogin();
		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Template', 'Template');
		// Set Page Description
		$this->_view->pageDescription = 'Template Add';
		// Set Page Section
		$this->_view->pageSection = 'Rooms';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'Templates';

        $this->_view->error = array();

		$this->_roomsModel = $this->loadModel('roomsBackoffice', 'backoffice');
		$this->_view->rooms = $this->_roomsModel->getAllData();

        // If Form has been submitted process it
		if(!empty($_POST)){
            //if user selected cancel
            if(!empty($_POST['cancel'])){
			    Url::redirect('backoffice/templates/index');
		    }

            // Create new Templates
            $createData = $this->_model->createData($_POST);
            if(isset($createData['error']) && $createData['error'] != null){
                foreach($createData['error'] as $key => $error){
                    $this->_view->error[$key] = $error;
                }
            }else{
        		// We need to populate template_rooms
        		if(isset($_POST['rooms']) && !empty($_POST['rooms'])){
    				foreach($_POST['rooms'] as $key => $room){
    					$this->_model->createTemplateRoom($createData, $room);
    				}
        		}

                $this->_view->flash[] = "Template added successfully.";
                Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                Url::redirect('backoffice/templates/index');
            }
		}
		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('templates/add', 'layout', 'backoffice');
	}

}
?>