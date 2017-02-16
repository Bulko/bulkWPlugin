<?php
class Core{
	/**
	 * __construct
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since 0.1
	 *@return boolean
	 */
	public function __construct()
	{
		return true;
	}
	/**
	 * __construct
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since 0.1
	 *@return boolean
	 */
	public function hook()
	{
		add_action( 'init', array( $this, 'createPostType' ) );
		add_action( 'add_meta_boxes', array( $this, 'addMetaBox' ) );
		add_action( 'save_post', array( $this, 'saveMetaData' ) );
	}
	/**
	 * __construct
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since 0.1
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
	function addMetaBox()
	{
		return true;
	}

	/**
	 * addMetaBox
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@return boolean
	 */
	function fichePdfHtml( $post )
	{
		return true;
	}

	/**
	 * saveMetaData
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since 0.1
	 *@param  Int $post_id
	 *@return boolean
	 */
	function saveMetaData( $post_id )
	{
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		{
			return false;
		}
		
		return true;
	}
}
?>