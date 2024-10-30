/* Admin End Scripts */
jQuery(document).ready(function () {
    'use strict';

    /* Upload Logo for mail html  */
    jQuery(document).on( 'click', '#upload-btn-ehrm', function (e) {
        e.preventDefault();
        var button = this;
        var image  = wp.media({
            title: 'Upload Image',
            multiple: false
        }).open().on('select', function (e) {
            var uploaded_image = image.state().get('selection').first();
            var location_image = uploaded_image.toJSON().url;
            jQuery('#logo_image_mail').val(location_image);
        });
    });

    /***-----------------------------------------------------------Designation-----------------------------------------------------------***/

	/* Add Designation details */
    jQuery(document).on('click', '#save-cleaner-pro', function (e) {
    	e.preventDefault();
    	var from_name    = jQuery( '#pro_select_from #from_name' ).val();
    	var from_address = jQuery( '#pro_select_from #from_address' ).val();
    	var logo_image   = jQuery( '#pro_select_from #logo_image_mail' ).val();
    	var footer_txt   = jQuery( '#pro_select_from #footer_txt' ).val();

    	var nounce = ajax_notification.notification_nonce;
        jQuery.ajax({
            url: ajax_notification.ajax_url,
            type: 'POST',
            data: {
                action: 'hrm_email_options_ajax',
                from_name: from_name,
                from_address: from_address,
                logo_image: logo_image,
                footer_txt: footer_txt,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                	if ( response.status == 'error' ) {
                		toastr.error(response.message);
                	} else {
                        toastr.success(response.message);
                	}   
                }
            }
        });
    });

    jQuery(document).on('click', '.email_template_settings', function (e) {
        e.preventDefault();
        var value = jQuery( this ).attr('data-value');
        var name  = jQuery( this ).attr('data-name');

    	var nounce = ajax_notification.notification_nonce;
        jQuery.ajax({
            url: ajax_notification.ajax_url,
            type: 'POST',
            data: {
                action: 'hrm_email_options_data',
                value: value,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                	if ( response.status == 'error' ) {
                		toastr.error(response.message);
                    } else {       
                        jQuery('#ShoeEmailOptions').modal('show');
                        console.log(response);
                        jQuery('#email_modal_options #email_subject').val(response.content.subject);
                        jQuery('#email_modal_options #email_heading').val(response.content.heading);
                        jQuery('#email_modal_options #email_id_name').val(value);
                        jQuery('#email_modal_options #email_template_tags').val(response.content.tags);
                        jQuery('.email_template_tags').text(response.content.tags);
                        jQuery('#ShoeEmailOptions h4').text(name);
                        tinyMCE.get('email_body').setContent(response.content.body);
                	}   
                }
            }
        });
    });

    jQuery(document).on('click', '#update_email_options', function (e) {
    	e.preventDefault();
    	var subject = jQuery( '#email_modal_options #email_subject' ).val();
    	var heading = jQuery( '#email_modal_options #email_heading' ).val();
    	var name    = jQuery( '#email_modal_options #email_id_name' ).val();
    	var tags    = jQuery( '#email_modal_options #email_template_tags' ).val();
    	var body    = tinyMCE.get('email_body').getContent();

    	var nounce = ajax_notification.notification_nonce;
        jQuery.ajax({
            url: ajax_notification.ajax_url,
            type: 'POST',
            data: {
                action: 'hrm_save_email_options_ajax',
                subject: subject,
                heading: heading,
                body: body,
                name: name,
                tags: tags,
                nounce: nounce,
            },
            success: function ( response ) {
                if ( response ) {
                	if ( response.status == 'error' ) {
                		toastr.error(response.message);
                	} else {
                        toastr.success(response.message);
                        jQuery('#ShoeEmailOptions').modal('hide');
                	}   
                }
            }
        });
    });

});