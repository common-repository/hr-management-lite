<?php
defined( 'ABSPATH' ) or die();

class HRMLiteSaveSettings {
	/* Register settings */
	public static function save_settings() {
		if ( ! wp_verify_nonce( $_REQUEST['wl_hrm_lite_setting_options'], 'wl_hrm_lite_save_settings' ) ) {
			die();
		}

		$timezone         = isset( $_POST['timezone'] ) ? sanitize_text_field( $_POST['timezone'] ) : 'Asia/Kolkata';
		$date_format      = isset( $_POST['date_format'] ) ? sanitize_text_field( $_POST['date_format'] ) : 'F j Y';
		$time_format      = isset( $_POST['time_format'] ) ? sanitize_text_field( $_POST['time_format'] ) : 'g:i A';
		$halfday_start    = isset( $_POST['halfday_start'] ) ? sanitize_text_field( $_POST['halfday_start'] ) : '';
		$halfday_end      = isset( $_POST['halfday_end'] ) ? sanitize_text_field( $_POST['halfday_end'] ) : '';
		$monday_status    = isset( $_POST['monday_status'] ) ? sanitize_text_field( $_POST['monday_status'] ) : 'Working';
		$tuesday_status   = isset( $_POST['tuesday_status'] ) ? sanitize_text_field( $_POST['tuesday_status'] ) : 'Working';
		$wednesday_status = isset( $_POST['wednesday_status'] ) ? sanitize_text_field( $_POST['wednesday_status'] ) : 'Working';
		$thursday_status  = isset( $_POST['thursday_status'] ) ? sanitize_text_field( $_POST['thursday_status'] ) : 'Working';
		$friday_status    = isset( $_POST['friday_status'] ) ? sanitize_text_field( $_POST['friday_status'] ) : 'Working';
		$saturday_status  = isset( $_POST['saturday_status'] ) ? sanitize_text_field( $_POST['saturday_status'] ) : 'Working';
		$sunday_status    = isset( $_POST['sunday_status'] ) ? sanitize_text_field( $_POST['sunday_status'] ) : 'Off';
		$lunch_start      = isset( $_POST['lunch_start'] ) ? sanitize_text_field( $_POST['lunch_start'] ) : '';
		$lunch_end        = isset( $_POST['lunch_end'] ) ? sanitize_text_field( $_POST['lunch_end'] ) : '';
		$cur_symbol       = isset( $_POST['currency_symbol'] ) ? sanitize_text_field( $_POST['currency_symbol'] ) : '₹';
		$cur_position     = isset( $_POST['currency_position'] ) ? sanitize_text_field( $_POST['currency_position'] ) : 'Right';
		$salary_method    = isset( $_POST['salary_method'] ) ? sanitize_text_field( $_POST['salary_method'] ) : 'Monthly';
		$lunchtime        = isset( $_POST['lunch_time_status'] ) ? sanitize_text_field( $_POST['lunch_time_status'] ) : 'Include';
		$shoot_mail       = isset( $_POST['shoot_mail'] ) ? sanitize_text_field( $_POST['shoot_mail'] ) : 'Yes';
		$show_holiday     = isset( $_POST['show_holiday'] ) ? sanitize_text_field( $_POST['show_holiday'] ) : 'Yes';
		$show_report      = isset( $_POST['report_submission'] ) ? sanitize_text_field( $_POST['report_submission'] ) : 'Yes';
		$show_notice      = isset( $_POST['show_notice'] ) ? sanitize_text_field( $_POST['show_notice'] ) : 'Yes';
		$late_reson       = isset( $_POST['late_reson'] ) ? sanitize_text_field( $_POST['late_reson'] ) : 'Yes';
		$salary_status    = isset( $_POST['salary_status'] ) ? sanitize_text_field( $_POST['salary_status'] ) : 'Yes';
		$show_projects    = isset( $_POST['show_projects'] ) ? sanitize_text_field( $_POST['show_projects'] ) : 'Yes';
		$mail_logo        = isset( $_POST['mail_logo'] ) ? sanitize_text_field( $_POST['mail_logo'] ) : '';
		$office_in_sub    = isset( $_POST['office_in_sub'] ) ? sanitize_text_field( $_POST['office_in_sub'] ) : __( 'Login Alert From Employee & HR Management', 'hr-management-lite' );
		$office_out_sub   = isset( $_POST['office_out_sub'] ) ? sanitize_text_field( $_POST['office_out_sub'] ) : __( 'Logout Alert From Employee & HR Management', 'hr-management-lite' );
		$mail_heading     = isset( $_POST['mail_heading'] ) ? sanitize_text_field( $_POST['mail_heading'] ) : __( 'Staff Login/Logout Details', 'hr-management-lite' );

		$user_roles = ( isset( $_POST['user_roles'] ) && is_array( $_POST['user_roles'] ) ) ? $_POST['user_roles'] : array();
		$user_roles = array_map( 'sanitize_text_field', $user_roles );

		/* validations */
		$errors = [];
		if ( empty ( $timezone ) ) {
			$errors['timezone'] = __( 'Please select TimeZone', 'hr-management-lite' );
		}
		if ( empty ( $date_format ) ) {
			$errors['date_format'] = __( 'Please select Date format', 'hr-management-lite' );
		}
		if ( empty ( $time_format ) ) {
			$errors['time_format'] = __( 'Please select time format', 'hr-management-lite' );
		}
		if ( empty( $halfday_start ) ) {
			$errors['halfday_start'] = __('Please enter half start time', 'hr-management-lite');
		}
		if ( empty( $halfday_end ) ) {
			$errors['halfday_end'] = __('Please enter half end time', 'hr-management-lite');
		}
		if ( empty ( $lunch_start ) ) {
			$errors['lunch_start'] = __( 'Please enter lunch start time', 'hr-management-lite' );
		}
		if ( empty ( $lunch_end ) ) {
			$errors['lunch_end'] = __( 'Please enter lunch end time', 'hr-management-lite' );
		}
		if ( empty ( $cur_symbol ) ) {
			$errors['currency_symbol'] = __( 'Please enter currency symbol', 'hr-management-lite' );
		}
		if ( empty ( $cur_position ) ) {
			$errors['currency_position'] = __( 'Please select currency position', 'hr-management-lite' );
		}

		/* End validations */

		if ( count( $errors ) < 1 ) {
			$ehrm_settings_data = array(
				'timezone'         => $timezone,
				'date_format'      => $date_format,
				'time_format'      => $time_format,
				'monday_status'    => $monday_status,
				'tuesday_status'   => $tuesday_status,
				'wednesday_status' => $wednesday_status,
				'thursday_status'  => $thursday_status,
				'friday_status'    => $friday_status,
				'saturday_status'  => $saturday_status,
				'sunday_status'    => $sunday_status,
				'halfday_start'    => $halfday_start,
				'halfday_end'      => $halfday_end,
				'lunch_start'      => $lunch_start,
				'lunch_end'        => $lunch_end,
				'cur_symbol'       => $cur_symbol,
				'cur_position'     => $cur_position,
				'salary_method'    => $salary_method,
				'lunchtime'        => $lunchtime,
				'shoot_mail'       => $shoot_mail,
				'show_holiday'     => $show_holiday,
				'show_report'      => $show_report,
				'show_notice'      => $show_notice,
				'late_reson'       => $late_reson,
				'salary_status'    => $salary_status,
				'show_projects'    => $show_projects,
				'mail_logo'        => $mail_logo,
				'office_in_sub'    => $office_in_sub,
				'office_out_sub'   => $office_out_sub,
				'mail_heading'     => $mail_heading,
				'user_roles'       => serialize( $user_roles ),
			);

			update_option( 'ehrm_settings_data', $ehrm_settings_data );
			wp_send_json_success( array( 'message' => esc_html__( 'Settings updated successfully', 'hr-management-lite' ) ) );
		}
		wp_send_json_error( $errors );
	}
} 
?>