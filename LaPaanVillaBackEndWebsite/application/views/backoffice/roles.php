<?php $this->load->view(ADMIN_URL.'/header'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/data-tables/DT_bootstrap.css" />
<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/multiselect/sumoselect.min.css"/>
<!-- END PAGE LEVEL STYLES -->
<div class="page-container">
	<!-- BEGIN sidebar -->
	<?php $this->load->view(ADMIN_URL.'/sidebar');?>
	<!-- END sidebar -->
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN PAGE header-->
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN PAGE TITLE & BREADCRUMB-->
					<h3 class="page-title">
						<?php echo $this->lang->line('manage_roles'); ?>
					</h3>
					<ul class="page-breadcrumb breadcrumb">
						<li>
							<i class="fa fa-home"></i>
							<a href="<?php echo base_url().ADMIN_URL; ?>/dashboard">
							<?php echo $this->lang->line('home') ?> </a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<?php echo $this->lang->line('manage_roles') ?>
						</li>
					</ul>
					<!-- END PAGE TITLE & BREADCRUMB-->
				</div>
			</div>
			<!-- END PAGE header-->
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box red">
						<div class="portlet-title">
							<div class="caption">
								<?php echo $this->lang->line('roles_list') ?>
							</div>
							<div class="actions c-dropdown">
								<?php if(in_array('role~add',$this->session->userdata("UserAccessArray"))) { ?>
									<a class="btn default-btn btn-sm danger-btn theme-btn" href="<?php echo base_url().ADMIN_URL.'/'.$this->controller_name.'/add/'.$this->session->userdata('language_slug'); ?>"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add') ?></a>
								<?php } ?>
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-container">
								<?php if(isset($_SESSION['page_MSG'])) { ?>
									<div class="alert alert-success">
										<?php echo $_SESSION['page_MSG'];
										unset($_SESSION['page_MSG']); ?>
									</div>
								<?php } ?>
								<div id="delete-msg" class="alert alert-success hidden">
									<?php echo $this->lang->line('success_delete');?>
								</div>
								<table class="table table-striped table-bordered table-hover" id="datatable_ajax">
									<thead>
										<tr role="row" class="heading">
											<th class="table-checkbox"><?php echo $this->lang->line('s_no') ?></th>
											<th><?php echo $this->lang->line('title') ?></th>
											<th><?php echo $this->lang->line('status') ?></th>
											<th><?php echo $this->lang->line('action') ?></th>
										</tr>
										<tr role="row" class="filter">
											<td></td>
											<td><input type="text" class="form-control form-filter input-sm" name="role_name"></td>
											<td>
												<select name="status" class="form-control form-filter input-sm">
													<option value=""><?php echo $this->lang->line('all')?></option>
													<option value="1"><?php echo $this->lang->line('active')?></option>
													<option value="0"><?php echo $this->lang->line('inactive')?></option>
												</select>
											</td>
											<td style="white-space: nowrap;">
												<button class="btn btn-sm red filter-submit" title="<?php echo $this->lang->line('search')?>"><i class="fa fa-search"></i></button>
												<button class="btn btn-sm red filter-cancel" title="<?php echo $this->lang->line('reset')?>"><i class="fa fa-refresh"></i></button>
											</td>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
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
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/data-tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/data-tables/DT_bootstrap.js"></script>
</script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo base_url();?>assets/admin/scripts/metronic.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/datatable.js"></script>
<script>
	var grid;
	var role_count = <?php echo ($role_count)?$role_count:0; ?>;
	jQuery(document).ready(function() {
		Layout.init(); // init current layout    
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
				// By default the ajax datatable's layout is horizontally scrollable and this can cause an issue of dropdown menu is used in the table rows which.
				//Use below "sDom" value for the datatable layout if you want to have a dropdown menu for each row in the datatable. But this disables the horizontal scroll.
				"sDom" : "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", 
				"aoColumns": [
					{ "bSortable": false },
					null,
					null,
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
				"bStateSave": true,
				"fnStateSave": function (oSettings, oData) {
					if(oSettings.aoData.length == 0 && role_count != 0 && oData.iStart >= role_count) {
						oData.iStart = 0;
						localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
						location.reload();
					} else {
						localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
					}
				},
				"fnStateLoad": function (oSettings) {
					var data = localStorage.getItem('DataTables_' + window.location.pathname);
					return JSON.parse(data);
				},
				"fnStateLoadParams": function (oSettings, oData) {
					oData.aaSorting = [[ 3, "desc" ]];
				},
				"bServerSide": true, // server side processing
				"sAjaxSource": "<?php echo base_url().ADMIN_URL.'/'.$this->controller_name ?>/ajaxview", // ajax source
				"aaSorting": [[ 3, "desc" ]] // set first column as a default sort by asc
			}
		});
		$('#datatable_ajax_filter').addClass('hide');
		$('input.form-filter, select.form-filter').keydown(function(e) {
			if (e.keyCode == 13) {
				grid.addAjaxParam($(this).attr("name"), $(this).val());
				grid.getDataTable().fnDraw(); 
			}
		});
	});
	function disableRecord(role_id,status) {
		var StatusVar = (status==0)?"<?php echo $this->lang->line('active_module'); ?>":"<?php echo $this->lang->line('deactive_module'); ?>";
		bootbox.confirm({
			message: StatusVar,
			buttons: {
				confirm: {
					label: "<?php echo $this->lang->line('ok'); ?>",
				},
				cancel: {
					label: "<?php echo $this->lang->line('cancel'); ?>",
				}
			},
			callback: function (disableConfirm) {
				if (disableConfirm) {
					jQuery.ajax({
						type : "POST",
						dataType : "json",
						url : '<?php echo base_url().ADMIN_URL."/".$this->controller_name ?>/ajaxDisable',
						data : {'role_id':role_id,'status':status},
						success: function(response) {
							grid.getDataTable().fnDraw(); 
						},
						error: function(XMLHttpRequest, textstatus, errorThrown) {           
							alert(errorThrown);
						}
					});
				}
			}
		});
	}
</script>
<?php $this->load->view(ADMIN_URL.'/footer');?>