<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://appest.xyz/
 * @since      1.0.0
 *
 * @package    Astro
 * @subpackage Astro/public
 */
class Astro_Public {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Astro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Astro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
        $options = get_option('astro-option-settings');
        $options = !empty($options) ? json_decode($options, true) : '';
        if (is_page($options['exclude_style'])) {
            wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/astro-public.css', array(), $this->version, 'all' );
            wp_enqueue_style( $this->plugin_name . '_bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css', array(), $this->version, 'all' );
            wp_enqueue_style( $this->plugin_name . '_datatables', '//cdn.datatables.net/1.12.0/css/jquery.dataTables.min.css', array(), $this->version, 'all' );
            wp_enqueue_style( $this->plugin_name . '_jqueryui', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css', array(), $this->version, 'all' );
            wp_enqueue_style( $this->plugin_name . '_assests', plugin_dir_url( __FILE__ ) . 'assets/css/style.css', array(), $this->version, 'all' );
        }
	}
    // wp_enqueue_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css' );
    //}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

        $options = get_option('astro-option-settings');
        $options = !empty($options) ? json_decode($options, true) : '';

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Astro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Astro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
        if (is_page($options['exclude_style'])) {
            wp_enqueue_script( $this->plugin_name . '_astro_jquery', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.min.js', array( '' ), $this->version, true );
            wp_enqueue_script( $this->plugin_name . '_astro_bootstrap', plugin_dir_url( __FILE__ ) . 'assets/js/bootstrap.min.js', array( 'jquery' ), $this->version, true );
            wp_enqueue_script( $this->plugin_name . '_astro_jqueryui_js', plugin_dir_url( __FILE__ ) . 'assets/js/jquery-ui.min.js', array( 'jquery' ), $this->version, true );
            wp_enqueue_script( $this->plugin_name . '_astro_inputmask', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.inputmask.js', array( 'jquery' ), $this->version, true );
            wp_enqueue_script( $this->plugin_name . '_astro_dataTables', '//cdn.datatables.net/1.12.0/js/jquery.dataTables.min.js', array( 'jquery' ), $this->version, true );
            wp_enqueue_script( $this->plugin_name . '_app', plugin_dir_url( __FILE__ ) . 'assets/js/app.js', array( 'jquery' ), $this->version, true );
            wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/astro-public.js', array( 'jquery' ), $this->version, true );
        }
        wp_localize_script($this->plugin_name, "ajax_obj", array('ajaxurl' => admin_url( 'admin-ajax.php' )));
	}
}
