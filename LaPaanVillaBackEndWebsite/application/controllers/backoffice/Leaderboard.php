<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Leaderboard extends CI_Controller {
    public $controller_name = 'leaderboard';    
    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model(ADMIN_URL.'/advance_dashboard_model');
        $this->load->model(ADMIN_URL.'/leaderboard_model');
        if (!$this->session->userdata('is_admin_login')) {
            redirect(ADMIN_URL.'/home');
        }
    }

    public function revenue() {
        if(in_array('leaderboard~revenue',$this->session->userdata("UserAccessArray"))) {
            $arr['meta_title'] = $this->lang->line('title_admin_revenue').' | '.$this->lang->line('site_title');
            // Get payment methods
            $arr['payment_method'] =  $this->db->get('payment_method')->result();        
            $this->load->view(ADMIN_URL.'/leaderboard_revenue',$arr);
        } else {
            redirect(base_url().ADMIN_URL);
        }
    }

    public function ajaxview_revenue()
    {
        $displayLength = ($this->input->post('iDisplayLength') != '')?intval($this->input->post('iDisplayLength')):'';
        $displayStart = ($this->input->post('iDisplayStart') != '')?intval($this->input->post('iDisplayStart')):'';
        $sEcho = ($this->input->post('sEcho'))?intval($this->input->post('sEcho')):'';
        $sortCol = ($this->input->post('iSortCol_0') != '')?$this->input->post('iSortCol_0'):'';
        $sortOrder = ($this->input->post('sSortDir_0'))?$this->input->post('sSortDir_0'):'ASC';
        $sortfields = array(0=>'order_id',1=>'restaurant_name',2=>'user_name',4=>'order_delivery',5=>'subtotal',6=>'tax_amount',7=>'service_fee_amount',8=>'delivery_charge',9=>'tip_amount',10=>'coupon_discount',11=>'total_rate');
        $sortFieldName = '';
        if(array_key_exists($sortCol, $sortfields))
        {
            $sortFieldName = $sortfields[$sortCol];
        }
        //Get Recored from model
        $grid_data = $this->leaderboard_model->get_revenue_report($sortFieldName,$sortOrder,$displayStart,$displayLength);        
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
        $data = $this->leaderboard_model->get_revenue_report_total('','','','');     
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
        $data = $this->leaderboard_model->get_revenue_report("order_id",'desc','','');
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
                $this->lang->line('order_status'),
                $this->lang->line('order_date'),
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
            $spreadsheet->getActiveSheet()->setCellValue('N'.$row, ($results[$r]->created_date)?$this->common_model->getZonebaseDateMDY($results[$r]->created_date):'');
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


    // Coupouns 
    public function coupons() {
        if(in_array('leaderboard~coupons',$this->session->userdata("UserAccessArray"))) {
            $arr['meta_title'] = $this->lang->line('title_admin_coupons').' | '.$this->lang->line('site_title');
            $this->load->view(ADMIN_URL.'/leaderboard_coupons',$arr);
        } else {
            redirect(base_url().ADMIN_URL);
        }
    }

    public function ajaxview_coupons()
    {
        $displayLength = ($this->input->post('iDisplayLength') != '')?intval($this->input->post('iDisplayLength')):'';
        $displayStart = ($this->input->post('iDisplayStart') != '')?intval($this->input->post('iDisplayStart')):'';
        $sEcho = ($this->input->post('sEcho'))?intval($this->input->post('sEcho')):'';
        $sortCol = ($this->input->post('iSortCol_0') != '')?$this->input->post('iSortCol_0'):'';
        $sortOrder = ($this->input->post('sSortDir_0'))?$this->input->post('sSortDir_0'):'ASC';
        
        $sortfields = array(0=>'coupon_name',1=>'total_orders',2=>'coupon_discount_total');
        $sortFieldName = '';
        if(array_key_exists($sortCol, $sortfields))
        {
            $sortFieldName = $sortfields[$sortCol];
        }
        //Get Recored from model
        $grid_data = $this->leaderboard_model->get_coupons_report($sortFieldName,$sortOrder,$displayStart,$displayLength);
        $totalRecords = $grid_data['total'];        
        $records = array();
        $records["aaData"] = array(); 
        $nCount = ($displayStart != '')?$displayStart+1:1;
        $default_currency = get_default_system_currency();
        foreach ($grid_data['data'] as $key => $val) {  

            $records["aaData"][] = array(
                /*$nCount,*/
                $val->coupon_name,
                $val->total_orders,
                currency_symboldisplay(number_format_unchanged_precision($val->coupon_discount_total,$default_currency->currency_code),$default_currency->currency_symbol),
                ''
            );
            $nCount++;
        }        
        $records["sEcho"] = $sEcho;
        $records["iTotalRecords"] = $totalRecords;
        $records["iTotalDisplayRecords"] = $totalRecords;
        echo json_encode($records);
    }

    // Export user data in Excel file
    public function coupons_report_export(){
        //get all invoice
        $data = $this->leaderboard_model->get_coupons_report("total_orders",'desc','','');
        $default_currency = get_default_system_currency();
        $results = $data['data'];

        $this->load->library('excel');
        $spreadsheet = new Excel();
        $spreadsheet->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Reports');
        $headers = array(
                $this->lang->line('coupon_code'),
                $this->lang->line('orders'),
                $this->lang->line('discount'),
            );
        for($h=0,$c='A'; $h<count($headers); $h++,$c++)
        {
            $spreadsheet->getActiveSheet()->setCellValue($c.'1', $headers[$h]);
            $spreadsheet->getActiveSheet()->getStyle($c.'1')->getFont()->setBold(true);
        }
        $row = 2;
        for($r=0; $r<count($results); $r++)
        {
            $spreadsheet->getActiveSheet()->setCellValue('A'.$row, $results[$r]->coupon_name);            
            $spreadsheet->getActiveSheet()->setCellValue('B'.$row, $results[$r]->total_orders);
            $spreadsheet->getActiveSheet()->setCellValue('C'.$row, currency_symboldisplay(number_format_unchanged_precision($results[$r]->coupon_discount_total,$default_currency->currency_code),$default_currency->currency_symbol));
            $row++;
        }

        $object_writer = $spreadsheet->print_sheet($spreadsheet);
        ob_end_clean();
        // create directory if not exists
        if (!@is_dir('uploads/export_coupons')) {
            @mkdir('./uploads/export_coupons', 0777, TRUE);
        }
        $filename = 'uploads/export_coupons/coupons_report'.date('y-m-d').'.xlsx';
        $object_writer->save(str_replace(__FILE__,$filename ,__FILE__));
        //echo $filename;
        $response =  array(
            'status' => TRUE,
            'filename' => $filename,
        );    
        $this->common_model->save_user_log('Coupons Report Exported');
        die(json_encode($response));
    }

    // Restaurants 
    public function restaurants() {
        if(in_array('leaderboard~restaurants',$this->session->userdata("UserAccessArray"))) {
            $arr['meta_title'] = $this->lang->line('title_admin_restaurants').' | '.$this->lang->line('site_title');
            $this->load->view(ADMIN_URL.'/leaderboard_restuarants',$arr);
        } else {
            redirect(base_url().ADMIN_URL);
        }
    }

    public function ajaxview_restaurants()
    {
        $displayLength = ($this->input->post('iDisplayLength') != '')?intval($this->input->post('iDisplayLength')):'';
        $displayStart = ($this->input->post('iDisplayStart') != '')?intval($this->input->post('iDisplayStart')):'';
        $sEcho = ($this->input->post('sEcho'))?intval($this->input->post('sEcho')):'';
        $sortCol = ($this->input->post('iSortCol_0') != '')?$this->input->post('iSortCol_0'):'';
        $sortOrder = ($this->input->post('sSortDir_0'))?$this->input->post('sSortDir_0'):'ASC';
        
        $sortfields = array(0=>'name',1=>'total_item',2=>'total_amount');
        $sortFieldName = '';
        if(array_key_exists($sortCol, $sortfields))
        {
            $sortFieldName = $sortfields[$sortCol];
        }
        //Get Recored from model
        $grid_data = $this->leaderboard_model->get_restaurants_report($sortFieldName,$sortOrder,$displayStart,$displayLength);        
        $totalRecords = $grid_data['total'];        
        $records = array();
        $records["aaData"] = array(); 
        $nCount = ($displayStart != '')?$displayStart+1:1;
        $default_currency = get_default_system_currency();
        foreach ($grid_data['data'] as $key => $val) {  

            $records["aaData"][] = array(
                /*$nCount,*/
                $val->name,
                $val->total_item,
                currency_symboldisplay(number_format_unchanged_precision($val->total_amount,$default_currency->currency_code),$default_currency->currency_symbol),
                ''
            );
            $nCount++;
        }        
        $records["sEcho"] = $sEcho;
        $records["iTotalRecords"] = $totalRecords;
        $records["iTotalDisplayRecords"] = $totalRecords;
        echo json_encode($records);
    }

    // Export user data in Excel file
    public function restaurants_report_export(){
        //get all invoice
        $data = $this->leaderboard_model->get_restaurants_report("total_item",'desc','','');
        $default_currency = get_default_system_currency();
        $results = $data['data'];

        $this->load->library('excel');
        $spreadsheet = new Excel();
        $spreadsheet->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Reports');
        $headers = array(
                $this->lang->line('name'),
                $this->lang->line('admin_item_sold'),
                $this->lang->line('admin_net_sale'),
            );
        for($h=0,$c='A'; $h<count($headers); $h++,$c++)
        {
            $spreadsheet->getActiveSheet()->setCellValue($c.'1', $headers[$h]);
            $spreadsheet->getActiveSheet()->getStyle($c.'1')->getFont()->setBold(true);
        }
        $row = 2;
        for($r=0; $r<count($results); $r++)
        {
            $spreadsheet->getActiveSheet()->setCellValue('A'.$row, $results[$r]->name);            
            $spreadsheet->getActiveSheet()->setCellValue('B'.$row, $results[$r]->total_item);
            $spreadsheet->getActiveSheet()->setCellValue('C'.$row, currency_symboldisplay(number_format_unchanged_precision($results[$r]->total_amount,$default_currency->currency_code),$default_currency->currency_symbol));
            $row++;
        }

        $object_writer = $spreadsheet->print_sheet($spreadsheet);
        ob_end_clean();
        // create directory if not exists
        if (!@is_dir('uploads/export_restaurants')) {
            @mkdir('./uploads/export_restaurants', 0777, TRUE);
        }
        $filename = 'uploads/export_restaurants/restaurants_report'.date('y-m-d').'.xlsx';
        $object_writer->save(str_replace(__FILE__,$filename ,__FILE__));
        //echo $filename;
        $response =  array(
            'status' => TRUE,
            'filename' => $filename,
        );    
        $this->common_model->save_user_log('Restaurants Report Exported');
        die(json_encode($response));
    }

    // Customers 
    public function customers() {
        if(in_array('leaderboard~customers',$this->session->userdata("UserAccessArray"))) {
            $arr['meta_title'] = $this->lang->line('title_admin_customers').' | '.$this->lang->line('site_title');
            $this->load->view(ADMIN_URL.'/leaderboard_customers',$arr);
        } else {
            redirect(base_url().ADMIN_URL);
        }
    }

    public function ajaxview_customers()
    {
        $displayLength = ($this->input->post('iDisplayLength') != '')?intval($this->input->post('iDisplayLength')):'';
        $displayStart = ($this->input->post('iDisplayStart') != '')?intval($this->input->post('iDisplayStart')):'';
        $sEcho = ($this->input->post('sEcho'))?intval($this->input->post('sEcho')):'';
        $sortCol = ($this->input->post('iSortCol_0') != '')?$this->input->post('iSortCol_0'):'';
        $sortOrder = ($this->input->post('sSortDir_0'))?$this->input->post('sSortDir_0'):'ASC';
        
        $sortfields = array(0=>'user_name',1=>'total_orders',2=>'order_total');
        $sortFieldName = '';
        if(array_key_exists($sortCol, $sortfields))
        {
            $sortFieldName = $sortfields[$sortCol];
        }
        //Get Recored from model
        $grid_data = $this->leaderboard_model->get_customers_report($sortFieldName,$sortOrder,$displayStart,$displayLength);        
        $totalRecords = $grid_data['total'];        
        $records = array();
        $records["aaData"] = array(); 
        $nCount = ($displayStart != '')?$displayStart+1:1;
        $default_currency = get_default_system_currency();
        foreach ($grid_data['data'] as $key => $val) {  

            $records["aaData"][] = array(
                /*$nCount,*/
                $val->user_name,
                $val->total_orders,
                currency_symboldisplay(number_format_unchanged_precision($val->order_total,$default_currency->currency_code),$default_currency->currency_symbol),
                ''
            );
            $nCount++;
        }        
        $records["sEcho"] = $sEcho;
        $records["iTotalRecords"] = $totalRecords;
        $records["iTotalDisplayRecords"] = $totalRecords;
        echo json_encode($records);
    }

    // Export user data in Excel file
    public function customers_report_export(){
        //get all invoice
        $data = $this->leaderboard_model->get_customers_report("total_orders",'desc','','');
        $default_currency = get_default_system_currency();
        $results = $data['data'];

        $this->load->library('excel');
        $spreadsheet = new Excel();
        $spreadsheet->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Reports');
        $headers = array(
                $this->lang->line('name'),
                $this->lang->line('orders'),
                $this->lang->line('total_spend'),
            );
        for($h=0,$c='A'; $h<count($headers); $h++,$c++)
        {
            $spreadsheet->getActiveSheet()->setCellValue($c.'1', $headers[$h]);
            $spreadsheet->getActiveSheet()->getStyle($c.'1')->getFont()->setBold(true);
        }
        $row = 2;
        for($r=0; $r<count($results); $r++)
        {
            $spreadsheet->getActiveSheet()->setCellValue('A'.$row, $results[$r]->user_name);            
            $spreadsheet->getActiveSheet()->setCellValue('B'.$row, $results[$r]->total_orders);
            $spreadsheet->getActiveSheet()->setCellValue('C'.$row, currency_symboldisplay(number_format_unchanged_precision($results[$r]->order_total,$default_currency->currency_code),$default_currency->currency_symbol));
            $row++;
        }

        $object_writer = $spreadsheet->print_sheet($spreadsheet);
        ob_end_clean();
        // create directory if not exists
        if (!@is_dir('uploads/export_customers')) {
            @mkdir('./uploads/export_customers', 0777, TRUE);
        }
        $filename = 'uploads/export_customers/customers_report'.date('y-m-d').'.xlsx';
        $object_writer->save(str_replace(__FILE__,$filename ,__FILE__));
        //echo $filename;
        $response =  array(
            'status' => TRUE,
            'filename' => $filename,
        );    
        $this->common_model->save_user_log('Customers Report Exported');
        die(json_encode($response));
    }

    // Products 
    public function products() {
        if(in_array('leaderboard~products',$this->session->userdata("UserAccessArray"))) {
            $arr['meta_title'] = $this->lang->line('title_admin_products').' | '.$this->lang->line('site_title');
            $this->load->view(ADMIN_URL.'/leaderboard_products',$arr);
        } else {
            redirect(base_url().ADMIN_URL);
        }
    }

    public function ajaxview_products()
    {
        $displayLength = ($this->input->post('iDisplayLength') != '')?intval($this->input->post('iDisplayLength')):'';
        $displayStart = ($this->input->post('iDisplayStart') != '')?intval($this->input->post('iDisplayStart')):'';
        $sEcho = ($this->input->post('sEcho'))?intval($this->input->post('sEcho')):'';
        $sortCol = ($this->input->post('iSortCol_0') != '')?$this->input->post('iSortCol_0'):'';
        $sortOrder = ($this->input->post('sSortDir_0'))?$this->input->post('sSortDir_0'):'ASC';
        
        $sortfields = array(0=>'menu_name',1=>'total_order',2=>'total_amount');
        $sortFieldName = '';
        if(array_key_exists($sortCol, $sortfields))
        {
            $sortFieldName = $sortfields[$sortCol];
        }
        //Get Recored from model
        $grid_data = $this->leaderboard_model->get_products_report($sortFieldName,$sortOrder,$displayStart,$displayLength);
        
        $totalRecords = $grid_data['total'];        
        $records = array();
        $records["aaData"] = array(); 
        $nCount = ($displayStart != '')?$displayStart+1:1;
        $default_currency = get_default_system_currency();
        foreach ($grid_data['data'] as $key => $val) {  

            $records["aaData"][] = array(
                /*$nCount,*/
                $val->menu_name,
                $val->total_order,
                currency_symboldisplay(number_format_unchanged_precision($val->total_amount,$default_currency->currency_code),$default_currency->currency_symbol),
                ''
            );
            $nCount++;
        }        
        $records["sEcho"] = $sEcho;
        $records["iTotalRecords"] = $totalRecords;
        $records["iTotalDisplayRecords"] = $totalRecords;
        echo json_encode($records);
    }

    public function products_report_export(){
        //get all invoice
        $data = $this->leaderboard_model->get_products_report("total_order",'desc','','');
        $default_currency = get_default_system_currency();
        $results = $data['data'];

        $this->load->library('excel');
        $spreadsheet = new Excel();
        $spreadsheet->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Reports');
        $headers = array(
                $this->lang->line('product_name'),
                $this->lang->line('order'),
                $this->lang->line('admin_net_sale'),
            );
        for($h=0,$c='A'; $h<count($headers); $h++,$c++)
        {
            $spreadsheet->getActiveSheet()->setCellValue($c.'1', $headers[$h]);
            $spreadsheet->getActiveSheet()->getStyle($c.'1')->getFont()->setBold(true);
        }
        $row = 2;
        for($r=0; $r<count($results); $r++)
        {
            $spreadsheet->getActiveSheet()->setCellValue('A'.$row, $results[$r]->menu_name);            
            $spreadsheet->getActiveSheet()->setCellValue('B'.$row, $results[$r]->total_order);
            $spreadsheet->getActiveSheet()->setCellValue('C'.$row, currency_symboldisplay(number_format_unchanged_precision($results[$r]->total_amount,$default_currency->currency_code),$default_currency->currency_symbol));
            $row++;
        }

        $object_writer = $spreadsheet->print_sheet($spreadsheet);
        ob_end_clean();
        // create directory if not exists
        if (!@is_dir('uploads/export_products')) {
            @mkdir('./uploads/export_products', 0777, TRUE);
        }
        $filename = 'uploads/export_products/products_report'.date('y-m-d').'.xlsx';
        $object_writer->save(str_replace(__FILE__,$filename ,__FILE__));
        //echo $filename;
        $response =  array(
            'status' => TRUE,
            'filename' => $filename,
        );    
        $this->common_model->save_user_log('Products Report Exported');
        die(json_encode($response));
    }

    // Products 
    public function categories() {
        if(in_array('leaderboard~products',$this->session->userdata("UserAccessArray"))) {
            $arr['meta_title'] = $this->lang->line('title_admin_categories').' | '.$this->lang->line('site_title');
            $this->load->view(ADMIN_URL.'/leaderboard_categories',$arr);
        } else {
            redirect(base_url().ADMIN_URL);
        }
    }

    public function ajaxview_categories()
    {
        $displayLength = ($this->input->post('iDisplayLength') != '')?intval($this->input->post('iDisplayLength')):'';
        $displayStart = ($this->input->post('iDisplayStart') != '')?intval($this->input->post('iDisplayStart')):'';
        $sEcho = ($this->input->post('sEcho'))?intval($this->input->post('sEcho')):'';
        $sortCol = ($this->input->post('iSortCol_0') != '')?$this->input->post('iSortCol_0'):'';
        $sortOrder = ($this->input->post('sSortDir_0'))?$this->input->post('sSortDir_0'):'ASC';
        
        $sortfields = array(0=>'category_name',1=>'total_item',2=>'total_amount');
        $sortFieldName = '';
        if(array_key_exists($sortCol, $sortfields))
        {
            $sortFieldName = $sortfields[$sortCol];
        }
        //Get Recored from model
        $grid_data = $this->leaderboard_model->get_categories_report($sortFieldName,$sortOrder,$displayStart,$displayLength);
        
        $totalRecords = $grid_data['total'];        
        $records = array();
        $records["aaData"] = array(); 
        $nCount = ($displayStart != '')?$displayStart+1:1;
        $default_currency = get_default_system_currency();
        foreach ($grid_data['data'] as $key => $val) {  

            $records["aaData"][] = array(
                /*$nCount,*/
                $val->category_name,
                $val->total_item,
                currency_symboldisplay(number_format_unchanged_precision($val->total_amount,$default_currency->currency_code),$default_currency->currency_symbol),
                ''
            );
            $nCount++;
        }        
        $records["sEcho"] = $sEcho;
        $records["iTotalRecords"] = $totalRecords;
        $records["iTotalDisplayRecords"] = $totalRecords;
        echo json_encode($records);
    }

    public function categories_report_export(){
        //get all invoice
        $data = $this->leaderboard_model->get_categories_report("total_item",'desc','','');
        $default_currency = get_default_system_currency();
        $results = $data['data'];

        $this->load->library('excel');
        $spreadsheet = new Excel();
        $spreadsheet->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('Reports');
        $headers = array(
                $this->lang->line('category'),
                $this->lang->line('admin_item_sold'),
                $this->lang->line('admin_net_sale'),
            );
        for($h=0,$c='A'; $h<count($headers); $h++,$c++)
        {
            $spreadsheet->getActiveSheet()->setCellValue($c.'1', $headers[$h]);
            $spreadsheet->getActiveSheet()->getStyle($c.'1')->getFont()->setBold(true);
        }
        $row = 2;
        for($r=0; $r<count($results); $r++)
        {
            $spreadsheet->getActiveSheet()->setCellValue('A'.$row, $results[$r]->category_name);            
            $spreadsheet->getActiveSheet()->setCellValue('B'.$row, $results[$r]->total_item);
            $spreadsheet->getActiveSheet()->setCellValue('C'.$row, currency_symboldisplay(number_format_unchanged_precision($results[$r]->total_amount,$default_currency->currency_code),$default_currency->currency_symbol));
            $row++;
        }

        $object_writer = $spreadsheet->print_sheet($spreadsheet);
        ob_end_clean();
        // create directory if not exists
        if (!@is_dir('uploads/export_categories')) {
            @mkdir('./uploads/export_categories', 0777, TRUE);
        }
        $filename = 'uploads/export_categories/categories_report'.date('y-m-d').'.xlsx';
        $object_writer->save(str_replace(__FILE__,$filename ,__FILE__));
        //echo $filename;
        $response =  array(
            'status' => TRUE,
            'filename' => $filename,
        );    
        $this->common_model->save_user_log('Categories Report Exported');
        die(json_encode($response));
    }
}