<?php
defined( 'ABSPATH' ) or die();
require_once( WL_HRML_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-hrm-lite-helper.php' );
/**
 *  Ajax Action calls for notices menu
 */
class LiteProjectAjaxAction {
    
    /** Add project **/
	public static function add_projects() {
        check_ajax_referer( 'project_ajax_nonce', 'nounce' );

        if ( ! empty ( $_POST['name'] ) && isset ( $_POST['desc'] ) && ! empty ( $_POST['member'] ) ) {

            $name    = sanitize_text_field( $_POST['name'] );
            $desc    = wp_kses_post( $_POST['desc'] );
            //$member  = array_map( 'sanitize_text_field', $_POST['member'] );
            $member  = serialize( $_POST['member']);
            $tags    = wp_kses_post( $_POST['tags'] );
            $status  = sanitize_text_field( $_POST['status'] );
            $pojects = get_option( 'ehrm_projects_data' );
            $html    = '';

            $date = date( 'Y-m-d' );
            $data   = array(
                'name'     => $name,
                'desc'     => $desc,
                'members'  => $member,
                'tags'     => $tags,
                'status'   => $status,
                'date'     => $date,
                'staff_id' => get_current_user_id(),
                'tasks'    => array(),
            );

            if ( empty ( $pojects ) ) {
                $pojects = array();
            } else {
                $project_arr_size = sizeof( $pojects );
                if ( $project_arr_size > 3 ) {
                    $status  = 'error';
                    $message = __( 'You can add only 4 projects in free version.!', 'hr-management-lite' );
                    $content = '';
                    $return = array(
                        'status'  => $status,
                        'message' => $message,
                        'content' => $content
					);
					wp_send_json( $return );
                }
            }
            array_push( $pojects, $data );

            if ( update_option( 'ehrm_projects_data', $pojects ) ) {

                $all_projects = get_option( 'ehrm_projects_data' );
                if ( ! empty ( $all_projects ) ) {
                    $sno = 1;
                    foreach ( $all_projects as $key => $project ) {
                        $members = unserialize( $project['members'] );

                        if ( $name == $project['name'] ) {
                            HRMLiteHelperClass::send_project_detail_mails( $key );
                        }

                        $html .= '<tr>
                                    <td>'.esc_html( $sno ).'.</td>
                                    <td>'.esc_html( $project['name'] ).'</td>
                                    <td>'.esc_html( date( HRMLiteHelperClass::get_date_format(), strtotime( $project['date'] ) ) ).'</td>
                                    <td>';
                        foreach ( $members as $member_key => $value ) {
                            $html .= HRMLiteHelperClass::get_current_user_data( $value, 'fullname' ) . ', ';
                        }
                        $html .= '</td>
                                    <td>'.esc_html( $project['tags'] ).'</td>
                                    <td>'.esc_html( $project['status'] ).'</td>
                                    <td class="designation-action-tools">
                                        <ul class="designation-action-tools-ul">
                                            <li class="designation-action-tools-li">
                                                <a href="#" title="'.esc_html__( 'View Tasks', 'hr-management-lite' ).'" class="designation-action-tools-a project-view-a" data-project="'.esc_attr( $key ).'">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </li>
                                            <li class="designation-action-tools-li">
                                                <a href="#" title="'.esc_html__( 'Edit', 'hr-management-lite' ).'" class="designation-action-tools-a project-edit-a" data-project="'.esc_attr( $key ).'">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            </li>
                                            <li class="designation-action-tools-li">
                                                <a href="#" title="'.esc_html__( 'Delete', 'hr-management-lite' ).'" class="designation-action-tools-a project-delete-a" data-project="'.esc_attr( $key ).'">
                                                    <i class="far fa-window-close"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>';
                    $sno++; 
                    }
                }
                $content = wp_kses_post( $html );
                $message = __( 'Project added successfully!', 'hr-management-lite' );
                $status  = 'success';
            } else {
                $content = '';
                $message = __( 'Project not added!', 'hr-management-lite' );
                $status  = 'error';
            }

        } else {
            if ( empty ( $name ) ) {
                $message = __( 'Please enter name.!', 'hr-management-lite' );
            } elseif ( empty ( $member ) ) {
                $message = __( 'Please select member.!', 'hr-management-lite' );
            } else {
                $message = __( 'Something went wrong.!', 'hr-management-lite' );
            }
			$content = '';
            $status  = 'error';

            $content = '';
            $status  = 'error';
        }
        $return = array(
            'status'  => $status,
            'message' => $message,
            'content' => $content,
        );
        wp_send_json( $return );
		wp_die();
    }

    /** Edit project **/
    public static function edit_projects() {
        check_ajax_referer( 'project_ajax_nonce', 'nounce' );

        if ( isset ( $_POST['key'] ) ) {
            $key      = sanitize_text_field( $_POST['key'] );
            $projects = get_option( 'ehrm_projects_data' );

            $data = array(
                'name'    => $projects[$key]['name'],
                'desc'    => $projects[$key]['desc'],
                'members' => unserialize( $projects[$key]['members'] ),
                'tags'    => $projects[$key]['tags'],
                'status'  => $projects[$key]['status'],
            );

            wp_send_json( $data );
        } else {
            wp_send_json( __( 'Something went wrong.!', 'hr-management-lite' ) );
        }
        wp_die();
    }

    /** Update project **/
    public static function update_projects() {
        check_ajax_referer( 'project_ajax_nonce', 'nounce' );

        if ( ! empty ( $_POST['name'] ) && isset ( $_POST['desc'] ) && ! empty ( $_POST['member'] ) ) {            
            $name     = sanitize_text_field( $_POST['name'] );
            $desc     = wp_kses_post( $_POST['desc'] );
            $key      = wp_kses_post( $_POST['key'] );
            $member   = array_map( 'sanitize_text_field', $_POST['member'] );
            $member   = serialize( $member );
            $tags     = wp_kses_post( $_POST['tags'] );
            $status   = sanitize_text_field( $_POST['status'] );
            $projects = get_option( 'ehrm_projects_data' );
            $html     = '';

            $projects[$key]['name']      = $name;
            $projects[$key]['desc']      = $desc;
            $projects[$key]['members']   = $member;
            $projects[$key]['tags']      = $tags;
            $projects[$key]['status']    = $status;
            $projects[$key]['staff_id']  = $projects[$key]['staff_id'];
            
            if ( update_option( 'ehrm_projects_data', $projects ) ) {
                $all_projects = get_option( 'ehrm_projects_data' );
                if ( ! empty ( $all_projects ) ) {
                    $sno = 1;
                    foreach ( $all_projects as $key => $project ) {
                        $members = unserialize( $project['members'] );

                        $html .= '<tr>
                                    <td>'.esc_html( $sno ).'.</td>
                                    <td>'.esc_html( $project['name'] ).'</td>
                                    <td>'.esc_html( date( HRMLiteHelperClass::get_date_format(), strtotime( $project['date'] ) ) ).'</td>
                                    <td>';
                        foreach ( $members as $member_key => $value ) {
                            $html .= HRMLiteHelperClass::get_current_user_data( $value, 'fullname' ) . ', ';
                        }
                        $html .= '</td>
                                    <td>';
                        $tags = explode( ",", $project['tags'] );
                        foreach ( $tags as $tag_key => $value ) {
                            $html .= '<span class="token-field-value-span">'.esc_html__( $value, 'hr-management-lite').'</span>';
                        }                         
                        $html .= '</td>
                                    <td>'.esc_html( $project['status'] ).'</td>
                                    <td class="designation-action-tools">
                                        <ul class="designation-action-tools-ul">
                                            <li class="designation-action-tools-li">
                                                <a href="#" title="'.esc_html__( 'View Tasks', 'hr-management-lite' ).'" class="designation-action-tools-a project-view-a" data-project="'.esc_attr( $key ).'">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </li>
                                            <li class="designation-action-tools-li">
                                                <a href="#" title="'.esc_html__( 'Edit', 'hr-management-lite' ).'" class="designation-action-tools-a project-edit-a" data-project="'.esc_attr( $key ).'">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            </li>
                                            <li class="designation-action-tools-li">
                                                <a href="#" title="'.esc_html__( 'Delete', 'hr-management-lite' ).'" class="designation-action-tools-a project-delete-a" data-project="'.esc_attr( $key ).'">
                                                    <i class="far fa-window-close"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>';
                        $sno++; 
                    }
                }
                $content = wp_kses_post( $html );
                $message = __( 'Project updated successfully!', 'hr-management-lite' );
                $status  = 'success';
            } else {
                $content = '';
                $message = __( 'Project not updated!', 'hr-management-lite' );
                $status  = 'error';
            }

        } else {
            if ( empty ( $name ) ) {
                $message = __( 'Please enter name.!', 'hr-management-lite' );
            } elseif ( empty ( $member ) ) {
                $message = __( 'Please select member.!', 'hr-management-lite' );
            } else {
                $message = __( 'Something went wrong.!', 'hr-management-lite' );
            }
			$content = '';
            $status  = 'error';
		}
		$return = array(
            'status'  => $status,
            'message' => $message,
            'content' => $content,
        );
        wp_send_json( $return );
		wp_die();
    }

    /** Delete project **/
    public static function delete_projects() {
        check_ajax_referer( 'project_ajax_nonce', 'nounce' );

        if ( isset ( $_POST['key'] ) ) {
            $key      = sanitize_text_field( $_POST['key'] );
            $projects = get_option( 'ehrm_projects_data' );
            $html     = '';

            unset( $projects[$key] );

            if ( update_option( 'ehrm_projects_data', $projects ) ) {
                $all_projects = get_option( 'ehrm_projects_data' );
                if ( ! empty ( $all_projects ) ) {
                    $sno = 1;
                    foreach ( $all_projects as $key => $project ) {
                        $members = unserialize( $project['members'] );

                        $html .= '<tr>
                                    <td>'.esc_html( $sno ).'.</td>
                                    <td>'.esc_html( $project['name'] ).'</td>
                                    <td>'.esc_html( date( HRMLiteHelperClass::get_date_format(), strtotime( $project['date'] ) ) ).'</td>
                                    <td>';
                        foreach ( $members as $member_key => $value ) {
                            $html .= HRMLiteHelperClass::get_current_user_data( $value, 'fullname' ) . ', ';
                        }
                        $html .= '</td>
                                    <td>';
                        $tags = explode( ",", $project['tags'] );
                        foreach ( $tags as $tag_key => $value ) {
                            $html .= '<span class="token-field-value-span">'.esc_html__( $value, 'hr-management-lite' ).'</span>';
                        }                         
                        $html .= '</td>
                                    <td>'.esc_html( $project['status'] ).'</td>
                                    <td class="designation-action-tools">
                                        <ul class="designation-action-tools-ul">
                                            <li class="designation-action-tools-li">
                                                <a href="#" title="'.esc_html__( 'View Tasks', 'hr-management-lite' ).'" class="designation-action-tools-a project-view-a" data-project="'.esc_attr( $key ).'">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </li>
                                            <li class="designation-action-tools-li">
                                                <a href="#" title="'.esc_html__( 'Edit', 'hr-management-lite' ).'" class="designation-action-tools-a project-edit-a" data-project="'.esc_attr( $key ).'">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            </li>
                                            <li class="designation-action-tools-li">
                                                <a href="#" title="'.esc_html__( 'Delete', 'hr-management-lite' ).'" class="designation-action-tools-a project-delete-a" data-project="'.esc_attr( $key ).'">
                                                    <i class="far fa-window-close"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>';
                        $sno++;
                    }
                }
                $content = wp_kses_post( $html );
                $message = __( 'Project deleted successfully!', 'hr-management-lite' );
                $status  = 'success';
            } else {
                $content = '';
                $message = __( 'Project not deleted!', 'hr-management-lite' );
                $status  = 'error';
            }

        } else {
			$content = '';
            $message = __( 'Something went wrong.!', 'hr-management-lite' );
            $status  = 'error';
		}
		$return = array(
            'status'  => $status,
            'message' => $message,
            'content' => $content,
        );
        wp_send_json( $return );
		wp_die();
    }

    /** View all tasks **/
    public static function view_all_tasks() {
        check_ajax_referer( 'project_ajax_nonce', 'nounce' );

        if ( isset ( $_POST['key'] ) ) {
            $key        = sanitize_text_field( $_POST['key'] );
            $projects   = get_option( 'ehrm_projects_data' );
            $html       = '';
            $mhtml      = '';
            $current_id = get_current_user_id();

            if ( empty ( $projects[$key]['tasks'] ) ) {
                $html .= esc_html__( 'No tasks created yet.!', 'hr-management-lite' );
            } else {
                foreach ( $projects[$key]['tasks'] as $task_key => $task_value ) {

					if ( ! empty ( $task_value['progress'] ) ) {
						if ( $task_value['progress'] == 'In Progress' ) {
							$status = 'in-progress';
						} elseif ( $task_value['progress'] == 'Completed' ) {
							$status = 'complete';
						}  elseif ( $task_value['progress'] == 'No Progress' ) {
							$status = 'no-progress';
						}
					}

					$html .= '<li class="project-task-li">
			                    <a class="task-completion-status '.esc_attr( $status ).'" href="#" data-status="'.esc_attr( $task_value['progress'] ).'" data-project="'.esc_attr( $task_value['project_id'] ).'" data-task="'.esc_attr( $task_key ).'">
						  			<i class="fa fa-check-circle"></i>
						  		</a>
            					<a href="#" class="view_task_detail '.esc_attr( $status ).'" data-project="'.esc_attr( $task_value['project_id'] ).'" data-task="'.esc_attr( $task_key ).'">
            						'.esc_html( $task_value['name'] ).'
            					</a>
            					<span class="badge task-priority '.esc_html( $task_value['priority'] ).'">'.esc_html($task_value['priority']).'</span>
						    	<span class="badge task-assign">';

                    foreach ( unserialize( $task_value['assign'] ) as $member_key => $value ) {
                        $html .= HRMLiteHelperClass::get_current_user_data( $value, 'fullname' ) . ', ';
                    }

					$html .= '  </span>
						    	<span class="badge task-duedate">Due '.date( 'd M Y', strtotime( $task_value['due_start'] ) ).'</span>';
					if ( $task_value['progress'] == 'Completed' ) { 
						$html .= '  <span class="badge task-complete">Completed on '.date( 'd M Y', strtotime( $task_value['due_start'] ) ).'</span>';
					} elseif ( $task_value['progress'] == 'In Progress' ) {
						$html .= '  <span class="badge task-in-progress">'.esc_html__("In Progress", 'hr-management-lite' ).'</span>';
                    }
                    if ( $current_id == $task_value['staff_id'] ) {
                        $html .= '  <div class="task__options">
                                        <ul class="options-list task_edit_options">
                                            <li class="task__edit">
                                                <a class="task__edit-a task__edit-btn" href="#" data-project="'.esc_attr($task_value['project_id']).'" data-task="'.esc_attr($task_key).'">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            </li>
                                            <li class="task__delete">
                                                <a class="task__delete-a task__delete-btn" href="#" data-project="'.esc_attr($task_value['project_id']).'" data-task="'.esc_attr($task_key).'" aria-hidden="true">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>';
                    }
                    $html .= '</li>';
				}
            }

            if ( ! empty ( $projects[$key]['members'] ) ) {
                foreach ( unserialize( $projects[$key]['members'] ) as $key => $value ) {
                    $mhtml .= '<option value="'.esc_attr( $value ).'">'.esc_html( HRMLiteHelperClass::get_current_user_data( $value, 'fullname' ) ).'</option>';
                }
            }

            $return = array(
                'tasks'   => wp_kses_post( $html ),
                'members' => $mhtml,
            );

            wp_send_json( $return );
        } else {
            wp_send_json( __( 'Something went wrong.!', 'hr-management-lite' ) );
        }
        wp_die();
    }

    /** Add project tasks **/
    public static function add_tasks() {
        check_ajax_referer( 'project_ajax_nonce', 'nounce' );

        if ( isset ( $_POST['key'] ) && ! empty ( $_POST['name'] ) && ! empty ( $_POST['assign'] ) ) {
            $key        = sanitize_text_field( $_POST['key'] );
            $projects   = get_option( 'ehrm_projects_data' );
            $name       = sanitize_text_field( $_POST['name'] );
            $desc       = wp_kses_post( $_POST['desc'] );
            $due_strt   = sanitize_text_field( $_POST['due_strt'] );
            $priority   = sanitize_text_field( $_POST['priority'] );
            $assign     = array_map( 'sanitize_text_field', $_POST['assign'] );
            $assign     = serialize( $assign );
            $progress   = sanitize_text_field( $_POST['progress'] );
            $current_id = get_current_user_id();
            $date       = date( 'Y-m-d' );
            $html       = '';

            $data   = array(
                'project_id' => $key,
                'staff_id'   => get_current_user_id(),
                'name'       => $name,
                'desc'       => $desc,
                'priority'   => $priority,
                'assign'     => $assign,
                'due_start'  => $due_strt,
                'progress'   => $progress,
                'complete'   => '',
                'date'       => $date,
                'comments'   => array(),
            );

            array_push( $projects[$key]['tasks'], $data );

            if ( update_option( 'ehrm_projects_data', $projects ) ) {

                $projects = get_option( 'ehrm_projects_data' );
                foreach ( $projects[$key]['tasks'] as $task_key => $task_value ) {

					if ( ! empty ( $task_value['progress'] ) ) {
						if ( $task_value['progress'] == 'In Progress' ) {
							$status = 'in-progress';
						} elseif ( $task_value['progress'] == 'Completed' ) {
							$status = 'complete';
						}  elseif ( $task_value['progress'] == 'No Progress' ) {
							$status = 'no-progress';
						}
                    }
                    
                    if ( $name == $task_value['name'] ) {
                        HRMLiteHelperClass::send_task_detail_mails( $key, $task_key );
                    }

					$html .= '<li class="project-task-li">
			                    <a class="task-completion-status '.esc_attr( $status ).'" href="#" data-status="'.esc_attr( $task_value['progress'] ).'" data-project="'.esc_attr( $task_value['project_id'] ).'" data-task="'.esc_attr( $task_key ).'">
						  			<i class="fa fa-check-circle"></i>
						  		</a>
            					<a href="#" class="view_task_detail '.esc_attr( $status ).'" data-project="'.esc_attr( $task_value['project_id'] ).'" data-task="'.esc_attr( $task_key ).'">
            						'.esc_html( $task_value['name'] ).'
            					</a>
            					<span class="badge task-priority '.esc_html($task_value['priority']).'">'.esc_html($task_value['priority']).'</span>
						    	<span class="badge task-assign">';

                    foreach ( unserialize( $task_value['assign'] ) as $member_key => $value ) {
                        $html .= HRMLiteHelperClass::get_current_user_data( $value, 'fullname' ) . ', ';
                    }

					$html .= '  </span>
						    	<span class="badge task-duedate">Due '.date( 'd M Y', strtotime( $task_value['due_start'] ) ).'</span>';
					if ( $task_value['progress'] == 'Completed' ) { 
						$html .= '  <span class="badge task-complete">Completed on '.date( 'd M Y', strtotime( $task_value['due_start'] ) ).'</span>';
					} elseif ( $task_value['progress'] == 'In Progress' ) {
						$html .= '  <span class="badge task-in-progress">'.esc_html__("In Progress", 'hr-management-lite' ).'</span>';
					}
                    if ( $current_id == $task_value['staff_id'] ) {
                        $html .= '  <div class="task__options">
                                        <ul class="options-list task_edit_options">
                                            <li class="task__edit">
                                                <a class="task__edit-a task__edit-btn" href="#" data-project="'.esc_attr($task_value['project_id']).'" data-task="'.esc_attr($task_key).'">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            </li>
                                            <li class="task__delete">
                                                <a class="task__delete-a task__delete-btn" href="#" data-project="'.esc_attr($task_value['project_id']).'" data-task="'.esc_attr($task_key).'" aria-hidden="true">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>';
                    }
                    $html .= '</li>';
                }
                $content = wp_kses_post( $html );
                $message = __( 'Task added successfully!', 'hr-management-lite' );
                $status  = 'success';
            } else {
                $content = '';
                $message = __( 'Task not added!', 'hr-management-lite' );
                $status  = 'error';
            }

        } else {
            if ( empty ( $name ) ) {
                $message = __( 'Please enter name.!', 'hr-management-lite' );
            } elseif ( empty ( $assign ) ) {
                $message = __( 'Please select member.!', 'hr-management-lite' );
            } else {
                $message = __( 'Something went wrong.!', 'hr-management-lite' );
            }
			$content = '';
            $status  = 'error';
        }
        $return = array(
            'status'  => $status,
            'message' => $message,
            'content' => $content,
        );
        wp_send_json( $return );
		wp_die();
    }

    /** Edit Task **/
    public static function edit_tasks() {
        check_ajax_referer( 'project_ajax_nonce', 'nounce' );

        if ( isset ( $_POST['task_key'] ) && isset ( $_POST['project_key'] ) ) {
            $task_key    = sanitize_text_field( $_POST['task_key'] );
            $project_key = sanitize_text_field( $_POST['project_key'] );
            $projects    = get_option( 'ehrm_projects_data' );

            $data = array(
                'project_id' => $projects[$project_key]['tasks'][$task_key]['project_id'],
                'name'       => $projects[$project_key]['tasks'][$task_key]['name'],
                'desc'       => $projects[$project_key]['tasks'][$task_key]['desc'],
                'priority'   => $projects[$project_key]['tasks'][$task_key]['priority'],
                'assign'     => unserialize( $projects[$project_key]['tasks'][$task_key]['assign'] ),
                'due_start'  => $projects[$project_key]['tasks'][$task_key]['due_start'],
                'progress'   => $projects[$project_key]['tasks'][$task_key]['progress'],
                'complete'   => $projects[$project_key]['tasks'][$task_key]['complete'],
                'date'       => $projects[$project_key]['tasks'][$task_key]['date'],
            );
            $mhtml = '';
            if ( ! empty ( $projects[$project_key]['members'] ) ) {
                foreach ( unserialize( $projects[$project_key]['members'] ) as $key => $value ) {
                    $mhtml .= '<option value="'.esc_attr( $value ).'">'.esc_html( HRMLiteHelperClass::get_current_user_data( $value, 'fullname' ) ).'</option>';
                }
            }

            $return = array(
                'status'  => 'success',
                'data'    => $data,
                'members' => $mhtml
            );

            wp_send_json( $return );
        } else {
            wp_send_json( array( 'status' => 'error', 'message' => 'Something went wrong.!' ) );
        }
        wp_die();
    }

    /** Update project tasks **/
    public static function update_tasks() {
        check_ajax_referer( 'project_ajax_nonce', 'nounce' );

        if ( isset ( $_POST['task_key'] ) && ! empty ( $_POST['name'] ) && ! empty ( $_POST['assign'] ) ) {
            $task_key   = sanitize_text_field( $_POST['task_key'] );
            $proj_key   = sanitize_text_field( $_POST['proj_key'] );
            $projects   = get_option( 'ehrm_projects_data' );
            $name       = sanitize_text_field( $_POST['name'] );
            $desc       = wp_kses_post( $_POST['desc'] );
            $due_strt   = sanitize_text_field( $_POST['due_strt'] );
            $priority   = sanitize_text_field( $_POST['priority'] );
            $assign     = array_map( 'sanitize_text_field', $_POST['assign'] );
            $assign     = serialize( $assign );
            $progress   = sanitize_text_field( $_POST['progress'] );
            $current_id = get_current_user_id();
            $html       = '';

            $data   = array(
                'project_id' => $proj_key,
                'staff_id'   => $projects[$proj_key]['tasks'][$task_key]['staff_id'],			
                'name'       => $name,
                'desc'       => $desc,
                'priority'   => $priority,
                'assign'     => $assign,
                'due_start'  => $due_strt,
                'progress'   => $progress,
                'complete'   => $projects[$proj_key]['tasks'][$task_key]['complete'],
                'date'       => $projects[$proj_key]['tasks'][$task_key]['date'],
                'comments'   => $projects[$proj_key]['tasks'][$task_key]['comments'],
            );

            $projects[$proj_key]['tasks'][$task_key] = $data;

            if ( update_option( 'ehrm_projects_data', $projects ) ) {

                $projects = get_option( 'ehrm_projects_data' );
                foreach ( $projects[$proj_key]['tasks'] as $task_key => $task_value ) {

					if ( ! empty ( $task_value['progress'] ) ) {
						if ( $task_value['progress'] == 'In Progress' ) {
							$status = 'in-progress';
						} elseif ( $task_value['progress'] == 'Completed' ) {
							$status = 'complete';
						}  elseif ( $task_value['progress'] == 'No Progress' ) {
							$status = 'no-progress';
						}
					}

					$html .= '<li class="project-task-li">
			                    <a class="task-completion-status '.esc_attr( $status ).'" href="#" data-status="'.esc_attr( $task_value['progress'] ).'" data-project="'.esc_attr( $task_value['project_id'] ).'" data-task="'.esc_attr( $task_key ).'">
						  			<i class="fa fa-check-circle"></i>
						  		</a>
            					<a href="#" class="view_task_detail '.esc_attr( $status ).'" data-project="'.esc_attr( $task_value['project_id'] ).'" data-task="'.esc_attr( $task_key ).'">
            						'.esc_html( $task_value['name'] ).'
            					</a>
            					<span class="badge task-priority '.esc_html($task_value['priority']).'">'.esc_html($task_value['priority']).'</span>
						    	<span class="badge task-assign">';

                    foreach ( unserialize( $task_value['assign'] ) as $member_key => $value ) {
                        $html .= HRMLiteHelperClass::get_current_user_data( $value, 'fullname' ) . ', ';
                    }

					$html .= '  </span>
						    	<span class="badge task-duedate">Due '.date( 'd M Y', strtotime( $task_value['due_start'] ) ).'</span>';
					if ( $task_value['progress'] == 'Completed' ) { 
						$html .= '  <span class="badge task-complete">Completed on '.date( 'd M Y', strtotime( $task_value['due_start'] ) ).'</span>';
					} elseif ( $task_value['progress'] == 'In Progress' ) {
						$html .= '  <span class="badge task-in-progress">'.esc_html__("In Progress", 'hr-management-lite' ).'</span>';
					}
                    if ( $current_id == $task_value['staff_id'] ) {
                        $html .= '  <div class="task__options">
                                        <ul class="options-list task_edit_options">
                                            <li class="task__edit">
                                                <a class="task__edit-a task__edit-btn" href="#" data-project="'.esc_attr($task_value['project_id']).'" data-task="'.esc_attr($task_key).'">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            </li>
                                            <li class="task__delete">
                                                <a class="task__delete-a task__delete-btn" href="#" data-project="'.esc_attr($task_value['project_id']).'" data-task="'.esc_attr($task_key).'" aria-hidden="true">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>';
                    }
                    $html .= '</li>';
                }
                $content = wp_kses_post( $html );
                $message = __( 'Task updated successfully!', 'hr-management-lite' );
                $status  = 'success';
            } else {
                $content = '';
                $message = __( 'Task not updated!', 'hr-management-lite' );
                $status  = 'error';
            }

        } else {
            if ( empty ( $name ) ) {
                $message = __( 'Please enter name.!', 'hr-management-lite' );
            } elseif ( empty ( $assign ) ) {
                $message = __( 'Please select member.!', 'hr-management-lite' );
            } else {
                $message = __( 'Something went wrong.!', 'hr-management-lite' );
            }
			$content = '';
            $status  = 'error';
        }
        $return = array(
            'status'  => $status,
            'message' => $message,
            'content' => $content,
        );
        wp_send_json( $return );
		wp_die();
    }

    /** Delte project tasks **/
    public static function delete_tasks() {
        check_ajax_referer( 'project_ajax_nonce', 'nounce' );

        if ( isset ( $_POST['task_key'] ) ) {
            $task_key   = sanitize_text_field( $_POST['task_key'] );
            $proj_key   = sanitize_text_field( $_POST['proj_key'] );
            $projects   = get_option( 'ehrm_projects_data' );
            $current_id = get_current_user_id();
            $html       = '';

            unset( $projects[$proj_key]['tasks'][$task_key] );

            if ( update_option( 'ehrm_projects_data', $projects ) ) {

                $projects = get_option( 'ehrm_projects_data' );
                foreach ( $projects[$proj_key]['tasks'] as $task_key => $task_value ) {

					if ( ! empty ( $task_value['progress'] ) ) {
						if ( $task_value['progress'] == 'In Progress' ) {
							$status = 'in-progress';
						} elseif ( $task_value['progress'] == 'Completed' ) {
							$status = 'complete';
						}  elseif ( $task_value['progress'] == 'No Progress' ) {
							$status = 'no-progress';
						}
					}

					$html .= '<li class="project-task-li">
			                    <a class="task-completion-status '.esc_attr( $status ).'" href="#" data-status="'.esc_attr( $task_value['progress'] ).'" data-project="'.esc_attr( $task_value['project_id'] ).'" data-task="'.esc_attr( $task_key ).'">
						  			<i class="fa fa-check-circle"></i>
						  		</a>
            					<a href="#" class="view_task_detail '.esc_attr( $status ).'" data-project="'.esc_attr( $task_value['project_id'] ).'" data-task="'.esc_attr( $task_key ).'">
            						'.esc_html( $task_value['name'] ).'
            					</a>
            					<span class="badge task-priority '.esc_html($task_value['priority']).'">'.esc_html($task_value['priority']).'</span>
						    	<span class="badge task-assign">';

                    foreach ( unserialize( $task_value['assign'] ) as $member_key => $value ) {
                        $html .= HRMLiteHelperClass::get_current_user_data( $value, 'fullname' ) . ', ';
                    }

					$html .= '  </span>
						    	<span class="badge task-duedate">Due '.date( 'd M Y', strtotime( $task_value['due_start'] ) ).'</span>';
					if ( $task_value['progress'] == 'Completed' ) { 
						$html .= '  <span class="badge task-complete">Completed on '.date( 'd M Y', strtotime( $task_value['due_start'] ) ).'</span>';
					} elseif ( $task_value['progress'] == 'In Progress' ) {
						$html .= '  <span class="badge task-in-progress">'.esc_html__("In Progress", 'hr-management-lite' ).'</span>';
					}
                    if ( $current_id == $task_value['staff_id'] ) {
                        $html .= '  <div class="task__options">
                                        <ul class="options-list task_edit_options">
                                            <li class="task__edit">
                                                <a class="task__edit-a task__edit-btn" href="#" data-project="'.esc_attr($task_value['project_id']).'" data-task="'.esc_attr($task_key).'">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            </li>
                                            <li class="task__delete">
                                                <a class="task__delete-a task__delete-btn" href="#" data-project="'.esc_attr($task_value['project_id']).'" data-task="'.esc_attr($task_key).'" aria-hidden="true">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>';
                    }
                    $html .= '</li>';
                }
                $content = wp_kses_post( $html );
                $message = __( 'Task deleted successfully!', 'hr-management-lite' );
                $status  = 'success';
            } else {
                $content = '';
                $message = __( 'Task not deleted!', 'hr-management-lite' );
                $status  = 'error';
            }

        } else {
            $message = __( 'Something went wrong.!', 'hr-management-lite' );
            $content = '';
            $status  = 'error';
        }
        $return = array(
            'status'  => $status,
            'message' => $message,
            'content' => $content,
        );
        wp_send_json( $return );
		wp_die();
    }

    /** View task details modal **/
    public static function view_task_details() {
        check_ajax_referer( 'project_ajax_nonce', 'nounce' );

        if ( isset ( $_POST['task_key'] ) && isset ( $_POST['proj_key'] ) ) {
            $task_key    = sanitize_text_field( $_POST['task_key'] );
            $project_key = sanitize_text_field( $_POST['proj_key'] );
            $projects    = get_option( 'ehrm_projects_data' );
            $html        = '';
            $members_name = '';
            foreach ( unserialize( $projects[$project_key]['tasks'][$task_key]['assign'] ) as $member_key => $value ) {
                $members_name .= HRMLiteHelperClass::get_current_user_data( $value, 'fullname' ) . ', ';
            }

            $creator_id   = $projects[$project_key]['tasks'][$task_key]['staff_id'];
            $creator_name = HRMLiteHelperClass::get_current_user_data( $creator_id, 'fullname' );
            $created_by   = $creator_name.' on '.date( 'F d, Y', strtotime( $projects[$project_key]['tasks'][$task_key]['date'] ) );

            $html .= '<div class="item-title-group">
                            <h4 class="task-group-title">Task List in project '.esc_html__( $projects[$project_key]['name'], 'hr-management-lite' ).'</h4>
                            <h3 class="project-group-title">'.esc_html__( $projects[$project_key]['tasks'][$task_key]['name'], 'hr-management-lite' ).'</h3>
                        </div>
                        <ul class="task-two-up">
                            <li>
                                <span class="key">Created By:</span>
                                <span class="value">'.esc_html($created_by).'</span>
                            </li>
                            <li class="assigned-to">
                                <span class="key">Assigned to:</span>
                                <span class="value">'.esc_html($members_name).'</span>
                            </li>
                            <li>
                                <span class="key">Due By:</span>
                                <span class="value">'.esc_html__( $projects[$project_key]['tasks'][$task_key]['due_start'] ).'</span>
                            </li>
                            <li>
                                <span class="key">Priority:</span>
                                <span class="pr_class_0 value">'.esc_html__( $projects[$project_key]['tasks'][$task_key]['priority'] ).'</span>
                            </li>
                            <li class="progresss">
                                <span class="key">Progress:</span>
                                <span class="value">'.esc_html__( $projects[$project_key]['tasks'][$task_key]['progress'] ).'</span>
                            </li>
                        </ul>
                        <hr/>
                        <h4 class="desc_title">'.esc_html__( 'Description', 'hr-management-lite' ).'</h4>
                        <div class="desc_text">
                        '.wp_kses_post( $projects[$project_key]['tasks'][$task_key]['desc'] ).'
                        </div>
                        <hr/>
                        <h4 class="desc_title">'.esc_html__( 'Comments', 'hr-management-lite' ).'</h4>
                        <div class="task_comment_list">';

                    $comments_arr = $projects[$project_key]['tasks'][$task_key]['comments'];
                    foreach ( $comments_arr as $comment_key => $value ) {
                            if ( ! empty ( $value['date'] ) ) {
                                $datetime1 = new DateTime();
                                $datetime2 = new DateTime( $value['date'] );
                                $interval  = $datetime1->diff($datetime2);
                                $year    = $interval->format('%y years');
                                $month   = $interval->format('%m months');
                                $days    = $interval->format('%a days');
                                $hour    = $interval->format('%h hours');
                                $minutes = $interval->format('%i minutes');
                                $sec     = $interval->format('%s seconds');
                            } else {
                                $elapsed = '';
                            } 
            
                            $html .= '<div class="single-comment-detail">';
            
                            $html .= '<li class="single-comment-'.esc_html($comment_key).'">
                                        <div class="single-comment-inner-div">
                                        <span class="user_avtar">'.get_avatar( $value["useremail"], 50  ).'</span><span class="name">'.esc_html($value["fullname"]).'</span><time class="timeago">';
                            if ( $year != '0 years' ) {
                                $html .= $year.' ';
                            }
                            if ( $month != '0 months' ) {
                                $html .= $month.' ';
                            }
                            if ( $days != '0 days' ) {
                                $html .= $days.' ';
                            }
                            if ( $hour != '0 hours' ) {
                                $html .= $hour.' ';
                            }
                            if ( $minutes != '0 minutes' ) {
                                $html .= $minutes.' ';
                            }
                            if ( $sec != '0 seconds' ) {
                                $html .= $sec.' ';
                            }
                            if ( $year != '0 years' || $month != '0 months' || $days != '0 days' || $hour != '0 hours' || $minutes != '0 minutes' || $sec != '0 seconds' ) {
                                $html .= 'ago';
                            }
                            $html .= '</time>';
            
                            $current_user = wp_get_current_user();
                            if ( $current_user->ID == $value['userid'] ) {
                                $html .= '<div class="comment-options">
                                            <ul class="options-list pre_edi">
                                                <li class="comment-edit">
                                                    <a class="comment-edit-a comment-edit-btn" href="#" data-comment="'.esc_attr($comment_key).'" data-task="'.esc_attr($task_key).'" data-project="'.esc_attr($project_key).'">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                </li>
                                                <li class="comment-delete">
                                                    <a class="comment-delete-a comment-delete-btn" href="#" data-comment="'.esc_attr($comment_key).'" data-task="'.esc_attr($task_key).'" data-project="'.esc_attr($project_key).'">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>';
                            }
            
                            $html .= '</div>';
                            $html .= '<p>'.esc_html($value["comment"]).'</p>';
                            $media = unserialize( $value['media'] );
                            if ( ! empty ( $media ) ) {
                                $html .= '<div class="comment_attachments">
                                            <p class="title">'.esc_html__( 'Attachments', 'hr-management-lite' ).'</p>';
                                foreach ( $media as $media_key => $media_value ) {
                                    $html .= '<a href="'.esc_url($media_value).'" target="_blank" ><img src="'.esc_url($media_value).'" width="150px" height="150px" class="img-responsive"></a>';
                                }               
                                $html .= '</div>';
                            }
                            $html .= '</div>';
                        }
            $html .= '</div>
                        <form class="forms-sample" method="post" id="add_comment_task" autocomplete="off" enctype="multipart/form-data">
                            <div class="form-group">
                                <textarea class="form-control" rows="4" id="task_comment_desc" name="task_comment_desc"></textarea>
                            </div>

                            <input type="hidden" name="image_length" id="image_length_ehrm" class="regular-text image_url">
                            <input type="button" name="upload-btn" id="upload-btn-ehrm" class="button-secondary button" value="'.esc_html__( 'Upload Files', 'hr-management-lite' ).'">

                            <div id="myplugin-placeholder_ehrm"></div>

                            <input type="hidden" name="comment_task_key" id="comment_task_key" value="'.esc_attr( $task_key ).'">
                            <input type="hidden" name="comment_project_key" id="comment_project_key" value="'.esc_attr( $project_key ).'">
                            <input type="hidden" name="comment_coment_key" id="comment_coment_key" value="">
                            <input type="button" class="btn btn-gradient-primary mr-2" id="add_comment_task_btn" value="'.esc_html__( 'Add Comment', 'hr-management-lite' ).'">
                            <input type="button" class="btn btn-gradient-info mr-2" id="edit_comment_task_btn" value="'.esc_html__( 'Edit Comment', 'hr-management-lite' ).'">
                            <input type="button" class="btn btn-gradient-danger mr-2" id="close_comment_btn" value="'.esc_html__( 'Close', 'hr-management-lite' ).'">
                        </form>';

            wp_send_json( $html );
        } else {
            wp_send_json( __( 'Something went wrong.!', 'hr-management-lite' ) );
        }
        wp_die();
    }

    /** Add comments **/
    public static function add_comments() {
        check_ajax_referer( 'project_ajax_nonce', 'nounce' );

        if ( isset ( $_POST['task_key'] ) && isset ( $_POST['proj_key'] ) && ! empty ( $_POST['comment'] ) ) {
            $task_key = sanitize_text_field( $_POST['task_key'] );
            $proj_key = sanitize_text_field( $_POST['proj_key'] );
            $comment  = wp_kses_post( $_POST['comment'] );
            $date1    = date( "Y-m-d H:i:s" );
		    $user_id  = get_current_user_id();
            if (isset($_POST['media'])) {
                $media    = array_map( 'sanitize_text_field', $_POST['media'] );
            }else {
                $media    = '';
            }
           
		    $media    = serialize( $media );
            $projects = get_option( 'ehrm_projects_data' );
            $html     = '';

            $data   = array(				
                'comment'   => $comment,
                'userid'    => $user_id,
                'userfirst' => esc_html(HRMLiteHelperClass::get_current_user_data( $user_id, 'first_name' )),
                'userlast'  => esc_html(HRMLiteHelperClass::get_current_user_data( $user_id, 'last_name' )),
                'useremail' => esc_html(HRMLiteHelperClass::get_current_user_data( $user_id, 'user_email' )),
                'fullname'  => esc_html(HRMLiteHelperClass::get_current_user_data( $user_id, 'fullname' )),
                'date'      => $date1,
                'media'     => $media,
            );

            array_push( $projects[$proj_key]['tasks'][$task_key]['comments'], $data );

            if ( update_option( 'ehrm_projects_data', $projects ) ) {
                $poject       = get_option( 'ehrm_projects_data' );
                $comments_arr = $poject[$proj_key]['tasks'][$task_key]['comments'];
                foreach ( $comments_arr as $key => $value ) {
                    if ( ! empty ( $value['date'] ) ) {
                        $datetime1 = new DateTime();
                        $datetime2 = new DateTime($value['date']);
                        $interval  = $datetime1->diff($datetime2);
                        $year      = $interval->format('%y years');
                        $month     = $interval->format('%m months');
                        $days      = $interval->format('%a days');
                        $hour      = $interval->format('%h hours');
                        $minutes   = $interval->format('%i minutes');
                        $sec                                              = $interval->format('%s seconds');
                    } else {
                        $elapsed = '';
                    } 
    
                    $html .= '<div class="single-comment-detail">';
    
                    $html .= '<li class="single-comment-'.esc_html($key).'">
                                <div class="single-comment-inner-div">
                                <span class="user_avtar">'.get_avatar( $value["useremail"], 50  ).'</span><span class="name">'.esc_html($value["fullname"]).'</span><time class="timeago">';
                    if ( $year != '0 years' ) {
                        $html .= $year.' ';
                    }
                    if ( $month != '0 months' ) {
                        $html .= $month.' ';
                    }
                    if ( $days != '0 days' ) {
                        $html .= $days.' ';
                    }
                    if ( $hour != '0 hours' ) {
                        $html .= $hour.' ';
                    }
                    if ( $minutes != '0 minutes' ) {
                        $html .= $minutes.' ';
                    }
                    if ( $sec != '0 seconds' ) {
                        $html .= $sec.' ';
                    }
                    if ( $year != '0 years' || $month != '0 months' || $days != '0 days' || $hour != '0 hours' || $minutes != '0 minutes' || $sec != '0 seconds' ) {
                        $html .= 'ago';
                    }
                    $html .= '</time>';
    
                    $current_user = wp_get_current_user();
                    if ( $current_user->ID == $value['userid'] ) {
                        $html .= '<div class="comment-options">
                                    <ul class="options-list pre_edi">
                                        <li class="comment-edit">
                                            <a class="comment-edit-a comment-edit-btn" href="#" data-comment="'.esc_attr($key).'" data-task="'.esc_attr($task_key).'" data-project="'.esc_attr($proj_key).'">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        </li>
                                        <li class="comment-delete">
                                            <a class="comment-delete-a comment-delete-btn" href="#" data-comment="'.esc_attr($key).'" data-task="'.esc_attr($task_key).'" data-project="'.esc_attr($proj_key).'">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>';
                    }
    
                    $html .= '</div>';
                    $html .= '<p>'.esc_html($value["comment"]).'</p>';
                    $media = unserialize( $value['media'] );
                    if ( ! empty ( $media ) ) {
                        $html .= '<div class="comment_attachments">
                                    <p class="title">'.esc_html__( 'Attachments', 'hr-management-lite' ).'</p>';
                        foreach ( $media as $media_key => $media_value ) {
                            $html .= '<a href="'.esc_url($media_value).'" target="_blank" ><img src="'.esc_url($media_value).'" width="150px" height="150px" class="img-responsive"></a>';
                        }               
                        $html .= '</div>';
                    }
                    $html .= '</div>';
                }
                $content = wp_kses_post( $html );
                $message = __( 'Comment added successfully!', 'hr-management-lite' );
                $status  = 'success';
            } else {
                $content = '';
                $message = __( 'Comment not added!', 'hr-management-lite' );
                $status  = 'error';
            }

        } else {
            if ( empty ( $_POST['comment'] ) ) {
                $message = esc_html__( 'Please enter something in comment box.!', 'hr-management-lite' );
            } else {
                $message = esc_html__( 'Something went wrong.!', 'hr-management-lite' );
            }
            
            $content = '';
            $status  = 'error';
        }
        $return = array(
            'status'  => $status,
            'message' => $message,
            'content' => $content,
        );
        wp_send_json( $return );
		wp_die();
    }

    /** Edit Comment **/
    public static function edit_comments() {
        check_ajax_referer( 'project_ajax_nonce', 'nounce' );

        if ( isset ( $_POST['task_key'] ) && isset ( $_POST['proj_key'] ) ) {
            $task_key    = sanitize_text_field( $_POST['task_key'] );
            $proj_key    = sanitize_text_field( $_POST['proj_key'] );
            $comment_key = sanitize_text_field( $_POST['comment_key'] );
            $projects    = get_option( 'ehrm_projects_data' );

            $data = array(
                'comment' => $projects[$proj_key]['tasks'][$task_key]['comments'][$comment_key]['comment'],
                'media'   => unserialize( $projects[$proj_key]['tasks'][$task_key]['comments'][$comment_key]['media'] )
            );

            wp_send_json( $data );
        } else {
            wp_send_json( __( 'Something went wrong.!', 'hr-management-lite' ) );
        }
        wp_die();
    }

    /** Update comments **/
    public static function update_comments() {
        check_ajax_referer( 'project_ajax_nonce', 'nounce' );

        if ( isset ( $_POST['task_key'] ) && isset ( $_POST['proj_key'] ) && ! empty ( $_POST['comment'] ) && isset ( $_POST['coment_key'] ) ) {
            $task_key   = sanitize_text_field( $_POST['task_key'] );
            $proj_key   = sanitize_text_field( $_POST['proj_key'] );
            $coment_key = sanitize_text_field( $_POST['coment_key'] );
            $comment    = wp_kses_post( $_POST['comment'] );
            $media      = array_map( 'sanitize_text_field', $_POST['media'] );
		    $media      = serialize( $media );
            $projects   = get_option( 'ehrm_projects_data' );
            $html       = '';

            $data   = array(				
                'comment'   => $comment,
                'userid'    => $projects[$proj_key]['tasks'][$task_key]['comments'][$coment_key]['userid'],
                'userfirst' => $projects[$proj_key]['tasks'][$task_key]['comments'][$coment_key]['userfirst'],
                'userlast'  => $projects[$proj_key]['tasks'][$task_key]['comments'][$coment_key]['userlast'],
                'useremail' => $projects[$proj_key]['tasks'][$task_key]['comments'][$coment_key]['useremail'],
                'fullname'  => $projects[$proj_key]['tasks'][$task_key]['comments'][$coment_key]['fullname'],
                'date'      => $projects[$proj_key]['tasks'][$task_key]['comments'][$coment_key]['date'],
                'media'     => $media,
            );

            $projects[$proj_key]['tasks'][$task_key]['comments'][$coment_key] = $data;

            if ( update_option( 'ehrm_projects_data', $projects ) ) {
                $poject       = get_option( 'ehrm_projects_data' );
                $comments_arr = $poject[$proj_key]['tasks'][$task_key]['comments'];
                foreach ( $comments_arr as $key => $value ) {
                    if ( ! empty ( $value['date'] ) ) {
                        $datetime1 = new DateTime();
                        $datetime2 = new DateTime($value['date']);
                        $interval  = $datetime1->diff($datetime2);
                        $year    = $interval->format('%y years');
                        $month   = $interval->format('%m months');
                        $days    = $interval->format('%a days');
                        $hour    = $interval->format('%h hours');
                        $minutes = $interval->format('%i minutes');
                        $sec     = $interval->format('%s seconds');
                    } else {
                        $elapsed = '';
                    } 
    
                    $html .= '<div class="single-comment-detail">';
    
                    $html .= '<li class="single-comment-'.$key.'">
                                <div class="single-comment-inner-div">
                                <span class="user_avtar">'.get_avatar( $value["useremail"], 50  ).'</span><span class="name">'.esc_html($value["fullname"]).'</span><time class="timeago">';
                    if ( $year != '0 years' ) {
                        $html .= $year.' ';
                    }
                    if ( $month != '0 months' ) {
                        $html .= $month.' ';
                    }
                    if ( $days != '0 days' ) {
                        $html .= $days.' ';
                    }
                    if ( $hour != '0 hours' ) {
                        $html .= $hour.' ';
                    }
                    if ( $minutes != '0 minutes' ) {
                        $html .= $minutes.' ';
                    }
                    if ( $sec != '0 seconds' ) {
                        $html .= $sec.' ';
                    }
                    if ( $year != '0 years' || $month != '0 months' || $days != '0 days' || $hour != '0 hours' || $minutes != '0 minutes' || $sec != '0 seconds' ) {
                        $html .= 'ago';
                    }
                    $html .= '</time>';
    
                    $current_user = wp_get_current_user();
                    if ( $current_user->ID == $value['userid'] ) {
                        $html .= '<div class="comment-options">
                                    <ul class="options-list pre_edi">
                                        <li class="comment-edit">
                                            <a class="comment-edit-a comment-edit-btn" href="#" data-comment="'.esc_attr($key).'" data-task="'.esc_attr($task_key).'" data-project="'.esc_attr($proj_key).'">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        </li>
                                        <li class="comment-delete">
                                            <a class="comment-delete-a comment-delete-btn" href="#" data-comment="'.esc_attr($key).'" data-task="'.esc_attr($task_key).'" data-project="'.esc_attr($proj_key).'">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>';
                    }
    
                    $html .= '</div>';
                    $html .= '<p>'.esc_html($value["comment"]).'</p>';
                    $media = unserialize( $value['media'] );
                    if ( ! empty ( $media ) ) {
                        $html .= '<div class="comment_attachments">
                                    <p class="title">'.esc_html__( 'Attachments', 'hr-management-lite' ).'</p>';
                        foreach ( $media as $media_key => $media_value ) {
                            $html .= '<a href="'.esc_url($media_value).'" target="_blank" ><img src="'.esc_url($media_value).'" width="150px" height="150px" class="img-responsive"></a>';
                        }               
                        $html .= '</div>';
                    }
                    $html .= '</div>';
                }
                $content = wp_kses_post( $html );
                $message = __( 'Comment updated successfully!', 'hr-management-lite' );
                $status  = 'success';
            } else {
                $content = '';
                $message = __( 'Comment not updated!', 'hr-management-lite' );
                $status  = 'error';
            }

        } else {
            if ( empty ( $comment) ) {
                $message = __( 'Please enter something in comment box.!', 'hr-management-lite' );
            } else {
                $message = __( 'Something went wrong.!', 'hr-management-lite' );
            }
            
            $content = '';
            $status  = 'error';
        }
        $return = array(
            'status'  => $status,
            'message' => $message,
            'content' => $content,
        );
        wp_send_json( $return );
		wp_die();
    }

    /** Delete comments **/
    public static function delete_comments() {
        check_ajax_referer( 'project_ajax_nonce', 'nounce' );

        if ( isset ( $_POST['task_key'] ) && isset ( $_POST['proj_key'] ) && isset ( $_POST['comment_key'] ) ) {
            $task_key   = sanitize_text_field( $_POST['task_key'] );
            $proj_key   = sanitize_text_field( $_POST['proj_key'] );
            $coment_key = sanitize_text_field( $_POST['comment_key'] );
            $projects   = get_option( 'ehrm_projects_data' );
            $html       = '';

            unset( $projects[$proj_key]['tasks'][$task_key]['comments'][$coment_key] );

            if ( update_option( 'ehrm_projects_data', $projects ) ) {
                $poject       = get_option( 'ehrm_projects_data' );
                $comments_arr = $poject[$proj_key]['tasks'][$task_key]['comments'];
                foreach ( $comments_arr as $key => $value ) {
                    if ( ! empty ( $value['date'] ) ) {
                        $datetime1 = new DateTime();
                        $datetime2 = new DateTime($value['date']);
                        $interval  = $datetime1->diff($datetime2);
                        $year    = $interval->format('%y years');
                        $month   = $interval->format('%m months');
                        $days    = $interval->format('%a days');
                        $hour    = $interval->format('%h hours');
                        $minutes = $interval->format('%i minutes');
                        $sec     = $interval->format('%s seconds');
                    } else {
                        $elapsed = '';
                    } 
    
                    $html .= '<div class="single-comment-detail">';
    
                    $html .= '<li class="single-comment-'.esc_html($key).'">
                                <div class="single-comment-inner-div">
                                <span class="user_avtar">'.get_avatar( $value["useremail"], 50  ).'</span><span class="name">'.esc_html($value["fullname"]).'</span><time class="timeago">';
                    if ( $year != '0 years' ) {
                        $html .= $year.' ';
                    }
                    if ( $month != '0 months' ) {
                        $html .= $month.' ';
                    }
                    if ( $days != '0 days' ) {
                        $html .= $days.' ';
                    }
                    if ( $hour != '0 hours' ) {
                        $html .= $hour.' ';
                    }
                    if ( $minutes != '0 minutes' ) {
                        $html .= $minutes.' ';
                    }
                    if ( $sec != '0 seconds' ) {
                        $html .= $sec.' ';
                    }
                    if ( $year != '0 years' || $month != '0 months' || $days != '0 days' || $hour != '0 hours' || $minutes != '0 minutes' || $sec != '0 seconds' ) {
                        $html .= 'ago';
                    }
                    $html .= '</time>';
    
                    $current_user = wp_get_current_user();
                    if ( $current_user->ID == $value['userid'] ) {
                        $html .= '<div class="comment-options">
                                    <ul class="options-list pre_edi">
                                        <li class="comment-edit">
                                            <a class="comment-edit-a comment-edit-btn" href="#" data-comment="'.esc_attr($key).'" data-task="'.esc_attr($task_key).'" data-project="'.esc_attr($proj_key).'">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        </li>
                                        <li class="comment-delete">
                                            <a class="comment-delete-a comment-delete-btn" href="#" data-comment="'.esc_attr($key).'" data-task="'.esc_attr($task_key).'" data-project="'.esc_attr($proj_key).'">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>';
                    }
    
                    $html .= '</div>';
                    $html .= '<p>'.esc_html($value["comment"]).'</p>';
                    $media = unserialize( $value['media'] );
                    if ( ! empty ( $media ) ) {
                        $html .= '<div class="comment_attachments">
                                    <p class="title">'.esc_html__( 'Attachments', 'hr-management-lite' ).'</p>';
                        foreach ( $media as $media_key => $media_value ) {
                            $html .= '<a href="'.esc_url($media_value).'" target="_blank" ><img src="'.esc_url($media_value).'" width="150px" height="150px" class="img-responsive"></a>';
                        }               
                        $html .= '</div>';
                    }
                    $html .= '</div>';
                }
                $content = wp_kses_post( $html );
                $message = esc_html__( 'Comment deleted successfully!', 'hr-management-lite' );
                $status  = 'success';
            } else {
                $content = '';
                $message = esc_html__( 'Comment not deleted!', 'hr-management-lite' );
                $status  = 'error';
            }

        } else {
            $message = __( 'Something went wrong.!', 'hr-management-lite' );
            $content = '';
            $status  = 'error';
        }
        $return = array(
            'status'  => $status,
            'message' => $message,
            'content' => $content,
        );
        wp_send_json( $return );
		wp_die();
    }

}

?>
