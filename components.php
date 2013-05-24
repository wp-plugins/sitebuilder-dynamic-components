<?php

/**
 * Encapsulates dynamic component functionality
 */
class SB_DC_Components {
	
	/**
	 * singleton instance
	 * @var SB_DC_Components
	 */
	private static $instance = null;

	/**
	 * get singleton instance	 
	 * @return SB_DC_Components
	 */
	public static function getInstance(){

		if ( self::$instance === null )
			self::$instance = new self();

		return self::$instance;

	}

	/**
	 * registered components
	 * @var array
	 */
	private $components = array();

	/**
	 * registered page arguments
	 * @var array
	 */
	private $page_args = array();

	private function __construct(){

		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts' ) );
		add_action( 'wp_footer', array( &$this, 'renderFooterJS' ), apply_filters( 'sb_dc_footer_js_priority', 10 ) );
		add_action( 'wp_ajax_nopriv_sb_dc_render_dynamic_components', array( &$this, 'ajaxRenderDynamicComponents' ) );
		add_action( 'wp_ajax_sb_dc_render_dynamic_components', array( &$this, 'ajaxRenderDynamicComponents' ) );

	}

	/**
	 * enqueue scripts	 
	 */
	public function enqueue_scripts(){

		wp_enqueue_script( 'sb-dc', SB_DC_URL . 'assets/scripts/sb-dc.js', array( 'jquery' ), '1.0', true );

	}

	/**
	 * ajax handler that renders dynamic components	 	
	 * @uses apply_filters() Calls 'sb_dc_user_data_fields' on user data fields. 
	 * @uses apply_filters() Calls 'sb_dc_rendered_components' on final response.
	 */
	public function ajaxRenderDynamicComponents(){

		$response = array();

		$user_logged_in = is_user_logged_in();

		$response[ 'is_user_logged_in' ] = $user_logged_in;

		if ( is_user_logged_in() ){

			$user = wp_get_current_user();

			$user_data_fields = array(
				'ID', 'user_login', 'display_name', 'user_email', 'user_firstname', 'user_lastname'
			);

			$user_data_fields = apply_filters( 'sb_dc_user_data_fields', $user_data_fields );

			$response[ 'user_data' ] = array();

			foreach ( $user_data_fields as $field )
				$response[ 'user_data' ][ $field ] = $user->$field; 

		}

		$requested_components = $_REQUEST[ 'components' ];

		if ( empty( $requested_components ) ){

			echo json_encode( $response );
			exit;

		}

		$args = unserialize( base64_decode( $_REQUEST[ 'args' ] ) );

		$component_names = explode( ',', $requested_components );

		foreach( $component_names as $component_name ){

			ob_start();

			try {

				do_action( "sb_dc_component-{$component_name}", $args );

				$data = ob_get_contents();
				ob_end_clean();

				if ( !empty( $data ) )
					$response[ $component_name ] = $data;

			} catch ( Exception $x ){				

				ob_end_clean();

				$response[ $component_name ] = '<span class="sb-dc-component-error">' . $x->getMessage() . '</span>';

				continue;

			}

		}

		$response = apply_filters( 'sb_dc_rendered_components', $response, $component_names, $args );

		echo json_encode( $response );
		exit;

	}

	/**
	 * render footer javascript necessary for everything to work	 	 
	 */
	public function renderFooterJS(){

		$component_names = array_keys( $this->components );

		?>

		<script type="text/javascript">
			var SB_DC_COMPONENTS = "<?php echo implode( ',', $component_names ) ?>";
			var SB_DC_PAGE_ARGS = "<?php echo base64_encode( serialize( $this->page_args ) ) ?>";
			var SB_DC_AJAXURL = "<?php echo admin_url( 'admin-ajax.php' ) ?>";
		</script>

		<?php

	}

	/**
	 * register and insert dynamic component into page
	 * @param  string                    $name [description]
	 * @param  array                     $args [description]
	 * @param  string                    $tag  [description]	 
	 */
	public function insertComponent( $name, $args = null, $tag = 'div' ){

		$this->registerComponent( $name, $args );

		echo "<{$tag} id=\"sb-dc-{$name}\"></{$tag}>";

	}

	/**
	 * register dyamic component	 
	 * @param  string                    $name [description]
	 * @param  array                     $args [description]	 
	 */
	public function registerComponent( $name, $args = null ){

		$this->components[ $name ] = $name;

		foreach( wp_parse_args( $args ) as $key => $value ) 
			$this->registerPageArg( $key, $value );

	}

	/**
	 * get registered components	 
	 * @return array
	 */
	public function getComponents(){

		return $this->components;

	}

	/**
	 * register page argument	 
	 * @param  string                    $key   [description]
	 * @param  mixed                     $value [description]	 
	 */
	public function registerPageArg( $key, $value ){

		$this->page_args[ $key ] = $value;

	}

	/**
	 * get registered page arguments
	 * @author brandon@digitaltrends.com
	 * @return array
	 */
	public function getPageArgs(){

		return $this->page_args;

	}

}

$SB_DC_Components = SB_DC_Components::getInstance();