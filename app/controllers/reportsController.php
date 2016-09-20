<?php
/** Reports Controller */

class ReportsController extends BaseController {

	/** __construct */
	public function __construct(){
		parent::__construct();
		// Load the User Model ($modelName, $area)
		$this->_model = $this->loadModel('reports');
	}


    /**
     * PAGE: reports start
     * GET: /reports/start/:id/
     * This method handles the creation of reports along with user creation where necessary
     * @param $int property_id
     */
    public function start($property_id){
        Auth::checkUserLogin();
        // Set the Page Title ('pageName', 'pageSection', 'areaName')
        $this->_view->pageTitle = array('Reports');
        // Set Page Description
        $this->_view->pageDescription = 'Checkmate Check In';
        // Set Page Section
        $this->_view->pageSection = 'Reports';
        // Set Page Sub Section
        $this->_view->pageSubSection = 'Reports';

        // Building drop down arrays
        $this->_view->status = explode(',', REPORT);
        $this->_view->meter_type = explode(',', METER);
        $this->_view->key_status = explode(',', KEYS);
        $this->_view->clean_status = explode(',', CLEAN);

        $this->_roomsModel = $this->loadModel('rooms');
        $this->_itemsModel = $this->loadModel('items');
        $this->_notificationModel = $this->loadModel('notifications');

        if(!isset($property_id) || empty($property_id)){
            $this->_view->flash[] = "No Property Id provided";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('users/dashboard');
        }

        $this->_propertiesModel = $this->loadModel('properties');
        $property = $this->_propertiesModel->selectDataByID($property_id);

        if(!isset($property) || empty($property)){
            $this->_view->flash[] = "No Property matches the id";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('users/dashboard');
        }

        $this->_view->property = $property;

        // Need to work out if we need a Lead Tenent or LandLord input box.
        $this->_userModel = $this->loadModel('users');
        $user = $this->_userModel->selectDataByID($property[0]['created_by']);
        if($user[0]['type'] == 0){
            $leadTenantId = $user[0]['id'];
            $_POST['lead_tenant_id'] = $user[0]['id'];
            $lordId = null;
        }else{
            $lordId = $user[0]['id'];
            $_POST['lord_id'] = $user[0]['id'];
            $leadTenantId = null;
        }
        $this->_view->leadTenantId = $leadTenantId;
        $this->_view->lordId = $lordId;

        
        if(!empty($_POST['cancel'])){
            Url::redirect('users/dashboard');
        }

        if(!empty($_POST['save'])){
            $userIdArray[] = $_SESSION['UserCurrentUserID'];
            $_POST['property_id'] = $property_id;

            // Debug::printr($_POST);die;

            if(isset($_POST['lord_id']) && !empty($_POST['lord_id']) && !is_numeric($_POST['lord_id'])){
                $user = $this->_userModel->getUserByEmail($_POST['lord_id']);
                // If this user already exists on our system
                if(isset($user) && !empty($user)){
                    $data['text'] = 'You have been named a Landlord / Letting Agent for property '.$property[0]['title'].' Please review check in at the following link. <a href = "'.SITE_URL.'reports/checkin/'.$property[0]['id'].'">Link</a>';
                    $data['user_id'] = $user[0]['id'];
                    $this->_notificationModel->createData($data);

                    $this->_view->data['name'] = $user[0]['firstname'].' '.$user[0]['surname'];
                    $this->_view->data['message'] = 'You have been named a Landlord / Letting Agent for property '.$property[0]['title'].' Please review check in at the following link.';
                    $this->_view->data['button_link'] = SITE_URL.'reports/checkin/'.$property[0]['id'];
                    $this->_view->data['button_text'] = 'Review Report';

                    // Need to create email
                    $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
                    Html::sendEmail($user[0]['email'], 'Checkmate - New report has been created for you.', SITE_EMAIL, $message);
                    $userIdArray[] = $user[0]['id'];
                    $_POST['lord_id'] = $user[0]['id'];

                }else{
                    // If user doesn't exist in our system, we need to create them and send email / Notification to them.
                    $newUser['type'] = 1;
                    $newUser['firstname'] = 'temp firstname';
                    $newUser['surname'] = 'temp Surname';
                    $newUser['email'] = $_POST['lord_id'];
                    $newUser['contact_num'] = 'temp number';
                    $random = rand();
                    $tempPassword = 'temp'.$random;
                    $hash = Password::password_hash($tempPassword);
                    $newUser['password'] = $hash[1];
                    $newUser['password_again'] = $hash[1];
                    $newUser['salt'] = $hash[2];

                    $createUser = $this->_userModel->createDataSystem($newUser);

                    $data['user_id'] = $createUser;
                    $data['text'] = 'You have been named a Landlord / Letting Agent for property '.$property[0]['title'].' Please review check in at the following link. <a href = "'.SITE_URL.'reports/checkin/'.$property[0]['id'].'">Link</a>';
                    $this->_notificationModel->createData($data);

                    $this->_view->data['name'] = 'New User';
                    $this->_view->data['message'] = 'You have been named a Landlord / Letting Agent for property '.$property[0]['title'].' Please review check in at the following link. Your password is: '.$tempPassword;
                    $this->_view->data['button_link'] = SITE_URL.'reports/checkin/'.$property[0]['id'];
                    $this->_view->data['button_text'] = 'Review Report';

                    // Need to create email
                    $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
                    Html::sendEmail($newUser['email'], 'Checkmate - New report has been created for you.', SITE_EMAIL, $message);
                    $userIdArray[] = $createUser;
                    $_POST['lord_id'] = $createUser;
                }
            }

            if(isset($_POST['lead_tenant_id']) && !empty($_POST['lead_tenant_id']) && !is_numeric($_POST['lead_tenant_id'])){
                $user = $this->_userModel->getUserByEmail($_POST['lead_tenant_id']);
                // If this user already exists on our system
                if(isset($user) && !empty($user)){
                    $data['user_id'] = $user[0]['id'];
                    $data['text'] = 'You have been named a Landlord / Letting Agent for property '.$property[0]['title'].' Please review check in at the following link. <a href = "'.SITE_URL.'reports/checkin/'.$property[0]['id'].'">Link</a>';
                    $this->_notificationModel->createData($data);

                    $this->_view->data['name'] = $user[0]['firstname'].' '.$user[0]['surname'];
                    $this->_view->data['message'] = 'You have been named a Landlord / Letting Agent for property '.$property[0]['title'].' Please review check in at the following link.';
                    $this->_view->data['button_link'] = SITE_URL.'reports/checkin/'.$property[0]['id'];
                    $this->_view->data['button_text'] = 'Review Report';

                    // Need to create email
                    $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
                    Html::sendEmail($user[0]['email'], 'Checkmate - New report has been created for you.', SITE_EMAIL, $message);
                    $userIdArray[] = $user[0]['id'];
                    $_POST['lead_tenant_id'] = $user[0]['id'];

                }else{
                    // If user doesn't exist in our system, we need to create them and send email / Notification to them.
                    $newUser['type'] = 0;
                    $newUser['firstname'] = 'temp firstname';
                    $newUser['surname'] = 'temp Surname';
                    $newUser['email'] = $_POST['lead_tenant_id'];
                    $newUser['contact_num'] = 'temp number';
                    $random = rand();
                    $tempPassword = 'temp'.$random;
                    $hash = Password::password_hash($tempPassword);
                    $newUser['password'] = $hash[1];
                    $newUser['salt'] = $hash[2];

                    $createUser = $this->_userModel->createDataSystem($newUser);

                    $data['user_id'] = $createUser;
                    $data['text'] = 'You have been named a Landlord / Letting Agent for property '.$property[0]['title'].' Please review check in at the following link. <a href = "'.SITE_URL.'reports/checkin/'.$property[0]['id'].'">Link</a>';
                    $this->_notificationModel->createData($data);

                    $this->_view->data['name'] = 'New User';
                    $this->_view->data['message'] = 'You have been named a Landlord / Letting Agent for property '.$property[0]['title'].' Please review check in at the following link. Your password is: '.$tempPassword;
                    $this->_view->data['button_link'] = SITE_URL.'reports/checkin/'.$property[0]['id'];
                    $this->_view->data['button_text'] = 'Review Report';

                    // Need to create email
                    $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
                    Html::sendEmail($newUser['email'], 'Checkmate - New report has been created for you.', SITE_EMAIL, $message);
                    $userIdArray[] = $createUser;
                    $_POST['lead_tenant_id'] = $createUser;

                }
            }

            if(isset($_POST['users']) && !empty($_POST['users'])){
                foreach($_POST['users'] as $key => $email){
                    $user = $this->_userModel->getUserByEmail($email);
                    // If this user already exists on our system
                    if(isset($user) && !empty($user)){
                        $data['user_id'] = $user[0]['id'];
                        $data['text'] = 'You have been named a tenant for property '.$property[0]['title'].' Please review check in at the following link. <a href = "'.SITE_URL.'reports/checkin/'.$property[0]['id'].'">Link</a>';
                        $this->_notificationModel->createData($data);

                        $this->_view->data['name'] = $user[0]['firstname'].' '.$user[0]['surname'];
                        $this->_view->data['message'] = 'You have been named a tenant for property '.$property[0]['title'].' Please review check in at the following link.';
                        $this->_view->data['button_link'] = SITE_URL.'reports/checkin/'.$property[0]['id'];
                        $this->_view->data['button_text'] = 'Review Report';

                        // Need to create email
                        $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
                        Html::sendEmail($user[0]['email'], 'Checkmate - New report has been created for you.', SITE_EMAIL, $message);
                        $userIdArray[] = $user[0]['id'];

                    }else{
                        // If user doesn't exist in our system, we need to create them and send email / Notification to them.
                        $newUser['type'] = 0;
                        $newUser['firstname'] = 'temp firstname';
                        $newUser['surname'] = 'temp Surname';
                        $newUser['email'] = $email;
                        $newUser['contact_num'] = 'temp number';
                        $random = rand();
                        $tempPassword = 'temp'.$random;
                        $hash = Password::password_hash($tempPassword);
                        $newUser['password'] = $hash[1];
                        $newUser['salt'] = $hash[2];

                        $createUser = $this->_userModel->createDataSystem($newUser);

                        $data['user_id'] = $createUser;
                        $data['text'] = 'You have been named a tenant for property '.$property[0]['title'].' Please review check in at the following link. <a href = "'.SITE_URL.'reports/checkin/'.$property[0]['id'].'">Link</a>';
                        $this->_notificationModel->createData($data);

                        $this->_view->data['name'] = 'New User';
                        $this->_view->data['message'] = 'You have been named a tenant for property '.$property[0]['title'].' Please review check in at the following link. Your password is: '.$tempPassword;
                        $this->_view->data['button_link'] = SITE_URL.'reports/checkin/'.$property[0]['id'];
                        $this->_view->data['button_text'] = 'Review Report';

                        // Need to create email
                        $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
                        Html::sendEmail($newUser['email'], 'Checkmate - New report has been created for you.', SITE_EMAIL, $message);
                        $userIdArray[] = $createUser;
                    }
                }
            }

            // Create Report
            $createData = $this->_model->startReport($_POST);
            if(isset($createData['error']) && $createData['error'] != null){
                foreach($createData['error'] as $key => $error){
                    $this->_view->error[$key] = $error;
                }
            }else{
                // We need to create user_reports for some reason maybe need it later.
                foreach($userIdArray as $key => $id){
                    $this->_userModel->createUserReport($id, $createData);
                }

                // We need to create check_in_rooms and check_in_items based on property template if exists
                if(isset($property[0]['room_ids']) && !empty($property[0]['room_ids'])){
                    $check_in_room_ids = explode(',', $property[0]['room_ids']);
                    foreach($check_in_room_ids as $key => $check_in_room){
                        $createRoom = $this->_roomsModel->createCheckInRoom($createData, $check_in_room);
                        $rooms = $this->_roomsModel->selectDataByID($check_in_room);
                        if(isset($rooms[0]['items']) && !empty($rooms[0]['items'])){
                            $item_ids = explode(',', $rooms[0]['items']);
                            foreach ($item_ids as $key2 => $item) {
                                $this->_itemsModel->createCheckInItem($createRoom, $item);
                            }
                        }
                    }
                }

                $this->_view->flash[] = "Report created successfully.";
                Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                Url::redirect('users/dashboard');
            }
        }

        // Render the view ($renderBody, $layout, $area)
        $this->_view->render('reports/start', 'layout');
    }

    /**
     * PAGE: reports checkin
     * GET: /reports/checkin/:id/
     * This method handles the checkin process for both the lord and lead tenant for a report
     * @param $int property_id
     */
    public function checkin($property_id){
        Auth::checkUserLogin();
        // Set the Page Title ('pageName', 'pageSection', 'areaName')
        $this->_view->pageTitle = array('Reports');
        // Set Page Description
        $this->_view->pageDescription = 'Checkmate Check In';
        // Set Page Section
        $this->_view->pageSection = 'Reports';
        // Set Page Sub Section
        $this->_view->pageSubSection = 'Reports';

        // Building drop down arrays
        $this->_view->status = explode(',', REPORT);
        $this->_view->meter_type = explode(',', METER);
        $this->_view->key_status = explode(',', KEYS);
        $this->_view->clean_status = explode(',', CLEAN);
        $this->_view->YesNo = array('No', 'Yes');

        $this->_roomsModel = $this->loadModel('rooms');
        $this->_itemsModel = $this->loadModel('items');
        $this->_notificationModel = $this->loadModel('notifications');
        $this->_userModel = $this->loadModel('users');
        $this->_propertiesModel = $this->loadModel('properties');

        if(!isset($property_id) || empty($property_id)){
            $this->_view->flash[] = "No ID provided for property";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('users/dashboard');
        }

        // Need to get the right report information based on property_id
        $report = $this->_model->getCheckInReportsByPropertyId($property_id);

        if(!isset($report) || empty($report)){
            $this->_view->flash[] = "No report checkin time matches this property";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('users/dashboard');
        }

        // Need to get the users assoicated with this check in and bounce them if they don't belong here.
        $users = $this->_model->getUserReports($report[0]['id']);
        $users = explode(',', $users[0]['user_id']);
        if(!in_array($_SESSION['UserCurrentUserID'], $users)){
            $this->_view->flash[] = "You don't appear to be assoicated with this check_in";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('users/dashboard');
        }

        // We need to work if we have already created rooms from template
        if(isset($report[0]['check_in_room_ids']) && !empty($report[0]['check_in_room_ids'])){
            $check_in_room_ids = explode(',', $report[0]['check_in_room_ids']);

            $this->_view->checkInData = array();
            foreach($check_in_room_ids as $key => $check_in_room){
                $rooms = $this->_roomsModel->selectCheckInRoomsByID($check_in_room);
                $rooms = $rooms[0];
                if(isset($rooms['check_in_item_ids']) && !empty($rooms['check_in_item_ids'])){
                    $item_ids = explode(',', $rooms['check_in_item_ids']);
                    foreach ($item_ids as $key2 => $item) {
                        $items = $this->_itemsModel->selectCheckInItemsByID($item);
                        $items = $items[0];
                        $rooms['items'][] = $items;
                    }
                }

                $this->_view->checkInData[] = $rooms;
            }
        }

        // We need to get normal tenants who or not lords or lead tenant
        foreach($users as $key => $user){
            $usersArray[] = $this->_userModel->selectDataByID($user);
        }

        $this->_view->users = $usersArray;
        $this->_view->report = $report;
        $this->_view->property = $this->_propertiesModel->selectDataByID($report[0]['property_id']);

        //working out if current users role.
        if($report[0]['lead_tenant_id'] == $_SESSION['UserCurrentUserID']){
            $this->_view->userRole = 'lead_tenant';
        }elseif($report[0]['lord_id'] == $_SESSION['UserCurrentUserID']){
            $this->_view->userRole = 'lord';
        }else{
            $this->_view->userRole = 'tenant';
        }
       
        Debug::printr($this->_view->property);

        Debug::printr($report);

        Debug::printr($this->_view->checkInData);






        //$this->_userModel = $this->_userModel->getUsersByUserReports();


        // Render the view ($renderBody, $layout, $area)
        $this->_view->render('reports/checkin', 'layout');

    }

    /**
     * PAGE: reports image download
     * GET: /backoffice/reports/download/:id/:type
     * This method handles the download image action.
     */
    public function download($id, $type){
        if(!empty($id && $type)) {
            $selectedData = $this->_model->selectDataByID($id);

            switch ($type) {
                case 'meter':
                    $image = $selectedData[0]['meter_image'];
                    break;
                case 'agreement':
                    $image = $selectedData[0]['tenant_agreement'];
                    break;
                case 2:
                    echo "i equals 2";
                    break;
            }

            if (isset($selectedData) && !empty($selectedData)) {
                header('Content-Description: File Transfer');
                header('Content-Disposition: attachment; filename="'.basename(ROOT.UPLOAD_DIR.$image).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Content-Transfer-Encoding: binary');
                header('Pragma: public');
                header('Content-Length: ' . filesize(ROOT.'assets/uploads/'.$image));
                readfile(ROOT.'assets/uploads/'.$image);
                exit;
            } else {
                $this->_view->flash[] = "No data matches this ID";
                Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
                Url::redirect('backoffice/reports/');
            }
        }else{
            $this->_view->flash[] = "No ID was provided";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('backoffice/reports/');
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

    /**
     * PAGE: Report Download
     * GET: /backoffice/reports/report-download/:id
     * This method handles downloading of PDF based on reports data
     * @param int $id
     */
    public function reportDownload($id = null){
        Auth::checkAdminLogin();

        if(!empty($id)){
            $selectDataByID = $this->_model->selectDataByID($id);
            if(isset($selectDataByID[0]['id']) && !empty($selectDataByID[0]['id'])){
                $this->_view->stored_data = $selectDataByID[0];

            }else{
                $this->_view->flash[] = "No Rooms matches this id";
                Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
                Url::redirect('backoffice/reports/index');
            }
        }else{
            $this->_view->flash[] = "No ID provided for Rooms";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('backoffice/reports/index');
        }

        // Building drop down arrays
        $this->_view->status = explode(',', REPORT);
        $this->_view->meter_type = explode(',', METER);
        $this->_view->key_status = explode(',', KEYS);
        $this->_view->clean_status = explode(',', CLEAN);


        $this->_roomsModel = $this->loadModel('roomsBackoffice', 'backoffice');
        $this->_itemsModel = $this->loadModel('itemsBackoffice', 'backoffice');

        // Constructing our check in details
        if(isset($selectDataByID[0]['check_in_room_ids']) && !empty($selectDataByID[0]['check_in_room_ids'])){
            $check_in_room_ids = explode(',', $selectDataByID[0]['check_in_room_ids']);

            $this->_view->checkInData = array();
            foreach($check_in_room_ids as $key => $check_in_room){
                $rooms = $this->_roomsModel->selectCheckInRoomsByID($check_in_room);
                $rooms = $rooms[0];
                if(isset($rooms['check_in_item_ids']) && !empty($rooms['check_in_item_ids'])){
                    $item_ids = explode(',', $rooms['check_in_item_ids']);
                    foreach ($item_ids as $key2 => $item) {
                        $items = $this->_itemsModel->selectCheckInItemsByID($item);
                        $items = $items[0];
                        $rooms['items'][] = $items;
                    }
                }

                $this->_view->checkInData[] = $rooms;
            }
        }

        // Constructing our check out details
        $rooms = array();
        $items = array();
        if(isset($selectDataByID[0]['check_out_room_ids']) && !empty($selectDataByID[0]['check_out_room_ids'])){
            $check_out_room_ids = explode(',', $selectDataByID[0]['check_out_room_ids']);
            $this->_view->checkOutData = array();
            foreach($check_out_room_ids as $key => $check_out_room){
                $rooms = $this->_roomsModel->selectCheckOutRoomsByID($check_out_room);
                $rooms = $rooms[0];
                if(isset($rooms['check_out_item_ids']) && !empty($rooms['check_out_item_ids'])){
                    $item_ids = explode(',', $rooms['check_out_item_ids']);
                    foreach ($item_ids as $key2 => $item) {
                        $items = $this->_itemsModel->selectCheckOutItemsByID($item);
                        $items = $items[0];
                        $rooms['items'][] = $items;
                    }
                }

                $this->_view->checkOutData[] = $rooms;
            }
        }

        require_once(ROOT.'system/helpers/mpdf/mpdf.php');
        $mpdf = new mPDF();
        $mpdf->showImageErrors = true;
        $stylesheet = file_get_contents(ROOT.'/app/areas/backoffice/assets/css/pdf.css');
        $mpdf->WriteHTML($stylesheet,1);

        $mpdf->WriteHTML('<div>');
            $mpdf->WriteHTML('<div>');
                $mpdf->WriteHTML('<h3>Report for '.$this->_view->stored_data['property_title'].'</h3>');
            $mpdf->WriteHTML('</div>');

            $mpdf->WriteHTML('<div>');
                $mpdf->WriteHTML('<table>');
                    $mpdf->WriteHTML('<tr>');
                        $mpdf->WriteHTML('<th>');
                            $mpdf->WriteHTML('Landlord / Letting Agent');
                        $mpdf->WriteHTML('</th>');

                        $mpdf->WriteHTML('<td>');
                            $mpdf->WriteHTML($this->_view->stored_data['lord_firstname'].' '.$this->_view->stored_data['lord_surname']);
                        $mpdf->WriteHTML('</td>');
                    $mpdf->WriteHTML('</tr>');

                    $mpdf->WriteHTML('<tr>');
                        $mpdf->WriteHTML('<th>');
                            $mpdf->WriteHTML('Leading Tenant Name');
                        $mpdf->WriteHTML('</th>');

                        $mpdf->WriteHTML('<td>');
                            $mpdf->WriteHTML($this->_view->stored_data['tenant_firstname'].' '.$this->_view->stored_data['tenant_surname']);
                        $mpdf->WriteHTML('</td>');
                    $mpdf->WriteHTML('</tr>');

                    $mpdf->WriteHTML('<tr>');
                        $mpdf->WriteHTML('<th>');
                            $mpdf->WriteHTML('Current Report Status');
                        $mpdf->WriteHTML('</th>');

                        $mpdf->WriteHTML('<td>');
                            $mpdf->WriteHTML($this->_view->status[$this->_view->stored_data['status']]);
                        $mpdf->WriteHTML('</td>');
                    $mpdf->WriteHTML('</tr>');

                    $mpdf->WriteHTML('<tr>');
                        $mpdf->WriteHTML('<th>');
                            $mpdf->WriteHTML('Check In Date');
                        $mpdf->WriteHTML('</th>');

                        $mpdf->WriteHTML('<td>');
                            $mpdf->WriteHTML(date("F j, Y", strtotime($this->_view->stored_data['check_in'])));
                        $mpdf->WriteHTML('</td>');
                    $mpdf->WriteHTML('</tr>');

                    $mpdf->WriteHTML('<tr>');
                        $mpdf->WriteHTML('<th>');
                            $mpdf->WriteHTML('Check Out Date');
                        $mpdf->WriteHTML('</th>');

                        $mpdf->WriteHTML('<td>');
                            $mpdf->WriteHTML(date("F j, Y", strtotime($this->_view->stored_data['check_out'])));
                        $mpdf->WriteHTML('</td>');
                    $mpdf->WriteHTML('</tr>');

                    $mpdf->WriteHTML('<tr>');
                        $mpdf->WriteHTML('<th>');
                            $mpdf->WriteHTML('Meter Type');
                        $mpdf->WriteHTML('</th>');

                        $mpdf->WriteHTML('<td>');
                            $mpdf->WriteHTML($this->_view->meter_type[$this->_view->stored_data['meter_type']]);
                        $mpdf->WriteHTML('</td>');
                    $mpdf->WriteHTML('</tr>');

                    $mpdf->WriteHTML('<tr>');
                        $mpdf->WriteHTML('<th>');
                            $mpdf->WriteHTML('Meter Reading');
                        $mpdf->WriteHTML('</th>');

                        $mpdf->WriteHTML('<td>');
                            $mpdf->WriteHTML($this->_view->stored_data['meter_reading']);
                        $mpdf->WriteHTML('</td>');
                    $mpdf->WriteHTML('</tr>');

                    $mpdf->WriteHTML('<tr>');
                        $mpdf->WriteHTML('<th>');
                            $mpdf->WriteHTML('Meter Measurement');
                        $mpdf->WriteHTML('</th>');

                        $mpdf->WriteHTML('<td>');
                            $mpdf->WriteHTML($this->_view->stored_data['meter_measurement']);
                        $mpdf->WriteHTML('</td>');
                    $mpdf->WriteHTML('</tr>');

                    $mpdf->WriteHTML('<tr>');
                        $mpdf->WriteHTML('<th>');
                            $mpdf->WriteHTML('Meter Image');
                        $mpdf->WriteHTML('</th>');

                        $mpdf->WriteHTML('<td>');
                            if(isset($this->_view->stored_data['meter_image']) && !empty($this->_view->stored_data['meter_image'])){
                                $mpdf->WriteHTML('<img src = "'.ROOT.'assets/uploads/'.$this->_view->stored_data['meter_image'].'" width="120px;" height="120px">');
                            }else{
                                $mpdf->WriteHTML('No Meter Image supplied');
                            }
                        $mpdf->WriteHTML('</td>');
                    $mpdf->WriteHTML('</tr>');

                    $mpdf->WriteHTML('<tr>');
                        $mpdf->WriteHTML('<th>');
                            $mpdf->WriteHTML('Oil Level');
                        $mpdf->WriteHTML('</th>');

                        $mpdf->WriteHTML('<td>');
                            $mpdf->WriteHTML($this->_view->stored_data['oil_level']);
                        $mpdf->WriteHTML('</td>');
                    $mpdf->WriteHTML('</tr>');

                    $mpdf->WriteHTML('<tr>');
                        $mpdf->WriteHTML('<th>');
                            $mpdf->WriteHTML('Key Status');
                        $mpdf->WriteHTML('</th>');

                        $mpdf->WriteHTML('<td>');
                            $mpdf->WriteHTML($this->_view->key_status[$this->_view->stored_data['keys_acquired']]);
                        $mpdf->WriteHTML('</td>');
                    $mpdf->WriteHTML('</tr>');

                    $mpdf->WriteHTML('<tr>');
                        $mpdf->WriteHTML('<th>');
                            $mpdf->WriteHTML('Check In Status');
                        $mpdf->WriteHTML('</th>');

                        $mpdf->WriteHTML('<td>');
                            if(isset($this->_view->stored_data['tenant_approved_check_in']) && $this->_view->stored_data['tenant_approved_check_in'] == 1 && isset($this->_view->stored_data['lord_approved_check_in']) && $this->_view->stored_data['lord_approved_check_in'] == 1){
                                $mpdf->WriteHTML('Both Tenant and LandLord have approved');
                            }elseif(isset($this->_view->stored_data['tenant_approved_check_in']) && $this->_view->stored_data['tenant_approved_check_in'] == 1){
                                $mpdf->WriteHTML('Only Tenant has approved');
                            }elseif(isset($this->_view->stored_data['lord_approved_check_in']) && $this->_view->stored_data['lord_approved_check_in'] == 1){
                                $mpdf->WriteHTML('Only Landlord has approved');
                            }else{
                                $mpdf->WriteHTML('Neither the Landlord nor Tenant has approved');
                            }
                        $mpdf->WriteHTML('</td>');
                    $mpdf->WriteHTML('</tr>');

                    $mpdf->WriteHTML('<tr>');
                        $mpdf->WriteHTML('<th>');
                            $mpdf->WriteHTML('Check Out Status');
                        $mpdf->WriteHTML('</th>');

                        $mpdf->WriteHTML('<td>');
                            if(isset($this->_view->stored_data['tenant_approved_check_out']) && $this->_view->stored_data['tenant_approved_check_out'] == 1 && isset($this->_view->stored_data['lord_approved_check_out']) && $this->_view->stored_data['lord_approved_check_out'] == 1){
                                $mpdf->WriteHTML('Both Tenant and LandLord have approved');
                            }elseif(isset($this->_view->stored_data['tenant_approved_check_out']) && $this->_view->stored_data['tenant_approved_check_out'] == 1){
                                $mpdf->WriteHTML('Only Tenant has approved');
                            }elseif(isset($this->_view->stored_data['lord_approved_check_out']) && $this->_view->stored_data['lord_approved_check_out'] == 1){
                                $mpdf->WriteHTML('Only Landlord has approved');
                            }else{
                                $mpdf->WriteHTML('Neither the Landlord nor Tenant has approved');
                            }
                        $mpdf->WriteHTML('</td>');
                    $mpdf->WriteHTML('</tr>');
                $mpdf->WriteHTML('</table>');
            $mpdf->WriteHTML('</div>');
        $mpdf->WriteHTML('</div>');


        if(isset($this->_view->checkInData) && !empty($this->_view->checkInData)){
            $mpdf->WriteHTML('<h1>Check In Details</h1>');
            foreach($this->_view->checkInData as $key => $room){
                $mpdf->WriteHTML('<div>');
                    $mpdf->WriteHTML('<div>');
                        $mpdf->WriteHTML('<h3>'.$room['name'].'</h3>');
                    $mpdf->WriteHTML('</div>');

                    $mpdf->WriteHTML('<div>');
                        $mpdf->WriteHTML('<table>');

                            $mpdf->WriteHTML('<tr>');
                                $mpdf->WriteHTML('<th>');
                                    $mpdf->WriteHTML('Clean Status');
                                $mpdf->WriteHTML('</th>');

                                $mpdf->WriteHTML('<td>');
                                    $mpdf->WriteHTML($this->_view->clean_status[$room['clean']]);
                                $mpdf->WriteHTML('</td>');
                            $mpdf->WriteHTML('</tr>');

                            $mpdf->WriteHTML('<tr>');
                                $mpdf->WriteHTML('<th>');
                                    $mpdf->WriteHTML('Tenant Comment');
                                $mpdf->WriteHTML('</th>');

                                $mpdf->WriteHTML('<td>');
                                    $mpdf->WriteHTML($room['tenant_comment']);
                                $mpdf->WriteHTML('</td>');
                            $mpdf->WriteHTML('</tr>');

                            $mpdf->WriteHTML('<tr>');
                                $mpdf->WriteHTML('<th>');
                                    $mpdf->WriteHTML('LandLord / Letting Agent Comment');
                                $mpdf->WriteHTML('</th>');

                                $mpdf->WriteHTML('<td>');
                                    $mpdf->WriteHTML($room['lord_comment']);
                                $mpdf->WriteHTML('</td>');
                            $mpdf->WriteHTML('</tr>');

                            if(isset($room['items']) && !empty($room['items'])){
                                foreach($room['items'] as $key2 => $item){
                                    $mpdf->WriteHTML('<tr>');
                                        $mpdf->WriteHTML('<th colspan = "2" style="text-align:center;">');
                                            $mpdf->WriteHTML($item['name']);
                                        $mpdf->WriteHTML('</th>');
                                    $mpdf->WriteHTML('</tr>');

                                    $mpdf->WriteHTML('<tr>');
                                        $mpdf->WriteHTML('<th>');
                                            $mpdf->WriteHTML('Item Status');
                                        $mpdf->WriteHTML('</th>');

                                        $mpdf->WriteHTML('<td>');
                                            $mpdf->WriteHTML($this->_view->status[$item['status']]);
                                        $mpdf->WriteHTML('</td>');
                                    $mpdf->WriteHTML('</tr>');

                                    $mpdf->WriteHTML('<tr>');
                                        $mpdf->WriteHTML('<th>');
                                            $mpdf->WriteHTML('Item Image');
                                        $mpdf->WriteHTML('</th>');

                                        $mpdf->WriteHTML('<td>');
                                            if(isset($item['image']) && !empty($item['image'])){
                                                $mpdf->WriteHTML('<img src = "'.ROOT.'assets/uploads/'.$item['image'].'" width="120px;" height="120px">');
                                            }else{
                                                $mpdf->WriteHTML('No Image Supplied');
                                            }
                                        $mpdf->WriteHTML('</td>');
                                    $mpdf->WriteHTML('</tr>');

                                    $mpdf->WriteHTML('<tr>');
                                        $mpdf->WriteHTML('<th>');
                                            $mpdf->WriteHTML('Tenant Comment');
                                        $mpdf->WriteHTML('</th>');

                                        $mpdf->WriteHTML('<td>');
                                            $mpdf->WriteHTML($item['tenant_comment']);
                                        $mpdf->WriteHTML('</td>');
                                    $mpdf->WriteHTML('</tr>');

                                    $mpdf->WriteHTML('<tr>');
                                        $mpdf->WriteHTML('<th>');
                                            $mpdf->WriteHTML('LandLord / Letting Agent Comment');
                                        $mpdf->WriteHTML('</th>');

                                        $mpdf->WriteHTML('<td>');
                                            $mpdf->WriteHTML($item['lord_comment']);
                                        $mpdf->WriteHTML('</td>');
                                    $mpdf->WriteHTML('</tr>');

                                    $mpdf->WriteHTML('<tr>');
                                        $mpdf->WriteHTML('<td colspan = "2">');
                                            $mpdf->WriteHTML('&nbsp;');
                                        $mpdf->WriteHTML('</td>');
                                    $mpdf->WriteHTML('</tr>');
                                }
                            }


                        $mpdf->WriteHTML('</table>');
                    $mpdf->WriteHTML('</div>');
                $mpdf->WriteHTML('</div>');
            }
        }

        if(isset($this->_view->checkOutData) && !empty($this->_view->checkOutData)){
            $mpdf->WriteHTML('<h1>Check Out Details</h1>');
            foreach($this->_view->checkOutData as $key => $room){
                $mpdf->WriteHTML('<div>');
                    $mpdf->WriteHTML('<div>');
                        $mpdf->WriteHTML('<h3>'.$room['name'].'</h3>');
                    $mpdf->WriteHTML('</div>');

                    $mpdf->WriteHTML('<div>');
                        $mpdf->WriteHTML('<table>');

                            $mpdf->WriteHTML('<tr>');
                                $mpdf->WriteHTML('<th>');
                                    $mpdf->WriteHTML('Clean Status');
                                $mpdf->WriteHTML('</th>');

                                $mpdf->WriteHTML('<td>');
                                    $mpdf->WriteHTML($this->_view->clean_status[$room['clean']]);
                                $mpdf->WriteHTML('</td>');
                            $mpdf->WriteHTML('</tr>');

                            $mpdf->WriteHTML('<tr>');
                                $mpdf->WriteHTML('<th>');
                                    $mpdf->WriteHTML('Tenant Comment');
                                $mpdf->WriteHTML('</th>');

                                $mpdf->WriteHTML('<td>');
                                    $mpdf->WriteHTML($room['tenant_comment']);
                                $mpdf->WriteHTML('</td>');
                            $mpdf->WriteHTML('</tr>');

                            $mpdf->WriteHTML('<tr>');
                                $mpdf->WriteHTML('<th>');
                                    $mpdf->WriteHTML('LandLord / Letting Agent Comment');
                                $mpdf->WriteHTML('</th>');

                                $mpdf->WriteHTML('<td>');
                                    $mpdf->WriteHTML($room['lord_comment']);
                                $mpdf->WriteHTML('</td>');
                            $mpdf->WriteHTML('</tr>');

                            if(isset($room['items']) && !empty($room['items'])){
                                foreach($room['items'] as $key2 => $item){
                                    $mpdf->WriteHTML('<tr>');
                                        $mpdf->WriteHTML('<th colspan = "2" style="text-align:center;">');
                                            $mpdf->WriteHTML($item['name']);
                                        $mpdf->WriteHTML('</th>');
                                    $mpdf->WriteHTML('</tr>');

                                    $mpdf->WriteHTML('<tr>');
                                        $mpdf->WriteHTML('<th>');
                                            $mpdf->WriteHTML('Item Status');
                                        $mpdf->WriteHTML('</th>');

                                        $mpdf->WriteHTML('<td>');
                                            $mpdf->WriteHTML($this->_view->status[$item['status']]);
                                        $mpdf->WriteHTML('</td>');
                                    $mpdf->WriteHTML('</tr>');

                                    $mpdf->WriteHTML('<tr>');
                                        $mpdf->WriteHTML('<th>');
                                            $mpdf->WriteHTML('Item Image');
                                        $mpdf->WriteHTML('</th>');

                                        $mpdf->WriteHTML('<td>');
                                            if(isset($item['image']) && !empty($item['image'])){
                                                $mpdf->WriteHTML('<img src = "'.ROOT.'assets/uploads/'.$item['image'].'" width="120px;" height="120px">');
                                            }else{
                                                $mpdf->WriteHTML('No Image Supplied');
                                            }
                                        $mpdf->WriteHTML('</td>');
                                    $mpdf->WriteHTML('</tr>');

                                    $mpdf->WriteHTML('<tr>');
                                        $mpdf->WriteHTML('<th>');
                                            $mpdf->WriteHTML('Tenant Comment');
                                        $mpdf->WriteHTML('</th>');

                                        $mpdf->WriteHTML('<td>');
                                            $mpdf->WriteHTML($item['tenant_comment']);
                                        $mpdf->WriteHTML('</td>');
                                    $mpdf->WriteHTML('</tr>');

                                    $mpdf->WriteHTML('<tr>');
                                        $mpdf->WriteHTML('<th>');
                                            $mpdf->WriteHTML('LandLord / Letting Agent Comment');
                                        $mpdf->WriteHTML('</th>');

                                        $mpdf->WriteHTML('<td>');
                                            $mpdf->WriteHTML($item['lord_comment']);
                                        $mpdf->WriteHTML('</td>');
                                    $mpdf->WriteHTML('</tr>');

                                    $mpdf->WriteHTML('<tr>');
                                        $mpdf->WriteHTML('<td colspan = "2">');
                                            $mpdf->WriteHTML('&nbsp;');
                                        $mpdf->WriteHTML('</td>');
                                    $mpdf->WriteHTML('</tr>');
                                }
                            }
                        $mpdf->WriteHTML('</table>');
                    $mpdf->WriteHTML('</div>');
                $mpdf->WriteHTML('</div>');
            }
        }

        $mpdf->debug = true; 

        $mpdf->Output();
        exit;
    }
}
?>