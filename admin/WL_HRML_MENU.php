<?php
defined('ABSPATH') or die();
require_once(WL_HRML_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-hrm-lite-helper.php');

/**
 *  Add Admin Menu Panel
 */
class WL_HRML_AdminMenu
{
    public static function create_menu()
    {
        $dashboard = add_menu_page(__('HR Management Lite', 'hr-management-lite'), __('HR Management Lite', 'hr-management-lite'), 'manage_options', 'hr-management-lite', array(
            'WL_HRML_AdminMenu',
            'dashboard'
        ), 'dashicons-groups', 25);
        add_action('admin_print_styles-' . $dashboard, array( 'WL_HRML_AdminMenu', 'dashboard_assets' ));

        /* Dashboard submenu */
        $dashboard_submenu = add_submenu_page('hr-management-lite', __('HR Management Lite', 'hr-management-lite'), __('Dashboard', 'hr-management-lite'), 'manage_options', 'hr-management-lite', array(
            'WL_HRML_AdminMenu',
            'dashboard'
        ));
        add_action('admin_print_styles-' . $dashboard_submenu, array( 'WL_HRML_AdminMenu', 'dashboard_assets' ));

        /* Designation submenu */
        $designation_submenu = add_submenu_page('hr-management-lite', __('Designation', 'hr-management-lite'), __('Designation', 'hr-management-lite'), 'manage_options', 'hr-management-lite-designation', array(
            'WL_HRML_AdminMenu',
            'designation'
        ));
        add_action('admin_print_styles-' . $designation_submenu, array( 'WL_HRML_AdminMenu', 'dashboard_assets' ));

        /* Leave Requests submenu */
        $requests_submenu = add_submenu_page('hr-management-lite', __('Leave Requests', 'hr-management-lite'), __('Leave Requests', 'hr-management-lite'), 'manage_options', 'hr-management-lite-requests', array(
            'WL_HRML_AdminMenu',
            'requests'
        ));
        add_action('admin_print_styles-' . $requests_submenu, array( 'WL_HRML_AdminMenu', 'dashboard_assets' ));

        /* Shift submenu */
        $shift_submenu = add_submenu_page('hr-management-lite', __('Shifts', 'hr-management-lite'), __('Shifts', 'hr-management-lite'), 'manage_options', 'hr-management-lite-shift', array(
            'WL_HRML_AdminMenu',
            'shift'
        ));
        add_action('admin_print_styles-' . $shift_submenu, array( 'WL_HRML_AdminMenu', 'event_assets' ));

        /* Staff submenu */
        $staff_submenu = add_submenu_page('hr-management-lite', __('Staff', 'hr-management-lite'), __('Staff', 'hr-management-lite'), 'manage_options', 'hr-management-lite-staff', array(
            'WL_HRML_AdminMenu',
            'staff'
        ));
        add_action('admin_print_styles-' . $staff_submenu, array( 'WL_HRML_AdminMenu', 'dashboard_assets' ));

        /* Reports submenu */
        $reports_submenu = add_submenu_page('hr-management-lite', __('Reports', 'hr-management-lite'), __('Reports', 'hr-management-lite'), 'manage_options', 'hr-management-lite-reports', array(
            'WL_HRML_AdminMenu',
            'reports'
        ));
        add_action('admin_print_styles-' . $reports_submenu, array( 'WL_HRML_AdminMenu', 'report_assets' ));

        /* Events submenu */
        $event_submenu = add_submenu_page('hr-management-lite', __('Events', 'hr-management-lite'), __('Events', 'hr-management-lite'), 'manage_options', 'hr-management-lite-events', array(
            'WL_HRML_AdminMenu',
            'events'
        ));
        add_action('admin_print_styles-' . $event_submenu, array( 'WL_HRML_AdminMenu', 'event_assets' ));

        /* Notices submenu */
        $notices_submenu = add_submenu_page('hr-management-lite', __('Notices', 'hr-management-lite'), __('Notices', 'hr-management-lite'), 'manage_options', 'hr-management-lite-notices', array(
            'WL_HRML_AdminMenu',
            'notices'
        ));
        add_action('admin_print_styles-' . $notices_submenu, array( 'WL_HRML_AdminMenu', 'dashboard_assets' ));

        /* Holidays submenu */
        $holiday_submenu = add_submenu_page('hr-management-lite', __('Holidays', 'hr-management-lite'), __('Holidays', 'hr-management-lite'), 'manage_options', 'hr-management-lite-holidays', array(
            'WL_HRML_AdminMenu',
            'holidays'
        ));
        add_action('admin_print_styles-' . $holiday_submenu, array( 'WL_HRML_AdminMenu', 'holiday_assets' ));

        /* Projects submenu */
        $holiday_submenu = add_submenu_page('hr-management-lite', __('Projects', 'hr-management-lite'), __('Projects', 'hr-management-lite'), 'manage_options', 'hr-management-lite-projects', array(
            'WL_HRML_AdminMenu',
            'projects'
        ));
        add_action('admin_print_styles-' . $holiday_submenu, array( 'WL_HRML_AdminMenu', 'project_assets' ));

        /* Notifications submenu */
        $notification_submenu = add_submenu_page('hr-management-lite', __('Notifications', 'hr-management-lite'), __('Notifications', 'hr-management-lite'), 'manage_options', 'hr-management-lite-notifications', array(
            'WL_HRML_AdminMenu',
            'notifications'
        ));
        add_action('admin_print_styles-' . $notification_submenu, array( 'WL_HRML_AdminMenu', 'notification_assets' ));

        /* Settings submenu */
        $settings_submenu = add_submenu_page('hr-management-lite', __('Settings', 'hr-management-lite'), __('Settings', 'hr-management-lite'), 'manage_options', 'hr-management-lite-settings', array(
            'WL_HRML_AdminMenu',
            'settings'
        ));
        add_action('admin_print_styles-' . $settings_submenu, array( 'WL_HRML_AdminMenu', 'event_assets' ));

        /* Go Pro submenu */
        $go_pro = __('Update to Pro', 'hr-management-lite').' '.wp_kses_post('<i class="fas fa-star"></i>');
        $settings_submenu = add_submenu_page('hr-management-lite', $go_pro, $go_pro, 'manage_options', 'hr-management-lite-go_rpo', array(
            'WL_HRML_AdminMenu',
            'go_rpo'
        ));
        add_action('admin_print_styles-' . $settings_submenu, array( 'WL_HRML_AdminMenu', 'dashboard_assets' ));

        /* Go Pro submenu */
        $help = __('Help', 'hr-management-lite').' '.wp_kses_post('<i class="fas fa-question-circle"></i>');
        $settings_submenu = add_submenu_page('hr-management-lite', $help, $help, 'manage_options', 'hr-management-lite-help', array(
            'WL_HRML_AdminMenu',
            'help_support'
        ));
        add_action('admin_print_styles-' . $settings_submenu, array( 'WL_HRML_AdminMenu', 'dashboard_assets' ));

        /***----------------------------------------------------------Menus for subscriber----------------------------------------------------------***/

        /* Dashboard submenu */
        $save_settings  = get_option('ehrm_settings_data');
        if (! empty($save_settings['user_roles'])) {
            $user_roles = unserialize($save_settings['user_roles']);
        } else {
            $user_roles = array('subscriber');
        }

        $role = HRMLiteHelperClass::ehrm_get_current_user_roles();

        if (is_array($user_roles)) {
            if (in_array($role, $user_roles) &&  HRMLiteHelperClass::check_user_availability() == true) {

                /** Dashboard**/
                $sub_dash_submenu = add_submenu_page('hr-management-lite', __('Dashboard', 'hr-management-lite'), __('Dashboard', 'hr-management-lite'), $role, 'employee-and-hr-management-staff-dashboard', array(
                    'WL_HRML_AdminMenu',
                    'staff_dashboard'
                ));
                add_action('admin_print_styles-' . $sub_dash_submenu, array( 'WL_HRML_AdminMenu', 'staff_dashboard_assets' ));

                /* Reports submenu */
                $staff_report_submenu = add_submenu_page('hr-management-lite', __('Reports', 'hr-management-lite'), __('Reports', 'hr-management-lite'), $role, 'employee-and-hr-management-staff-reports', array(
                    'WL_HRML_AdminMenu',
                    'staff_reports'
                ));
                add_action('admin_print_styles-' . $staff_report_submenu, array( 'WL_HRML_AdminMenu', 'report_assets' ));

                /* Leave Requests submenu */
                $staff_requests_submenu = add_submenu_page('hr-management-lite', __('Leaves', 'hr-management-lite'), __('Leaves', 'hr-management-lite'), $role, 'employee-and-hr-management-staff-requests', array(
                    'WL_HRML_AdminMenu',
                    'staff_requests'
                ));
                add_action('admin_print_styles-' . $staff_requests_submenu, array( 'WL_HRML_AdminMenu', 'staff_requests_assets' ));

                if (! empty($save_settings['show_holiday']) && $save_settings['show_holiday'] == 'Yes') {
                    /* Holidays submenu */
                    $staff_holidays_submenu = add_submenu_page('hr-management-lite', __('Holidays', 'hr-management-lite'), __('Holidays', 'hr-management-lite'), $role, 'employee-and-hr-management-staff-holidays', array(
                        'WL_HRML_AdminMenu',
                        'staff_holidays'
                    ));
                    add_action('admin_print_styles-' . $staff_holidays_submenu, array( 'WL_HRML_AdminMenu', 'holiday_assets' ));
                }

                if (! empty($save_settings['show_notice']) && $save_settings['show_notice'] == 'Yes') {
                    /* Notice submenu */
                    $staff_notice_submenu = add_submenu_page('hr-management-lite', __('Notice', 'hr-management-lite'), __('Notice', 'hr-management-lite'), $role, 'employee-and-hr-management-staff-notice', array(
                        'WL_HRML_AdminMenu',
                        'staff_notice'
                    ));
                    add_action('admin_print_styles-' . $staff_notice_submenu, array( 'WL_HRML_AdminMenu', 'dashboard_assets' ));
                }

                if (! empty($save_settings['show_projects']) && $save_settings['show_projects'] == 'Yes') {
                    /* Projects submenu */
                    $staff_project_submenu = add_submenu_page('hr-management-lite', __('Projects', 'hr-management-lite'), __('Projects', 'hr-management-lite'), $role, 'employee-and-hr-management-staff-project', array(
                        'WL_HRML_AdminMenu',
                        'staff_project'
                    ));
                    add_action('admin_print_styles-' . $staff_project_submenu, array( 'WL_HRML_AdminMenu', 'project_assets' ));
                }
            }
        }
    }

    /* Dashboard menu/submenu callback */
    public static function dashboard()
    {
        require_once('inc/wl_hrm-lite_dashboard.php');
    }

    /* Designation menu/submenu callback */
    public static function designation()
    {
        require_once('inc/administrator/wl_hrm-lite_designation.php');
    }

    /* Requests menu/submenu callback */
    public static function requests()
    {
        require_once('inc/administrator/wl_hrm-lite_requests.php');
    }

    /* Shift menu/submenu callback */
    public static function shift()
    {
        require_once('inc/administrator/wl_hrm-lite_shift.php');
    }

    /* Staff menu/submenu callback */
    public static function staff()
    {
        require_once('inc/administrator/wl_hrm-lite_staff.php');
    }

    /* Reports menu/submenu callback */
    public static function reports()
    {
        require_once('inc/administrator/wl_hrm-lite_reports.php');
    }

    /* Events menu/submenu callback */
    public static function events()
    {
        require_once('inc/administrator/wl_hrm-lite_event.php');
    }

    /* Notices menu/submenu callback */
    public static function notices()
    {
        require_once('inc/administrator/wl_hrm-lite_notice.php');
    }

    /* Holidays menu/submenu callback */
    public static function holidays()
    {
        require_once('inc/administrator/wl_hrm-lite_holiday.php');
    }

    /* Projects menu/submenu callback */
    public static function projects()
    {
        require_once('inc/administrator/wl_hrm-lite_project.php');
    }

    /* Notifications menu/submenu callback */
    public static function notifications()
    {
        require_once('inc/administrator/wl_hrm-lite_notification.php');
    }

    /* Settings menu/submenu callback */
    public static function settings()
    {
        require_once('inc/wl_hrm-lite_settings.php');
    }

    /* Pro Banner menu/submenu callback */
    public static function go_rpo()
    {
        require_once('wl_hrm-lite_banner.php');
    }

    /* Help & Support */
    public static function help_support()
    {
        require_once('wl_hrm-lite_help.php');
    }

    /* Staff's dashboard */
    public static function staff_dashboard()
    {
        require_once('inc/subscriber/wl_hrm_lite_staff_dash.php');
    }

    /* Staff's reports */
    public static function staff_reports()
    {
        require_once('inc/subscriber/wl_hrm_lite_staff_report.php');
    }

    /* Staff's requests */
    public static function staff_requests()
    {
        require_once('inc/subscriber/wl_hrm_lite_staff_requests.php');
    }

    /* Staff's Holidays */
    public static function staff_holidays()
    {
        require_once('inc/subscriber/wl_hrm_lite_staff_holidays.php');
    }

    /* Staff's Notice */
    public static function staff_notice()
    {
        require_once('inc/subscriber/wl_hrm_lite_staff_notices.php');
    }

    /* Staff's Notice */
    public static function staff_project()
    {
        require_once('inc/subscriber/wl_hrm_lite_staff_projects.php');
    }

    /* Dashboard menu/submenu assets */
    public static function dashboard_assets()
    {
        self::enqueue_libraries();
        self::enqueue_datatable_assets();
        self::enqueue_custom_assets();
    }

    /* Event menu/submenu assets */
    public static function event_assets()
    {
        self::enqueue_libraries();
        self::enqueue_datatable_assets();
        self::enqueue_datetimepicker();
        self::enqueue_custom_assets();
    }

    /* Holiday menu/submenu assets */
    public static function holiday_assets()
    {
        self::enqueue_libraries();
        self::enqueue_datatable_assets();
        self::enqueue_datetimepicker();
        self::enqueue_holiday_assets();
        self::enqueue_custom_assets();
    }

    /* Staff's dashboard assets */
    public static function staff_dashboard_assets()
    {
        self::enqueue_libraries();
        self::enqueue_datatable_assets();
        self::enqueue_custom_assets();
        self::staffs_dashboard();
    }

    /* Staff's Requests assets */
    public static function staff_requests_assets()
    {
        self::enqueue_libraries();
        self::enqueue_datatable_assets();
        self::enqueue_daterangepicker();
        self::enqueue_custom_assets();
    }

    /* Staff's dashboard assets */
    public static function report_assets()
    {
        self::enqueue_libraries();
        self::enqueue_datatable_assets();
        self::enqueue_datetimepicker();
        self::enqueue_custom_assets();
        self::reports_dashboard();
    }

    /* Projects menu/submenu assets */
    public static function project_assets()
    {
        self::enqueue_libraries();
        self::enqueue_datatable_assets();
        self::enqueue_datetimepicker();
        self::enqueue_custom_assets();
        self::enqueue_project_assets();
    }

    /* Notifications menu/submenu assets */
    public static function notification_assets()
    {
        self::enqueue_libraries();
        self::enqueue_datatable_assets();
        self::enqueue_custom_assets();
        self::enqueue_notification_assets();
    }

    public static function enqueue_datatable_assets()
    {
        wp_enqueue_style('jquery-dataTables', WL_HRML_PLUGIN_URL . '/assets/css/jquery.dataTables.min.css');
        wp_enqueue_style('dataTables-bootstrap4', WL_HRML_PLUGIN_URL . '/assets/css/dataTables.bootstrap4.min.css');
        wp_enqueue_script('jquery-datatable-js', WL_HRML_PLUGIN_URL . '/assets/js/jquery.dataTables.min.js', array( 'jquery' ), true, true);
        wp_enqueue_script('datatable-bootstrap4-js', WL_HRML_PLUGIN_URL . '/assets/js/dataTables.bootstrap4.min.js', array( 'jquery' ), true, true);
    }

    /* Enqueue third party libraties */
    public static function enqueue_libraries()
    {

        /* Enqueue styles */
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_style('wl-hrm-lite-dashboard', WL_HRML_PLUGIN_URL . 'assets/css/dashboard-style.css');
        wp_enqueue_style('toastr', WL_HRML_PLUGIN_URL . 'assets/css/toastr.min.css');
        wp_enqueue_style('jquery-confirm', WL_HRML_PLUGIN_URL . 'admin/css/jquery-confirm.min.css');
        wp_enqueue_style('bootstrap-multiselect', WL_HRML_PLUGIN_URL . 'assets/css/bootstrap-multiselect.css');
        wp_enqueue_style('wl-hrm-lite-banner', WL_HRML_PLUGIN_URL . 'admin/css/wl-hrm-lite-banner.css');

        /* Enqueue Scripts */
        wp_enqueue_script('jquery');
        wp_enqueue_script('moment');
        wp_enqueue_media();
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script('jquery-form');
        wp_enqueue_script('popper-js', WL_HRML_PLUGIN_URL . 'assets/js/popper.min.js', array( 'jquery' ), true, true);
        wp_enqueue_script('bootstrap-js', WL_HRML_PLUGIN_URL . 'assets/js/bootstrap.min.js', array( 'jquery' ), true, true);
        wp_enqueue_script('toastr-js', WL_HRML_PLUGIN_URL . 'assets/js/toastr.min.js', array( 'jquery' ), true, true);
        wp_enqueue_script('jquery-confirm-js', WL_HRML_PLUGIN_URL . 'admin/js/jquery-confirm.min.js', array( 'jquery' ), true, true);
        wp_enqueue_script('bootstrap-multiselect-js', WL_HRML_PLUGIN_URL . 'assets/js/bootstrap-multiselect.js', array( 'jquery' ), true, true);
    }

    public static function staffs_dashboard()
    {

        /* Staff dash board ajax js */
        wp_enqueue_script('wl-hrm-lite-staff-ajax-js', WL_HRML_PLUGIN_URL . 'admin/js/wl-hrm-lite-staff-ajax.js', array( 'jquery' ), true, true);
        wp_localize_script('wl-hrm-lite-staff-ajax-js', 'ajax_staff', array(
            'ajax_url'      => admin_url('admin-ajax.php'),
            'staff_nonce'   => wp_create_nonce('staff_ajax_nonce'),
            'ehrm_timezone' => HRMLiteHelperClass::get_setting_timezone(),
        ));
    }

    public static function reports_dashboard()
    {

        /* Staff dash board ajax js */
        wp_enqueue_script('wl-hrm-lite-report-ajax-js', WL_HRML_PLUGIN_URL . 'admin/js/wl-hrm-lite-report-ajax.js', array( 'jquery' ), true, true);
        wp_localize_script('wl-hrm-lite-report-ajax-js', 'ajax_report', array(
            'ajax_url'      => admin_url('admin-ajax.php'),
            'report_nonce'  => wp_create_nonce('report_ajax_nonce'),
        ));
    }

    /** Libraries for DateRangePicker **/
    public static function enqueue_daterangepicker()
    {
        wp_enqueue_style('daterangepicker', WL_HRML_PLUGIN_URL . 'assets/css/daterangepicker.css');
		wp_enqueue_script('daterangepicker-js', WL_HRML_PLUGIN_URL . 'assets/js/daterangepicker.min.js', array('jquery'), true, true);
        wp_enqueue_script('wl-hrm-lite-holiday-js', WL_HRML_PLUGIN_URL . 'admin/js/wl-hrm-lite-holiday.js', array( 'jquery' ), true, true); 
    }

    public static function enqueue_datetimepicker()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_style('datetimepicker', WL_HRML_PLUGIN_URL . 'assets/css/tempusdominus-bootstrap-4.min.css');
        wp_enqueue_style('bootstrap-timepicker', WL_HRML_PLUGIN_URL . 'assets/css/bootstrap-timepicker.css');
        wp_enqueue_style('font-awesome', WL_HRML_PLUGIN_URL . 'assets/css/font-awesome.min.css');

        wp_enqueue_script('datetimepicker-js', WL_HRML_PLUGIN_URL . 'assets/js/tempusdominus-bootstrap-4.min.js', array('jquery'), true, true);
        wp_enqueue_script('bootstrap-timepicker-js', WL_HRML_PLUGIN_URL . 'assets/js/bootstrap-timepicker.js', array('jquery'), true, true);
        wp_enqueue_script('wl-hrm-lite-event-js', WL_HRML_PLUGIN_URL . 'admin/js/wl-hrm-lite-event.js', array( 'jquery' ), true, true);
    }

    public static function enqueue_project_assets()
    {
        wp_enqueue_media();

        /** For Multi tags field **/
        wp_enqueue_style('bootstrap-tokenfield', WL_HRML_PLUGIN_URL . '/assets/css/bootstrap-tokenfield.min.css');
        wp_enqueue_script('bootstrap-tokenfiled-js', WL_HRML_PLUGIN_URL . '/assets/js/bootstrap-tokenfield.min.js', array( 'jquery' ), true, true);

        /* Project ajax js */
        wp_enqueue_script('wl-hrm-project-ajax-js', WL_HRML_PLUGIN_URL . 'admin/js/wl-hrm-lite-project-ajax.js', array( 'jquery' ), true, true);
        wp_localize_script('wl-hrm-project-ajax-js', 'ajax_project', array(
            'ajax_url'       => admin_url('admin-ajax.php'),
            'project_nonce'  => wp_create_nonce('project_ajax_nonce'),
        ));
    }

    public static function enqueue_holiday_assets()
    {
        /* Enqueue scripts */
        wp_enqueue_script('wl-hrm-holiday-js', WL_HRML_PLUGIN_URL . 'admin/js/wl-hrm-lite-holiday.js', array( 'jquery' ), true, true);
    }

    public static function enqueue_notification_assets()
    {
        /* Enqueue scripts */
        wp_enqueue_script('wl-hrm-notification-js', WL_HRML_PLUGIN_URL . 'admin/js/wl-hrm-lite-notification.js', array( 'jquery' ), true, true);
        wp_localize_script('wl-hrm-notification-js', 'ajax_notification', array(
            'ajax_url'           => admin_url('admin-ajax.php'),
            'notification_nonce' => wp_create_nonce('notification_ajax_nonce'),
        ));
    }

    /* Enqueue custom assets */
    public static function enqueue_custom_assets()
    {

        /* Enqueue styles */
        wp_enqueue_style('wl-hrm-lite-style', WL_HRML_PLUGIN_URL . 'admin/css/wl-hrm-lite-backend-style.css');
        wp_enqueue_style('font-awesome', WL_HRML_PLUGIN_URL . 'assets/css/font-awesome.min.css');

        /* Enqueue scripts */
        wp_enqueue_script('wl-hrm-lite-settings-js', WL_HRML_PLUGIN_URL . 'admin/js/wl-hrm-lite-settings.js', array( 'jquery' ), true, true);
        wp_enqueue_script('wl-hrm-lite-backend-js', WL_HRML_PLUGIN_URL . 'admin/js/wl-hrm-lite-backend.js', array( 'jquery', 'wp-color-picker' ), true, true);
        wp_enqueue_script('wl-hrm-lite-ajax-js', WL_HRML_PLUGIN_URL . 'admin/js/wl-hrm-lite-ajax.js', array( 'jquery' ), true, true);
        wp_localize_script('wl-hrm-lite-ajax-js', 'ajax_backend', array(
            'ajax_url'      => admin_url('admin-ajax.php'),
            'backend_nonce' => wp_create_nonce('backend_ajax_nonce'),
        ));

        $role = HRMLiteHelperClass::ehrm_get_current_user_roles();
        if (is_admin() && $role == 'administrator') {
            /** Staff Login/Logout action from Admin Dashboard **/
            wp_enqueue_script('wl-hrm-admin-ajax-js', WL_HRML_PLUGIN_URL . 'admin/js/wl-hrm-lite-admin-dashboard.js', array( 'jquery' ), true, true);
            wp_localize_script('wl-hrm-admin-ajax-js', 'ajax_admin', array(
                'ajax_url'      => admin_url('admin-ajax.php'),
                'admin_nonce'   => wp_create_nonce('admin_ajax_nonce'),
            ));
        }
    }
}
