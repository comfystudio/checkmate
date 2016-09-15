<?php
/** Properties Controller */

class PropertiesController extends BaseController {

	/** __construct */
	public function __construct(){
		parent::__construct();
		// Load the User Model ($modelName, $area)
		$this->_model = $this->loadModel('propertiesBackoffice', 'backoffice');
	}

    /**
	 * PAGE: Properties Index
	 * GET: /backoffice/properties/index
	 * This method handles the view awards page
	 */
	public function index(){
        Auth::checkAdminLogin();

		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Properties');
		// Set Page Description
		$this->_view->pageDescription = 'Properties Index';
		// Set Page Section
		$this->_view->pageSection = 'Properties';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'Properties';

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
		$this->_view->render('properties/index', 'layout', 'backoffice');
	}


    /**
     * PAGE: Properties View
     * GET: /backoffice/properties/view:id
     * This method handles the view properties page
     * @param int $id
     */
    public function view($id){
        if(!empty($id)){
            // Set the Page Title ('pageName', 'pageSection', 'areaName')
            $this->_view->pageTitle = array('Properties');
            // Set Page Description
            $this->_view->pageDescription = 'View Properties';
            // Set Page Section
            $this->_view->pageSection = 'Properties';
            // Set Page Sub Section
            $this->_view->pageSubSection = 'Properties';
            
            // Fetch array of messages for this conversation
            $this->_view->getAllData = $this->_model->selectDataByID($id);

            //Debug::printr($this->_view->getAllData);die;

            //We need to get our rooms and then items
            if(isset($this->_view->getAllData[0]['room_ids']) && !empty($this->_view->getAllData[0]['room_ids'])){
                $this->_roomsModel = $this->loadModel('roomsBackoffice', 'backoffice');
                $this->_itemsModel = $this->loadModel('itemsBackoffice', 'backoffice');

                $room_ids = explode(', ', $this->_view->getAllData[0]['room_ids']);

                foreach($room_ids as $key => $room){
                    $rooms = $this->_roomsModel->selectDataByID($room);
                    $rooms = $rooms[0];
                    if(isset($rooms['items']) && !empty($rooms['items'])){
                        $item_ids = explode(',', $rooms['items']);
                        foreach ($item_ids as $key2 => $item) {
                            $items = $this->_itemsModel->selectDataByID($item);
                            $items = $items[0];
                            $rooms['items_array'][] = $items;
                        }
                    }
                    $this->_view->getAllData[0]['rooms'][] = $rooms;
                }
            }    
            // Render the view ($renderBody, $layout, $area)
            $this->_view->render('properties/view', 'layout', 'backoffice');
        } else {
            Url::redirect('backoffice/properties/');
        }
    }

    /**
     * PAGE: Properties Delete
     * GET: /backoffice/properties/delete/:id
     * This method handles the deletion of Properties
     * @param string $id The unique id for the Properties
     */
    public function delete($id){
        Auth::checkAdminLogin();
        // Set the Page Title ('pageName', 'pageSection', 'areaName')
        $this->_view->pageTitle = array('Properties');
        // Set Page Description
        $this->_view->pageDescription = 'View Properties';
        // Set Page Section
        $this->_view->pageSection = 'Properties';
        // Set Page Sub Section
        $this->_view->pageSubSection = 'Properties';

        //Check we got ID
        if(!empty($id)){
            $selectDataByID = $this->_model->selectDataByID($id);
            $this->_view->selectedData = $selectDataByID;

            // We need to work out if its safe to delete this user.
            $this->_reportsModel = $this->loadModel('reportsBackoffice', 'backoffice');
            $currentDate = date("Y-m-d");
            $this->_view->conflict = $this->_reportsModel->getReportsByPropertyIdAndDate($id, $currentDate);

            //Check ID returns an Properties
            if(isset($selectDataByID[0]['id']) && !empty($selectDataByID[0]['id'])){
                if(isset($_POST) && !empty($_POST)) {
                    if (!empty($_POST['delete'])) {
                        $deleteAttempt = $this->_model->deleteData($id);
                        //Check we have deleted Properties
                        if (!empty($deleteAttempt)) {

                            // Redirect to next page
                            $this->_view->flash[] = "Properties deleted successfully.";
                            Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                            Url::redirect('backoffice/properties/index');
                        } else {
                            $this->_view->error[] = 'A problem has occurred when trying to delete this award.';
                        }
                    } elseif (!empty($_POST['cancel'])) {
                        Url::redirect('backoffice/properties/index');
                    }
                }
            }else{
                $this->_view->flash[] = "No Properties matches this id";
                Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
                Url::redirect('backoffice/properties/index');
            }
        }else{
            $this->_view->flash[] = "No ID provided for Properties";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('backoffice/properties/index');
        }
        // Render the view ($renderBody, $layout, $area)
        $this->_view->render('properties/delete', 'layout', 'backoffice');
    }

    /**
     * PAGE: Properties Edit
     * GET: /backoffice/properties/edit:id
     * @param string $id The unique id for the award user
     * This method handles the edit properties page
     */
    public function edit($id = false){
        Auth::checkAdminLogin();
        if(!empty($id)){
            $selectDataByID = $this->_model->selectDataByID($id);
            if(isset($selectDataByID[0]['id']) && !empty($selectDataByID[0]['id'])){
                $this->_view->stored_data = $selectDataByID[0];

            }else{
                $this->_view->flash[] = "No Properties matches this id";
                Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
                Url::redirect('backoffice/properties/index');
            }
        }else{
            $this->_view->flash[] = "No ID provided for Properties";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('backoffice/properties/index');
        }

        // Set the Page Title ('pageName', 'pageSection', 'areaName')
        $this->_view->pageTitle = array('Properties', 'Properties');
        // Set Page Description
        $this->_view->pageDescription = 'Properties Edit';
        // Set Page Section
        $this->_view->pageSection = 'Properties';
        // Set Page Sub Section
        $this->_view->pageSubSection = 'Properties';

        // Set default variables
        $this->_view->error = array();

        // Need to get templates for selection
        $this->_templatesModel = $this->loadModel('templatesBackoffice', 'backoffice');
        $this->_view->templates = $this->_templatesModel->getAllData();

        // If Form has been submitted process it
        if(!empty($_POST['save'])){
            $_POST['id'] = $id;

            if(!isset($_FILES) || $_FILES['image']['name'] == null) {
                $_POST['image'][0] = $this->_view->stored_data['image'];
            }else{
                //calls function that moves resourced documents
                $this->uploadFile($_FILES);
            }

            // Update Properties details
            $updateData = $this->_model->updateData($_POST);

            if(isset($updateData['error']) && $updateData['error'] != null){
                foreach($updateData['error'] as $key => $error){
                    $this->_view->error[$key] = $error;
                }
            } else {
                if (isset($_FILES) && $_FILES['image']['name'] != null) {
                    //remove old file
                    unlink(ROOT . UPLOAD_DIR . '/' . $this->_view->stored_data['image']);
                }

                //We need to remove property_templates and add new ones
                $this->_model->deletePropertyTemplatesByPropertyId($id);
                if(isset($_POST['template_id']) && !empty($_POST['template_id'])){
                    $this->_model->createPropertyTemplates($id, $_POST['template_id']);
                }

                // Need to create notification for property owner
                $this->_notificationModel = $this->loadModel('notificationsBackoffice', 'backoffice');
                $notificationData['user_id'] = $selectDataByID[0]['created_by'];
                $notificationData['text'] = 'Administrator has made changes to your property please review at the following link. <a href = "'.SITE_URL.'properties/edit/'.$selectDataByID[0]['id'].'">Link</a>';
                $this->_notificationModel->createData($notificationData);

                $this->_view->data = $selectDataByID[0];
                $this->_view->data['message'] = 'Administrator has made changes to your property please review at the following link.';

                // Need to create email for property owner
                $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
                Html::sendEmail('will_byrne56@hotmail.com', 'Property Updated by Administrator', SITE_EMAIL, $message);
                Html::sendEmail('william@websiteni.com', 'Property Updated by Administrator', SITE_EMAIL, $message);


                $this->_view->flash[] = "Properties updated successfully.";
                Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                Url::redirect('backoffice/properties/index');
            }
        }

        if(!empty($_POST['cancel'])){
            Url::redirect('backoffice/properties/index');
        }

        // Render the view ($renderBody, $layout, $area)
        $this->_view->render('properties/add', 'layout', 'backoffice');
    }

    /**
     * PAGE: properties image download
     * GET: /backoffice/properties/download/:id/
     * This method handles the download image action.
     */
    public function download($id){
        if(!empty($id)) {
            $selectedData = $this->_model->selectDataByID($id);
            if (isset($selectedData) && !empty($selectedData)) {
                header('Content-Description: File Transfer');
                header('Content-Disposition: attachment; filename="'.basename(ROOT.UPLOAD_DIR.$selectedData[0]['image']).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Content-Transfer-Encoding: binary');
                header('Pragma: public');
                header('Content-Length: ' . filesize(ROOT.'assets/uploads/'.$selectedData[0]['image']));
                readfile(ROOT.'assets/uploads/'.$selectedData[0]['image']);
                exit;
            } else {
                $this->_view->flash[] = "No data matches this ID";
                Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
                Url::redirect('backoffice/properties/');
            }
        }else{
            $this->_view->flash[] = "No ID was provided";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('backoffice/properties/');
        }
    }

    /**
     * UploadFile
     * This method handles the upload and moving of docs on backoffice
     * @param array $files is the $_FILES
     */
    public function uploadFile($files){
        require_once(ROOT.'system/helpers/Upload.php');
        // upload file
        try {
            if(isset($files['image'])){
                $file = new Ps2_Upload(ROOT.UPLOAD_DIR.'/', 'image', true);
                $file->addPermittedTypes(array(
                        'image/png', 'image/jpeg', 'image/gif',
                    )
                );
                $file->setMaxSize(MAX_FILE_SIZE);
                $file->move();
                $_POST['image'] = $file->getFilenames();

                return $this->_view->error = array_merge($this->_view->error, $file->getMessages());
            }
        } catch (Exception $e) {
            return $this->_view->error[] = $e->getMessage();
        }
    }
}
?>