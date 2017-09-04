<?php
class Realisation extends Core
{
	/**
	 * createPostType
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1 (11/07/2016 219de3a2e59b09fb8f5954b609ea44167536a7d9)
	 *@return void
	 */
	public function createPostType()
	{
		$labels = array(
			'name' => __( 'Réalisations', 'realisation' ),
			'singular_name' => __( 'Réalisation', 'realisation' ),
			'add_new' => __( 'Ajouter', 'realisation' ),
			'add_new_item' => __( 'Ajouter une réalisation', 'realisation' ),
			'edit_item' => __( 'Modifier la réalisation', 'realisation' ),
			'new_item' => __( 'Nouvelle réalisation', 'realisation' ),
			'view_item' => __( 'Voir la réalisation', 'realisation' ),
			'search_items' => __( 'Rechercher une réalisation', 'realisation' ),
			'not_found' => __( 'Réalisation introuvable', 'realisation' ),
			'not_found_in_trash' => __( 'réalisation introuvable dans la corbeille', 'realisation' ),
			'parent_item_colon' => __( 'Element Parent', 'realisation' ),
			'menu_name' => __( 'Réalisations', 'realisation' ),
		);

		$args = array(
			'labels' => $labels,
			'hierarchical' => false,
			'description' => 'Gestions des réalisation pour AA',
			'supports' => array( 'editor', 'thumbnail', 'title' ),
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 20,
			'menu_icon' => 'dashicons-admin-multisite',
			'show_in_nav_menus' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'has_archive' => true,
			'query_var' => true,
			'can_export' => true,
			'rewrite' => true,
			'capability_type' => 'post'
		);

		register_post_type( 'realisation', $args );
	}

	/**
	 * addMetaBox
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1 (11/07/2016 219de3a2e59b09fb8f5954b609ea44167536a7d9)
	 *@return void
	 */
	public function addMetaBox()
	{
		add_meta_box(
			'fiche_pdf-fiche-pdf',
			__( 'Fiche PDF', 'fiche_pdf' ),
			array($this, 'fichePdfHtml'),
			'realisation',
			'normal',
			'default'
		);
		add_meta_box(
			'images_slider-images-slider',
			__( 'Images Slider', 'images_slider' ),
			array($this, 'imagesSliderHtml'),
			'realisation',
			'normal',
			'default'
		);
	}

	/**
	 * fichePdfHtml
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1 (11/07/2016 219de3a2e59b09fb8f5954b609ea44167536a7d9)
	 *@param  Post $post
	 *@return Void
	 */
	public function fichePdfHtml( $post )
	{
		wp_nonce_field( '_fiche_pdf_nonce', 'fiche_pdf_nonce' ); ?>

		<p>
			<label for="fiche_pdf_url"><?php _e( 'URL', 'fiche_pdf' ); ?></label><br>
			<input type="text" name="fiche_pdf_url" id="fiche_pdf_url" class="image_path_text" value="<?php echo $this->getMeta( 'fiche_pdf_url' ); ?>"><input type="button" id="upload-btn" class="button-secondary media_upload_bko" value="Choisir un fichier">
		</p><?php
	}

	/**
	 * imagesSliderHtml
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1 (11/07/2016 219de3a2e59b09fb8f5954b609ea44167536a7d9)
	 *@param  Post $post
	 *@return Void
	 */
	public function imagesSliderHtml( $post )
	{
		wp_nonce_field( '_images_slider_nonce', 'images_slider_nonce' ); 
		?>

		<button class="display_button button-secondary">Ajouter une image</button>
		<p class="model">
			<label for="images_slider_url">URL</label><br>
			<input type="text" name="images_slider_url[]" class="image_path_text" value=""><input type="button" id="upload-btn" class="button-secondary media_upload_bko" value="Choisir un fichier"><a class="delete_image_bko"> <span class="dashicons dashicons-no-alt bigger"></span> </a>
		</p>		

		<?php 

		$serialized_images = $this->getMeta('images_slider_url');
		$images = unserialize($serialized_images);
		if(!empty( $images ) )
		{
			foreach ($images as $key => $image) {
			?>
			<p class="image_slider">
				<label for="images_slider_url">URL</label><br>
				<input type="text" name="images_slider_url[]" class="image_path_text" value="<?php echo $image; ?>"><input type="button" id="upload-btn" class="button-secondary media_upload_bko" value="Choisir un fichier"><a class="delete_image_bko"> <span class="dashicons dashicons-no-alt bigger"></span> </a>
			</p>
			<?php
			}
		}
		
	}

	/**
	 * saveMetaData
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1 (11/07/2016 219de3a2e59b09fb8f5954b609ea44167536a7d9)
	 *@param  int $post_id
	 *@return boolean
	 */
	public function saveMetaData( $post_id )
	{
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		{
			return false;
		}
		if ( ! isset( $_POST['fiche_pdf_nonce'] ) || ! wp_verify_nonce( $_POST['fiche_pdf_nonce'], '_fiche_pdf_nonce' ) )
		{
			return false;
		}

		if ( ! isset( $_POST['images_slider_nonce'] ) || ! wp_verify_nonce( $_POST['images_slider_nonce'], '_images_slider_nonce' ) )
		{
			return false;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) )
		{
			return false;
		}

		if ( isset( $_POST['fiche_pdf_url'] ) )
		{
			update_post_meta( $post_id, 'fiche_pdf_url', esc_attr( $_POST['fiche_pdf_url'] ) );
		}

		if( isset( $_POST['images_slider_url'] ) )
		{
			foreach ($_POST['images_slider_url'] as $key => $image) {
				if($image == ''){
					unset($_POST['images_slider_url'][$key]);
				}
			}
			$_POST['images_slider_url'] = array_values($_POST['images_slider_url']);
			update_post_meta( $post_id, 'images_slider_url', serialize( $_POST['images_slider_url'] ) );
		}

		return true;
	}
}
?>
