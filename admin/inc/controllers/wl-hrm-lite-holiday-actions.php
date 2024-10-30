<?php
defined( 'ABSPATH' ) or die();
require_once( WL_HRML_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-hrm-lite-helper.php' );

/**
 *  Ajax Action calls for events menu
 */
class LiteHolidaysAjaxAction {
	
	public static function add_holidays() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( ! empty ( $_POST['name'] ) && ! empty ( $_POST['start'] ) && ! empty ( $_POST['to'] ) && ! empty ( $_POST['status'] ) ) {
			$name     = sanitize_text_field( $_POST['name'] );
			$start    = sanitize_text_field( $_POST['start'] );
			$to       = sanitize_text_field( $_POST['to'] );
			$status   = sanitize_text_field( $_POST['status'] );
			$Holidays = get_option( 'ehrm_holidays_data' );
			$date1    = date_create( $start );
			$date2    = date_create( $to );
			$diff     = date_diff( $date1, $date2 );
			$leaves   = $diff->format( "%a" );	
			$leaves   = $leaves + 1;

			$data = array(
				'name'   => $name,
				'start'  => $start,
				'to'     => $to,
				'days'   => $leaves,
				'status' => $status,
			);

			if ( empty ( $Holidays ) ) {
				$Holidays = array();
			}
			array_push( $Holidays, $data );

			if ( update_option( 'ehrm_holidays_data', $Holidays ) ) {

				$all_holidays = get_option( 'ehrm_holidays_data' );

				if ( ! empty ( $all_holidays ) ) {
            		$sno = 1;
            		foreach ( $all_holidays as $key => $holiday ) {
            	
		                $html = '<tr>
				                	<td>'.esc_html( $sno ).'.</td>
				                  	<td>'.esc_html( $holiday['name'] ).'</td>
				                  	<td class="badge-desc">'.esc_html( "From ".date( HRMLiteHelperClass::get_date_format(), strtotime( $holiday['start'] ) )." to ".date( HRMLiteHelperClass::get_date_format(), strtotime( $holiday['to'] ) ) ).'</td>
				                  	<td>'.esc_html( $holiday['days'] ).'</td>
				                  	<td>'.esc_html( $holiday['status'] ).'</td>
				                  	<td class="designation-action-tools">
		                          		<ul class="designation-action-tools-ul">
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a holiday-edit-a" data-holiday="'.esc_attr( $key ).'">
		                          					<i class="fas fa-pencil-alt"></i>
		                          				</a>
		                          			</li>
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a holiday-delete-a" data-holiday="'.esc_attr( $key ).'">
		                          					<i class="far fa-window-close"></i>
		                          				</a>
		                          			</li>
		                          		</ul>
		                          	</td>
				                </tr>';
		                $sno++; 
		            } 
		        }
				$status  = 'success';
				$message = __( 'Holiday added successfully!', 'hr-management-lite' );
				$content = wp_kses_post( $html );
			} else {
				$status  = 'error';
				$message = __( 'Holiday not added!', 'hr-management-lite' );
				$content = '';
			}

		} else {
			if ( empty ( $name ) ) {
				$message = __( 'Please enter name.!', 'hr-management-lite' );		
			} elseif ( empty ( $start ) ) {
				$message = __( 'Please select starting date.!', 'hr-management-lite' );
			} elseif ( empty ( $to ) ) {
				$message = __( 'Please select ending date.!', 'hr-management-lite' );
			} elseif ( empty ( $status ) ) {
				$message = __( 'Please select status.!', 'hr-management-lite' );
			}

			$status  = 'error';
			$content = '';
		}
		$return = array(
			'status'  => $status,
			'message' => $message,
			'content' => $content
		);

		wp_send_json( $return );
		wp_die();
	}

	/* Edit Holidays Action Call */
	public static function edit_holidays() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( isset ( $_POST['key'] ) ) {
			$key    = sanitize_text_field( $_POST['key'] );
			$events = get_option( 'ehrm_holidays_data' );

			$data = array(
				'name'   => $events[$key]['name'],
				'start'  => $events[$key]['start'],
				'to'     => $events[$key]['to'],
				'days'   => $events[$key]['days'],
				'status' => $events[$key]['status'],
			);
			wp_send_json( $data );

		} else {
			wp_send_json( __( 'Something went wrong.!', 'hr-management-lite' ) );
		}
		wp_die();
	}

	/* Update Events Action Call */
	public static function update_holidays() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( ! empty ( $_POST['name'] ) && ! empty ( $_POST['start'] ) && ! empty ( $_POST['to'] ) && ! empty ( $_POST['status'] ) ) {
			$name     = sanitize_text_field( $_POST['name'] );
			$key      = sanitize_text_field( $_POST['key'] );
			$start    = sanitize_text_field( $_POST['start'] );
			$to       = sanitize_text_field( $_POST['to'] );
			$status   = sanitize_text_field( $_POST['status'] );
			$Holidays = get_option( 'ehrm_holidays_data' );
			$date1    = date_create( $start );
			$date2    = date_create( $to );
			$diff     = date_diff( $date1, $date2 );
			$leaves   = $diff->format( "%a" );	
			$leaves   = $leaves + 1;

			$data = array(
				'name'   => $name,
				'start'  => $start,
				'to'     => $to,
				'days'   => $leaves,
				'status' => $status,
			);

			$Holidays[$key] = $data;

			if ( update_option( 'ehrm_holidays_data', $Holidays ) ) {

				$all_holidays = get_option( 'ehrm_holidays_data' );

				if ( ! empty ( $all_holidays ) ) {
            		$sno = 1;
            		foreach ( $all_holidays as $key => $holiday ) {
            	
		                $html = '<tr>
				                	<td>'.esc_html( $sno ).'.</td>
				                  	<td>'.esc_html( $holiday['name'] ).'</td>
				                  	<td class="badge-desc">'.esc_html( "From ".date( HRMLiteHelperClass::get_date_format(), strtotime( $holiday['start'] ) )." to ".date( HRMLiteHelperClass::get_date_format(), strtotime( $holiday['to'] ) ) ).'</td>
				                  	<td>'.esc_html( $holiday['days'] ).'</td>
				                  	<td>'.esc_html( $holiday['status'] ).'</td>
				                  	<td class="designation-action-tools">
		                          		<ul class="designation-action-tools-ul">
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a holiday-edit-a" data-holiday="'.esc_attr( $key ).'">
		                          					<i class="fas fa-pencil-alt"></i>
		                          				</a>
		                          			</li>
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a holiday-delete-a" data-holiday="'.esc_attr( $key ).'">
		                          					<i class="far fa-window-close"></i>
		                          				</a>
		                          			</li>
		                          		</ul>
		                          	</td>
				                </tr>';
		                $sno++; 
		            } 
		        }
				$status  = 'success';
				$message = __( 'Holiday updated successfully!', 'hr-management-lite' );
				$content = wp_kses_post( $html );
			} else {
				$status  = 'error';
				$message = __( 'Holiday not updated!', 'hr-management-lite' );
				$content = '';
			}

		} else {
			if ( empty ( $name ) ) {
				$message = __( 'Please enter name.!', 'hr-management-lite' );		
			} elseif ( empty ( $status ) ) {
				$message = __( 'Please select starting date.!', 'hr-management-lite' );
			} elseif ( empty ( $to ) ) {
				$message = __( 'Please select ending date.!', 'hr-management-lite' );
			} elseif ( empty ( $status ) ) {
				$message = __( 'Please select status.!', 'hr-management-lite' );
			}

			$status  = 'error';
			$content = '';
		}
		$return = array(
			'status'  => $status,
			'message' => $message,
			'content' => $content
		);

		wp_send_json( $return );
		wp_die();
	}

	/* Delete Events Action Call */
	public static function delete_holidays() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( isset ( $_POST['key'] ) ) {
			$key    = sanitize_text_field( $_POST['key'] );
			$holidays = get_option( 'ehrm_holidays_data' );

			unset($holidays[$key]);

			if ( update_option( 'ehrm_holidays_data', $holidays ) ) {

				$all_holidays = get_option( 'ehrm_holidays_data' );

				if ( ! empty ( $all_holidays ) ) {
            		$sno = 1;
            		foreach ( $all_holidays as $key => $holiday ) {
            	
		                $html = '<tr>
				                	<td>'.esc_html( $sno ).'.</td>
				                  	<td>'.esc_html( $holiday['name'] ).'</td>
				                  	<td class="badge-desc">'.esc_html( "From ".date( HRMLiteHelperClass::get_date_format(), strtotime( $holiday['start'] ) )." to ".date( HRMLiteHelperClass::get_date_format(), strtotime( $holiday['to'] ) ) ).'</td>
				                  	<td>'.esc_html( $holiday['days'] ).'</td>
				                  	<td>'.esc_html( $holiday['status'] ).'</td>
				                  	<td class="designation-action-tools">
		                          		<ul class="designation-action-tools-ul">
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a holiday-edit-a" data-holiday="'.esc_attr( $key ).'">
		                          					<i class="fas fa-pencil-alt"></i>
		                          				</a>
		                          			</li>
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a holiday-delete-a" data-holiday="'.esc_attr( $key ).'">
		                          					<i class="far fa-window-close"></i>
		                          				</a>
		                          			</li>
		                          		</ul>
		                          	</td>
				                </tr>';
		                $sno++; 
		            } 
		        }
				$status  = 'success';
				$message = __( 'Holiday deleted successfully!', 'hr-management-lite' );
				$content = wp_kses_post( $html );
			} else {
				$status  = 'error';
				$message = __( 'Holiday not deleted!', 'hr-management-lite' );
				$content = '';
			}

		} else {
			$status  = 'error';
			$message = __( 'Something went wrong.!', 'hr-management-lite' );
			$content = '';
		}
		$return = array(
			'status'  => $status,
			'message' => $message,
			'content' => $content
		);

		wp_send_json( $return );
		wp_die();
	}

}