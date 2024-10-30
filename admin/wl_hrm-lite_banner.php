<?php
defined( 'ABSPATH' ) or die();
require_once( WL_HRML_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-hrm-lite-helper.php' ); ?>
<!-- partial -->
<div class="main-panel main-dashboard">
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
	            		<div class="row">
	            			<div class="col-6 inner-banner-sec img-sec">
	            				<a href="<?php echo esc_url('https://weblizar.com/plugins/employee-and-hr-management-wordpress-plugin/'); ?>" >
		            				<div class="image-wrapper text-center">
		            					<img src="<?php echo esc_url(WL_HRML_PLUGIN_URL . 'assets/images/IMG.jpg'); ?>" alt="<?php echo esc_html('banner_image'); ?>">
		            				</div>
		            			</a>
	            			</div>
	            			<div class="col-6 inner-banner-sec description-sec text-center">
	            				<div class="plugin-heading">
	            					<h1><?php echo esc_html('EMPLOYEE & HR MANAGEMENT PLUGIN') ?></h1>
	            				</div>
	            				<p class="plugin_details desc">
	            					<?php esc_html_e( 'EHRM is a user-friendly, intuitive system that provides smoothly integrated essential HR Time and Attendance functionality, Employee management, Leave management, Scheduling, Time tracking, Reporting and more.', 'hr-management-lite' ); ?>
	            				</p>
	            				<p class="plugin_details price"><?php esc_html_e( 'Single License $19', 'hr-management-lite' ); ?></p>
	            				<p class="plugin_details">
	            					<a href="<?php echo esc_url('https://weblizar.com/plugins/employee-and-hr-management-wordpress-plugin/'); ?>" class="btn btn-gradient-primary custom-btn"><?php esc_html_e( 'Buy Now', 'hr-management-lite' ); ?></a>
	            				</p>
	            			</div>
	            		</div>
	            	</div>
	            </div>
	        </div>
	    </div>
	</div>
</div>
