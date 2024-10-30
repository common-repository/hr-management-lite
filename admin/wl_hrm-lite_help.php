<?php
defined( 'ABSPATH' ) or die();
require_once( WL_HRML_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-hrm-lite-helper.php' ); ?>

<!-- partial -->
<div class="main-panel main-dashboard help_banner_dash">
	<div class="content-wrapper">
	    <div class="page-header">
	      	<h3 class="page-title">
	        	<span class="page-title-icon bg-gradient-primary text-white mr-2">
	          		<i class="fas fa-home"></i>
	        	</span>
	        	<?php esc_html_e( 'HR Management Lite ('.HRMLiteHelperClass::get_plugin_version().')', 'hr-management-lite' ); ?>
	      	</h3>
	  	</div>
	  	<div class="row dashboard_status_table">
	      	<div class="col-12 grid-margin">
	        	<div class="card">
	         	 	<div class="card-body">
	         	 		<h4 class="card-title help_banner"><?php esc_html_e( 'How To Configure', 'hr-management-lite' ); ?></h4>
	            		<div class="row">
	            			<div class="col-12 inner-banner-sec img-sec">
	            				<p><?php esc_html_e( 'Step 1. HR Management Lite->Settings->Select TimeZone first.', 'hr-management-lite' ); ?></p>
	            				<p><?php esc_html_e( 'Step 2. Then configure remaning settings as your requirement and save it.', 'hr-management-lite' ); ?></p>
	            				<p><?php esc_html_e( 'Step 3. HR Management Lite->Designation, Create staff designations you need it while staff creation.', 'hr-management-lite' ); ?></p>
	            				<p><?php esc_html_e( 'Step 4. HR Management Lite->Shift, Create shifts for your working hours.', 'hr-management-lite' ); ?></p>
	            				<p><?php esc_html_e( 'Step 5. HR Management Lite->Staff, Create or Add existing staff to "HR Management lite" plugin.', 'hr-management-lite' ); ?></p>
	            				<p><?php esc_html_e( 'Step 6. That\'s all.!', 'hr-management-lite' ); ?></p>
	            				<p><?php esc_html_e( 'You can manage "Email Templates via HR Management Lite->Notifications as you required so all get proper mail in proper format as you want.', 'hr-management-lite' ); ?></p>
	            				<p><?php esc_html_e( 'Remaining you can manage Events, Notices, Projects, Tasks and Upcoming Holidays.', 'hr-management-lite' ); ?></p>
	            			</div>
	            			<div class="col-12 inner-banner-sec img-sec">
	            				<h4 class="card-title help_banner"><?php esc_html_e( 'Shortcode', 'hr-management-lite' ); ?></h4>
	            				<div class="shortcode_inner">
	            					<p><?php esc_html_e( 'You can use this ', 'hr-management-lite' ); echo wp_kses_post( '<b>[WL_EHRM_LOGIN_FORM]</b> '); esc_html_e( 'for frontend login portal, Employee has to login first to use this. so employee can Office in & Office out from frontend.', 'hr-management-lite' ); ?></p>
	            				</div>
	            			</div>
	            		</div>
	            	</div>
	            </div>
	        </div>
	    </div>
	</div>
</div>