<?php
defined( 'ABSPATH' ) or die();
require_once( WL_HRML_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-hrm-lite-helper.php' );

/**
 *  Ajax Action calls for events menu
 */
class LiteEventsAjaxAction {
	
	public static function add_events() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( ! empty ( $_POST['name'] ) && ! empty ( $_POST['desc'] ) && ! empty ( $_POST['date'] ) && ! empty ( $_POST['time'] ) ) {
			$name   = sanitize_text_field( $_POST['name'] );
			$desc   = sanitize_text_field( $_POST['desc'] );
			$date   = sanitize_text_field( $_POST['date'] );
			$time   = sanitize_text_field( $_POST['time'] );
			$status = sanitize_text_field( $_POST['status'] );
			$Events = get_option( 'ehrm_events_data' );

			$data = array(
				'name'   => $name,
				'desc'   => $desc,
				'date'   => $date,
				'time'   => $time,
				'status' => $status,
			);

			if ( empty ( $Events ) ) {
				$Events = array();
			}
			array_push( $Events, $data );

			if ( update_option( 'ehrm_events_data', $Events ) ) {

				$all_events = get_option( 'ehrm_events_data' );

				if ( ! empty ( $all_events ) ) {
            		$sno = 1;
            		foreach ( $all_events as $key => $event ) {
            	
		                $html = '<tr>
				                	<td>'.esc_html( $sno ).'.</td>
				                  	<td>'.esc_html( $event['name'] ).'</td>
				                  	<td class="badge-desc">'.esc_html( $event['desc'] ).'</td>
				                  	<td>'.esc_attr( date( HRMLiteHelperClass::get_date_format(), strtotime( $event['date'] ) ) ).'</td>
				                  	<td>'.esc_attr( $event['time'] ).'</td>
				                  	<td>'.esc_html( $event['status'] ).'</td>
				                  	<td class="designation-action-tools">
		                          		<ul class="designation-action-tools-ul">
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a event-edit-a" data-event="'.esc_attr( $key ).'">
		                          					<i class="fas fa-pencil-alt"></i>
		                          				</a>
		                          			</li>
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a event-delete-a" data-event="'.esc_attr( $key ).'">
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
				$message = __( 'Event added successfully!', 'hr-management-lite' );
				$content = wp_kses_post( $html );
			} else {
				$status  = 'error';
				$message = __( 'Event not added!', 'hr-management-lite' );
				$content = '';
			}

		} else {

			if ( empty ($name  ) ) {
				$message = __( 'Please enter name.!', 'hr-management-lite' );		
			} elseif ( empty ($desc ) ) {
				$message = __( 'Please enter description.!', 'hr-management-lite' );
			} elseif ( empty ( $data ) ) {
				$message = __( 'Please select date.!', 'hr-management-lite' );
			} elseif ( empty ( $time ) ) {
				$message = __( 'Please select time.!', 'hr-management-lite' );
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

	/* Edit Events Action Call */
	public static function edit_events() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( isset ( $_POST['key'] ) ) {
			$key    = sanitize_text_field( $_POST['key'] );
			$events = get_option( 'ehrm_events_data' );

			$data = array(
				'name'   => $events[$key]['name'],
				'desc'   => $events[$key]['desc'],
				'date'   => $events[$key]['date'],
				'time'   => $events[$key]['time'],
				'status' => $events[$key]['status'],
			);
			wp_send_json( $data );

		} else {
			wp_send_json( __( 'Something went wrong.!', 'hr-management-lite' ) );
		}
		wp_die();
	}

	/* Update Events Action Call */
	public static function update_events() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( ! empty ( $_POST['name'] ) && ! empty ( $_POST['desc'] ) && ! empty ( $_POST['date'] ) && ! empty ( $_POST['time'] ) ) {
			$name   = sanitize_text_field( $_POST['name'] );
			$desc   = sanitize_text_field( $_POST['desc'] );
			$date   = sanitize_text_field( $_POST['date'] );
			$time   = sanitize_text_field( $_POST['time'] );
			$status = sanitize_text_field( $_POST['status'] );
			$key    = sanitize_text_field( $_POST['key'] );
			$events = get_option( 'ehrm_events_data' );

			$data = array(
				'name'   => $name,
				'desc'   => $desc,
				'date'   => $date,
				'time'   => $time,
				'status' => $status,
			);

			$events[$key] = $data;

			if ( update_option( 'ehrm_events_data', $events ) ) {

				$events = get_option( 'ehrm_events_data' );

				if ( ! empty ( $events ) ) {
            		$sno = 1;
            		foreach ( $events as $key => $event ) {
            	
		                $html = '<tr>
				                	<td>'.esc_html( $sno ).'.</td>
				                  	<td>'.esc_html( $event['name'] ).'</td>
				                  	<td class="badge-desc">'.esc_html( $event['desc'] ).'</td>
				                  	<td>'.esc_attr( date( HRMLiteHelperClass::get_date_format(), strtotime( $event['date'] ) ) ).'</td>
				                  	<td>'.esc_attr( $event['time'] ).'</td>
				                  	<td>'.esc_html( $event['status'] ).'</td>
				                  	<td class="designation-action-tools">
		                          		<ul class="designation-action-tools-ul">
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a event-edit-a" data-event="'.esc_attr( $key ).'">
		                          					<i class="fas fa-pencil-alt"></i>
		                          				</a>
		                          			</li>
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a event-delete-a" data-event="'.esc_attr( $key ).'">
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
				$message = __( 'Event updated successfully!', 'hr-management-lite' );
				$content = wp_kses_post( $html );
			} else {
				$status  = 'error';
				$message = __( 'Event not updated!', 'hr-management-lite' );
				$content = '';
			}

		} else {
			if ( empty ( $name ) ) {
				$message = __( 'Please enter name.!', 'hr-management-lite' );		
			} elseif ( empty ( $desc ) ) {
				$message = __( 'Please enter description.!', 'hr-management-lite' );
			} elseif ( empty ( $date ) ) {
				$message = __( 'Please select date.!', 'hr-management-lite' );
			} elseif ( empty ( $time ) ) {
				$message = __( 'Please select time.!', 'hr-management-lite' );
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
	public static function delete_events() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( isset ( $_POST['key'] ) ) {
			$key    = sanitize_text_field( $_POST['key'] );
			$events = get_option( 'ehrm_events_data' );

			unset($events[$key]);

			if ( update_option( 'ehrm_events_data', $events ) ) {

				$all_events = get_option( 'ehrm_events_data' );

				if ( ! empty ( $all_events ) ) {
            		$sno = 1;
            		foreach ( $all_events as $key => $event ) {
            	
		                $html = '<tr>
				                	<td>'.esc_html( $sno ).'.</td>
				                  	<td>'.esc_html( $event['name'] ).'</td>
				                  	<td class="badge-desc">'.esc_html( $event['desc'] ).'</td>
				                  	<td>'.esc_attr( date( HRMLiteHelperClass::get_date_format(), strtotime( $event['date'] ) ) ).'</td>
				                  	<td>'.esc_attr( $event['time'] ).'</td>
				                  	<td>'.esc_html( $event['status'] ).'</td>
				                  	<td class="designation-action-tools">
		                          		<ul class="designation-action-tools-ul">
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a event-edit-a" data-event="'.esc_attr( $key ).'">
		                          					<i class="fas fa-pencil-alt"></i>
		                          				</a>
		                          			</li>
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a event-delete-a" data-event="'.esc_attr( $key ).'">
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
				$message = __( 'Event delete successfully!', 'hr-management-lite' );
				$content = wp_kses_post( $html );
			} else {
				$status  = 'error';
				$message = __( 'Event not deleted!', 'hr-management-lite' );
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

?>