<?php $this->load->view(ADMIN_URL.'/header');?>
<script src="<?php echo base_url();?>assets/admin/plugins/multiselect/dashboard/jquery.sumoselect.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/multiselect/dashboard/sumoselect.css"/>
<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/css/highchart.css"/>
<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/data-tables/DT_bootstrap.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admin/css/daterangepicker.css" />
<style type="text/css">
.dashboard_statnew{
    padding-top :0px !important;
}
.SumoSelect>.optWrapper>.options li label{
    white-space: break-spaces;
}
</style>
<!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
<div class="page-container">
    <!-- BEGIN sidebar -->
    <?php $this->load->view(ADMIN_URL.'/sidebar');?>
    <!-- END sidebar -->
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <div class="page-content admin-dashboard advance--dashboard">
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                    <h3 class="page-title">
                        <?php echo $this->lang->line('revenue'); ?>
                    </h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="<?php echo base_url().ADMIN_URL?>/dashboard">
                            <?php echo $this->lang->line('home')?> </a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <?php echo $this->lang->line('revenue'); ?>
                        </li>
                    </ul>
                    <!-- END PAGE TITLE & BREADCRUMB-->
                </div>
            </div> 
            <!-- END PAGE header-->            
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN VALIDATION STATES-->
                    <div class="page-content-wrapper">
                        <!-- BEGIN PAGE header-->
                        <div class="row">
                            <div class="col-md-12">    
                                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                <div class="portlet box red">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <?php echo $this->lang->line('revenue') ?> <?php echo $this->lang->line('list') ?>
                                        </div>
                                        <div class="actions">
                                            <?php if(in_array('leaderboard~revenue_export',$this->session->userdata("UserAccessArray"))) { ?>
                                                <a class="btn danger-btn btn-sm theme-btn" id="revenue_report_export"><i class="fa fa-download"></i> <?php echo $this->lang->line('export')?></a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="table-container">
                                            <table class="table table-striped table-bordered table-hover" id="revenue_datatable_ajax">
                                                <thead>
                                                    <tr role="row" class="heading">                                    
                                                        <th><?php echo $this->lang->line('order') ?>#</th>
                                                        <th><?php echo $this->lang->line('restaurant') ?></th>
                                                        <th><?php echo $this->lang->line('customer') ?></th>
                                                        <th><?php echo $this->lang->line('payment_method') ?></th>
                                                        <th><?php echo $this->lang->line('order_type') ?></th>
                                                        <th><?php echo $this->lang->line('sub_total') ?></th>
                                                        <th><?php echo $this->lang->line('service_tax') ?></th>
                                                        <th><?php echo $this->lang->line('service_fee') ?></th>
                                                        <th><?php echo $this->lang->line('title_delivery_charges') ?></th>
                                                        <th><?php echo $this->lang->line('driver_tip') ?></th>                                    
                                                        <th><?php echo $this->lang->line('coupon_used') ?></th>                                    
                                                        <th><?php echo $this->lang->line('total_rate') ?></th>                                    
                                                                                            
                                                        <th><?php echo $this->lang->line('order_status') ?></th>                                    
                                                        <th><?php echo $this->lang->line('action') ?></th>
                                                    </tr>
                                                    <tr role="row" class="filter">
                                                        <td><input type="text" class="form-control form-filter input-sm" name="order_id" id="order_id"></td>                                
                                                        <td><input type="text" class="form-control form-filter input-sm" name="restaurant" id="restaurant"></td>                                    
                                                        <td><input type="text" class="form-control form-filter input-sm" name="customer_name" id="customer_name"></td>
                                                        <td>
                                                            <select name="payment_method" id="payment_method" class="form-control form-filter input-sm">
                                                                <option value=""><?php echo $this->lang->line('select') ?></option>

                                                                <?php $order_status = order_status($this->session->userdata('language_slug'));
                                                                foreach ($payment_method as $key => $value) { 
                                                                    $display_name =  "display_name_".$this->session->userdata('language_slug');
                                                                    ?><option value="<?php echo $value->payment_gateway_slug; ?>"><?php echo $value->$display_name; ?></option>
                                                                <?php  } ?>
                                                            </select>
                                                        </td>
                                                        <td><select name="order_delivery" id="order_delivery" class="form-control form-filter input-sm">
                                                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                                                <option value="PickUp"><?php echo $this->lang->line('pickup') ?></option> 
                                                                <option value="Delivery"><?php echo $this->lang->line('delivery_order') ?></option>
                                                                <option value="DineIn"><?php echo $this->lang->line('dinein') ?></option>
                                                            </select> 
                                                        </td>
                                                        <td><!-- <input type="text" class="form-control form-filter input-sm" name="sub_total" id="sub_total"> --></td>                                    
                                                        <td><!-- <input type="text" class="form-control form-filter input-sm" name="sales_tax" id="sales_tax"> --></td>                                    
                                                        <td><!-- <input type="text" class="form-control form-filter input-sm" name="service_fee" id="service_fee"> --></td>                                    
                                                        <td><!-- <input type="text" class="form-control form-filter input-sm" name="delivery_charges" id="delivery_charges"> --></td>
                                                        <td><!-- <input type="text" class="form-control form-filter input-sm" name="driver_tips" id="driver_tips"> --></td>
                                                        <td><!-- <input type="text" class="form-control form-filter input-sm" name="coupon_discount" id="coupon_discount"> --></td>
                                                        <td><!-- <input type="text" class="form-control form-filter input-sm" name="total_rate" id="total_rate"> --></td>                                    
                                                        <td>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm red filter-submit" title="<?php echo $this->lang->line('search') ?>"><i class="fa fa-search"></i></button>
                                                            <button class="btn btn-sm red filter-cancel" title="<?php echo $this->lang->line('reset') ?>"><i class="fa fa-refresh"></i></button>
                                                        </td>
                                                    </tr>
                                                </thead>                                        
                                                <tbody class="order-tbl-action">
                                                </tbody>
                                                <tfoot id="revenue_footer">
                                                    <tr>
                                                      <td><b>Total</td>
                                                      <td></td>
                                                      <td></td>
                                                      <td></td>
                                                      <td></td>
                                                      <td><b><span id="revenue_subtotal"><?php echo $currency_symbol->currency_symbol;?>0.00</span></b></td>
                                                      <td><b><span id="revenue_tax_rate"><?php echo $currency_symbol->currency_symbol;?>0.00</span></b></td>
                                                      <td><b><span id="revenue_service_fee"><?php echo $currency_symbol->currency_symbol;?>0.00</span></b></td>
                                                      <td><b><span id="revenue_delivery_charge"><?php echo $currency_symbol->currency_symbol;?>0.00</span></b></td>
                                                      <td><b><span id="revenue_tip_amount"><?php echo $currency_symbol->currency_symbol;?>0.00</span></b></td>
                                                      <td><b><span id="revenue_coupon_discount"><?php echo $currency_symbol->currency_symbol;?>0.00</span></b></td>
                                                      <td><b><span id="revenue_total_rate"><?php echo $currency_symbol->currency_symbol;?>0.00</span></b></td>                                  
                                                      <td></td>
                                                      <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
            <!-- END PAGE CONTENT-->
        </div>
    </div>
    <!-- END CONTENT -->
</div>
<!-- END CONTENT -->
<div id="view_order_detail" class="modal fade" role="dialog">
</div>
<div class="wait-loader display-no" id="quotes-main-loader"><img  src="<?php echo base_url() ?>assets/admin/img/ajax-loader.gif" align="absmiddle"  ></div>
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> -->
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/scripts/daterangepicker/daterangepicker.min.js"></script>
<!-- graph js end -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/data-tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/data-tables/DT_bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/metronic.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/datatable.js"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/index.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/pages/scripts/admin-management.js"></script>
<?php if($this->session->userdata("language_slug")=='ar'){  ?>
<script type="text/javascript" src="<?php echo base_url()?>assets/admin/pages/scripts/localization/messages_ar.js"> </script>
<?php } ?>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
    Metronic.init();
    Layout.init(); // init layout 
    // Datatable for Revenue
    grid_revenue = new Datatable();
    grid_revenue.init({
        src: $("#revenue_datatable_ajax"),
        onSuccess: function(grid_revenue) {
            // execute some code after table records loaded
        },
        onError: function(grid_revenue) {
            // execute some code on network or other general error  
        },
        dataTable: {  // here you can define a typical datatable settings from http://datatables.net/usage/options 
            "sDom" : "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", 
            "aoColumns": [                
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },              
                { "bSortable": false },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": false }
              ],
            "sPaginationType": "bootstrap_full_number",
            "oLanguage":{
                "sProcessing": sProcessing,
                "sLengthMenu": sLengthMenu,
                "sInfo": sInfo,
                "sInfoEmpty":'', //sInfoEmpty,
                "sGroupActions":sGroupActions,
                "sAjaxRequestGeneralError": sAjaxRequestGeneralError,
                "sEmptyTable": sEmptyTable,
                "sZeroRecords":sZeroRecords,
                "oPaginate": {
                    "sPrevious": sPrevious,
                    "sNext": sNext,
                    "sPage": sPage,
                    "sPageOf":sPageOf,
                    "sFirst": sFirst,
                    "sLast": sLast
                }
            },
            "fnDrawCallback": function( settings ) 
            {
                jQuery.ajax({
                    type : "POST",
                    dataType: "json",
                    url:"<?php echo base_url().ADMIN_URL.'/'.$this->controller_name; ?>/get_revenue_totals",
                    data : {
                        order_id : $('#order_id').val(),
                        restaurant : $('#restaurant').val(),
                        customer_name : $('#customer_name').val(),
                        sub_total : $('#sub_total').val(),
                        sales_tax : $('#sales_tax').val(),
                        service_fee : $('#service_fee').val(),
                        delivery_charges : $('#delivery_charges').val(),
                        driver_tips : $('#driver_tips').val(),
                        coupon_discount : $('#coupon_discount').val(),
                        total_rate : $('#total_rate').val(),
                        payment_method : $('#payment_method').val(),
                        order_delivery : $('#order_delivery').val(),
                    },
                    success: function(response) 
                    {
                        $('#revenue_subtotal').html(response.revenue_subtotal);
                        $('#revenue_tax_rate').html(response.revenue_tax_rate);
                        $('#revenue_delivery_charge').html(response.revenue_delivery_charge);
                        $('#revenue_coupon_discount').html(response.revenue_coupon_discount);
                        $('#revenue_total_rate').html(response.revenue_total_rate);
                        $('#revenue_tip_amount').html(response.revenue_tip_amount);
                        $('#revenue_service_fee').html(response.revenue_service_fee);
                    },
                    error: function(XMLHttpRequest, textstatus, errorThrown) 
                    {           
                        alert(errorThrown);
                    }
                });        
            },
            "bServerSide": true, // server side processing
            "sAjaxSource": "<?php echo base_url().ADMIN_URL.'/'.$this->controller_name;?>/ajaxview_revenue", // ajax source
            "aaSorting": [[ 0, "desc" ]] // set first column as a default sort by asc
        }
    });            
    $('#revenue_datatable_ajax_filter').addClass('hide');
    $('#revenue_datatable_ajax').on('keydown','input.form-filter,select.form-filter',function(e) 
    {
        if (e.keyCode == 13) 
        {
            grid_revenue.addAjaxParam($(this).attr("name"), $(this).val());
            grid_revenue.getDataTable().fnDraw(); 
        }
    });
});
</script>
<!-- END JAVASCRIPTS -->
<script>
// openOrderDetails
function openOrderDetails(entity_id){
    jQuery.ajax({
      type : "POST",                      
      url : BASEURL+"backoffice/order/orderDetail",
      data : {'entity_id':entity_id},
      cache: false,
      success: function(response) {
        $('#view_order_detail').html(response);
        $('#view_order_detail').modal('show');      
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        alert(errorThrown);
      }
    });
}
$("#revenue_report_export").on("click",function(event){
    event.preventDefault();
    jQuery.ajax({
        type : "POST",
        dataType: "json",
        url:"<?php echo base_url().ADMIN_URL.'/'.$this->controller_name; ?>/revenue_report_export",
        data : {'order_id':$("#order_id").val(),"restaurant": $("#restaurant").val(),"customer_name": $("#customer_name").val(),"sub_total": $("#sub_total").val(),"sales_tax": $("#sales_tax").val(),"service_fee": $("#service_fee").val(),"delivery_charges": $("#delivery_charges").val(),"driver_tips": $("#driver_tips").val(),"coupon_discount": $("#coupon_discount").val(),"total_rate": $("#total_rate").val(),"payment_method": $("#payment_method").val(),"order_delivery": $("#order_delivery").val()},
        success: function(response) 
        {
            url = "<?php echo base_url();?>"+response.filename;
            window.location.href = url;       
        },
        error: function(XMLHttpRequest, textstatus, errorThrown) 
        {           
            alert(errorThrown);
        }
    });
    return false;
});
</script>
<?php $this->load->view(ADMIN_URL.'/footer');?>