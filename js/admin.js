jQuery( function ($) {
	//ADD MEDIA UPLOADER/SELECTOR
	upload_image = ( text_input, id_input = null ) => {
		const image = wp.media({
			title: 'Choisir une image', // mutiple: true if you want to upload multiple files at once
			multiple: false
		})
		.open()
		.on('select', () =>{
			// This will return the selected image from the Media Uploader, the result is an object
			const uploaded_image = image.state().get('selection').first();
			// We convert uploaded_image to a JSON object to make accessing it easier
			// Output to the console uploaded_image
			const image_url = uploaded_image.toJSON().url;
			// Let's assign the url value to the input field
			if( text_input !== null )
			{
				$(text_input).val(image_url);
			}
			if( id_input !== null )
			{
				$(id_input).val( uploaded_image.toJSON().id );
			}
		});
	};

	delete_image = del => {
		del.parent().children('.image_path_text').val('');
		del.parent().slideToggle();
	}

	// protect user role ie fix :poop:
	$('select#role option[value="administrator"]').remove();
	$('select#role option[value=""]').remove();


	$('.model').css('display', 'none');

	$('.display_button').on( "click", function(e){
		e.preventDefault();
		const model = $(this).parent().find(".model").html();
		const p = '<p class="image_slider"></p>';
		const container = $(this).parent().append( p );
		const pnode = container.find(".image_slider").last().append( model );
		const upload = pnode.find('.media_upload_bko');
		const text_input = pnode.find('.image_path_text');
		const del = pnode.find('.delete_image_bko');
		upload.on('click', () => {
			upload_image( text_input );
		});
		del.on('click', () => {
			delete_image( del );
		});
	});

	$('.delete_image_bko').on( "click", function(){
		delete_image( $(this) );
	});

	$('.media_upload_bko').on( "click", function(){
		upload_image( $(this).prev('.image_path_text') );
	});
	$('.media_upload_single_bko').on( "click", function(){
		upload_image( null, $(this).prev('.image_id') );
	});
});
