<?php
defined( 'ABSPATH' ) or die();
require_once( WL_HRML_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-hrm-lite-helper.php' );
$email_settings = get_option( 'ehrm_email_options_data' );
$from_name      = isset( $email_settings['name'] ) ? sanitize_text_field( $email_settings['name'] ) : get_bloginfo();
$from_address   = isset( $email_settings['name'] ) ? sanitize_text_field( $email_settings['name'] ) : get_bloginfo('admin_email');
$logo_image     = isset( $email_settings['logo'] ) ? sanitize_text_field( $email_settings['logo'] ) : '';
$footer_txt     = isset( $email_settings['footer'] ) ? sanitize_text_field( $email_settings['footer'] ) : get_bloginfo().'  - Powered by HR Management Lite';

// HRMLiteHelperClass::send_project_detail_mails( 0 );
?>
<!-- partial -->
<div class="main-panel">
  	<div class="content-wrapper">
	    <div class="page-header">
	      	<h3 class="page-title">
	        	<span class="page-title-icon bg-gradient-primary text-white mr-2">
	          	<i class="fas fa-envelope-open-text"></i>                 
	        	</span>
	        	<?php esc_html_e( 'Email Sender Options', 'hr-management-lite' ); ?>
              </h3>
        </div>

        <div class="row calander_table_div3">
            <div class="col-12 stretch-card grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title noti_sub_title"><?php esc_html_e( 'Email notification settings for HR Management Lite. Customize the look and feel of outgoing emails.', 'hr-management-lite' ); ?></h4>
                        <div class="row">
                            <form action="" method="get" accept-charset="utf-8" id="pro_select_from">

                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label"><?php esc_html_e( '"From" Name ', 'hr-management-lite' ); ?></label>
                                        <div class="col-sm-9">
                                            <input class="form-control" id="from_name" name="from_name" value="<?php echo esc_attr( $from_name ); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label"><?php esc_html_e( '"From" Address ', 'hr-management-lite' ); ?></label>
                                        <div class="col-sm-9">
                                            <input class="form-control" id="from_address" name="from_address" value="<?php echo esc_attr( $from_address ); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Logo Image', 'hr-management-lite' ); ?></label>
                                        <div class="col-sm-7">
                                            <input class="form-control" id="logo_image_mail" name="logo_image_mail" value="<?php echo esc_attr( $logo_image ) ?>">
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="button" name="upload-btn" id="upload-btn-ehrm" class="btn btn-block btn-lg btn-gradient-primary custom-btn" value="<?php esc_html_e( 'Upload File', 'hr-management-lite' ); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Footer Text', 'hr-management-lite' ); ?></label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" rows="4" id="footer_txt" name="footer_txt" placeholder="<?php echo esc_attr( get_bloginfo().'  - Powered by HR Management Lite'); ?>"><?php echo esc_textarea( $footer_txt ); ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row">
                                        <button type="button" class="btn btn-lg btn-gradient-success mr-2" id="save-cleaner-pro">
                                            <?php esc_html_e( 'Submit', 'hr-management-lite' ); ?>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row calander_table_div4">
            <div class="col-12 stretch-card grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title noti_sub_title"><?php esc_html_e( 'Notification Emails', 'hr-management-lite' ); ?></h4>
                        <div class="table-responsive">
		                  	<table class="table table-striped notification_emails_table">
		                    	<thead>
			                      	<tr>
				                        <th><?php esc_html_e( 'No.', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Email', 'hr-management-lite' ); ?></th>
			                        	<th><?php esc_html_e( 'Description', 'hr-management-lite' ); ?></th>
			                     	</tr>
                                </thead>
                                <tbody id="notification_emails_tbody">
                                    <tr>
                                        <td><?php esc_html_e( '1.', 'hr-management-lite' ); ?></td>
                                        <td>
                                            <a href="#" class="email_template_settings" data-name="<?php echo esc_attr_e( 'Employee welcome', 'hr-management-lite' ); ?>" data-value="employee_welcome"><?php esc_html_e( 'Employee welcome', 'hr-management-lite' ); ?></a>
                                        </td>
                                        <td><?php esc_html_e( 'Welcome email to new employees.', 'hr-management-lite' ); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php esc_html_e( '2.', 'hr-management-lite' ); ?></td>
                                        <td>
                                            <a href="#" class="email_template_settings" data-name="<?php echo esc_attr_e( 'New Leave Request', 'hr-management-lite' ); ?>" data-value="new_leave_request"><?php esc_html_e( 'New Leave Request', 'hr-management-lite' ); ?></a>
                                        </td>
                                        <td><?php esc_html_e( 'New leave request notification to HR Manager.', 'hr-management-lite' ); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php esc_html_e( '3.', 'hr-management-lite' ); ?></td>
                                        <td>
                                            <a href="#" class="email_template_settings" data-name="<?php echo esc_attr_e( 'Approved Leave Request', 'hr-management-lite' ); ?>" data-value="approved_leave_request"><?php esc_html_e( 'Approved Leave Request', 'hr-management-lite' ); ?></a>
                                        </td>
                                        <td><?php esc_html_e( 'Approved leave request notification to employee.', 'hr-management-lite' ); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php esc_html_e( '4.', 'hr-management-lite' ); ?></td>
                                        <td>
                                            <a href="#" class="email_template_settings" data-name="<?php echo esc_attr_e( 'Rejected Leave Request', 'hr-management-lite' ); ?>" data-value="rejected_leave_request"><?php esc_html_e( 'Rejected Leave Request', 'hr-management-lite' ); ?></a>
                                        </td>
                                        <td><?php esc_html_e( 'Rejected leave request notification to employee.', 'hr-management-lite' ); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php esc_html_e( '5.', 'hr-management-lite' ); ?></td>
                                        <td>
                                            <a href="#" class="email_template_settings" data-name="<?php echo esc_attr_e( 'New Project Assigned', 'hr-management-lite' ); ?>" data-value="new_project_assigned"><?php esc_html_e( 'New Project Assigned', 'hr-management-lite' ); ?></a>
                                        </td>
                                        <td><?php esc_html_e( 'New project assigned notification to employee.', 'hr-management-lite' ); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php esc_html_e( '6.', 'hr-management-lite' ); ?></td>
                                        <td>
                                            <a href="#" class="email_template_settings" data-name="<?php echo esc_attr_e( 'New Task Assigned', 'hr-management-lite' ); ?>" data-value="new_task_assigned"><?php esc_html_e( 'New Task Assigned', 'hr-management-lite' ); ?></a>
                                        </td>
                                        <td><?php esc_html_e( 'New task assigned notification to employee.', 'hr-management-lite' ); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php esc_html_e( '7.', 'hr-management-lite' ); ?></td>
                                        <td>
                                            <a href="#" class="email_template_settings" data-name="<?php echo esc_attr_e( 'New Employee Introduction Email', 'hr-management-lite' ); ?>" data-value="new_contact_assigned"><?php esc_html_e( 'New Employee Introduction Email', 'hr-management-lite' ); ?></a>
                                        </td>
                                        <td><?php esc_html_e( 'New Employee joining Announcement.', 'hr-management-lite' ); ?></td>
                                    </tr>
                                </tbody>
                                <tfoot>
			                      	<tr>
				                        <th><?php esc_html_e( 'No.', 'hr-management-lite' ); ?></th>
										<th><?php esc_html_e( 'Email', 'hr-management-lite' ); ?></th>
										<th><?php esc_html_e( 'Description', 'hr-management-lite' ); ?></th>
			                     	</tr>
		                    	</tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Notice Modal -->
		<div class="modal fade" id="ShoeEmailOptions" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg modal-notify modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"></h4>
	                  <form class="forms-sample" method="post" id="email_modal_options">
	                  	<div class="form-group">
	                      <label for="email_subject"><?php esc_html_e( 'Subject', 'hr-management-lite' ); ?></label>
	                      <input type="text" class="form-control" id="email_subject" placeholder="<?php esc_html_e( 'Email Subject', 'hr-management-lite' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="email_heading"><?php esc_html_e( 'Email Heading', 'hr-management-lite' ); ?></label>
                          <input type="text" class="form-control" id="email_heading" placeholder="<?php esc_html_e( 'Email heading....', 'hr-management-lite' ); ?>">
	                    </div>
                        <div class="form-group">
	                        <label for="notice_desc"><?php esc_html_e( 'Email Body', 'hr-management-lite' ); ?></label>
	                        <?php                                 
                                wp_editor( '', 'email_body', $settings = array( 'editor_height' => 200, 'textarea_rows' => 20, 'drag_drop_upload' => true ) ); 
                            ?>
                        </div>
                        <div class="form-group">
	                      <label for="tags"><?php esc_html_e( 'Template Tags', 'hr-management-lite' ); ?></label>
                          <p class="email_template_tags"><p>
                        </div>
                        <input type="hidden" id='email_template_tags' value="">
                        <input type="hidden" id="email_id_name" name="email_id_name" value="">
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="update_email_options" value="<?php esc_html_e( 'Save changes', 'hr-management-lite' ); ?>">
	                  </form>
	                </div>
	            </div>
		    </div>
		  </div>
		</div>

        </div>

    </div>
</div>