jQuery(function ($) {
	//ADD MEDIA UPLOADER/SELECTOR
	function upload_image(e, text_input, id_input = null)
	{
		// e.preventDefault();
		var image = wp.media({
			title: 'Choisir une image',
			// mutiple: true if you want to upload multiple files at once
			multiple: false
		}).open()
		.on('select', function(e){
			// This will return the selected image from the Media Uploader, the result is an object
			var uploaded_image = image.state().get('selection').first();
			// We convert uploaded_image to a JSON object to make accessing it easier
			// Output to the console uploaded_image
			var image_url = uploaded_image.toJSON().url;

			// Let's assign the url value to the input field
			if( text_input !== null )
			{
				$(text_input).val(image_url);
			}

			if( id_input !== null )
			{
				$(id_input).val( uploaded_image.toJSON().id );
			}
			// window.aa_name_img_input[$(text_input).parent().index('.image_slider')] = image_url;
			// console.log(window.aa_name_img_input);
		});
	};

	function delete_image(e, del){
		del.parent().children('.image_path_text').val('');
		del.parent().slideToggle();
	}

	// protect user role ie fix :poop:
	$('select#role option[value="administrator"]').remove();
	$('select#role option[value=""]').remove();

	p = '<p class="image_slider"></p>';
	// window.aa_name_img_input = [];
	$('.model').css('display', 'none');
	var model = $('.model').html();
	$('.display_button').click(function(e)
	{
		e.preventDefault();
		$(this).parent().append(p);
		$(this).parent().children('p').last().append(model);
		var upload = $(this).parent().children('p').last().children('.media_upload_bko');
		var text_input = upload.prev('.image_path_text');
		var del = $(this).parent().children('p').last().children('.delete_image_bko');
		upload.on('click', function(){
			upload_image(e, text_input);
		});
		del.on('click', function(){
			delete_image(e, del);
		});

	});

	$('.delete_image_bko').click( function(e)
	{
		delete_image(e, $(this));
	});

	$('.media_upload_bko').click( function(e)
	{
		text_input = $(this).prev('.image_path_text');
		upload_image(e, text_input);
	});

	$('.media_upload_single_bko').click( function(e)
	{
		text_input = $(this).prev('.image_id');
		upload_image(e, null, text_input);
	});

	// Service technique
	$('.display_button_st').click(function(e)
	{
		e.preventDefault();
		$(this).parent().append('<div class="serial_number"></div>');
		$(this).parent().children('div').last().append(model);
		// var upload = $(this).parent().children('div').last().children('.media_upload_bko');
		// var text_input = upload.prev('.image_path_text');
		var del = $(this).parent().children('div').last().children('.delete_number_st');
		// upload.on('click', function(){
		// 	upload_image(e, text_input);
		// });
		del.on('click', function(){
			delete_image(e, del);
		});
	});


});
