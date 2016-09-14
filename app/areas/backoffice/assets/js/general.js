jQuery(document).ready(function(){	
	// External Link
	jQuery('a[rel="external"]').click(function(){
        window.open( jQuery(this).attr('href') );
        return false;
    });

	// Print
	jQuery('a[rel="print"]').click(function() {
		window.print();
		return false;
	});
	
	// Generate a random password for users
	jQuery("#generate_password").change(function() {
		var generate = jQuery(this).prop('checked');
		if(generate == true){
			jQuery.post(
				'/backoffice/users/generate-random-password/',
				{generate: 1},
				function(data) {
					jQuery("#generated_password").html(data);
					var new_pw = jQuery("#new_pw").val();
					jQuery("#password").val(new_pw).trigger('keyup');
					jQuery("#password_again").val(new_pw);
				}
			);
		}else{
			var new_pw = '';
			jQuery("#generated_password").html('');
			jQuery("#new_pw").val(new_pw);
			jQuery("#password").val(new_pw).trigger('keyup');
			jQuery("#password_again").val(new_pw);
		}
	});

	//Hide or Show Users logo Image based on user type selected
	if(jQuery('#type').val() == 2){
		jQuery('#letting-image').show();
	} else{
		jQuery('#letting-image').hide();
	}

	jQuery("#type").change(function(event){
		if(jQuery('#type').val() == 2){
			jQuery('#letting-image').show();
		} else{
			jQuery('#letting-image').hide();
		}
	});


	
	// Add region select based on country select
	jQuery("#location_country").change(function(event){
		var country_id = jQuery(this).val();
		jQuery.post(
			'/backoffice/users/regions/',
			{country_id: country_id},
			function(data) {
				if(data) {
					jQuery("#location_region").removeClass("hide");
					jQuery("#location_region").html(data);
				} else {
					jQuery("#location_region").html("");
					jQuery("#location_region").addClass("hide");
				}
			}
		);
	});
	
	// Change property type
	jQuery(".change_property_type").change(function(event){
		var property_type = jQuery(this).val(),
			property_id = jQuery(this).next("#property_id").val();
		jQuery.post(
			'/backoffice/properties/update-property-type/',
			{property_id: property_id, property_type: property_type}
		);
		jQuery(this).parent().append("<span class='label label-success'>Property Type Updated</span>");
		jQuery(this).parent().find(".label").delay(1800).fadeOut('slow');
	});
	
	// Change property status
	jQuery(".change_property_status").change(function(event){
		var property_status = jQuery(this).val(),
			property_id = jQuery(this).next("#property_id").val();
		jQuery.post(
			'/backoffice/properties/update-property-status/',
			{property_id: property_id, property_status: property_status}
		);
		jQuery(this).parent().append("<span class='label label-success'>Property Status Updated</span>");
		jQuery(this).parent().find(".label").delay(1800).fadeOut('slow');
	});

	//Adding more images to a blog post
    jQuery("#template-add-room").click(function(){
        var current =  jQuery(this).data('id');

        jQuery.post(
			'/backoffice/rooms/getRooms/',
			{current: current},
			function(data) {
				if(data) {
					jQuery("#template-add-room").parent().before(data);
			        removeRooms();
				} else {
				}
			}
		);
        current = current+1;
        jQuery(this).data('id', current);
    })

    // Remove room if remove room is selected
    jQuery(".remove-room").click(function(){
    	var current =  jQuery(this).data('id');
    	jQuery("#room-group_"+current).remove();

	})

	function removeRooms(){
		jQuery(".remove-room").off();
		jQuery(".remove-room").click(function(){
	    	var current =  jQuery(this).data('id');
	    	jQuery("#room-group_"+current).remove();
		})
	}

});