<?php
defined('ABSPATH') or die();

/* Set Option names to store data */
add_option('ehrm_settings_data');
add_option('ehrm_staffs_data');
add_option('ehrm_designations_data');
add_option('ehrm_events_data');
add_option('ehrm_holidays_data');
add_option('ehrm_notices_data');
add_option('ehrm_shifts_data');
add_option('ehrm_projects_data');
add_option('ehrm_email_options_data');
add_option('ehrm_staff_attendence_data');
add_option('ehrm_requests_data');

/** Default email template data **/
add_option('ehrm_email_employee_welcome');
add_option('ehrm_email_new_leave_request');
add_option('ehrm_email_approved_leave_request');
add_option('ehrm_email_rejected_leave_request');
add_option('ehrm_email_new_project_assigned');
add_option('ehrm_email_new_task_assigned');
add_option('ehrm_email_new_contact_assigned');

require_once('WL_HRML_MENU.php');
require_once('wl_hrm-lite_pro_button.php');
require_once('inc/controllers/wl-hrm-lite-settings.php');

/** Administrator **/
require_once('inc/controllers/wl-hrm-lite-designation-action.php');
require_once('inc/controllers/wl-hrm-lite-shift-actions.php');
require_once('inc/controllers/wl-hrm-lite-event-actions.php');
require_once('inc/controllers/wl-hrm-lite-notice-actions.php');
require_once('inc/controllers/wl-hrm-lite-holiday-actions.php');
require_once('inc/controllers/wl-hrm-lite-staff-actions.php');
require_once('inc/controllers/wl-hrm-lite-projects-actions.php');
require_once('inc/controllers/wl-hrm-lite-notification-actions.php');
require_once('inc/controllers/wl-hrm-lite-reports-actions.php');
require_once('inc/controllers/wl-hrm-lite-admin-dash-actions.php');
require_once('inc/controllers/wl-hrm-lite-staff-dash-actions.php');
require_once('inc/controllers/wl-hrm-lite-requests-actions.php');

/* Action for creating menu pages */
add_action('admin_menu', array( 'WL_HRML_AdminMenu', 'create_menu' ));

/* On admin init */
add_action('wp_ajax_wl-hrm-lite-settings', array( 'HRMLiteSaveSettings', 'save_settings' ));

/**-------------------------------------------------------------Designations-------------------------------------------------------------**/

/* Add Designations */
add_action('wp_ajax_nopriv_hrm_add_designation_action', array( 'LiteDesignationsAjaxAction', 'add_designations' ));
add_action('wp_ajax_hrm_add_designation_action', array( 'LiteDesignationsAjaxAction', 'add_designations' ));

/* Edit Designations */
add_action('wp_ajax_nopriv_hrm_edit_designation_action', array( 'LiteDesignationsAjaxAction', 'edit_designations' ));
add_action('wp_ajax_hrm_edit_designation_action', array( 'LiteDesignationsAjaxAction', 'edit_designations' ));

/* Update Designations */
add_action('wp_ajax_nopriv_hrm_update_designation_action', array( 'LiteDesignationsAjaxAction', 'update_designations' ));
add_action('wp_ajax_hrm_update_designation_action', array( 'LiteDesignationsAjaxAction', 'update_designations' ));

/* Delete Designations */
add_action('wp_ajax_nopriv_hrm_delete_designation_action', array( 'LiteDesignationsAjaxAction', 'delete_designations' ));
add_action('wp_ajax_hrm_delete_designation_action', array( 'LiteDesignationsAjaxAction', 'delete_designations' ));

/**----------------------------------------------------------------Shifts----------------------------------------------------------------**/

/* Add Notices */
add_action('wp_ajax_nopriv_hrm_add_shift_action', array( 'LiteShiftAjaxActions', 'add_shift' ));
add_action('wp_ajax_hrm_add_shift_action', array( 'LiteShiftAjaxActions', 'add_shift' ));

/* Edit Notices */
add_action('wp_ajax_nopriv_hrm_edit_shift_action', array( 'LiteShiftAjaxActions', 'edit_shift' ));
add_action('wp_ajax_hrm_edit_shift_action', array( 'LiteShiftAjaxActions', 'edit_shift' ));

/* Update Notices */
add_action('wp_ajax_nopriv_hrm_update_shift_action', array( 'LiteShiftAjaxActions', 'update_shift' ));
add_action('wp_ajax_hrm_update_shift_action', array( 'LiteShiftAjaxActions', 'update_shift' ));

/* Delete Notices */
add_action('wp_ajax_nopriv_hrm_delete_shift_action', array( 'LiteShiftAjaxActions', 'delete_shift' ));
add_action('wp_ajax_hrm_delete_shift_action', array( 'LiteShiftAjaxActions', 'delete_shift' ));

/**----------------------------------------------------------------Events----------------------------------------------------------------**/

/* Add Events */
add_action('wp_ajax_nopriv_hrm_add_event_action', array( 'LiteEventsAjaxAction', 'add_events' ));
add_action('wp_ajax_hrm_add_event_action', array( 'LiteEventsAjaxAction', 'add_events' ));

/* Edit Events */
add_action('wp_ajax_nopriv_hrm_edit_event_action', array( 'LiteEventsAjaxAction', 'edit_events' ));
add_action('wp_ajax_hrm_edit_event_action', array( 'LiteEventsAjaxAction', 'edit_events' ));

/* Update Events */
add_action('wp_ajax_nopriv_hrm_update_event_action', array( 'LiteEventsAjaxAction', 'update_events' ));
add_action('wp_ajax_hrm_update_event_action', array( 'LiteEventsAjaxAction', 'update_events' ));

/* Delete Events */
add_action('wp_ajax_nopriv_hrm_delete_event_action', array( 'LiteEventsAjaxAction', 'delete_events' ));
add_action('wp_ajax_hrm_delete_event_action', array( 'LiteEventsAjaxAction', 'delete_events' ));

/**----------------------------------------------------------------Notice----------------------------------------------------------------**/

/* Add Notices */
add_action('wp_ajax_nopriv_hrm_add_notice_action', array( 'LiteNoticeAjaxAction', 'add_notices' ));
add_action('wp_ajax_hrm_add_notice_action', array( 'LiteNoticeAjaxAction', 'add_notices' ));

/* Edit Notices */
add_action('wp_ajax_noprivhrm_edit_notice_action', array( 'LiteNoticeAjaxAction', 'edit_notices' ));
add_action('wp_ajax_hrm_edit_notice_action', array( 'LiteNoticeAjaxAction', 'edit_notices' ));

/* Update Notices */
add_action('wp_ajax_nopriv_hrm_update_notice_action', array( 'LiteNoticeAjaxAction', 'update_notices' ));
add_action('wp_ajax_hrm_update_notice_action', array( 'LiteNoticeAjaxAction', 'update_notices' ));

/* Delete Notices */
add_action('wp_ajax_nopriv_hrm_delete_notice_action', array( 'LiteNoticeAjaxAction', 'delete_notices' ));
add_action('wp_ajax_hrm_delete_notice_action', array( 'LiteNoticeAjaxAction', 'delete_notices' ));

/**----------------------------------------------------------------Holidayss----------------------------------------------------------------**/

/* Add Holidays */
add_action('wp_ajax_nopriv_hrm_add_holiday_action', array( 'LiteHolidaysAjaxAction', 'add_holidays' ));
add_action('wp_ajax_hrm_add_holiday_action', array( 'LiteHolidaysAjaxAction', 'add_holidays' ));

/* Edit Holidays */
add_action('wp_ajax_nopriv_hrm_edit_holiday_action', array( 'LiteHolidaysAjaxAction', 'edit_holidays' ));
add_action('wp_ajax_hrm_edit_holiday_action', array( 'LiteHolidaysAjaxAction', 'edit_holidays' ));

/* Update Holidays */
add_action('wp_ajax_nopriv_hrm_update_holiday_action', array( 'LiteHolidaysAjaxAction', 'update_holidays' ));
add_action('wp_ajax_hrm_update_holiday_action', array( 'LiteHolidaysAjaxAction', 'update_holidays' ));

/* Delete Holidays */
add_action('wp_ajax_nopriv_hrm_delete_holiday_action', array( 'LiteHolidaysAjaxAction', 'delete_holidays' ));
add_action('wp_ajax_hrm_delete_holiday_action', array( 'LiteHolidaysAjaxAction', 'delete_holidays' ));

/**----------------------------------------------------------------Staff----------------------------------------------------------------**/

/* Fetch user's data */
add_action('wp_ajax_nopriv_hrm_fetch_staff_action', array( 'LiteStaffAjaxActions', 'fetch_userdata' ));
add_action('wp_ajax_hrm_fetch_staff_action', array( 'LiteStaffAjaxActions', 'fetch_userdata' ));

/* Add Staff */
add_action('wp_ajax_nopriv_hrm_add_staff_action', array( 'LiteStaffAjaxActions', 'add_staff' ));
add_action('wp_ajax_hrm_add_staff_action', array( 'LiteStaffAjaxActions', 'add_staff' ));

/* Edit Staff */
add_action('wp_ajax_nopriv_hrm_edit_staff_action', array( 'LiteStaffAjaxActions', 'edit_staff' ));
add_action('wp_ajax_hrm_edit_staff_action', array( 'LiteStaffAjaxActions', 'edit_staff' ));

/* Update Staff */
add_action('wp_ajax_nopriv_hrm_update_staff_action', array( 'LiteStaffAjaxActions', 'update_staff' ));
add_action('wp_ajax_hrm_update_staff_action', array( 'LiteStaffAjaxActions', 'update_staff' ));

/* Delete Staff */
add_action('wp_ajax_nopriv_hrm_delete_staff_action', array( 'LiteStaffAjaxActions', 'delete_staff' ));
add_action('wp_ajax_hrm_delete_staff_action', array( 'LiteStaffAjaxActions', 'delete_staff' ));

/**----------------------------------------------------------------Admin Login Dashboard----------------------------------------------------------------**/

/* Staff's clock actions */
add_action('wp_ajax_nopriv_hrm_login_dash_action', array( 'LiteAdminDashBoardAction', 'clock_actions' ));
add_action('wp_ajax_hrm_login_dash_action', array( 'LiteAdminDashBoardAction', 'clock_actions' ));

/**----------------------------------------------------------------Projects and Tasks----------------------------------------------------------------**/

/* Project Add actions */
add_action('wp_ajax_nopriv_hrm_add_project_ajax', array( 'LiteProjectAjaxAction', 'add_projects' ));
add_action('wp_ajax_hrm_add_project_ajax', array( 'LiteProjectAjaxAction', 'add_projects' ));

/* Project Edit actions */
add_action('wp_ajax_nopriv_hrm_edit_project_ajax', array( 'LiteProjectAjaxAction', 'edit_projects' ));
add_action('wp_ajax_hrm_edit_project_ajax', array( 'LiteProjectAjaxAction', 'edit_projects' ));

/* Project Update actions */
add_action('wp_ajax_nopriv_hrm_update_project_ajax', array( 'LiteProjectAjaxAction', 'update_projects' ));
add_action('wp_ajax_hrm_update_project_ajax', array( 'LiteProjectAjaxAction', 'update_projects' ));

/* Project Delete actions */
add_action('wp_ajax_nopriv_hrm_delete_project_ajax', array( 'LiteProjectAjaxAction', 'delete_projects' ));
add_action('wp_ajax_hrm_delete_project_ajax', array( 'LiteProjectAjaxAction', 'delete_projects' ));

/* View all tasks actions */
add_action('wp_ajax_nopriv_hrm_view_all_tasks_ajax', array( 'LiteProjectAjaxAction', 'view_all_tasks' ));
add_action('wp_ajax_hrm_view_all_tasks_ajax', array( 'LiteProjectAjaxAction', 'view_all_tasks' ));

/* Add tasks actions */
add_action('wp_ajax_nopriv_hrm_add_task_ajax', array( 'LiteProjectAjaxAction', 'add_tasks' ));
add_action('wp_ajax_hrm_add_task_ajax', array( 'LiteProjectAjaxAction', 'add_tasks' ));

/* Edit tasks actions */
add_action('wp_ajax_nopriv_hrm_edit_task_ajax', array( 'LiteProjectAjaxAction', 'edit_tasks' ));
add_action('wp_ajax_hrm_edit_task_ajax', array( 'LiteProjectAjaxAction', 'edit_tasks' ));

/* Update tasks actions */
add_action('wp_ajax_nopriv_hrm_update_task_ajax', array( 'LiteProjectAjaxAction', 'update_tasks' ));
add_action('wp_ajax_hrm_update_task_ajax', array( 'LiteProjectAjaxAction', 'update_tasks' ));

/* Delete tasks actions */
add_action('wp_ajax_nopriv_hrm_delete_task_ajax', array( 'LiteProjectAjaxAction', 'delete_tasks' ));
add_action('wp_ajax_hrm_delete_task_ajax', array( 'LiteProjectAjaxAction', 'delete_tasks' ));

/* View task details */
add_action('wp_ajax_nopriv_hrm_view_task_ajax', array( 'LiteProjectAjaxAction', 'view_task_details' ));
add_action('wp_ajax_hrm_view_task_ajax', array( 'LiteProjectAjaxAction', 'view_task_details' ));

/* Add Comment details */
add_action('wp_ajax_nopriv_hrm_add_comment_ajax', array( 'LiteProjectAjaxAction', 'add_comments' ));
add_action('wp_ajax_hrm_add_comment_ajax', array( 'LiteProjectAjaxAction', 'add_comments' ));

/* Edit Comment details */
add_action('wp_ajax_nopriv_hrm_edit_comment_ajax', array( 'LiteProjectAjaxAction', 'edit_comments' ));
add_action('wp_ajax_hrm_edit_comment_ajax', array( 'LiteProjectAjaxAction', 'edit_comments' ));

/* Update Comment details */
add_action('wp_ajax_nopriv_hrm_update_comment_ajax', array( 'LiteProjectAjaxAction', 'update_comments' ));
add_action('wp_ajax_hrm_update_comment_ajax', array( 'LiteProjectAjaxAction', 'update_comments' ));

/* Delete Comment details */
add_action('wp_ajax_nopriv_hrm_delete_comment_ajax', array( 'LiteProjectAjaxAction', 'delete_comments' ));
add_action('wp_ajax_hrm_delete_comment_ajax', array( 'LiteProjectAjaxAction', 'delete_comments' ));

/**----------------------------------------------------------------Email notifications----------------------------------------------------------------**/

/* Save options */
add_action('wp_ajax_nopriv_hrm_email_options_ajax', array( 'LiteNotificationsAjaxAction', 'save_options' ));
add_action('wp_ajax_hrm_email_options_ajax', array( 'LiteNotificationsAjaxAction', 'save_options' ));

/* Fetch options */
add_action('wp_ajax_nopriv_hrm_email_options_data', array( 'LiteNotificationsAjaxAction', 'show_email_template_data' ));
add_action('wp_ajax_hrm_email_options_data', array( 'LiteNotificationsAjaxAction', 'show_email_template_data' ));

/* Save data */
add_action('wp_ajax_nopriv_hrm_save_email_options_ajax', array( 'LiteNotificationsAjaxAction', 'save_email_template_data' ));
add_action('wp_ajax_hrm_save_email_options_ajax', array( 'LiteNotificationsAjaxAction', 'save_email_template_data' ));

/**----------------------------------------------------------------Reports----------------------------------------------------------------**/

/* Generate report */
add_action('wp_ajax_nopriv_hrm_get_reports_action', array( 'LiteReportAjaxAction', 'get_reports' ));
add_action('wp_ajax_hrm_get_reports_action', array( 'LiteReportAjaxAction', 'get_reports' ));

/* Calculate salary */
add_action('wp_ajax_nopriv_hrm_show_salary_action', array( 'LiteReportAjaxAction', 'display_salary' ));
add_action('wp_ajax_hrm_show_salary_action', array( 'LiteReportAjaxAction', 'display_salary' ));

/* Edit report */
add_action('wp_ajax_nopriv_hrm_edit_report_action', array( 'LiteReportAjaxAction', 'edit_reports' ));
add_action('wp_ajax_hrm_edit_report_action', array( 'LiteReportAjaxAction', 'edit_reports' ));

/* Update report */
add_action('wp_ajax_nopriv_hrm_update_report_action', array( 'LiteReportAjaxAction', 'update_reports' ));
add_action('wp_ajax_hrm_update_report_action', array( 'LiteReportAjaxAction', 'update_reports' ));

/* Download report */
add_action('admin_init', array( 'LiteReportAjaxAction', 'download_reports' ));

/**----------------------------------------------------------------Admin Login Dashboard----------------------------------------------------------------**/

/* Staff's clock actions */
add_action('wp_ajax_nopriv_hrm_login_dash_action', array( 'LiteAdminDashBoardAction', 'clock_actions' ));
add_action('wp_ajax_hrm_login_dash_action', array( 'LiteAdminDashBoardAction', 'clock_actions' ));

/**----------------------------------------------------------------Staff Dashboard----------------------------------------------------------------**/

/* Staff's clock actions */
add_action('wp_ajax_nopriv_hrm_clock_action', array( 'LiteStaffDashBoardAction', 'clock_actions' ));
add_action('wp_ajax_hrm_clock_action', array( 'LiteStaffDashBoardAction', 'clock_actions' ));

/* Late reson submit actions */
add_action('wp_ajax_nopriv_hrm_late_reson_action', array( 'LiteStaffDashBoardAction', 'late_reson_submit' ));
add_action('wp_ajax_hrm_late_reson_action', array( 'LiteStaffDashBoardAction', 'late_reson_submit' ));

/* Daily report submit actions */
add_action('wp_ajax_nopriv_hrm_daily_report_action', array( 'LiteStaffDashBoardAction', 'staff_daily_report' ));
add_action('wp_ajax_hrm_daily_report_action', array( 'LiteStaffDashBoardAction', 'staff_daily_report' ));

/**----------------------------------------------------------------Requests----------------------------------------------------------------**/

/* Add Requests */
add_action('wp_ajax_nopriv_hrm_add_req_action', array( 'LiteRequestsAjaxAction', 'add_requests' ));
add_action('wp_ajax_hrm_add_req_action', array( 'LiteRequestsAjaxAction', 'add_requests' ));

/* Edit Requests */
add_action('wp_ajax_nopriv_hrm_edit_req_action', array( 'LiteRequestsAjaxAction', 'edit_requests' ));
add_action('wp_ajax_hrm_edit_req_action', array( 'LiteRequestsAjaxAction', 'edit_requests' ));

/* Update Requests */
add_action('wp_ajax_nopriv_hrm_update_req_action', array( 'LiteRequestsAjaxAction', 'update_requests' ));
add_action('wp_ajax_hrm_update_req_action', array( 'LiteRequestsAjaxAction', 'update_requests' ));

/* Delete Requests */
add_action('wp_ajax_nopriv_hrm_delete_req_action', array( 'LiteRequestsAjaxAction', 'delete_requests' ));
add_action('wp_ajax_hrm_delete_req_action', array( 'LiteRequestsAjaxAction', 'delete_requests' ));


/**-------------------------------------------------------------- Go Pro Banner-------------------------------------------------------------- **/

add_action('admin_enqueue_scripts', 'wl_hrm_lite_banner_scripts');
function wl_hrm_lite_banner_scripts()
{
    wp_enqueue_style('font-awesome', WL_HRML_PLUGIN_URL . 'assets/css/font-awesome.min.css');
}

/**---------------------------------------------------------------Pro links-------------------------------------------------------------------**/
add_filter('plugin_action_links_' . WL_HRML_PLUGIN_BASENAME, array( 'WL_HRM_LITE_INIT', 'wl_hrm_lite_links' ));
add_action('wp_dashboard_setup', array( 'WL_HRM_LITE_INIT', 'wl_hrm_custom_dashboard_widgets' ));
add_action('admin_enqueue_scripts', array( 'WL_HRM_LITE_INIT', 'wl_hrm_admin_widget_assets' ));
