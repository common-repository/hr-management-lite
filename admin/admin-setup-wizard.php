<?php
/**
 * Setup Wizard Class
 *
 * Takes new users through some basic steps to setup their store.
 *
 * @package  Employee & HR Management
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;

}

/**
 * hrm_lite__Admin_Setup_Wizard class.
 */
class HRMLite_AdminSetupWizard {


    /**
	 * Current step
	 *
	 * @var string
	 */
    private $step = '';

	/**
	 * Steps for the setup wizard
	 *
	 * @var array
	 */
    private $steps = array();

    /**
	 * Department status
	 *
	 * @var string
	 */
	private $dept_status = 0;

    /**
	 * Hook in tabs.
	 */

	public function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menus' ) );
        add_action( 'admin_init', array( $this, 'setup_wizard' ) );
		add_action( 'hrm_lite__setup_setup_footer', array( $this, 'add_footer_scripts' ) );
		
    }

    public function dashboard_assets() {
		self::enqueue_scripts();
	}
    /**
	 * Add admin menus/screens.
	 */
	public function admin_menus() {
		add_dashboard_page( '', '', 'manage_options', 'hrm-lite-setup-wizard', '' );
	}

	/**
	 * Add footer scripts to OBW via woocommerce_setup_footer
	 */
	public function add_footer_scripts() {
		wp_print_scripts();
    }

    /**
	 * Register/enqueue scripts and styles for the Setup Wizard.
	 *
	 * Hooked onto 'admin_enqueue_scripts'.
	 */
	public static function enqueue_scripts() {
		

			if (isset($_GET['page']) && $_GET['page'] == 'hrm-lite-setup-wizard'  or  isset($_GET['page']) && $_GET['page'] == 'hr-management-lite-settings') {

			
			
			/** Call  enqueue */
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_style('bootstrap', WL_HRML_PLUGIN_URL . 'public/css/bootstrap.min.css');
			wp_enqueue_style('bootstrap-timepicker', WL_HRML_PLUGIN_URL . 'assets/css/bootstrap-timepicker.css');
			wp_enqueue_style('font-awesome', WL_HRML_PLUGIN_URL . 'assets/css/font-awesome.min.css');
			wp_enqueue_style('hrm-lite-setup-css', WL_HRML_PLUGIN_URL . '/admin/css/admin-setup-wizard.css');

			/* Add the color picker css file */
			wp_enqueue_script('jquery');
			wp_enqueue_script('wp-color-picker');
			wp_enqueue_script('moment');
			wp_enqueue_script('popper-js', WL_HRML_PLUGIN_URL . 'assets/js/popper.min.js', array(''), true, true);
			wp_enqueue_script('bootstrap-js', WL_HRML_PLUGIN_URL . 'assets/js/bootstrap.min.js', array(''), true, true);
			wp_enqueue_script('bootstrap-timepicker-js', WL_HRML_PLUGIN_URL . 'assets/js/bootstrap-timepicker.js', array('jquery'), true, true);
			wp_enqueue_script('hrm-lite-setup-js', WL_HRML_PLUGIN_URL . '/admin/js/admin-setup.js', array('jquery'), true, true);
		}
		
    }

     /**
	 * Show the setup wizard.
	 */
	public function setup_wizard() {
		self::enqueue_scripts();
		if ( empty( $_GET['page'] ) || 'hrm-lite-setup-wizard' !== $_GET['page'] ) {

			return;
		}
		$default_steps = array(
			'shifts' => array(
				'name'    => __( 'Create Shift', 'hr-management-lite' ),
				'view'    => array( $this, 'hrm_lite__setup_shift_setup' ),
				'handler' => array( $this, 'hrm_lite__setup_shift_setup_save' ),
            ),
			'designation'     => array(
				'name'    => __( 'Create Designation', 'hr-management-lite' ),
				'view'    => array( $this, 'hrm_lite__setup_desig' ),
				'handler' => '',
			),
			'settings'    => array(
				'name'    => __( 'Configure Settings', 'hr-management-lite' ),
				'view'    => array( $this, 'hrm_lite__setup_settings' ),
				'handler' => array( $this, 'hrm_lite__setup_settings_save' ),
			),
			'next_steps'  => array(
				'name'    => __( 'Ready!', 'hr-management-lite' ),
				'view'    => array( $this, 'hrm_lite__setup_ready' ),
				'handler' => '',
			),
		);


		$this->steps = apply_filters( 'hrm_lite__setup_wizard_steps', $default_steps );
		$this->step  = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : current( array_keys( $this->steps ) );
		// @codingStandardsIgnoreStart
		if ( ! empty( $_POST['save_step'] ) && isset( $this->steps[ $this->step ]['handler'] ) ) {
			call_user_func( $this->steps[ $this->step ]['handler'], $this );
		}
		// @codingStandardsIgnoreEnd

		ob_start();
		$this->setup_wizard_header();
		$this->setup_wizard_steps();
		$this->setup_wizard_content();
		$this->setup_wizard_footer();
		exit;
    }

    /** Next step function **/
	public function get_next_step_link( $step = '' ) {
		if ( ! $step ) {
			$step = $this->step;
		}

		$keys = array_keys( $this->steps );
		if ( end( $keys ) === $step ) {
			return admin_url();
		}

		$step_index = array_search( $step, $keys, true );
		if ( false === $step_index ) {
			return '';
		}

		return add_query_arg( 'step', $keys[ $step_index + 1 ], remove_query_arg( 'activate_error' ) );
	}

    /**
	 * Setup Wizard Header.
	 */
	public function setup_wizard_header() {
		set_current_screen();
		?>
		<!DOCTYPE html>
		<html <?php language_attributes(); ?>>
		<head>
			<meta name="viewport" content="width=device-width" />
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title><?php esc_html_e( 'Employee & HR Management &rsaquo; Setup Wizard', 'hr-management-lite' ); ?></title>
			<?php do_action( 'admin_enqueue_scripts' ); ?>
			<?php wp_print_scripts( 'hrm-lite-setup-wizard' ); ?>
			<?php do_action( 'admin_print_styles' ); ?>
			<?php do_action( 'admin_head' ); ?>
		</head>
		<body class="hrm-lite-setup-wizard wp-core-ui wl_custom wl_ehrm">
            <div class="main-panel">
  	            <div class="content-wrapper container" style="position: relative">
                    <div class="logo">
                        <img style="width: 55%;height: auto;margin-bottom: 2%;" src="<?php echo esc_url(WL_HRML_PLUGIN_URL. '/assets/images/logo.png'); ?>" alt="<?php esc_html_e('logo', 'hr-management-lite'); ?>">
                    </div>
		<?php
    }

    /**
	 * Output the steps.
	 */
	public function setup_wizard_steps() {
		$output_steps = $this->steps;
		?>
		<ol class="hrm-lite-setup-steps">
			<?php
			foreach ( $output_steps as $step_key => $step ) {
				$is_completed = array_search( $this->step, array_keys( $this->steps ), true ) > array_search( $step_key, array_keys( $this->steps ), true );

				if ( $step_key === $this->step ) {
					?>
					<li class="active"><?php echo esc_html( $step['name'] ); ?></li>
					<?php
				} elseif ( $is_completed ) {
					?>
					<li class="done">
						<a href="<?php echo esc_url( add_query_arg( 'step', $step_key, remove_query_arg( 'activate_error' ) ) ); ?>"><?php echo esc_html( $step['name'] ); ?></a>
					</li>
					<?php
				} else {
					?>
					<li><?php echo esc_html( $step['name'] ); ?></li>
					<?php
				}
			}
			?>
		</ol>
		<?php
    }

    /**
	 * Setup Wizard Footer.
	 */
	public function setup_wizard_footer() {
		?>
			<a class="hrm-lite-setup-footer-links" href="<?php echo esc_url( admin_url() ); ?>"><?php esc_html_e( 'Not right now', 'hr-management-lite' ); ?></a>
            <?php do_action( 'hrm_lite__setup_setup_footer' ); ?>
                    </div>
                </div>
			</body>
		</html>
		<?php
	}

	/**
	 * Output the content for the current step.
	 */
	public function setup_wizard_content() {
		echo '<div class="hrm-lite-setup-content">';
		if ( ! empty( $this->steps[ $this->step ]['view'] ) ) {
			call_user_func( $this->steps[ $this->step ]['view'], $this );
		}
		echo '</div>';
    }

    /** Shift step **/
	public function hrm_lite__setup_shift_setup() {
		?>
		<form method="post" class="shifts-step" aria-hidden="true" autocomplete="off">
			<p class="store-setup"><?php esc_html_e( 'The following wizard will help you to create multiple shift for your employees.', 'hr-management-lite' ); ?></p>
			<hr>
			<div class="form-body">
                <div class="form-group row">
                    <label for="shift_name"><?php esc_html_e( 'Shift Name', 'hr-management-lite' ); ?></label>
                    <input type="text" class="form-control" name="shift_name" id="shift_name" placeholder="<?php esc_html_e( 'Name', 'hr-management-lite' ); ?>">
                </div>
                <div class="form-group row" >
                    <label><?php esc_html_e( 'Starting Time', 'hr-management-lite' ); ?></label>
                    <input type="text" class="form-control datetimepicker-input" placeholder="<?php esc_html_e( 'Format:- 10:00 AM', 'hr-management-lite' ); ?>" id="start_time" name="start_time" data-toggle="datetimepicker" data-target="#start_time"/>
                </div>
                <div class="form-group row" >
                    <label><?php esc_html_e( 'Ending Time', 'hr-management-lite' ); ?></label>
                    <input type="text" id="end_time" name="end_time" placeholder="<?php esc_html_e( 'Format:- 1:39 PM', 'hr-management-lite' ); ?>" class="form-control datetimepicker-input" data-toggle="datetimepicker" data-target="#end_time">
                </div>
                <div class="form-group row" >
                    <label><?php esc_html_e( 'Late Time', 'hr-management-lite' ); ?></label>
                    <input type="text" id="late_time" name="late_time" placeholder="<?php esc_html_e( 'Format:- 10:15 AM', 'hr-management-lite' ); ?>" class="form-control datetimepicker-input" data-toggle="datetimepicker" data-target="#late_time">
                </div>
			</div>
			<hr>
			<p class="hrm-lite-setup-actions step">
				<button type="submit" class="button-primary button button-large button-next" value="<?php echo esc_attr_e( "Next", 'hr-management-lite' ); ?>" name="save_step"><?php esc_html_e( "Next", 'hr-management-lite' ); ?></button>
			</p>
			<?php wp_nonce_field( 'hrm-lite-setup-wizard' ); ?>
		</form>
		<?php
    }

    public function hrm_lite__setup_shift_setup_save() {
        check_admin_referer( 'hrm-lite-setup-wizard' );

        $name   = isset( $_POST['shift_name'] ) ? sanitize_text_field( $_POST['shift_name'] ) : '';
        $start  = isset( $_POST['start_time'] ) ? sanitize_text_field( $_POST['start_time'] ) : '';
        $end    = isset( $_POST['end_time'] ) ? sanitize_text_field( $_POST['end_time'] ) : '';
        $late   = isset( $_POST['late_time'] ) ? sanitize_text_field( $_POST['late_time'] ) : '';
        $shifts = get_option( 'ehrm_shifts_data' );
        $data   = array(
            'name'   => $name,
            'start'  => $start,
            'end'    => $end,
            'late'   => $late,
            'status' => 'Active',
        );

        if ( empty ( $shifts ) ) {
            $shifts = array();
        }
        array_push( $shifts, $data );
        update_option( 'ehrm_shifts_data', $shifts );       
       	wp_safe_redirect( esc_url_raw( $this->get_next_step_link() ) );
        //echo "<pre>"; print_r($shifts);
        exit;
    }

	/** Designation step **/
	public function hrm_lite__setup_desig() {

        if ( isset( $_POST['save_desig_step'] ) ) {
            $name   = isset( $_POST['designation_name'] ) ? sanitize_text_field( $_POST['designation_name'] ) : '';
            $color  = isset( $_POST['designation_color'] ) ? sanitize_text_field( $_POST['designation_color'] ) : '';
            $design = get_option( 'ehrm_designations_data' );
            $data   = array(
                'name'      => $name,
                'color'     => $color,
                'status'    => 'Active',
            );

            if ( empty ( $design ) ) {
                $design = array();
            }
            array_push( $design, $data );

            if ( update_option( 'ehrm_designations_data', $design ) ) {
				$this->dept_status++;
			}
        }
		?>
		<form method="post" class="designation-step" autocomplete="off">
			<p class="store-setup"><?php esc_html_e( 'The following wizard will help you to create multiple Designations for you employees.', 'hr-management-lite' ); ?></p>
			<hr>
			<div class="form-group row">
				<label for="designation_name"><?php esc_html_e( 'Designation Name', 'hr-management-lite' ); ?></label>
				<input type="text" class="form-control" name="designation_name" id="designation_name" placeholder="<?php esc_html_e( 'Designation Type', 'hr-management-lite' ); ?>">
			</div>
			<hr>
			<div class="row">
				<div class="col-lg-12 col-md-12">
					<div class="form-group row">
						<label for="designation_color" class="col-sm-12 col-form-label"><?php esc_html_e( 'Designation Color', 'hr-management-lite' ); ?></label>
						<div class="col-sm-11">
							<input type="text" class="form-control color-field" name="designation_color" id="designation_color" placeholder="<?php echo sanitize_hex_color('#ffffff'); ?>">
						</div>
					</div>
				</div>
			</div>
			<hr>
			<p class="hrm-lite-setup-actions step">
                <?php if ( $this->dept_status != 0 ) { ?>
                    <button type="submit" class="btn btn-gradient-primary"  name="save_desig_step"><?php esc_html_e( "Add more !", 'hr-management-lite' ); ?></button>
                    <a href="<?php echo esc_url($this->get_next_step_link()); ?>" class="btn button-primary"  name=""><?php esc_html_e( "Next", 'hr-management-lite' ); ?></a>
                <?php } else { ?>
                    <button type="submit" class="btn btn-gradient-primary"  name="save_desig_step"><?php esc_html_e( "Create !", 'hr-management-lite' ); ?></button>
                <?php } ?>
			</p>
			<?php wp_nonce_field( 'hrm-lite-setup-wizard' ); ?>
		</form>
		<?php
    }

	/** Designation step **/
	public function hrm_lite__setup_settings() {
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
        $office_out_sub   = isset( $save_settings['office_out_sub'] ) ? sanitize_text_field( $save_settings['office_out_sub'] ) : __( 'Logout Alert From Employee & HR Management', 'hr-management-lite' );
        $mail_heading     = isset( $save_settings['mail_heading'] ) ? sanitize_text_field( $save_settings['mail_heading'] ) : __( 'Staff Login/Logout Details', 'hr-management-lite' );

		?>
		<form method="post" class="settings-step" autocomplete="off">
			<p class="store-setup"><?php esc_html_e( 'General settings', 'hr-management-lite' ); ?></p>
			<hr>
			<div class="form-group row">
				<label class="col-sm-3 col-form-label"><?php esc_html_e('TimeZone', 'hr-management-lite'); ?></label>
				<div class="col-sm-11">
					<select class="form-control" id="timezone" name="timezone">
						<option value=""><?php esc_html_e('----------------------------------------------------------Select timezone----------------------------------------------------------', 'hr-management-lite'); ?></option>
					<?php foreach ( $timezone_list as $timezone ) { ?>
						<option value="<?php echo esc_attr( $timezone ); ?>" <?php selected( $TimeZone, $timezone ); ?>><?php echo esc_html( $timezone ); ?></option>
					<?php } ?>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-5 col-md-12">
					<div class="form-group row">
						<label class="col-sm-12 col-form-label"><?php esc_html_e('Date Format', 'hr-management-lite'); ?></label>
						<div class="col-sm-11">
							<select class="form-control" id="date_format" name="date_format">
								<option value="<?php echo esc_attr('F j Y'); ?>" <?php selected( $date_format, 'F j Y' ); ?>><?php echo esc_html( date( 'F j Y' ) . ' ( F j Y ) '); ?></option>
								<option value="<?php echo esc_attr('Y-m-d'); ?>" <?php selected( $date_format, 'Y-m-d' ); ?>><?php echo esc_html( date( 'Y-m-d' ) . ' ( YYYY-MM-DD )'); ?></option>
								<option value="<?php echo esc_attr('m/d/Y'); ?>" <?php selected( $date_format, 'm/d/Y' ); ?>><?php echo esc_html( date( 'm/d/Y' ) . ' ( MM/DD/YYYY )'); ?></option>
								<option value="<?php echo esc_attr('d-m-Y'); ?>" <?php selected( $date_format, 'd-m-Y' ); ?>><?php echo esc_html( date( 'd-m-Y' ) . ' ( DD-MM-YYYY )'); ?></option>
								<option value="<?php echo esc_attr('m-d-Y'); ?>" <?php selected( $date_format, 'm-d-Y' ); ?>><?php echo esc_html( date( 'm-d-Y' ) . ' ( MM-DD-YYYY )'); ?></option>
								<option value="<?php echo esc_attr('jS F Y'); ?>" <?php selected( $date_format, 'jS F Y' ); ?>><?php echo esc_html( date( 'jS F Y' ) . ' ( d M YYYY )'); ?></option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-lg-5 col-md-12">
					<div class="form-group row">
						<label class="col-sm-12 col-form-label"><?php esc_html_e('Time Format', 'hr-management-lite'); ?></label>
						<div class="col-sm-11">
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
			<hr>
			<h4 class="card-title week_days"><?php esc_html_e( 'Week days status', 'hr-management-lite'); ?></h4>
              <div class="row">
                <div class="col-lg-3 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Monday', 'hr-management-lite'); ?></label>
                    <div class="col-sm-8">
                      <select class="form-control" id="monday_status" name="monday_status">
                        <option value="<?php echo esc_attr('Working'); ?>" <?php selected( $monday_status, 'Working' ); ?>><?php esc_html_e( 'Working', 'hr-management-lite' ); ?></option>
                        <option value="<?php echo esc_attr('Half Day'); ?>" <?php selected( $monday_status, 'Half Day' ); ?>><?php esc_html_e( 'Half Day', 'hr-management-lite' ); ?></option>
                        <option value="<?php echo esc_attr('Off'); ?>" <?php selected( $monday_status, 'Off' ); ?>><?php esc_html_e( 'Off', 'hr-management-lite' ); ?></option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label"><?php esc_html_e( 'Tuesday', 'hr-management-lite'); ?></label>
                    <div class="col-sm-8">
                      <select class="form-control" id="tuesday_status" name="tuesday_status">
                        <option value="<?php echo esc_attr('Working'); ?>" <?php selected( $tuesday_status, 'Working' ); ?>><?php esc_html_e( 'Working', 'hr-management-lite' ); ?></option>
                        <option value="<?php echo esc_attr('Half Day'); ?>" <?php selected( $tuesday_status, 'Half Day' ); ?>><?php esc_html_e( 'Half Day', 'hr-management-lite' ); ?></option>
                        <option value="<?php echo esc_attr('Off'); ?>" <?php selected( $tuesday_status, 'Off' ); ?>><?php esc_html_e( 'Off', 'hr-management-lite' ); ?></option>
                      </select>
                    </div>
                  </div>
                </div>
				<div class="col-lg-3 col-md-12">
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
			    <div class="col-lg-3 col-md-12">
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
			    <div class="col-lg-3 col-md-12">
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
			   	<div class="col-lg-3 col-md-12">
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
				<div class="col-lg-3 col-md-12">
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
			<hr>
			<h4 class="card-title week_days"><?php esc_html_e( 'Half Day Timing', 'hr-management-lite'); ?></h4>
			<div class="row">
				<div class="col-lg-5 col-md-12">
					<div class="form-group row">
						<label class="col-sm-12 col-form-label"><?php esc_html_e( 'Halfday Start Time', 'hr-management-lite' ); ?></label>
						<div class="col-sm-9">
							<input type="text" name="halfday_start" id="halfday_start" class="form-control" placeholder="<?php esc_html_e( 'Format:- 10:00 AM', 'hr-management-lite' ); ?>" data-toggle="datetimepicker" data-target="#halfday_start" value="<?php echo esc_attr( $halfday_start ); ?>">
						</div>
					</div>
				</div>
				<div class="col-lg-5 col-md-12">
					<div class="form-group row">
						<label class="col-sm-12 col-form-label"><?php esc_html_e('Halfday End Time', 'hr-management-lite'); ?></label>
						<div class="col-sm-9">
							<input type="text" name="halfday_end" id="halfday_end" class="form-control" placeholder="<?php esc_html_e('Format:- 03:00 PM', 'hr-management-lite'); ?>" data-toggle="datetimepicker" data-target="#halfday_end" value="<?php echo esc_attr($halfday_end); ?>">
						</div>
					</div>
				</div>
			</div>
			<hr>
			<h4 class="card-title week_days"><?php esc_html_e( 'Lunch Timing', 'hr-management-lite'); ?></h4>
			<div class="row">
				<div class="col-lg-5 col-md-12">
					<div class="form-group row">
						<label class="col-sm-12 col-form-label"><?php esc_html_e('Lunch Start Time', 'hr-management-lite'); ?></label>
						<div class="col-sm-9">
							<input type="text" name="lunch_start" id="lunch_start" class="form-control" placeholder="<?php esc_html_e('Format:- 02:00 PM', 'hr-management-lite'); ?>" data-toggle="datetimepicker" data-target="#lunch_start" value="<?php echo esc_attr($lunch_start); ?>">
						</div>
					</div>
				</div>
				<div class="col-lg-5 col-md-12">
					<div class="form-group row">
						<label class="col-sm-12 col-form-label"><?php esc_html_e('Lunch End Time', 'hr-management-lite'); ?></label>
						<div class="col-sm-9">
							<input type="text" name="lunch_end" id="lunch_end" class="form-control" placeholder="<?php esc_html_e('Format:- 02:30 PM', 'hr-management-lite'); ?>" data-toggle="datetimepicker" data-target="#lunch_end" value="<?php echo esc_attr($lunch_end); ?>">
						</div>
					</div>
				</div>
			</div>
			<hr>
			<h4 class="card-title week_days"><?php esc_html_e( 'Currency Detials', 'hr-management-lite'); ?></h4>
			<div class="row">
			    <div class="col-lg-5 col-md-12">
					<div class="form-group row">
						<label class="col-sm-12 col-form-label"><?php esc_html_e( 'Currency Symbol', 'hr-management-lite' ); ?></label>
						<div class="col-sm-9">
							<input type="text" class="form-control" placeholder="$" id="currency_symbol" name="currency_symbol" value="<?php echo esc_attr( $cur_symbol ); ?>">
						</div>
					</div>
				</div>
				<div class="col-lg-5 col-md-12">
					<div class="form-group row">
						<label class="col-sm-12 col-form-label"><?php esc_html_e( 'Currency Position', 'hr-management-lite' ); ?></label>
						<div class="col-sm-11">
							<select class="form-control" id="currency_position" name="currency_position">
							<option value="<?php echo esc_attr('Right'); ?>" <?php selected( $cur_position, 'Right' ); ?>><?php esc_html_e( 'Right', 'hr-management-lite' ); ?></option>
							<option value="<?php echo esc_attr('Left'); ?>" <?php selected( $cur_position, 'Left' ); ?>><?php esc_html_e( 'Left', 'hr-management-lite' ); ?></option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<h4 class="card-title week_days"><?php esc_html_e( 'Salary Calculation', 'hr-management-lite'); ?></h4>
			<div class="row">
			    <div class="col-lg-12 col-md-12">
					<div class="form-group row">
						<label class="col-sm-12 col-form-label"><?php esc_html_e( 'Salary paid by', 'hr-management-lite' ); ?></label>
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
			</div>
			<hr>
			<div class="row">
			    <div class="col-lg-12 col-md-12">
					<div class="form-group row">
						<label class="col-sm-12 col-form-label"><?php esc_html_e('Include/Exclude Lunch time from Working Hours', 'hr-management-lite'); ?></label>
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
			<hr>
			<h4 class="card-title week_days"><?php esc_html_e( 'Select Roles for employee', 'hr-management-lite'); ?></h4>
			<div class="form-group row">
				<label class="col-sm-3 col-form-label"><?php esc_html_e('Select roles for staff\'s.', 'hr-management-lite'); ?></label>
				<?php if ( ! empty( $save_settings['user_roles'] ) ) {
					$user_roles = unserialize( $save_settings['user_roles'] );
				} else {
					$user_roles = array();
				}
				?>
				<div class="col-sm-3">
					<div class="form-check form-check-success">
					<label class="form-check-label">
						<input type="checkbox" class="form-check-input" <?php if ( is_array( $user_roles ) ) { if ( in_array( 'subscriber', $user_roles ) ) { echo esc_attr('checked'); } } ?> name="user_roles[]" value="<?php echo esc_attr('subscriber'); ?>">
						<?php esc_html_e( 'Subscriber', 'hr-management-lite' ); ?>
						<i class="input-helper"></i></label>
					</div>
					<div class="form-check form-check-success">
					<label class="form-check-label">
						<input type="checkbox" class="form-check-input" name="user_roles[]" value="<?php echo esc_attr('contributor'); ?>" <?php if ( is_array( $user_roles ) ) { if ( in_array( 'contributor', $user_roles ) ) { echo esc_attr('checked'); } } ?>>
						<?php esc_html_e( 'Contributor', 'hr-management-lite' ); ?>
						<i class="input-helper"></i></label>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-check form-check-success">
					<label class="form-check-label">
						<input type="checkbox" class="form-check-input" name="user_roles[]" value="<?php echo esc_attr('author'); ?>" <?php if ( is_array( $user_roles ) ) { if ( in_array( 'author', $user_roles ) ) { echo esc_attr('checked'); } } ?>>
						<?php esc_html_e( 'Author', 'hr-management-lite' ); ?>
						<i class="input-helper"></i></label>
					</div>
					<div class="form-check form-check-success">
					<label class="form-check-label">
						<input type="checkbox" class="form-check-input" name="user_roles[]" value="<?php echo esc_attr('editor'); ?>" <?php if ( is_array( $user_roles ) ) { if ( in_array( 'editor', $user_roles ) ) { echo esc_attr('checked'); } } ?>>
						<?php esc_html_e( 'Editor', 'hr-management-lite' ); ?>
						<i class="input-helper"></i></label>
					</div>
				</div>
				<p class="info-text-hr">
					<span class="option-info-text">
						<i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
						<?php esc_html_e( 'Staff\'s login dashboard shows only for selected user roles.', 'hr-management-lite' ); ?>
					</span>
				</p>
			</div>
			<hr>
			<p class="hrm-lite-setup-actions step">
				<button type="submit" class="button-primary button button-large button-next" value="<?php echo esc_attr_e( "Next", 'hr-management-lite' ); ?>" name="save_step"><?php esc_html_e( "Next", 'hr-management-lite' ); ?></button>
			</p>
			<?php wp_nonce_field( 'hrm-lite-setup-wizard' ); ?>
		</form>
		<?php
	}

	/** setings save step **/
	public function hrm_lite__setup_settings_save() {
		check_admin_referer( 'hrm-lite-setup-wizard' );

		$timezone         = isset( $_POST['timezone'] ) ? sanitize_text_field( $_POST['timezone'] ) : 'Asia/Kolkata';
		$date_format      = isset( $_POST['date_format'] ) ? sanitize_text_field( $_POST['date_format'] ) : 'F j Y';
		$time_format      = isset( $_POST['time_format'] ) ? sanitize_text_field( $_POST['time_format'] ) : 'g:i A';
		$monday_status    = isset( $_POST['monday_status'] ) ? sanitize_text_field( $_POST['monday_status'] ) : 'Working';
		$tuesday_status   = isset( $_POST['tuesday_status'] ) ? sanitize_text_field( $_POST['tuesday_status'] ) : 'Working';
		$wednesday_status = isset( $_POST['wednesday_status'] ) ? sanitize_text_field( $_POST['wednesday_status'] ) : 'Working';
		$thursday_status  = isset( $_POST['thursday_status'] ) ? sanitize_text_field( $_POST['thursday_status'] ) : 'Working';
		$friday_status    = isset( $_POST['friday_status'] ) ? sanitize_text_field( $_POST['friday_status'] ) : 'Working';
		$saturday_status  = isset( $_POST['saturday_status'] ) ? sanitize_text_field( $_POST['saturday_status'] ) : 'Working';
		$sunday_status    = isset( $_POST['sunday_status'] ) ? sanitize_text_field( $_POST['sunday_status'] ) : 'Off';
		$halfday_start    = isset( $_POST['halfday_start'] ) ? sanitize_text_field( $_POST['halfday_start'] ) : '';
		$halfday_end      = isset( $_POST['halfday_end'] ) ? sanitize_text_field( $_POST['halfday_end'] ) : '';
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

		$user_roles = ( isset( $_POST['user_roles'] ) && is_array( $_POST['user_roles'] ) ) ? $_POST['user_roles'] : array('subscriber');
		$user_roles = array_map( 'sanitize_text_field', $user_roles );

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
		wp_safe_redirect( esc_url_raw( $this->get_next_step_link() ) );
		exit;
	}

    /** Final step **/
	public function hrm_lite__setup_ready() {
		?>
		<div class="final-setup text-center">
			<h3 class="main-heading text-center"><?php esc_html_e( 'You re ready to start!', 'hr-management-lite' ); ?></h3>
			<h4 class="sub-heading text-center"><?php esc_html_e( 'All configurations are done..!! Now you just need to add your staff into system', 'hr-management-lite' ); ?></h4>
			<a href="<?php echo admin_url( 'admin.php?page=hr-management-lite-staff' ); ?>" class="btn btn-success final-step_btn"><?php esc_html_e( ' Add staff', 'hr-management-lite' ); ?></a>
		</div>
		<?php
	}
}

new HRMLite_AdminSetupWizard();
