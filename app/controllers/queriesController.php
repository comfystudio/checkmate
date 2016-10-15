<?php
/** Queries Controller */

class QueriesController extends BaseController {

    /** __construct */
    public function __construct(){
        parent::__construct();
        // Load the User Model ($modelName, $area)
        $this->_model = $this->loadModel('queries');
    }

    /**
     * PAGE: Queries create
     * GET: /queries/create
     * This method handles the view awards page
     */
    public function create(){
        // If Form has been submitted process it
        if(!empty($_POST)){
            //if user selected cancel
            if(!empty($_POST['cancel'])){
                Url::redirect('/');
            }

            // Create new Items
            $createData = $this->_model->createData($_POST);
            if(isset($createData['error']) && $createData['error'] != null){
                foreach($createData['error'] as $key => $error){
                    $this->_view->error[$key] = $error;
                    DEBUG::printr($error);

                }
            }else{
                $this->_view->flash[] = "Question added successfully.";
                Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                Url::redirect('/contact-us/');
            }
        }
    }


}
?>