<?php
defined( 'ABSPATH' ) or die();

class HRMLiteLanguage {
	public static function load_translation() {
		load_plugin_textdomain( 'hr-management-lite', false, basename( WL_HRML_PLUGIN_DIR_PATH ) . '/lang' );
	}
}