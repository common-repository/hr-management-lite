jQuery(document).ready(function() {
    'use strict';

    /** Event **/
    jQuery('#event_date').datetimepicker({
        format: 'LT',
        format: 'YYYY-MM-DD',
        autoclose: true
    });
    
    jQuery('#holiday_start').datetimepicker({
        format: 'LT',
        format: 'YYYY-MM-DD',
        autoclose: true
    });
    
    jQuery('#holiday_to').datetimepicker({
        format: 'LT',
        format: 'YYYY-MM-DD',
        autoclose: true
    });

    jQuery('#edit_holiday_start').datetimepicker({
        format: 'LT',
        format: 'YYYY-MM-DD',
        autoclose: true
    });
    
    jQuery('#edit_holiday_to').datetimepicker({
        format: 'LT',
        format: 'YYYY-MM-DD',
        autoclose: true
    });

    /**
     * Leave datefilter
     */
     jQuery('#datefilter').datetimepicker({
        format: 'LT',
        format: 'YYYY-MM-DD',
        autoclose: true
    });
    jQuery('#event_time').timepicker({format: 'h:MM TT', autoclose: true});
    jQuery('#edit_event_date').datetimepicker({
        format: 'LT',
        format: 'YYYY-MM-DD',
        autoclose: true
    });
    jQuery('#edit_event_time').timepicker({format: 'h:MM TT', autoclose: true});

    /* Shift */
    jQuery('#shift_start').timepicker({format: 'h:MM TT', autoclose: true});
    jQuery('#shift_end').timepicker({format: 'h:MM TT', autoclose: true});
    jQuery('#shift_late').timepicker({format: 'h:MM TT', autoclose: true});
    jQuery('#edit_shift_start').timepicker({format: 'h:MM TT', autoclose: true});
    jQuery('#edit_shift_end').timepicker({format: 'h:MM TT', autoclose: true});
    jQuery('#edit_shift_late').timepicker({ format: 'h:MM TT', autoclose: true });
    
    /* Settings */
    jQuery('#halfday_start').timepicker({format: 'h:MM TT', autoclose: true});
    jQuery('#halfday_end').timepicker({format: 'h:MM TT', autoclose: true});
    jQuery('#lunch_start').timepicker({format: 'h:MM TT', autoclose: true});
    jQuery('#lunch_end').timepicker({ format: 'h:MM TT', autoclose: true });

});
