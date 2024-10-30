<?php
defined( 'ABSPATH' ) or die();
require_once( WL_HRML_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-hrm-lite-helper.php' );

/**
 *  Ajax Action calls for Reports menu
 */
class LiteReportAjaxAction {

    /** Generate reports **/
    public static function get_reports() {
        check_ajax_referer( 'report_ajax_nonce', 'nounce' );

        if ( isset( $_POST['staff_id'] ) && isset( $_POST['month'] ) && isset( $_POST['type'] ) ) {
            $staff_id     = sanitize_text_field( $_POST['staff_id'] );
            $month        = sanitize_text_field( $_POST['month'] );
            $type         = sanitize_text_field( $_POST['type'] );
            $attendences  = get_option( 'ehrm_staff_attendence_data' );
            $staffs_data  = get_option( 'ehrm_staffs_data' );
            $save_setting = get_option( 'ehrm_settings_data' );
            $all_holidays = HRMLiteHelperClass::ehrm_all_holidays();
            $all_dates    = HRMLiteHelperClass::get_all_dates_reports( $month );
            $Reports      = array();
            $date_arr     = array();
            $off_days     = HRMLiteHelperClass::get_offdays();
            $user_role    = HRMLiteHelperClass::ehrm_get_current_user_roles();

            /** Staff's data **/
            if ( ! empty( $staffs_data ) ) {
                foreach ( $staffs_data as $staff_key => $staffs ) {
                    if ( $staffs['ID'] == $staff_id ) {
                        $fullname = $staffs['fullname'];
                    }
                }
            }

            foreach ( $all_dates as $date_key => $date ) {
                if ( ! empty( $attendences ) && ( $type == 'all' || $type == 'attend' ) ) {
                    foreach ( $attendences as $key => $attendence ) {
                        if ( $attendence['date'] == $date && $attendence['staff_id'] == $staff_id && ! empty( $attendence['office_in'] ) ) {
                            
                            $late          = $attendence['late'];
                            $working_hours = HRMLiteHelperClass::ehrm_daily_working_hours( $staff_id, $date );

                            if ( ! empty ( $attendence['office_in']  ) ) {
                                $office_in = date( HRMLiteHelperClass::get_time_format(), strtotime( $attendence['office_in'] ) );
                            } else {
                                $office_in = '---';
                            }

                            if ( ! empty ( $attendence['office_out']  ) ) {
                                $office_out = date( HRMLiteHelperClass::get_time_format(), strtotime( $attendence['office_out'] ) );
                            } else {
                                $office_out = '---';
                            }

                            if ( ! empty ( $attendence['lunch_in']  ) ) {
                                $lunch_in = date( HRMLiteHelperClass::get_time_format(), strtotime( $attendence['lunch_in'] ) );
                            } else {
                                $lunch_in = '---';
                            }

                            if ( ! empty ( $attendence['lunch_out']  ) ) {
                                $lunch_out = date( HRMLiteHelperClass::get_time_format(), strtotime( $attendence['lunch_out'] ) );
                            } else {
                                $lunch_out = '---';
                            }

                            if ( ! empty ( $attendence['late_reson']  ) ) {
                                $late_reson = $attendence['late_reson'];
                            } else {
                                $late_reson = '---';
                            }

                            if ( $late == 1 ) {
                                $late_status = 'Late';
                            } else {
                                $late_status = 'On Time';
                            }
                            
                            $html = '<div class="report_other_detail">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table id="report_table" class="table table-striped report_table" cellspacing="0" style="width:100%">
                                                    <tbody class="inner_report_body">
                                                        <tr>
                                                            <td>'.esc_html__( 'Lunch In', 'hr-management-lite' ).'</td>
                                                            <td class="right-td">'.esc_html( $lunch_in ).'</td>
                                                        </tr>
                                                        <tr>
                                                            <td>'.esc_html__( 'Lunch Out', 'hr-management-lite' ).'</td>
                                                            <td class="right-td">'.esc_html( $lunch_out ).'</td>
                                                        </tr>
                                                        <tr>
                                                            <td>'.esc_html__( 'Punctuality', 'hr-management-lite' ).'</td>
                                                            <td class="right-td">'.esc_html__( $late_status, 'hr-management-lite' ).'</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table id="report_table" class="table table-striped report_table" cellspacing="0" style="width:100%">
                                                    <tbody class="inner_report_body">
                                                        <tr>
                                                            <td>'.esc_html__( 'Location', 'hr-management-lite' ).'</td>
                                                            <td class="right-td">'.esc_html( $attendence['location'] ).'</td>
                                                        </tr>
                                                        <tr>
                                                            <td>'.esc_html__( 'Late Reason', 'hr-management-lite' ).'</td>
                                                            <td class="right-td">'.esc_html__( $attendence['late_reson'], 'hr-management-lite' ).'</td>
                                                        </tr>
                                                        <tr>
                                                            <td>'.esc_html__( 'Daily Report', 'hr-management-lite' ).'</td>
                                                            <td class="right-td">'.esc_html__( $attendence['report'] , 'hr-management-lite' ).'</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>';
                            if ( $user_role == 'administrator' ) {
                                $html .= '<button class="btn btn-sm btn-gradient-success custom-btn edit_report_btn" data-report="'.esc_attr( $key ).'" data-staffid="'.esc_attr( $staff_id ).'">
                                            '.esc_html__( 'Edit', 'hr-management-lite' ).'
                                        </button>';
                            }
                            $html .= '</div>
                                    </div>';
                            $data = array( date( HRMLiteHelperClass::get_date_format(), strtotime( $date ) ), $fullname, esc_html__( date( 'l', strtotime( $date ) ) ), esc_html( $office_in ) , esc_html( $office_out ), esc_html( $working_hours ), esc_html( $attendence['id_address'] ), wp_kses_post( $html ) );
                            array_push( $Reports, $data );
                            array_push( $date_arr, $date );
                        }
                    }
                }
                if ( $type == 'absent' || $type == 'all' ) {
                    if ( ! in_array( $date, $date_arr ) ) {
                         if ( in_array( date( 'l', strtotime( $date ) ), $off_days ) ) {

                            $data = array( date( HRMLiteHelperClass::get_date_format(), strtotime( $date ) ), $fullname, esc_html__( date( 'l', strtotime( $date ) ), 'hr-management-lite'  ), esc_html__( 'Day Off', 'hr-management-lite' ), esc_html__( 'Day Off', 'hr-management-lite' ), esc_html__( 'Day Off', 'hr-management-lite' ), esc_html__( 'Day Off', 'hr-management-lite' ), esc_html__( 'Day Off', 'hr-management-lite' ), esc_html__( 'Day Off', 'hr-management-lite' ) );
                            array_push( $Reports, $data );

                        } elseif ( ! in_array( date( 'l', strtotime( $date ) ), $off_days ) && in_array( $date, $all_holidays ) ) {

                            $data = array( date( HRMLiteHelperClass::get_date_format(), strtotime( $date ) ), esc_html__( HRMLiteHelperClass::ehrm_get_holiday_name( $date ), 'hr-management-lite'  ), esc_html__( date( 'l', strtotime( $date ) ), 'hr-management-lite'  ), esc_html__( 'Holiday', 'hr-management-lite' ), esc_html__( 'Holiday', 'hr-management-lite' ), esc_html__( 'Holiday', 'hr-management-lite' ), esc_html__( 'Holiday', 'hr-management-lite' ), esc_html__( 'Holiday', 'hr-management-lite' ), '' );
                            array_push( $Reports, $data );

                        } else {

                            $data = array( date( HRMLiteHelperClass::get_date_format(), strtotime( $date ) ), $fullname, esc_html__( date( 'l', strtotime( $date ) ), 'hr-management-lite'  ), esc_html__( 'Staff', 'hr-management-lite' ), esc_html__( 'absent', 'hr-management-lite' ), esc_html__( 'on', 'hr-management-lite' ), esc_html__( 'this day', 'hr-management-lite' ), esc_html__( 'No', 'hr-management-lite' ), esc_html__( 'details', 'hr-management-lite' ) );
                            array_push( $Reports, $data );

                        }
                    }
                }
            }
            wp_send_json( $Reports );

        } else {
			wp_send_json( __( 'Something went wrong.!', 'hr-management-lite' ) );
		}
		wp_die();
    }

    /* Display selected user salary for selected month */
    public static function display_salary() {
        check_ajax_referer( 'report_ajax_nonce', 'nounce' );

        if ( isset( $_POST['staff_id'] ) && isset( $_POST['month'] ) && isset( $_POST['type'] ) ) {
            $staff_id    = sanitize_text_field ( $_POST['staff_id'] );
            $month       = sanitize_text_field ( $_POST['month'] );
            $savesetting = get_option( 'ehrm_settings_data' );
            $staffs_data = get_option('ehrm_staffs_data');
            $html        = '';

            /** Staff's data **/
            if ( ! empty( $staffs_data ) ) {
                foreach ( $staffs_data as $staff_key => $staffs ) {
                    if ( $staffs['ID'] == $staff_id ) {
                        $fullname    = $staffs['fullname'];
                        $salary      = $staffs['salary'];
                        $shift_start = $staffs['shift_start'];
                        $shift_end   = $staffs['shift_end'];
                        $all_leaves  = unserialize( $staffs['leave_value'] );
                        $size        = sizeof( $all_leaves );
                        $leaves      = 0;

                        for ( $i = 0; $i < $size; $i++ ) {
                            $leaves = (int)$leaves + (int)$all_leaves[$i];
                        }

                        $dteStart 	    = new DateTime( $shift_start );
                        $dteEnd   	    = new DateTime( $shift_end );
                        $dteDiff  	    = $dteStart->diff( $dteEnd );
                        $EstWorkingHour = $dteDiff->format( "%H" );
                    }
                }
            }

            if ( ! empty ( $savesetting ) && isset( $savesetting['salary_method'] ) ) {
                $salary_method = $savesetting['salary_method'];
            } else {
                $salary_method = 'Monthly';
            }

            if ( $month == '1' ) {
                $first = date( "Y-m-01" );
                $last  = date( "Y-m-t", strtotime( $first ) );            
            } elseif ( $month == '2' ) {
                $first = date( "Y-m-01", strtotime( "-1 month" ) );
                $last  = date( "Y-m-t", strtotime( $first ) );              
            }  elseif ( $month == '3' ) {
                $first = date( "Y-m-01", strtotime( "-2 month" ) );
                $last  = date( "Y-m-t", strtotime( $first ) );              
            }  elseif ( $month == '4' ) {
                $first = date( "Y-m-01", strtotime( "-3 month" ) );
                $last  = date( "Y-m-t", strtotime( $first ) );          
            }  elseif ( $month == '5' ) {
                $first = date( "Y-m-01", strtotime( "-4 month" ) );
                $last  = date( "Y-m-t", strtotime( $first ) );     
            }  elseif ( $month == '6' ) {
                $first = date( "Y-m-01", strtotime( "-5 month" ) );
                $last  = date( "Y-m-t", strtotime( $first ) );         
            }  elseif ( $month == '7' ) {
                $first = date( "Y-m-01", strtotime( "-6 month" ) );
                $last  = date( "Y-m-t", strtotime( $first ) );        
            }  elseif ( $month == '8' ) {
                $first = date( "Y-m-01", strtotime( "-7 month" ) );
                $last  = date( "Y-m-t", strtotime( $first ) );       
            }  elseif ( $month == '9' ) {
                $first = date( "Y-m-01", strtotime( "-8 month" ) );
                $last  = date( "Y-m-t", strtotime( $first ) );           
            }  elseif ( $month == '10' ) {
                $first = date( "Y-m-01", strtotime( "-9 month" ) );
                $last  = date( "Y-m-t", strtotime( $first ) );    
            }  elseif ( $month == '11' ) {
                $first = date( "Y-m-01", strtotime( "-10 month" ) );
                $last  = date( "Y-m-t", strtotime( $first ) );         
            }  elseif ( $month == '12' ) {
                $first = date( "Y-m-01", strtotime( "-11 month" ) );
                $last  = date( "Y-m-t", strtotime( $first ) );        
            }  elseif ( $month == '13' ) {
                $first = date( "Y-m-01", strtotime( "-12 month" ) );
                $last  = date( "Y-m-t", strtotime( $first ) );     
            } elseif ( $month == '14' ) {
                $first = date( "Y-m-01", strtotime( "-3 month" ) );
                $last  = date( "Y-m-t", strtotime( $first ) );
                $last  = date( "Y-m-d", strtotime( "+2 month", strtotime( $last ) ) );      
            } elseif ( $month == "15" ) {
                $first = date( "Y-m-01", strtotime( "-6 month" ) );
                $last  = date( "Y-m-t", strtotime( $first ) );
                $last  = date( "Y-m-d", strtotime( "+5 month", strtotime( $last ) ) );    
            } elseif ( $month == "16" ) {
                $first = date( "Y-m-01", strtotime( "-9 month" ) );
                $last  = date( "Y-m-t", strtotime( $first ) );
                $last  = date( "Y-m-d", strtotime( "+8 month", strtotime( $last ) ) );     
            } elseif(  $month == "17" ) {
                $first = date( "Y-m-01", strtotime( "-12 month" ) );
                $last  = date( "Y-m-t", strtotime( $first ) );
                $last  = date( "Y-m-d", strtotime( "+11 month", strtotime( $last ) ) ); 
            }

            $full_working_days = HRMLiteHelperClass::full_working_days( $first, $last );
            $half_working_days = HRMLiteHelperClass::half_working_days( $first, $last );
            $total_absents     = HRMLiteHelperClass::ehrm_total_absents( $staff_id, $first, $last );
            $total_absents     = sizeof( $total_absents['dates1'] );
            $total_presents    = HRMLiteHelperClass:: ehrm_total_attendance_count( $staff_id, $first, $last );
            $currency_symbl    = $savesetting['cur_symbol'];

            /** Halfday working hours **/
            $dteStart 	 = new DateTime( $savesetting['halfday_start'] );
            $dteEnd   	 = new DateTime( $savesetting['halfday_end'] );
            $dteDiff  	 = $dteStart->diff( $dteEnd );
            $HalfDayHour = $dteDiff->format( "%H" );

            $total_working_hours = HRMLiteHelperClass::get_staff_total_working_hours( $staff_id, $first, $last );

            /** Salary html **/
            if ( $salary_method == 'Monthly' ) {

                /* Total Working Days */
                $total_working_days = ( $full_working_days + $half_working_days ) - $leaves;

                /* Per day salary */
                $PerDaySalary   = $salary / $total_working_days;

                /* Salary upto current date */
                $CurrentSalary  = $PerDaySalary * $total_presents;

                /* Unpaid salary upto current date */
                $UnpaidSalary = $PerDaySalary * $total_absents;
            
                $html = '<div class="card table_card">
                            <div class="card-body salary_status_ul">
                            <h4 class="card-title">'.esc_html__( 'Salary status', 'hr-management-lite' ).'</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td>'.esc_html__( 'Your Monthly Salary' ).'</td>
                                                <td class="right-td">'.HRMLiteHelperClass::get_currency_position_html( $salary ).'</td>
                                            </tr>
                                            <tr>
                                                <td>'.esc_html__( 'Total Working days', 'hr-management-lite' ). '</td>
                                                <td class="right-td"><span class="info-value-span">' . esc_html( $total_working_days ) . ' ' . esc_html__( 'Days', 'hr-management-lite' ) . '</span></td>
                                            </tr>
                                            <tr>
                                                <td>'.esc_html__( 'Per day salary' ).'</td>
                                                <td class="right-td">' . esc_html( HRMLiteHelperClass::get_currency_position_html( round( $PerDaySalary ) ) ) . '</td>
                                            </tr>
                                            <tr>
                                                <td>'.esc_html__( 'Total absent days' ).'</td>
                                                <td class="right-td">' . esc_html( $total_absents ) . ' '.esc_html__( 'Days', 'hr-management-lite' ).'</td>
                                            </tr>
                                            <tr>
                                                <td>'.esc_html__( 'Total present days' ).'</td>
                                                <td class="right-td">' . esc_html( $total_presents ) . ' '.esc_html__( 'Days', 'hr-management-lite' ).'</td>
                                            </tr>
                                            <tr class="final-result-tr">
                                                <td>' . esc_html__( 'Total Paid Salary as per total attendance', 'hr-management-lite') . ' [ ' . esc_html( round( $PerDaySalary ) ) . ' X ' . esc_html( $total_presents ) . ' ]</td>
                                                <td class="right-td">' . esc_html( HRMLiteHelperClass::get_currency_position_html( round( $CurrentSalary ) ) ) . '</td>
                                            </tr>                                         
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>';
            } else {

                $TotalEstimateTime = ( $EstWorkingHour * $full_working_days ) + ( $HalfDayHour * $half_working_days );
                $TotalEstimateTime = $TotalEstimateTime - ( $leaves * $EstWorkingHour );
                $PerHourSalary     = number_format( (float) $salary / $TotalEstimateTime, 2, '.', '' );
                $TotalWorkingHours = HRMLiteHelperClass::get_staff_total_working_hours( $staff_id, $first, $last );

                $html = '<div class="card table_card">
                            <div class="card-body salary_status_ul">
                            <h4 class="card-title">'.esc_html__( 'Salary status', 'hr-management-lite' ).'</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td>'.esc_html__( 'Your Salary' ).'</td>
                                                <td class="right-td">'.esc_html(HRMLiteHelperClass::get_currency_position_html( $salary )).'</td>
                                            </tr>
                                            <tr>
                                                <td>' . esc_html__( 'Estimate total time', 'hr-management-lite' ) . '</td>
                                                <td class="right-td">' . esc_html( $TotalEstimateTime ) . ' ' . esc_html__( 'Hours', 'hr-management-lite' ) . '</td>
                                            </tr>
                                            <tr>
                                                <td>'.esc_html__( 'Per hour salary' ).'</td>
                                                <td class="right-td">' . esc_html( HRMLiteHelperClass::get_currency_position_html( $PerHourSalary ) ) . '</td>
                                            </tr>
                                            <tr>
                                                <td>'.esc_html__( 'Total working hours' ).'</td>
                                                <td class="right-td">'.esc_html( $TotalWorkingHours ).' ' . esc_html__( 'Hours', 'hr-management-lite' ) . '</td>
                                            </tr>
                                            <tr class="final-result-tr">
                                                <td>'.esc_html__( 'Your Calculated Salary according to your working hours.!', 'hr-management-lite' ).'</td>
                                                <td class="right-td">'.esc_html( HRMLiteHelperClass::get_currency_position_html( round( $PerHourSalary * $TotalWorkingHours ) ) ).'</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>';
            }
            $status  = 'success';
            $message = esc_html__( 'Report and salary calulated for '.$fullname, 'hr-management-lite' );
            $content = wp_kses_post( $html );    
        } else {
            $status  = 'error';
            $message = __( 'Something went wrong.!', 'hr-management-lite' );
            $content = '';
        }
        
        $return = array(
            'status'  => $status,
            'message' => $message,
            'content' => $content,
            'first'   => $first,
            'last'    => $last,
        );
        wp_send_json( $return );
		wp_die();
    }

    /* Edit Reports Action Call */
	public static function edit_reports() {
		check_ajax_referer( 'report_ajax_nonce', 'nounce' );

		if ( isset ( $_POST['key'] ) && isset ( $_POST['staffid'] ) ) {
			$key      = sanitize_text_field( $_POST['key'] );
			$staff_id = sanitize_text_field( $_POST['staffid'] );
            $reports  = get_option( 'ehrm_staff_attendence_data' );
            $staffs   = get_option( 'ehrm_staffs_data' );
            
            /** Staff's data **/
            if ( ! empty( $staffs ) ) {
                foreach ( $staffs as $staff_key => $staffs ) {
                    if ( $staffs['ID'] == $staff_id ) {
                        $fullname  = $staffs['fullname'];
                    }
                }
            }

            if ( $reports[$key]['late'] == 1 ) {
                $late_status = 'Late';
            } else {
                $late_status = 'On Time';
            }

            if ( ! empty ( $reports[$key]['office_in']  ) ) {
                $office_in = date( HRMLiteHelperClass::get_time_format(), strtotime( $reports[$key]['office_in'] ) );
            } else {
                $office_in = '';
            }

            if ( ! empty ( $reports[$key]['office_out']  ) ) {
                $office_out = date( HRMLiteHelperClass::get_time_format(), strtotime( $reports[$key]['office_out'] ) );
            } else {
                $office_out = '';
            }

            if ( ! empty ( $reports[$key]['lunch_in']  ) ) {
                $lunch_in = date( HRMLiteHelperClass::get_time_format(), strtotime( $reports[$key]['lunch_in'] ) );
            } else {
                $lunch_in = '';
            }

            if ( ! empty ( $reports[$key]['lunch_out']  ) ) {
                $lunch_out = date( HRMLiteHelperClass::get_time_format(), strtotime( $reports[$key]['lunch_out'] ) );
            } else {
                $lunch_out = '';
            }

             if ( ! empty ( $reports[$key]['late_reson']  ) ) {
                $late_reson = $reports[$key]['late_reson'];
            } else {
                $late_reson = '';
            }

			$data = array(
				'name'         => $fullname,
				'date'         => date( HRMLiteHelperClass::get_date_format(), strtotime( $reports[$key]['date'] ) ),
				'office_in'    => $office_in,
				'office_out'   => $office_out,
				'lunch_in'     => $lunch_in,
				'lunch_out'    => $lunch_out,
				'punctual'     => $late_status,
				'working_hour' => $reports[$key]['working_hour'],
				'late'         => $reports[$key]['late'],
                'late_reson'  => $reports[$key]['late_reson'],
				'report'       => $reports[$key]['report'],
			);
			wp_send_json( $data );

		} else {
			wp_send_json( esc_html__( 'Something went wrong.!', 'hr-management-lite' ) );
		}
		wp_die();
    }
    
    /* Update Reports Action Call */
	public static function update_reports() {
		check_ajax_referer( 'report_ajax_nonce', 'nounce' );

		if ( isset ( $_POST['report_key'] ) && isset ( $_POST['staff_id'] ) ) {

			$report_key   = sanitize_text_field( $_POST['report_key'] );
			$staff_id     = sanitize_text_field( $_POST['staff_id'] );
			$office_in    = sanitize_text_field( $_POST['office_in'] );
			$office_out   = sanitize_text_field( $_POST['office_out'] );
			$lunch_in     = sanitize_text_field( $_POST['lunch_in'] );
			$lunch_out    = sanitize_text_field( $_POST['lunch_out'] );
			$punctual     = sanitize_text_field( $_POST['punctual'] );
			$working_hour = sanitize_text_field( $_POST['working_hour'] );
			$late         = sanitize_text_field( $_POST['late'] );
            $late_reson   = sanitize_text_field( $_POST['late_reson'] );
			$report       = sanitize_text_field( $_POST['report'] );
            $reports      = get_option( 'ehrm_staff_attendence_data' );

            if ( $punctual == 'Late' ) {
                $late = 1;
            } else {
                $late = 0;
            }

			$data = array(
                'staff_id'     => $staff_id,
                'name'         => $reports[$report_key]['name'],
                'email'        => $reports[$report_key]['email'],
                'office_in'    => $office_in,
                'office_out'   => $office_out,
                'lunch_in'     => $lunch_in,
                'lunch_out'    => $lunch_out,
                'late'         => $late,
                'late_reson'   => $late_reson,
                'report'       => $report,
                'working_hour' => $working_hour,
                'date'         => $reports[$report_key]['date'],
                'timestamp'    => $reports[$report_key]['timestamp'],
                'id_address'   => $reports[$report_key]['id_address'],
                'location'     => $reports[$report_key]['location'],
            );
            $reports[$report_key] = $data;

            if ( update_option( 'ehrm_staff_attendence_data', $reports ) ) {
                $return = array(
                    'message' => esc_html__( 'Report edit successfully!', 'hr-management-lite' )
                );
            } else {
                $return = array(
                    'message' => esc_html__( 'Something went wrong!', 'hr-management-lite' )
                );
            }

		} else {
			$return = array(
                'message' => esc_html__( 'Something went wrong!', 'hr-management-lite' )
            );
        }
        wp_send_json( $return );
		wp_die();
    }
    
    /* Update Reports Action Call */
	public static function download_reports() {

        if( isset( $_POST['csv_download_btn'] ) ) {

            if ( empty( $_POST['report_action'] ) || 'export_settings' != $_POST['report_action'] )
                return;

            if ( ! wp_verify_nonce( $_POST['report_export_nonce'], 'report_export_nonce' ) )
                return;

            $start        = sanitize_text_field( $_POST['download_strt'] );
            $end          = sanitize_text_field( $_POST['download_to'] );
            $staff_id     = sanitize_text_field( $_POST['csv_user_id'] );
            $type         = sanitize_text_field( $_POST['csv_report_type'] );
            $month        = sanitize_text_field( $_POST['csv_report_month'] );
            $attendences  = get_option( 'ehrm_staff_attendence_data' );
            $staffs_data  = get_option('ehrm_staffs_data');
            $save_setting = get_option( 'ehrm_settings_data' );
            $all_holidays = HRMLiteHelperClass::ehrm_all_holidays();
            $all_dates    = HRMLiteHelperClass::ehrm_get_date_range( $start, $end );
            $off_days     = HRMLiteHelperClass::get_offdays();
            $date_arr     = array();

            /** Staff's data **/
            if ( ! empty( $staffs_data ) ) {
                foreach ( $staffs_data as $staff_key => $staffs ) {
                    if ( $staffs['ID'] == $staff_id ) {
                        $fullname = $staffs['fullname'];
                    }
                }
            }

            if ( $type == 'all' ) { 
                $file_suffix = "All-days";
            } elseif ( $type == 'attend' ) { 
                $file_suffix = "Only-Attend-days";
            } elseif ( $type == 'absent' ) { 
                $file_suffix = "Only-absent-days";
            }

            $file_text = $fullname.'-'.date( 'm-Y', strtotime( $start ) ).'-'.date( 'm-Y', strtotime( $end ) ).'-'.$file_suffix;

            ignore_user_abort( true );
            nocache_headers();
            header('Content-Type: text/csv');
            header( 'Content-Disposition: inline; filename='.$file_text.'-'. date( 'm-d-Y' ) . '.csv' );
            header( "Expires: 0" );

            echo esc_html("No., Name, Date, Day, Office In, Office Out, Lunch In, Lunch Out, Work Hours, Ip, Location, Puncuality, Late reason, Daily report \r\n");

            foreach ( $all_dates as $date_key => $date ) {
                if ( ! empty( $attendences ) && ( $type == 'all' || $type == 'attend' ) ) {
                    foreach ( $attendences as $key => $attendence ) {
                        if ( $attendence['date'] == $date && $attendence['staff_id'] == $staff_id && ! empty( $attendence['office_in'] ) ) {
                            
                            $late = $attendence['late'];

                            if ( $late == 1 ) {
                                $late_status = 'Late';
                            } else {
                                $late_status = 'On Time';
                            }

                            $working_hours = HRMLiteHelperClass::ehrm_daily_working_hours( $staff_id, $month, $date );

                            echo esc_html( $date_key ).', '. esc_html( $fullname ).', '. date( HRMLiteHelperClass::get_date_format(), strtotime( $date ) ).', '. esc_html__( date( 'l', strtotime( $date ) ) ).', '. date( HRMLiteHelperClass::get_time_format(), strtotime( $attendence['office_in'] ) ).', '. date( HRMLiteHelperClass::get_time_format(), strtotime( $attendence['office_out'] ) ).', '. date( HRMLiteHelperClass::get_time_format(), strtotime( $attendence['lunch_in'] ) ).', '. date( HRMLiteHelperClass::get_time_format(), strtotime( $attendence['lunch_out'] ) ).', '. $working_hours.', '. $attendence['id_address'].', '. $attendence['location'].', '. $late_status.', '. $attendence['late'].', '. $attendence['late_reson'].', '. $attendence['report']." \r\n";

                            array_push( $date_arr, $date );
                        }
                    }
                }
                if ( $type == 'absent' || $type == 'all' ) {
                    if ( ! in_array( $date, $date_arr ) ) {
                        if ( in_array( date( 'l', strtotime( $date ) ), $off_days ) ) {

                            echo esc_html( $date_key ).', '. esc_html( $fullname ).', '. esc_html(date( HRMLiteHelperClass::get_date_format(), strtotime( $date ) )), esc_html__( date( 'l', strtotime( $date ) ) ).', '. esc_html__( 'Sunday', 'hr-management-lite' ).', '. esc_html__( 'Sunday', 'hr-management-lite' ).', '. esc_html__( 'Sunday', 'hr-management-lite' ).', '. esc_html__( 'Sunday', 'hr-management-lite' ).', '. esc_html__( 'Sunday', 'hr-management-lite' ).', '. esc_html__( 'Sunday', 'hr-management-lite' ).', '. esc_html__( 'Sunday', 'hr-management-lite' ).', '. esc_html__( 'Sunday', 'hr-management-lite' ).', '. esc_html__( 'Sunday', 'hr-management-lite' ).', '. esc_html__( 'Sunday', 'hr-management-lite' )." \r\n";

                        } elseif ( ! in_array( date( 'l', strtotime( $date ) ), $off_days ) && in_array( $date, $all_holidays ) ) {

                            echo esc_html( $date_key ).', '. esc_html( $fullname ).', '. esc_html(date( HRMLiteHelperClass::get_date_format(), strtotime( $date ) )).', '. esc_html__( date( 'l', strtotime( $date ) ) ).', '. HRMLiteHelperClass::ehrm_get_holiday_name( $date ).', '. esc_html__( 'Holiday', 'hr-management-lite' ).', '. esc_html__( 'Holiday', 'hr-management-lite' ).', '. esc_html__( 'Holiday', 'hr-management-lite' ).', '. esc_html__( 'Holiday', 'hr-management-lite' ).', '. esc_html__( 'Holiday', 'hr-management-lite' ).', '. esc_html__( 'Holiday', 'hr-management-lite' ).', '. esc_html__( 'Holiday', 'hr-management-lite' ).', '. esc_html__( 'Holiday', 'hr-management-lite' ).', '. esc_html__( 'Holiday', 'hr-management-lite' )." \r\n";

                        } else {

                            echo esc_html( $date_key ).', '. esc_html( $fullname ).', '. esc_html(date( HRMLiteHelperClass::get_date_format(), strtotime( $date ) )).', '. esc_html__( date( 'l', strtotime( $date ) ) ).', '. esc_html__( 'Staff', 'hr-management-lite' ).', '. esc_html__( 'absent', 'hr-management-lite' ).', '. esc_html__( 'on', 'hr-management-lite' ).', '. esc_html__( 'this', 'hr-management-lite' ).', '. esc_html__( 'day', 'hr-management-lite' ).', '. esc_html__( 'No', 'hr-management-lite' ).', '. esc_html__( 'details', 'hr-management-lite' ).', '. esc_html__( 'found', 'hr-management-lite' ).', '. esc_html__( 'for', 'hr-management-lite' ).', '. esc_html__( 'this day.', 'hr-management-lite' )." \r\n";

                        }
                    }
                }
            }
            exit;
        }
	}
}

?>