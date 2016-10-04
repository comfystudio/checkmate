<?php
/** Payments Controller */

class PaymentsController extends BaseController {

	/** __construct */
	public function __construct(){
		parent::__construct();
		// Load the User Model ($modelName, $area)
		$this->_model = $this->loadModel('payments');
        $this->_notificationsModel = $this->loadModel('notifications');
        require_once(ROOT.'system/helpers/stripe/init.php');

	}

    /**
     * PAGE: Payments Upgrade
     * GET: /backoffice/payments/upgrade
     * This method handles upgrading of a users account
     */
    public function upgrade(){
        Auth::checkUserLogin();

        //Set the Page Title ('pageName', 'pageSection', 'areaName')
        $this->_view->pageTitle = array('Payments');
        // Set Page Description
        $this->_view->pageDescription = 'Payments Upgrade';
        // Set Page Section
        $this->_view->pageSection = 'Users';
        // Set Page Sub Section
        $this->_view->pageSubSection = 'Payments';

        echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>';
        echo '<script type="text/javascript" src="https://js.stripe.com/v2/"></script>';
        echo '<script src="/assets/js/payment.initialize.js"></script>';

        if(!isset($_SESSION['UserCurrentUserID']) || empty($_SESSION['UserCurrentUserID'])){
            $this->_view->flash[] = "No User ID was provided";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('users/login');
        }

        //Getting the user details
        $this->_usersModel = $this->loadModel('users');
        $user = $this->_usersModel->selectDataByID($_SESSION['UserCurrentUserID']);

        //if no matching user then boot them back to user login
        if(!isset($user) || empty($user)){
            $this->_view->flash[] = "No User matches provided ID";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('users/login');
        }

        $this->_view->user = $user;

        //Customer Name
        $customer_description = $user[0]['firstname'].' '.$user[0]['surname']. '(' .$user[0]['email'].')';

        // If this user doesn't have an existing payment then bounce them to create page
        if(!isset($user[0]['stripe_cus_id']) || empty($user[0]['stripe_cus_id'])){
            Url::redirect('payments/create');
        }

        if($user[0]['payment_type'] == 5){
            $this->_view->flash[] = "Account cannot be upgraded beyond unlimited.";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('users/dashboard');
        }

        //Debug::printr($user);die;

        // If Form has been submitted process it
        if(!empty($_POST['stripeToken'])){
            // See your keys here https://dashboard.stripe.com/account/apikeys
            \Stripe\Stripe::setApiKey(STRIPE_SECRET);

            // Get the credit card details submitted by the form
            $token = $_POST['stripeToken'];

            //Error message contact info
            $error_contact = 'If problem persists please contact us at '.SITE_EMAIL;

            if(!isset($_POST['type']) || empty($_POST['type'])){
                $this->_view->error[] = 'A payment type must be selected';
            }else{
                //we need to match up our payment type with some varibles we will need.
                $payment_types = explode(',',PAYMENTS);

                try {

                    $customer = \Stripe\Subscription::retrieve($user[0]['stripe_sub_id']);
                    $customer->plan = strtolower($payment_types[$_POST['type']]);
                    $customer->save();

                }catch(\Stripe\Error\Card $e) {
                    // Since it's a decline, \Stripe\Error\Card will be caught
                    $body = $e->getJsonBody();
                    $err  = $body['error'];
                    $this->_view->error[] = 'Payment Failed: '. $err['message'];
                    $this->_view->error[] = $error_contact;
                    //$this->_view->error[] = 'Status is:' . $e->getHttpStatus();
                    //$this->_view->error[] = 'Type is:' . $err['type'];
                    //$this->_view->error[] = 'Code is:' . $err['code'];
                    // param is '' in this case
                    //$this->_view->error[] = 'Param is:' . $err['param'];
                } catch (\Stripe\Error\RateLimit $e) {
                    // Too many requests made to the API too quickly
                    $body = $e->getJsonBody();
                    $err  = $body['error'];
                    $this->_view->error[] = 'Payment Failed: '. $err['message'];
                    $this->_view->error[] = $error_contact;
                } catch (\Stripe\Error\InvalidRequest $e) {
                    // Invalid parameters were supplied to Stripe's API
                    $body = $e->getJsonBody();
                    $err  = $body['error'];
                    $this->_view->error[] = 'Payment Failed: '. $err['message'];
                    $this->_view->error[] = $error_contact;
                } catch (\Stripe\Error\Authentication $e) {
                    // Authentication with Stripe's API failed
                    // (maybe you changed API keys recently)
                    $body = $e->getJsonBody();
                    $err  = $body['error'];
                    $this->_view->error[] = 'Payment Failed: '. $err['message'];
                    $this->_view->error[] = $error_contact;
                } catch (\Stripe\Error\ApiConnection $e) {
                    // Network communication with Stripe failed
                    $body = $e->getJsonBody();
                    $err  = $body['error'];
                    $this->_view->error[] = 'Payment Failed: '. $err['message'];
                    $this->_view->error[] = $error_contact;
                } catch (\Stripe\Error\Base $e) {
                    // Display a very generic error to the user, and maybe send
                    // yourself an email
                    $body = $e->getJsonBody();
                    $err  = $body['error'];
                    $this->_view->error[] = 'Payment Failed: '. $err['message'];
                    $this->_view->error[] = $error_contact;
                } catch (Exception $e) {
                    // Something else happened, completely unrelated to Stripe
                    $body = $e->getJsonBody();
                    $err  = $body['error'];
                    $this->_view->error[] = 'Payment Failed: '. $err['message'];
                    $this->_view->error[] = $error_contact;
                }

                //we need to update our payments table with the correct data.
                if(isset($customer->id) && !empty($customer->id)){
                    switch ($_POST['type']) {
                        case 1:
                            $data['remaining_credits'] = 1;
                            break;
                        case 2:
                            $data['remaining_credits'] = 50;
                            break;
                        case 3:
                            $data['remaining_credits'] = 100;
                            break;
                        case 4:
                            $data['remaining_credits'] = 200;
                            break;
                        case 5:
                            $data['remaining_credits'] = 9999;
                            break;
                        default:
                            # code...
                            break;
                    }

                    $data['type'] = $_POST['type'];
                    $data['last_payment'] = date('Y-m-d H:i:s', $customer['current_period_start']);
                    $data['active_until'] = date('Y-m-d H:i:s', $customer['current_period_end']);
                    $data['id'] = $user[0]['payment_id'];

                    //updating our payments
                    $updateData = $this->_model->updateData($data);

                    if(isset($updateData) && !empty($updateData)){
                        //notification creation
                        $data['text'] = 'You have upgraded to a '.$payment_types[$_POST['type']].' membership. Thank you.';
                        $data['user_id'] =  $_SESSION['UserCurrentUserID'];
                        $this->_notificationsModel->createData($data);

                        //Email setup
                        $this->_view->data['name'] = $user[0]['firstname'].' '.$user[0]['surname'];
                        $this->_view->data['message'] = 'You have upgraded to a '.$payment_types[$_POST['type']].' membership. Thank you.';
                        $this->_view->data['button_link'] = SITE_URL.'users/dashboard/';
                        $this->_view->data['button_text'] = 'Checkmate Site.';

                        // Need to create email
                        $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
                        Html::sendEmail($user[0]['email'], 'Checkmate - Account upgraded', SITE_EMAIL, $message);

                        //redirect user to thank you page.
                        Url::redirect('payments/thanks');
                    }
                }

            }
        }

        // Render the view ($renderBody, $layout, $area)
        $this->_view->render('payments/upgrade', 'layout');

    }

    /**
     * PAGE: Payments Create
     * GET: /payments/create
     * This method handles creation of a sub
     */
    public function create(){
        Auth::checkUserLogin();

        //Set the Page Title ('pageName', 'pageSection', 'areaName')
        $this->_view->pageTitle = array('Payments');
        // Set Page Description
        $this->_view->pageDescription = 'Payments Upgrade';
        // Set Page Section
        $this->_view->pageSection = 'Users';
        // Set Page Sub Section
        $this->_view->pageSubSection = 'Payments';

        echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>';
        echo '<script type="text/javascript" src="https://js.stripe.com/v2/"></script>';
        echo '<script src="/assets/js/payment.initialize.js"></script>';

        if(!isset($_SESSION['UserCurrentUserID']) || empty($_SESSION['UserCurrentUserID'])){
            $this->_view->flash[] = "No User ID was provided";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('users/login');
        }

        //Getting the user details
        $this->_usersModel = $this->loadModel('users');
        $user = $this->_usersModel->selectDataByID($_SESSION['UserCurrentUserID']);

        //if no matching user then boot them back to user login
        if(!isset($user) || empty($user)){
            $this->_view->flash[] = "No User matches provided ID";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('users/login');
        }

        // If this user already has a cus_id and basically previously subbed then redirect them to the upgrade page.
        if(isset($user[0]['stripe_cus_id']) && !empty($user[0]['stripe_cus_id'])){
            Url::redirect('payments/upgrade');
        }

        $this->_view->user = $user;

        //Customer Name
        $customer_description = $user[0]['firstname'].' '.$user[0]['surname']. '(' .$user[0]['email'].')';

        // If Form has been submitted process it
        if(!empty($_POST['stripeToken'])){
            // See your keys here https://dashboard.stripe.com/account/apikeys
            \Stripe\Stripe::setApiKey(STRIPE_SECRET);

            // Get the credit card details submitted by the form
            $token = $_POST['stripeToken'];

            //Error message contact info
            $error_contact = 'If problem persists please contact us at '.SITE_EMAIL;

            if(!isset($_POST['type']) || empty($_POST['type'])){
                $this->_view->error[] = 'A payment type must be selected';
            }else{
                //we need to match up our payment type with some varibles we will need.
                $payment_types = explode(',',PAYMENTS);

                try {

                    $customer = \Stripe\Customer::create(array(
                        'email' => $_POST['stripeEmail'],
                        'source'  => $_POST['stripeToken'],
                        'plan' => strtolower($payment_types[$_POST['type']]),
                        'description' => $customer_description
                      ));

                }catch(\Stripe\Error\Card $e) {
                    // Since it's a decline, \Stripe\Error\Card will be caught
                    $body = $e->getJsonBody();
                    $err  = $body['error'];
                    $this->_view->error[] = 'Payment Failed: '. $err['message'];
                    $this->_view->error[] = $error_contact;
                    //$this->_view->error[] = 'Status is:' . $e->getHttpStatus();
                    //$this->_view->error[] = 'Type is:' . $err['type'];
                    //$this->_view->error[] = 'Code is:' . $err['code'];
                    // param is '' in this case
                    //$this->_view->error[] = 'Param is:' . $err['param'];
                } catch (\Stripe\Error\RateLimit $e) {
                    // Too many requests made to the API too quickly
                    $body = $e->getJsonBody();
                    $err  = $body['error'];
                    $this->_view->error[] = 'Payment Failed: '. $err['message'];
                    $this->_view->error[] = $error_contact;
                } catch (\Stripe\Error\InvalidRequest $e) {
                    // Invalid parameters were supplied to Stripe's API
                    $body = $e->getJsonBody();
                    $err  = $body['error'];
                    $this->_view->error[] = 'Payment Failed: '. $err['message'];
                    $this->_view->error[] = $error_contact;
                } catch (\Stripe\Error\Authentication $e) {
                    // Authentication with Stripe's API failed
                    // (maybe you changed API keys recently)
                    $body = $e->getJsonBody();
                    $err  = $body['error'];
                    $this->_view->error[] = 'Payment Failed: '. $err['message'];
                    $this->_view->error[] = $error_contact;
                } catch (\Stripe\Error\ApiConnection $e) {
                    // Network communication with Stripe failed
                    $body = $e->getJsonBody();
                    $err  = $body['error'];
                    $this->_view->error[] = 'Payment Failed: '. $err['message'];
                    $this->_view->error[] = $error_contact;
                } catch (\Stripe\Error\Base $e) {
                    // Display a very generic error to the user, and maybe send
                    // yourself an email
                    $body = $e->getJsonBody();
                    $err  = $body['error'];
                    $this->_view->error[] = 'Payment Failed: '. $err['message'];
                    $this->_view->error[] = $error_contact;
                } catch (Exception $e) {
                    // Something else happened, completely unrelated to Stripe
                    $body = $e->getJsonBody();
                    $err  = $body['error'];
                    $this->_view->error[] = 'Payment Failed: '. $err['message'];
                    $this->_view->error[] = $error_contact;
                }

                //we need to update our payments table with the correct data.
                if(isset($customer->id) && !empty($customer->id)){
                    switch ($_POST['type']) {
                        case 1:
                            $data['remaining_credits'] = 1;
                            break;
                        case 2:
                            $data['remaining_credits'] = 50;
                            break;
                        case 3:
                            $data['remaining_credits'] = 100;
                            break;
                        case 4:
                            $data['remaining_credits'] = 200;
                            break;
                        case 5:
                            $data['remaining_credits'] = 9999;
                            break;
                        default:
                            # code...
                            break;
                    }

                    $data['user_id'] = $_SESSION['UserCurrentUserID'];
                    $data['stripe_cus_id'] = $customer->id;
                    $data['stripe_sub_id'] = $customer->subscriptions->data[0]['id'];
                    $data['type'] = $_POST['type'];
                    $data['last_payment'] = date('Y-m-d H:i:s', $customer->subscriptions->data[0]['current_period_start']);
                    $data['active_until'] = date('Y-m-d H:i:s', $customer->subscriptions->data[0]['current_period_end']);

                    //creating our payments
                    $createData = $this->_model->createData($data);

                    if(isset($createData) && !empty($createData)){
                        //notification creation
                        $data['text'] = 'You have subscribed to a '.$payment_types[$_POST['type']].' membership. Thank you.';
                        $data['user_id'] =  $_SESSION['UserCurrentUserID'];
                        $this->_notificationsModel->createData($data);

                        //Email setup
                        $this->_view->data['name'] = $user[0]['firstname'].' '.$user[0]['surname'];
                        $this->_view->data['message'] = 'You have subscribed to a '.$payment_types[$_POST['type']].' membership. Thank you.';
                        $this->_view->data['button_link'] = SITE_URL.'users/dashboard/';
                        $this->_view->data['button_text'] = 'Checkmate Site.';

                        // Need to create email
                        $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
                        Html::sendEmail($user[0]['email'], 'Checkmate - Payment recieved.', SITE_EMAIL, $message);

                        //redirect user to thank you page.
                        Url::redirect('payments/thanks');
                    }
                }
            }
        }

        // Render the view ($renderBody, $layout, $area)
        $this->_view->render('payments/upgrade', 'layout');

    }

    /**
     * PAGE: Payments Hook
     * GET: /payments/hook
     * This method handles the reponse from strip regarding payments etc.
     */
    public function hook(){
        // See your keys here: https://dashboard.stripe.com/account/apikeys
        \Stripe\Stripe::setApiKey(STRIPE_SECRET);

        // Retrieve the request's body and parse it as JSON
        $input = file_get_contents("php://input");
        $event_json = json_decode($input);

        ob_start();
        Debug::printr($event_json);
        //Debug::printr($event_json->id);
        Debug::printr($event_json->data->object->period_end);
        $data = ob_get_clean();
        $fp = fopen(date('g:i:s-')."hook.txt", "w");
        fwrite($fp, $data);
        fclose($fp);

        // If invoice payment is successful then update payment table.
        if(isset($event_json->type) && $event_json->type == 'invoice.payment_succeeded'){
            //trying to find payment with stripe customer id.
            $payment = $this->_model->getPaymentByStripeCusId($event_json->data->object->customer);
            if(isset($payment) && !empty($payment)){
                $data['last_payment'] = date('Y-m-d H:i:s', $eveÚnt_json->data->object->period_start);
                $data['active_until'] = date('Y-m-d H:i:s', $event_json->data->object->period_end);
                $data['id'] = $payment[0]['id'];
                //updating payment with new times.
                $this->_model->updatePaymentTime($data);

                //notification creation
                $data['text'] = 'We have recieved your payment until the period '.$data['active_until'].'. Thank you!';
                $data['user_id'] = $payment[0]['user_id'];
                $this->_notificationsModel->createData($data);

                //Email setup
                $this->_view->data['name'] = $payment[0]['firstname'].' '.$payment[0]['surname'];
                $this->_view->data['message'] = 'We have recieved your payment until the period '.$data['active_until'].'. Thank you!';
                $this->_view->data['button_link'] = SITE_URL.'users/dashboard/';
                $this->_view->data['button_text'] = 'Checkmate Site.';

                // Need to create email
                $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
                Html::sendEmail($payment[0]['email'], 'Checkmate - Payment recieved', SITE_EMAIL, $message);

            }

        }

        // Verify the event by fetching it from Stripe
        //$event = \Stripe\Event::retrieve($event_json->id);

        http_response_code(200);

        exit;
    }


    /**
     * PAGE: Payments thanks
     * GET: /payments/thanks
     * This method shows a thank you.
     */
    public function thanks(){
        //Set the Page Title ('pageName', 'pageSection', 'areaName')
        $this->_view->pageTitle = array('Payments');
        // Set Page Description
        $this->_view->pageDescription = 'Payments Thanks';
        // Set Page Section
        $this->_view->pageSection = 'Users';
        // Set Page Sub Section
        $this->_view->pageSubSection = 'Payments';

        // Render the view ($renderBody, $layout, $area)
        $this->_view->render('payments/thanks', 'layout');

    }

    /**
     * PAGE: Payments Cancel
     * GET: /payments/cancel
     * This method handles cancelling a subscription
     */
    public function cancel(){
        Auth::checkUserLogin();

        //Set the Page Title ('pageName', 'pageSection', 'areaName')
        $this->_view->pageTitle = array('Payments');
        // Set Page Description
        $this->_view->pageDescription = 'Payments Cancel';
        // Set Page Section
        $this->_view->pageSection = 'Users';
        // Set Page Sub Section
        $this->_view->pageSubSection = 'Payments';

        // echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>';
        // echo '<script type="text/javascript" src="https://js.stripe.com/v2/"></script>';
        // echo '<script src="/assets/js/payment.initialize.js"></script>';

        if(!isset($_SESSION['UserCurrentUserID']) || empty($_SESSION['UserCurrentUserID'])){
            $this->_view->flash[] = "No User ID was provided";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('users/login');
        }

        //Getting the user details
        $this->_usersModel = $this->loadModel('users');
        $user = $this->_usersModel->selectDataByID($_SESSION['UserCurrentUserID']);

        //if no matching user then boot them back to user login
        if(!isset($user) || empty($user)){
            $this->_view->flash[] = "No User matches provided ID";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('users/login');
        }

        $this->_view->user = $user;

        //Customer Name
        $customer_description = $user[0]['firstname'].' '.$user[0]['surname']. '(' .$user[0]['email'].')';

        // If this user doesn't have an existing payment then bounce them to create page
        if(!isset($user[0]['stripe_cus_id']) || empty($user[0]['stripe_cus_id'])){
            $this->_view->flash[] = "You do not have a subscription to cancel";
            Session::set('backofficeFlash', array($this->_view->flash, 'failure'));
            Url::redirect('users/dashboard');
        }

        if(!empty($_POST['cancel'])){
            Url::redirect('users/dashboard');
        }

        // If Form has been submitted process it
        if(!empty($_POST['delete'])){
            // See your keys here https://dashboard.stripe.com/account/apikeys
            \Stripe\Stripe::setApiKey(STRIPE_SECRET);

            // Get the credit card details submitted by the form
            //$token = $_POST['stripeToken'];

            //Error message contact info
            $error_contact = 'If problem persists please contact us at '.SITE_EMAIL;

            //we need to match up our payment type with some varibles we will need.
            $payment_types = explode(',',PAYMENTS);


            try {
                $customer = \Stripe\Subscription::retrieve($user[0]['stripe_sub_id']);
                $customer->cancel(array('at_period_end' => true));

            }catch(\Stripe\Error\Card $e) {
                // Since it's a decline, \Stripe\Error\Card will be caught
                $body = $e->getJsonBody();
                $err  = $body['error'];
                $this->_view->error[] = 'Payment Failed: '. $err['message'];
                $this->_view->error[] = $error_contact;
                //$this->_view->error[] = 'Status is:' . $e->getHttpStatus();
                //$this->_view->error[] = 'Type is:' . $err['type'];
                //$this->_view->error[] = 'Code is:' . $err['code'];
                // param is '' in this case
                //$this->_view->error[] = 'Param is:' . $err['param'];
            } catch (\Stripe\Error\RateLimit $e) {
                // Too many requests made to the API too quickly
                $body = $e->getJsonBody();
                $err  = $body['error'];
                $this->_view->error[] = 'Payment Failed: '. $err['message'];
                $this->_view->error[] = $error_contact;
            } catch (\Stripe\Error\InvalidRequest $e) {
                // Invalid parameters were supplied to Stripe's API
                $body = $e->getJsonBody();
                $err  = $body['error'];
                $this->_view->error[] = 'Payment Failed: '. $err['message'];
                $this->_view->error[] = $error_contact;
            } catch (\Stripe\Error\Authentication $e) {
                // Authentication with Stripe's API failed
                // (maybe you changed API keys recently)
                $body = $e->getJsonBody();
                $err  = $body['error'];
                $this->_view->error[] = 'Payment Failed: '. $err['message'];
                $this->_view->error[] = $error_contact;
            } catch (\Stripe\Error\ApiConnection $e) {
                // Network communication with Stripe failed
                $body = $e->getJsonBody();
                $err  = $body['error'];
                $this->_view->error[] = 'Payment Failed: '. $err['message'];
                $this->_view->error[] = $error_contact;
            } catch (\Stripe\Error\Base $e) {
                // Display a very generic error to the user, and maybe send
                // yourself an email
                $body = $e->getJsonBody();
                $err  = $body['error'];
                $this->_view->error[] = 'Payment Failed: '. $err['message'];
                $this->_view->error[] = $error_contact;
            } catch (Exception $e) {
                // Something else happened, completely unrelated to Stripe
                $body = $e->getJsonBody();
                $err  = $body['error'];
                $this->_view->error[] = 'Payment Failed: '. $err['message'];
                $this->_view->error[] = $error_contact;
            }

            //we need to update our payments table with the correct data.
            if(isset($customer) && !empty($customer)){
                //notification creation
                $data['text'] = 'You have cancelled your subscription. You can still use it til the next payment was due.';
                $data['user_id'] =  $_SESSION['UserCurrentUserID'];
                $this->_notificationsModel->createData($data);

                //Email setup
                $this->_view->data['name'] = $user[0]['firstname'].' '.$user[0]['surname'];
                $this->_view->data['message'] = 'You have cancelled your subscription. You can still use it til the next payment was due.';
                $this->_view->data['button_link'] = SITE_URL.'users/dashboard/';
                $this->_view->data['button_text'] = 'Checkmate Site.';

                // Need to create email
                $message = $this->_view->renderToString('email-templates/general-message-with-button', 'blank-layout');
                Html::sendEmail($user[0]['email'], 'Checkmate - Subscription Cancelled', SITE_EMAIL, $message);

                //redirect user to thank you page.
                $this->_view->flash[] = "Subscription cancelled";
                Session::set('backofficeFlash', array($this->_view->flash, 'success'));
                Url::redirect('users/dashboard/');
            }
        }

        // Render the view ($renderBody, $layout, $area)
        $this->_view->render('payments/cancel', 'layout');

    }

}
?>