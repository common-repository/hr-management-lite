/* Reports End Scripts */
jQuery(document).ready( function() {
    'use strict';

    /** Edit Office in field **/
    jQuery('#edit_office_in').timepicker({format: 'h:MM TT'});

    /** Edit Office in field **/
    jQuery('#edit_office_out').timepicker({format: 'h:MM TT'});

    /** Edit lunch in field **/
    jQuery('#edit_lunch_in').timepicker({format: 'h:MM TT'});

    /** Edit lunch out field **/
    jQuery('#edit_lunch_out').timepicker({format: 'h:MM TT'});

    /** Generate report **/
    jQuery(document).on('click', '#report_form_btn', function (e) {
        e.preventDefault();
        var staff_id  = jQuery( '#report_form #report_staff_id' ).val();
    	var month     = jQuery( '#report_form #report_month' ).val();
        var type      = jQuery( '#report_form #report_type' ).val();
        var nounce    = ajax_report.report_nonce;
        
        if ( staff_id == undefined || staff_id.length == 0 ) {
            toastr.error( 'Please Select Staff Member.' );
            return false;
        }

        jQuery.ajax({
            url: ajax_report.ajax_url,
            type: 'POST',
            data: {
                action: 'hrm_get_reports_action',
                staff_id: staff_id,
                month: month,
                type: type,
                nounce: nounce
            },
            success: function ( response ) {
                if (response) {
                    if ( response != 'Something went wrong.!' ) {
                        jQuery('.report_tbody').empty();
                        var table = jQuery('#report_table').DataTable({
                            'responsive': true,
                            'destroy': true,
                            'order': [],
                            'data': response
                        });

                        // Handle click on "Expand All" button
                        jQuery('#btn-show-all-children').on('click', function () {
                            // Expand row details
                            table.rows(':not(.parent)').nodes().to$().find('td:first-child').trigger('click');
                        });

                        // Handle click on "Collapse All" button
                        jQuery('#btn-hide-all-children').on('click', function () {
                            // Collapse row details
                            table.rows('.parent').nodes().to$().find('td:first-child').trigger('click');
                        });

                        // Find indexes of rows which have `Yes` in the second column
                        var indexes1 = table.rows().eq(0).filter(function (rowIdx) {
                            return table.cell(rowIdx, 2).data() === 'Sunday' ? true : false;
                        });
                        
                        // Add a class to those rows using an index selector
                        table.rows(indexes1)
                            .nodes()
                            .to$()
                            .addClass('sunday_report');
                        
                        // Find indexes of rows which have `Yes` in the second column
                        var indexes2 = table.rows().eq(0).filter(function (rowIdx) {
                            return table.cell(rowIdx, 4).data() === 'absent' ? true : false;
                        });
                        
                        // Add a class to those rows using an index selector
                        table.rows(indexes2)
                            .nodes()
                            .to$()
                            .addClass('absent_report');
                        
                        // Find indexes of rows which have `Yes` in the second column
                        var indexes3 = table.rows().eq(0).filter(function (rowIdx) {
                            return table.cell(rowIdx, 3).data() === 'Holiday' ? true : false;
                        });
                        
                        // Add a class to those rows using an index selector
                        table.rows(indexes3)
                            .nodes()
                            .to$()
                            .addClass('holiday_report');
                        
                        /** To display salary total amount action call **/
                        jQuery.ajax({
                            url: ajax_backend.ajax_url,
                            type: 'POST',
                            data: {
                                action: 'hrm_show_salary_action',
                                staff_id: staff_id,
                                month: month,
                                type: type,
                                nounce: nounce,
                            },
                            success: function ( response ) {
                                if ( response ) {
                                    if ( response.status == 'error' ) {
                                        toastr.error(response.message);
                                    } else {
                                        toastr.success(response.message);
                                        jQuery( '#report_salary_result' ).empty();
                                        jQuery('#report_salary_result').append(response.content);
                                        jQuery( '#download_strt' ).val(response.first);
                                        jQuery( '#download_to' ).val(response.last);
                                        jQuery('#csv_user_id').val(staff_id);
                                        jQuery( '#csv_report_type' ).val(type);
                                        jQuery( '#csv_report_month' ).val(month);
                                        jQuery( '#csv_form_div' ).show();
                                    }   
                                }
                            }
                        });
                        /** End of display salary total amount action call **/
                    } else {
                        toastr.error(response); 
                    }
                }
            }
        });
    });

    /* Edit Report details */
    jQuery(document).on('click', '.edit_report_btn', function (e) {
    	e.preventDefault();
    	var key      = jQuery(this).attr('data-report');
    	var staffid  = jQuery(this).attr('data-staffid');
    	var nounce   = ajax_report.report_nonce;
        jQuery.ajax({
            url: ajax_report.ajax_url,
            type: 'POST',
            data: {
                action: 'hrm_edit_report_action',
                key: key,
                staffid: staffid,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                	if ( response == 'Something went wrong.!' ) {
                		toastr.error(response);
                	} else {
                        jQuery('#EditReports').modal('show');
                        jQuery( '#edit_report_form #edit_name' ).val(response.name);
                        jQuery( '#edit_report_form #edit_date' ).val(response.date);
                        jQuery( '#edit_report_form #edit_office_in' ).val(response.office_in);                   
                        jQuery( '#edit_report_form #edit_office_out' ).val(response.office_out);
                        jQuery( '#edit_report_form #edit_lunch_in' ).val(response.lunch_in);
                        jQuery( '#edit_report_form #edit_lunch_out' ).val(response.lunch_out);
                        jQuery( '#edit_report_form #edit_report_punctual' ).val(response.punctual);
                        jQuery( '#edit_report_form #edit_working_hours' ).val(response.working_hour);
                        jQuery( '#edit_report_form #edit_late' ).val(response.late);
                        jQuery( '#edit_report_form #edit_work' ).val(response.report);
                        jQuery( '#edit_report_form #edit_staff_id' ).val(staffid);
                        jQuery( '#edit_report_form #report_key' ).val(key);
                	}   
                }
            }
        });
    });

    /* Update Report details */
    jQuery(document).on('click', '#edit_report_btn', function (e) {
    	e.preventDefault();
    	var name         = jQuery( '#edit_report_form #edit_name' ).val();
        var date         = jQuery( '#edit_report_form #edit_date' ).val();
        var office_in    = jQuery( '#edit_report_form #edit_office_in' ).val();                   
        var office_out   = jQuery( '#edit_report_form #edit_office_out' ).val();
        var lunch_in     = jQuery( '#edit_report_form #edit_lunch_in' ).val();
        var lunch_out    = jQuery( '#edit_report_form #edit_lunch_out' ).val();
        var punctual     = jQuery( '#edit_report_form #edit_report_punctual' ).val();
        var working_hour = jQuery( '#edit_report_form #edit_working_hours' ).val();
        var late         = jQuery( '#edit_report_form #edit_late' ).val();
        var report       = jQuery( '#edit_report_form #edit_work' ).val();
        var staff_id     = jQuery( '#edit_report_form #edit_staff_id' ).val();
        var report_key   = jQuery( '#edit_report_form #report_key' ).val();
    	var nounce       = ajax_report.report_nonce;
        jQuery.ajax({
            url: ajax_report.ajax_url,
            type: 'POST',
            data: {
                action: 'hrm_update_report_action',
                name: name,
                date: date,
                office_in: office_in,
                office_out: office_out,
                lunch_in: lunch_in,
                lunch_out: lunch_out,
                punctual: punctual,
                working_hour: working_hour,
                late: late,
                report: report,
                staff_id: staff_id,
                report_key: report_key,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                	if ( response.message == 'Something went wrong!' ) {
                		toastr.error(response.message);
                    } else {
                        toastr.success(response.message);
                        jQuery('#EditReports').modal('hide');
                        location.reload();
                	}   
                }
            }
        });
    });
});