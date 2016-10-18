<?php
/** Reports Controller */

class ReportsController extends BaseController {

	/** __construct */
	public function __construct(){
		parent::__construct();
		// Load the User Model ($modelName, $area)
		$this->_model = $this->loadModel('reportsBackoffice', 'backoffice');
	}

    /**
	 * PAGE: Reports Index
	 * GET: /backoffice/reports/index
	 * This method handles the view awards page
	 */
	public function index(){
        Auth::checkAdminLogin();

		// Set the Page Title ('pageName', 'pageSection', 'areaName')
		$this->_view->pageTitle = array('Reports');
		// Set Page Description
		$this->_view->pageDescription = 'Reports Index';
		// Set Page Section
		$this->_view->pageSection = 'Reports';
		// Set Page Sub Section
		$this->_view->pageSubSection = 'Reports';

        //Need a bunch of status etc
        $this->_view->status = explode(',', REPORT);

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
		$this->_view->render('reports/index', 'layout', 'backoffice');
	}


    /**
     * PAGE: Reports Delete
     * GET: /backoffice/reports/delete/:id
     * This method handles the deletion of Reports
     * @param string $id The unique id for the Reports
     */
    public function delete($id){
        Auth::checkAdminLogin();
        // Set the Page Title ('pageName', 'pageSection', 'areaName')
        $this->_view->pageTitle = array('Reports');
        // Set Page Description
        $this->_view->pageDescription = 'View Reports';
        // Set Page Section
        $this->_view->pageSection = 'Reports';
        // Set Page Sub Section
        $this->_view->pageSubSection = 'Reports';

        //Check we got ID
        if(!empty($id)){
            $selectDataByID = $this->_model->selectDataByID($id);
            $this->_view->selectedData = $selectDataByID;

            // We need to work out if its safe to delete this user.
            $currentDate = date("Y-m-d");
            $this->_view->conflict = $this->_model->getReportsByIdAndDate($id, $currentDate);

            //Check ID returns an Reports
            if(isset($selectDataByID[0]['id']) && !empty($selectDataByID[0]['id'])){
                if(isset($_POST) && !empty($_POST)) {
                    if (!empty($_POST['delete'])) {

                        $this->_roomsModel = $this->loadModel('roomsBackoffice', 'backoffice');
                        $this->_itemsModel = $this->loadModel('itemsBackoffice', 'backoffice');

                        $imageArray = array();

                        // Need to remove check_in_items and check_out_item images
                        if(isset($selectDataByID[0]['check_in_room_ids']) && !empty($selectDataByID[0]['check_in_room_ids'])){
                            $check_in_room_ids = explode(',', $selectDataByID[0]['check_in_room_ids']);

                            foreach($check_in_room_ids as $key => $check_in_room){
                                $rooms = $this->_roomsModel->selectCheckInRoomsByID($check_in_room);
                                $rooms = $rooms[0];
                                if(isset($rooms['check_in_item_ids']) && !empty($rooms['check_in_item_ids'])){
                                    $item_ids = explode(',', $rooms['check_in_item_ids']);
                                    foreach ($item_ids as $key2 => $item) {
                                        $items = $this->_itemsModel->selectCheckInItemsByID($item);
                                        $items = $items[0];
                                        if(isset($items['image']) && !empty($items['image'])){
                                            $imageArray[] = $items['image'];
                                        }

                                    }
                                }
                            }
                        }

                        // Need to remove check_in_items and check_out_item images
                        if(isset($selectDataByID[0]['check_out_room_ids']) && !empty($selectDataByID[0]['check_out_room_ids'])){
                            $check_out_room_ids = explode(',', $selectDataByID[0]['check_out_room_ids']);

                            foreach($check_out_room_ids as $key => $check_out_room){
                                $rooms = $this->_roomsModel->selectCheckOutRoomsByID($check_out_room);
                                $rooms = $rooms[0];
                                if(isset($rooms['check_out_item_ids']) && !empty($rooms['check_out_item_ids'])){
                                    $item_ids = explode(',', $rooms['check_out_item_ids']);
                                    foreach ($item_ids as $key2 => $item) {
                                        $items = $this->_itemsModel->selectCheckOutItemsByID($item);
                                        $items = $items[0];
                                        if(isset($items['image']) && !empty($items['image'])){
                                            $imageArray[] = $items['image'];
                                        }

                                    }
                                }
                            }
                        }

                        $deleteAttempt = $this->_model->deleteData($id);
                        //Check we have deleted Reports
                        if (!empty($deleteAttempt)) {

                            // Need to remove meter image if it exists
                            if(isset($selectDataByID[0]['meter_image']) && !empty($selectDataByID[0]['meter_image'])){
                                unlink(ROOT . UPLOAD_DIR . '/' . $selectDataByID[0]['meter_image']);
                            }

                            //Removing items images
                            if(isset($imageArray) && !empty($imageArray)){
                                foreach($imageArray as $image){
                                    unlink(ROOT . UPLOAD_DIR . '/' . $image);
                                }
                            }

                            // Redirect to next page
                            $this->_view->flash[] = "Reports deleted successfully.";
                            Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                            Url::redirect('backoffice/reports/index');
                        } else {
                            $this->_view->error[] = 'A problem has occurred when trying to delete this award.';
                        }
                    } elseif (!empty($_POST['cancel'])) {
                        Url::redirect('backoffice/reports/index');
                    }
                }
            }else{
                $this->_view->flash[] = "No Reports matches this id";
                Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
                Url::redirect('backoffice/reports/index');
            }
        }else{
            $this->_view->flash[] = "No ID provided for Reports";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('backoffice/reports/index');
        }
        // Render the view ($renderBody, $layout, $area)
        $this->_view->render('reports/delete', 'layout', 'backoffice');
    }

    /**
     * PAGE: Reports Edit
     * GET: /backoffice/reports/edit:id
     * @param string $id The unique id for the award user
     * This method handles the edit reports page
     */
    public function edit($id = false){
        Auth::checkAdminLogin();
        if(!empty($id)){
            $selectDataByID = $this->_model->selectDataByID($id);
            if(isset($selectDataByID[0]['id']) && !empty($selectDataByID[0]['id'])){
                $this->_view->stored_data = $selectDataByID[0];

            }else{
                $this->_view->flash[] = "No Reports matches this id";
                Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
                Url::redirect('backoffice/reports/index');
            }
        }else{
            $this->_view->flash[] = "No ID provided for Rooms";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('backoffice/reports/index');
        }

        // Set the Page Title ('pageName', 'pageSection', 'areaName')
        $this->_view->pageTitle = array('Reports', 'Reports');
        // Set Page Description
        $this->_view->pageDescription = 'Reports Edit';
        // Set Page Section
        $this->_view->pageSection = 'Reports';
        // Set Page Sub Section
        $this->_view->pageSubSection = 'Reports';

        // Set default variables
        $this->_view->error = array();

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

        // If Form has been submitted process it
        if(!empty($_POST['save'])){
            $_POST['id'] = $id;

            // Update Rooms details
            $updateData = $this->_model->updateData($_POST);

            if(isset($updateData['error']) && $updateData['error'] != null){
                foreach($updateData['error'] as $key => $error){
                    $this->_view->error[$key] = $error;
                }
            } else {
                $this->_view->flash[] = "Rooms updated successfully.";
                Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                Url::redirect('backoffice/reports/index');
            }
        }

        if(!empty($_POST['cancel'])){
            Url::redirect('backoffice/reports/index');
        }

        // Render the view ($renderBody, $layout, $area)
        $this->_view->render('reports/add', 'layout', 'backoffice');
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
                $this->_view->flash[] = "No Reports matches this id";
                Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
                Url::redirect('/backoffice/reports/');
            }
        }else{
            $this->_view->flash[] = "No ID provided for Reports";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('/backoffice/reports/');
        }

        // Building drop down arrays
        $this->_view->status = explode(',', REPORT);
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

         $mpdf->WriteHTML('<div>');
         $mpdf->WriteHTML('<div');
         $mpdf->WriteHTML('<img class = "logo" src = "'.ROOT.'assets/images/logo2.png">');
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

         $mpdf->WriteHTML('<tr>');
         $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:30%;">');
         $mpdf->WriteHTML('Key Status');
         $mpdf->WriteHTML('</th>');

         $mpdf->WriteHTML('<td class = "align-right">');
         $mpdf->WriteHTML($this->_view->key_status[$this->_view->stored_data['keys_acquired']]);
         $mpdf->WriteHTML('</td>');
         $mpdf->WriteHTML('</tr>');

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
         $mpdf->WriteHTML('Red means neither the Landlord / Agent nor Lead Tenant have approved.');
         $mpdf->WriteHTML('</td>');

         $mpdf->WriteHTML('<tr>');

         $mpdf->WriteHTML('<tr>');
         $mpdf->WriteHTML('<td class = "amber">');
         $mpdf->WriteHTML('Amber');
         $mpdf->WriteHTML('</td>');
         $mpdf->WriteHTML('<td class = "amber">');
         $mpdf->WriteHTML('Amber means either the Landlord / Agent or Lead Tenant have approved.');
         $mpdf->WriteHTML('</td>');

         $mpdf->WriteHTML('<tr>');

         $mpdf->WriteHTML('<tr>');
         $mpdf->WriteHTML('<td class = "green">');
         $mpdf->WriteHTML('Green');
         $mpdf->WriteHTML('</td>');
         $mpdf->WriteHTML('<td class = "green">');
         $mpdf->WriteHTML('Green means both the Landlord / Agent and Lead Tenant have approved.');
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
                 $mpdf->WriteHTML('No approval / signature given');
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
                 $mpdf->WriteHTML('No approval / signature given');
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
                         $mpdf->WriteHTML('No approval / signature given');
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
                 $mpdf->WriteHTML('LandLord / Agent Comment');
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
                         $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:20%;" rowspan = "3">');
                         if(isset($item['image']) && !empty($item['image'])) {
                             $mpdf->WriteHTML('<img src = "' . ROOT . 'assets/uploads/' . $item['image'] . '" width="160px;">');
                         }
                         $mpdf->WriteHTML('</th>');

                         $mpdf->WriteHTML('<td class = "blue bold align-right">');
                         $mpdf->WriteHTML('Item Status');
                         $mpdf->WriteHTML('</td>');


                         $mpdf->WriteHTML('<td class = "align-right">');
                         $mpdf->WriteHTML($this->_view->status[$item['tenant_approved'] + $item['lord_approved']]);
                         $mpdf->WriteHTML('</td>');
                         $mpdf->WriteHTML('</tr>');

                         $mpdf->WriteHTML('<tr>');
                         $mpdf->WriteHTML('<td class = "blue bold align-right">');
                         $mpdf->WriteHTML('Tenant Comment');
                         $mpdf->WriteHTML('</td>');


                         $mpdf->WriteHTML('<td class = "align-right">');
                         $mpdf->WriteHTML($item['tenant_comment']);
                         $mpdf->WriteHTML('</td>');
                         $mpdf->WriteHTML('</tr>');

                         $mpdf->WriteHTML('<tr>');
                         $mpdf->WriteHTML('<td class = "blue bold align-right">');
                         $mpdf->WriteHTML('Landlord / Agent Comment');
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

         $mpdf->WriteHTML('<pagebreak />');

         if(isset($this->_view->checkOutData) && !empty($this->_view->checkOutData)){
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
                 $mpdf->WriteHTML('No approval / signature given');
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
                 $mpdf->WriteHTML('No approval / signature given');
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
                         $mpdf->WriteHTML('No approval / signature given');
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
                 $mpdf->WriteHTML('LandLord / Agent Comment');
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
                         $mpdf->WriteHTML('<th class = "blue bold align-left" style = "width:20%;" rowspan = "3">');
                         if(isset($item['image']) && !empty($item['image'])){
                             $mpdf->WriteHTML('<img src = "'.ROOT.'assets/uploads/'.$item['image'].'" width="160px;">');
                         }
                         $mpdf->WriteHTML('</th>');

                         $mpdf->WriteHTML('<td class = "blue bold align-right">');
                         $mpdf->WriteHTML('Item Status');
                         $mpdf->WriteHTML('</td>');


                         $mpdf->WriteHTML('<td class = "align-right">');
                         $mpdf->WriteHTML($this->_view->status[$item['tenant_approved'] + $item['lord_approved']]);
                         $mpdf->WriteHTML('</td>');
                         $mpdf->WriteHTML('</tr>');

                         $mpdf->WriteHTML('<tr>');
                         $mpdf->WriteHTML('<td class = "blue bold align-right">');
                         $mpdf->WriteHTML('Tenant Comment');
                         $mpdf->WriteHTML('</td>');


                         $mpdf->WriteHTML('<td class = "align-right">');
                         $mpdf->WriteHTML($item['tenant_comment']);
                         $mpdf->WriteHTML('</td>');
                         $mpdf->WriteHTML('</tr>');

                         $mpdf->WriteHTML('<tr>');
                         $mpdf->WriteHTML('<td class = "blue bold align-right">');
                         $mpdf->WriteHTML('Landlord / Agent Comment');
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
}
?>