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

        if(!$this->checkMembership()){
            $this->_view->flash[] = "Your membership has expired. Please cancel and renew membership";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('users/dashboard');
        }

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
                        if(isset($email) && !empty($email)) {
                            $newUser['type'] = 0;
                            $newUser['firstname'] = 'temp firstname';
                            $newUser['surname'] = 'temp Surname';
                            $newUser['email'] = $email;
                            $newUser['contact_num'] = 'temp number';
                            $random = rand();
                            $tempPassword = 'temp' . $random;
                            $hash = Password::password_hash($tempPassword);
                            $newUser['password'] = $hash[1];
                            $newUser['salt'] = $hash[2];

                            $createUser = $this->_userModel->createDataSystem($newUser);

                            $data['user_id'] = $createUser;
                            $data['text'] = 'You have been named a tenant for property ' . $property[0]['title'] . ' Please review check in at the following link. <a href = "' . SITE_URL . 'reports/checkin/' . $property[0]['id'] . '">Link</a>';
                            $this->_notificationModel->createData($data);

                            $this->_view->data['name'] = 'New User';
                            $this->_view->data['message'] = 'You have been named a tenant for property ' . $property[0]['title'] . ' Please review check in at the following link. Your password is: ' . $tempPassword;
                            $this->_view->data['button_link'] = SITE_URL . 'reports/checkin/' . $property[0]['id'];
                            $this->_view->data['button_text'] = 'Review Report';

                            // Need to create email
                            $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
                            Html::sendEmail($newUser['email'], 'Checkmate - New report has been created for you.', SITE_EMAIL, $message);
                            $userIdArray[] = $createUser;
                        }
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
        $this->_view->item_status = array('Green', 'Yellow', 'Red');
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

        // We need to work out if we have already created rooms from template
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

        // If Form has been submitted process it
        if(!empty($_POST)){

            if(!empty($_POST['cancel'])){
                Url::redirect('users/dashboard');
            }

            // If we have a signature we need to process it.
            if(isset($_POST['signature']) && !empty($_POST['signature'])){
                $data_uri = $_POST['signature'];
                $encoded_image = explode(",", $data_uri)[1];
                $decoded_image = base64_decode($encoded_image);
                $signatureData['check_in_signature'] = date('Y-m-d-H-i-s-').$_SESSION['UserCurrentUserID'].'.png';
                file_put_contents('assets/uploads/'. $signatureData['check_in_signature'], $decoded_image);
                // We now need to update the user_reports with our signature data and timestamp
                $signatureData['check_in_time'] = date('Y-m-d H:i:s');
                $signatureData['user_id'] = $_SESSION['UserCurrentUserID'];
                $signatureData['report_id'] = $report[0]['id'];
                $this->_userModel->updateUserReportCheckIn($signatureData);
            }

            //Need to workout new status of the report
            $tenant_approved_check_in = (isset($_POST['tenant_approved_check_in'])) ? $_POST['tenant_approved_check_in'] : $report[0]['tenant_approved_check_in'];
            $lord_approved_check_in = (isset($_POST['lord_approved_check_in'])) ? $_POST['lord_approved_check_in'] : $report[0]['lord_approved_check_in'];
            $oil_level = (isset($_POST['oil_level']) && !empty($_POST['oil_level'])) ? $_POST['oil_level'] : $report[0]['oil_level'];
            $keys_front_door = (isset($_POST['keys_front_door'])) ? $_POST['keys_front_door'] : $report[0]['keys_front_door'];
            $keys_bedroom_door = (isset($_POST['keys_bedroom_door'])) ? $_POST['keys_bedroom_door'] : $report[0]['keys_bedroom_door'];
            $keys_block_door = (isset($_POST['keys_block_door'])) ? $_POST['keys_block_door'] : $report[0]['keys_block_door'];
            $keys_back_door = (isset($_POST['keys_back_door'])) ? $_POST['keys_back_door'] : $report[0]['keys_back_door'];
            $keys_garage_door = (isset($_POST['keys_garage_door'])) ? $_POST['keys_garage_door'] : $report[0]['keys_garage_door'];
            $keys_other_door = (isset($_POST['keys_other_door'])) ? $_POST['keys_other_door'] : $report[0]['keys_other_door'];
            $fire_extin = (isset($_POST['fire_extin']) && !empty($_POST['fire_extin'])) ? $_POST['fire_extin'] : $report[0]['fire_extin'];
            $fire_blanket = (isset($_POST['fire_blanket']) && !empty($_POST['fire_blanket'])) ? $_POST['fire_blanket'] : $report[0]['fire_blanket'];
            $smoke_alarm = (isset($_POST['smoke_alarm']) && !empty($_POST['smoke_alarm'])) ? $_POST['smoke_alarm'] : $report[0]['smoke_alarm'];


            if( $tenant_approved_check_in == 1 && $lord_approved_check_in == 1){
                $status = 2;
            }elseif ( $tenant_approved_check_in == 1 || $lord_approved_check_in == 1) {
                $status = 1;
            }else{
                $status = 0;
            }
            $_POST['status'] = $status;
            $_POST['tenant_approved_check_in'] = $tenant_approved_check_in;
            $_POST['lord_approved_check_in'] = $lord_approved_check_in;
            $_POST['oil_level'] = $oil_level;
            $_POST['keys_front_door'] = $keys_front_door;
            $_POST['keys_bedroom_door'] = $keys_bedroom_door;
            $_POST['keys_block_door'] = $keys_block_door;
            $_POST['keys_back_door'] = $keys_back_door;
            $_POST['keys_garage_door'] = $keys_garage_door;
            $_POST['keys_other_door'] = $keys_other_door;
            $_POST['fire_extin'] = $fire_extin;
            $_POST['fire_blanket'] = $fire_blanket;
            $_POST['smoke_alarm'] = $smoke_alarm;
            $_POST['id'] = $report[0]['id'];
            $_POST['tenant_approved_check_out'] = 0;
            $_POST['lord_approved_check_out'] = 0;

            // Uploading meter image
            if(!isset($_FILES['meter_image']) || $_FILES['meter_image']['name'] == null) {
                $_POST['meter_image'][0] = $report[0]['meter_image'];
            }else{
                //calls function that moves resourced documents
                $_POST['meter_image'] = $this->uploadFile($_FILES, 'meter_image');
            }

            // Uploading tenant Agreement
            if(!isset($_FILES['tenant_agreement']) || $_FILES['tenant_agreement']['name'] == null) {
                $_POST['tenant_agreement'][0] = $report[0]['tenant_agreement'];
            }else{
                //calls function that moves resourced documents
                $_POST['tenant_agreement'] = $this->uploadFile($_FILES, 'tenant_agreement');
            }

            foreach($this->_view->checkInData as $key => $roomData){
                $roomArray[$roomData['id']] = $roomData;
                $itemsArray = array();
                foreach($roomData['items'] as $key2 => $item){
                    $itemsArray[$item['id']] = $item;
                }
                $roomArray[$roomData['id']]['items'] = $itemsArray;
            }

            $rooms = $_POST['rooms'];
            unset($_POST['rooms']);

            // Update user details
            $updateData = $this->_model->updateData($_POST);

            if(isset($updateData['error']) && $updateData['error'] != null){
                foreach($updateData['error'] as $key => $error) {
                    $this->_view->error[$key] = $error;
                }
            } else {
                //We need to remove check OUT rooms and create new ones to keep them the same
                $this->_roomsModel->deleteCheckOutRoomsByReportId($report[0]['id']);

                // We now need to update our rooms
                foreach($rooms as $key => $room){
                    $tenant_comment = (isset($rooms[$key]['tenant_comment']) && !empty($rooms[$key]['tenant_comment'])) ? $rooms[$key]['tenant_comment'] : $roomArray[$key]['tenant_comment'];
                    $lord_comment = (isset($rooms[$key]['lord_comment']) && !empty($rooms[$key]['lord_comment'])) ? $rooms[$key]['lord_comment'] : $roomArray[$key]['lord_comment'];
                    $clean = (isset($rooms[$key]['clean'])) ? $rooms[$key]['clean'] : $roomArray[$key]['clean'];
                    $room['lord_comment'] = $lord_comment;
                    $room['tenant_comment'] = $tenant_comment;
                    $room['clean'] = $clean;
                    $room['id'] = $key;
                    $this->_roomsModel->updateCheckInRoom($room);

                    $checkOutRoomId = $this->_roomsModel->createCheckOutRoom($report[0]['id'], $room['room_id']);

                    // Now we need to update our items and possibly create new ones....
                    foreach($room['items'] as $key2 => $item){
                        // If its a new item
                        if(preg_match('/new/', $key2)){
                            if(isset($item['name']) && !empty($item['name'])){
                                //We need to create the new item in the items table
                                $newItem['is_active'] = 1;
                                $newItem['name'] = $item['name'];
                                $newItemId = $this->_itemsModel->createData($newItem);

                                //Now we need to create the new check_in_item
                                $newCheckInItem['item_id'] = $newItemId;
                                $newCheckInItem['report_rooms_id'] = $key;
                                $newCheckInItem['tenant_comment'] = $item['tenant_comment'];
                                $newCheckInItem['tenant_approved'] = $item['tenant_approved'];

                                //We need to handle our image if there is one
                                if(!isset($_FILES[$key2]) || $_FILES[$key2]['name'] == null) {
                                    $newCheckInItem['image'][0] = null;
                                }else{
                                    //calls function that moves resourced documents
                                    $newCheckInItem['image'] = $this->uploadFile($_FILES, $key2);
                                }

//                                DEBUG::printr($room['items']);die;
//                                //We need to handle our Lord image if there is one
//                                if(!isset($_FILES["lord_".$key2]) || $_FILES["lord_".$key2]['name'] == null) {
//                                    $newCheckInItem['lord_image'][0] = null;
//                                }else{
//                                    //calls function that moves resourced documents
//                                    DEBUG::printr('here');die;
//                                    $newCheckInItem['lord_image'] = $this->uploadFile($_FILES, $key2);
//                                }

                                $this->_itemsModel->createCheckInItemTenant($newCheckInItem);

                                //Creating check_out_item
                                $this->_itemsModel->createCheckOutItem($checkOutRoomId, $newCheckInItem['item_id']);
                            }

                        //Else its a current item
                        }else{
                            $tenant_comment_item = (isset($item['tenant_comment']) && !empty($item['tenant_comment'])) ? $item['tenant_comment'] : $roomArray[$key]['items'][$key2]['tenant_comment'];
                            $lord_comment_item = (isset($item['lord_comment']) && !empty($item['lord_comment'])) ? $item['lord_comment'] : $roomArray[$key]['items'][$key2]['lord_comment'];
                            $tenant_approved_item = (isset($item['tenant_approved'])) ? $item['tenant_approved'] : $roomArray[$key]['items'][$key2]['tenant_approved'];
                            $lord_approved_item = (isset($item['lord_approved'])) ? $item['lord_approved'] : $roomArray[$key]['items'][$key2]['lord_approved'];


                            if( $tenant_approved_item == 1 && $lord_approved_item == 1){
                                $status_item = 2;
                            }elseif ( $tenant_approved_item == 1 || $lord_approved_item == 1) {
                                $status_item = 1;
                            }else{
                                $status_item = 0;
                            }
                            $item['id'] = $key2;
                            $item['tenant_comment'] = $tenant_comment_item;
                            $item['lord_comment'] = $lord_comment_item;
                            $item['tenant_approved'] = $tenant_approved_item;
                            $item['lord_approved'] = $lord_approved_item;
                            $item['status'] = $status_item;

                            // We need to handle our image
                            if(!isset($_FILES['item_'.$key2]) || $_FILES['item_'.$key2]['name'] == null) {
                                $item['image'][0] = $roomArray[$key]['items'][$key2]['image'];
                            }else{
                                //remove old file
                                unlink(ROOT . UPLOAD_DIR . '/' . $roomArray[$key]['items'][$key2]['image']);
                                //calls function that moves resourced documents
                                $item['image'] = $this->uploadFile($_FILES, 'item_'.$key2);
                            }

                            // We need to handle our lord_image
                            if(!isset($_FILES['lord_item_'.$key2]) || $_FILES['lord_item_'.$key2]['name'] == null) {
                                $item['lord_image'][0] = $roomArray[$key]['items'][$key2]['lord_image'];
                            }else{
                                //remove old file
                                unlink(ROOT . UPLOAD_DIR . '/' . $roomArray[$key]['items'][$key2]['lord_image']);
                                //calls function that moves resourced documents
                                $item['lord_image'] = $this->uploadFile($_FILES, 'lord_item_'.$key2);
                            }

                            // Need to now update our check_in_item
                            $this->_itemsModel->updateCheckInItem($item);

                            //Creating check_out_item
                            $this->_itemsModel->createCheckOutItem($checkOutRoomId, $roomArray[$key]['items'][$key2]['item_id']);
                        }
                    }
                }

                //removing old files if new one is uploaded
                if (isset($_FILES['meter_image']) && $_FILES['meter_image']['name'] != null) {
                    //remove old file
                    unlink(ROOT . UPLOAD_DIR . '/' . $report[0]['meter_image']);
                }
                if (isset($_FILES['tenant_agreement']) && $_FILES['tenant_agreement']['name'] != null) {
                    //remove old file
                    unlink(ROOT . UPLOAD_DIR . '/' . $report[0]['tenant_agreement']);
                }


                // If both the lord and tenant have approved then email them both
                if ($_POST['lord_approved_check_in'] == 1 && $_POST['tenant_approved_check_in'] == 1){
                    //Email setup
                    $this->_view->data['name'] = $report[0]['lord_firstname'].' '.$report[0]['lord_surname'];
                    $this->_view->data['message'] = 'Both Lead Tenant and Landlord / Agent have approved the check in';
                    $this->_view->data['button_link'] = SITE_URL.'reports/checkin/'.$report[0]['property_id'];
                    $this->_view->data['button_text'] = 'Check In Complete';

                    // Need to create email
                    $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
                    Html::sendEmail($report[0]['lord_email'], 'Checkmate - Check in Complete', SITE_EMAIL, $message);

                    $this->_view->data['name'] = $report[0]['tenant_firstname'].' '.$report[0]['tenant_surname'];
                    // Need to create email
                    $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
                    Html::sendEmail($report[0]['tenant_email'], 'Checkmate - Check in Complete', SITE_EMAIL, $message);

                }else{
                    //Need to email / notify either LL or lead tenant that update has taken place.
                    if($_SESSION['UserCurrentUserID'] != $report[0]['lord_id']){
                        //notification creation
                        $data['text'] = 'Lead Tenant has made changes to a check in. Please review at <a href = "'.SITE_URL.'reports/checkin/'.$report[0]['property_id'].'">Link</a>';
                        $data['user_id'] = $report[0]['lord_id'];
                        $this->_notificationModel->createData($data);

                        //Email setup
                        $this->_view->data['name'] = $report[0]['lord_firstname'].' '.$report[0]['lord_surname'];
                        $this->_view->data['message'] = 'Lead Tenant has made changes to a check in';
                        $this->_view->data['button_link'] = SITE_URL.'reports/checkin/'.$report[0]['property_id'];
                        $this->_view->data['button_text'] = 'Review Check In';

                        // Need to create email
                        $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
                        Html::sendEmail($report[0]['lord_email'], 'Checkmate - Lead Tenant has updated a Check in', SITE_EMAIL, $message);

                    }else{
                        //notification creation
                        $data['text'] = 'Landlord / Letting Agent has made changes to a check in. Please review at <a href = "'.SITE_URL.'reports/checkin/'.$report[0]['property_id'].'">Link</a>';
                        $data['user_id'] = $report[0]['tenant_id'];
                        $this->_notificationModel->createData($data);

                        //Email setup
                        $this->_view->data['name'] = $report[0]['tenant_firstname'].' '.$report[0]['tenant_surname'];
                        $this->_view->data['message'] = 'Landlord / Letting Agent has made changes to a check in';
                        $this->_view->data['button_link'] = SITE_URL.'reports/checkin/'.$report[0]['property_id'];
                        $this->_view->data['button_text'] = 'Review Check In';

                        // Need to create email
                        $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
                        Html::sendEmail($report[0]['tenant_email'], 'Checkmate - Landlord / Letting Agent has updated a Check in', SITE_EMAIL, $message);
                    }
                }


                $this->_view->flash[] = "Check In updated successfully.";
                Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                Url::redirect('users/dashboard');
            }
        }

        // Render the view ($renderBody, $layout, $area)
        $this->_view->render('reports/checkin', 'layout');
    }

    /**
     * PAGE: reports checkout
     * GET: /reports/checkout/:id/
     * This method handles the checkout process for both the lord and lead tenant for a report
     * @param $int property_id
     */
    public function checkout($property_id){
        Auth::checkUserLogin();
        // Set the Page Title ('pageName', 'pageSection', 'areaName')
        $this->_view->pageTitle = array('Reports');
        // Set Page Description
        $this->_view->pageDescription = 'Checkmate Check Out';
        // Set Page Section
        $this->_view->pageSection = 'Reports';
        // Set Page Sub Section
        $this->_view->pageSubSection = 'Reports';

        // Building drop down arrays
        $this->_view->status = explode(',', REPORT);
        $this->_view->meter_type = explode(',', METER);
        $this->_view->key_status = explode(',', KEYS);
        $this->_view->clean_status = explode(',', CLEAN);
        $this->_view->item_status = array('Green', 'Yellow', 'Red');
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
        $report = $this->_model->getCheckOutReportsByPropertyId($property_id);

        if(!isset($report) || empty($report)){
            $this->_view->flash[] = "No report checkout time matches this property";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('users/dashboard');
        }

        // Need to get the users assoicated with this check out and bounce them if they don't belong here.
        $users = $this->_model->getUserReports($report[0]['id']);
        $users = explode(',', $users[0]['user_id']);
        if(!in_array($_SESSION['UserCurrentUserID'], $users)){
            $this->_view->flash[] = "You don't appear to be assoicated with this check out";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('users/dashboard');
        }

        // We need to work if we have already created rooms from template
        if(isset($report[0]['check_out_room_ids']) && !empty($report[0]['check_out_room_ids'])){
            $check_out_room_ids = explode(',', $report[0]['check_out_room_ids']);

            $this->_view->checkInData = array();
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

        // If Form has been submitted process it
        if(!empty($_POST)){

            if(!empty($_POST['cancel'])){
                Url::redirect('users/dashboard');
            }

            // If we have a signature we need to process it.
            if(isset($_POST['signature']) && !empty($_POST['signature'])){
                $data_uri = $_POST['signature'];
                $encoded_image = explode(",", $data_uri)[1];
                $decoded_image = base64_decode($encoded_image);
                $signatureData['check_out_signature'] = date('Y-m-d-H-i-s-').$_SESSION['UserCurrentUserID'].'.png';
                file_put_contents('assets/uploads/'. $signatureData['check_out_signature'], $decoded_image);
                // We now need to update the user_reports with our signature data and timestamp
                $signatureData['check_out_time'] = date('Y-m-d H:i:s');
                $signatureData['user_id'] = $_SESSION['UserCurrentUserID'];
                $signatureData['report_id'] = $report[0]['id'];
                $this->_userModel->updateUserReportCheckOut($signatureData);
            }

            //Need to workout new status of the report
            $tenant_approved_check_out = (isset($_POST['tenant_approved_check_out'])) ? $_POST['tenant_approved_check_out'] : $report[0]['tenant_approved_check_out'];
            $lord_approved_check_out = (isset($_POST['lord_approved_check_out'])) ? $_POST['lord_approved_check_out'] : $report[0]['lord_approved_check_out'];
            $oil_level = (isset($_POST['oil_level']) && !empty($_POST['oil_level'])) ? $_POST['oil_level'] : $report[0]['oil_level'];
            $keys_front_door = (isset($_POST['keys_front_door'])) ? $_POST['keys_front_door'] : $report[0]['keys_front_door'];
            $keys_bedroom_door = (isset($_POST['keys_bedroom_door'])) ? $_POST['keys_bedroom_door'] : $report[0]['keys_bedroom_door'];
            $keys_block_door = (isset($_POST['keys_block_door'])) ? $_POST['keys_block_door'] : $report[0]['keys_block_door'];
            $keys_back_door = (isset($_POST['keys_back_door'])) ? $_POST['keys_back_door'] : $report[0]['keys_back_door'];
            $keys_garage_door = (isset($_POST['keys_garage_door'])) ? $_POST['keys_garage_door'] : $report[0]['keys_garage_door'];
            $keys_other_door = (isset($_POST['keys_other_door'])) ? $_POST['keys_other_door'] : $report[0]['keys_other_door'];
            $fire_extin = (isset($_POST['fire_extin']) && !empty($_POST['fire_extin'])) ? $_POST['fire_extin'] : $report[0]['fire_extin'];
            $fire_blanket = (isset($_POST['fire_blanket']) && !empty($_POST['fire_blanket'])) ? $_POST['fire_blanket'] : $report[0]['fire_blanket'];
            $smoke_alarm = (isset($_POST['smoke_alarm']) && !empty($_POST['smoke_alarm'])) ? $_POST['smoke_alarm'] : $report[0]['smoke_alarm'];

            if( $tenant_approved_check_out == 1 && $lord_approved_check_out == 1){
                $status = 2;
            }elseif ( $tenant_approved_check_out == 1 || $lord_approved_check_out == 1) {
                $status = 1;
            }else{
                $status = 0;
            }
            $_POST['status'] = $status;
            $_POST['tenant_approved_check_in'] = $report[0]['tenant_approved_check_in'];
            $_POST['lord_approved_check_in'] = $report[0]['lord_approved_check_in'];
            $_POST['tenant_approved_check_out'] = $tenant_approved_check_out;
            $_POST['lord_approved_check_out'] = $lord_approved_check_out;
            $_POST['oil_level'] = $oil_level;
            $_POST['keys_front_door'] = $keys_front_door;
            $_POST['keys_bedroom_door'] = $keys_bedroom_door;
            $_POST['keys_block_door'] = $keys_block_door;
            $_POST['keys_back_door'] = $keys_back_door;
            $_POST['keys_garage_door'] = $keys_garage_door;
            $_POST['keys_other_door'] = $keys_other_door;
            $_POST['fire_extin'] = $fire_extin;
            $_POST['fire_blanket'] = $fire_blanket;
            $_POST['smoke_alarm'] = $smoke_alarm;

            $_POST['id'] = $report[0]['id'];

            // Uploading meter image
            if(!isset($_FILES['meter_image']) || $_FILES['meter_image']['name'] == null) {
                $_POST['meter_image'][0] = $report[0]['meter_image'];
            }else{
                //calls function that moves resourced documents
                $_POST['meter_image'] = $this->uploadFile($_FILES, 'meter_image');
            }

            // Uploading tenant Agreement
            if(!isset($_FILES['tenant_agreement']) || $_FILES['tenant_agreement']['name'] == null) {
                $_POST['tenant_agreement'][0] = $report[0]['tenant_agreement'];
            }else{
                //calls function that moves resourced documents
                $_POST['tenant_agreement'] = $this->uploadFile($_FILES, 'tenant_agreement');
            }

            foreach($this->_view->checkOutData as $key => $roomData){
                $roomArray[$roomData['id']] = $roomData;
                foreach($roomData['items'] as $key2 => $item){
                    $roomArray[$roomData['id']]['items'][$item['id']] = $item;
                    unset($roomArray[$roomData['id']]['items'][$key2]);
                }
            }

            $rooms = $_POST['rooms'];
            unset($_POST['rooms']);

            // Update report details
            $updateData = $this->_model->updateData($_POST);

            if(isset($updateData['error']) && $updateData['error'] != null){
                foreach($updateData['error'] as $key => $error) {
                    $this->_view->error[$key] = $error;
                }
            } else {
                // We now need to update our rooms
                foreach($rooms as $key => $room){
                    $tenant_comment = (isset($rooms[$key]['tenant_comment']) && !empty($rooms[$key]['tenant_comment'])) ? $rooms[$key]['tenant_comment'] : $roomArray[$key]['tenant_comment'];
                    $lord_comment = (isset($rooms[$key]['lord_comment']) && !empty($rooms[$key]['lord_comment'])) ? $rooms[$key]['lord_comment'] : $roomArray[$key]['lord_comment'];
                    $clean = (isset($rooms[$key]['clean'])) ? $rooms[$key]['clean'] : $roomArray[$key]['clean'];
                    $room['lord_comment'] = $lord_comment;
                    $room['tenant_comment'] = $tenant_comment;
                    $room['clean'] = $clean;
                    $room['id'] = $key;
                    $this->_roomsModel->updateCheckOutRoom($room);

                    // Now we need to update our items and possibly create new ones....
                    foreach($room['items'] as $key2 => $item){
                        $tenant_comment_item = (isset($item['tenant_comment']) && !empty($item['tenant_comment'])) ? $item['tenant_comment'] : $roomArray[$key]['items'][$key2]['tenant_comment'];
                        $lord_comment_item = (isset($item['lord_comment']) && !empty($item['lord_comment'])) ? $item['lord_comment'] : $roomArray[$key]['items'][$key2]['lord_comment'];
                        $tenant_approved_item = (isset($item['tenant_approved'])) ? $item['tenant_approved'] : $roomArray[$key]['items'][$key2]['tenant_approved'];
                        $lord_approved_item = (isset($item['lord_approved'])) ? $item['lord_approved'] : $roomArray[$key]['items'][$key2]['lord_approved'];

                        if( $tenant_approved_item == 1 && $lord_approved_item == 1){
                            $status_item = 2;
                        }elseif ( $tenant_approved_item == 1 || $lord_approved_item == 1) {
                            $status_item = 1;
                        }else{
                            $status_item = 0;
                        }
                        $item['id'] = $key2;
                        $item['tenant_comment'] = $tenant_comment_item;
                        $item['lord_comment'] = $lord_comment_item;
                        $item['tenant_approved'] = $tenant_approved_item;
                        $item['lord_approved'] = $lord_approved_item;
                        $item['status'] = $status_item;

                        // We need to handle our image
                        if(!isset($_FILES['item_'.$key2]) || $_FILES['item_'.$key2]['name'] == null) {
                            $item['image'][0] = $roomArray[$key]['items'][$key2]['image'];
                        }else{
                            //remove old file
                            unlink(ROOT . UPLOAD_DIR . '/' . $roomArray[$key]['items'][$key2]['image']);
                            //calls function that moves resourced documents
                            $item['image'] = $this->uploadFile($_FILES, 'item_'.$key2);
                        }

                        // We need to handle our lord_image
                        if(!isset($_FILES['lord_item_'.$key2]) || $_FILES['lord_item_'.$key2]['name'] == null) {
                            $item['lord_image'][0] = $roomArray[$key]['items'][$key2]['lord_image'];
                        }else{
                            //remove old file
                            unlink(ROOT . UPLOAD_DIR . '/' . $roomArray[$key]['items'][$key2]['lord_image']);
                            //calls function that moves resourced documents
                            $item['lord_image'] = $this->uploadFile($_FILES, 'lord_item_'.$key2);
                        }

                        // Need to now update our check_in_item
                        $this->_itemsModel->updateCheckOutItem($item);
                    }
                }

                //removing old files if new one is uploaded
                if (isset($_FILES['meter_image']) && $_FILES['meter_image']['name'] != null) {
                    //remove old file
                    unlink(ROOT . UPLOAD_DIR . '/' . $report[0]['meter_image']);
                }
                if (isset($_FILES['tenant_agreement']) && $_FILES['tenant_agreement']['name'] != null) {
                    //remove old file
                    unlink(ROOT . UPLOAD_DIR . '/' . $report[0]['tenant_agreement']);
                }

                // If both the lord and tenant have approved then email them both
                if ($_POST['lord_approved_check_out'] == 1 && $_POST['tenant_approved_check_out'] == 1){
                    //Email setup
                    $this->_view->data['name'] = $report[0]['lord_firstname'].' '.$report[0]['lord_surname'];
                    $this->_view->data['message'] = 'Both Lead Tenant and Landlord / Agent have approved the check out';
                    $this->_view->data['button_link'] = SITE_URL.'reports/checkout/'.$report[0]['property_id'];
                    $this->_view->data['button_text'] = 'Check In Complete';

                    // Need to create email
                    $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
                    Html::sendEmail($report[0]['lord_email'], 'Checkmate - Check out Complete', SITE_EMAIL, $message);

                    $this->_view->data['name'] = $report[0]['tenant_firstname'].' '.$report[0]['tenant_surname'];
                    // Need to create email
                    $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
                    Html::sendEmail($report[0]['tenant_email'], 'Checkmate - Check out Complete', SITE_EMAIL, $message);

                }else{
                    //Need to email / notify either LL or lead tenant that update has taken place.
                    if ($_SESSION['UserCurrentUserID'] != $report[0]['lord_id']) {
                        //notification creation
                        $data['text'] = 'Lead Tenant has made changes to a check out. Please review at <a href = "' . SITE_URL . 'reports/checkout/' . $report[0]['property_id'] . '">Link</a>';
                        $data['user_id'] = $report[0]['lord_id'];
                        $this->_notificationModel->createData($data);

                        //Email setup
                        $this->_view->data['name'] = $report[0]['lord_firstname'] . ' ' . $report[0]['lord_surname'];
                        $this->_view->data['message'] = 'Lead Tenant has made changes to a check out';
                        $this->_view->data['button_link'] = SITE_URL . 'reports/checkout/' . $report[0]['property_id'];
                        $this->_view->data['button_text'] = 'Review Check Out';

                        // Need to create email
                        $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
                        Html::sendEmail($report[0]['lord_email'], 'Checkmate - Lead Tenant has updated a Check Out', SITE_EMAIL, $message);

                    } else {
                        //notification creation
                        $data['text'] = 'Landlord / Letting Agent has made changes to a check out. Please review at <a href = "' . SITE_URL . 'reports/checkout/' . $report[0]['property_id'] . '">Link</a>';
                        $data['user_id'] = $report[0]['tenant_id'];
                        $this->_notificationModel->createData($data);

                        //Email setup
                        $this->_view->data['name'] = $report[0]['tenant_firstname'] . ' ' . $report[0]['tenant_surname'];
                        $this->_view->data['message'] = 'Landlord / Letting Agent has made changes to a check out';
                        $this->_view->data['button_link'] = SITE_URL . 'reports/checkout/' . $report[0]['property_id'];
                        $this->_view->data['button_text'] = 'Review Check Out';

                        // Need to create email
                        $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
                        Html::sendEmail($report[0]['tenant_email'], 'Checkmate - Landlord / Letting Agent has updated a Check out', SITE_EMAIL, $message);
                    }
                }

                $this->_view->flash[] = "Checkout updated successfully.";
                Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                Url::redirect('users/dashboard');
            }
        }

        // Render the view ($renderBody, $layout, $area)
        $this->_view->render('reports/checkout', 'layout');
    }

    /**
     * PAGE: reports image download
     * GET: /backoffice/reports/download/:id/:type
     * This method handles the download image action.
     */
    public function download($id, $type){
        Auth::checkUserLogin();
        $this->_itemsModel = $this->loadModel('items');

        if(!empty($id)) {
            switch ($type) {
                case 'meter':
                    $selectedData = $this->_model->selectDataByID($id);
                    $image = $selectedData[0]['meter_image'];
                    break;
                case 'tenant':
                    $selectedData = $this->_model->selectDataByID($id);
                    $image = $selectedData[0]['tenant_agreement'];
                    break;
                case 'item':
                    //Need to search for checkinItem
                    $checkinItem = $this->_itemsModel->selectCheckInItemsByID($id);
                    $image = $checkinItem[0]['image'];
                    break;
                case 'lord_item':
                    //Need to search for checkinItem
                    $checkinItem = $this->_itemsModel->selectCheckInItemsByID($id);
                    $image = $checkinItem[0]['lord_image'];
                    break;
                case 'checkoutItem':
                    //Need to search for checkoutItem
                    $checkOutItem = $this->_itemsModel->selectCheckOutItemsByID($id);
                    $image = $checkOutItem[0]['image'];
                    break;
                case 'checkoutLordItem':
                    //Need to search for checkoutItem
                    $checkOutItem = $this->_itemsModel->selectCheckOutItemsByID($id);
                    $image = $checkOutItem[0]['lord_image'];
                    break;
            }

            if (isset($image) && !empty($image)) {
                header('Content-Description: File Transfer');
                //header('Content-Type: '.$selectedData[0]['type']);
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
                Url::redirect('users/dashboard/');
            }
        }else{
            $this->_view->flash[] = "No ID was provided";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('users/dashboard/');
        }
    }

    /**
     * UploadFile
     * This method handles the upload and moving of docs on backoffice
     * @param array $files is the $_FILES
     */
    public function uploadFile($files, $name){
        require_once(ROOT.'system/helpers/Upload.php');
        // upload file
        try {
            if(isset($files[$name])){
                $file = new Ps2_Upload(ROOT.UPLOAD_DIR.'/', $name, true);
                if($name == 'tenant_agreement'){
                    $file->addPermittedTypes(array(
                            'text/plain', 'application/pdf', 'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                        )
                    );
                }else{
                    $file->addPermittedTypes(array(
                            'image/png', 'image/jpeg', 'image/gif',
                        )
                    );
                }
                $file->setMaxSize(MAX_FILE_SIZE);
                $file->move();
                return $file->getFilenames();
            }
        } catch (Exception $e) {
            return $this->_view->error[] = $e->getMessage();
        }
    }

    /**
     * PAGE: Report Download
     * GET: /reports/report-download/:id
     * This method handles downloading of PDF based on reports data
     * @param int $id
     */
    public function reportDownload($id = null){
        Auth::checkUserLogin();

        if(!empty($id)){
            $selectDataByID = $this->_model->selectDataByID($id);
            if(isset($selectDataByID[0]['id']) && !empty($selectDataByID[0]['id'])){
                $this->_view->stored_data = $selectDataByID[0];

            }else{
                $this->_view->flash[] = "No Rooms matches this id";
                Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
                Url::redirect('/users/dashboard');
            }
        }else{
            $this->_view->flash[] = "No ID provided for Rooms";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('/users/dashboard');
        }

        // Building drop down arrays
        //$this->_view->status = explode(',', REPORT);
        $this->_view->status = array('Green', 'Yellow', 'Red');
        $this->_view->meter_type = explode(',', METER);
        $this->_view->key_status = explode(',', KEYS);
        $this->_view->clean_status = explode(',', CLEAN);

        $this->_roomsModel = $this->loadModel('rooms');
        $this->_itemsModel = $this->loadModel('items');
        $this->_userModel = $this->loadModel('users');

        $users = $this->_userModel->getAllByArray($selectDataByID[0]['user_ids'], $id);
        foreach ($users as $key => $user) {
            $users[$user['id']] = $user;
            unset($users[$key]);
        }

        // setting up vars so we can decide if we want to show checkout data
        $current_time = strtotime(date('Y-m-d'));
        $checkout_time = strtotime($selectDataByID[0]['check_out']);
        $week = 60 * 60 * 24 * 7;
        $checkout_time = $checkout_time - $week;


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
        $mpdf->defaultfooterline = 0;
        $mpdf->setFooter('<img class = "left" src = "'.ROOT.'assets/images/logo-small.png" width="30px;" height="30px"> <p style = "color:grey;">Page {PAGENO} of {nb}</p>');
        $stylesheet = file_get_contents(ROOT.'/app/areas/backoffice/assets/css/pdf.css');
        $mpdf->WriteHTML($stylesheet,1);

        $mpdf->WriteHTML('<div class = "white">');
            $mpdf->WriteHTML('<div class = "white"');
                $mpdf->WriteHTML('<img class = "logo" src = "'.ROOT.'assets/images/logo.png">');
                $mpdf->WriteHTML('<p class = "center" style ="padding-top:12px">'.SITE_URL.'</p>');
            $mpdf->WriteHTML('</div>');

            $mpdf->WriteHTML('<div>');
                $mpdf->WriteHTML('<h3 class = "center blue" style ="padding-top:30px; padding-bottom:30px;">Report for property '.$this->_view->stored_data['property_title'].'</h3>');
            $mpdf->WriteHTML('</div>');

            $mpdf->WriteHTML('<table style = "width:100%;">');
                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Date:');
                    $mpdf->WriteHTML('</th>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        $mpdf->WriteHTML(date('d/m/Y'));
                    $mpdf->WriteHTML('</td>');
                $mpdf->WriteHTML('</tr>');
            $mpdf->WriteHTML('</table>');

            $mpdf->WriteHTML('<div class = "underline" style="padding-bottom:25px; clear:both;"></div>');


            $mpdf->WriteHTML('<table style = "width:100%;">');
                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Property Image:');
                    $mpdf->WriteHTML('</th>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        if(isset($this->_view->stored_data['property_image']) && !empty($this->_view->stored_data['property_image'])) {
                            $mpdf->WriteHTML('<img src = "' . ROOT . 'assets/uploads/' . $this->_view->stored_data['property_image'] . '" width="240px;">');
                        }
                    $mpdf->WriteHTML('</td>');
                $mpdf->WriteHTML('</tr>');
            $mpdf->WriteHTML('</table>');

            $mpdf->WriteHTML('<div class = "underline" style="padding-bottom:25px; clear:both;"></div>');


            $mpdf->WriteHTML('<table style = "width:100%;">');
                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Address:');
                    $mpdf->WriteHTML('</th>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        $mpdf->WriteHTML($this->_view->stored_data['property_number']);
                        $mpdf->WriteHTML('<br/>');
                        $mpdf->WriteHTML($this->_view->stored_data['property_address_1']);
                        $mpdf->WriteHTML('<br/>');
                        $mpdf->WriteHTML($this->_view->stored_data['property_address_2']);
                        $mpdf->WriteHTML('<br/>');
                        $mpdf->WriteHTML($this->_view->stored_data['property_address_3']);
                        $mpdf->WriteHTML('<br/>');
                        $mpdf->WriteHTML($this->_view->stored_data['property_address_4']);
                        $mpdf->WriteHTML('<br/>');
                        $mpdf->WriteHTML($this->_view->stored_data['property_postcode']);
                    $mpdf->WriteHTML('</td>');
                $mpdf->WriteHTML('</tr>');
            $mpdf->WriteHTML('</table>');

            $mpdf->WriteHTML('<div class = "underline" style="padding-bottom:25px; clear:both;"></div>');


            $mpdf->WriteHTML('<table style = "width:100%;">');
                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Land Lord / Letting Agent:');
                    $mpdf->WriteHTML('</th>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        $mpdf->WriteHTML($this->_view->stored_data['lord_firstname'].' '.$this->_view->stored_data['lord_surname'].' ('.$this->_view->stored_data['lord_email'].')');
                    $mpdf->WriteHTML('</td>');
                $mpdf->WriteHTML('</tr>');
            $mpdf->WriteHTML('</table>');

            $mpdf->WriteHTML('<div class = "underline" style="padding-bottom:25px; clear:both;"></div>');


            $mpdf->WriteHTML('<table style = "width:100%;">');
                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Lead Tenant');
                    $mpdf->WriteHTML('</th>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        $mpdf->WriteHTML($this->_view->stored_data['tenant_firstname'].' '.$this->_view->stored_data['tenant_surname'].' ('.$this->_view->stored_data['tenant_email'].')');
                    $mpdf->WriteHTML('</td>');
                $mpdf->WriteHTML('</tr>');
            $mpdf->WriteHTML('</table>');

            $mpdf->WriteHTML('<div class = "underline" style="padding-bottom:25px; clear:both;"></div>');


            foreach ($users as $key => $user) {
                //If the user is not the lead tenant AND the lland lord
                if($user['id'] != $this->_view->stored_data['lord_id'] && $user['id'] != $this->_view->stored_data['lead_tenant_id']){
                    $mpdf->WriteHTML('<table style = "width:100%;">');
                        $mpdf->WriteHTML('<tr>');
                            $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                                $mpdf->WriteHTML('Other Tenant');
                            $mpdf->WriteHTML('</th>');

                            $mpdf->WriteHTML('<td class = "align-right">');
                                $mpdf->WriteHTML($user['firstname'].' '.$user['surname'].' ('.$user['email'].')');
                            $mpdf->WriteHTML('</td>');
                        $mpdf->WriteHTML('</tr>');
                    $mpdf->WriteHTML('</table>');

                    $mpdf->WriteHTML('<div class = "underline" style="padding-bottom:25px; clear:both;"></div>');

                }
            }

            $mpdf->WriteHTML('<table style = "width:100%;">');
                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Check In');
                    $mpdf->WriteHTML('</th>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        $mpdf->WriteHTML(date("F j, Y", strtotime($this->_view->stored_data['check_in'])));
                    $mpdf->WriteHTML('</td>');
                $mpdf->WriteHTML('</tr>');
            $mpdf->WriteHTML('</table>');

            $mpdf->WriteHTML('<div class = "underline" style="padding-bottom:25px; clear:both;"></div>');


            $mpdf->WriteHTML('<table style = "width:100%;">');
                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Check Out');
                    $mpdf->WriteHTML('</th>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        $mpdf->WriteHTML(date("F j, Y", strtotime($this->_view->stored_data['check_out'])));
                    $mpdf->WriteHTML('</td>');
                $mpdf->WriteHTML('</tr>');
            $mpdf->WriteHTML('</table>');

            $mpdf->WriteHTML('<div class = "underline" style="padding-bottom:25px; clear:both;"></div>');


            $mpdf->WriteHTML('<pagebreak />');

            $mpdf->WriteHTML('<div style="margin-bottom:25px; padding:4px 12px" class = "background-main color-offset">Meter</div>');

            $mpdf->WriteHTML('<table style = "width:100%; margin-bottom:25px;">');
                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<td class = "blue bold align-left" style = "width:30%;" rowspan = "2">');
                        if(isset($this->_view->stored_data['meter_image']) && !empty($this->_view->stored_data['meter_image'])) {
                            $mpdf->WriteHTML('<img src = "' . ROOT . 'assets/uploads/' . $this->_view->stored_data['meter_image'] . '" width="160px;">');
                        }
                    $mpdf->WriteHTML('</td>');

                    $mpdf->WriteHTML('<td class = "blue bold align-left" style = "width:40%;">');
                        $mpdf->WriteHTML('Meter Type');
                    $mpdf->WriteHTML('</td>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        $mpdf->WriteHTML($this->_view->meter_type[$this->_view->stored_data['meter_type']]);
                    $mpdf->WriteHTML('</td>');
                $mpdf->WriteHTML('</tr>');

                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<td class = "blue bold align-left" style = "width:40%;">');
                        $mpdf->WriteHTML('Meter Reading');
                    $mpdf->WriteHTML('</td>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        $mpdf->WriteHTML($this->_view->stored_data['meter_reading'].' '.$this->_view->stored_data['meter_measurement']);
                    $mpdf->WriteHTML('</td>');
                $mpdf->WriteHTML('</tr>');

            $mpdf->WriteHTML('</table>');

            // $mpdf->WriteHTML('<div class = "underline" style="padding:25px 0; clear:both;"></div>');

            $mpdf->WriteHTML('<div style="margin-bottom:25px; padding:4px 12px" class = "background-main color-offset">Other</div>');

            $mpdf->WriteHTML('<table style = "width:100%;">');
                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Oil Level');
                    $mpdf->WriteHTML('</th>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        $mpdf->WriteHTML($this->_view->stored_data['oil_level']);
                    $mpdf->WriteHTML('</td>');
                $mpdf->WriteHTML('</tr>');

                /*Keys*/
                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Keys Front Door');
                    $mpdf->WriteHTML('</th>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        $mpdf->WriteHTML($this->_view->stored_data['keys_front_door']);
                    $mpdf->WriteHTML('</td>');
                $mpdf->WriteHTML('</tr>');

                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Keys Bedroom Door');
                    $mpdf->WriteHTML('</th>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        $mpdf->WriteHTML($this->_view->stored_data['keys_bedroom_door']);
                    $mpdf->WriteHTML('</td>');
                $mpdf->WriteHTML('</tr>');

                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Keys Block Door');
                    $mpdf->WriteHTML('</th>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        $mpdf->WriteHTML($this->_view->stored_data['keys_block_door']);
                    $mpdf->WriteHTML('</td>');
                $mpdf->WriteHTML('</tr>');

                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Keys Back Door');
                    $mpdf->WriteHTML('</th>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        $mpdf->WriteHTML($this->_view->stored_data['keys_back_door']);
                    $mpdf->WriteHTML('</td>');
                $mpdf->WriteHTML('</tr>');

                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Keys Garage Door');
                    $mpdf->WriteHTML('</th>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        $mpdf->WriteHTML($this->_view->stored_data['keys_garage_door']);
                    $mpdf->WriteHTML('</td>');
                $mpdf->WriteHTML('</tr>');

                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Keys Other Door');
                    $mpdf->WriteHTML('</th>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        $mpdf->WriteHTML($this->_view->stored_data['keys_other_door']);
                    $mpdf->WriteHTML('</td>');
                $mpdf->WriteHTML('</tr>');

                /*End of Keys*/

                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Fire Extinguishers');
                    $mpdf->WriteHTML('</th>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        $mpdf->WriteHTML($this->_view->stored_data['fire_extin']);
                    $mpdf->WriteHTML('</td>');
                $mpdf->WriteHTML('</tr>');

                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Fire Blankets');
                    $mpdf->WriteHTML('</th>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        $mpdf->WriteHTML($this->_view->stored_data['fire_blanket']);
                    $mpdf->WriteHTML('</td>');
                $mpdf->WriteHTML('</tr>');

                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Smoke Alarms');
                    $mpdf->WriteHTML('</th>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        $mpdf->WriteHTML($this->_view->stored_data['smoke_alarm']);
                    $mpdf->WriteHTML('</td>');
                $mpdf->WriteHTML('</tr>');
            $mpdf->WriteHTML('</table>');

            //New colour key section
            $mpdf->WriteHTML('<table class = "colour-key">');
            $mpdf->WriteHTML('<tr>');
            $mpdf->WriteHTML('<td class = "red">');
            $mpdf->WriteHTML('Red');
            $mpdf->WriteHTML('</td>');
            $mpdf->WriteHTML('<td class = "red">');
            $mpdf->WriteHTML('Red means neither the Landlord nor Lead Tenant have approved.');
            $mpdf->WriteHTML('</td>');

            $mpdf->WriteHTML('<tr>');

            $mpdf->WriteHTML('<tr>');
            $mpdf->WriteHTML('<td class = "amber">');
            $mpdf->WriteHTML('Amber');
            $mpdf->WriteHTML('</td>');
            $mpdf->WriteHTML('<td class = "amber">');
            $mpdf->WriteHTML('Amber means either the Landlord or Lead Tenant have approved.');
            $mpdf->WriteHTML('</td>');

            $mpdf->WriteHTML('<tr>');

            $mpdf->WriteHTML('<tr>');
            $mpdf->WriteHTML('<td class = "green">');
            $mpdf->WriteHTML('Green');
            $mpdf->WriteHTML('</td>');
            $mpdf->WriteHTML('<td class = "green">');
            $mpdf->WriteHTML('Green means both the Landlord and Lead Tenant have approved.');
            $mpdf->WriteHTML('</td>');
            $mpdf->WriteHTML('<tr>');
            $mpdf->WriteHTML('</table>');
            //END OF NEW KEY COLOUR SECTION

        $mpdf->WriteHTML('<pagebreak />');


        if(isset($this->_view->checkInData) && !empty($this->_view->checkInData)){
            $mpdf->WriteHTML('<div>');
                $mpdf->WriteHTML('<h3 class = "center blue" style ="padding-top:30px; padding-bottom:30px;">Check In Report</h3>');
            $mpdf->WriteHTML('</div>');

            $mpdf->WriteHTML('<table style = "width:100%;">');
                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Check In Status:');
                    $mpdf->WriteHTML('</th>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        $mpdf->WriteHTML($this->_view->status[$this->_view->stored_data['tenant_approved_check_in'] + $this->_view->stored_data['lord_approved_check_in']]);
                    $mpdf->WriteHTML('</td>');
                $mpdf->WriteHTML('</tr>');
            $mpdf->WriteHTML('</table>');

            $mpdf->WriteHTML('<div class = "underline" style="padding-bottom:25px; clear:both;"></div>');

            $mpdf->WriteHTML('<table style = "width:100%;">');
                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Check In Date:');
                    $mpdf->WriteHTML('</th>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        $mpdf->WriteHTML(date("F j, Y", strtotime($this->_view->stored_data['check_in'])));
                    $mpdf->WriteHTML('</td>');
                $mpdf->WriteHTML('</tr>');
            $mpdf->WriteHTML('</table>');

            $mpdf->WriteHTML('<div class = "underline" style="padding-bottom:25px; clear:both;"></div>');

            $mpdf->WriteHTML('<table style = "width:100%;">');
                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Lead Tenant Approval');
                    $mpdf->WriteHTML('</th>');

                     $mpdf->WriteHTML('<td class = "align-right">');
                        if($this->_view->stored_data['tenant_approved_check_in'] == 1){
                            if(isset($users[$this->_view->stored_data['lead_tenant_id']]['check_in_signature']) && !empty($users[$this->_view->stored_data['lead_tenant_id']]['check_in_signature'])) {
                                $mpdf->WriteHTML('<img src = "' . ROOT . 'assets/uploads/' . $users[$this->_view->stored_data['lead_tenant_id']]['check_in_signature'] . '" width="160px;">');
                            }
                        }
                    $mpdf->WriteHTML('</td>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        if($this->_view->stored_data['tenant_approved_check_in'] == 1){
                            $mpdf->WriteHTML(date("F j, Y", strtotime($users[$this->_view->stored_data['lead_tenant_id']]['check_in_time'])));
                        }else{
                            //$mpdf->WriteHTML('No approval / signature given');
                        }
                    $mpdf->WriteHTML('</td>');

                $mpdf->WriteHTML('</tr>');
            $mpdf->WriteHTML('</table>');

            $mpdf->WriteHTML('<div class = "underline" style="padding-bottom:25px; clear:both;"></div>');

            $mpdf->WriteHTML('<table style = "width:100%;">');
                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Landlord / Letting Agent Approval:');
                    $mpdf->WriteHTML('</th>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        if($this->_view->stored_data['lord_approved_check_in'] == 1){
                            if(isset($users[$this->_view->stored_data['lord_id']]['check_in_signature']) && !empty($users[$this->_view->stored_data['lord_id']]['check_in_signature'])) {
                                $mpdf->WriteHTML('<img src = "' . ROOT . 'assets/uploads/' . $users[$this->_view->stored_data['lord_id']]['check_in_signature'] . '" width="160px;">');
                            }
                        }else{
                        }
                    $mpdf->WriteHTML('</td>');


                    $mpdf->WriteHTML('<td class = "align-right">');
                        if($this->_view->stored_data['lord_approved_check_in'] == 1){
                            $mpdf->WriteHTML(date("F j, Y", strtotime($users[$this->_view->stored_data['lord_id']]['check_in_time'])));
                        }else{
                            //$mpdf->WriteHTML('No approval / signature given');
                        }
                    $mpdf->WriteHTML('</td>');

                $mpdf->WriteHTML('</tr>');
            $mpdf->WriteHTML('</table>');

            $mpdf->WriteHTML('<div class = "underline" style="padding-bottom:25px; clear:both;"></div>');

            foreach ($users as $key => $user) {
                if($user['id'] != $this->_view->stored_data['lord_id'] && $user['id'] != $this->_view->stored_data['lead_tenant_id']){
                    $mpdf->WriteHTML('<table style = "width:100%;">');
                        $mpdf->WriteHTML('<tr>');
                            $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                                $mpdf->WriteHTML('Other Tenant '.$user['firstname'].' '.$user['surname']);
                            $mpdf->WriteHTML('</th>');

                            $mpdf->WriteHTML('<td class = "align-right">');
                                if(isset($user['check_in_signature']) && !empty($user['check_in_signature'])){
                                    if(isset($users[$user['id']]['check_in_signature']) && !empty($users[$user['id']]['check_in_signature'])) {
                                        $mpdf->WriteHTML('<img src = "' . ROOT . 'assets/uploads/' . $users[$user['id']]['check_in_signature'] . '" width="160px;">');
                                    }
                                }else{
                                }
                            $mpdf->WriteHTML('</td>');


                            $mpdf->WriteHTML('<td class = "align-right">');
                                if(isset($user['check_in_signature']) && !empty($user['check_in_signature'])){
                                    $mpdf->WriteHTML(date("F j, Y", strtotime($users[$user['id']]['check_in_time'])));
                                }else{
                                    //$mpdf->WriteHTML('No approval / signature given');
                                }
                            $mpdf->WriteHTML('</td>');

                        $mpdf->WriteHTML('</tr>');
                    $mpdf->WriteHTML('</table>');

                    $mpdf->WriteHTML('<div class = "underline" style="padding-bottom:25px; clear:both;"></div>');
                }
            }

            foreach($this->_view->checkInData as $key => $room){
                $mpdf->WriteHTML('<pagebreak />');

                $mpdf->WriteHTML('<div style="margin-bottom:25px; padding:4px 12px" class = "background-main color-offset">'.$room['name'].'</div>');

                $mpdf->WriteHTML('<table style = "width:100%;">');
                    $mpdf->WriteHTML('<tr>');
                        $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                            $mpdf->WriteHTML('Clean Status');
                        $mpdf->WriteHTML('</th>');

                        $mpdf->WriteHTML('<td class = "align-right">');
                            $mpdf->WriteHTML($this->_view->clean_status[$room['clean']]);
                        $mpdf->WriteHTML('</td>');
                    $mpdf->WriteHTML('</tr>');

                    $mpdf->WriteHTML('<tr>');
                        $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                            $mpdf->WriteHTML('Tenant Comment');
                        $mpdf->WriteHTML('</th>');

                        $mpdf->WriteHTML('<td class = "align-right">');
                            $mpdf->WriteHTML($room['tenant_comment']);
                        $mpdf->WriteHTML('</td>');
                    $mpdf->WriteHTML('</tr>');

                    $mpdf->WriteHTML('<tr>');
                        $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                            $mpdf->WriteHTML('LandLord Comment');
                        $mpdf->WriteHTML('</th>');

                        $mpdf->WriteHTML('<td class = "align-right">');
                            $mpdf->WriteHTML($room['lord_comment']);
                        $mpdf->WriteHTML('</td>');
                    $mpdf->WriteHTML('</tr>');
                $mpdf->WriteHTML('</table>');

                $mpdf->WriteHTML('<div class = "underline" style="padding:25px; clear:both;"></div>');


                if(isset($room['items']) && !empty($room['items'])){
                    foreach($room['items'] as $key2 => $item){
                        $mpdf->WriteHTML('<div style="margin-bottom:25px; padding:4px 12px" class = "background-secondary color-offset">'.$item['name'].'</div>');

                        $mpdf->WriteHTML('<table style = "width:100%; padding-bottom:20px">');
                            $mpdf->WriteHTML('<tr>');
                                $mpdf->WriteHTML('<td class = "blue bold align-left" style = "width:20%;">');
                                    $mpdf->WriteHTML('Tenant Item Image');
                                $mpdf->WriteHTML('</td>');

                                $mpdf->WriteHTML('<td class = "blue bold align-right" style = "width:20%;">');
                                    if(isset($item['image']) && !empty($item['image'])) {
                                        $mpdf->WriteHTML('<img src = "' . ROOT . 'assets/uploads/' . $item['image'] . '" width="160px;">');
                                    }
                                $mpdf->WriteHTML('</td>');
                            $mpdf->WriteHTML('</tr>');

                            $mpdf->WriteHTML('<tr>');
                                $mpdf->WriteHTML('<td class = "blue bold align-left" style = "width:20%;">');
                                    $mpdf->WriteHTML('LL / Agent Item Image');
                                $mpdf->WriteHTML('</td>');

                                $mpdf->WriteHTML('<td class = "blue bold align-right" style = "width:20%;">');
                                    if(isset($item['lord_image']) && !empty($item['lord_image'])) {
                                        $mpdf->WriteHTML('<img src = "' . ROOT . 'assets/uploads/' . $item['lord_image'] . '" width="160px;">');
                                    }
                                $mpdf->WriteHTML('</td>');
                            $mpdf->WriteHTML('</tr>');


                            $mpdf->WriteHTML('<tr>');
                                $mpdf->WriteHTML('<td class = "blue bold align-left">');
                                    $mpdf->WriteHTML('Item Status');
                                $mpdf->WriteHTML('</td>');

                                $mpdf->WriteHTML('<td class = "align-right">');
                                        $mpdf->WriteHTML($this->_view->status[max($item['tenant_approved'], $item['lord_approved'])]);
                                $mpdf->WriteHTML('</td>');
                            $mpdf->WriteHTML('</tr>');

                            $mpdf->WriteHTML('<tr>');
                                $mpdf->WriteHTML('<td class = "blue bold align-left">');
                                    $mpdf->WriteHTML('Tenant Comment');
                                $mpdf->WriteHTML('</td>');


                                $mpdf->WriteHTML('<td class = "align-right">');
                                        $mpdf->WriteHTML($item['tenant_comment']);
                                $mpdf->WriteHTML('</td>');
                            $mpdf->WriteHTML('</tr>');

                             $mpdf->WriteHTML('<tr>');
                                $mpdf->WriteHTML('<td class = "blue bold align-left">');
                                    $mpdf->WriteHTML('Landlord Comment');
                                $mpdf->WriteHTML('</td>');


                                $mpdf->WriteHTML('<td class = "align-right">');
                                        $mpdf->WriteHTML($item['lord_comment']);
                                $mpdf->WriteHTML('</td>');
                            $mpdf->WriteHTML('</tr>');
                        $mpdf->WriteHTML('</table>');

                    }
                }
            }
        }
        if(isset($this->_view->checkOutData) && !empty($this->_view->checkOutData) && $current_time >= $checkout_time){
            $mpdf->WriteHTML('<pagebreak />');

            $mpdf->WriteHTML('<div>');
                $mpdf->WriteHTML('<h3 class = "center blue" style ="padding-top:30px; padding-bottom:30px;">Check Out Report</h3>');
            $mpdf->WriteHTML('</div>');

            $mpdf->WriteHTML('<table style = "width:100%;">');
                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Check Out Status:');
                    $mpdf->WriteHTML('</th>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        $mpdf->WriteHTML($this->_view->status[$this->_view->stored_data['tenant_approved_check_out'] + $this->_view->stored_data['lord_approved_check_out']]);
                    $mpdf->WriteHTML('</td>');
                $mpdf->WriteHTML('</tr>');
            $mpdf->WriteHTML('</table>');

            $mpdf->WriteHTML('<div class = "underline" style="padding-bottom:25px; clear:both;"></div>');

            $mpdf->WriteHTML('<table style = "width:100%;">');
                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Check Out Date:');
                    $mpdf->WriteHTML('</th>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        $mpdf->WriteHTML(date("F j, Y", strtotime($this->_view->stored_data['check_out'])));
                    $mpdf->WriteHTML('</td>');
                $mpdf->WriteHTML('</tr>');
            $mpdf->WriteHTML('</table>');

            $mpdf->WriteHTML('<div class = "underline" style="padding-bottom:25px; clear:both;"></div>');

            $mpdf->WriteHTML('<table style = "width:100%;">');
                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Lead Tenant Approval');
                    $mpdf->WriteHTML('</th>');

                     $mpdf->WriteHTML('<td class = "align-right">');
                        if($this->_view->stored_data['tenant_approved_check_out'] == 1){
                            if(isset($users[$this->_view->stored_data['lead_tenant_id']]['check_out_signature']) && !empty($users[$this->_view->stored_data['lead_tenant_id']]['check_out_signature'])) {
                                $mpdf->WriteHTML('<img src = "' . ROOT . 'assets/uploads/' . $users[$this->_view->stored_data['lead_tenant_id']]['check_out_signature'] . '" width="160px;">');
                            }
                        }
                    $mpdf->WriteHTML('</td>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        if($this->_view->stored_data['tenant_approved_check_out'] == 1){
                            $mpdf->WriteHTML(date("F j, Y", strtotime($users[$this->_view->stored_data['lead_tenant_id']]['check_out_time'])));
                        }else{
                            //$mpdf->WriteHTML('No approval / signature given');
                        }
                    $mpdf->WriteHTML('</td>');

                $mpdf->WriteHTML('</tr>');
            $mpdf->WriteHTML('</table>');

            $mpdf->WriteHTML('<div class = "underline" style="padding-bottom:25px; clear:both;"></div>');

            $mpdf->WriteHTML('<table style = "width:100%;">');
                $mpdf->WriteHTML('<tr>');
                    $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                        $mpdf->WriteHTML('Landlord / Letting Agent Approval:');
                    $mpdf->WriteHTML('</th>');

                    $mpdf->WriteHTML('<td class = "align-right">');
                        if($this->_view->stored_data['lord_approved_check_out'] == 1){
                            if(isset($users[$this->_view->stored_data['lord_id']]['check_out_signature']) && !empty($users[$this->_view->stored_data['lord_id']]['check_out_signature'])) {
                                $mpdf->WriteHTML('<img src = "' . ROOT . 'assets/uploads/' . $users[$this->_view->stored_data['lord_id']]['check_out_signature'] . '" width="160px;">');
                            }
                        }
                    $mpdf->WriteHTML('</td>');


                    $mpdf->WriteHTML('<td class = "align-right">');
                        if($this->_view->stored_data['lord_approved_check_out'] == 1){
                            $mpdf->WriteHTML(date("F j, Y", strtotime($users[$this->_view->stored_data['lord_id']]['check_out_time'])));
                        }else{
                            //$mpdf->WriteHTML('No approval / signature given');
                        }
                    $mpdf->WriteHTML('</td>');

                $mpdf->WriteHTML('</tr>');
            $mpdf->WriteHTML('</table>');

            $mpdf->WriteHTML('<div class = "underline" style="padding-bottom:25px; clear:both;"></div>');

            foreach ($users as $key => $user) {
                if($user['id'] != $this->_view->stored_data['lord_id'] && $user['id'] != $this->_view->stored_data['lead_tenant_id']){
                    $mpdf->WriteHTML('<table style = "width:100%;">');
                        $mpdf->WriteHTML('<tr>');
                            $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                                $mpdf->WriteHTML('Other Tenant '.$user['firstname'].' '.$user['surname']);
                            $mpdf->WriteHTML('</th>');

                            $mpdf->WriteHTML('<td class = "align-right">');
                                if(isset($user['check_out_signature']) && !empty($user['check_out_signature'])){
                                    if(isset($users[$user['id']]['check_out_signature']) && !empty($users[$user['id']]['check_out_signature'])) {
                                        $mpdf->WriteHTML('<img src = "' . ROOT . 'assets/uploads/' . $users[$user['id']]['check_out_signature'] . '" width="160px;">');
                                    }
                                }
                            $mpdf->WriteHTML('</td>');


                            $mpdf->WriteHTML('<td class = "align-right">');
                                if(isset($user['check_out_signature']) && !empty($user['check_out_signature'])){
                                    $mpdf->WriteHTML(date("F j, Y", strtotime($users[$user['id']]['check_out_time'])));
                                }else{
                                    //$mpdf->WriteHTML('No approval / signature given');
                                }
                            $mpdf->WriteHTML('</td>');

                        $mpdf->WriteHTML('</tr>');
                    $mpdf->WriteHTML('</table>');

                    $mpdf->WriteHTML('<div class = "underline" style="padding-bottom:25px; clear:both;"></div>');
                }
            }

            foreach($this->_view->checkOutData as $key => $room){
                $mpdf->WriteHTML('<pagebreak />');

                $mpdf->WriteHTML('<div style="margin-bottom:25px; padding:4px 12px" class = "background-main color-offset">'.$room['name'].'</div>');

                $mpdf->WriteHTML('<table style = "width:100%;">');
                    $mpdf->WriteHTML('<tr>');
                        $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                            $mpdf->WriteHTML('Clean Status');
                        $mpdf->WriteHTML('</th>');

                        $mpdf->WriteHTML('<td class = "align-right">');
                            $mpdf->WriteHTML($this->_view->clean_status[$room['clean']]);
                        $mpdf->WriteHTML('</td>');
                    $mpdf->WriteHTML('</tr>');

                    $mpdf->WriteHTML('<tr>');
                        $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                            $mpdf->WriteHTML('Tenant Comment');
                        $mpdf->WriteHTML('</th>');

                        $mpdf->WriteHTML('<td class = "align-right">');
                            $mpdf->WriteHTML($room['tenant_comment']);
                        $mpdf->WriteHTML('</td>');
                    $mpdf->WriteHTML('</tr>');

                    $mpdf->WriteHTML('<tr>');
                        $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
                            $mpdf->WriteHTML('LandLord Comment');
                        $mpdf->WriteHTML('</th>');

                        $mpdf->WriteHTML('<td class = "align-right">');
                            $mpdf->WriteHTML($room['lord_comment']);
                        $mpdf->WriteHTML('</td>');
                    $mpdf->WriteHTML('</tr>');
                $mpdf->WriteHTML('</table>');

                $mpdf->WriteHTML('<div class = "underline" style="padding:25px; clear:both;"></div>');


                if(isset($room['items']) && !empty($room['items'])){
                    foreach($room['items'] as $key2 => $item){
                        $mpdf->WriteHTML('<div style="margin-bottom:25px; padding:4px 12px" class = "background-secondary color-offset">'.$item['name'].'</div>');

                        $mpdf->WriteHTML('<table style = "width:100%; padding-bottom:20px">');
                            $mpdf->WriteHTML('<tr>');
                                $mpdf->WriteHTML('<td class = "blue bold">');
                                    $mpdf->WriteHTML('Tenant Item Image');
                                $mpdf->WriteHTML('</td>');

                                $mpdf->WriteHTML('<td class = "blue bold align-right" style = "width:20%;">');
                                    if(isset($item['image']) && !empty($item['image'])){
                                        $mpdf->WriteHTML('<img src = "'.ROOT.'assets/uploads/'.$item['image'].'" width="160px;">');
                                    }
                                $mpdf->WriteHTML('</td>');
                            $mpdf->WriteHTML('</tr>');

                            $mpdf->WriteHTML('<tr>');
                                $mpdf->WriteHTML('<td class = "blue bold">');
                                    $mpdf->WriteHTML('LL / Agent Item Image');
                                $mpdf->WriteHTML('</td>');

                                $mpdf->WriteHTML('<td class = "align-right" style = "width:20%;">');
                                    if(isset($item['lord_image']) && !empty($item['lord_image'])){
                                        $mpdf->WriteHTML('<img src = "'.ROOT.'assets/uploads/'.$item['lord_image'].'" width="160px;">');
                                    }
                                $mpdf->WriteHTML('</td>');
                            $mpdf->WriteHTML('</tr>');

                            $mpdf->WriteHTML('<tr>');
                                $mpdf->WriteHTML('<td class = "blue bold">');
                                    $mpdf->WriteHTML('Item Status');
                                $mpdf->WriteHTML('</td>');

                                $mpdf->WriteHTML('<td class = "align-right">');
                                        $mpdf->WriteHTML($this->_view->status[max($item['tenant_approved'], $item['lord_approved'])]);
                                $mpdf->WriteHTML('</td>');
                            $mpdf->WriteHTML('</tr>');

                            $mpdf->WriteHTML('<tr>');
                                $mpdf->WriteHTML('<td class = "blue bold">');
                                    $mpdf->WriteHTML('Tenant Comment');
                                $mpdf->WriteHTML('</td>');


                                $mpdf->WriteHTML('<td class = "align-right">');
                                        $mpdf->WriteHTML($item['tenant_comment']);
                                $mpdf->WriteHTML('</td>');
                            $mpdf->WriteHTML('</tr>');

                             $mpdf->WriteHTML('<tr>');
                                $mpdf->WriteHTML('<td class = "blue bold">');
                                    $mpdf->WriteHTML('Landlord Comment');
                                $mpdf->WriteHTML('</td>');


                                $mpdf->WriteHTML('<td class = "align-right">');
                                        $mpdf->WriteHTML($item['lord_comment']);
                                $mpdf->WriteHTML('</td>');
                            $mpdf->WriteHTML('</tr>');
                        $mpdf->WriteHTML('</table>');
                    }
                }
            }
        }
        // $mpdf->debug = true; 
        $mpdf->Output();
        exit;
    }

    /**
     * PAGE: Report sign
     * GET: /reports/sign/:report_id
     * This method handles an other tenant signing the check in or check out
     * @param int $report_id, string $type
     */
    public function sign($report_id, $type){
        Auth::checkUserLogin();

        if(!isset($report_id) || empty($report_id)){
            $this->_view->flash[] = "No report ID provided";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('users/dashboard');
        }

        // Need to get the users assoicated with this check in and bounce them if they don't belong here.
        $users = $this->_model->getUserReports($report_id);
        $users = explode(',', $users[0]['user_id']);
        if(!in_array($_SESSION['UserCurrentUserID'], $users)){
            $this->_view->flash[] = "You don't appear to be assoicated with this report";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('users/dashboard');
        }


        if(isset($_POST['signature']) && !empty($_POST['signature'])){
            $this->_userModel = $this->loadModel('users');

            $data_uri = $_POST['signature'];
            $encoded_image = explode(",", $data_uri)[1];
            $decoded_image = base64_decode($encoded_image);
            $signatureData['user_id'] = $_SESSION['UserCurrentUserID'];
            $signatureData['report_id'] = $report_id;

            switch ($type) {
                case 'checkin':
                    $signatureData['check_in_signature'] = date('Y-m-d-H-i-s-').$_SESSION['UserCurrentUserID'].'.png';
                    file_put_contents('assets/uploads/'. $signatureData['check_in_signature'], $decoded_image);
                    $signatureData['check_in_time'] = date('Y-m-d H:i:s');
                    $this->_userModel->updateUserReportCheckIn($signatureData);
                    break;
                case 'checkout':
                    $signatureData['check_out_signature'] = date('Y-m-d-H-i-s-').$_SESSION['UserCurrentUserID'].'.png';
                    file_put_contents('assets/uploads/'. $signatureData['check_out_signature'], $decoded_image);
                    $signatureData['check_out_time'] = date('Y-m-d H:i:s');
                    $this->_userModel->updateUserReportCheckOut($signatureData);

                    break;
                default:
                    $this->_view->flash[] = "Incorrect type was provided";
                    Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
                    Url::redirect('users/dashboard');
                    break;
            }
            $this->_view->flash[] = "Signature added successfully.";
            Session::set('backofficeFlash', array($this->_view->flash, 'success'));
            Url::redirect('users/dashboard');
        }
        $this->_view->flash[] = "No signature was provided";
        Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
        Url::redirect('users/dashboard');
    }

    /**
     * PAGE: Report completed
     * GET: /reports/completed
     * This method handles shows completed reports for the current user
     */
    public function completed(){
        Auth::checkUserLogin();
        // Set the Page Title ('pageName', 'pageSection', 'areaName')
        $this->_view->pageTitle = array('Reports');
        // Set Page Description
        $this->_view->pageDescription = 'Checkmate completed reports';
        // Set Page Section
        $this->_view->pageSection = 'Reports';
        // Set Page Sub Section
        $this->_view->pageSubSection = 'Reports';

        if(!isset($_SESSION['UserCurrentUserID']) || empty($_SESSION['UserCurrentUserID'])){
            $this->_view->flash[] = "No user id provided";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('users/login');
        }

        //getting completed reports for current user
        $this->_view->getAllData = $this->_model->getCompletedReportsByUserId($_SESSION['UserCurrentUserID']);

        // Render the view ($renderBody, $layout, $area)
        $this->_view->render('reports/completed', 'layout');


    }
}
?>