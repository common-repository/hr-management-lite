<?php
defined( 'ABSPATH' ) or die();

/**
 *  calss for set default options
 */
class LiteSetDeafaultOptions {

	public static function hrm_lite_activation_actions() {

		do_action( 'hrm_lite_extension_activation' );

	}

	public static function hrm_lite_activation_default_emails() {

		do_action( 'hrm_lite_default_emails_activation' );

	}
	
	public static function default_settings() {

		// $all_settings = get_option( 'hrm_lite_settings_data' );
		$all_settings = get_option( 'ehrm_settings_data' );

		if ( empty ( $all_settings ) ) {
			$hrm_lite_settings_data = array(
				'timezone'         => 'Asia/Kolkata',
				'date_format'      => 'F j Y',
				'time_format'      => 'g:i A',
				'monday_status'    => 'Working',
				'tuesday_status'   => 'Working',
				'wednesday_status' => 'Working',
				'thursday_status'  => 'Working',
				'friday_status'    => 'Working',
				'saturday_status'  => 'Working',
				'sunday_status'    => 'Off',
				'halfday_start'    => '',
				'halfday_end'      => '',
				'lunch_start'      => '',
				'lunch_end'        => '',
				'cur_symbol'       => '₹',
				'cur_position'     => 'Right',
				'salary_method'    => 'Monthly',
				'lunchtime'        => 'Include',
				'shoot_mail'       => 'Yes',
				'show_holiday'     => 'Yes',
				'show_report'      => 'Yes',
				'show_notice'      => 'Yes',
				'late_reson'       => 'Yes',
				'salary_status'    => 'Yes',
				'mail_logo'        => '',
				'office_in_sub'    => __( 'Login Alert From Employee & HR Management', 'hr-management-lite' ),
				'office_out_sub'   => __( 'Logout Alert From Employee & HR Management', 'hr-management-lite' ),
				'mail_heading'     => __( 'Staff Login/Logout Details', 'hr-management-lite' ),
				'user_roles'       => serialize( array( 'subscriber' ) ),
			);

			// update_option( 'hrm_lite_settings_data', $hrm_lite_settings_data );
			update_option( 'ehrm_settings_data', $hrm_lite_settings_data );
		}
	}

	public static function hrm_lite_allow_subscriber_uploads() {
		if ( current_user_can('subscriber') && !current_user_can('upload_files') ){
			$contributor = get_role('subscriber');
			$contributor->add_cap('upload_files');
		}
	}

	public static function hrm_lite_users_own_attachments( $wp_query_obj ) {

		global $current_user, $pagenow;
	
		$is_attachment_request = ($wp_query_obj->get('post_type')=='attachment');
	
		if( !$is_attachment_request )
			return;
	
		if( !is_a( $current_user, 'WP_User') )
			return;
	
		if( !in_array( $pagenow, array( 'upload.php', 'admin-ajax.php' ) ) )
			return;
	
		if( !current_user_can('delete_pages') )
			$wp_query_obj->set('author', $current_user->ID );
	
		return;
	}

	public static function ehrm_setup_wizard_activation_hook() {
		add_option( 'wl_ehrm_setup_wizard', true );
	}

	public static function ehrm_setup_wizard_redirect() {
		if ( get_option( 'wl_ehrm_setup_wizard', false ) ) {
			delete_option( 'wl_ehrm_setup_wizard' );
			if ( ! isset( $_GET['activate-multi'] ) ) {
				wp_redirect( "index.php?page=hrm-lite-setup-wizard" );
			}
		}
	}

	public static function hrm_lite_setup_default_emails() {

		//Employee welcome
        $welcome = [
            'subject' => 'Welcome {full_name} to {company_name}',
            'heading' => 'Welcome Onboard {first_name}!',
			'body'    => 'Dear {full_name},
			<br>
			Welcome aboard as a <strong>{job_title}</strong> in our team at <strong>{company_name}</strong>! I am pleased to have you working with us. You were selected for employment due to the attributes that you displayed that appear to match the qualities I look for in an employee.
			<br>
			I’m looking forward to seeing you grow and develop into an outstanding employee that exhibits a high level of care, concern, and compassion for others. I hope that you will find your work to be rewarding, challenging, and meaningful.
			<br>
			Please take your time and review our yearly goals so that you can know what is expected and make a positive contribution. Again, I look forward to seeing you grow as a professional while enhancing the lives of the clients entrusted in your care.
			<br>
			Sincerely,
			<br>
			Manager Name
			<br>
			CEO, Company Name
			<br>',
			'tags'   => 'You may use these template tags inside subject, heading, body and those will be replaced by original values: {full_name}, {last_name}, {first_name}, {job_title}, {company_name}.'

		];
		update_option( 'ehrm_email_employee_welcome', $welcome );
		//EOD Employee welcome

		//New Leave Request
        $new_leave_request = [
            'subject' => 'New leave request received from {employee_name}',
            'heading' => 'New Leave Request',
            'body'    => 'Hello,
			<br>
			A new leave request has been received from {employee_name}.
			<br>
			<strong>Date:</strong> {date_from} to {date_to}
			<br>
			<strong>Days:</strong> {no_days}
			<br>
			<strong>Reason:</strong> {reason}
			<br>
			<br>
			Please approve/reject this leave application.
			<br>
			Thanks.',
			'tags'   => 'You may use these template tags inside subject, heading, body and those will be replaced by original values: {employee_name}, {date_from},{date_to}, {no_days}, {reason}.'
		];

		update_option( 'ehrm_email_new_leave_request', $new_leave_request );
		//EOD New Leave Request

		//Approved Leave Request
		$approved_request = [
			'subject' => 'Your leave request has been approved',
			'heading' => 'Leave Request Approved',
			'body'    => 'Hello {employee_name},
			<br>
			Your leave request for <strong>{no_days} days</strong> from {date_from} to {date_to} has been approved.
			<br>
			Regards
			<br>
			Manager Name
			<br>
			Company',
			'tags'   => 'You may use these template tags inside subject, heading, body and those will be replaced by original values: {employee_name}, {date_from},{date_to}, {no_days}, {reason}.'
		];
		update_option( 'ehrm_email_approved_leave_request', $approved_request );
		//EOD Approved Leave Request

		//Rejected Leave Request
		$reject_request = [
			'subject' => 'Your leave request has been rejected',
			'heading' => 'Leave Request Rejected',
			'body'    => 'Hello {employee_name},
			<br>
			Your leave request for <strong>{no_days} days</strong> from {date_from} to {date_to} has been rejected.
			<br>
			Regards
			<br>
			Manager Name
			<br>
			Company',
			'tags'   => 'You may use these template tags inside subject, heading, body and those will be replaced by original values: {employee_name}, {date_from},{date_to}, {no_days}.'
		];
		update_option( 'ehrm_email_rejected_leave_request', $reject_request );
		//EOD Rejected Leave Request

		// New Project Assigned
		$new_project_assigned = [
			'subject' => 'New project has been assigned to you',
			'heading' => 'New project Assigned',
			'body'    => 'Hello {employee_name},
			<br>
			A new project <strong>{project_title}</strong> has been assigned to you by {created_by}.
			<br>
			Regards
			<br>
			Manager Name
			<br>
			Company',
			'tags'   => 'You may use these template tags inside subject, heading, body and those will be replaced by original values: {employee_name}, {project_title},{created_by}.'
		];
		update_option( 'ehrm_email_new_project_assigned', $new_project_assigned );
		//EOD New Project Assigned
		
		// New Task Assigned
		$new_task_assigned = [
			'subject' => 'New task has been assigned to you',
			'heading' => 'New Task Assigned',
			'body'    => 'Hello {employee_name},
			<br>
			A new task <strong>{task_title}</strong> has been assigned to you by {created_by}, from project {project_title}.
			<br>
			Due Date: {due_date}
			<br>
			Regards
			<br>
			Manager Name
			<br>
			Company',
			'tags'   => 'You may use these template tags inside subject, heading, body and those will be replaced by original values: {employee_name}, {project_title}, {task_title},{created_by}, {due_date}.'
		];
		update_option( 'ehrm_email_new_task_assigned', $new_task_assigned );
		//EOD New Task Assigned

		// New Employee Introduction Email
		$new_contact_assigned = [
			'subject' => 'New Employee Introduction Email',
			'heading' => 'New Employee joining Announcement',
			'body'    => 'Dear Staffs,
			<br>
			I\'m happy to let you know that {employee_name} has accepted our job offer and joined the team as a quality {employee_designation}.
			<br>
			You can contact him/her by {employee_email}.
			<br>
			I appreciate you joining me in providing a warm welcome for {employee_name} with excitement.
			<br>
			Regards
			<br>
			Name of Department Manager/Boss
			<br>
			Company',
			'tags'   => 'You may use these template tags inside subject, heading, body and those will be replaced by original values: {employee_name}, {employee_email},{employee_designation}.'
		];
		update_option( 'ehrm_email_new_contact_assigned', $new_contact_assigned );
		//EOD New Employee Introduction Email
	}
}