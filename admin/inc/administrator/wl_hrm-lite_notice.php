<?php
defined( 'ABSPATH' ) or die();
require_once( WL_HRML_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-hrm-lite-helper.php' );
$all_notices = get_option( 'ehrm_notices_data' );
?>
<!-- partial -->
<div class="main-panel">
  	<div class="content-wrapper">
	    <div class="page-header">
	      	<h3 class="page-title">
	        	<span class="page-title-icon bg-gradient-primary text-white mr-2">
	          	<i class="fab fa-evernote"></i>                 
	        	</span>
	        	<?php esc_html_e( 'Notices', 'hr-management-lite' ); ?>
	      	</h3>
	      	<nav aria-label="breadcrumb notice">
	        	<ul class="breadcrumb">
	            <li class="breadcrumb-item active" aria-current="page">
	            	<button class="btn btn-block btn-lg btn-gradient-primary custom-btn" data-bs-toggle="modal" data-bs-target="#AddNotices">
	            		<i class="fas fa-plus"></i> <?php esc_html_e( 'Add Notice', 'hr-management-lite' ); ?>
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
		                  	<h4 class="card-title"><?php esc_html_e( 'Notice', 'hr-management-lite' ); ?></h4>
		                  	<table class="table table-striped events_table">
		                    	<thead>
			                      	<tr>
				                        <th><?php esc_html_e( 'No.', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Title', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Date', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Description', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Status', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Action', 'hr-management-lite' ); ?></th>
			                     	</tr>
		                    	</thead>
		                    	<tbody id="notice_tbody">
				                    <?php 
				                    	if ( ! empty ( $all_notices ) ) {

											$sno         = 1;        
											$first       = new \DateTime( date( "Y" )."-01-01" );
											$first       = $first->format( "Y-m-d" );
											$plusOneYear = date( "Y" )+1;
											$last        = new \DateTime( $plusOneYear."-12-31" );          
											$last        = $last->format( "Y-m-d" );          
											$all_dates   = HRMLiteHelperClass::ehrm_get_date_range( $first, $last );
	
											foreach ( $all_notices as $key => $notice ) {
                                                if ( in_array( $notice['date'], $all_dates ) ) {
                                                    ?>
			                        <tr>
			                        	<td><?php echo esc_html( $sno ); ?>.</td>
										<td><?php echo esc_html( $notice['name'] ); ?></td>
										<td><?php echo esc_html( date( HRMLiteHelperClass::get_date_format(), strtotime( $notice['date'] ) ) ); ?></td>
			                          	<td><?php echo esc_html( stripslashes( $notice['desc'] ) ); ?></td>
			                          	<td><?php echo esc_html( $notice['status'] ); ?></td>
			                          	<td class="designation-action-tools">
			                          		<ul class="designation-action-tools-ul">
			                          			<li class="designation-action-tools-li">
			                          				<a href="#" class="designation-action-tools-a notice-edit-a" data-notice="<?php echo esc_attr( $key ); ?>">
			                          					<i class="fas fa-pencil-alt"></i>
			                          				</a>
			                          			</li>
			                          			<li class="designation-action-tools-li">
			                          				<a href="#" class="designation-action-tools-a notice-delete-a" data-notice="<?php echo esc_attr( $key ); ?>">
			                          					<i class="far fa-window-close"></i>
			                          				</a>
			                          			</li>
			                          		</ul>
			                          	</td>
			                        </tr>
				                    <?php $sno++;
                                                } } } else { ?>
				                    <tr>
				                    	<td><?php esc_html_e( 'No notices added yet.!', 'hr-management-lite' ); ?></td>
				                    </tr>
				                    <?php } ?>
		                    	</tbody>
		                    	<tfoot>
			                      	<tr>
				                        <th><?php esc_html_e( 'No.', 'hr-management-lite' ); ?></th>
										<th><?php esc_html_e( 'Title', 'hr-management-lite' ); ?></th>
										<th><?php esc_html_e( 'Date', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Description', 'hr-management-lite' ); ?></th>
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

        <!-- Add Notice Modal -->
		<div class="modal fade" id="AddNotices" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-info">
		    <div class="modal-content">

		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Notice Details', 'hr-management-lite' ); ?></h4>
	                  <form class="forms-sample" method="post" id="add_notice_form">
	                  	<div class="form-group">
	                      <label for="notice_name"><?php esc_html_e( 'Notice Title', 'hr-management-lite' ); ?></label>
	                      <input type="text" class="form-control" id="notice_name" placeholder="<?php esc_html_e( 'Name', 'hr-management-lite' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="notice_desc"><?php esc_html_e( 'Notice Description', 'hr-management-lite' ); ?></label>
	                      <textarea class="form-control" rows="4" id="notice_desc" name="notice_desc" placeholder="<?php esc_html_e( 'Description....', 'hr-management-lite' ); ?>"></textarea>
	                    </div>
	                    <div class="form-group">
	                      	<label for="notice_status"><?php esc_html_e( 'Status', 'hr-management-lite' ); ?></label>
	                      	<select name="notice_status" id="notice_status" class="form-control">
	                      		<option value="<?php echo esc_attr('Active'); ?>"><?php esc_html_e( 'Active', 'hr-management-lite' ); ?></option>
	                      		<option value="<?php echo esc_attr('Inactive'); ?>"><?php esc_html_e( 'Inactive', 'hr-management-lite' ); ?></option>
	                      	</select>
	                    </div>
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="add_notice_btn" value="<?php esc_html_e( 'Submit', 'hr-management-lite' ); ?>">
	                  </form>
	                </div>
	            </div>

		    </div>
		  </div>
		</div>

		<!-- Edit Notice Modal -->
		<div class="modal fade" id="EditNotice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Notice Details', 'hr-management-lite' ); ?></h4>
	                  <form class="forms-sample" method="post" id="edit_notice_form">
	                  	<div class="form-group">
	                      <label for="edit_notice_name"><?php esc_html_e( 'Notice Title', 'hr-management-lite' ); ?></label>
	                      <input type="text" class="form-control" id="edit_notice_name" placeholder="<?php esc_html_e( 'Name', 'hr-management-lite' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="edit_notice_desc"><?php esc_html_e( 'Notice Description', 'hr-management-lite' ); ?></label>
	                      <textarea class="form-control" rows="4" id="edit_notice_desc" name="edit_notice_desc" placeholder="<?php esc_html_e( 'Description....', 'hr-management-lite' ); ?>"></textarea>
	                    </div>
	                    <div class="form-group">
	                      	<label for="edit_notice_status"><?php esc_html_e( 'Status', 'hr-management-lite' ); ?></label>
	                      	<select name="edit_notice_status" id="edit_notice_status" class="form-control">
	                      		<option value="<?php echo esc_attr('Active'); ?>"><?php esc_html_e( 'Active', 'hr-management-lite' ); ?></option>
	                      		<option value="<?php echo esc_attr('Inactive'); ?>"><?php esc_html_e( 'Inactive', 'hr-management-lite' ); ?></option>
	                      	</select>
	                    </div>
	                    <input type="hidden" name="notice_key" id="notice_key">
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="edit_notice_btn" value="<?php esc_html_e( 'Submit', 'hr-management-lite' ); ?>">
	                  </form>
	                </div>
	            </div>

		    </div>
		  </div>
		</div>
	</div>
</div>