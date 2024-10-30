/* Staff Admin End Ajax Scripts */
jQuery(document).ready(function () {
    'use strict';

    var table = jQuery('#task_staff_table').DataTable({
        'responsive': true,
        'destroy': true,
        'order': [],
    });

    /** Staff dashboard clock **/
    setInterval(function () {
        var currentUtcTime1 = new Date(); // This is in UTC
        var currentDateTimeCentralTimeZone1 = new Date(currentUtcTime1.toLocaleString('en-US', { timeZone: ajax_staff.ehrm_timezone }));
        var hours   = currentDateTimeCentralTimeZone1.getHours();
        jQuery(".hours").html((hours < 10 ? "0" : "") + hours);
    }, 1000);
    setInterval(function () {
        var currentUtcTime2 = new Date(); // This is in UTC
        var currentDateTimeCentralTimeZone2 = new Date(currentUtcTime2.toLocaleString('en-US', { timeZone: ajax_staff.ehrm_timezone }));
        var minutes = currentDateTimeCentralTimeZone2.getMinutes();
        jQuery(".min").html((minutes < 10 ? "0" : "") + minutes);
    }, 1000);
    setInterval(function () {
        var currentUtcTime3 = new Date(); // This is in UTC
        var currentDateTimeCentralTimeZone3 = new Date(currentUtcTime3.toLocaleString('en-US', { timeZone: ajax_staff.ehrm_timezone }));
        var seconds = currentDateTimeCentralTimeZone3.getSeconds();
        jQuery(".sec").html((seconds < 10 ? "0" : "") + seconds);
    }, 1000);

	/* Action on Clock btns */
    jQuery(document).on('click', '.clock-action-btn', function (e) {
    	e.preventDefault();
    	var value    = jQuery( this ).attr( 'data-value' );
    	var timezone = jQuery( this ).attr( 'data-timezone' );
		var nounce = ajax_staff.staff_nonce;
       
        jQuery.ajax({
            url: ajax_staff.ajax_url,
            type: 'POST',
            data: {
                action: 'hrm_clock_action',
                timezone: timezone,
                value: value,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
					if (response.status == 'success') {
						toastr.success( response.message );
                		location.reload();
                	} else {
                		toastr.error( response.message );
					}
					//location.reload();
                }
                //location.reload();
            }
        });
	});

    /* Submit Late reson */
    jQuery(document).on('click', '#late_reson_submit_btn', function (e) {
    	e.preventDefault();
    	let reson    = jQuery( '#late_reson_form #late_resonn' ).val();
    	let staff_id = jQuery( '#late_reson_form #staff_id' ).val();
    	let nounce   = ajax_staff.staff_nonce;
        jQuery.ajax({
            url: ajax_staff.ajax_url,
            type: 'POST',
            data: {
                action: 'hrm_late_reson_action',
                staff_id: staff_id,
                reson: reson,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                    console.log(response);
                	if ( response == 'Updated' ) {
                		jQuery('#LateReson').modal('hide');
                		jQuery('#late_reson_btn').remove();
                	} else {
                		toastr.error( response );
                	}           	
                }
            }
        });
    });

    /* Submit Daily Report */
    jQuery(document).on('click', '#daily_report_btn', function (e) {
    	e.preventDefault();
    	var report   = jQuery( '#daily_report_form #daily_report' ).val();
    	var staff_id = jQuery( '#daily_report_form #user_id' ).val();
    	var nounce   = ajax_staff.staff_nonce;
        jQuery.ajax({
            url: ajax_staff.ajax_url,
            type: 'POST',
            data: {
                action: 'hrm_daily_report_action',
                staff_id: staff_id,
                report: report,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                	if ( response == 'Updated' ) {
	                	jQuery('#DailyReport').modal('hide');
	                	jQuery('#daily_reportbtn').remove();
	                } else {
                		toastr.error( response );
                	} 
                }
            }
        });
    });

    /** Staff panel late reason and dashboard modal */
    jQuery('#late_reson_btn').click(function(e) {
        e.preventDefault();      
        jQuery('#LateReson').modal('show');
    });
    jQuery('#daily_reportbtn').click(function(e) {
        e.preventDefault();     
        jQuery('#DailyReport').modal('show');
    });
});