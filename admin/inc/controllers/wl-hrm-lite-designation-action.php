<?php
defined( 'ABSPATH' ) or die();

/**
 *  Ajax Action calls for designations menu
 */
class LiteDesignationsAjaxAction {
	
	public static function add_designations() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( ! empty ( $_POST['name'] ) && ! empty ( $_POST['color'] ) && ! empty ( $_POST['status'] ) ) {
			$name         = sanitize_text_field( $_POST['name'] );
			$color        = sanitize_text_field( $_POST['color'] );
			$status       = sanitize_text_field( $_POST['status'] );
			$designations = get_option( 'ehrm_designations_data' );
			$html         = '';

			$data = array(
				'deparment' => '',
				'name'      => $name,
				'color'     => $color,
				'status'    => $status,
			);

			if ( empty ( $designations ) ) {
				$designations = array();
			}
			array_push( $designations, $data );

			if ( update_option( 'ehrm_designations_data', $designations ) ) {

				$all_designations = get_option( 'ehrm_designations_data' );

				if ( ! empty ( $all_designations ) ) {
            		$sno = 1;
            		foreach ( $all_designations as $key => $designation ) {
            	
		                $html = '<tr>
				                	<td>'.esc_html( $sno ).'.</td>
				                  	<td>'.esc_html( $designation['name'] ).'</td>
				                  	<td><label class="badge" style="background-color:'.esc_attr( $designation['color'] ).';">'.esc_attr( $designation['color'] ).'</label></td>
				                  	<td>'.esc_html( $designation['status'] ).'</td>
				                  	<td class="designation-action-tools">
		                          		<ul class="designation-action-tools-ul">
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a designation-edit-a" data-designation="'.esc_attr( $key ).'">
		                          					<i class="fas fa-pencil-alt"></i>
		                          				</a>
		                          			</li>
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a designation-delete-a" data-designation="'.esc_attr( $key ).'">
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
				$message = __( 'Designation added successfully!', 'employee-&-hr-management' );
				$content = wp_kses_post( $html );

			} else {
				$status  = 'error';
				$message = __( 'Designation not added!', 'employee-&-hr-management' );
				$content = '';
			}

		} else {

			if ( empty ( $_POST['name'] ) ) {
				$message = __( 'Please enter name.!', 'employee-&-hr-management' );
			} elseif ( empty ( $_POST['color'] ) ) {
				$message = __( 'Please select color.!', 'employee-&-hr-management' );
			} elseif ( empty ( $_POST['status'] ) ) {
				$message = __( 'Please select status.!', 'employee-&-hr-management' );
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

	/* Edit Designations Action Call */
	public static function edit_designations() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( isset ( $_POST['key'] ) ) {
			$key          = sanitize_text_field( $_POST['key'] );
			$designations = get_option( 'ehrm_designations_data' );

			$data = array(
				'deparment' => '',
				'name'      => $designations[$key]['name'],
				'color'     => $designations[$key]['color'],
				'status'    => $designations[$key]['status'],
			);
			wp_send_json( $data );

		} else {
			wp_send_json( __( 'Something went wrong.!', 'employee-&-hr-management' ) );
		}
		wp_die();
	}

	/* Update Designations Action Call */
	public static function update_designations() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( ! empty ( $_POST['name'] ) && ! empty ( $_POST['color'] ) && ! empty ( $_POST['status'] ) ) {
			$name         = sanitize_text_field( $_POST['name'] );
			$key          = sanitize_text_field( $_POST['key'] );
			$color        = sanitize_text_field( $_POST['color'] );
			$status       = sanitize_text_field( $_POST['status'] );
			$designations = get_option( 'ehrm_designations_data' );
			$html         = '';

			$data = array(
				'deparment' => '',
				'name'      => $name,
				'color'     => $color,
				'status'    => $status,
			);

			$designations[$key] = $data;

			if ( update_option( 'ehrm_designations_data', $designations ) ) {

				$designations = get_option( 'ehrm_designations_data' );

				if ( ! empty ( $designations ) ) {
            		$sno = 1;
            		foreach ( $designations as $key => $designation ) {
            	
		                $html = '<tr>
				                	<td>'.esc_html( $sno ).'.</td>
				                  	<td>'.esc_html( $designation['name'] ).'</td>
				                  	<td><label class="badge" style="background-color:'.esc_attr( $designation['color'] ).';">'.esc_attr( $designation['color'] ).'</label></td>
				                  	<td>'.esc_html( $designation['status'] ).'</td>
				                  	<td class="designation-action-tools">
		                          		<ul class="designation-action-tools-ul">
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a designation-edit-a" data-designation="'.esc_attr( $key ).'">
		                          					<i class="fas fa-pencil-alt"></i>
		                          				</a>
		                          			</li>
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a designation-delete-a" data-designation="'.esc_attr( $key ).'">
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
				$message = __( 'Designation updated successfully!', 'employee-&-hr-management' );
				$content = wp_kses_post( $html );
			} else {
				$status  = 'error';
				$message = __( 'Designation not Updated!', 'employee-&-hr-management' );
				$content = '';
			}

		} else {
			if ( empty ($name ) ) {
				$message = __( 'Please enter name.!', 'employee-&-hr-management' );
			} elseif ( empty ($color  ) ) {
				$message = __( 'Please select color.!', 'employee-&-hr-management' );
			} elseif ( empty ($status ) ) {
				$message = __( 'Please select status.!', 'employee-&-hr-management' );
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

	/* Delete Designations Action Call */
	public static function delete_designations() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( ! empty ( $_POST['key'] ) ) {
			$key          = sanitize_text_field( $_POST['key'] );
			$designations = get_option( 'ehrm_designations_data' );

			unset($designations[$key]);

			if ( update_option( 'ehrm_designations_data', $designations ) ) {

				$designations = get_option( 'ehrm_designations_data' );

				if ( ! empty ( $designations ) ) {
            		$sno = 1;
            		foreach ( $designations as $key => $designation ) {
            	
		                $html = '<tr>
				                	<td>'.esc_html( $sno ).'.</td>
				                  	<td>'.esc_html( $designation['name'] ).'</td>
				                  	<td><label class="badge" style="background-color:'.esc_attr( $designation['color'] ).';">'.esc_attr( $designation['color'] ).'</label></td>
				                  	<td>'.esc_html( $designation['status'] ).'</td>
				                  	<td class="designation-action-tools">
		                          		<ul class="designation-action-tools-ul">
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a designation-edit-a" data-designation="'.esc_attr( $key ).'">
		                          					<i class="fas fa-pencil-alt"></i>
		                          				</a>
		                          			</li>
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" class="designation-action-tools-a designation-delete-a" data-designation="'.esc_attr( $key ).'">
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
				$message = __( 'Designation deleted successfully!', 'employee-&-hr-management' );
				$content = wp_kses_post( $html );
			} else {
				$status  = 'error';
				$message = __( 'Designation not Deleted!', 'employee-&-hr-management' );
				$content = '';
			}

		} else {
			$status  = 'error';
			$message = __( 'Something went wrong.!!', 'employee-&-hr-management' );
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