<?php
/** ContactUs Controller */

class ContactUsController extends BaseController {

    /** __construct */
    public function __construct(){
        parent::__construct();
        // Load the User Model ($modelName, $area)
        $this->_model = $this->loadModel('contactUsBackoffice', 'backoffice');
    }

    /**
     * PAGE: Contacts Index
     * GET: /backoffice/contact-us/index
     * This method handles the view awards page
     */
    public function index(){
        Auth::checkAdminLogin();

        // Set the Page Title ('pageName', 'pageSection', 'areaName')
        $this->_view->pageTitle = array('Contact', 'Contact');
        // Set Page Description
        $this->_view->pageDescription = 'Contact Index';
        // Set Page Section
        $this->_view->pageSection = 'Pages';
        // Set Page Sub Section
        $this->_view->pageSubSection = 'Contact Us';

        ###### PAGINATION ######
        //sanitise or set keywords to false
        if(isset($_GET['keywords']) && !empty($_GET['keywords'])){
            $_GET['keywords'] = FormInput::checkKeywords($_GET['keywords']);
        }else{
            $_GET['keywords'] = false;
        }

        $totalItems = $this->_model->countAllData($_GET['keywords']);
        $pages = new Pagination(20,'keywords='.$_GET['keywords'].'&page', $totalItems[0]['total']);
        $this->_view->getAllData = $this->_model->getAllData($pages->get_limit(), $_GET['keywords']);
        $this->_view->countData = $this->_model->countAllData();

        // Create the pagination nav menu
        $this->_view->page_links = $pages->page_links();

        // Render the view ($renderBody, $layout, $area)
        $this->_view->render('contact-us/index', 'layout', 'backoffice');
    }

    /**
     * PAGE: Contacts Edit
     * GET: /backoffice/contact-us/edit:id
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
                $this->_view->flash[] = "No Contact matches this id";
                Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
                Url::redirect('backoffice/contact-us/index');
            }
        }else{
            $this->_view->flash[] = "No ID provided for Contact";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('backoffice/contact-us/index');
        }

        // Set the Page Title ('pageName', 'pageSection', 'areaName')
        $this->_view->pageTitle = array('Contact', 'Contact');
        // Set Page Description
        $this->_view->pageDescription = 'Contact Index';
        // Set Page Section
        $this->_view->pageSection = 'Pages';
        // Set Page Sub Section
        $this->_view->pageSubSection = 'Contact Us';

        $pageJs[] = (object) array('theFile' => 'contact-googlemap', 'theArea' => 'backoffice');
        $this->_view->pageJs = $pageJs;

        // Set default variables
        $this->_view->error = array();

        // If Form has been submitted process it
        if(!empty($_POST['save'])){
            $_POST['id'] = $id;

            if(!isset($_FILES) || $_FILES['image']['name'] == null) {
                $_POST['image'][0] = $this->_view->stored_data['image'];
            }else{
                //calls function that moves resourced documents
                $this->uploadFile($_FILES);
            }

            // Update Contact details
            $updateData = $this->_model->updateData($_POST);

            if(isset($updateData['error']) && $updateData['error'] != null){
                foreach($updateData['error'] as $key => $error){
                    $this->_view->error[$key] = $error;
                }
            } else {
                if (isset($_FILES) && $_FILES['image']['name'] != null) {
                    //remove old file
                    unlink(ROOT . UPLOAD_DIR . '/'. $this->_view->stored_data['image']);
                }

                $this->_view->flash[] = "Contact updated successfully.";
                Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                Url::redirect('backoffice/contact-us/index');
            }
        }

        if(!empty($_POST['cancel'])){
            Url::redirect('backoffice/contact-us/index');
        }

        // Render the view ($renderBody, $layout, $area)
        $this->_view->render('contact-us/add', 'layout', 'backoffice');
    }

    /**
     * PAGE: Contact Delete
     * GET: /backoffice/admin-users/delete/:id
     * This method handles the deletion of Contact
     * @param string $id The unique id for the Contact
     */
    public function delete($id){
        Auth::checkAdminLogin();
        // Set the Page Title ('pageName', 'pageSection', 'areaName')
        $this->_view->pageTitle = array('Contact', 'Contact');
        // Set Page Description
        $this->_view->pageDescription = 'Contact Index';
        // Set Page Section
        $this->_view->pageSection = 'Pages';
        // Set Page Sub Section
        $this->_view->pageSubSection = 'Contact Us';

        //Check we got ID
        if(!empty($id)){
            $selectDataByID = $this->_model->selectDataByID($id);
            $this->_view->selectedData = $selectDataByID;

            //Check ID returns an Contact
            if(isset($selectDataByID[0]['id']) && !empty($selectDataByID[0]['id'])){
                if(isset($_POST) && !empty($_POST)) {
                    if (!empty($_POST['delete'])) {
                        $deleteAttempt = $this->_model->deleteData($id);
                        //Check we have deleted Contact
                        if (!empty($deleteAttempt)) {
                            unlink(ROOT.UPLOAD_DIR . '/'. $selectDataByID[0]['image']);


                            // Redirect to next page
                            $this->_view->flash[] = "Contact deleted successfully.";
                            Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                            Url::redirect('backoffice/contact-us/index');
                        } else {
                            $this->_view->error[] = 'A problem has occurred when trying to delete this award.';
                        }
                    } elseif (!empty($_POST['cancel'])) {
                        Url::redirect('backoffice/contact-us/index');
                    }
                }
            }else{
                $this->_view->flash[] = "No Contact matches this id";
                Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
                Url::redirect('backoffice/contact-us/index');
            }
        }else{
            $this->_view->flash[] = "No ID provided for Contact";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('backoffice/contact-us/index');
        }
        // Render the view ($renderBody, $layout, $area)
        $this->_view->render('contact-us/delete', 'layout', 'backoffice');
    }


    /**
     * PAGE: Contact Add
     * GET: /backoffice/contact-us/add/:id
     * This method handles the adding of contact-us
     */
    public function add(){
        Auth::checkAdminLogin();
        // Set the Page Title ('pageName', 'pageSection', 'areaName')
        $this->_view->pageTitle = array('Contact', 'Contact');
        // Set Page Description
        $this->_view->pageDescription = 'Contact Index';
        // Set Page Section
        $this->_view->pageSection = 'Pages';
        // Set Page Sub Section
        $this->_view->pageSubSection = 'Contact Us';

        $pageJs[] = (object) array('theFile' => 'contact-googlemap', 'theArea' => 'backoffice');
        $this->_view->pageJs = $pageJs;

        $this->_view->error = array();

        // If we already have one data then bounce them back to index
        $this->_view->countData = $this->_model->countAllData();
        if($this->_view->countData[0]['total'] != 0){
            Url::redirect('backoffice/contact-us/index');
        }

        // If Form has been submitted process it
        if(!empty($_POST)){
            //if user selected cancel
            if(!empty($_POST['cancel'])){
                Url::redirect('backoffice/contact-us/index');
            }

            if(!isset($_FILES) || empty($_FILES['image']['name'])){
                $_POST['image'] = null;
            }else{
                $this->uploadFile($_FILES);
            }

            // Create new Contact
            $createData = $this->_model->createData($_POST);
            if(isset($createData['error']) && $createData['error'] != null){
                foreach($createData['error'] as $key => $error){
                    $this->_view->error[$key] = $error;
                }
            }else{

                $this->_view->flash[] = "Contact added successfully.";
                Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                Url::redirect('backoffice/contact-us/index');
            }
        }
        // Render the view ($renderBody, $layout, $area)
        $this->_view->render('contact-us/add', 'layout', 'backoffice');
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