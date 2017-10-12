<?php
class Core{
	/**
	 * __construct
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1
	 *@return boolean
	 */
	public function __construct()
	{
		return true;
	}
	/**
	 * hook
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1
	 *@return boolean
	 */
	public function hook()
	{
		add_action( 'init', array( $this, 'createPostType' ) );
		add_action( 'init', array( $this, 'createPostTaxonomy' ) );
		add_action( 'add_meta_boxes', array( $this, 'addMetaBox' ) );
		add_action( 'save_post', array( $this, 'saveMetaData' ) );
		add_action( 'after_setup_theme', array( $this, 'agcThumbnail' ) );
	}

	/**
	 * createPostTaxonomy
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since 1.0.0 Agc
	 *@return boolean
	 */
	public function createPostTaxonomy()
	{
		return true;
	}

	/**
	 * createPostType
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1
	 *@return boolean
	 */
	public function createPostType()
	{
		return true;
	}

	/**
	 * getMeta
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since 1.2 (11/07/2016 ce544a796a98a664a7109f58e49b0e7630a5a3fe)
	 *@see http://jeremyhixon.com/tool/wordpress-meta-box-generator/
	 *@param  String $value
	 *@return Mixed
	 */
	public function getMeta( $value )
	{
		/*
			Usage: getMeta( 'aa_meta_url' )
			Usage: getMeta( 'aa_meta_target' )
			Usage: getMeta( 'aa_meta_rel' )
			Usage: getMeta( 'aa_meta_class_css' )
			Usage: getMeta( 'aa_meta_title' )
		*/
		global $post;

		$field = get_post_meta( $post->ID, $value, true );
		if ( ! empty( $field ) )
		{
			return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
		}
		else
		{
			return false;
		}
	}

	/**
	 * addMetaBox
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@return boolean
	 */
	public function addMetaBox()
	{
		return true;
	}

	/**
	 * addMetaBox
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@return boolean
	 */
	public function fichePdfHtml( WP_Post $post )
	{
		return true;
	}

	/**
	 * saveMetaData
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1
	 *@param  Int $post_id
	 *@return boolean
	 */
	public function saveMetaData( Int $post_id )
	{
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		{
			return false;
		}

		return true;
	}

	/**
	 *getWithMeta
	 *@author Golga <r-ro@bulko.net>
	 *@since AGC 1.0.0
	 *@param  Array $args argument of wp_query
	 *@return Array of posts
	 */
	public function getWithMeta( Array $args )
	{
		$posts = (array) get_posts( $args );
		return $this->retriveMeta( $posts );
	}

	/**
	 *retriveMeta
	 *@author Golga <r-ro@bulko.net>
	 *@since AGC 1.0.0
	 *@param  Array $posts
	 *@return Array of posts
	 */
	public function retriveMeta( Array $posts )
	{
		foreach ( $posts as $key => $post )
		{
			$posts[$key] = (array) $post;
			$metas = get_post_meta( $post->ID );
			foreach ( $metas as $k => $meta )
			{
				if ( isset( $meta[1] ) )
				{
					$posts[$key][$k] = $meta;
				}
				else
				{
					$posts[$key][$k] = $meta[0];
				}
			}
			if ( !empty( $posts[$key]["_thumbnail_id"] ) )
			{
				$posts[$key]["full-thumbnail"] = wp_get_attachment_image_src( $posts[$key]["_thumbnail_id"], 'single-post-thumbnail' )[0];
				$posts[$key]["thumbnail"] = wp_get_attachment_image_src( $posts[$key]["_thumbnail_id"], 'thumbnail' )[0];
				// $posts[$key]["agc-thumbnail"] = wp_get_attachment_image_src( $posts[$key]["_thumbnail_id"], 'agc-thumbnail' )[0];
			}
			$posts[$key]["permalink"] =  get_permalink( $post->ID );
			unset( $metas );
		}
		return $posts;
	}

	public function agcThumbnail()
	{
		// add_image_size( 'agc-thumbnail', 500, 270, true ); // (cropped)
		// @see getRealisationImgHtml in Realisation.php
		// add_image_size( 'agc-images-realisation', 370, 250, true ); // (cropped)
		return true;
	}
}
?>
