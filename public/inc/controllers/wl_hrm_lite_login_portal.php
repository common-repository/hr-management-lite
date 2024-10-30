<?php
defined('ABSPATH') or die();
require_once(WL_HRML_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-hrm-lite-helper.php');

/* Fetching Location Post(Shortcode) Id */
extract(shortcode_atts(array(), $attr));

$save_settings = get_option('ehrm_settings_data');
if (!empty($save_settings)) {
  date_default_timezone_set(HRMLiteHelperClass::get_setting_timezone());
  $current_time = date("H:i:s");
  if ($current_time < '12:00:00') {
    $greetings = esc_html__('Good Morning, ', 'hr-management-lite');
  }
  if ($current_time > '12:00:00' && $current_time < '17:00:00') {
    $greetings = esc_html__('Good Afternoon, ', 'hr-management-lite');
  }
  if ($current_time > '17:00:00' && $current_time < '21:00:00') {
    $greetings = esc_html__('Good Evening, ', 'hr-management-lite');
  }
  if ($current_time > '21:00:00' && $current_time < '04:00:00') {
    $greetings = esc_html__('Good Night, ', 'hr-management-lite');
  }
}

?>

<?php

if (is_user_logged_in()) { ?>
  <div class="wl_ehrm container">
    <div class="login-form">
      <form action="" method="post">
        <div class="avatar">
          <img src="<?php echo esc_url(get_avatar_url(get_current_user_id())); ?>" alt="Avatar">
        </div>
        <h2 class="text-center"><?php esc_html_e('Staff Attendance', 'hr-management-lite'); ?></h2>

        <h3 class="text-center">
          <?php
          esc_html_e($greetings, 'hr-management-lite');
          if (is_user_logged_in()) {
            echo esc_html(HRMLiteHelperClass::get_current_user_data(get_current_user_id(), 'fullname'));
          }
          ?>
        </h3>

        <div class="current_time_clock">
          <div class="card bg-dark text-white">
            <h3 class="card-title text-center">
              <div class="d-flex flex-wrap justify-content-center mt-2">
                <a><span class="badge hours"></span></a> :
                <a><span class="badge min"></span></a> :
                <a><span class="badge sec"></span></a>
              </div>
            </h3>
          </div>
        </div>

        <div id="ehrm-login-portal">
          <?php
          $attendences  = get_option('ehrm_staff_attendence_data');
          $user_id      = get_current_user_id();
          $html         = '';
          $current_date = date('Y-m-d');

          if (!empty($attendences)) {
            foreach ($attendences as $key => $attendence) {
              if ($attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && !empty($attendence['office_in']) && $attendence['late'] == 0) {

                echo '<h3 class="">' . esc_html__('Your Office In Time is', 'hr-management-lite') . ' ' . esc_html(date(self::get_time_format(), strtotime($attendence['office_in']))) . '</h3>';
              } elseif ($attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && !empty($attendence['office_in']) && $attendence['late'] == 1) {
                echo '<h3 class="">' . esc_html__('You are late today!', 'hr-management-lite') . '</strong> ' . esc_html__('Your Office In Time is', 'hr-management-lite') . ' ' . esc_html(date(HRMLiteHelperClass::get_time_format(), strtotime($attendence['office_in']))) . '</h3>';
              }

              if ($attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && !empty($attendence['office_in']) && !empty($attendence['lunch_in'])) {
                echo '<h3 class="">' . esc_html__('Success!', 'hr-management-lite') . '</strong> ' . esc_html__('Your Lunch In Time is', 'hr-management-lite') . ' ' . esc_html(date(HRMLiteHelperClass::get_time_format(), strtotime($attendence['lunch_in']))) . '</h3>';
              }

              if ($attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && !empty($attendence['office_in']) && !empty($attendence['lunch_out'])) {
                echo '<h3 class="">' . esc_html__('Success!', 'hr-management-lite') . '</strong> ' . esc_html__('Your Lunch Out Time is', 'hr-management-lite') . ' ' . esc_html(date(HRMLiteHelperClass::get_time_format(), strtotime($attendence['lunch_out']))) . '</h3>';
              }

              if ($attendence['date'] == $current_date && $attendence['staff_id'] == $user_id && !empty($attendence['office_in']) && !empty($attendence['office_out'])) {
                echo '<h3 class="">' . esc_html__('Success!', 'hr-management-lite') . '</strong> ' . esc_html__('Your Office Out Time is', 'hr-management-lite') . ' ' . esc_html(date(HRMLiteHelperClass::get_time_format(), strtotime($attendence['office_out']))) . '</h3>';
              }
            }
          }
          ?>
        </div>

        <?php echo wp_kses_post(HRMLiteHelperClass::frondent_login_portal()); ?>
      </form>
    </div>

    <!-- Late Reson Modal -->
    <div class="modal fade" id="LateReson" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-notify modal-info">
        <div class="modal-content">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title"><?php esc_html_e('Submit you reson', 'hr-management-lite'); ?></h4>
              <form class="forms-sample" method="post" id="late_reson_form">
                <div class="form-group">
                  <label for="late_resonn"><?php esc_html_e('Enter your reson to come late today', 'hr-management-lite'); ?></label>
                  <textarea class="form-control" rows="6" id="late_resonn" name="late_resonn" placeholder="<?php esc_html_e('Content....', 'hr-management-lite'); ?>"></textarea>
                </div>
                <input type="hidden" name="staff_id" id="staff_id" value="<?php echo esc_attr(get_current_user_id()); ?>">
                <input type="button" class="btn btn-gradient-primary mr-2" id="late_reson_submit_btn" value="<?php esc_html_e('Submit', 'hr-management-lite'); ?>">
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Daily Report Modal -->
    <div class="modal fade" id="DailyReport" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-notify modal-info">
        <div class="modal-content">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title"><?php esc_html_e('Daily Report', 'hr-management-lite'); ?></h4>
              <form class="forms-sample" method="post" id="daily_report_form">
                <div class="form-group">
                  <label for="daily_report"><?php esc_html_e('Submit your daily report', 'hr-management-lite'); ?></label>
                  <textarea class="form-control" rows="6" id="daily_report" name="daily_report" placeholder="<?php esc_html_e('Content....', 'hr-management-lite'); ?>"></textarea>
                </div>
                <input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr(get_current_user_id()); ?>">
                <input type="button" class="btn btn-gradient-primary mr-2" id="daily_report_btn" value="<?php esc_html_e('Submit', 'hr-management-lite'); ?>">
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

<?php
} else {
  echo esc_html('Welcome, visitor Please!');
 ?>

 <?php
    if(get_permalink() != wp_login_url() && !is_user_logged_in()){
        // wp_redirect( wp_login_url() ); exit;
?>
        <a href="<?php echo esc_url(wp_login_url( get_permalink() )); ?>" title="<?php esc_html_e('Login', 'hr-management-lite'); ?>">
          <?php esc_html_e('Login', 'hr-management-lite'); ?>
        </a>
<?php
    }
}

?>