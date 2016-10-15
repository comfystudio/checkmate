<?php
/** News Controller */

class NewsController extends BaseController {

	/** __construct */
	public function __construct(){
		parent::__construct();
		// Load the User Model ($modelName, $area)
		$this->_model = $this->loadModel('newsBackoffice', 'backoffice');
	}

    /**
	 * PAGE: News Index
	 * GET: /backoffice/news/index
	 * This method handles the view awards page
	 */
	public function index(){
        Auth::checkAdminLogin();

		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('News', 'News');
		// Set Page Description
		$this->_view->pageDescription = 'News Index';
		// Set Page Section
        $this->_view->pageSection = 'Pages';
        // Set Page Sub Section
        $this->_view->pageSubSection = 'News';

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
		$this->_view->render('news/index', 'layout', 'backoffice');
	}

    /**
	 * PAGE: News Edit
	 * GET: /backoffice/news/edit:id
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
                $this->_view->flash[] = "No News matches this id";
                Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
				Url::redirect('backoffice/news/index');
			}
		}else{
            $this->_view->flash[] = "No ID provided for News";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
			Url::redirect('backoffice/news/index');
		}

		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('News', 'News');
		// Set Page Description
		$this->_view->pageDescription = 'News Edit';
		// Set Page Section
		$this->_view->pageSection = 'Pages';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'News';

		// Set Page Specific CSS
		//$this->_view->pageCss = $pageCss;

		// Set Page Specific Javascript
		//$this->_view->pageJs = $pageJs;

		// Set default variables
		$this->_view->error = array();
      
        // If Form has been submitted process it
		if(!empty($_POST['save'])){
            $_POST['id'] = $id;
            $_POST['stored_title'] = $selectDataByID[0]['title'];


            if(!isset($_FILES) || $_FILES['image']['name'] == null) {
                $_POST['image'][0] = $this->_view->stored_data['image'];
            }else{
                //calls function that moves resourced documents
                $this->uploadFile($_FILES);
            }

            $_POST['text_clean'] = $_POST['text'];

            // Update News details
            $updateData = $this->_model->updateData($_POST);

            if(isset($updateData['error']) && $updateData['error'] != null){
                foreach($updateData['error'] as $key => $error){
                    $this->_view->error[$key] = $error;
                }
            } else {
                if (isset($_FILES) && $_FILES['image']['name'] != null) {
                    //remove old file
                    unlink(ROOT . UPLOAD_DIR . $this->_view->stored_data['image']);
                }

                $this->_view->flash[] = "News updated successfully.";
                Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                Url::redirect('backoffice/news/index');
            }
		}

		if(!empty($_POST['cancel'])){
			Url::redirect('backoffice/news/index');
		}

		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('news/add', 'layout', 'backoffice');
	}

    /**
     * PAGE: News Delete
     * GET: /backoffice/admin-users/delete/:id
     * This method handles the deletion of News
     * @param string $id The unique id for the News
     */
    public function delete($id){
        Auth::checkAdminLogin();
        // Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('News', 'News');
		// Set Page Description
		$this->_view->pageDescription = 'News Delete';
		// Set Page Section
        $this->_view->pageSection = 'Pages';
        // Set Page Sub Section
        $this->_view->pageSubSection = 'News';

        //Check we got ID
        if(!empty($id)){
			$selectDataByID = $this->_model->selectDataByID($id);
            $this->_view->selectedData = $selectDataByID;

            //Check ID returns an News
			if(isset($selectDataByID[0]['id']) && !empty($selectDataByID[0]['id'])){
                if(isset($_POST) && !empty($_POST)) {
                    if (!empty($_POST['delete'])) {
                        $deleteAttempt = $this->_model->deleteData($id);
                        //Check we have deleted News
                        if (!empty($deleteAttempt)) {
                            unlink(ROOT.UPLOAD_DIR.$selectDataByID[0]['image']);

                            // Redirect to next page
                            $this->_view->flash[] = "News deleted successfully.";
                            Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                            Url::redirect('backoffice/news/');
                        } else {
                            $this->_view->error[] = 'A problem has occurred when trying to delete this award.';
                        }
                    } elseif (!empty($_POST['cancel'])) {
                        Url::redirect('backoffice/news/');
                    }
                }
			}else{
                $this->_view->flash[] = "No News matches this id";
                Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
				Url::redirect('backoffice/news/');
			}
		}else{
            $this->_view->flash[] = "No ID provided for News";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
			Url::redirect('backoffice/news/');
		}
        // Render the view ($renderBody, $layout, $area)
		$this->_view->render('news/delete', 'layout', 'backoffice');
    }


    /**
     * PAGE: News Add
     * GET: /backoffice/news/add/:id
     * This method handles the adding of news
     */
	public function add(){
        Auth::checkAdminLogin();
		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('News', 'News');
		// Set Page Description
		$this->_view->pageDescription = 'News Add';
		// Set Page Section
        $this->_view->pageSection = 'Pages';
        // Set Page Sub Section
        $this->_view->pageSubSection = 'News';

        $this->_view->error = array();

        // Need to get Categories for select
        // $this->_categoriesModel = $this->loadModel('categoriesBackoffice', 'backoffice');
        // $this->_view->categories = $this->_categoriesModel->getAllData(false, false, 1);

        // If Form has been submitted process it
		if(!empty($_POST)){
            //if user selected cancel
            if(!empty($_POST['cancel'])){
			    Url::redirect('backoffice/news/index');
		    }

            if(!isset($_FILES) || empty($_FILES['image']['name'])){
                $_POST['image'] = null;
            }else{
                $this->uploadFile($_FILES);
            }

            $_POST['text_clean'] = $_POST['text'];
            // Create new News
            $createData = $this->_model->createData($_POST);
            if(isset($createData['error']) && $createData['error'] != null){
                foreach($createData['error'] as $key => $error){
                    $this->_view->error[$key] = $error;
                }
            }else{

                $this->_view->flash[] = "News added successfully.";
                Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                Url::redirect('backoffice/news/index');
            }
		}
		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('news/add', 'layout', 'backoffice');
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

    /**
	 * PAGE: news-items image download
	 * GET: /backoffice/news-items/download/:id/
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
                Url::redirect('backoffice/news/');
            }
        }else{
            $this->_view->flash[] = "No ID was provided";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('backoffice/news/');
        }
    }
}
?>