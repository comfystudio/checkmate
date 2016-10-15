<?php
/** Users Controller */
class UsersController extends BaseController {

	/**  __construct */
	public function __construct(){
		parent::__construct();
	    // Load the User Model ($modelName, $area)
		$this->_model = $this->loadModel('users');
	}

	/**
	 * PAGE: login
	 * This method handles the User Log In page
	 */
	public function login($id = false){
		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Login');
		// Set Page Description
		$this->_view->pageDescription = 'Login to '.SITE_NAME;
		// Set Page Section
		$this->_view->pageSection = 'Login';

		// Define expected and required
		$this->_view->expected = array('email', 'password');
		$this->_view->required = array('email', 'password');

		// Set default variables
		$this->_view->missing = array();
		$this->_view->error = array();
		$this->_view->postData = array();

        //if already logged in bouce to dashboard
        if(isset($_SESSION['UserCurrentUserID']) && !empty($_SESSION['UserCurrentUserID'])){
            $this->_view->flash[] = "Already logged in";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('users/dashboard/');
        }

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

				$email = $this->_view->postData['email'];
				$password = $this->_view->postData['password'];

				// Validate Email
				$validateEmail = Form::ValidateEmail($email);
				// If true do nothing
				if($validateEmail[0] == true){}
				// else return error message
				else{
					$this->_view->error[] = $validateEmail[1];
				}

				// If no errors yet continue
				if(empty($this->_view->error)){

					// Get User ID, Salt and Password for given Email Address
					$checkUser = $this->_model->checkUserLogin($email);

					if(!empty($checkUser)){
						$userID = $checkUser[0]['id'];
						$storedSalt = $checkUser[0]['salt'];
						$storedPassword = $checkUser[0]['password'];

						if(!empty($userID) && !empty($storedSalt) && !empty($storedPassword)){

							// Check if the given password matches the hashed password
							$verify = Password::password_verify($storedPassword, $storedSalt, $password);

							// If true comtinue with login
							if($verify[0] == true){
								// Get User Details
								$userData = $this->_model->selectDataByID($userID);

								// Set User Sessions
								Session::set('UserLoggedIn', true);
								Session::set('UserAccountIsVerified', $userData[0]['email_verified']);
								Session::set('UserCurrentUserID', $userID);
								Session::set('UserCurrentFirstName', $userData[0]['firstname']);
								Session::set('UserCurrentSurname', $userData[0]['surname']);
								Session::set('UserCurrentFullName', $userData[0]['firstname'] . ' ' . $userData[0]['surname']);

								// Redirect depending on Referer
								if(Session::get('RefererController')){
									// Set Variables as Referer Session needs destroyed before redirecting
									$TheController = ltrim(Session::get('RefererController'), '/');

									// Destroy Referer to clear it before redirecting
									Referer::destroyReferer();
									Url::redirect($TheController);
								}else{
									// Destroy Referer to clear it before redirecting
									Referer::destroyReferer();
									Url::redirect('users/');
								}

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
		$this->_view->render('users/login');
	}


	/**
	 * PAGE: ForgotPassword
	 * This method handles the Forgotten Password page
	 */
	public function forgotPassword(){
		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Forgot Your Password', 'Login');
		// Set Page Description
		$this->_view->pageDescription = 'You can reset your password.';
		// Set Page Section
		$this->_view->pageSection = 'Login';

		// Define expected and required
		$this->_view->expected = array('email');
		$this->_view->required = array('email');

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

				$email = $this->_view->postData['email'];
				
				// Fetch array of user details
//				$userDetails = $this->_model->getUserByEmail($email);
//				$this->_view->userDetails = $userDetails[0];

				// Validate Email
				$validateEmail = Form::ValidateEmail($email);
				// If true do nothing
				if($validateEmail[0] == true){}
				// else return error message
				else{
					$this->_view->error[] = $validateEmail[1];
				}

				// If no errors yet continue
				if(empty($this->_view->error)){

					// Get User ID for given Email Address
					$checkUser = $this->_model->checkUserLogin($email);

					if(!empty($checkUser)){
						$userID = $checkUser[0]['id'];

						// Create Unique string
						$security_key = hash('sha256', $email.time());
						$exp_date = date("Y-m-d H:i:s", strtotime('+2 hours'));

						// Create array of data to post to the model
						$data = array();
						$data['user_id'] = $userID;
						$data['security_key'] = $security_key;
						$data['exp_date'] = $exp_date;

						// Send the Data to the Model
						$insertPasswordRecovery = $this->_model->insertPasswordRecovery($data);
						if($insertPasswordRecovery > 0){
							
							// Send confirmation email
							$to = $checkUser[0]['email'];
							$this->_view->data['name'] = $checkUser[0]['firstname'];
							$this->_view->data['message'] = "We have received a request to reset your account password. If you wish to reset your password click the button below and follow the steps listed. If you have not requested a password reset then ignore this email.";
                            $this->_view->data['button_link'] = $_SERVER['REDIRECT_proto'].'://'.$_SERVER['SERVER_NAME'].'/users/reset-password/'.$userID.'/'.$security_key.'/';
							$this->_view->data['button_text'] = "Reset Password";
							
							$subject = "Reset account email";
                            $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');

							$sendEmail = Html::sendEmail($to, $subject, SITE_EMAIL, $message);

							$this->_view->success[] = "We have sent you a link to reset your password. Please check your inbox for further instructions.";
						}else{
							// Error Message
							$this->_view->error[] = 'There was a problem trying to create new reset password.';
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
		$this->_view->render('users/forgot-password', 'login-layout');
	}

	/**
	 * PAGE: ResetPassword
	 * GET: /users/reset-password
	 * This method handles the Reset Password page
	 */
	public function resetPassword($user_id = false, $security_key = false){
		if(empty($user_id) || empty($security_key)){
			Url::redirect('error');
		}else{
			// Get the User Password Reset Key for the Given User ID
			$validatePasswordRecovery = $this->_model->validatePasswordRecovery($user_id, $security_key);
			$recoveryID = $validatePasswordRecovery[0]['id'];

			// Check the Keys match
			if ($recoveryID > 0){
				$this->_view->keysMatch = TRUE;
			}else{
				$this->_view->keysMatch = FALSE;
			}

			// Define expected and required
			$this->_view->expected = array('password', 'confirm_password');
			$this->_view->required = array('password', 'confirm_password');

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
					$password = $this->_view->postData['password'];
				    $confirm_password = $this->_view->postData['confirm_password'];
					// Check if passwords match
					if(!empty($password) && !empty($confirm_password)){
						if ($password != $confirm_password) {
							$this->_view->error[] = 'Your passwords don\'t match.';
						}else{
							$passwordStrength = Password::password_strength($password);
							if($passwordStrength[0] == true){
								$hash = Password::password_hash($password);
								if($hash[0] == true){
									$new_password = $hash[1];
									$salt = $hash[2];
								}
								// else return error message
								else{
									$this->_view->error[] = $hash[1];
								}
							}
							// else return error message
							else{
								$this->_view->error[] = $passwordStrength[1];
							}
						}
					}

					// If no errors yet continue
					if(empty($this->_view->error)){

						// Create array of data to post to the model
						$data['id'] = $user_id;
						$data['password'] = $new_password;
						$data['salt'] = $salt;

						// Update user password
						$updateUserPassword = $this->_model->updateUserPassword($data);

						if(!empty($updateUserPassword)){

							// If user password has been updated then delete their password recovery security key
							$deletePasswordRecovery = $this->_model->deletePasswordRecovery($user_id);

							// Get User Details
							$userData = $this->_model->selectDataByID($user_id);

                            // Set User Sessions
                            Session::set('UserLoggedIn', true);
                            Session::set('UserAccountIsVerified', $userData[0]['email_verified']);
                            Session::set('UserCurrentUserID', $user_id);
                            Session::set('UserCurrentFirstName', $userData[0]['firstname']);
                            Session::set('UserCurrentSurname', $userData[0]['surname']);
                            Session::set('UserCurrentFullName', $userData[0]['firstname'] . ' ' . $userData[0]['surname']);

							// Redirect depending on Referer
							if(Session::get('RefererController')){
								// Set Variables as Referer Session needs destroyed before redirecting
								$TheController = ltrim(Session::get('RefererController'), '/');

								// Destroy Referer to clear it before redirecting
								Referer::destroyReferer();
								Url::redirect($TheController);
							}else{
								// Destroy Referer to clear it before redirecting
								Referer::destroyReferer();
								Url::redirect('login');
							}
						}
					}
		    	}else{
                    $this->_view->error[] = 'Please complete the missing required fields.';
                }
		    }

			// Set the Page Title ('pageName', 'pageSection', 'areaName')
			$this->_view->pageTitle = array('Reset Password', 'Login');
			// Set Page Description
			$this->_view->pageDescription = 'Reset your Password';
			// Set Page Section
			$this->_view->pageSection = 'Login';


			// Render the view ($renderBody, $layout, $area)
			$this->_view->render('users/reset-password');
		}
	}

    /**
	 * PAGE: logout
	 * GET: /logout/index
	 * This method handles the User Log Out page
	 */
	public function logout(){
		// Destroy the All Session Variables
		Session::destroyAll();

		// Redirect to login page with message
		Url::redirect('login/?logout=1');
	}

    /**
	 * PAGE: user index
	 * GET: /users/index
	 * This method handles the view users page
	 */
	public function index(){
        Auth::checkUserLogin();

        Url::redirect('users/dashboard');

		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Index Users', 'Users');
		// Set Page Description
		$this->_view->pageDescription = 'Users';
		// Set Page Section
		$this->_view->pageSection = 'Users';

		###### PAGINATION ######
        //sanitise or set keywords to false
        if(isset($_GET['keywords']) && !empty($_GET['keywords'])){
            $_GET['keywords'] = FormInput::checkKeywords($_GET['keywords']);
        }else{
            $_GET['keywords'] = false;
        }

        $totalItems = $this->_model->countAllData($_GET['keywords'], 'active');
        $pages = new Pagination(12,'keywords='.$_GET['keywords'].'&page', $totalItems[0]['total']);
        $this->_view->getAllData = $this->_model->getAllData($pages->get_limit(), $_GET['keywords'], 'active');

		// Create the pagination nav menu
		$this->_view->page_links = $pages->page_links('?', null, 'front');

		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('users/index', 'layout');
	}

    /**
	 * PAGE: user add
	 * GET: /users/add
	 * This method handles the add user page
	 */
    public function add(){
        Auth::checkUserLogin();
		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Add', 'Users');
		// Set Page Description
		$this->_view->pageDescription = '';
		// Set Page Section
		$this->_view->pageSection = 'Users';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'Add';

        $this->_view->error = array();

        // If Form has been submitted process it
		if(!empty($_POST)){
            //if user selected cancel
            if(!empty($_POST['cancel'])){
			    Url::redirect('users/index');
		    }

            // Create new user
            $createData = $this->_model->createData($_POST);
            if(isset($createData['error']) && $createData['error'] != null){
                foreach($createData['error'] as $key => $error){
                    $this->_view->error[$key] = $error;
                }
            }else{
                $this->_view->flash[] = "User added successfully.";
                Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                Url::redirect('users/index');
            }
		}
		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('users/add', 'layout');
	}

    /**
	 * PAGE: User Edit
	 * GET: /users/edit
	 * This method handles the edit User page
	 */
	public function edit($id = false){
        Auth::checkUserLogin();

		if(!empty($id)){
			$selectDataByID = $this->_model->selectDataByID($id);
			if(isset($selectDataByID[0]['id']) && !empty($selectDataByID[0]['id'])){
                $this->_view->stored_data = $selectDataByID[0];
                if(isset($_POST['is_active']) && ($_POST['is_active'] == 1 || $_POST['is_active'] == 0)){
                     $this->_view->stored_data['is_active'] = $_POST['is_active'];
                }
                if($id != $_SESSION['UserCurrentUserID']){
            		$this->_view->flash[] = "ID does not match current user";
			        Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
					Url::redirect('users/');
                }
			}else{
                $this->_view->flash[] = "No Users matches this id";
                Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
				Url::redirect('users/');
			}
		}else{
            $this->_view->flash[] = "No ID was provided";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
			Url::redirect('users/');
		}

		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Edit User', 'Users');
		// Set Page Description
		$this->_view->pageDescription = '';
		// Set Page Section
		$this->_view->pageSection = 'Users';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'Edit User';

		$this->_view->userTypes = explode(',', USERS);


		// Set default variables
		$this->_view->error = array();

        // If Form has been submitted process it
		if(!empty($_POST['save'])){
            $_POST['id'] = $id;
            $_POST['salt'] = $selectDataByID[0]['salt'];
            $_POST['user_pass'] = $selectDataByID[0]['password'];
            $_POST['stored_user_email'] = $selectDataByID[0]['email'];
            $_POST['email_verified'] = 1;

            if(!isset($_FILES) || $_FILES['logo_image']['name'] == null) {
                $_POST['image'][0] = $this->_view->stored_data['logo_image'];
            }else{
                //calls function that moves resourced documents
                $_POST['image'] = $this->uploadFile($_FILES);
            }

            // Update user details
            $updateData = $this->_model->updateData($_POST);

            if(isset($updateData['error']) && $updateData['error'] != null){
                foreach($updateData['error'] as $key => $error) {
                    $this->_view->error[$key] = $error;
                }
            } else {
            	if (isset($_FILES) && $_FILES['image']['name'] != null) {
                    //remove old file
                    unlink(ROOT . UPLOAD_DIR . '/' . $this->_view->stored_data['logo_image']);
                }

                $this->_view->flash[] = "Account updated successfully.";
                Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                Url::redirect('users/');
            }
		}

		if(!empty($_POST['cancel'])){
			Url::redirect('users/');
		}

		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('users/add', 'layout');
	}

    /**
	 * PAGE: Generate Random Password
	 * GET: /users/generate-random-password
	 * This method give out a new random password
	 */
	public function generateRandomPassword(){
		$new_password = htmlspecialchars(Password::strong_random_password());
		if(!empty($new_password)){
			echo '<input id="new_pw" value="'.$new_password.'" class="form-control" readonly/>';
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
            if(isset($files['logo_image'])){
                $file = new Ps2_Upload(ROOT.UPLOAD_DIR.'/', 'logo_image', true);
                $file->addPermittedTypes(array(
                        'image/png', 'image/jpeg', 'image/gif',
                    )
                );
                $file->setMaxSize(MAX_FILE_SIZE);
                $file->move();
                return $file->getFilenames();

                //return $this->_view->error = array_merge($this->_view->error, $file->getMessages());
            }
        } catch (Exception $e) {
            return $this->_view->error[] = $e->getMessage();
        }
    }

	/**
	 * PAGE: register
	 * This method handles the User register
	 */
	public function register($type = false){
		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Signup');
		// Set Page Description
		$this->_view->pageDescription = 'Signup to checkmatedeposit.com';
		// Set Page Section
		$this->_view->pageSection = 'Signup';

		// Define expected and required
		$this->_view->expected = array('firstname', 'surname', 'email', 'password', 'contact_num', 'confirm_password');
		$this->_view->required = array('firstname', 'surname', 'email', 'password', 'contact_num', 'confirm_password');

		// Set default variables
		$this->_view->missing = array();
		$this->_view->error = array();
		$this->_view->postData = array();

        //if already logged in bouce to dashboard
        if(isset($_SESSION['UserCurrentUserID']) && !empty($_SESSION['UserCurrentUserID'])){
            $this->_view->flash[] = "Already logged in";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('users/dashboard/');
        }

		if(!empty($_SESSION['error'])){
			$this->_view->error = $_SESSION['error'];
			Session::destroy('error');
		}

		if(isset($type) && $type == 'lords'){
			$this->_view->userType = 1;
		}else{
			$this->_view->userType = 0;
		}

		// google catcha key: 6LceKioTAAAAAFNEHQkLD6PKBDpwtsnUunX2a3Mh

        // If Form has been submitted process it
		if(!empty($_POST)){
            if(isset($_POST['type']) && !empty($_POST['type'])) {
                $this->_view->userType = $_POST['type'];
            }

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

			if(!isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response'])){
				$this->_view->missing['capthca'] = 'Please complete the reCAPTCHA'; 
			} 

			if(empty($this->_view->missing)){

				// Define variables from $_POST
				$firstname = $this->_view->postData['firstname'];
				$surname = $this->_view->postData['surname'];
				$email = $this->_view->postData['email'];
				$password = $this->_view->postData['password'];
				$confirm_password = $this->_view->postData['confirm_password'];

				if($password != $confirm_password){
					$this->_view->error[] = 'Password and confirm password must match';
				}
				
				// Check if an account already exists with this email address
				$checkEmail = $this->_model->checkEmail($email);
				
				if(!empty($checkEmail)){
					$this->_view->error[] = "An account with this email address already exists";
				}else {
					// Validate Email
					$validateEmail = Form::ValidateEmail($email);
					// If true do nothing
					if($validateEmail[0] == true){}
					// else return error message
					else{
						$this->_view->error[] = $validateEmail[1];
					}
	
					// Check if passwords match
					if(!empty($password)){
						$passwordStrength = Password::password_strength($password);
						if($passwordStrength[0] == true){
							$hash = Password::password_hash($password);
							if($hash[0] == true){
								$new_password = $hash[1];
								$salt = $hash[2];
							}
							// else return error message
							else{
								$this->_view->error[] = $hash[1];
							}
						}
						// else return error message
						else{
							$this->_view->error[] = $passwordStrength[1];
						}
					}
	
					// If no errors yet continue
					if(empty($this->_view->error)){
						// Create array of data to post to the model
						$data = array();
						$data['type'] = $this->_view->userType;
						$data['firstname'] = $firstname;
						$data['surname'] = $surname;
						$data['email'] = $email;
						$data['password'] = $new_password;
						$data['salt'] = $salt;
						$data['contact_num'] = $this->_view->postData['contact_num'];

						if(!isset($_FILES) || empty($_FILES['logo_image']['name'])){
			                $data['logo_image'] = null;
			            }else{
			                $data['logo_image'] = $this->uploadFile($_FILES);
			            }

	
						// create user
						$createUser = $this->_model->createUserByEmail($data);
	
						if(!empty($createUser)){
	
							// Set User Sessions
							Session::set('UserLoggedIn', true);
							Session::set('UserAccountType', $this->_view->userType);
							Session::set('UserAccountIsVerified', 0);
							Session::set('UserCurrentUserID', $createUser);
							Session::set('UserCurrentFirstName', $firstname);
							Session::set('UserCurrentSurname', $surname);
							Session::set('UserCurrentFullName', $firstname. ' ' . $surname);

                            // Create Unique string
                            // $security_key = hash('sha256', $data['email'].time());
                            // $exp_date = date("Y-m-d H:i:s", strtotime('+2 hours'));

                            // Create array of data to post to the model
                            // $emailData = array();
                            // $emailData['user_id'] =  $data['users_id'];
                            // $emailData['security_key'] = $security_key;
                            // $emailData['exp_date'] = $exp_date;

                            // Send the Data to the Model
                            // $insertEmailVerified = $this->_model->insertEmailVerified($emailData);
							
							// Send confirmation email
							// $to = $email;
							// $name = $firstname;
							// $link = "https://luxurylet.com/user/profile/verify-email/".$emailData["user_id"]."/".$security_key."/";
							// $subject = "Verify Email Address";
							// $message = file_get_contents(ROOT.'/app/views/email-templates/verify-email-address.html');
		
							// $message = str_replace("%email_name%", "$name","$message");
							// $message = str_replace("%email_link%", "$link","$message");
		
							// $sendEmail = Html::sendEmail($to, $subject, "noreply@luxurylet.com", $message);
							
							Url::redirect('users/dashboard');
							
							
						}else {
							// else return error message
							$this->_view->error[] = "There was a problem creating your account. Please contact us to fix this issue.";
						}
					}
				}

			}else{
				// Error Message
				$this->_view->error[] = 'Please complete the missing required fields.';
			}
		}
		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('users/register');
	}

	/**
	 * PAGE: user dashboard
	 * GET: /users/dashboard
	 * This method handles the users dashboard
	 */
	public function dashboard(){
        Auth::checkUserLogin();

		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Index Users', 'Users');
		// Set Page Description
		$this->_view->pageDescription = 'Checkmate user dashboard';
		// Set Page Section
		$this->_view->pageSection = 'User dashboard';

		$user_id = $_SESSION['UserCurrentUserID'];

		// Need to get user details
		$this->_view->user = $this->_model->selectDataByID($user_id);

		if(!isset($this->_view->user) || empty($this->_view->user)){
			Url::redirect('users/login');
		}

        //sanitise or set keywords to false
        if(isset($_GET['keywords']) && !empty($_GET['keywords'])){
            $_GET['keywords'] = FormInput::checkKeywords($_GET['keywords']);
        }else{
            $_GET['keywords'] = false;
        }

		// Need to get user notifications
		$this->_notificationModel = $this->loadModel('notifications');
		$this->_view->notifications = $this->_notificationModel->getAlldataByUserId($user_id);

		// Need to get properties and reports by user_id
		$this->_propertyModel = $this->loadModel('properties');
		$this->_view->properties = $this->_propertyModel->getAlldataByUserId($user_id, $_GET['keywords']);
		$this->_view->propertyCount = count($this->_view->properties);
		// Need to get reports with tenant ID of user_id
		$this->_reportsModel = $this->loadModel('reports');
		$this->_view->reports = $this->_reportsModel->getAlldataByUserId($user_id);

		$this->_view->userTypes = explode(',', USERS);

		$this->_view->paymentTypes = explode(',', PAYMENTS);

		// Render the view ($renderBody, $layout, $area)
		$this->_view->render('users/dashboard', 'layout');
	}
}
?>