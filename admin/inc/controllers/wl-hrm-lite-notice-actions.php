<?php
defined( 'ABSPATH' ) or die();

/**
 *  Ajax Action calls for notices menu
 */
class LiteNoticeAjaxAction {
	
	public static function add_notices() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( ! empty ( $_POST['name'] ) && ! empty ( $_POST['desc'] ) && ! empty ( $_POST['status'] ) ) {
			$name    = sanitize_text_field( $_POST['name'] );
			$desc    = sanitize_text_field( $_POST['desc'] );
			$status  = sanitize_text_field( $_POST['status'] );
			$notices = get_option( 'ehrm_notices_data' );
			$html    = '';

			$data = array(
				'name'   => $name,
				'desc'   => $desc,
				'status' => $status,
				'date'   => date( 'Y-m-d' ),
			);

			if ( empty ( $notices ) ) {
				$notices = array();
			}
			array_push( $notices, $data );

			if ( update_option( 'ehrm_notices_data', $notices ) ) {

				$all_notices = get_option( 'ehrm_notices_data' );

				if ( ! empty ( $all_notices ) ) {
					$sno = 1;
					$first       = new \DateTime( date( "Y" )."-01-01" );
					$first       = $first->format( "Y-m-d" );
					$plusOneYear = date( "Y" )+1;
					$last        = new \DateTime( $plusOneYear."-12-31" );          
					$last        = $last->format( "Y-m-d" );          
					$all_dates   = HRMLiteHelperClass::ehrm_get_date_range( $first, $last );
            		foreach ( $all_notices as $key => $notice ) {
						if ( in_array( $notice['date'], $all_dates ) ) {
							$html = '<tr>
										<td>'.esc_html( $sno ).'.</td>
										<td>'.esc_html( $notice['name'] ).'</td>
										<td>'.esc_html( date( HRMLiteHelperClass::get_date_format(), strtotime( $notice['date'] ) ) ).'</td>
										<td class="badge-desc">'.esc_html( $notice['desc'] ).'</td>
										<td>'.esc_html( $notice['status'] ).'</td>
										<td class="designation-action-tools">
											<ul class="designation-action-tools-ul">
												<li class="designation-action-tools-li">
													<a href="#" class="designation-action-tools-a notice-edit-a" data-notice="'.esc_attr( $key ).'">
														<i class="fas fa-pencil-alt"></i>
													</a>
												</li>
												<li class="designation-action-tools-li">
													<a href="#" class="designation-action-tools-a notice-delete-a" data-notice="'.esc_attr( $key ).'">
														<i class="far fa-window-close"></i>
													</a>
												</li>
											</ul>
										</td>
									</tr>';
							$sno++; 
						}
					}
		        }
				$status  = 'success';
				$message = __( 'Notice added successfully!', 'hr-management-lite' );
				$content = wp_kses_post( $html );
			} else {
				$status  = 'error';
				$message = __( 'Notice not added!', 'hr-management-lite' );
				$content = '';
			}

		} else {
			if ( empty ( $name ) ) {
				$message = __( 'Please enter name.!', 'hr-management-lite' );		
			} elseif ( empty ( $desc ) ) {
				$message = __( 'Please enter description.!', 'hr-management-lite' );
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

	/* Edit notices Action Call */
	public static function edit_notices() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( isset ( $_POST['key'] ) ) {
			$key          = sanitize_text_field( $_POST['key'] );
			$notices = get_option( 'ehrm_notices_data' );

			$data = array(
				'name'   => $notices[$key]['name'],
				'desc'   => $notices[$key]['desc'],
				'status' => $notices[$key]['status'],
				'date'   => $notices[$key]['date'],
			);
			wp_send_json( $data );

		} else {
			wp_send_json( __( 'Something went wrong.!', 'hr-management-lite' ) );
		}
		wp_die();
	}

	/* Update notices Action Call */
	public static function update_notices() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( isset ( $_POST['key'] ) ) {
			$key    	= sanitize_text_field( $_POST['key'] );
			$name   	= sanitize_text_field( $_POST['name'] );
			$desc   	= sanitize_text_field( $_POST['desc'] );
			$status 	= sanitize_text_field( $_POST['status'] );
			$key    	= sanitize_text_field( $_POST['key'] );
			$notices 	= get_option( 'ehrm_notices_data' );

			$data = array(
				'name'   => $name,
				'desc'   => $desc,
				'status' => $status,
				'date'   => $notices[$key]['date'],
			);

			$notices[$key] = $data;

			if ( update_option( 'ehrm_notices_data', $notices ) ) {

				$notices = get_option( 'ehrm_notices_data' );

				if ( ! empty ( $notices ) ) {
					$sno = 1;
					$first       = new \DateTime( date( "Y" )."-01-01" );
					$first       = $first->format( "Y-m-d" );
					$plusOneYear = date( "Y" )+1;
					$last        = new \DateTime( $plusOneYear."-12-31" );          
					$last        = $last->format( "Y-m-d" );          
					$all_dates   = HRMLiteHelperClass::ehrm_get_date_range( $first, $last );
            		foreach ( $notices as $key => $notice ) {
						if ( in_array( $notice['date'], $all_dates ) ) {
							$html = '<tr>
										<td>'.esc_html( $sno ).'.</td>
										<td>'.esc_html( $notice['name'] ).'</td>
										<td>'.esc_html( date( HRMLiteHelperClass::get_date_format(), strtotime( $notice['date'] ) ) ).'</td>
										<td class="badge-desc">'.esc_html( $notice['desc'] ).'</td>
										<td>'.esc_html( $notice['status'] ).'</td>
										<td class="designation-action-tools">
											<ul class="designation-action-tools-ul">
												<li class="designation-action-tools-li">
													<a href="#" class="designation-action-tools-a notice-edit-a" data-notice="'.esc_attr( $key ).'">
														<i class="fas fa-pencil-alt"></i>
													</a>
												</li>
												<li class="designation-action-tools-li">
													<a href="#" class="designation-action-tools-a notice-delete-a" data-notice="'.esc_attr( $key ).'">
														<i class="far fa-window-close"></i>
													</a>
												</li>
											</ul>
										</td>
									</tr>';
							$sno++;
						}
		            } 
		        }
				$status  = 'success';
				$message = __( 'Notice updated successfully!', 'hr-management-lite' );
				$content = wp_kses_post( $html );
			} else {
				$status  = 'error';
				$message = __( 'Notice not updated!', 'hr-management-lite' );
				$content = '';
			}

		} else {
			if ( empty ( $name ) ) {
				$message = __( 'Please enter name.!', 'hr-management-lite' );		
			} elseif ( empty ( $desc) ) {
				$message = __( 'Please enter description.!', 'hr-management-lite' );
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

	/* Delete notices Action Call */
	public static function delete_notices() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( isset ( $_POST['key'] ) ) {
			$key    = sanitize_text_field( $_POST['key'] );
			$notices = get_option( 'ehrm_notices_data' );

			unset($notices[$key]);

			if ( update_option( 'ehrm_notices_data', $notices ) ) {

				$all_notices = get_option( 'ehrm_notices_data' );

				if ( ! empty ( $all_notices ) ) {
					$sno = 1;
					$first       = new \DateTime( date( "Y" )."-01-01" );
					$first       = $first->format( "Y-m-d" );
					$plusOneYear = date( "Y" )+1;
					$last        = new \DateTime( $plusOneYear."-12-31" );          
					$last        = $last->format( "Y-m-d" );          
					$all_dates   = HRMLiteHelperClass::ehrm_get_date_range( $first, $last );
            		foreach ( $all_notices as $key => $notice ) {
						if ( in_array( $notice['date'], $all_dates ) ) {
							$html = '<tr>
										<td>'.esc_html( $sno ).'.</td>
										<td>'.esc_html( $notice['name'] ).'</td>
										<td>'.esc_html( date( HRMLiteHelperClass::get_date_format(), strtotime( $notice['date'] ) ) ).'</td>
										<td class="badge-desc">'.esc_html( $notice['desc'] ).'</td>
										<td>'.esc_html( $notice['status'] ).'</td>
										<td class="designation-action-tools">
											<ul class="designation-action-tools-ul">
												<li class="designation-action-tools-li">
													<a href="#" class="designation-action-tools-a notice-edit-a" data-notice="'.esc_attr( $key ).'">
														<i class="fas fa-pencil-alt"></i>
													</a>
												</li>
												<li class="designation-action-tools-li">
													<a href="#" class="designation-action-tools-a notice-delete-a" data-notice="'.esc_attr( $key ).'">
														<i class="far fa-window-close"></i>
													</a>
												</li>
											</ul>
										</td>
									</tr>';
							$sno++; 
						}
		            } 
		        }
				$status  = 'success';
				$message = __( 'Notice deleted successfully!', 'hr-management-lite' );
				$content = wp_kses_post( $html );
			} else {
				$status  = 'error';
				$message = __( 'Notice not deleted!', 'hr-management-lite' );
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