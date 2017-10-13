<?php
class Slider extends Core
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
			'name' => __( 'Slider', 'slider' ),
			'singular_name' => __( 'Slide', 'slider' ),
			'add_new' => __( 'Ajouter une slide', 'slider' ),
			'add_new_item' => __( 'Ajouter une slide', 'slider' ),
			'edit_item' => __( 'Modifier la slide', 'slider' ),
			'new_item' => __( 'Nouvelle slide', 'slider' ),
			'view_item' => __( 'Voir la slide', 'slider' ),
			'search_items' => __( 'Rechercher une slide', 'slider' ),
			'not_found' => __( 'Slide introuvable', 'slider' ),
			'not_found_in_trash' => __( 'Slide introuvable dans la corbeille', 'slider' ),
			'parent_item_colon' => __( 'Element Parent', 'slider' ),
			'menu_name' => __( 'Slider', 'slider' ),
		);

		$args = array(
			'labels' => $labels,
			'hierarchical' => false,
			'description' => 'Gestions du slider pour AGC',
			'supports' => array( 'thumbnail', 'title' ),
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 20,
			'menu_icon' => 'dashicons-tickets-alt',
			'show_in_nav_menus' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'has_archive' => true,
			'query_var' => true,
			'can_export' => true,
			'rewrite' => true,
			'capability_type' => 'post'
		);
		register_post_type( 'slider', $args );
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
			'avanced_slider-avanced-slider',
			__( 'Slide', 'avanced-slider' ),
			array($this, 'infoSliderHtml'),
			'slider',
			'normal',
			'default'
		);
	}

	/**
	 * infoSliderHtml
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1 (11/07/2016 219de3a2e59b09fb8f5954b609ea44167536a7d9)
	 *@param  WP_Post $post
	 *@return Void
	 */
	public function infoSliderHtml( WP_Post $post )
	{
		wp_nonce_field( '_avanced_slider_nonce', 'avanced_slider_nonce' );
		$subtitle = $this->getMeta('subtitle');
		$url = $this->getMeta('url');
		$order = $this->getMeta('order');
		$target = $this->getMeta('target');
		?>
		<p class="field_slider">
			<textarea placeholder="Sous-titre" type="text" name="subtitle" class="input-text"><?php echo $subtitle; ?></textarea>
		</p>
		<p class="field_slider">
			<input placeholder="Url" type="url" name="url" class="input-text" value="<?php echo $url; ?>">
		</p>
		<p class="field_slider">
			<select name="target">
				<option value="_self" <?php if( $target === "_self" ){ echo 'selected="selected"'; } ?>>_self</option>
				<option value="_blank" <?php if( $target === "_blank" ){ echo 'selected="selected"'; } ?>>_blank</option>
				<!-- <option value="_video" <?php if( $target === "_video" ){ echo 'selected="selected"'; } ?>>_video</option> -->
			</select>
		</p>
		<p class="field_slider">
			<input placeholder="Ordre" type="number" name="order" class="input-text" value="<?php echo $order; ?>">
		</p>
		<?php
	}

	/**
	 * saveMetaData
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1 (11/07/2016 219de3a2e59b09fb8f5954b609ea44167536a7d9)
	 *@param  int $post_id
	 *@return boolean
	 */
	public function saveMetaData( Int $post_id )
	{
		if (
			!isset( $_POST['avanced_slider_nonce'] )
			|| !wp_verify_nonce( $_POST['avanced_slider_nonce'], '_avanced_slider_nonce' )
			|| !current_user_can( 'edit_post', $post_id )
			|| defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE
		)
		{
			return false;
		}
		if( isset( $_POST['subtitle'] ) )
		{
			update_post_meta( $post_id, 'subtitle', $_POST['subtitle'] );
		}
		if( isset( $_POST['url'] ) )
		{
			update_post_meta( $post_id, 'url', $_POST['url'] );
		}
		if( isset( $_POST['order'] ) )
		{
			update_post_meta( $post_id, 'order', $_POST['order'] );
		}
		if( isset( $_POST['target'] ) )
		{
			update_post_meta( $post_id, 'target', $_POST['target'] );
		}
		return true;
	}

	/**
	 *getSlides
	 *@author Golga <r-ro@bulko.net>
	 *@since AGC 1.0.0
	 *@see https://codex.wordpress.org/Template_Tags/get_posts
	 *@return array
	 */
	public function getSlides()
	{
		$args = array(
			'posts_per_page' => 5,
			'offset' => 0,
			'category' => '',
			'category_name' => '',
			'orderby' => 'meta_value',
			'order' => 'ASC',
			'include' => '',
			'exclude' => '',
			'meta_key' => 'order',
			'meta_value' => '',
			'post_type' => 'slider',
			'post_mime_type' => '',
			'post_parent' => '',
			'author' => '',
			'author_name' => '',
			'post_status' => 'publish',
			'suppress_filters' => true
		);
		return $this->getWithMeta( $args );
	}

	/**
	 *getSlidesHtml
	 *@author Golga <r-ro@bulko.net>
	 *@since AGC 1.0.0
	 *@return String
	 */
	public function getSlidesHtml()
	{
		$slides = $this->getSlides();
		$html = "<!-- Start " . BKO_PLUGIN_NAME . " Slider -->";
		if ( isset( $slides[1] ) && !empty( $slides[1] ) )
		{
			$html .= "<ul class='" . BKO_PLUGIN_NAME . "-slider slider'>";
			foreach ( $slides as $key => $slide )
			{
				if( !empty( $slide["thumbnail"] ) )
				{
					$html .= "<li class='slide' style='background-image:url(" . $slide["full-thumbnail"] . ")'>";
				}
				else
				{
					$html .= "<li class='slide'>";
				}
				if( !empty( $slide["url"] ) )
				{
					if ( $slide["target"] == "_blank" )
					{
						$html .= "<a class='slide-link' href='" . $slide["url"] . "' target='" . $slide["target"] . "' rel='noopener'>";
					}
					elseif ( $slide["target"] == "_video" )
					{
						$html .= "<a class='slide-link slide-lb-trigger' href='" . $slide["url"] . "'>";
					}
					else
					{
						$html .= "<a class='slide-link' href='" . $slide["url"] . "' >";
					}
					$html .= "<div class='title-container'>";
					$html .= "<div class='title'>";
					$html .= $slide["post_title"];
					$html .= "</div>";
					if ( !empty( $slide["subtitle"] ) )
					{
						$html .= "<div class='sub-title'>";
						$html .= nl2br( $slide["subtitle"] );
						$html .= "</div>";
					}
					$html .= "<button class='read-more'>En savoire +</button>";
					$html .= "</div>";
					$html .= "</a>";
				}
				$html .= "</li>";
			}
			$html .= "</ul>";
		}
		$html .= "<div class='" . BKO_PLUGIN_NAME . "-slider-lb'>";
		$html .= "<div class='" . BKO_PLUGIN_NAME . "-lb-layer'>";
		$html .= "<div class='" . BKO_PLUGIN_NAME . "-lb-box'>";
		$html .= "<div class='" . BKO_PLUGIN_NAME . "-lb-close'>";
		$html .= "<i class='fa fa-times'></i>";
		$html .= "</div>";
		$html .= "<div class='" . BKO_PLUGIN_NAME . "-lb-content'>";
		$html .= "<!-- 🦄 -->";
		$html .= "</div>";
		$html .= "</div>";
		$html .= "</div>";
		$html .= "</div>";
		$html .= "<!-- End " . BKO_PLUGIN_NAME . " Slider -->";
		return $html;
	}
}
?>
