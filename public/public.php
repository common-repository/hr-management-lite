<?php
defined( 'ABSPATH' ) or die();

require_once( 'wl_hrm_lite_language.php' );
require_once( 'wl_hrm_lite_shortcode.php' );
require_once( 'inc/controllers/wl_hrm_lite_login_actions.php' );

/* Load text domain */
add_action( 'plugins_loaded', array( 'HRMLiteLanguage', 'load_translation' ) );

/* Enqueue Assets for shortcodes */
add_action( 'wp_enqueue_scripts', array( 'LiteHRMShortcode', 'shortcode_enqueue_assets' ) );

/* Login Form Shortcode files */
add_shortcode( 'WL_EHRM_LOGIN_FORM', array( 'LiteHRMShortcode', 'login_portal' ) );

/**----------------------------------------------------------------Staff login actions for frontend shortcode----------------------------------------------------------------**/

/* Staff's clock actions */
add_action( 'wp_ajax_nopriv_hrm_front_clock_action', array( 'LiteFrontDashBoardAction', 'clock_actions' ) );
add_action( 'wp_ajax_hrm_front_clock_action', array( 'LiteFrontDashBoardAction', 'clock_actions' ) );

/* Late reson submit actions */
add_action( 'wp_ajax_nopriv_hrm_front_late_reson_action', array( 'LiteFrontDashBoardAction', 'late_reson_submit' ) );
add_action( 'wp_ajax_hrm_front_late_reson_action', array( 'LiteFrontDashBoardAction', 'late_reson_submit' ) );

/* Daily report submit actions */
add_action( 'wp_ajax_nopriv_hrm_front_daily_report_action', array( 'LiteFrontDashBoardAction', 'staff_daily_report' ) );
add_action( 'wp_ajax_hrm_front_daily_report_action', array( 'LiteFrontDashBoardAction', 'staff_daily_report' ) );
