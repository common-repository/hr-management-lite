<?php
defined('ABSPATH') or die();
require_once( WL_HRML_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-hrm-lite-helper.php' );

$timezone_list    = HRMLiteHelperClass::timezone_list();
$save_settings    = get_option( 'ehrm_settings_data' );
$TimeZone         = isset( $save_settings['timezone'] ) ? sanitize_text_field( $save_settings['timezone'] ) : 'Asia/Kolkata';
$date_format      = isset( $save_settings['date_format'] ) ? sanitize_text_field( $save_settings['date_format'] ) : 'F j Y';
$time_format      = isset( $save_settings['time_format'] ) ? sanitize_text_field( $save_settings['time_format'] ) : 'g:i A';
$monday_status    = isset( $save_settings['monday_status'] ) ? sanitize_text_field( $save_settings['monday_status'] ) : 'Working';
$tuesday_status   = isset( $save_settings['tuesday_status'] ) ? sanitize_text_field( $save_settings['tuesday_status'] ) : 'Working';
$wednesday_status = isset( $save_settings['wednesday_status'] ) ? sanitize_text_field( $save_settings['wednesday_status'] ) : 'Working';
$thursday_status  = isset( $save_settings['thursday_status'] ) ? sanitize_text_field( $save_settings['thursday_status'] ) : 'Working';
$friday_status    = isset( $save_settings['friday_status'] ) ? sanitize_text_field( $save_settings['friday_status'] ) : 'Working';
$saturday_status  = isset( $save_settings['saturday_status'] ) ? sanitize_text_field( $save_settings['saturday_status'] ) : 'Working';
$sunday_status    = isset( $save_settings['sunday_status'] ) ? sanitize_text_field( $save_settings['sunday_status'] ) : 'Off';
$halfday_start    = isset( $save_settings['halfday_start'] ) ? sanitize_text_field( $save_settings['halfday_start'] ) : '';
$halfday_end      = isset( $save_settings['halfday_end'] ) ? sanitize_text_field( $save_settings['halfday_end'] ) : '';
$lunch_start      = isset( $save_settings['lunch_start'] ) ? sanitize_text_field( $save_settings['lunch_start'] ) : '';
$lunch_end        = isset( $save_settings['lunch_end'] ) ? sanitize_text_field( $save_settings['lunch_end'] ) : '';
$cur_symbol       = isset( $save_settings['cur_symbol'] ) ? sanitize_text_field( $save_settings['cur_symbol'] ) : '₹';
$cur_position     = isset( $save_settings['cur_position'] ) ? sanitize_text_field( $save_settings['cur_position'] ) : 'Right';
$salary_method    = isset( $save_settings['salary_method'] ) ? sanitize_text_field( $save_settings['salary_method'] ) : 'Monthly';
$lunchtime        = isset( $save_settings['lunchtime'] ) ? sanitize_text_field( $save_settings['lunchtime'] ) : 'Include';
$shoot_mail       = isset( $save_settings['shoot_mail'] ) ? sanitize_text_field( $save_settings['shoot_mail'] ) : 'Yes';
$show_holiday     = isset( $save_settings['show_holiday'] ) ? sanitize_text_field( $save_settings['show_holiday'] ) : 'Yes';
$show_report      = isset( $save_settings['show_report'] ) ? sanitize_text_field( $save_settings['show_report'] ) : 'Yes';
$show_notice      = isset( $save_settings['show_notice'] ) ? sanitize_text_field( $save_settings['show_notice'] ) : 'Yes';
$late_reson       = isset( $save_settings['late_reson'] ) ? sanitize_text_field( $save_settings['late_reson'] ) : 'Yes';
$salary_status    = isset( $save_settings['salary_status'] ) ? sanitize_text_field( $save_settings['salary_status'] ) : 'Yes';
$show_projects    = isset( $save_settings['show_projects'] ) ? sanitize_text_field( $save_settings['show_projects'] ) : 'Yes';
$user_roles       = isset( $save_settings['user_roles'] ) ? sanitize_text_field( $save_settings['user_roles'] ) : '';
$mail_logo        = isset( $save_settings['mail_logo'] ) ? sanitize_text_field( $save_settings['mail_logo'] ) : '';
$office_in_sub    = isset( $save_settings['office_in_sub'] ) ? sanitize_text_field( $save_settings['office_in_sub'] ) : __( 'Login Alert From Employee & HR Management', 'hr-management-lite' );
$office_out_sub = isset( $save_settings['office_out_sub'] ) ? sanitize_text_field( $save_settings['office_out_sub'] ) : __( 'Logout Alert From Employee & HR Management', 'hr-management-lite' );
$mail_heading   = isset( $save_settings['mail_heading'] ) ? sanitize_text_field( $save_settings['mail_heading'] ) : __( 'Staff Login/Logout Details', 'hr-management-lite' );
?>
<!-- partial -->
<div class="main-panel">
  <div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
          <i class="fas fa-sliders-h"></i>
        </span>
        <?php esc_html_e( 'Settings', 'hr-management-lite' ); ?>
      </h3>
      <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">
            <span></span><?php esc_html_e( 'Overview', 'hr-management-lite' ); ?>
            <i class="fas fa-exclamation-circle icon-sm text-primary align-middle"></i>
          </li>
        </ul>
      </nav>
    </div>
    <div class="row settings_panel">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">

            <!----- General settings ------>
            <h4 class="card-title bg-gradient-primary"><?php esc_html_e('General settings', 'hr-management-lite'); ?></h4>
            <form class="form-sample" id="hrm-lite-settings-form" method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
              <?php $nonce = wp_create_nonce('wl_hrm_lite_save_settings'); ?>
              <input type="hidden" name="wl_hrm_lite_setting_options" value="<?php echo esc_attr( $nonce ); ?>">
              <input type="hidden" name="action" value="wl-hrm-lite-settings">
              <div class="row">
                <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php esc_html_e( 'TimeZone', 'hr-management-lite' ); ?></label>
                    <div class="col-sm-8">
                      <select class="form-control" id="timezone" name="timezone">
                        <option value=""><?php esc_html_e('----------------------------------------------------------Select timezone----------------------------------------------------------', 'hr-management-lite'); ?></option>
                        <?php foreach ( $timezone_list as $timezone ) { ?>
                          <option value="<?php echo esc_attr( $timezone ); ?>" <?php selected( $TimeZone, $timezone ); ?>><?php echo esc_html( $timezone ); ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php esc_html_e('Date Format', 'hr-management-lite'); ?></label>
                    <div class="col-sm-8">
                      <select class="form-control" id="date_format" name="date_format">
                        <option value="<?php echo esc_attr('F j Y'); ?>" <?php selected( $date_format, 'F j Y' ); ?>><?php echo esc_html( date( 'F j Y' ) . ' ( F j Y ) '); ?></option>
                        <option value="<?php echo esc_attr('Y-m-d'); ?>" <?php selected( $date_format, 'Y-m-d' ); ?>><?php echo esc_html( date( 'Y-m-d' ) . ' ( YYYY-MM-DD )'); ?></option>
                        <option value="<?php echo esc_attr('m/d/Y'); ?>" <?php selected( $date_format, 'm/d/Y' ); ?>><?php echo esc_html( date( 'm/d/Y' ) . ' ( MM/DD/YYYY )'); ?></option>
                        <option value="<?php echo esc_attr('d-m-Y'); ?>" <?php selected( $date_format, 'd-m-Y' ); ?>><?php echo esc_html( date( 'd-m-Y' ) . ' ( DD-MM-YYYY )'); ?></option>
                        <option value="<?php echo esc_attr('m-d-Y'); ?>" <?php selected( $date_format, 'm-d-Y' ); ?>><?php echo esc_html( date( 'm-d-Y' ) . ' ( MM-DD-YYYY )'); ?></option>
                        <option value="e<?php echo esc_attr('jS F Y'); ?>" <?php selected( $date_format, 'jS F Y' ); ?>><?php echo esc_html( date( 'jS F Y' ) . ' ( d M YYYY )'); ?></option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php esc_html_e('Time Format', 'hr-management-lite'); ?></label>
                    <div class="col-sm-8">
                      <select class="form-control" id="time_format" name="time_format">
                        <option value="<?php echo esc_attr('g:i a'); ?>" <?php selected( $time_format, 'g:i a' ); ?>><?php echo esc_html( date( 'g:i a' ) . ' (  g:i a  )' ); ?></option>
                        <option value="<?php echo esc_attr('g:i A'); ?>" <?php selected( $time_format, 'g:i A' ); ?>><?php echo esc_html( date( 'g:i A' ) . ' (  g:i A  )' ); ?></option>
                        <option value="<?php echo esc_attr('H:i'); ?>" <?php selected( $time_format, 'H:i' ); ?>><?php echo esc_html( date( 'H:i' ) . ' (  H:i  )' ); ?></option>
                        <option value="<?php echo esc_attr('H:i:s'); ?>" <?php selected( $time_format, 'H:i:s' ); ?>><?php echo esc_html( date( 'H:i:s' ) . ' (  H:i:s  )' ); ?></option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php esc_html_e( 'Halfday Start Time', 'hr-management-lite' ); ?></label>
                    <div class="col-sm-8 bootstrap-timepicker timepicker">
                      <input type="text" name="halfday_start" id="halfday_start" class="form-control custom-timepicker-input" placeholder="<?php esc_html_e( 'Format:- 10:00 AM', 'hr-management-lite' ); ?>" value="<?php echo esc_attr( $halfday_start ); ?>">
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php esc_html_e('Lunch Start Time', 'hr-management-lite'); ?></label>
                    <div class="col-sm-8 bootstrap-timepicker timepicker">
                      <input type="text" name="lunch_start" id="lunch_start" class="form-control custom-timepicker-input" placeholder="<?php esc_html_e('Format:- 02:00 PM', 'hr-management-lite'); ?>" data-toggle="datetimepicker" data-target="#lunch_start" value="<?php echo esc_attr($lunch_start); ?>">
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php esc_html_e( 'Currency Symbol', 'hr-management-lite' ); ?></label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" placeholder="$" id="currency_symbol" name="currency_symbol" value="<?php echo esc_attr( $cur_symbol ); ?>">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php esc_html_e('Halfday End Time', 'hr-management-lite'); ?></label>
                    <div class="col-sm-8 bootstrap-timepicker timepicker">
                      <input type="text" name="halfday_end" id="halfday_end" class="form-control custom-timepicker-input" placeholder="<?php esc_html_e('Format:- 03:00 PM', 'hr-management-lite'); ?>" data-toggle="datetimepicker" data-target="#halfday_end" value="<?php echo esc_attr($halfday_end); ?>">
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php esc_html_e('Lunch End Time', 'hr-management-lite'); ?></label>
                    <div class="col-sm-8 bootstrap-timepicker timepicker">
                      <input type="text" name="lunch_end" id="lunch_end" class="form-control custom-timepicker-input" placeholder="<?php esc_html_e('Format:- 02:30 PM', 'hr-management-lite'); ?>" data-toggle="datetimepicker" data-target="#lunch_end" value="<?php echo esc_attr($lunch_end); ?>">
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php esc_html_e( 'Currency Position', 'hr-management-lite' ); ?></label>
                    <div class="col-sm-8">
                      <select class="form-control" id="currency_position" name="currency_position">
                        <option value="<?php echo esc_attr('Right'); ?>" <?php selected( $cur_position, 'Right' ); ?>><?php esc_html_e( 'Right', 'hr-management-lite' ); ?></option>
                        <option value="<?php echo esc_attr('Left'); ?>" <?php selected( $cur_position, 'Left' ); ?>><?php esc_html_e( 'Left', 'hr-management-lite' ); ?></option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">   
                <div class="col-lg-6 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php esc_html_e( 'Salary paid by', 'hr-management-lite' ); ?></label>
                    <div class="col-sm-3">
                      <div class="form-check form-check-success">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="salary_method" value="<?php echo esc_attr('Monthly'); ?>" checked="" <?php checked( $salary_method, 'Monthly' ); ?>>
                          <?php esc_html_e( 'Monthly', 'hr-management-lite' ); ?>
                          <i class="input-helper"></i></label>
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-check form-check-success">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="salary_method" value="<?php echo esc_attr('Hourly'); ?>" <?php checked( $salary_method, 'Hourly' ); ?>>
                          <?php esc_html_e( 'Hourly', 'hr-management-lite' ); ?>
                          <i class="input-helper"></i></label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php esc_html_e('Include/Exclude Lunch time from Working Hours', 'hr-management-lite'); ?></label>
                    <div class="col-sm-3">
                      <div class="form-check form-check-success">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="lunch_time_status" value="<?php echo esc_attr('Include'); ?>" checked="" <?php checked($lunchtime, 'Include'); ?>>
                          <?php esc_html_e('Include', 'hr-management-lite'); ?>
                          <i class="input-helper"></i></label>
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-check form-check-danger">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="lunch_time_status" value="<?php echo esc_attr('Exclude'); ?>" <?php checked($lunchtime, 'Exclude'); ?>>
                          <?php esc_html_e('Exclude', 'hr-management-lite'); ?>
                          <i class="input-helper"></i></label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <h4 class="card-title bg-gradient-primary"><?php esc_html_e( 'Week days status', 'hr-management-lite'); ?></h4>
              <div class="row">   
                <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php esc_html_e( 'Monday', 'hr-management-lite'); ?></label>
                    <div class="col-sm-8">
                      <select class="form-control" id="monday_status" name="monday_status">
                        <option value="<?php echo esc_attr('Working'); ?>" <?php selected( $monday_status, 'Working' ); ?>><?php esc_html_e( 'Working', 'hr-management-lite' ); ?></option>
                        <option value="<?php echo esc_attr('Half Day'); ?>" <?php selected( $monday_status, 'Half Day' ); ?>><?php esc_html_e( 'Half Day', 'hr-management-lite' ); ?></option>
                        <option value="<?php echo esc_attr('Off'); ?>" <?php selected( $monday_status, 'Off' ); ?>><?php esc_html_e( 'Off', 'hr-management-lite' ); ?></option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php esc_html_e( 'Tuesday', 'hr-management-lite'); ?></label>
                    <div class="col-sm-8">
                      <select class="form-control" id="tuesday_status" name="tuesday_status">
                        <option value="<?php echo esc_attr('Working'); ?>" <?php selected( $tuesday_status, 'Working' ); ?>><?php esc_html_e( 'Working', 'hr-management-lite' ); ?></option>
                        <option value="<?php echo esc_attr('Half Day'); ?>" <?php selected( $tuesday_status, 'Half Day' ); ?>><?php esc_html_e( 'Half Day', 'hr-management-lite' ); ?></option>
                        <option value="<?php echo esc_attr('Off'); ?>" <?php selected( $tuesday_status, 'Off' ); ?>><?php esc_html_e( 'Off', 'hr-management-lite' ); ?></option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php esc_html_e( 'Wednesday', 'hr-management-lite'); ?></label>
                    <div class="col-sm-8">
                      <select class="form-control" id="wednesday_status" name="wednesday_status">
                        <option value="<?php echo esc_attr('Working'); ?>" <?php selected( $wednesday_status, 'Working' ); ?>><?php esc_html_e( 'Working', 'hr-management-lite' ); ?></option>
                        <option value="<?php echo esc_attr('Half Day'); ?>" <?php selected( $wednesday_status, 'Half Day' ); ?>><?php esc_html_e( 'Half Day', 'hr-management-lite' ); ?></option>
                        <option value="<?php echo esc_attr('Off'); ?>" <?php selected( $wednesday_status, 'Off' ); ?>><?php esc_html_e( 'Off', 'hr-management-lite' ); ?></option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">   
                <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php esc_html_e( 'Thursday', 'hr-management-lite'); ?></label>
                    <div class="col-sm-8">
                      <select class="form-control" id="thursday_status" name="thursday_status">
                        <option value="<?php echo esc_attr('Working'); ?>" <?php selected( $thursday_status, 'Working' ); ?>><?php esc_html_e( 'Working', 'hr-management-lite' ); ?></option>
                        <option value="<?php echo esc_attr('Half Day'); ?>" <?php selected( $thursday_status, 'Half Day' ); ?>><?php esc_html_e( 'Half Day', 'hr-management-lite' ); ?></option>
                        <option value="<?php echo esc_attr('Off'); ?>" <?php selected( $thursday_status, 'Off' ); ?>><?php esc_html_e( 'Off', 'hr-management-lite' ); ?></option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php esc_html_e( 'Friday', 'hr-management-lite'); ?></label>
                    <div class="col-sm-8">
                      <select class="form-control" id="friday_status" name="friday_status">
                        <option value="<?php echo esc_attr('Working'); ?>" <?php selected( $friday_status, 'Working' ); ?>><?php esc_html_e( 'Working', 'hr-management-lite' ); ?></option>
                        <option value="<?php echo esc_attr('Half Day'); ?>" <?php selected( $friday_status, 'Half Day' ); ?>><?php esc_html_e( 'Half Day', 'hr-management-lite' ); ?></option>
                        <option value="<?php echo esc_attr('Off'); ?>" <?php selected( $friday_status, 'Off' ); ?>><?php esc_html_e( 'Off', 'hr-management-lite' ); ?></option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php esc_html_e( 'Saturday', 'hr-management-lite'); ?></label>
                    <div class="col-sm-8">
                      <select class="form-control" id="saturday_status" name="saturday_status">
                        <option value="<?php echo esc_attr('Working'); ?>" <?php selected( $saturday_status, 'Working' ); ?>><?php esc_html_e( 'Working', 'hr-management-lite' ); ?></option>
                        <option value="<?php echo esc_attr('Half Day'); ?>" <?php selected( $saturday_status, 'Half Day' ); ?>><?php esc_html_e( 'Half Day', 'hr-management-lite' ); ?></option>
                        <option value="<?php echo esc_attr('Off'); ?>" <?php selected( $saturday_status, 'Off' ); ?>><?php esc_html_e( 'Off', 'hr-management-lite' ); ?></option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">   
                <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php esc_html_e( 'Sunday', 'hr-management-lite'); ?></label>
                    <div class="col-sm-8">
                      <select class="form-control" id="sunday_status" name="sunday_status">
                        <option value="<?php echo esc_attr('Working'); ?>" <?php selected( $sunday_status, 'Working' ); ?>><?php esc_html_e( 'Working', 'hr-management-lite' ); ?></option>
                        <option value="<?php echo esc_attr('Half Day'); ?>" <?php selected( $sunday_status, 'Half Day' ); ?>><?php esc_html_e( 'Half Day', 'hr-management-lite' ); ?></option>
                        <option value="<?php echo esc_attr('Off'); ?>" <?php selected( $sunday_status, 'Off' ); ?>><?php esc_html_e( 'Off', 'hr-management-lite' ); ?></option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <!----- Staff's settings ------>
              <h4 class="card-title bg-gradient-primary"><?php esc_html_e('Staff\'s settings', 'hr-management-lite'); ?></h4>
              <div class="row">
                <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php esc_html_e('Show Holidays', 'hr-management-lite'); ?></label>
                    <div class="col-sm-3">
                      <div class="form-check form-check-success">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="show_holiday" value="<?php echo esc_attr('Yes'); ?>" checked="" <?php checked($show_holiday, 'Yes'); ?>>
                          <?php esc_html_e('Yes', 'hr-management-lite'); ?>
                          <i class="input-helper"></i></label>
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-check form-check-danger">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="show_holiday" value="<?php echo esc_attr('No'); ?>" <?php checked($show_holiday, 'No'); ?>>
                          <?php esc_html_e('No', 'hr-management-lite'); ?>
                          <i class="input-helper"></i></label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php esc_html_e('Enable Report Submission', 'hr-management-lite'); ?></label>
                    <div class="col-sm-3">
                      <div class="form-check form-check-success">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="report_submission" value="<?php echo esc_attr('Yes'); ?>" checked="" <?php checked($show_report, 'Yes'); ?>>
                          <?php esc_html_e('Yes', 'hr-management-lite'); ?>
                          <i class="input-helper"></i></label>
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-check form-check-danger">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="report_submission" value="<?php echo esc_attr('No'); ?>" <?php checked($show_report, 'No'); ?>>
                          <?php esc_html_e('No', 'hr-management-lite'); ?>
                          <i class="input-helper"></i></label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php esc_html_e('Show Notice', 'hr-management-lite'); ?></label>
                    <div class="col-sm-3">
                      <div class="form-check form-check-success">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="show_notice" value="<?php echo esc_attr('Yes'); ?>" checked="" <?php checked($show_notice, 'Yes'); ?>>
                          <?php esc_html_e('Yes', 'hr-management-lite'); ?>
                          <i class="input-helper"></i></label>
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-check form-check-danger">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="show_notice" value="<?php echo esc_attr('No'); ?>" <?php checked($show_notice, 'No'); ?>>
                          <?php esc_html_e('No', 'hr-management-lite'); ?>
                          <i class="input-helper"></i></label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php esc_html_e('Enable Late Reason Submission', 'hr-management-lite'); ?></label>
                    <div class="col-sm-3">
                      <div class="form-check form-check-success">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="late_reson" value="<?php echo esc_attr('Yes'); ?>" checked="" <?php checked($late_reson, 'Yes'); ?>>
                          <?php esc_html_e('Yes', 'hr-management-lite'); ?>
                          <i class="input-helper"></i></label>
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-check form-check-danger">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="late_reson" value="<?php echo esc_attr('No'); ?>" <?php checked($late_reson, 'No'); ?>>
                          <?php esc_html_e('No', 'hr-management-lite'); ?>
                          <i class="input-helper"></i></label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php esc_html_e('Show Salary Status', 'hr-management-lite'); ?></label>
                    <div class="col-sm-3">
                      <div class="form-check form-check-success">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="salary_status" value="<?php echo esc_attr('Yes'); ?>" checked="" <?php checked($salary_status, 'Yes'); ?>>
                          <?php esc_html_e('Yes', 'hr-management-lite'); ?>
                          <i class="input-helper"></i></label>
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-check form-check-danger">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="salary_status" value="<?php echo esc_attr('No'); ?>" <?php checked($salary_status, 'No'); ?>>
                          <?php esc_html_e('No', 'hr-management-lite'); ?>
                          <i class="input-helper"></i></label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php esc_html_e('Show Projects', 'hr-management-lite'); ?></label>
                    <div class="col-sm-3">
                      <div class="form-check form-check-success">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="show_projects" value="<?php echo esc_attr('Yes'); ?>" checked="" <?php checked($show_projects, 'Yes'); ?>>
                          <?php esc_html_e('Yes', 'hr-management-lite'); ?>
                          <i class="input-helper"></i></label>
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-check form-check-danger">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="show_projects" value="<?php echo esc_attr('No'); ?>" <?php checked($show_projects, 'No'); ?>>
                          <?php esc_html_e('No', 'hr-management-lite'); ?>
                          <i class="input-helper"></i></label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-12 col-form-label"><?php esc_html_e('Select roles for staff\'s.', 'hr-management-lite'); ?></label>
                    <?php 
                        if ( ! empty( $save_settings['user_roles'] ) ) {
                          $user_roles = unserialize( $save_settings['user_roles'] );
                        } else {
                          $user_roles = array('subscriber');
                        }
                        global $wp_roles;
                        $all_roles = $wp_roles->roles;
                        foreach ( $all_roles as $key => $value ) {
                          if ( $value["name"] != 'Administrator' ) {
                      ?>
                      <div class="col-sm-3">
                        <div class="form-check form-check-success">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" <?php if ( is_array( $user_roles ) ) { if ( in_array( strtolower( $value["name"] ), $user_roles ) ) { echo esc_attr('checked'); } } ?> name="user_roles[]" value="<?php echo esc_attr( strtolower( $value["name"] ) ); ?>">
                            <?php esc_html_e( $value["name"], 'hr-management-lite' ); ?>
                            <i class="input-helper"></i></label>
                        </div>
                      </div>
                      <?php } } ?>
                    <br>
                    <span class="option-info-text">
                      <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                      <?php esc_html_e( 'Staff\'s login dashboard shows only for selected user roles.', 'hr-management-lite' ); ?>
                    </span>
                  </div>
                </div>
              </div>

              <!----- Email settings ------>
              <h4 class="card-title bg-gradient-primary"><?php esc_html_e( 'Email Settings', 'hr-management-lite' ); ?></h4>
              <div class="row">
                <div class="col-lg-6 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php esc_html_e('Shoot Mail when user Login/Logout', 'hr-management-lite'); ?></label>
                    <div class="col-sm-3">
                      <div class="form-check form-check-success">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="shoot_mail" value="<?php echo esc_attr('Yes'); ?>" checked="" <?php checked($shoot_mail, 'Yes'); ?>>
                          <?php esc_html_e('Yes', 'hr-management-lite'); ?>
                          <i class="input-helper"></i></label>
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-check form-check-danger">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="shoot_mail" value="<?php echo esc_attr('No'); ?>" <?php checked($shoot_mail, 'No'); ?>>
                          <?php esc_html_e('No', 'hr-management-lite'); ?>
                          <i class="input-helper"></i></label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">
                      <?php esc_html_e( 'Your logo for mail', 'hr-management-lite' ); ?>
                    </label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" name="mail_logo" id="mail_logo" value="<?php if ( ! empty ( $mail_logo ) ) { echo esc_attr( $mail_logo ); } ?>">
                    </div>
                    <div class="col-sm-3">
                      <button type="button" class="btn btn-gradient-success mr-2" id="upload_logo">
                        <?php esc_html_e( 'Upload', 'hr-management-lite' ); ?>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Notification Mail Subject ( Office in )', 'hr-management-lite' ); ?></label>
                    <div class="col-sm-9">
                      <textarea class="form-control" name="office_in_sub" id="office_in_sub"><?php if ( ! empty ( $office_in_sub ) ) { echo esc_textarea( $office_in_sub ); } ?></textarea>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Notification Mail Subject ( Office out )', 'hr-management-lite' ); ?></label>
                    <div class="col-sm-9">
                      <textarea class="form-control" name="office_out_sub" id="office_out_sub"><?php if ( ! empty ( $office_out_sub ) ) { echo esc_textarea( $office_out_sub ); } ?></textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Heading for mail content', 'hr-management-lite' ); ?></label>
                    <div class="col-sm-9">
                      <textarea class="form-control" name="mail_heading" id="mail_heading"><?php if ( ! empty ( $mail_heading ) ) { echo esc_textarea( $mail_heading ); } ?></textarea>
                    </div>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-gradient-success mr-2" id="save-settings-btn">
                <?php esc_html_e( 'Save Changes', 'hr-management-lite' ); ?>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>