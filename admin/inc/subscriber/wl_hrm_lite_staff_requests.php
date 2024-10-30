<?php
defined( 'ABSPATH' ) or die();
require_once( WL_HRML_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-hrm-lite-helper.php' );
$all_requests     = get_option( 'ehrm_requests_data' );
$the_current_user = get_current_user_id();
?>
<!-- partial -->
<div class="main-panel">
  	<div class="content-wrapper">
	    <div class="page-header">
	      	<h3 class="page-title">
	        	<span class="page-title-icon bg-gradient-primary text-white mr-2">
	          	<i class="fas fa-notes-medical"></i>                 
	        	</span>
	        	<?php esc_html_e( 'Leave Requests', 'hr-management-lite' ); ?>
            </h3>
            <nav aria-label="breadcrumb requests">
                <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <button class="btn btn-block btn-lg btn-gradient-primary custom-btn" data-bs-toggle="modal" data-bs-target="#AddRequests">
                        <i class="fas fa-plus"></i> <?php esc_html_e( 'Add Leave Requests', 'hr-management-lite' ); ?>
                    </button>
                </li>
                </ul>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              	<div class="card table_card">
                	<div class="card-body">
                		<div class="table-responsive">
		                  	<h4 class="card-title"><?php esc_html_e( 'Requests', 'hr-management-lite' ); ?></h4>
		                  	<table class="table table-striped shifts_table">
		                    	<thead>
			                      	<tr>
				                        <th><?php esc_html_e( 'No.', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Title', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Staff Name', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Short Description', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Date', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Request For', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Leaves', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Status', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Action', 'hr-management-lite' ); ?></th>
			                     	</tr>
		                    	</thead>
		                    	<tbody id="request_tbody">
				                    <?php 
				                    	if ( ! empty ( $all_requests ) ) {
		                        		$sno = 1;
		                        		foreach ( $all_requests as $key => $request ) {
											if($request['s_id'] == $the_current_user ) {
												?>
												<tr>
													<td><?php if( ! empty ( $sno ) ) { echo esc_html( $sno ); } ?>.</td>
													<td><?php if( ! empty ( $request['name'] ) ) { echo esc_html( $request['name'] ); } ?></td>
													<td><?php if( ! empty ( $request['s_name'] ) ) { echo esc_html( $request['s_name']  ); } ?></td>
													<td><?php if( ! empty ( $request['desc'] ) ) { echo esc_html( $request['desc'] ); } ?></td>
													<td><?php if( ! empty ( $request['date'] ) ) { echo esc_html( date( HRMLiteHelperClass::get_date_format(), strtotime( $request['date'] ) ) ); } ?></td>
													<td><?php if( ! empty ( $request['date'] ) ) { echo esc_html( "From ".date( HRMLiteHelperClass::get_date_format(), strtotime( $request['start'] ) )." to ".date( HRMLiteHelperClass::get_date_format(), strtotime( $request['to'] ) ) ); } ?></td>
													<td>
														<?php 
														if( ! empty ( $request['days'] ) ) {
															echo esc_html( $request['days'] );
														} 
														?>
													</td>
													<td class="status-<?php echo esc_attr( $request['status'] ); ?>">
														<span><?php echo esc_html( $request['status'] ); ?></span>
													</td>
													<td class="designation-action-tools">
														<ul class="designation-action-tools-ul">
															<li class="designation-action-tools-li">
																<a href="#" class="designation-action-tools-a request-edit-a" data-request="<?php echo esc_attr( $key ); ?>">
																	<i class="fas fa-pencil-alt"></i>
																</a>
															</li>
															<li class="designation-action-tools-li">
																<a href="#" class="designation-action-tools-a request-delete-a" data-request="<?php echo esc_attr( $key ); ?>">
																	<i class="far fa-window-close"></i>
																</a>
															</li>
														</ul>
													</td>
												</tr>
												<?php
											}
		                        	 $sno++; } } else { ?>
				                    <tr>
				                    	<td><?php esc_html_e( 'No Requests added yet.!', 'hr-management-lite' ); ?></td>
				                    </tr>
				                    <?php } ?>
		                    	</tbody>
		                    	<tfoot>
			                      	<tr>
                                        <th><?php esc_html_e( 'No.', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Title', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Staff Name', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Short Description', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Date', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Request For', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Leaves', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Status', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Action', 'hr-management-lite' ); ?></th>
			                     	</tr>
		                    	</tfoot>
		                  	</table>
	                  	</div>
                	</div>
              	</div>
            </div>
        </div>

        <!-- Add Request Modal -->
		<div class="modal fade" id="AddRequests" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Request Details', 'hr-management-lite' ); ?></h4>
	                  <form class="forms-sample" method="post" id="add_request_form">
	                  	<div class="form-group">
	                      <label for="request_name"><?php esc_html_e( 'Request Title', 'hr-management-lite' ); ?></label>
	                      <input type="text" class="form-control" id="request_name" placeholder="<?php esc_html_e( 'Name', 'hr-management-lite' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="notice_desc"><?php esc_html_e( 'Request Description', 'hr-management-lite' ); ?></label>
	                      <textarea class="form-control" rows="4" id="notice_desc" name="notice_desc" placeholder="<?php esc_html_e( 'Description....', 'hr-management-lite' ); ?>"></textarea>
                        </div>
                        <div class="form-group">
	                      <label for="holiday_start"><?php esc_html_e( 'Holiday From', 'hr-management-lite' ); ?></label>
	                      <input type="text" class="form-control" name="holiday_start" id="holiday_start" placeholder="<?php esc_html_e( 'Format:- YYYY-MM-DD', 'hr-management-lite' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="holiday_to"><?php esc_html_e( 'Holiday To', 'hr-management-lite' ); ?></label>
	                      <input type="text" class="form-control" id="holiday_to" placeholder="<?php esc_html_e( 'Format:- YYYY-MM-DD', 'hr-management-lite' ); ?>">
	                    </div>
                        <input type="hidden" name="staff_id" id="staff_id" value="<?php echo esc_html( get_current_user_id() ); ?>" >
                        <input type="hidden" name="staff_name" id="staff_name" value="<?php echo esc_html( HRMLiteHelperClass::get_current_user_data( get_current_user_id(), 'fullname' ) ); ?>" >
                        <input type="hidden" name="request_status" id="request_status" value="<?php echo esc_attr('Pending'); ?>" >
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="add_request_btn" value="<?php esc_html_e( 'Submit', 'hr-management-lite' ); ?>">
	                  </form>
	                </div>
	            </div>
		    </div>
		  </div>
        </div>

        <!-- Edit Request Modal -->
		<div class="modal fade" id="EditRequests" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Request Details', 'hr-management-lite' ); ?></h4>
	                  <form class="forms-sample" method="post" id="edit_request_form">
	                  	<div class="form-group">
	                      <label for="edit_request_name"><?php esc_html_e( 'Request Title', 'hr-management-lite' ); ?></label>
	                      <input type="text" class="form-control" id="edit_request_name" placeholder="<?php esc_html_e( 'Name', 'hr-management-lite' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="edit_notice_desc"><?php esc_html_e( 'Request Description', 'hr-management-lite' ); ?></label>
	                      <textarea class="form-control" rows="4" id="edit_notice_desc" name="edit_notice_desc" placeholder="<?php esc_html_e( 'Description....', 'hr-management-lite' ); ?>"></textarea>
                        </div>
                        <div class="form-group">
	                      <label for="edit_holiday_start"><?php esc_html_e( 'Holiday From', 'hr-management-lite' ); ?></label>
	                      <input type="text" class="form-control" name="edit_holiday_start" id="edit_holiday_start" placeholder="<?php esc_html_e( 'Format:- YYYY-MM-DD', 'hr-management-lite' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="edit_holiday_to"><?php esc_html_e( 'Holiday To', 'hr-management-lite' ); ?></label>
	                      <input type="text" class="form-control" id="edit_holiday_to" placeholder="<?php esc_html_e( 'Format:- YYYY-MM-DD', 'hr-management-lite' ); ?>">
                        </div>
                        <input type="hidden" name="request_key" id="request_key" value="">
                        <input type="hidden" name="edit_staff_id" id="edit_staff_id" value="" >
                        <input type="hidden" name="edit_staff_name" id="edit_staff_name" value="" >
                        <input type="hidden" name="edit_request_status" id="edit_request_status" value="" >
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="edit_request_btn" value="<?php esc_html_e( 'Submit', 'hr-management-lite' ); ?>">
	                  </form>
	                </div>
	            </div>
		    </div>
		  </div>
        </div>
        
    </div>
</div>
<script>
	jQuery('#datefilter').datetimepicker({
        format: 'LT',
        format: 'YYYY-MM-DD',
        autoclose: true
    });
</script>