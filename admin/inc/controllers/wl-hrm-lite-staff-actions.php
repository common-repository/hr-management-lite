<?php defined( 'ABSPATH' ) or die();
require_once( WL_HRML_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-hrm-lite-helper.php' );

/**
 * Staff ajax action class
 */
class LiteStaffAjaxActions {

	/* Add Fetch User's data Action Call */
	public static function fetch_userdata() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( isset ( $_POST['staff_key'] ) ) {
			$user_id = sanitize_text_field( $_POST['staff_key'] );
			$user    = get_userdata( $user_id );
			if ( ! empty ( $user ) ) {
				$data = array(
					'ID'            => $user->ID,
					'first_name'    => $user->first_name,
					'last_name'     => $user->last_name,
					'user_login'    => $user->user_login,
					'user_nicename' => $user->user_nicename,
					'user_email'    => $user->user_email,
					'display_name'  => $user->display_name,
				);
				wp_send_json( $data );
			} else {
				wp_send_json( 'No data' );
			}

		} else {
			wp_send_json( 'Something went wrong.!' );
		}
		wp_die();
	}

	/* Add Staff Action Call */
	public static function add_staff() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( ! empty ( $_POST['name'] ) && ! empty ( $_POST['first'] ) && ! empty ( $_POST['last'] ) && ! empty ( $_POST['email'] ) && ( ! empty ( $_POST['shift'] ) || $_POST['shift'] == '0' ) && ! empty ( $_POST['staff'] ) && ( ! empty ( $_POST['designation'] ) || $_POST['designation'] == '0' ) && ! empty ( $_POST['salary'] ) && ! empty ( $_POST['status'] ) ) {
			$name        = sanitize_text_field( $_POST['name'] );
			$first       = sanitize_text_field( $_POST['first'] );
			$last        = sanitize_text_field( $_POST['last'] );
			$email       = sanitize_email( $_POST['email'] );
			$shift       = sanitize_text_field( $_POST['shift'] );
			$desig_id    = sanitize_text_field( $_POST['designation'] );
			$salary      = sanitize_text_field( $_POST['salary'] );
			$status      = sanitize_text_field( $_POST['status'] );
			$leave_name  = array_map( 'sanitize_text_field', $_POST['leave_name'] );
			$leave_name  = serialize( $leave_name );
			$leave_value = array_map( 'sanitize_text_field', $_POST['leave_value'] );
			$leave_value = serialize( $leave_value );
			$user_id     = sanitize_text_field( $_POST['staff'] );
			$staffs      = get_option( 'ehrm_staffs_data' );
			$fullname    = $first.' '.$last;
			$shifts      = get_option( 'ehrm_shifts_data' );
			$designation = get_option( 'ehrm_designations_data' );
			$exist       = 0;
			$html        = '';

			if ( ! empty ( $staffs ) ) {
        		$sno = 1;
        		foreach ( $staffs as $key => $staff ) {
        			if ( $staff['ID'] == $user_id && $staff['fullname'] == $fullname && $email == $staff['email'] ) {
        				$exist = 1;
        			}
        		}
        	}

        	if ( $exist == 0 ) {
				$data = array(
						'ID'          => $user_id,
						'fullname'    => $fullname,
						'first_name'  => $first,
						'last_name'   => $last,
						'username'    => $name,
						'email'       => $email,
						'salary'      => $salary,
						'shift_id'    => $shift,
						'shift_name'  => $shifts[$shift]['name'],
						'shift_start' => $shifts[$shift]['start'],
						'shift_end'   => $shifts[$shift]['end'],
						'desig_id'    => $desig_id,
						'deparment'   => '',
						'desig_name'  => $designation[$desig_id]['name'],
						'desig_color' => $designation[$desig_id]['color'],
						'leave_name'  => $leave_name,
						'leave_value' => $leave_value,
						'status'      => $status,
					);

				if ( empty ( $staffs ) ) {
					$staffs = array();
				}
				array_push( $staffs, $data );

				if ( update_option( 'ehrm_staffs_data', $staffs ) ) {

                    $id = isset($id) ? $id : $user_id;
					HRMLiteHelperClass::send_new_joining_greet_mail( $id );
					HRMLiteHelperClass::send_new_joining_employee_mails( $id );

					$all_staffs = get_option( 'ehrm_staffs_data' );

					if ( ! empty ( $all_staffs ) ) {
	            		$sno = 1;
	            		foreach ( $all_staffs as $key => $staff ) {

	            			$leave_name  = unserialize( $staff['leave_name'] );
							$leave_value = unserialize( $staff['leave_value'] );
							$leave_no    = sizeof( $leave_name );

			                $html .= '<tr>
					                	<td>'.esc_html( $sno ).'</td>
					                  	<td>'.esc_html( $staff['fullname'] ).'</td>
					                  	<td>'.esc_html( $staff['email'] ).'</td>
					                  	<td>'.esc_html( $staff['shift_name'] . '( ' . date( HRMLiteHelperClass::get_time_format(), strtotime( $staff['shift_start'] ) ) . ' to ' . date( HRMLiteHelperClass::get_time_format(), strtotime( $staff['shift_end'] ) ) . ' )').'</td>
					                  	<td>'.esc_html( $staff['desig_name'] ).'</td>';

							$html .= '<td>';
					        for ( $i = 0; $i < $leave_no; $i++ ) {
	                            $html .= '<span>'.$leave_name[$i].' ( '.$leave_value[$i].')</br></br></span>';
	                        }
							$html .= '</td>
										<td>'.esc_html( $staff['salary'] ).'</td>
					                  	<td>'.esc_html( $staff['status'] ).'</td>
					                  	<td class="designation-action-tools">
			                          		<ul class="designation-action-tools-ul">
			                          			<li class="designation-action-tools-li">
			                          				<a href="#" title="'.esc_html( 'Edit', 'hr-management-lite' ).'" class="designation-action-tools-a staff-edit-a" data-staff="'.esc_attr( $key ).'">
			                          					<i class="fas fa-pencil-alt"></i>
			                          				</a>
			                          			</li>
			                          			<li class="designation-action-tools-li">
			                          				<a href="#" title="'.esc_html( 'Delete', 'hr-management-lite' ).'" class="designation-action-tools-a staff-delete-a" data-staff="'.esc_attr( $key ).'">
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
					$message = esc_html__( 'Staff added successfully!', 'hr-management-lite' );
					$content = wp_kses_post( $html );
				} else {
					$status  = 'error';
					$message = esc_html__( 'Staff not added!', 'hr-management-lite' );
					$content = '';
				}
			} else {
				$status  = 'error';
				$message = esc_html__( 'Staff already exist.!!', 'hr-management-lite' );
				$content = '';
			}
		} else {

			if ( empty ( $_POST['name'] ) ) {
				$message = esc_html__( 'Please enter name.!', 'hr-management-lite' );
			} elseif ( empty ( $_POST['first'] ) ) {
				$message = esc_html__( 'Please enter first name.!', 'hr-management-lite' );
			} elseif ( empty ( $_POST['last'] ) ) {
				$message = esc_html__( 'Please enter last name.!', 'hr-management-lite' );
			} elseif ( empty ( $_POST['email'] ) ) {
				$message = esc_html__( 'Please enter email.!', 'hr-management-lite' );
			} elseif ( empty ( $_POST['shift'] ) && $_POST['shift'] != '0' ) {
				$message = esc_html__( 'Please select shift.!', 'hr-management-lite' );
			} elseif ( empty ( $_POST['designation'] )  && $_POST['designation'] != '0'  ) {
				$message = esc_html__( 'Please select designation.!', 'hr-management-lite' );
			} elseif ( empty ( $_POST['salary'] ) ) {
				$message = esc_html__( 'Please enter salary.!', 'hr-management-lite' );
			} elseif ( empty ( $_POST['status'] ) ) {
				$message = esc_html__( 'Please select status.!', 'hr-management-lite' );
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

	/* Edit Staff Action Call */
	public static function edit_staff() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( isset ( $_POST['staff_key'] ) ) {
			$key    = sanitize_text_field( $_POST['staff_key'] );
			$staffs = get_option( 'ehrm_staffs_data' );
			$names  = json_encode( unserialize( $staffs[$key]['leave_name'] ) );
            $values = json_encode( unserialize( $staffs[$key]['leave_value'] ) );

			$data = array(
					'ID'          => $staffs[$key]['ID'],
					'fullname'    => $staffs[$key]['fullname'],
					'first_name'  => $staffs[$key]['first_name'],
					'last_name'   => $staffs[$key]['last_name'],
					'username'    => $staffs[$key]['username'],
					'email'       => $staffs[$key]['email'],
					'salary'      => $staffs[$key]['salary'],
					'shift_id'    => $staffs[$key]['shift_id'],
					'shift_name'  => $staffs[$key]['shift_name'],
					'shift_start' => $staffs[$key]['shift_start'],
					'shift_end'   => $staffs[$key]['shift_end'],
					'desig_id'    => $staffs[$key]['desig_id'],
					'deparment'   => '',
					'desig_name'  => $staffs[$key]['desig_name'],
					'desig_color' => $staffs[$key]['desig_color'],
					'leave_name'  => $names,
					'leave_value' => $values,
					'status'      => $staffs[$key]['status'],
				);

			wp_send_json( $data );
		} else {
			wp_send_json( __( 'Something went wrong.!', 'hr-management-lite' ) );
		}
		wp_die();
	}

	/* Update Staff Action Call */
	public static function update_staff() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( ! empty ( $_POST['name'] ) && ! empty ( $_POST['first'] ) && ! empty ( $_POST['last'] ) && ! empty ( $_POST['email'] ) && ( ! empty ( $_POST['shift'] ) || $_POST['shift'] == '0' ) && ! empty ( $_POST['staff'] ) && ( ! empty ( $_POST['designation'] ) || $_POST['designation'] == '0' ) && ! empty ( $_POST['salary'] ) && ! empty ( $_POST['status'] ) ) {
			$staff_key   = sanitize_text_field( $_POST['staff_key'] );
			$name        = sanitize_text_field( $_POST['name'] );
			$first       = sanitize_text_field( $_POST['first'] );
			$last        = sanitize_text_field( $_POST['last'] );
			$email       = sanitize_email( $_POST['email'] );
			$shift       = sanitize_text_field( $_POST['shift'] );
			$desig_id    = sanitize_text_field( $_POST['designation'] );
			$salary      = sanitize_text_field( $_POST['salary'] );
			$status      = sanitize_text_field( $_POST['status'] );
			$leave_name  = array_map( 'sanitize_text_field', $_POST['leave_name'] );
			$leave_name  = serialize( $leave_name );
			$leave_value = array_map( 'sanitize_text_field', $_POST['leave_value'] );
			$leave_value = serialize( $leave_value );
			$user_id     = sanitize_text_field( $_POST['staff'] );
			$staffs      = get_option( 'ehrm_staffs_data' );
			$fullname    = $first.' '.$last;
			$shifts      = get_option( 'ehrm_shifts_data' );
			$designation = get_option( 'ehrm_designations_data' );
			$html        = '';

			$data = array(
					'ID'          => $user_id,
					'fullname'    => $fullname,
					'first_name'  => $first,
					'last_name'   => $last,
					'username'    => $name,
					'email'       => $email,
					'salary'      => $salary,
					'shift_id'    => $shift,
					'shift_name'  => $shifts[$shift]['name'],
					'shift_start' => $shifts[$shift]['start'],
					'shift_end'   => $shifts[$shift]['end'],
					'desig_id'    => $desig_id,
					'deparment'   => '',
					'desig_name'  => $designation[$desig_id]['name'],
					'desig_color' => $designation[$desig_id]['color'],
					'leave_name'  => $leave_name,
					'leave_value' => $leave_value,
					'status'      => $status,
				);

			$staffs[$staff_key] = $data;

			if ( update_option( 'ehrm_staffs_data', $staffs ) ) {

				$all_staffs = get_option( 'ehrm_staffs_data' );

				if ( ! empty ( $all_staffs ) ) {
            		$sno = 1;
            		foreach ( $all_staffs as $key => $staff ) {

            			$leave_name  = unserialize( $staff['leave_name'] );
						$leave_value = unserialize( $staff['leave_value'] );
						$leave_no    = sizeof( $leave_name );

		                $html .= '<tr>
				                	<td>'.esc_html( $sno ).'</td>
				                  	<td>'.esc_html( $staff['fullname'] ).'</td>
				                  	<td>'.esc_html( $staff['email'] ).'</td>
				                  	<td>'.esc_html( $staff['shift_name'] . '( ' . date( HRMLiteHelperClass::get_time_format(), strtotime( $staff['shift_start'] ) ) . ' to ' . date( HRMLiteHelperClass::get_time_format(), strtotime( $staff['shift_end'] ) ) . ' )').'</td>
				                  	<td>'.esc_html( $staff['desig_name'] ).'</td>';

						$html .= '<td>';
						for ($i = 0; $i < $leave_no; $i++) {
							$html .= '<span>' . $leave_name[$i] . ' ( ' . $leave_value[$i] . ')</br></br></span>';
						}
						$html .= '</td>
									<td>'.esc_html( $staff['salary'] ).'</td>
				                  	<td>'.esc_html( $staff['status'] ).'</td>
				                  	<td class="designation-action-tools">
		                          		<ul class="designation-action-tools-ul">
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" title="'.esc_html( 'Edit', 'hr-management-lite' ).'" class="designation-action-tools-a staff-edit-a" data-staff="'.esc_attr( $key ).'">
		                          					<i class="fas fa-pencil-alt"></i>
		                          				</a>
		                          			</li>
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" title="'.esc_html( 'Delete', 'hr-management-lite' ).'" class="designation-action-tools-a staff-delete-a" data-staff="'.esc_attr( $key ).'">
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
				$message = esc_html__( 'Staff updated successfully!', 'hr-management-lite' );
				$content = wp_kses_post( $html );
			} else {
				$status  = 'error';
				$message = esc_html__( 'Staff not updated!', 'hr-management-lite' );
				$content = '';
			}
		 } else {
			if ( empty ( $_POST['name'] ) ) {
				$message = esc_html__( 'Please enter name.!', 'hr-management-lite' );
			} elseif ( empty ( $_POST['first'] ) ) {
				$message = esc_html__( 'Please enter first name.!', 'hr-management-lite' );
			} elseif ( empty ( $_POST['last'] ) ) {
				$message = esc_html__( 'Please enter last name.!', 'hr-management-lite' );
			} elseif ( empty ( $_POST['email'] ) ) {
				$message = esc_html__( 'Please enter email.!', 'hr-management-lite' );
			} elseif ( empty ( $_POST['shift'] ) && $_POST['shift'] != '0' ) {
				$message = esc_html__( 'Please select shift.!', 'hr-management-lite' );
			} elseif ( empty ( $_POST['designation'] )  && $_POST['designation'] != '0'  ) {
				$message = esc_html__( 'Please select designation.!', 'hr-management-lite' );
			} elseif ( empty ( $_POST['salary'] ) ) {
				$message = esc_html__( 'Please enter salary.!', 'hr-management-lite' );
			} elseif ( empty ( $_POST['status'] ) ) {
				$message = esc_html__( 'Please select status.!', 'hr-management-lite' );
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

	/* Delete Staff Action Call */
	public static function delete_staff() {
		check_ajax_referer( 'backend_ajax_nonce', 'nounce' );

		if ( isset ( $_POST['staff_key'] ) ) {
			$staff_key = sanitize_text_field( $_POST['staff_key'] );
			$staffs    = get_option( 'ehrm_staffs_data' );
			$html      = '';

			unset( $staffs[$staff_key] );

			if ( update_option( 'ehrm_staffs_data', $staffs ) ) {

				$all_staffs = get_option( 'ehrm_staffs_data' );

				if ( ! empty ( $all_staffs ) ) {
            		$sno = 1;
            		foreach ( $all_staffs as $key => $staff ) {

            			$leave_name  = unserialize( $staff['leave_name'] );
						$leave_value = unserialize( $staff['leave_value'] );
						$leave_no    = sizeof( $leave_name );

		                $html .= '<tr>
				                	<td>'.esc_html( $sno ).'</td>
				                  	<td>'.esc_html( $staff['fullname'] ).'</td>
				                  	<td>'.esc_html( $staff['email'] ).'</td>
				                  	<td>'.esc_html( $staff['shift_name'] . '( ' . date( HRMLiteHelperClass::get_time_format(), strtotime( $staff['shift_start'] ) ) . ' to ' . date( HRMLiteHelperClass::get_time_format(), strtotime( $staff['shift_end'] ) ) . ' )').'</td>
				                  	<td>'.esc_html( $staff['desig_name'] ).'</td>';

						$html .= '<td>';
						for ($i = 0; $i < $leave_no; $i++) {
							$html .= '<span>' . $leave_name[$i] . ' ( ' . $leave_value[$i] . ')</br></br></span>';
						}
						$html .= '</td>
									<td>'.esc_html( $staff['salary'] ).'</td>
				                  	<td>'.esc_html( $staff['status'] ).'</td>
				                  	<td class="designation-action-tools">
		                          		<ul class="designation-action-tools-ul">
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" title="'.esc_html( 'Edit', 'hr-management-lite' ).'" class="designation-action-tools-a staff-edit-a" data-staff="'.esc_attr( $key ).'">
		                          					<i class="fas fa-pencil-alt"></i>
		                          				</a>
		                          			</li>
		                          			<li class="designation-action-tools-li">
		                          				<a href="#" title="'.esc_html( 'Delete', 'hr-management-lite' ).'" class="designation-action-tools-a staff-delete-a" data-staff="'.esc_attr( $key ).'">
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
				$message = esc_html__( 'Staff deleted successfully!', 'hr-management-lite' );
				$content = wp_kses_post( $html );
			} else {
				$status  = 'error';
				$message = esc_html__( 'Staff not deleted!', 'hr-management-lite' );
				$content = '';
			}
		} else {
			$status  = 'error';
			$message = esc_html__( 'Something went wrong.!', 'hr-management-lite' );
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
