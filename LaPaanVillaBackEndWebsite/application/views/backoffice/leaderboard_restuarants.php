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
                        <?php echo $this->lang->line('restaurant'); ?>
                    </h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="<?php echo base_url().ADMIN_URL?>/dashboard">
                            <?php echo $this->lang->line('home')?> </a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <?php echo $this->lang->line('restaurant'); ?>
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
                                            <?php echo $this->lang->line('restaurant') ?> <?php echo $this->lang->line('list') ?>
                                        </div>
                                        <div class="actions">
                                            <?php if(in_array('leaderboard~restaurants_export',$this->session->userdata("UserAccessArray"))) { ?>
                                                <a class="btn danger-btn btn-sm theme-btn" id="restaurants_report_export"><i class="fa fa-download"></i> <?php echo $this->lang->line('export')?></a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="table-container">
                                            <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                                                <thead>
                                                    <tr role="row" class="heading">                                    
                                                        <th><?php echo $this->lang->line('name') ?></th>
                                                        <th><?php echo $this->lang->line('admin_item_sold') ?></th>
                                                        <th><?php echo $this->lang->line('admin_net_sale') ?></th>
                                                        <th><?php echo $this->lang->line('action') ?></th>
                                                    </tr>
                                                    <tr role="row" class="filter">
                                                        <td><input type="text" class="form-control form-filter input-sm" name="name" id="name"></td>                                
                                                        <td><!-- <input type="text" class="form-control form-filter input-sm" name="total_item" id="total_item"> --></td>                                    
                                                        <td><!-- <input type="text" class="form-control form-filter input-sm" name="total_amount" id="total_amount"> --></td>
                                                        <td>
                                                            <button class="btn btn-sm red filter-submit" title="<?php echo $this->lang->line('search') ?>"><i class="fa fa-search"></i></button>
                                                            <button class="btn btn-sm red filter-cancel" title="<?php echo $this->lang->line('reset') ?>"><i class="fa fa-refresh"></i></button>
                                                        </td>
                                                    </tr>
                                                </thead>                                        
                                                <tbody class="order-tbl-action">
                                                </tbody>
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
    // Datatable 
    grid = new Datatable();
    grid.init({
        src: $("#datatable_ajax"),
        onSuccess: function(grid) {
            // execute some code after table records loaded
        },
        onError: function(grid) {
            // execute some code on network or other general error  
        },
        dataTable: {  // here you can define a typical datatable settings from http://datatables.net/usage/options 
            "sDom" : "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", 
            "aoColumns": [                
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },              
                { "bSortable": false },
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
            "bServerSide": true, // server side processing
            "sAjaxSource": "<?php echo base_url().ADMIN_URL.'/'.$this->controller_name;?>/ajaxview_restaurants", // ajax source
            "aaSorting": [[ 1, "desc" ]] // set first column as a default sort by asc
        }
    });            
    $('#datatable_ajax_filter').addClass('hide');
    $('#datatable_ajax').on('keydown','input.form-filter,select.form-filter',function(e) 
    {
        if (e.keyCode == 13) 
        {
            grid.addAjaxParam($(this).attr("name"), $(this).val());
            grid.getDataTable().fnDraw(); 
        }
    });
});
</script>
<!-- END JAVASCRIPTS -->
<script>
$("#restaurants_report_export").on("click",function(event){
    event.preventDefault();
    jQuery.ajax({
        type : "POST",
        dataType: "json",
        url:"<?php echo base_url().ADMIN_URL.'/'.$this->controller_name; ?>/restaurants_report_export",
        data : {'name':$("#name").val(),"total_item": $("#total_item").val(),"total_amount": $("#total_amount").val()},
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