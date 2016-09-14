<?php
/** Users Controller */
class AdminUsersController extends BaseController {

	/** __construct */
	public function __construct(){
		parent::__construct();
		// Load the User Model ($modelName, $area)
		$this->_model = $this->loadModel('adminUsersBackoffice', 'backoffice');
	}


    /**
	 * PAGE: Admin_index
	 * GET: /backoffice/admin-users/index
	 * This method handles the view admin users page
	 */
	public function index(){
        Auth::checkAdminLogin();

		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('View Admin Users', 'Users');
		// Set Page Description
		$this->_view->pageDescription = 'Admin Users';
		// Set Page Section
		$this->_view->pageSection = 'Admin Users';

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
		$this->_view->render('admin-users/index', 'layout', 'backoffice');
	}

    /**
	 * PAGE: Admin Edit
	 * GET: /backoffice/admin-users/edit:id
     * @param string $id The unique id for the admin user
	 * This method handles the edit admin user page
	 */
	public function edit($id = false){
        Auth::checkAdminLogin();
		if(!empty($id)){
			$selectDataByID = $this->_model->selectDataByID($id);
            if(isset($selectDataByID[0]['id']) && !empty($selectDataByID[0]['id'])){
                $this->_view->stored_data = $selectDataByID[0];
			}else{
                $this->_view->flash[] = "No Admin users matches this id";
                Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
				Url::redirect('backoffice/admin-users/index');
			}
		}else{
            $this->_view->flash[] = "No ID provided for Admin user";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
			Url::redirect('backoffice/admin-users/index');
		}

		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Edit Admin User', 'Users');
		// Set Page Description
		$this->_view->pageDescription = '';
		// Set Page Section
		$this->_view->pageSection = 'Admin Users';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'Edit Admin User';

		// Set Page Specific CSS
		//$this->_view->pageCss = $pageCss;

		// Set Page Specific Javascript
		//$this->_view->pageJs = $pageJs;

		// Set default variables
		$this->_view->error = array();

        // If Form has been submitted process it
		if(!empty($_POST['save'])){
            $_POST['id'] = $id;
            $_POST['salt'] = $selectDataByID[0]['salt'];
            $_POST['user_pass'] = $selectDataByID[0]['user_pass'];
            $_POST['stored_user_email'] = $selectDataByID[0]['user_email'];

            // Update user details
            $updateData = $this->_model->updateData($_POST);

            if(isset($updateData['error']) && $updateData['error'] != null){
                foreach($updateData['error'] as $key => $error){
                    $this->_view->error[$key] = $error;
                }
            } else {
                $this->_view->flash[] = "Admin user updated successfully.";
                Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                Url::redirect('backoffice/admin-users/index');
            }
		}

		if(!empty($_POST['cancel'])){
			Url::redirect('backoffice/admin-users/index');
		}

		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('admin-users/add', 'layout', 'backoffice');
	}

    /**
     * PAGE: Admin Delete
     * GET: /backoffice/admin-users/delete/:id
     * This method handles the deletion of admin users
     * @param string $id The unique id for the admin user
     */
    public function delete($id){
        Auth::checkAdminLogin();
        // Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Delete Admin User', 'Users');
		// Set Page Description
		$this->_view->pageDescription = '';
		// Set Page Section
		$this->_view->pageSection = 'Admin Users';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'Delete Admin User';

        //Check we got ID
        if(!empty($id)){
			$selectDataByID = $this->_model->selectDataByID($id);
            $this->_view->selectedData = $selectDataByID;

            //Check ID returns an admin user
			if(isset($selectDataByID[0]['id']) && !empty($selectDataByID[0]['id'])){
                if(isset($_POST) && !empty($_POST)) {
                    if (!empty($_POST['delete'])) {
                        $deleteAttempt = $this->_model->deleteData($id);
                        //Check we have deleted admin user
                        if (!empty($deleteAttempt)) {
                            // Redirect to next page
                            $this->_view->flash[] = "Admin user deleted successfully.";
                            Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                            Url::redirect('backoffice/admin-users/index');
                        } else {
                            $this->_view->error[] = 'A problem has occurred when trying to delete this admin user.';
                        }
                    } elseif (!empty($_POST['cancel'])) {
                        Url::redirect('backoffice/admin-users/index');
                    }
                }
			}else{
                $this->_view->flash[] = "No Admin users matches this id";
                Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
				Url::redirect('backoffice/admin-users/index');
			}
		}else{
            $this->_view->flash[] = "No ID provided for Admin user";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
			Url::redirect('backoffice/admin-users/index');
		}
        // Render the view ($renderBody, $layout, $area)
		$this->_view->render('admin-users/delete', 'layout', 'backoffice');
    }


	public function add(){
        Auth::checkAdminLogin();
		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Add', 'Users');
		// Set Page Description
		$this->_view->pageDescription = '';
		// Set Page Section
		$this->_view->pageSection = 'Admin Users';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'Add';

        $this->_view->error = array();

        // If Form has been submitted process it
		if(!empty($_POST)){
            //if user selected cancel
            if(!empty($_POST['cancel'])){
			    Url::redirect('backoffice/admin-users/index');
		    }

            // Create new user
            $createData = $this->_model->createData($_POST);
            if(isset($createData['error']) && $createData['error'] != null){
                foreach($createData['error'] as $key => $error){
                    $this->_view->error[$key] = $error;
                }
            }else{
                $this->_view->flash[] = "Admin User added successfully.";
                Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                Url::redirect('backoffice/admin-users/index');
            }
		}
		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('admin-users/add', 'layout', 'backoffice');
	}

    /**
	 * PAGE: login
	 * GET: /backoffice/admin-users/login
	 * This method handles the admin login
	 */
    public function login(){
        // Check User is Logged In
		if(Session::get('AdminLoggedIn') == true){
            $this->_view->flash[] = "You are already logged in";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
			Url::redirect('backoffice/users');
		}
        // Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Login', 'Login');
		// Set Page Description
		$this->_view->pageDescription = 'User Login';
		// Set Page Section
		$this->_view->pageSection = 'Login';

		// Set Page Specific CSS
		//$this->_view->pageCss = $pageCss;

		// Set Page Specific Javascript
		//$this->_view->pageJs = $pageJs;

		// Define expected and required
		$this->_view->expected = array('username', 'password');
		$this->_view->required = array('username', 'password');

		// Set default variables
        $this->_view->missing = array();
        $this->_view->error = array();
        $this->_view->postData = array();

        // If Form has been submitted process it
		if(!empty($_POST)){
			// Send $_POST to validate function
			$post = Form::ValidatePost($_POST, $this->_view->expected, $this->_view->required);

			// If true return array of formatted $_POST data
			if($post[0] == true){
				$this->_view->postData = $post[1];
			}
			// else return array of missing required fields
			else{
				$this->_view->missing = $post[1];
			}

			if(empty($this->_view->missing)){

				$username = $this->_view->postData['username'];
				$password = $this->_view->postData['password'];

				//If no errors yet continue
				if(empty($this->_view->error)){

					//Get User ID, Salt and Password for given Email Address
					$checkUser = $this->_model->checkAdminLogin($username);

					if(!empty($checkUser)){
						$userID = $checkUser[0]['id'];
						$storedSalt = $checkUser[0]['salt'];
						$storedPassword = $checkUser[0]['user_pass'];

						if(!empty($userID) && !empty($storedSalt) && !empty($storedPassword)){

							// Check if the given password matches the hashed password
							$verify = Password::password_verify($storedPassword, $storedSalt, $password);

							// If true comtinue with login
							if($verify[0] == true){
								// Get User Details
								$userData = $this->_model->getDataByID($userID);

								// Set User Sessions
								Session::set('AdminLoggedIn', true);
								Session::set('AdminCurrentUserID', $userID);
								Session::set('AdminCurrentUserName', $userData[0]['display_name']);
                                Session::set('AdminIsSuper', $userData[0]['is_super']);

								Url::redirect('backoffice/admin-users/index');

							}
							// else return error message
							else{
								$this->_view->error[] = $verify[1];
							}
						}else{
							$this->_view->error[] = 'We could not find a password for your account. Please <a href="/contact">contact us</a> and we will be happy to set this up for you.';
						}
					}else{
						$this->_view->error[] = 'No user found with this email. Please try different email.';
					}
				}

			}else{
				// Error Message
				$this->_view->error[] = 'Please complete the missing required fields.';
			}
		}

		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('admin-users/login', 'login-layout', 'backoffice');
    }

    /**
	 * PAGE: Logout
	 * GET: /admin-users/logout
	 * This method handles the User Log Out page
	 */
	public function logout(){
        Auth::checkAdminLogin();
		// Destroy the All Session Variables
		Session::destroyAll();
        $this->_view->flash[] = "You have successfully logged out";
        Session::set('backofficeFlash', array($this->_view->flash, 'failure'));

		// Redirect to login page with message
		Url::redirect('backoffice/login?logout=1');
	}

}
?>