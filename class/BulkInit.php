<?php
class BulkInit{
	private $pluginName;
	private $BulkObj;

	/**
	 * __construct
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since 0.1
	 *@param String $pluginName
	 *@return  void
	 */
	public function __construct( $pluginName )
	{
		//define some informations
		$this->pluginName = $pluginName;
		define( $pluginName . '_VERSION', '1.2' );
		define( $pluginName . '_MINIMUM_WP_VERSION', '4.5.2' );
		define( $pluginName . '_PLUGIN_CLASS_DIR', plugin_dir_path( __FILE__ ) );
		define( $pluginName . '_DELETE_LIMIT', 100000 );
		//Model & Controller loading
		
	}

	/**
	 * admScript
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since 0.1
	 *@return void
	 */
	public function admScript()
	{
		wp_enqueue_script( 'aa-admin-script', plugins_url(  '../js/admin.js', __FILE__ ), array(), '20151215', true );
		wp_enqueue_media();
	}

	/**
	 * hook
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since 0.1
	 *@return void
	 */
	public function hook()
	{
		add_action('admin_enqueue_scripts', array( $this, 'admScript' ) );
	}

	/**
	 * initHook
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since 0.1
	 *@return void
	 */
	public function initHook()
	{
		$this->hook();
		foreach ( $this->BulkObj as $objKey => $obj )
		{
			if ( method_exists( $this->BulkObj->$objKey , "hook") )
			{
				$this->BulkObj->$objKey->hook();
			}
		}
	}

	/**
	 * initObj
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since 0.1
	 *@return Array of mixed obj BulkObj
	 */
	public function initObj()
	{
		//TODO :  refactor with loop
		require_once( constant( $this->pluginName . '_PLUGIN_CLASS_DIR' ) . '/Core.php' );
		require_once( constant( $this->pluginName . '_PLUGIN_CLASS_DIR' ) . '/Realisation.php' );
		require_once( constant( $this->pluginName . '_PLUGIN_CLASS_DIR' ) . '/MetaBox.php' );
		require_once( constant( $this->pluginName . '_PLUGIN_CLASS_DIR' ) . '/User.php' );
		require_once( constant( $this->pluginName . '_PLUGIN_CLASS_DIR' ) . '/Color.php' );
		require_once( constant( $this->pluginName . '_PLUGIN_CLASS_DIR' ) . '/ReCaptchaForm.php' );
		$BulkObj = array(
			"Realisation" => new Realisation(),
			"MetaBox" => new MetaBox(),
			"User" => new User(),
			"Color" => new Color(),
			"ReCaptchaForm" => new ReCaptchaForm(),
		);
		$this->BulkObj = (object) $BulkObj;
		return $this->BulkObj;
	}
}
?>
