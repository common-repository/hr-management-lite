<?php
/*
Plugin Name: HR Management Lite
Plugin URI:  https://wordpress.org/plugins/hr-management-lite/
Description:HR Management is the wordPress Plugin for hrm, crm, erp and also manage the Projects,  Departments, Employees Attendance, Salary, Real Time Working Hours, Monthly Report Generation, Leaves, Notices, Holidays. HR Management is a HRM plugin for WordPress sites. That can manage staff/ employee related activities  in any organization and small type of business, corporation, companies. You can create unlimited staff, designations, shifts, events, holidays, notification, projects and  much more  option are available.
Author: weblizar
Author URI: https://weblizar.com/
Version: 3.1
Text Domain: hr-management-lite
Domain Path: /lang/
*/

defined('ABSPATH') or die();

if (! defined('WL_HRML_PLUGIN_URL')) {
    define('WL_HRML_PLUGIN_URL', plugin_dir_url(__FILE__));
}

if (! defined('WL_HRML_PLUGIN_DIR_PATH')) {
    define('WL_HRML_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
}

if (! defined('WL_HRML_PLUGIN_BASENAME')) {
    define('WL_HRML_PLUGIN_BASENAME', plugin_basename(__FILE__));
}

if (! defined('WL_HRML_PLUGIN_FILE')) {
    define('WL_HRML_PLUGIN_FILE', __FILE__);
}

final class HRManagementLite
{
    private static $instance = null;

    private function __construct()
    {
        $this->initialize_hooks();
        $this->setup_init();
    }

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function initialize_hooks()
    {
        if (is_admin()) {
            require_once('admin/admin.php');
            require_once('admin/admin-setup-wizard.php');
        }
        require_once('public/public.php');
    }

    private function setup_init()
    {
        require_once('admin/inc/wl_hrm_lite_default_options.php');

        register_activation_hook(__FILE__, array( 'LiteSetDeafaultOptions', 'hrm_lite_activation_actions' ));
        register_activation_hook(__FILE__, array( 'LiteSetDeafaultOptions', 'hrm_lite_activation_default_emails' ));
        add_action('hrm_lite_extension_activation', array( 'LiteSetDeafaultOptions', 'default_settings' ));
        add_action('init', array( 'LiteSetDeafaultOptions', 'hrm_lite_allow_subscriber_uploads' ));
        add_action('pre_get_posts', array( 'LiteSetDeafaultOptions', 'hrm_lite_users_own_attachments' ));
        add_action('hrm_lite_default_emails_activation', array( 'LiteSetDeafaultOptions', 'hrm_lite_setup_default_emails' ));
        register_activation_hook(__FILE__, array( 'LiteSetDeafaultOptions', 'ehrm_setup_wizard_activation_hook'));
        add_action('admin_init', array( 'LiteSetDeafaultOptions', 'ehrm_setup_wizard_redirect' ));
    }
}

function hrm_lite_staff_login_redirect($url, $request, $user)
{
    $staffs = get_option('ehrm_staffs_data');

    if ($user && is_object($user) && is_a($user, 'WP_User')) {
        if ($user->has_cap('administrator')) {
            $url = admin_url();
        } else {
            if (! empty($staffs)) {
                foreach ($staffs as $key => $staff) {
                    if ($staff['ID'] == get_current_user_id()) {
                        $url = admin_url('/admin.php?page=employee-and-hr-management-staff-dashboard/');
                    } else {
                        $url = admin_url();
                    }
                }
            } else {
                $url = admin_url();
            }
        }
    }
    return $url;
}
add_filter('login_redirect', 'hrm_lite_staff_login_redirect', 10, 3);

HRManagementLite::get_instance();