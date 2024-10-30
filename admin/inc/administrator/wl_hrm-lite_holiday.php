<?php
defined( 'ABSPATH' ) or die();
require_once( WL_HRML_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-hrm-lite-helper.php' );
$all_holidays = get_option( 'ehrm_holidays_data' );
?>
<!-- partial -->
<div class="main-panel">
  	<div class="content-wrapper">
	    <div class="page-header">
	      	<h3 class="page-title">
	        	<span class="page-title-icon bg-gradient-primary text-white mr-2">
	          	<i class="fas fa-golf-ball"></i>                 
	        	</span>
	        	<?php esc_html_e( 'Holidays', 'hr-management-lite' ); ?>
	      	</h3>
	      	<nav aria-label="breadcrumb holiday">
	        	<ul class="breadcrumb">
	            <li class="breadcrumb-item active" aria-current="page">
	            	<button class="btn btn-block btn-lg btn-gradient-primary custom-btn" data-bs-toggle="modal" data-bs-target="#AddHolidays">
	            		<i class="fas fa-plus"></i> <?php esc_html_e( 'Add Holiday', 'hr-management-lite' ); ?>
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
		                  	<h4 class="card-title"><?php esc_html_e( 'Holiday', 'hr-management-lite' ); ?></h4>
		                  	<table class="table table-striped events_table">
		                    	<thead>
			                      	<tr>
				                        <th><?php esc_html_e( 'No.', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Name', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Date(s)', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Days', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Status', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Action', 'hr-management-lite' ); ?></th>
			                     	</tr>
		                    	</thead>
		                    	<tbody id="holiday_tbody">
								<?php 
				                    	if ( ! empty ( $all_holidays ) ) {

                                        $sno         = 1;        
                                        $first       = new \DateTime( date( "Y" )."-01-01" );
                                        $first       = $first->format( "Y-m-d" );
                                        $plusOneYear = date( "Y" )+1;
                                        $last        = new \DateTime( $plusOneYear."-12-31" );          
                                        $last        = $last->format( "Y-m-d" );          
                                        $all_dates   = HRMLiteHelperClass::ehrm_get_date_range( $first, $last );

		                        		foreach ( $all_holidays as $key => $holiday ) {
                                            if ( in_array( $holiday['to'], $all_dates ) ) {
                                ?>
			                        <tr>
										<td><?php echo esc_html( $sno ); ?>.</td>
			                          	<td><?php echo esc_html( $holiday['name'] ); ?></td>
			                          	<td><?php echo esc_html( "From ".date( HRMLiteHelperClass::get_date_format(), strtotime( $holiday['start'] ) )." to ".date( HRMLiteHelperClass::get_date_format(), strtotime( $holiday['to'] ) ) ); ?></td>
			                          	<td><?php echo esc_html( $holiday['days'] ); ?></td>
			                          	<td><?php echo esc_html( $holiday['status'] ); ?></td>
			                          	<td class="designation-action-tools">
			                          		<ul class="designation-action-tools-ul">
			                          			<li class="designation-action-tools-li">
			                          				<a href="#" class="designation-action-tools-a holiday-edit-a" data-holiday="<?php echo esc_attr($key); ?>">
			                          					<i class="fas fa-pencil-alt"></i>
			                          				</a>
			                          			</li>
			                          			<li class="designation-action-tools-li">
			                          				<a href="#" class="designation-action-tools-a holiday-delete-a" data-holiday="<?php echo esc_attr($key); ?>">
			                          					<i class="far fa-window-close"></i>
			                          				</a>
			                          			</li>
			                          		</ul>
			                          	</td>
			                        </tr>
				                    <?php $sno++;
                                            } } } else { ?>
				                    <tr>
				                    	<td><?php esc_html_e( 'No Holidays added yet.!', 'hr-management-lite' ); ?></td>
				                    </tr>
				                    <?php } ?>
		                    	</tbody>
		                    	<tfoot>
			                      	<tr>
				                        <th><?php esc_html_e( 'No.', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Name', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Date(s)', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Days', 'hr-management-lite' ); ?></th>
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

        <!-- Add Holiday Modal -->
		<div class="modal fade" id="AddHolidays" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-info">
		    <div class="modal-content">

		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Holiday Details88', 'hr-management-lite' ); ?></h4>
	                  <form class="forms-sample" method="post" id="add_holiday_form">
	                  	<div class="form-group">
	                      <label for="holiday_name"><?php esc_html_e( 'Holiday Name', 'hr-management-lite' ); ?></label>
	                      <input type="text" class="form-control" id="holiday_name" placeholder="<?php esc_html_e( 'Name', 'hr-management-lite' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="holiday_start"><?php esc_html_e( 'Holiday From', 'hr-management-lite' ); ?></label>
	                      <input type="text" class="form-control" name="holiday_start" id="holiday_start" placeholder="<?php echo esc_html('Format:- YYYY-MM-DD'); ?>" data-toggle="datetimepicker" data-target="#holiday_start">
	                    </div>
	                    <div class="form-group">
	                      <label for="holiday_to"><?php esc_html_e( 'Holiday To', 'hr-management-lite' ); ?></label>
	                      <input type="text" class="form-control" id="holiday_to" placeholder="<?php echo esc_html('Format:- YYYY-MM-DD'); ?>" data-toggle="datetimepicker" data-target="#holiday_to" >
	                    </div>
	                    <div class="form-group">
	                      	<label for="holiday_status"><?php esc_html_e( 'Status', 'hr-management-lite' ); ?></label>
	                      	<select name="holiday_status" id="holiday_status" class="form-control">
	                      		<option value="<?php echo esc_attr('Active'); ?>"><?php esc_html_e( 'Active', 'hr-management-lite' ); ?></option>
	                      		<option value="<?php echo esc_attr('Inactive'); ?>"><?php esc_html_e( 'Inactive', 'hr-management-lite' ); ?></option>
	                      	</select>
	                    </div>
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="add_holiday_btn" value="<?php echo esc_html_e( 'Submit', 'hr-management-lite' ); ?>">
	                  </form>
	                </div>
	            </div>

		    </div>
		  </div>
		</div>

		<!-- Edit Holiday Modal -->
		<div class="modal fade" id="EditHoliday" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Holiday Details', 'hr-management-lite' ); ?></h4>
	                  <form class="forms-sample" method="post" id="edit_holiday_form">
	                  	<div class="form-group">
	                      <label for="edit_holiday_name"><?php esc_html_e( 'Holiday Name', 'hr-management-lite' ); ?></label>
	                      <input type="text" class="form-control" id="edit_holiday_name" placeholder="<?php esc_html_e( 'Name', 'hr-management-lite' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="edit_holiday_start"><?php esc_html_e( 'Holiday From', 'hr-management-lite' ); ?></label>
	                      <input type="text" class="form-control" name="edit_holiday_start" id="edit_holiday_start" placeholder="<?php echo esc_html('Format:- YYYY-MM-DD'); ?>" data-toggle="datetimepicker" data-target="#edit_holiday_start" />
	                    </div>
	                    <div class="form-group">
	                      <label for="edit_holiday_to"><?php esc_html_e( 'Holiday To', 'hr-management-lite' ); ?></label>
	                      <input type="text" class="form-control" name="edit_holiday_to" id="edit_holiday_to" placeholder="<?php echo esc_html('Format:- YYYY-MM-DD'); ?>" data-toggle="datetimepicker" data-target="#edit_holiday_to" />
	                    </div>
	                    <div class="form-group">
	                      	<label for="edit_holiday_status"><?php esc_html_e( 'Status', 'hr-management-lite' ); ?></label>
	                      	<select name="edit_holiday_status" id="edit_holiday_status" class="form-control">
	                      		<option value="<?php echo esc_attr('Active'); ?>"><?php esc_html_e( 'Active', 'hr-management-lite' ); ?></option>
	                      		<option value="<?php echo esc_attr('Inactive'); ?>"><?php esc_html_e( 'Inactive', 'hr-management-lite' ); ?></option>
	                      	</select>
	                    </div>
	                    <input type="hidden" name="holiday_key" id="holiday_key">
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="edit_holiday_btn" value="<?php esc_html_e( 'Submit', 'hr-management-lite' ); ?>">
	                  </form>
	                </div>
	            </div>

		    </div>
		  </div>
		</div>
	</div>
</div>
