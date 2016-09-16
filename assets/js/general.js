jQuery(document).ready(function(){
    // Generate a random password for users
	jQuery("#generate_password").change(function() {
		var generate = jQuery(this).prop('checked');
		if(generate == true){
			jQuery.post(
				'/users/generate-random-password/',
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

    //Close alert when close is pressed
    $('.alert-danger .close').click(function(){;
        $('.alert-danger .close').parent().hide();
    })

    //Adding more images to a blog post
    jQuery("#template-add-room").click(function(){
        var current =  jQuery(this).data('id');

        jQuery.post(
			'/templates/getRooms/',
			{current: current},
			function(data) {
				if(data) {
					jQuery("#template-add-room").parent().before(data);
			        removeRooms();
			        addItems();
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

	// Add a new input box for the items
	jQuery(".add-items").click(function(){
    	var currentItem =  jQuery(this).data('id');
    	var html = '<div class="form-group ">';
	    		html += '<label class="col-md-3 control-label">Additional Item</label>';
	    		html += '<div class="col-md-4">';
		    		html += '<input type="text" name="items['+currentItem+'][]" class="form-control">';
				html += '</div>';
    	html += '</div>';

    	jQuery('#room-group_'+currentItem).after(html);
    	//jQuery("#room-group_"+current).remove();
	})

	function addItems(){
		jQuery(".add-items").off();
		jQuery(".add-items").click(function(){
	    	var currentItem =  jQuery(this).data('id');
	    	var html = '<div class="form-group ">';
		    		html += '<label class="col-md-3 control-label">Additional Item</label>';
		    		html += '<div class="col-md-4">';
		    			html += '<input type="text" name="items['+currentItem+'][]" class="form-control">';
	    			html += '</div>';
	    	html += '</div>';

	    	jQuery('#room-group_'+currentItem).after(html);
		})
	}

});