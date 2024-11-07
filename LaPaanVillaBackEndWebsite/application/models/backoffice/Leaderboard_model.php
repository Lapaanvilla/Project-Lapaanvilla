<?php
class Leaderboard_model extends CI_Model {
    function __construct()
    {
        parent::__construct();
    }

    public function get_revenue_report($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10)
    {
        $status_arr = array('delivered','complete');  
        // Get Total
        if($this->input->post('order_id') != ''){
            $this->db->like('order_master.entity_id', trim($this->input->post('order_id')));
        }        
        if($this->input->post('restaurant') != ''){
            $name = $this->common_model->escapeString(trim($this->input->post('restaurant')));
            $where = "(restaurant.name LIKE '%".$this->common_model->escapeString(trim($this->input->post('restaurant')))."%' OR (order_detail.restaurant_detail REGEXP '.*".'"'."name".'"'.";s:[0-9]+:".'"'."$name".'"'.".*'))";
            $this->db->or_where($where);
        }
        if($this->input->post('customer_name') != ''){
            $where_string="(order_detail.user_name like '%".$this->common_model->escapeString(trim($this->input->post('customer_name')))."%')";
            $this->db->where($where_string);
        }
        if($this->input->post('sub_total') != ''){
            $sub_total = trim($this->input->post('sub_total'));
            if($sub_total[0] == '$')
            {
                $sub_total_search = substr($sub_total, 1);
                $this->db->like('order_master.subtotal', trim($sub_total_search));
            }else{
                $this->db->like('order_master.subtotal', trim($this->input->post('sub_total')));
            }
        }
        if($this->input->post('sales_tax') != ''){
            $sales_tax = trim($this->input->post('sales_tax'));
            if($sales_tax[0] == '$')
            {
                $sales_tax_search = substr($sales_tax, 1);
                $this->db->like('order_master.tax_amount', trim($sales_tax_search));
            }else{
                $this->db->like('order_master.tax_amount', trim($this->input->post('sales_tax')));
            }
        }
        if($this->input->post('service_fee') != ''){
            $service_fee = trim($this->input->post('service_fee'));
            if($service_fee[0] == '$')
            {
                $service_fee_search = substr($service_fee, 1);
                $this->db->like('order_master.service_fee_amount', trim($service_fee_search));
            }else{
                $this->db->like('order_master.service_fee_amount', trim($this->input->post('service_fee')));
            }
        }
        if($this->input->post('delivery_charges') != ''){
            $delivery_charges = trim($this->input->post('delivery_charges'));
            if($delivery_charges[0] == '$')
            {
                $delivery_charges_search = substr($delivery_charges, 1);
                $this->db->like('order_master.delivery_charge', trim($delivery_charges_search));
            }else{
                $this->db->like('order_master.delivery_charge', trim($this->input->post('delivery_charges')));
            }
        }
        if($this->input->post('driver_tips') != ''){
            $driver_tips = trim($this->input->post('driver_tips'));
            if($driver_tips[0] == '$')
            {
                $driver_tips_search = substr($driver_tips, 1);
                $this->db->like('tips.amount', trim($driver_tips_search));
            }else{
                $this->db->like('tips.amount', trim($this->input->post('driver_tips')));
            }
        }
        if($this->input->post('coupon_discount') != ''){
            $coupon_discount = trim($this->input->post('coupon_discount'));
            if($coupon_discount[0] == '$')
            {
                $coupon_discount_search = substr($coupon_discount, 1);
                $this->db->like('order_master.coupon_discount', trim($coupon_discount_search));
            }else{
                $this->db->like('order_master.coupon_discount', trim($this->input->post('coupon_discount')));
            }
        }
        if($this->input->post('total_rate') != ''){
            $total_rate = trim($this->input->post('total_rate'));
            if($total_rate[0] == '$')
            {
                $total_rate_search = substr($total_rate, 1);
                $this->db->like('order_master.total_rate', trim($total_rate_search));
            }else{
                $this->db->like('order_master.total_rate', trim($this->input->post('total_rate')));
            }
        }
        if($this->input->post('payment_method') != ''){
            $this->db->where('order_master.payment_option',trim($this->input->post('payment_method')));
        }
        if($this->input->post('order_delivery') != ''){
            $this->db->where('order_master.order_delivery',trim($this->input->post('order_delivery')));
        }      
        $this->db->select(
            'order_master.entity_id as order_id,restaurant.name as restaurant_name,order_detail.user_name,order_master.subtotal,
            ,order_master.delivery_charge,order_master.coupon_discount,order_master.total_rate,order_master.payment_option,order_master.order_delivery,order_master.order_status,order_master.status,tips.amount as tip_amount,order_detail.restaurant_detail,order_master.tax_amount,order_master.service_fee_amount,order_master.created_date',
        );
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id');
        $this->db->join('order_detail','order_detail.order_id = order_master.entity_id','left');
        $this->db->join('tips','order_master.entity_id = tips.order_id','left');
        if($this->session->userdata('AdminUserType') == 'Restaurant Admin'){
            $this->db->where('restaurant.restaurant_owner_id',$this->session->userdata('AdminUserID'));
        }
        if($this->session->userdata('AdminUserType') == 'Branch Admin'){
            $this->db->where('restaurant.branch_admin_id',$this->session->userdata('AdminUserID'));
        }
        $this->db->where_in('order_master.order_status',$status_arr);
        $this->db->where('order_master.stripe_refund_id',null);
        $result['total'] = $this->db->count_all_results('order_master');

        // Get Data
        if($this->input->post('order_id') != ''){
            $this->db->like('order_master.entity_id', trim($this->input->post('order_id')));
        }        
        if($this->input->post('restaurant') != ''){
            $name = $this->common_model->escapeString(trim($this->input->post('restaurant')));
            $where = "(restaurant.name LIKE '%".$this->common_model->escapeString(trim($this->input->post('restaurant')))."%' OR (order_detail.restaurant_detail REGEXP '.*".'"'."name".'"'.";s:[0-9]+:".'"'."$name".'"'.".*'))";
            $this->db->or_where($where);
        }
        if($this->input->post('customer_name') != ''){
            $where_string="(order_detail.user_name like '%".$this->common_model->escapeString(trim($this->input->post('customer_name')))."%')";
            $this->db->where($where_string);
        }
        if($this->input->post('sub_total') != ''){
            $sub_total = trim($this->input->post('sub_total'));
            if($sub_total[0] == '$')
            {
                $sub_total_search = substr($sub_total, 1);
                $this->db->like('order_master.subtotal', trim($sub_total_search));
            }else{
                $this->db->like('order_master.subtotal', trim($this->input->post('sub_total')));
            }
        }
        if($this->input->post('sales_tax') != ''){
            $sales_tax = trim($this->input->post('sales_tax'));
            if($sales_tax[0] == '$')
            {
                $sales_tax_search = substr($sales_tax, 1);
                $this->db->like('order_master.tax_amount', trim($sales_tax_search));
            }else{
                $this->db->like('order_master.tax_amount', trim($this->input->post('sales_tax')));
            }
        }
        if($this->input->post('service_fee') != ''){
            $service_fee = trim($this->input->post('service_fee'));
            if($service_fee[0] == '$')
            {
                $service_fee_search = substr($service_fee, 1);
                $this->db->like('order_master.service_fee_amount', trim($service_fee_search));
            }else{
                $this->db->like('order_master.service_fee_amount', trim($this->input->post('service_fee')));
            }
        }
        if($this->input->post('delivery_charges') != ''){
            $delivery_charges = trim($this->input->post('delivery_charges'));
            if($delivery_charges[0] == '$')
            {
                $delivery_charges_search = substr($delivery_charges, 1);
                $this->db->like('order_master.delivery_charge', trim($delivery_charges_search));
            }else{
                $this->db->like('order_master.delivery_charge', trim($this->input->post('delivery_charges')));
            }
        }
        if($this->input->post('driver_tips') != ''){
            $driver_tips = trim($this->input->post('driver_tips'));
            if($driver_tips[0] == '$')
            {
                $driver_tips_search = substr($driver_tips, 1);
                $this->db->like('tips.amount', trim($driver_tips_search));
            }else{
                $this->db->like('tips.amount', trim($this->input->post('driver_tips')));
            }
        }
        if($this->input->post('coupon_discount') != ''){
            $coupon_discount = trim($this->input->post('coupon_discount'));
            if($coupon_discount[0] == '$')
            {
                $coupon_discount_search = substr($coupon_discount, 1);
                $this->db->like('order_master.coupon_discount', trim($coupon_discount_search));
            }else{
                $this->db->like('order_master.coupon_discount', trim($this->input->post('coupon_discount')));
            }
        }
        if($this->input->post('total_rate') != ''){
            $total_rate = trim($this->input->post('total_rate'));
            if($total_rate[0] == '$')
            {
                $total_rate_search = substr($total_rate, 1);
                $this->db->like('order_master.total_rate', trim($total_rate_search));
            }else{
                $this->db->like('order_master.total_rate', trim($this->input->post('total_rate')));
            }
        }
        if($this->input->post('payment_method') != ''){
            $this->db->where('order_master.payment_option',trim($this->input->post('payment_method')));
        }
        if($this->input->post('order_delivery') != ''){
            $this->db->where('order_master.order_delivery',trim($this->input->post('order_delivery')));
        }
              
        $this->db->select(
            'order_master.entity_id as order_id,restaurant.name as restaurant_name,order_detail.user_name,order_master.subtotal,
            ,order_master.delivery_charge,order_master.coupon_discount,order_master.total_rate,order_master.payment_option,order_master.order_delivery,order_master.order_status,order_master.status,tips.amount as tip_amount,order_detail.restaurant_detail,order_master.tax_amount,order_master.service_fee_amount,order_master.created_date',
        );
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id');
        $this->db->join('order_detail','order_detail.order_id = order_master.entity_id','left');
        $this->db->join('tips','order_master.entity_id = tips.order_id','left');
        if($this->session->userdata('AdminUserType') == 'Restaurant Admin'){
            $this->db->where('restaurant.restaurant_owner_id',$this->session->userdata('AdminUserID'));
        }
        if($this->session->userdata('AdminUserType') == 'Branch Admin'){
            $this->db->where('restaurant.branch_admin_id',$this->session->userdata('AdminUserID'));
        }
        $this->db->where_in('order_master.order_status',$status_arr);
        $this->db->where('order_master.stripe_refund_id',null);
        if($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);
        if($displayLength>1)
            $this->db->limit($displayLength,$displayStart);
        $result_data = $this->db->get('order_master')->result();        
        $result['data'] = $result_data;        
        return $result;
    }

    public function get_revenue_report_total()
    {
        $status_arr = array('delivered','complete');
        if($this->input->post('order_id') != ''){
            $this->db->like('order_master.entity_id', trim($this->input->post('order_id')));
        }        
        if($this->input->post('restaurant') != ''){
            $name = $this->common_model->escapeString(trim($this->input->post('restaurant')));
            $where = "(restaurant.name LIKE '%".$this->common_model->escapeString(trim($this->input->post('restaurant')))."%' OR (order_detail.restaurant_detail REGEXP '.*".'"'."name".'"'.";s:[0-9]+:".'"'."$name".'"'.".*'))";
            $this->db->or_where($where);
        }
        if($this->input->post('customer_name') != ''){
            $where_string="(order_detail.user_name like '%".$this->common_model->escapeString(trim($this->input->post('customer_name')))."%')";
            $this->db->where($where_string);
        }
        if($this->input->post('sub_total') != ''){
            $sub_total = trim($this->input->post('sub_total'));
            if($sub_total[0] == '$')
            {
                $sub_total_search = substr($sub_total, 1);
                $this->db->like('order_master.subtotal', trim($sub_total_search));
            }else{
                $this->db->like('order_master.subtotal', trim($this->input->post('sub_total')));
            }
        }
        if($this->input->post('sales_tax') != ''){
            $sales_tax = trim($this->input->post('sales_tax'));
            if($sales_tax[0] == '$')
            {
                $sales_tax_search = substr($sales_tax, 1);
                $this->db->like('order_master.tax_amount', trim($sales_tax_search));
            }else{
                $this->db->like('order_master.tax_amount', trim($this->input->post('sales_tax')));
            }
        }
        if($this->input->post('service_fee') != ''){
            $service_fee = trim($this->input->post('service_fee'));
            if($service_fee[0] == '$')
            {
                $service_fee_search = substr($service_fee, 1);
                $this->db->like('order_master.service_fee_amount', trim($service_fee_search));
            }else{
                $this->db->like('order_master.service_fee_amount', trim($this->input->post('service_fee')));
            }
        }
        if($this->input->post('delivery_charges') != ''){
            $delivery_charges = trim($this->input->post('delivery_charges'));
            if($delivery_charges[0] == '$')
            {
                $delivery_charges_search = substr($delivery_charges, 1);
                $this->db->like('order_master.delivery_charge', trim($delivery_charges_search));
            }else{
                $this->db->like('order_master.delivery_charge', trim($this->input->post('delivery_charges')));
            }
        }
        if($this->input->post('driver_tips') != ''){
            $driver_tips = trim($this->input->post('driver_tips'));
            if($driver_tips[0] == '$')
            {
                $driver_tips_search = substr($driver_tips, 1);
                $this->db->like('tips.amount', trim($driver_tips_search));
            }else{
                $this->db->like('tips.amount', trim($this->input->post('driver_tips')));
            }
        }
        if($this->input->post('coupon_discount') != ''){
            $coupon_discount = trim($this->input->post('coupon_discount'));
            if($coupon_discount[0] == '$')
            {
                $coupon_discount_search = substr($coupon_discount, 1);
                $this->db->like('order_master.coupon_discount', trim($coupon_discount_search));
            }else{
                $this->db->like('order_master.coupon_discount', trim($this->input->post('coupon_discount')));
            }
        }
        if($this->input->post('total_rate') != ''){
            $total_rate = trim($this->input->post('total_rate'));
            if($total_rate[0] == '$')
            {
                $total_rate_search = substr($total_rate, 1);
                $this->db->like('order_master.total_rate', trim($total_rate_search));
            }else{
                $this->db->like('order_master.total_rate', trim($this->input->post('total_rate')));
            }
        }
        if($this->input->post('payment_method') != ''){
            $this->db->where('order_master.payment_option',trim($this->input->post('payment_method')));
        }
        if($this->input->post('order_delivery') != ''){
            $this->db->where('order_master.order_delivery',trim($this->input->post('order_delivery')));
        }                
        $this->db->select(
            'sum(order_master.subtotal) as revenue_subtotal,            
            sum(order_master.tax_amount) as revenue_tax_rate,            
            sum(order_master.service_fee_amount) as revenue_service_fee,            
            sum(order_master.delivery_charge) as revenue_delivery_charge,
            sum(order_master.coupon_discount) as revenue_coupon_discount,
            sum(ROUND(order_master.total_rate,2)) as revenue_total_rate,
            sum(tips.amount) as revenue_tip_amount',
        );

        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id');
        $this->db->join('order_detail','order_detail.order_id = order_master.entity_id','left');
        $this->db->join('tips','order_master.entity_id = tips.order_id','left');
        if($this->session->userdata('AdminUserType') == 'Restaurant Admin'){
            $this->db->where('restaurant.restaurant_owner_id',$this->session->userdata('AdminUserID'));
        }
        if($this->session->userdata('AdminUserType') == 'Branch Admin'){
            $this->db->where('restaurant.branch_admin_id',$this->session->userdata('AdminUserID'));
        }
        $this->db->where_in('order_master.order_status',$status_arr);
        $this->db->where('order_master.stripe_refund_id',null);
        $result = $this->db->get('order_master')->result();        
        return $result;
    }

    public function get_coupons_report($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10){   
        $status_arr = array('cancel','rejected');
        if($this->input->post('coupon_code') != ''){
            $this->db->like('order_master.coupon_name', trim($this->input->post('coupon_code')));
        }
        $this->db->select('sum(coupon_discount) as coupon_discount_total,coupon_name,count(order_master.entity_id) as total_orders');
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id');
        if($this->session->userdata('AdminUserType') == 'Restaurant Admin'){
            $this->db->where('restaurant.restaurant_owner_id',$this->session->userdata('AdminUserID'));
        }
        if($this->session->userdata('AdminUserType') == 'Branch Admin'){
            $this->db->where('restaurant.branch_admin_id',$this->session->userdata('AdminUserID'));
        }
        $this->db->where_not_in('order_master.order_status',$status_arr);
        $this->db->where('order_master.stripe_refund_id',null);
        $this->db->where('order_master.coupon_name != ""');
        $this->db->where('order_master.coupon_id != ""');
        $this->db->where('order_master.coupon_discount > 0');
        $this->db->group_by('coupon_id');
        $result['total'] = $this->db->count_all_results('order_master');

        if($this->input->post('coupon_code') != ''){
            $this->db->like('order_master.coupon_name', trim($this->input->post('coupon_code')));
        }
        $this->db->select('sum(coupon_discount) as coupon_discount_total,coupon_name,count(order_master.entity_id) as total_orders');
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id');
        if($this->session->userdata('AdminUserType') == 'Restaurant Admin'){
            $this->db->where('restaurant.restaurant_owner_id',$this->session->userdata('AdminUserID'));
        }
        if($this->session->userdata('AdminUserType') == 'Branch Admin'){
            $this->db->where('restaurant.branch_admin_id',$this->session->userdata('AdminUserID'));
        }
        $this->db->where_not_in('order_master.order_status',$status_arr);
        $this->db->where('order_master.stripe_refund_id',null);
        $this->db->where('order_master.coupon_name != ""');
        $this->db->where('order_master.coupon_id != ""');
        $this->db->where('order_master.coupon_discount > 0');
        $this->db->group_by('coupon_id'); 

        if($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);
        if($displayLength>1)
            $this->db->limit($displayLength,$displayStart);
        $result['data'] = $this->db->get('order_master')->result();        
        return $result;
    }

    public function get_restaurants_report($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10){   
        $status_arr = array('cancel','rejected');

        if($this->input->post('name') != ''){
            $this->db->like('restaurant.name', trim($this->input->post('name')));
        }
        //$this->db->select(' restaurant.name,count(distinct(order_detail_items.order_id)) as total_order,order_detail_items.restaurant_content_id,sum(order_detail_items.quantity) as total_item,sum(order_detail_items.itemTotal) as total_amount');        
        $this->db->select(' restaurant.name,count(distinct(order_detail_items.order_id)) as total_order,sum(order_detail_items.quantity) as total_item,sum(order_detail_items.itemTotal) as total_amount');        
        $this->db->join('order_detail_items','order_master.entity_id = order_detail_items.order_id');
        //$this->db->join('restaurant_menu_item','restaurant_menu_item.content_id = order_detail_items.menu_content_id');
        //$this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id');
        $this->db->join('restaurant','order_detail_items.restaurant_content_id = restaurant.content_id');
        if($this->session->userdata('AdminUserType') == 'Restaurant Admin'){
            $this->db->where('restaurant.restaurant_owner_id',$this->session->userdata('AdminUserID'));
        }
        if($this->session->userdata('AdminUserType') == 'Branch Admin'){
            $this->db->where('restaurant.branch_admin_id',$this->session->userdata('AdminUserID'));
        }
        $this->db->where_not_in('order_master.order_status',$status_arr);
        $this->db->where('order_master.stripe_refund_id',null);                
        $this->db->where('restaurant.language_slug',$this->session->userdata('language_slug'));                
        $this->db->group_by('order_detail_items.restaurant_content_id'); 
        $result['total'] = $this->db->count_all_results('order_master');

        if($this->input->post('name') != ''){
            $this->db->like('restaurant.name', trim($this->input->post('name')));
        }
        //$this->db->select(' restaurant.name,count(distinct(order_detail_items.order_id)) as total_order,order_detail_items.restaurant_content_id,sum(order_detail_items.quantity) as total_item,sum(order_detail_items.itemTotal) as total_amount');        
        $this->db->select(' restaurant.name,count(distinct(order_detail_items.order_id)) as total_order,sum(order_detail_items.quantity) as total_item,sum(order_detail_items.itemTotal) as total_amount');        
        $this->db->join('order_detail_items','order_master.entity_id = order_detail_items.order_id');
        //$this->db->join('restaurant_menu_item','restaurant_menu_item.content_id = order_detail_items.menu_content_id');
        //$this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id');
        $this->db->join('restaurant','order_detail_items.restaurant_content_id = restaurant.content_id');
        if($this->session->userdata('AdminUserType') == 'Restaurant Admin'){
            $this->db->where('restaurant.restaurant_owner_id',$this->session->userdata('AdminUserID'));
        }
        if($this->session->userdata('AdminUserType') == 'Branch Admin'){
            $this->db->where('restaurant.branch_admin_id',$this->session->userdata('AdminUserID'));
        }
        $this->db->where_not_in('order_master.order_status',$status_arr);
        $this->db->where('order_master.stripe_refund_id',null);                
        $this->db->where('restaurant.language_slug',$this->session->userdata('language_slug'));                
        $this->db->group_by('order_detail_items.restaurant_content_id'); 

        if($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);
        if($displayLength>1)
            $this->db->limit($displayLength,$displayStart);
        $result['data'] = $this->db->get('order_master')->result();        
        return $result;
    }

    public function get_customers_report($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10){

        $status_arr = array('cancel','rejected');
        if($this->input->post('name') != ''){
            $where_string="((CASE WHEN last_name is NULL THEN first_name ELSE CONCAT(first_name,' ',last_name) END) like '%".$this->common_model->escapeString(trim($this->input->post('name')))."%')";
            $this->db->where($where_string);
        }
        $this->db->select('CONCAT(users.first_name," ",users.last_name) as user_name,sum(total_rate) as order_total,count(order_master.entity_id) as total_orders');        
        $this->db->join('users','order_master.user_id = users.entity_id AND users.user_type = "User"');
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id');
        if($this->session->userdata('AdminUserType') == 'Restaurant Admin'){
            $this->db->where('restaurant.restaurant_owner_id',$this->session->userdata('AdminUserID'));
        }
        if($this->session->userdata('AdminUserType') == 'Branch Admin'){
            $this->db->where('restaurant.branch_admin_id',$this->session->userdata('AdminUserID'));
        }
        $this->db->where_not_in('order_master.order_status',$status_arr);
        $this->db->where('order_master.stripe_refund_id',null);                
        $this->db->group_by('users.entity_id'); 
        $result['total'] = $this->db->count_all_results('order_master');

        if($this->input->post('name') != ''){
            $where_string="((CASE WHEN last_name is NULL THEN first_name ELSE CONCAT(first_name,' ',last_name) END) like '%".$this->common_model->escapeString(trim($this->input->post('name')))."%')";
            $this->db->where($where_string);
        }
        $this->db->select('CONCAT(users.first_name," ",users.last_name) as user_name,sum(total_rate) as order_total,count(order_master.entity_id) as total_orders');        
        $this->db->join('users','order_master.user_id = users.entity_id AND users.user_type = "User"');
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id');
        if($this->session->userdata('AdminUserType') == 'Restaurant Admin'){
            $this->db->where('restaurant.restaurant_owner_id',$this->session->userdata('AdminUserID'));
        }
        if($this->session->userdata('AdminUserType') == 'Branch Admin'){
            $this->db->where('restaurant.branch_admin_id',$this->session->userdata('AdminUserID'));
        }
        $this->db->where_not_in('order_master.order_status',$status_arr);
        $this->db->where('order_master.stripe_refund_id',null);                
        $this->db->group_by('users.entity_id'); 
        if($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);
        if($displayLength>1)
            $this->db->limit($displayLength,$displayStart);
        $result['data'] = $this->db->get('order_master')->result();        
        return $result;
    }

    public function get_products_report($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10){

        $status_arr = array('cancel','rejected');
        if($this->input->post('name') != ''){
            $this->db->like('restaurant_menu_item.name', trim($this->input->post('name')));
        }
        $this->db->select('restaurant_menu_item.name as menu_name,count(order_detail_items.order_id) as total_order,sum(order_detail_items.itemTotal) as total_amount');        
        $this->db->join('order_detail_items','order_master.entity_id = order_detail_items.order_id');
        $this->db->join('restaurant_menu_item','restaurant_menu_item.content_id = order_detail_items.menu_content_id');        
        if($this->session->userdata('AdminUserType') == 'Restaurant Admin'){
            $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id');
            $this->db->where('restaurant.restaurant_owner_id',$this->session->userdata('AdminUserID'));
        }
        if($this->session->userdata('AdminUserType') == 'Branch Admin'){
            $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id');
            $this->db->where('restaurant.branch_admin_id',$this->session->userdata('AdminUserID'));
        }
        $this->db->where_not_in('order_master.order_status',$status_arr);
        $this->db->where('order_master.stripe_refund_id',null);                
        $this->db->where('restaurant_menu_item.language_slug',$this->session->userdata('language_slug'));                
        $this->db->group_by('order_detail_items.menu_content_id'); 
        $result['total'] = $this->db->count_all_results('order_master');

        if($this->input->post('name') != ''){
            $this->db->like('restaurant_menu_item.name', trim($this->input->post('name')));
        }
        $this->db->select('restaurant_menu_item.name as menu_name,count(order_detail_items.order_id) as total_order,sum(order_detail_items.itemTotal) as total_amount');        
        $this->db->join('order_detail_items','order_master.entity_id = order_detail_items.order_id');
        $this->db->join('restaurant_menu_item','restaurant_menu_item.content_id = order_detail_items.menu_content_id');        
        if($this->session->userdata('AdminUserType') == 'Restaurant Admin'){
            $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id');
            $this->db->where('restaurant.restaurant_owner_id',$this->session->userdata('AdminUserID'));
        }
        if($this->session->userdata('AdminUserType') == 'Branch Admin'){
            $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id');
            $this->db->where('restaurant.branch_admin_id',$this->session->userdata('AdminUserID'));
        }
        $this->db->where_not_in('order_master.order_status',$status_arr);
        $this->db->where('order_master.stripe_refund_id',null);                
        $this->db->where('restaurant_menu_item.language_slug',$this->session->userdata('language_slug'));                
        $this->db->group_by('order_detail_items.menu_content_id');  
        if($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);
        if($displayLength>1)
            $this->db->limit($displayLength,$displayStart);
        $result['data'] = $this->db->get('order_master')->result();        
        return $result;
    }


    public function get_categories_report($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10){

        $status_arr = array('cancel','rejected');
        if($this->input->post('name') != ''){
            $this->db->like('category.name', trim($this->input->post('name')));
        }
        $this->db->select('category.name as category_name,sum(order_detail_items.quantity) as total_item,sum(order_detail_items.itemTotal) as total_amount');        
        $this->db->join('order_detail_items','order_master.entity_id = order_detail_items.order_id');
        $this->db->join('category','category.content_id = order_detail_items.category_content_id');
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id');
        if($this->session->userdata('AdminUserType') == 'Restaurant Admin'){
            $this->db->where('restaurant.restaurant_owner_id',$this->session->userdata('AdminUserID'));
        }
        if($this->session->userdata('AdminUserType') == 'Branch Admin'){
            $this->db->where('restaurant.branch_admin_id',$this->session->userdata('AdminUserID'));
        }
        $this->db->where_not_in('order_master.order_status',$status_arr);
        $this->db->where('order_master.stripe_refund_id',null);                
        $this->db->where('category.language_slug',$this->session->userdata('language_slug'));                
        $this->db->group_by('order_detail_items.category_content_id'); 
        $result['total'] = $this->db->count_all_results('order_master');

        if($this->input->post('name') != ''){
            $this->db->like('category.name', trim($this->input->post('name')));
        }
        $this->db->select('category.name as category_name,sum(order_detail_items.quantity) as total_item,sum(order_detail_items.itemTotal) as total_amount');        
        $this->db->join('order_detail_items','order_master.entity_id = order_detail_items.order_id');
        $this->db->join('category','category.content_id = order_detail_items.category_content_id');
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id');
        if($this->session->userdata('AdminUserType') == 'Restaurant Admin'){
            $this->db->where('restaurant.restaurant_owner_id',$this->session->userdata('AdminUserID'));
        }
        if($this->session->userdata('AdminUserType') == 'Branch Admin'){
            $this->db->where('restaurant.branch_admin_id',$this->session->userdata('AdminUserID'));
        }
        $this->db->where_not_in('order_master.order_status',$status_arr);
        $this->db->where('order_master.stripe_refund_id',null);                
        $this->db->where('category.language_slug',$this->session->userdata('language_slug'));                
        $this->db->group_by('order_detail_items.category_content_id'); 
        if($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);
        if($displayLength>1)
            $this->db->limit($displayLength,$displayStart);
        $result['data'] = $this->db->get('order_master')->result();        
        return $result;
    }
}
?>