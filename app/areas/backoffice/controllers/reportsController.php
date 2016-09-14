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
                            $mpdf->WriteHTML('Meter Image');
                        $mpdf->WriteHTML('</th>');

                        $mpdf->WriteHTML('<td>');
                            $mpdf->WriteHTML('<img src = "'.ROOT.'/assets/uploads/'.$this->_view->stored_data['meter_image'].'" width="120px;" height="120px">');
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
                                                $mpdf->WriteHTML('<img src = "'.ROOT.'/assets/uploads/'.$item['image'].'" width="120px;" height="120px">');
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
                                                $mpdf->WriteHTML('<img src = "'.ROOT.'/assets/uploads/'.$item['image'].'" width="120px;" height="120px">');
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