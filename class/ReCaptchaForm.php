<?php
class ReCaptchaForm
{
	private $publicKey;
	private $privateKey;
	private $url;
	private $https;
	private $recaptcha;

	/**
	 *__construct
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1 (11/07/2016 219de3a2e59b09fb8f5954b609ea44167536a7d9)
	 *@see https://www.google.com/recaptcha/intro/index.html
	 *@return boolean
	 */
	public function __construct()
	{
		$this->publicKey  = '6LcunCUTAAAAABHnAGfsmJEoDeiLAnFy0CuuvXln';
		$this->privateKey = '6LcunCUTAAAAAPma73_lWR3IFyxj56Y36XK4WaEL';
		$this->url = 'https://www.google.com/recaptcha/api/siteverify';
		$this->https = false;
		return true;
	}

	/**
	 *hook
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1 (11/07/2016 219de3a2e59b09fb8f5954b609ea44167536a7d9)
	 *@see https://www.google.com/recaptcha/intro/index.html
	 *@return Void
	 */
	public function hook()
	{
		// adds the captcha to the login form
		add_action( 'login_form', array( $this, 'displayCaptcha' ) );
		add_action( 'register_form', array( $this, 'displayCaptcha' ) );
		add_action( 'lostpassword_form', array( $this, 'displayCaptcha' ) );
		add_action( 'login_enqueue_scripts', array( $this, 'cssCaptcha' ) );

		// authenticate the captcha answer
		add_action( 'wp_authenticate_user', array( $this, 'validateCaptchaLogin' ), 10, 2 );
		add_action( 'registration_errors', array( $this, 'validateCaptchaInsc' ), 10, 2 );
		add_action( 'lostpassword_post', array( $this, 'validateCaptchaInsc' ), 10, 2 );
	}

	/**
	 *displayCaptcha
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1 (11/07/2016 219de3a2e59b09fb8f5954b609ea44167536a7d9)
	 *@see https://developers.google.com/recaptcha/docs/display#render_param
	 *@return boolean
	 */
	public function displayCaptcha()
	{
		echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
		echo "<div class='g-recaptcha' data-theme='ight' data-sitekey='" . $this->publicKey . "' ></div>";
		return true;
	}

	/**
	 * cssCaptcha
	 *
	 *@author Audrey <a-le@bulko.net>
	 *@since ADVE 1.0.0
	 *@see https://codex.wordpress.org/Customizing_the_Login_Form
	 *@return boolean
	 */
	public function cssCaptcha()
	{
		wp_enqueue_style( 'reCaptcha-login', plugins_url( "../css/login.css", __FILE__ ) );
		return true;
	}

	/**
	 *validateCaptcha
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1 (11/07/2016 219de3a2e59b09fb8f5954b609ea44167536a7d9)
	 *@param  WP_User $user
	 *@param  String  $password
	 *@return WP_User
	 */
	public function validateCaptchaLogin( $user, $password )
	{
		if( $this->isValidRecaptha() === false )
		{
			return new WP_Error( 'invalid_captcha', 'CAPTCHA response was incorrect');
		}
		return $user;
	}

	/**
	 *validateCaptchaInsc
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1 (11/07/2016 219de3a2e59b09fb8f5954b609ea44167536a7d9)
	 *@see https://www.google.com/recaptcha/intro/index.html
	 *@param  WP_Error $errors
	 *@return WP_Error
	 */
	public function validateCaptchaInsc( $errors = null )
	{
		if( $this->isValidRecaptha() === false )
		{
			return new WP_Error( 'invalid_captcha', 'CAPTCHA response was incorrect');
		}
		return $errors;
	}

	/**
	 *isValidRecaptha
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@see https://www.google.com/recaptcha/intro/index.html NHAM Controller.php
	 *@since AA 0.1 (11/07/2016 219de3a2e59b09fb8f5954b609ea44167536a7d9)
	 *@return boolean
	 */
	public function isValidRecaptha()
	{
		if ( !empty($_POST[ "g-recaptcha-response" ]) )
		{
			$this->recaptcha = $_POST[ "g-recaptcha-response" ];
			$fields = array(
				"secret" => $this->privateKey,
				"response" => $this->recaptcha
			);
			$isHuman = $this->getCurlData( $this->url, $fields );

			if ( $isHuman["success"] )
			{
				return true;
			}
		}
		return false;
	}

	/**
	 *getCurlData
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1 (11/07/2016 219de3a2e59b09fb8f5954b609ea44167536a7d9)
	 *@see NHAM Controller.php
	 *@param  String $url
	 *@param  Array  $fields
	 *@return Array
	 */
	public function getCurlData( $url, $fields )
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		if ( !$this->https )
		{
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		}
		return (array) json_decode( curl_exec($ch) );
	}

	/**
	 *setPrivate
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1 (11/07/2016 219de3a2e59b09fb8f5954b609ea44167536a7d9)
	 *@param  [type] $publicKey
	 */
	public function setPrivate( $publicKey )
	{
		$this->publicKey = $publicKey;
	}

	/**
	 *setPublic
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1 (11/07/2016 219de3a2e59b09fb8f5954b609ea44167536a7d9)
	 *@param  String $privateKey
	 */
	public function setPublic( $privateKey )
	{
		$this->privateKey = $privateKey;
	}

	/**
	 *setUrl
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1 (11/07/2016 219de3a2e59b09fb8f5954b609ea44167536a7d9)
	 *@param  String $url
	 */
	public function setUrl( $url )
	{
		$this->url = $url;
	}

	/**
	 *setHttps
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA 0.1 (11/07/2016 219de3a2e59b09fb8f5954b609ea44167536a7d9)
	 *@param  Boolean $https
	 */
	public function setHttps( $https )
	{
		$this->https = $https;
	}

	/**
	 *setTestMod
	 *
	 *@author Golga <r-ro@bulko.net>
	 *@since AA
	 *@see https://developers.google.com/recaptcha/docs/faq
	 */
	public function setTestMod()
	{
		$this->setPrivate( "6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI" );
		$this->setPublic( "6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe" );
	}
}
