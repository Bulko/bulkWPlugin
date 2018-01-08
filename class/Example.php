<?php
class Example extends Core
{
	/**
	 * createPostType
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1 (11/07/2016 219de3a2e59b09fb8f5954b609ea44167536a7d9)
	 *@see https://developer.wordpress.org/reference/functions/register_post_type/
	 *@return void
	 */
	public function createPostType()
	{
		$labels = array(
			'name' => __( 'Examples', 'example' ),
			'singular_name' => __( 'Example', 'example' ),
			'add_new' => __( 'Add', 'example' ),
			'add_new_item' => __( 'Add example', 'example' ),
			'edit_item' => __( 'Update example', 'example' ),
			'new_item' => __( 'New example', 'example' ),
			'view_item' => __( 'Wiew example', 'example' ),
			'search_items' => __( 'Search example', 'example' ),
			'not_found' => __( 'Example not found', 'example' ),
			'not_found_in_trash' => __( 'Example not found in trash', 'example' ),
			'parent_item_colon' => __( 'Parent item', 'example' ),
			'menu_name' => __( 'Examples', 'example' ),
		);
		$args = array(
			'labels' => $labels,
			'hierarchical' => false,
			'description' => __( 'BulkWPlugin example customPostType', 'example' ),
			'supports' => array( 'editor', 'thumbnail', 'title' ),
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 20,
			'menu_icon' => 'dashicons-layout',
			'show_in_nav_menus' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'has_archive' => true,
			'query_var' => true,
			'can_export' => true,
			'rewrite' => true,
			'capability_type' => 'post'
		);
		register_post_type( 'example', $args );
	}

	/**
	 * addMetaBox
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1 (11/07/2016 219de3a2e59b09fb8f5954b609ea44167536a7d9)
	 *@see https://developer.wordpress.org/reference/functions/add_meta_box/
	 *@return void
	 */
	public function addMetaBox()
	{
		add_meta_box(
			'linked_pdf-linked-pdf',
			__( 'Fiche PDF', 'example' ),
			array($this, 'linkedPdfHtml'),
			'example',
			'normal',
			'default'
		);
		add_meta_box(
			'images_slider-images-slider',
			__( 'Images Slider', 'example' ),
			array($this, 'imagesSliderHtml'),
			'example',
			'normal',
			'default'
		);
	}

	/**
	 * linkedPdfHtml
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1 (11/07/2016 219de3a2e59b09fb8f5954b609ea44167536a7d9)
	 *@param  WP_Post $post
	 *@return Void
	 */
	public function linkedPdfHtml( WP_Post $post )
	{
		wp_nonce_field( '_linked_pdf_nonce', 'linked_pdf_nonce' );
		?>
		<p>
			<label for="linked_pdf_url">
				<?php _e( 'URL', 'example' ); ?> :
			</label>
			<input type="text" name="linked_pdf_url" id="linked_pdf_url" class="image_path_text" value="<?php echo $this->getMeta( 'linked_pdf_url' ); ?>" />
			<input type="button" id="upload-btn" class="button-secondary media_upload_bko" value="<?php _e( 'Choose file', 'example' ); ?>" />
		</p>
		<?php
	}

	/**
	 * imagesSliderHtml
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1 (11/07/2016 219de3a2e59b09fb8f5954b609ea44167536a7d9)
	 *@param  WP_Post $post
	 *@return Void
	 */
	public function imagesSliderHtml( WP_Post $post )
	{
		wp_nonce_field( '_images_slider_nonce', 'images_slider_nonce' );
		?>
		<button class="display_button button-secondary"><?php _e( 'Add image', 'example' ); ?></button>
		<p class="model">
			<label for="images_slider_url"><?php _e( 'URL :', 'example' ); ?></label>
			<input type="text" name="images_slider_url[]" class="image_path_text" value="" />
			<input type="button" id="upload-btn" class="button-secondary media_upload_bko" value="<?php _e( 'Choose file', 'example' ); ?>">
			<a class="delete_image_bko">
				<span class="dashicons dashicons-no-alt bigger"></span>
			</a>
		</p>
		<?php
		$serialized_images = $this->getMeta('images_slider_url');
		$images = unserialize($serialized_images);
		if( !empty( $images ) ):
			foreach ( $images as $key => $image ):
			?>
			<p class="image_slider">
				<label for="images_slider_url"><?php _e( 'URL :', 'example' ); ?></label>
				<input type="text" name="images_slider_url[]" class="image_path_text" value="<?php echo $image; ?>">
				<input type="button" id="upload-btn" class="button-secondary media_upload_bko" value="<?php _e( 'Choose file', 'example' ); ?>">
				<a class="delete_image_bko">
					<span class="dashicons dashicons-no-alt bigger"></span>
				</a>
			</p>
			<?php
			endforeach;
		endif;
	}

	/**
	 * saveMetaData
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1 (11/07/2016 219de3a2e59b09fb8f5954b609ea44167536a7d9)
	 *@param int $post_id
	 *@see https://codex.wordpress.org/Function_Reference/update_post_meta
	 *@return boolean
	 */
	public function saveMetaData( Int $post_id )
	{
		if (
			( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			|| !current_user_can( 'edit_post', $post_id )
			|| !isset( $_POST['linked_pdf_nonce'] )
			|| !isset( $_POST['images_slider_nonce'] )
			|| !wp_verify_nonce( $_POST['linked_pdf_nonce'], '_linked_pdf_nonce' )
			|| !wp_verify_nonce( $_POST['images_slider_nonce'], '_images_slider_nonce' )
		)
		{
			return false;
		}
		update_post_meta( $post_id, 'linked_pdf_url', esc_attr( $_POST['linked_pdf_url'] ) );
		foreach ( $_POST['images_slider_url'] as $key => $image )
		{
			if( $image === '' )
			{
				unset( $_POST['images_slider_url'][$key] );
			}
		}
		$_POST['images_slider_url'] = array_values($_POST['images_slider_url']);
		update_post_meta( $post_id, 'images_slider_url', serialize( $_POST['images_slider_url'] ) );
		return true;
	}

	/**
	 *createPostTaxonomy
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since 1.0.0 Agc
	 *@see https://codex.wordpress.org/Function_Reference/register_taxonomy
	 *@return boolean
	 */
	public function createPostTaxonomy()
	{
		register_taxonomy(
			'taxonomy_example',
			'example',
			array(
				'hierarchical' => true,
				'label' => __( 'Taxonomy example', 'exemple'),
				'query_var' => true,
				'rewrite' => array(
					'slug' => 'taxonomy_example',
					'with_front' => false
				)
			)
		);
		// Register hook to alowing meta overriding.
		$this->registerTaxonomyHook( 'taxonomy_example' );
		return true;
	}

	/**
	 *getTaxonomyExempleTerms
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since Peperiot 1.0.0
	 *@see Core::getTaxonomyTerms()
	 *@param  Bool|boolean $hide_empty
	 *@return Array
	 */
	public function getTaxonomyExempleTerms( Bool $hide_empty = true )
	{
		return (array) $this->getTaxonomyTerms( 'taxonomy_example' );
	}

	/**
	 *taxonomy_exampleMetaDataHtmlUpdate
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since Peperiot 1.0.0
	 *@param WP_Term $term
	 *@return Void
	 */
	public function taxonomy_exampleMetaDataHtmlUpdate( WP_Term $term )
	{
		?>
		<tr class="form-field">
			<th>
				<label for="textSEO">
					<?php _e( 'Text SEO', 'exemple' ); ?>
				</label>
			</th>
			<td>
				<?php wp_editor(
						htmlspecialchars_decode( get_term_meta( $term->term_id, 'textSEO', true ) ),
						"textSEO",
						array(
							'textarea_rows'=>12,
							'editor_class'=>'textSEO'
						)
					);
				?>
			</td>
		</tr>
	<?php
	}

	/**
	 *taxonomy_exampleSaveMetaData
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since Peperiot 1.0.0
	 *@param  Int $term_id
	 *@return boolean
	 */
	public function taxonomy_exampleSaveMetaData( Int $term_id )
	{
		// wp_verify_nonce dosent worck here (Wordpress 4.8.1)
		if ( !isset( $_POST['textSEO'] ) )
		{
			return;
		}
		update_term_meta( $term_id, 'textSEO', esc_attr( $_POST['textSEO'] ) );
		return true;
	}
}
?>
