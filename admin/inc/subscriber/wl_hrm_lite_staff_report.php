<?php
defined('ABSPATH') or die();
require_once( WL_HRML_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-hrm-lite-helper.php' );
$staffs = HRMLiteHelperClass::ehrm_get_staffs_list();
$months = HRMLiteHelperClass::ehrm_month_filter();
?>
<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                    <i class="fas fa-network-wired"></i>
                </span>
                <?php esc_html_e( 'Reports', 'hr-management-lite' ); ?>
            </h3>
            <nav aria-label="breadcrumb" class="report">
                <form method="post" id="report_form" action="">
                    <ul class="breadcrumb">
                        <input type="hidden" name="report_staff_id" id="report_staff_id" value="<?php echo esc_attr( get_current_user_id() ); ?>" />
                        <li class="breadcrumb-item" aria-current="page">
                            <select class="form-control" id="report_month" name="report_month">
                                <optgroup label="<?php esc_html_e('Select Any Filter ( individual Months )', 'hr-management-lite'); ?>">
                                    <?php foreach ( $months as $key => $month ) { ?>
                                        <option value="<?php echo esc_attr( $key + 1 ); ?>"><?php esc_html_e( $month, 'hr-management-lite' ); ?></option>
                                    <?php } ?>
                                </optgroup>
				                <optgroup label="Select Any Filter ( Combine Months )">
                                    <option value="<?php echo esc_attr('14'); ?>"><?php esc_html_e( 'Previous Three Month', 'hr-management-lite' );?></option>
                                    <option value="<?php echo esc_attr('15'); ?>"><?php esc_html_e( 'Previous Six Month', 'hr-management-lite' );?></option>
                                    <option value="<?php echo esc_attr('16'); ?>"><?php esc_html_e( 'Previous Nine Month', 'hr-management-lite' );?></option>
                                    <option value="<?php echo esc_attr('17'); ?>"><?php esc_html_e( 'Previous One Year', 'hr-management-lite' );?></option>
                                </optgroup>
                            </select>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <select class="form-control" id="report_type" name="report_type">
                                <option value="<?php echo esc_attr('all'); ?>"><?php esc_html_e( 'All Days', 'hr-management-lite' ); ?></option>
                                <option value="<?php echo esc_attr('attend'); ?>"><?php esc_html_e( 'Only Attend days', 'hr-management-lite' ); ?></option>
                                <option value="<?php echo esc_attr('absent'); ?>"><?php esc_html_e( 'Only Absent Days', 'hr-management-lite' ); ?></option>
                            </select>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <button type="button" class="btn btn-block btn-lg btn-gradient-primary custom-btn" id="report_form_btn">
                                <i class="fas fa-network-wired"></i> <?php esc_html_e( 'Generate Report', 'hr-management-lite' ); ?>
                            </button>
                        </li>
                    </ul>
                </form>
            </nav>
        </div>
        <div class="row report_row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card table_card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="table_expand_row">
                                <button class="btn btn-sm btn-gradient-success custom-btn" id="btn-show-all-children" type="button"><?php esc_html_e( 'Expand All', 'hr-management-lite' ); ?></button>
                                <button class="btn btn-sm btn-gradient-danger custom-btn" id="btn-hide-all-children" type="button"><?php esc_html_e( 'Collapse All', 'hr-management-lite' ); ?></button>
                            </div>
                            <table id="report_table" class="table table-striped report_table" cellspacing="0" style="width:100%">
                                <thead>
                                    <tr>
                                        <th><?php esc_html_e( 'Date', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Name', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Day', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Office In', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Office Out', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Working Hours', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'IP', 'hr-management-lite' ); ?></th>
                                        <th class="none"><?php esc_html_e( 'Other Details', 'hr-management-lite' ); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="report_tbody">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><?php esc_html_e( 'No data', 'hr-management-lite' ); ?></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th><?php esc_html_e( 'Date', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Name', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Day', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Office In', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Office Out', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Working Hours', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'IP', 'hr-management-lite' ); ?></th>
                                        <th class="none"><?php esc_html_e( 'Other Details', 'hr-management-lite' ); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="download_report_csv">
            <div class="col-lg-6 grid-margin stretch-card" id="report_salary_result"></div>
            <div class="col-lg-6 grid-margin stretch-card" id="csv_form_div">
            </div>
        </div>
        
    </div>
</div>