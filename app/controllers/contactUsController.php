<?php
/** ContactUs Controller */
class ContactUsController extends BaseController {
    /** __construct */
    public function __construct(){
        parent::__construct();

        $this->_model = $this->loadModel('contactUs');
    }

    /**
     * PAGE: index
     * GET: /contact-us/index
     * This method handles the sites index ContactUs page
     */
    public function index(){
        $this->_queriesModel = $this->loadModel('queries');

        $this->_view->data = $this->_model->getAllData();

        if(!isset($this->_view->data) || empty($this->_view->data)){
            Url::redirect('users/index');
        }

        // Set the Page Title ('pageName', 'pageSection', 'areaName')
        $this->_view->pageTitle = array('ContactUs');
        // Set Page Description
        $this->_view->pageDescription = 'Checkmate Contact Us';
        // Set Page Section
        $this->_view->pageSection = 'ContactUs';
        // Set Page Sub Section
        $this->_view->pageSubSection = '';

        // If Form has been submitted process it
        if(!empty($_POST)){
            //if user selected cancel
            if(!empty($_POST['cancel'])){
                Url::redirect('/');
            }

            // Create new Items
            $createData = $this->_queriesModel->createData($_POST);
            if(isset($createData['error']) && $createData['error'] != null){
                foreach($createData['error'] as $key => $error){
                    $this->_view->error[$key] = $error;
                }
            }else{
                $this->_view->flash[] = "Question added successfully.";
                Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                Url::redirect('contact-us/');
            }
        }


        // Render the view ($renderBody, $layout, $area)
        $this->_view->render('contact-us/index');
    }

}
?>