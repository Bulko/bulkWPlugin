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
	 *globHooking Hooking helper
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since Peperiot 1.0.0
	 *@param  String $type (action||filter)
	 *@param  String $location
	 *@param  Array||String $function
	 *@return Void
	 */
	public function globHooking( String $type = 'action', String $location = 'init', $function )
	{
		$functionName = "add_" . $type;
		$functionName( $location, $function );
	}

	/**
	 *globHookingA Hooking helper
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since Peperiot 1.0.0
	 *@param  String $location
	 *@param  Array||String $function
	 *@return Void
	 */
	public function globHookingA( String $location = 'init', $function )
	{
		$this->globHooking( 'action', $location, [$this, $function] );
	}

	/**
	 *globHookingA Hooking helper
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since Peperiot 1.0.0
	 *@param  String $location
	 *@param  Array||String $function
	 *@return Void
	 */
	public function globHookingF( String $location = 'init', $function )
	{
		$this->globHooking( 'filter', $location, [$this, $function] );
	}

	/**
	 *hooking Hooking helper
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since Peperiot 1.0.0
	 *@param  String $type (action||filter)
	 *@param  String $location
	 *@param  Array $function
	 *@return Void
	 */
	public function hooking( String $type = 'action', String $location = 'init', String $function )
	{
		$this->globHooking( $type, $location, [$this, $function] );
	}

	/**
	 *hookingA Hooking helper
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since Peperiot 1.0.0
	 *@param  String $type (action||filter)
	 *@param  String $location
	 *@param  Array $function
	 *@return Void
	 */
	public function hookingA( String $location = 'init', String $function )
	{
		$this->globHooking( 'action', $location, [$this, $function] );
	}

	/**
	 *hookingA Hooking helper
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since Peperiot 1.0.0
	 *@param  String $type (action||filter)
	 *@param  String $location
	 *@param  Array $function
	 *@return Void
	 */
	public function hookingF( String $location = 'init', String $function )
	{
		$this->globHooking( 'filter', $location, [$this, $function] );
	}

	/**
	 * hook
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1
	 *@return Void
	 */
	public function hook()
	{
		$this->hookingA( 'init', 'createPostType' );
		$this->hookingA( 'init', 'createPostTaxonomy' );
		$this->hookingA( 'add_meta_boxes', 'createPostTaxonomy' );
		$this->hookingA( 'save_post', 'createPostTaxonomy' );
		$this->hookingA( 'after_setup_theme', 'createPostTaxonomy' );
		$this->hookingF( 'body_class', 'createPostTaxonomy' );
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
	 * registerTaxonomyHook
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since 1.0.0 Peperiot
	 *@param String $taxonomy
	 *@return Void
	 */
	public function registerTaxonomyHook( String $taxonomy )
	{
		$this->hookingA( $taxonomy . '_edit_form_fields', $taxonomy . 'MetaDataHtmlUpdate' );
		$this->hookingA( 'edited_' . $taxonomy, $taxonomy . 'SaveMetaData' );
		$this->hookingA( 'created_' . $taxonomy, $taxonomy . 'SaveMetaData' );
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

	/**
	 *customThumbnail
	 *@author Golga <r-ro@bulko.net>
	 *@since AGC 1.0.0
	 *@return Boolean
	 */
	public function customThumbnail()
	{
		// add_image_size( 'agc-thumbnail', 500, 270, true ); // (cropped)
		// add_image_size( 'agc-images-realisation', 370, 250, true ); // (cropped)
		return true;
	}

	/**
	 *getTaxonomyTerms
	 *@author Golga <r-ro@bulko.net>
	 *@since Peperiot 1.0.0
	 *@see https://developer.wordpress.org/reference/functions/get_terms/
	 *@param Bool $hide_empty
	 *@return Array
	 */
	public function getTaxonomyTerms( String $taxonomy, Bool $hide_empty = true )
	{
		return (array) get_terms( array(
			'orderby'		=> 'count',
			'taxonomy'		=> $taxonomy,
			'hide_empty'	=> $hide_empty,
		) );
	}

	/**
	 *addBodyClass
	 *@author Golga <r-ro@bulko.net>
	 *@since AGC 1.0.0
	 *@see http://php.net/manual/fr/function.get-browser.php && https://gist.github.com/rolandinsh/3510701
	 *@param  Array $classes
	 *@return Array
	 */
	public function addBodyClass( Array $classes )
	{
		$browserInfo = $this->getBrowserInfos();
		return array_merge( $classes, array( $browserInfo['browserName'], $browserInfo['platform'] ) );
	}

	/**
	 *getBrowserInfos
	 *@author Golga <r-ro@bulko.net>
	 *@since AGC 1.0.0
	 *@see http://php.net/manual/fr/function.get-browser.php && https://gist.github.com/rolandinsh/3510701
	 *@return Array
	 */
	public function getBrowserInfos()
	{
		$u_agent = $_SERVER['HTTP_USER_AGENT'];
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version = "";
		$dnt = 0;
		//First get the platform?
		if (preg_match('/linux/i', $u_agent))
		{
			$platform = 'linux';
		}
		elseif (preg_match('/macintosh|mac os x/i', $u_agent))
		{
			if ( preg_match('/iPad/i', $u_agent) )
			{
				$platform = 'ipad';
			}
			else
			{
				$platform = 'mac';
			}
		}
		elseif (preg_match('/windows|win32/i', $u_agent))
		{
			$platform = 'windows';
		}
		// Next get the name of the useragent yes seperately and for good reason
		if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
		{
			$bname = 'InternetExplorer';
			$ub = "MSIE";
		}
		elseif(preg_match('/Edge/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
		{
			$bname = 'Edge';
			$ub = "Edge";
		}
		elseif(preg_match('/Firefox/i',$u_agent))
		{
			$bname = 'MozillaFirefox';
			$ub = "Firefox";
		}
		elseif(preg_match('/Chrome/i',$u_agent))
		{
			$bname = 'GoogleChrome';
			$ub = "Chrome";
		}
		elseif(preg_match('/Safari/i',$u_agent))
		{
			$bname = 'AppleSafari';
			$ub = "Safari";
		}
		elseif(preg_match('/Opera/i',$u_agent))
		{
			$bname = 'Opera';
			$ub = "Opera";
		}
		elseif(preg_match('/Netscape/i',$u_agent))
		{
			$bname = 'Netscape';
			$ub = "Netscape";
		}
		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) .
		')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches))
		{
			// we have no matching number just continue
		}
		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1)
		{
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent,"Version") < strripos($u_agent,$ub))
			{
				$version= $matches['version'][0];
			}
			else
			{
				$version= $matches['version'][1];
			}
		}
		else
		{
			$version= $matches['version'][0];
		}
		// check if we have a number
		if ($version==null || $version=="")
		{
			$version="?";
		}
		if ( isset($_SERVER['HTTP_DNT']) && $_SERVER['HTTP_DNT'] == 1 )
		{
			$dnt = 1;
		}
		return array(
			'userAgent' => $u_agent,
			'browserName' => $bname,
			'browserVersion' => $version,
			'platform' => $platform,
			'pattern' => $pattern,
			'dnt' => $dnt,
			'request' => $_SERVER["REQUEST_URI"],
			'host' => $_SERVER["HTTP_HOST"],
			'time' => date( "Y-m-d h:i:sa" )
		);
	}
}
?>
