<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Advance_dashboard extends CI_Controller {
    public $controller_name = 'advance_dashboard';    
    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model(ADMIN_URL.'/advance_dashboard_model');
        $this->load->model(ADMIN_URL.'/order_model');
        if (!$this->session->userdata('is_admin_login')) {
            redirect(ADMIN_URL.'/home');
        }
    }
    public function index() {
        $arr['meta_title'] = $this->lang->line('title_admin_dashboard').' | '.$this->lang->line('site_title');   
        if (isset($_COOKIE['user_notifications'])) {
            if ($_COOKIE['user_notifications'] == $this->session->userdata('AdminUserID')) {
                if (isset($_COOKIE['accepted_notifications'])) {
                    $acceptedNotifications = json_decode($_COOKIE['accepted_notifications']);
                    foreach ($acceptedNotifications as $key => $value) {
                        $array_accepted[] = $value->alert_id;
                    }
                } 
            }
        }
        $getAllNotifications = $this->advance_dashboard_model->getNotifications();  
        if (!empty($getAllNotifications)) {
            foreach ($getAllNotifications as $key => $value) {
                if (!empty($array_accepted)) {
                    if (in_array($value['alert_id'], $array_accepted)) {
                        unset($getAllNotifications[$key]);
                    }
                }
            }
        }
        $arr['Notifications'] = $getAllNotifications;   
        //Graph related feature :: Start
        $arr['sale'] = $this->advance_dashboard_model->getLifetimeSale(); 
        $arr['this_month'] = $this->advance_dashboard_model->this_month(); 
        $arr['last_month'] = $this->advance_dashboard_model->last_month();        
        $arr['delivery_charges'] = $this->advance_dashboard_model->getDeliveryCharges();  

        // Get cod payment 
        $arr['cod_payment'] = $this->advance_dashboard_model->getCODPayment();

        // Get online payment 
        $arr['online_payment'] = $this->advance_dashboard_model->getOnlinePayment();

        // Get Top coupon used
        $arr['top_used_coupon'] = $this->advance_dashboard_model->getTopCouponUsed();        
        // Get Top Customers 
        $arr['top_customers'] = $this->advance_dashboard_model->getTopCustomers();  

        // Get Top Category       
        $arr['top_category'] = $this->advance_dashboard_model->getTopCategory();  

        // Get Top Products
        $arr['top_products'] = $this->advance_dashboard_model->getTopProducts();          
        // Get Top Products
        $arr['top_restaurant'] = $this->advance_dashboard_model->getTopRestaurant();  

        // Get paid and unpaid tips
        //$arr['tip_paid_count'] = $this->advance_dashboard_model->getTipsCount('Paid');
        
        // Get Unpaid tips count
        $arr['tip_unpaid_count'] = $this->advance_dashboard_model->getTipsCount('Unpaid');  

        // Get Unpaid tips count
        $arr['unpaid_commission_count'] = $this->advance_dashboard_model->getUnpaidCommissionCount();  
        
        // Get payment methods
        $arr['payment_method'] =  $this->db->get('payment_method')->result();        
        $this->load->view(ADMIN_URL.'/advance_dashboard',$arr);
    }
    public function ajaxNotification(){
        $count = $this->advance_dashboard_model->ajaxNotification();
        if($count->delivery_pickup_count == null){
           $count->delivery_pickup_count=0; 
        }
        if($count->dinein_count == null){
           $count->dinein_count=0; 
        }
        if($count->order_count == null){
           $count->order_count=0; 
        }
        if($count->event_count == null){
           $count->event_count=0; 
        }
        if($count->tablebooking_count == null){
           $count->tablebooking_count=0; 
        }
        if($count->placed_order_count == null){
           $count->placed_order_count = 0; 
        }
        echo json_encode($count); 
    }
    public function change_delivery_pickup_order_view_status(){
        $this->advance_dashboard_model->change_delivery_pickup_order_view_status();
    }
    public function change_dinein_order_view_status(){
        $this->advance_dashboard_model->change_dinein_order_view_status();
    }
    public function ajaxEventNotification(){
        $count = $this->advance_dashboard_model->ajaxEventNotification();
        echo json_encode($count);   
    }
    public function changeEventStatus(){
        $this->advance_dashboard_model->changeEventStatus();
    }

    
    // Graph bar start
    public function fetch_data()
    {
        $var = explode(" - ", $this->input->post('daterange'));
        $from_date = str_replace('-', '/', $var[0]);
        $to_date = str_replace('-', '/', $var[1]);
        $startdate = date('Y-m-d', strtotime($from_date));
        $enddate = date('Y-m-d', strtotime($to_date));
        $payment_option = $this->input->post('payment_option');
        $result = $this->advance_dashboard_model->fetch_chart_data($startdate, $enddate,$payment_option);

        $length = count($result);
        $output = array();
        for($i=0; $i < $length ; $i++) {
        $dateObj   = DateTime::createFromFormat('Y-m-d H:i:s', $result[$i]['day']);
        $monthName = $dateObj->format('m-d-Y');  
            $output[] = array(
                'day'  => $monthName,   
                'total' => $result[$i]['total']
            );
        }        
        echo json_encode($output);
    }
    // Graph bar end
    // user accepted the notification
    public function notification_accepted()
    {
      $allNotifications = $this->advance_dashboard_model->getNotifications();
      if (!empty($allNotifications)) {
        foreach ($allNotifications as $key => $value) {
          $notificationData[] = array(                   
              'notification_id'=> $value['alert_id'],
              'UserID' => $this->session->userdata('AdminUserID'),
          ); 
        }
        setcookie("accepted_notifications", json_encode($notificationData), time() + (86400 * 30), "/"); 
        setcookie("user_notifications", $this->session->userdata('AdminUserID'), time() + (86400 * 30), "/");   
        echo 'success';  
      }
      else
      {
        echo 'fail';
      }
    }    
    public function autoCancelOrders() {
        //get all placed orders
        $response = array('reload_flag' => 0, 'status' => 0);
        $placed_orders = $this->advance_dashboard_model->getPlacedOrders();
        $payment_methodarr = array('stripe','paypal','applepay');
        $is_cancelled = 0;
        if(!empty($placed_orders)) {
            foreach ($placed_orders as $key => $value) {
                $autocancelflag = 0;
                $current_time = date('Y-m-d H:i:s');
                $current_time = date('Y-m-d H:i:s', strtotime($this->common_model->getZonebaseDate($current_time)));

                $scheduledorderclosetime = date('Y-m-d H:i:s', strtotime("$value->scheduled_date $value->slot_close_time"));
                $order_scheduled_date = ($value->scheduled_date) ? date('Y-m-d', strtotime($this->common_model->getZonebaseDate($scheduledorderclosetime))) : NULL;
                $order_slot_close_time = ($value->slot_close_time) ? date('H:i:s', strtotime($this->common_model->getZonebaseDate($scheduledorderclosetime))) : NULL;

                if($value->scheduled_date && $value->slot_close_time) {
                    $combined_scheduled_date = date('Y-m-d H:i:s', strtotime("$order_scheduled_date $order_slot_close_time"));
                    $scheduleddatetime = new DateTime($combined_scheduled_date);
                    $currentdatetime = new DateTime($current_time);

                    if($scheduleddatetime <= $currentdatetime) {
                        $autocancelflag = 1;
                        $order_date = $combined_scheduled_date;
                    }
                } else if($value->scheduled_date=='' || $value->slot_close_time=='') {
                    $autocancelflag = 1;
                    $order_date = date('Y-m-d H:i:s', strtotime($this->common_model->getZonebaseDate($value->order_date)));
                }                
                
                if($autocancelflag == 1) {
                    //difference between current datetime and order datetime
                    $datetime_diff = $this->common_model->dateDifference($order_date, $current_time);
                    $diff_year = $datetime_diff['diff_year'];
                    $diff_month = $datetime_diff['diff_month'];
                    $diff_day = $datetime_diff['diff_day'];
                    $diff_hr = $datetime_diff['diff_hr'];
                    $diff_min = $datetime_diff['diff_min'];
                    //convert system option auto cancellation minutes to compare
                    $convert_mins = $this->common_model->convertMinutes('auto_cancel');
                    $compare_year = $convert_mins['compare_year'];
                    $compare_month = $convert_mins['compare_month'];
                    $compare_day = $convert_mins['compare_day'];
                    $compare_hour = $convert_mins['compare_hour'];
                    $compare_minute = $convert_mins['compare_minute'];
                    
                    if($diff_year > $compare_year || $diff_month > $compare_month || $diff_day > $compare_day || $diff_hr > $compare_hour || $diff_min > $compare_minute) {
                        //stripe/paypal refund amount :: start
                        if($value->refund_status != 'pending' && $value->tips_refund_status != 'pending')
                        {
                            if(($value->transaction_id != '' && in_array(strtolower($value->payment_option), $payment_methodarr) && $value->refund_status != 'refunded') || ($value->tips_transaction_id != '' && $value->tips_refund_status != 'refunded'))
                            {
                                $transaction_id = ($value->transaction_id != '' && ($value->refund_status == '' || strtolower($value->refund_status) == 'partial refunded')) ? $value->transaction_id : '';
                                $tips_transaction_id = ($value->tips_transaction_id != '' && $value->tips_refund_status == '') ? $value->tips_transaction_id:'';

                                $tip_payment_option = ($value->tip_payment_option!='' && $value->tip_payment_option!=null)?$value->tip_payment_option:'';
                                if($tip_payment_option=='' && $tips_transaction_id!='')
                                {
                                    $tip_payment_option = 'stripe';
                                }
                                $refund_reason = $this->lang->line('autocancel_refund_reason');
                                if(strtolower($value->payment_option)=='stripe' || strtolower($value->payment_option)=='applepay' || $tip_payment_option=='stripe')
                                {
                                    $response = $this->common_model->StripeRefund($transaction_id,$value->order_id,$tips_transaction_id,'',$tip_payment_option,$refund_reason,'full',0);
                                }
                                else if(strtolower($value->payment_option)=='paypal' || $tip_payment_option=='paypal')
                                {   
                                    $response = $this->common_model->PaypalRefund($transaction_id,$value->order_id,$tips_transaction_id,'',$tip_payment_option,$refund_reason,'full',0);
                                }
                                
                                //Mail send code Start
                                if(!empty($response) && ($response['paymentIntentstatus'] || $response['tips_paymentIntentstatus']))
                                {
                                    $language_slug = $this->session->userdata('language_slug');
                                    $updated_bytxt = $this->session->userdata("adminFirstname").' '.$this->session->userdata("adminLastname");
                                    $this->common_model->refundMailsend($value->order_id,$value->user_id,0,'full',$updated_bytxt,$language_slug);
                                }                                
                                //Mail send code End
                                
                                if(in_array(strtolower($value->payment_option), $payment_methodarr))
                                {
                                    //Code for save updated by and date value 
                                    $update_array = array(
                                        'updated_by' => 0,
                                        'updated_date' => date('Y-m-d H:i:s')
                                    );
                                    $this->db->set($update_array)->where('entity_id',$value->order_id)->update('order_master');
                                    //Code for save updated by and date value
                                }
                            }
                        }
                        $this->common_model->save_user_log('Auto canceled an order - '.$value->order_id);
                        //stripe refund amount :: end
                        $this->db->set('order_status','cancel')->where('entity_id',$value->order_id)->update('order_master');
                        $cancel_reason = $this->lang->line('auto_cancelled_by_txt');
                        $this->db->set('cancel_reason',$cancel_reason)->where('entity_id',$value->order_id)->update('order_master');
                        $status_created_by = 'auto_cancelled';
                        $addData = array(
                            'order_id'=>$value->order_id,
                            'order_status'=>'cancel',
                            'time'=>date('Y-m-d H:i:s'),
                            'status_created_by'=>$status_created_by
                        );
                        $orderstatustbl_id = $this->common_model->addData('order_status',$addData);

                        $user_id = $value->user_id;
                        if($user_id && $user_id > 0) {
                            //wallet changes :: start
                            //if order is cancelled both debit and credit should be removed from wallet history
                            $users_wallet = $this->common_model->getUsersWalletMoney($user_id);
                            $current_wallet = $users_wallet->wallet; //money in wallet
                            $credit_walletDetails = $this->common_model->getRecordMultipleWhere('wallet_history',array('order_id' => $value->order_id,'user_id' => $user_id, 'credit'=>1, 'is_deleted'=>0));
                            $credit_amount = $credit_walletDetails->amount;
                            $debit_walletDetails = $this->common_model->getRecordMultipleWhere('wallet_history',array('order_id' => $value->order_id,'user_id' => $user_id, 'debit'=>1, 'is_deleted'=>0));
                            $debit_amount = $debit_walletDetails->amount;
                            $new_wallet_amount = ($current_wallet + $debit_amount) - $credit_amount;
                            $new_wallet_amount = ($new_wallet_amount<0)?0:$new_wallet_amount;
                            //delete order_id from wallet history and update users wallet
                            if(!empty($credit_amount) || !empty($debit_amount)){
                                $this->common_model->deletewallethistory($value->order_id); // delete by order id
                                $new_wallet = array(
                                    'wallet'=>$new_wallet_amount
                                );
                                $this->common_model->updateMultipleWhere('users', array('entity_id'=>$user_id), $new_wallet);
                            }
                            //wallet changes :: end
                            $notification = array(
                                'order_id' => $value->order_id,
                                'user_id' => $user_id,
                                'notification_slug' => 'order_canceled',
                                'view_status' => 0,
                                'datetime' => date("Y-m-d H:i:s"),
                            );
                            $this->common_model->addData('user_order_notification',$notification);

                            //get langauge
                            $device = $this->common_model->getDevice($user_id);
                            if($device->notification == 1){
                                $languages = $this->db->select('language_directory')->get_where('languages',array('language_slug'=>$device->language_slug))->first_row();
                                $this->lang->load('messages_lang', $languages->language_directory);
                                
                                //$message = sprintf($this->lang->line('order_canceled'),$value->order_id).'-'.$cancel_reason;
                                $message = sprintf($this->lang->line('order_autocancelled_notimsg'),$value->order_id);

                                $device_id = $device->device_id;
                                // Send Latest wallet balance               
                                $users_wallet = $this->common_model->getUsersWalletMoney($user_id);
                                $latest_wallet_balance = $users_wallet->wallet; 

                                $this->common_model->sendFCMRegistration($device_id,$message,'cancel',$value->restaurant_id,FCM_KEY,$value->order_delivery,'',$value->order_id,'',$latest_wallet_balance);
                            }
                            //send refund noti to user
                            if(!empty($response) && ($response['paymentIntentstatus'] || $response['tips_paymentIntentstatus'])) {
                                $this->common_model->sendRefundNoti($value->order_id,$user_id,$value->restaurant_id,$transaction_id,$tips_transaction_id,$response['paymentIntentstatus'],$response['tips_paymentIntentstatus'],$response['error']);
                            }
                        }
                        //send email and sms notification to user on order cancel
                        $langslugval = ($device->language_slug) ? $device->language_slug : '';
                        $useridval = ($user_id && $user_id > 0) ? $user_id : 0;
                        $this->common_model->sendSMSandEmailToUserOnCancelOrder($langslugval,$useridval,$value->order_id,'auto_cancelled');
                        if($value->agent_id){
                            $this->common_model->notificationToAgent($value->order_id, 'cancel');
                        }
                        $is_cancelled++;
                    }
                }
            }
        }
        if($is_cancelled > 0) {
            $response = array('reload_flag' => 1, 'status' => 1);
        }
        echo json_encode($response);
    }
    public function markDelayedOrders() { 
        $response = array('reload_flag' => 0, 'status' => 0);
        $get_all_orders = $this->advance_dashboard_model->getOrderstoMarkDelayed();
        //echo "<pre>"; print_r($get_all_orders); exit;
        $delayed_count = 0;
        if(!empty($get_all_orders)) {
            foreach ($get_all_orders as $key => $val) {
                $markdelayedflag = 0;
                $compare_time_chk = ($val->check_status_time)?date('Y-m-d H:i:s',strtotime($val->check_status_time)):date('Y-m-d H:i:s');
                $compare_time_chk = date('Y-m-d H:i:s', strtotime($this->common_model->getZonebaseDate($compare_time_chk)));

                $scheduledorderclosetime = date('Y-m-d H:i:s', strtotime("$val->scheduled_date $val->slot_close_time"));
                $order_scheduled_date = ($val->scheduled_date) ? date('Y-m-d', strtotime($this->common_model->getZonebaseDate($scheduledorderclosetime))) : NULL;
                $order_slot_close_time = ($val->slot_close_time) ? date('H:i:s', strtotime($this->common_model->getZonebaseDate($scheduledorderclosetime))) : NULL;

                if($val->scheduled_date && $val->slot_close_time) {                    
                    $combined_scheduled_date = date('Y-m-d H:i:s', strtotime("$order_scheduled_date $order_slot_close_time"));
                    $scheduleddatetime = new DateTime($combined_scheduled_date);
                    $currentdatetime = new DateTime($compare_time_chk);

                    if($scheduleddatetime <= $currentdatetime) {                        
                        $markdelayedflag = 1;
                        $order_date_chk = $combined_scheduled_date;
                    }
                } elseif($val->scheduled_date=='' || $val->slot_close_time=='') {
                    $markdelayedflag = 1;                    
                    $order_date_chk = date('Y-m-d H:i:s', strtotime($this->common_model->getZonebaseDate($val->order_date)));
                }

                if($markdelayedflag == 1) {
                    //difference between current datetime and order datetime
                    $datetime_diff = $this->common_model->dateDifference($order_date_chk , $compare_time_chk);
                    $diff_year = $datetime_diff['diff_year'];
                    $diff_month = $datetime_diff['diff_month'];
                    $diff_day = $datetime_diff['diff_day'];
                    $diff_hr = $datetime_diff['diff_hr'];
                    $diff_min = $datetime_diff['diff_min'];
                    //convert system option auto cancellation minutes to compare
                    $convert_mins = $this->common_model->convertMinutes('check_delayed');
                    $compare_year = $convert_mins['compare_year'];
                    $compare_month = $convert_mins['compare_month'];
                    $compare_day = $convert_mins['compare_day'];
                    $compare_hour = $convert_mins['compare_hour'];
                    $compare_minute = $convert_mins['compare_minute'];

                    if($diff_year > $compare_year || $diff_month > $compare_month || $diff_day > $compare_day || $diff_hr > $compare_hour || $diff_min > $compare_minute) {
                        $this->db->set('is_delayed',1)->where('entity_id',$val->order_id)->update('order_master');
                        $delayed_count++;
                    }
                }
            }
        }
        if($delayed_count > 0) {
            $response = array('reload_flag' => 1, 'status' => 1);
        }
        echo json_encode($response);
    }
    //update table booking view status
    public function changeTableBookingStatus(){
        $this->advance_dashboard_model->changeTableBookingStatus();
    }

    // Graph wesbite vs application orders 
    public function get_website_app_data()
    {
        $var = explode(" - ", $this->input->post('daterange'));
        $from_date = str_replace('-', '/', $var[0]);
        $to_date = str_replace('-', '/', $var[1]);
        $startdate = date('Y-m-d', strtotime($from_date));
        $enddate = date('Y-m-d', strtotime($to_date));
        $result = $this->advance_dashboard_model->get_website_vs_app_orders($startdate, $enddate);
        $length = count($result);
        $output = array();
        for($i=0; $i < $length ; $i++) {
            $output[] = array(
                'month_year' => $result[$i]['month_year'],
                'website_total' => $result[$i]['website_total'],
                'app_total' => $result[$i]['app_total']
            );
        }        
        echo json_encode($output);
    }
    // Graph wesbite vs application orders 
    public function get_customer_vs_guest_data()
    {
        $var = explode(" - ", $this->input->post('daterange'));
        $from_date = str_replace('-', '/', $var[0]);
        $to_date = str_replace('-', '/', $var[1]);
        $startdate = date('Y-m-d', strtotime($from_date));
        $enddate = date('Y-m-d', strtotime($to_date));
        $result = $this->advance_dashboard_model->get_customer_vs_guest_orders($startdate, $enddate);
        $length = count($result);
        $output = array();
        for($i=0; $i < $length ; $i++) {
            $output[] = array(
                'month_year' => $result[$i]['month_year'],
                'guest_total' => $result[$i]['guest_total'],
                'customer_total' => $result[$i]['customer_total']
            );
        }        
        echo json_encode($output);
    }

    public function ajaxview()
    {
        $user_id = ($this->uri->segment('5'))?$this->uri->segment('5'):0; 
        $order_id = ($this->uri->segment('6') && $this->uri->segment('6')!='order_id')?$this->uri->segment('6'):0;
        $displayLength = ($this->input->post('iDisplayLength') != '')?intval($this->input->post('iDisplayLength')):'';
        $displayStart = ($this->input->post('iDisplayStart') != '')?intval($this->input->post('iDisplayStart')):'';
        $sEcho = ($this->input->post('sEcho'))?intval($this->input->post('sEcho')):'';
        $sortCol = ($this->input->post('iSortCol_0'))?intval($this->input->post('iSortCol_0')):'';
        $sortOrder = ($this->input->post('sSortDir_0'))?$this->input->post('sSortDir_0'):'ASC';
        $order_status = ($this->uri->segment('4') && $this->uri->segment('4')!='all')?$this->uri->segment('4'):''; 
        $sortfields = array('0'=>'o.entity_id','1'=>'restaurant.name','2'=>'order_detail.user_name','3'=>'o.total_rate','4'=>'o.order_status','5'=>'o.payment_option','6'=>'o.order_delivery');
        $sortFieldName = '';
        if(array_key_exists($sortCol, $sortfields))
        {
            $sortFieldName = $sortfields[$sortCol];
        }        
        //Get Recored from model
        $sortFieldName = "o.entity_id";
        $sortOrder = "desc";
        $displayLength = 10;
        $grid_data = $this->order_model->getGridList($sortFieldName,$sortOrder,$displayStart,$displayLength,$order_status,$user_id,$order_id);      
        $totalRecords = $grid_data['total'];        
        $records = array();
        $records["aaData"] = array(); 
        $nCount = ($displayStart != '')?$displayStart+1:1;        
        $payment_methodarr = array('stripe','paypal','applepay');
        $default_currency = get_default_system_currency();
        foreach ($grid_data['data'] as $key => $val) {
            $ordermode = ($val->order_delivery)?"'".strtolower($val->order_delivery)."'":'';
            $restaurant = ($val->restaurant_detail)?unserialize($val->restaurant_detail):'';
            $order_user_id = ($val->user_id && $val->user_id>0)?$val->user_id:0;
            $accept = ($val->status != 1 && $val->restaurant_id && $val->ostatus != 'delivered' && $val->ostatus != 'cancel' && $val->ostatus != 'rejected' && in_array('order~updateOrderStatus',$this->session->userdata("UserAccessArray")))?'<button onclick="disableDetail('.$val->entity_id.','.$val->restaurant_id.','.$val->entity_id.','.$val->o_user_id.','.$ordermode.')"  title="'.$this->lang->line('accept').'" class="delete btn btn-sm default-btn margin-bottom red btn-green" ><i class="fa fa-check"></i></button>':'';
            $refundstatus = ($val->refund_status=='pending' || $val->tips_refund_status=='pending')?'refunded':(($val->tips_transaction_id!='' && $val->tips_refund_status!='refunded')?'refund_needed':(($val->payment_option=='cod' && $val->tips_transaction_id=='')?'refunded':$val->refund_status));
            $reject = ($val->ostatus != 'delivered' && $val->ostatus != 'cancel' && $val->status != 1 && $val->ostatus != 'rejected' && in_array('order~updateOrderStatus',$this->session->userdata("UserAccessArray")))?'<button onclick="rejectOrder('.$order_user_id.','.$val->restaurant_id.','.$val->entity_id.',\''.$refundstatus.'\')"  title="'.$this->lang->line('reject').'" class="delete btn btn-sm default-btn margin-bottom btn-red"><i class="fa fa-times"></i></button>':'';
            
            $show_editbtn = 'yes';
            if($val->ostatus == "placed" && $val->status!='1')
            {
                $ostatuslng = $this->lang->line('placed');
                //$initiate_refund = '';
                $show_editbtn = 'no';
            }
            if(($val->ostatus == "placed" && $val->status=='1') || $val->ostatus == "accepted")
            {
                $ostatuslng = $this->lang->line('accepted');
            }
            if($val->ostatus == "rejected")
            {
                $ostatuslng = $this->lang->line('rejected');
            }
            if($val->ostatus == "delivered"){
                $ostatuslng = $this->lang->line('delivered');
            }
            if($val->ostatus == "onGoing")
            {
                $ostatuslng = $this->lang->line('onGoing');
                if($val->order_delivery == "PickUp")
                {
                    $ostatuslng = $this->lang->line('order_ready');
                }
            }
            
            if($val->ostatus == "cancel"){
                $ostatuslng = $this->lang->line('cancel');
            }
            if($val->ostatus == "ready"){
                $ostatuslng = $this->lang->line('order_ready');
            }
            if($val->ostatus == "complete"){
                $ostatuslng = $this->lang->line('complete');
            }
            // if($val->ostatus == "preparing"){
            //     $ostatuslng = $this->lang->line('preparing');
            // }
            if($val->ostatus == "pending"){
                $ostatuslng = $this->lang->line('pending');
            }
            if($val->order_delivery == "Delivery"){
                $order_delivery = $this->lang->line('delivery_order');
            }
            if($val->order_delivery == "PickUp"){
                $order_delivery = $this->lang->line('pickup');
            }            
            if($val->payment_option=='cod' && $val->tips_transaction_id!='' && $val->tips_refund_status=='refunded'){
                $payment_option = $this->lang->line('cod_display').'<br><span style="pointer-events: none;border:0px;color:#d9214e;font-weight:900;">'.$this->lang->line('cod_initiate_refunded').'</span>';
            }else if($val->payment_option=='cod' && $val->tips_transaction_id!=''){
                $payment_option = $this->lang->line('cod_display')."<br>".$this->lang->line('cod_initiate');
            }else if($val->payment_option=='cod'){
                $payment_option = $this->lang->line('cod_display');
            }
            $is_showeditbutton = 'yes'; $is_showrefundedby = 'no';
            if($val->payment_option == 'stripe' && $val->transaction_id != '' && $val->refund_status == 'refunded') {
                $payment_option = 'Stripe<br><span style="pointer-events: none;border:0px;color:#d9214e;font-weight:900;">(refunded)</span>';
                $is_showeditbutton = 'no';
                $is_showrefundedby = 'yes';
            } else if($val->payment_option == 'stripe' && $val->transaction_id != '' && $val->refund_status == 'partial refunded') {
                $payment_option = 'Stripe<br><span style="pointer-events: none;border:0px;color:#d9214e;font-weight:900;">(partial refunded)</span>';
                $is_showrefundedby = 'yes'; 
                $is_showeditbutton = 'no';
            } else if($val->payment_option=='stripe' && $val->tips_transaction_id!='' && $val->tips_refund_status=='refunded'){
                $payment_option = 'Stripe<br><span style="pointer-events: none;border:0px;color:#d9214e;font-weight:900;">(refunded)</span>';
                $is_showeditbutton = 'no';
                $is_showrefundedby = 'yes';
            }else if($val->payment_option=='stripe' && $val->tips_transaction_id=='' && $val->refund_status=='refunded'){
                $payment_option = 'Stripe<br><span style="pointer-events: none;border:0px;color:#d9214e;font-weight:900;">(refunded)</span>';
                $is_showeditbutton = 'no';
                $is_showrefundedby = 'yes';
            }else if($val->payment_option=='stripe' && $val->stripe_refund_id!='' && $val->refund_status=='partial refunded'){
                $payment_option = 'Stripe<br><span style="pointer-events: none;border:0px;color:#d9214e;font-weight:900;">(partial refunded)</span>';
                $is_showrefundedby = 'yes'; 
                $is_showeditbutton = 'no';               
            }else if($val->payment_option=='stripe'){
                $payment_option = 'Stripe';
            }
            //Code Apple Pay :: Start
            if($val->payment_option=='applepay' && $val->tips_transaction_id!='' && $val->tips_refund_status=='refunded'){
                $payment_option = 'Apple Pay<br><span style="pointer-events: none;border:0px;color:#d9214e;font-weight:900;">(refunded)</span>';
                $is_showeditbutton = 'no';
                $is_showrefundedby = 'yes';
            }else if($val->payment_option=='applepay' && $val->tips_transaction_id=='' && $val->refund_status=='refunded'){
                $payment_option = 'Apple Pay<br><span style="pointer-events: none;border:0px;color:#d9214e;font-weight:900;">(refunded)</span>';
                $is_showeditbutton = 'no';
                $is_showrefundedby = 'yes';
            }else if($val->payment_option=='applepay' && $val->stripe_refund_id!='' && $val->refund_status=='partial refunded'){
                $payment_option = 'Apple Pay<br><span style="pointer-events: none;border:0px;color:#d9214e;font-weight:900;">(partial refunded)</span>';
                $is_showrefundedby = 'yes';
                $is_showeditbutton = 'no';                
            }else if($val->payment_option=='applepay'){
                $payment_option = 'Apple Pay';
            }
            //Code Apple Pay :: End
            if(strtolower($val->payment_option)=='paypal')
            {
                $payment_option = 'Paypal';
                if($val->refund_status=='refunded')
                {
                    $payment_option .= '<br><span style="pointer-events: none;border:0px;color:#d9214e;font-weight:900;">(refunded)</span>';
                    $is_showeditbutton = 'no';
                    $is_showrefundedby = 'yes';
                }
                else if($val->refund_status=='partial refunded' && $val->stripe_refund_id!='')
                {
                    $payment_option .= '<br><span style="pointer-events: none;border:0px;color:#d9214e;font-weight:900;">(partial refunded)</span>'; 
                    $is_showrefundedby = 'yes';
                    $is_showeditbutton = 'no';                   
                }
            }

            //Code for display order updated by :: Start            
            $admin_namedis = '';
            if($val->adminf_name && $val->adminf_name!='' && $val->adminf_name!=null && $is_showrefundedby=='yes')
            {
                $admin_namedis = ($val->adminl_name && $val->adminl_name!='' && $val->adminl_name!=null)?$val->adminf_name.' '.$val->adminl_name:$val->adminf_name;
                $payment_option .= '<br> (Refunded by '.ucwords($admin_namedis).')'; 
            }
            //End
            $user_order_phn_no = ($val->user_mobile_number)?'(+'.$val->user_mobile_number.')':''; //order detail table

            $user_name = ($val->fname)?$val->fname.' '.$val->lname:''; //user's table
            $order_user_name = ($val->user_name)?$val->user_name:''; //order detail table

            $scheduledorderopentime = date('Y-m-d H:i:s', strtotime("$val->scheduled_date $val->slot_open_time"));
            $scheduledorderclosetime = date('Y-m-d H:i:s', strtotime("$val->scheduled_date $val->slot_close_time"));
            $order_scheduled_date = ($val->scheduled_date) ? date('Y-m-d', strtotime($this->common_model->getZonebaseDate($scheduledorderopentime))) : NULL;
            $order_slot_open_time = ($val->slot_open_time) ? date('H:i:s', strtotime($this->common_model->getZonebaseDate($scheduledorderopentime))) : NULL;
            $order_slot_close_time = ($val->slot_close_time) ? date('H:i:s', strtotime($this->common_model->getZonebaseDate($scheduledorderclosetime))) : NULL;

            //delayed order changes :: start
            if($val->is_delayed == 0 && strtolower($val->order_delivery)!='dinein') {
                $markdelayedflag = 0;
                $compare_time_chk = ($val->check_status_time)?date('Y-m-d H:i:s',strtotime($val->check_status_time)):date('Y-m-d H:i:s');
                $compare_time_chk = date('Y-m-d H:i:s', strtotime($this->common_model->getZonebaseDate($compare_time_chk)));

                if($val->scheduled_date && $val->slot_close_time) {
                    $combined_scheduled_date = date('Y-m-d H:i:s', strtotime("$order_scheduled_date $order_slot_close_time"));
                    $scheduleddatetime = new DateTime($combined_scheduled_date);
                    $currentdatetime = new DateTime($compare_time_chk);

                    if($scheduleddatetime <= $currentdatetime) {
                        $markdelayedflag = 1;                          
                        $order_date_chk = $combined_scheduled_date;
                    }
                } elseif($val->scheduled_date=='' || $val->slot_close_time=='') {
                    $markdelayedflag = 1;                      
                    $order_date_chk = date('Y-m-d H:i:s', strtotime($this->common_model->getZonebaseDate($val->order_date)));
                }
                if($markdelayedflag == 1) { 
                    //difference between current datetime and order datetime
                    $datetime_diff = $this->common_model->dateDifference($order_date_chk , $compare_time_chk);
                    $diff_year = $datetime_diff['diff_year'];
                    $diff_month = $datetime_diff['diff_month'];
                    $diff_day = $datetime_diff['diff_day'];
                    $diff_hr = $datetime_diff['diff_hr'];
                    $diff_min = $datetime_diff['diff_min'];
                    //convert system option auto cancellation minutes to compare
                    $convert_mins = $this->common_model->convertMinutes('check_delayed');
                    $compare_year = $convert_mins['compare_year'];
                    $compare_month = $convert_mins['compare_month'];
                    $compare_day = $convert_mins['compare_day'];
                    $compare_hour = $convert_mins['compare_hour'];
                    $compare_minute = $convert_mins['compare_minute'];

                    if($diff_year > $compare_year || $diff_month > $compare_month || $diff_day > $compare_day || $diff_hr > $compare_hour || $diff_min > $compare_minute) {
                        $this->db->set('is_delayed',1)->where('entity_id',$val->entity_id)->update('order_master');
                        $val->is_delayed = 1;
                    }
                }
            }            
            $delayed_label = ($val->is_delayed == 1)?'<br><button style="pointer-events: none;border:0px;color:white;background:#d9214e;font-weight:900;">'.$this->lang->line('delayed').'</button>':'';
            //delayed order changes :: end

            $view_order_details_btn = (in_array('order~view',$this->session->userdata("UserAccessArray"))) ? '<button class="btn btn-sm  default-btn danger-btn theme-btn margin-bottom-5" onclick="openOrderDetails('.$val->entity_id.')" title="'.$this->lang->line('order_details').'"><i class="fa fa-eye"></i></button>' : '';

            //Code for display order updated by :: Start
            $order_dispid = $val->entity_id;            
            if(trim($admin_namedis)!='')
            {
                $order_dispid .= '<br> (Updated by '.ucwords($admin_namedis).')'; 
            }
            //End

            $records["aaData"][] = array(
                $order_dispid,
                ($restaurant)?$restaurant->name:$val->name,
                ($order_user_name)?$order_user_name.' '.$user_order_phn_no:'Order by Restaurant',
                ($val->rate) ? (!empty($default_currency)) ? currency_symboldisplay(number_format_unchanged_precision($val->rate,$default_currency->currency_code),$default_currency->currency_symbol) : currency_symboldisplay(number_format_unchanged_precision($val->rate,$restaurant->currency_code),$restaurant->currency_symbol) : '',
                $ostatuslng.$delayed_label,
                $payment_option,
                $order_delivery,
                $view_order_details_btn.$accept.$reject.''
            );
            $nCount++;
        }
        $records["sEcho"] = $sEcho;
        $records["iTotalRecords"] = $totalRecords;
        $records["iTotalDisplayRecords"] = $totalRecords;
        echo json_encode($records);
    }

    public function ajaxview_revenue()
    {
        $displayLength = ($this->input->post('iDisplayLength') != '')?intval($this->input->post('iDisplayLength')):'';
        $displayStart = ($this->input->post('iDisplayStart') != '')?intval($this->input->post('iDisplayStart')):'';
        $sEcho = ($this->input->post('sEcho'))?intval($this->input->post('sEcho')):'';
        $sortCol = ($this->input->post('iSortCol_0'))?intval($this->input->post('iSortCol_0')):'';
        $sortOrder = ($this->input->post('sSortDir_0'))?$this->input->post('sSortDir_0'):'ASC';
        
        $sortfields = array(1=>'order_id');
        $sortFieldName = '';
        if(array_key_exists($sortCol, $sortfields))
        {
            $sortFieldName = $sortfields[$sortCol];
        }
        $displayLength = 5;
        //Get Recored from model
        $grid_data = $this->advance_dashboard_model->get_revenue_report($sortFieldName,$sortOrder,$displayStart,$displayLength);
        $totalRecords = $grid_data['total'];        
        $records = array();
        $records["aaData"] = array(); 
        $nCount = ($displayStart != '')?$displayStart+1:1;
        $default_currency = get_default_system_currency();
        $display_name =  "display_name_".$this->session->userdata('language_slug');
        foreach ($grid_data['data'] as $key => $val) {           

            $restaurant = ($val->restaurant_detail)?unserialize($val->restaurant_detail):'';            
            $view_order_details_btn = (in_array('order~view',$this->session->userdata("UserAccessArray"))) ? '<button class="btn btn-sm  default-btn danger-btn theme-btn margin-bottom-5" onclick="openOrderDetails('.$val->order_id.')" title="'.$this->lang->line('order_details').'"><i class="fa fa-eye"></i></button>' : '';
            $payment_option_detail = $this->common_model->get_payment_method_detail(strtolower($val->payment_option));
            $payment_method_name = $payment_option_detail->$display_name;
            if($val->order_delivery == "Delivery"){
                $order_delivery = $this->lang->line('delivery_order');
            }
            if($val->order_delivery == "PickUp"){
                $order_delivery = $this->lang->line('pickup');
            }
            if($val->order_delivery == "DineIn"){
                $order_delivery = $this->lang->line('dinein');
            }            

            // Order Status 
            if($val->order_status == "placed" && $val->status!='1')
            {
                $ostatuslng = $this->lang->line('placed');
            }
            if(($val->order_status == "placed" && $val->status=='1') || $val->order_status == "accepted")
            {
                $ostatuslng = $this->lang->line('accepted');
            }
            if($val->order_status == "rejected")
            {
                $ostatuslng = $this->lang->line('rejected');
            }
            if($val->order_status == "delivered"){
                $ostatuslng = $this->lang->line('delivered');
            }
            if($val->order_status == "onGoing")
            {
                $ostatuslng = $this->lang->line('onGoing');
                if($val->order_delivery == "PickUp")
                {
                    $ostatuslng = $this->lang->line('order_ready');
                }
            }
            
            if($val->order_status == "cancel"){
                $ostatuslng = $this->lang->line('cancel');
            }
            if($val->order_status == "ready"){
                $ostatuslng = $this->lang->line('order_ready');
            }
            if($val->order_status == "complete"){
                $ostatuslng = $this->lang->line('complete');
            }
            if($val->order_status == "pending"){
                $ostatuslng = $this->lang->line('pending');
            }

            $records["aaData"][] = array(
                /*$nCount,*/
                $val->order_id,
                $val->restaurant_name,
                $val->user_name,
                $payment_method_name,
                $order_delivery,
                ($val->subtotal) ? (!empty($default_currency)) ? currency_symboldisplay(number_format_unchanged_precision($val->subtotal,$default_currency->currency_code),$default_currency->currency_symbol) : currency_symboldisplay(number_format_unchanged_precision($val->subtotal,$restaurant->currency_code),$restaurant->currency_symbol) : '',
                ($val->tax_amount) ? (!empty($default_currency)) ? currency_symboldisplay(number_format_unchanged_precision($val->tax_amount,$default_currency->currency_code),$default_currency->currency_symbol) : currency_symboldisplay(number_format_unchanged_precision($val->tax_amount,$restaurant->currency_code),$restaurant->currency_symbol) : '',
                ($val->service_fee_amount) ? (!empty($default_currency)) ? currency_symboldisplay(number_format_unchanged_precision($val->service_fee_amount,$default_currency->currency_code),$default_currency->currency_symbol) : currency_symboldisplay(number_format_unchanged_precision($val->service_fee_amount,$restaurant->currency_code),$restaurant->currency_symbol) : '',
                ($val->delivery_charge) ? (!empty($default_currency)) ? currency_symboldisplay(number_format_unchanged_precision($val->delivery_charge,$default_currency->currency_code),$default_currency->currency_symbol) : currency_symboldisplay(number_format_unchanged_precision($val->delivery_charge,$restaurant->currency_code),$restaurant->currency_symbol) : '',
                ($val->tip_amount) ? (!empty($default_currency)) ? currency_symboldisplay(number_format_unchanged_precision($val->tip_amount,$default_currency->currency_code),$default_currency->currency_symbol) : currency_symboldisplay(number_format_unchanged_precision($val->tip_amount,$restaurant->currency_code),$restaurant->currency_symbol) : '',       
                ($val->coupon_discount) ? (!empty($default_currency)) ? currency_symboldisplay(number_format_unchanged_precision($val->coupon_discount,$default_currency->currency_code),$default_currency->currency_symbol) : currency_symboldisplay(number_format_unchanged_precision($val->coupon_discount,$restaurant->currency_code),$restaurant->currency_symbol) : '',
                ($val->total_rate) ? (!empty($default_currency)) ? currency_symboldisplay(number_format_unchanged_precision($val->total_rate,$default_currency->currency_code),$default_currency->currency_symbol) : currency_symboldisplay(number_format_unchanged_precision($val->total_rate,$restaurant->currency_code),$restaurant->currency_symbol) : '',
                $ostatuslng,
                $view_order_details_btn
            );
            $nCount++;
        }        
        $records["sEcho"] = $sEcho;
        $records["iTotalRecords"] = $totalRecords;
        $records["iTotalDisplayRecords"] = $totalRecords;
        echo json_encode($records);
    }

    public function get_revenue_totals(){
        $data = $this->advance_dashboard_model->get_revenue_report_total('','','','');     
        $default_currency = get_default_system_currency();        
        $base_total_amount = 0;
        if(!empty($data[0])){
            foreach ($data[0] as $key => $details) 
            {
                $records[$key] = currency_symboldisplay(number_format_unchanged_precision($details,$default_currency->currency_code),$default_currency->currency_symbol);
            }
        }
        echo json_encode($records);
    }

    // Export user data in Excel file
    public function revenue_report_export(){
        //get all invoice
        $data = $this->advance_dashboard_model->get_revenue_report("order_id",'desc','','');
        $results = $data['data'];

        $this->load->library('excel');
        $spreadsheet = new Excel();
        $spreadsheet->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Reports');
        $headers = array(
                $this->lang->line('order').' #',
                $this->lang->line('restaurant'),
                $this->lang->line('customer'),
                $this->lang->line('payment_method'), 
                $this->lang->line('order_type'),
                $this->lang->line('sub_total'),
                $this->lang->line('service_tax'),
                $this->lang->line('service_fee'),
                $this->lang->line('title_delivery_charges'),
                $this->lang->line('driver_tip'),
                $this->lang->line('coupon_used'),
                $this->lang->line('total_rate'),                
                $this->lang->line('order_status')
            );
        for($h=0,$c='A'; $h<count($headers); $h++,$c++)
        {
            $spreadsheet->getActiveSheet()->setCellValue($c.'1', $headers[$h]);
            $spreadsheet->getActiveSheet()->getStyle($c.'1')->getFont()->setBold(true);
        }
        $row = 2;
        $display_name =  "display_name_".$this->session->userdata('language_slug');
        for($r=0; $r<count($results); $r++)
        {
            $payment_option_detail = $this->common_model->get_payment_method_detail(strtolower($results[$r]->payment_option));
            $payment_method_name = $payment_option_detail->$display_name;
            if($results[$r]->order_delivery == "Delivery"){
                $order_delivery = $this->lang->line('delivery_order');
            }
            if($results[$r]->order_delivery == "PickUp"){
                $order_delivery = $this->lang->line('pickup');
            }
            if($results[$r]->order_delivery == "DineIn"){
                $order_delivery = $this->lang->line('dinein');
            }

            // Order Status 
            if($results[$r]->order_status == "placed" && $val->status!='1')
            {
                $ostatuslng = $this->lang->line('placed');
            }
            if(($results[$r]->order_status == "placed" && $results[$r]->status=='1') || $results[$r]->order_status == "accepted")
            {
                $ostatuslng = $this->lang->line('accepted');
            }
            if($results[$r]->order_status == "rejected")
            {
                $ostatuslng = $this->lang->line('rejected');
            }
            if($results[$r]->order_status == "delivered"){
                $ostatuslng = $this->lang->line('delivered');
            }
            if($results[$r]->order_status == "onGoing")
            {
                $ostatuslng = $this->lang->line('onGoing');
                if($results[$r]->order_delivery == "PickUp")
                {
                    $ostatuslng = $this->lang->line('order_ready');
                }
            }
            
            if($results[$r]->order_status == "cancel"){
                $ostatuslng = $this->lang->line('cancel');
            }
            if($results[$r]->order_status == "ready"){
                $ostatuslng = $this->lang->line('order_ready');
            }
            if($results[$r]->order_status == "complete"){
                $ostatuslng = $this->lang->line('complete');
            }
            if($results[$r]->order_status == "pending"){
                $ostatuslng = $this->lang->line('pending');
            }

            $spreadsheet->getActiveSheet()->setCellValue('A'.$row, $results[$r]->order_id);            
            $spreadsheet->getActiveSheet()->setCellValue('B'.$row, $results[$r]->restaurant_name);
            $spreadsheet->getActiveSheet()->setCellValue('C'.$row, $results[$r]->user_name);
            $spreadsheet->getActiveSheet()->setCellValue('D'.$row, $payment_method_name);
            $spreadsheet->getActiveSheet()->setCellValue('E'.$row, $order_delivery);
            $spreadsheet->getActiveSheet()->setCellValue('F'.$row, $results[$r]->subtotal);
            $spreadsheet->getActiveSheet()->setCellValue('G'.$row, $results[$r]->tax_amount);
            $spreadsheet->getActiveSheet()->setCellValue('H'.$row, $results[$r]->service_fee_amount);
            $spreadsheet->getActiveSheet()->setCellValue('I'.$row, $results[$r]->delivery_charge);
            $spreadsheet->getActiveSheet()->setCellValue('J'.$row, $results[$r]->tip_amount);
            $spreadsheet->getActiveSheet()->setCellValue('K'.$row, $results[$r]->coupon_discount);
            $spreadsheet->getActiveSheet()->setCellValue('L'.$row, $results[$r]->total_rate);            
            $spreadsheet->getActiveSheet()->setCellValue('M'.$row, $ostatuslng);
            $row++;
        }

        $object_writer = $spreadsheet->print_sheet($spreadsheet);
        ob_end_clean();
        // create directory if not exists
        if (!@is_dir('uploads/export_revenue')) {
            @mkdir('./uploads/export_revenue', 0777, TRUE);
        }
        $filename = 'uploads/export_revenue/revenue'.date('y-m-d').'.xlsx';
        $object_writer->save(str_replace(__FILE__,$filename ,__FILE__));
        //echo $filename;
        $response =  array(
            'status' => TRUE,
            'filename' => $filename,
        );    
        $this->common_model->save_user_log('Revenue Report Exported');
        die(json_encode($response));
    }
}