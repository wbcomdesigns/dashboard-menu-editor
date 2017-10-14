<?php
 /*
 * Plugin Name: WBCOM Dashboard Menu Editor
 * Plugin URI: https://wbcomdesigns.com/downloads
 * Description: provides solution to change positions of admin panel menus & submenus.
 * Version: 1.0.0
 * Tags: admin menu, wp dashboard menu, menu reorder
 * Author: Wbcom Designs
 * Author URI: https://wbcomdesigns.com
 * Tested up to: 4.8.2
 * Stable tag: 1.0.0
 * License: GPL2
 * http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wb-dashboard-menu-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DBME_PLUGIN_PATH', plugin_dir_path(__FILE__) );
define( 'DBME_PLUGIN_URL', plugin_dir_url(__FILE__) );
define( 'DBME_TEXT_DOMAIN', 'wb-dashboard-menu-editor' );

/**
 * Constants used in the plugin
 *  @since   1.0.0
 *  @author  Wbcom Designs
*/

define( 'WP_DME_DOMAIN', 'wb-dashboard-menu-editor' );

if( !class_exists('Menu_Editor') ) {

	/**
	* Wrap all the methods and members
	*/

	class Menu_Editor{

    /** constructor to load all the functionality
     *  @since   1.0.0
     *  @access   public
     *  @author  Wbcom Designs
     */

		public function __construct(){
			$this->version = '1.0.0';
			add_action('admin_menu', array($this,'admin_menu_editor_page'));
			add_action('admin_init',array($this,'enqueue_plugin_scripts'));
			add_action('wp_ajax_custom_order',array($this,'custom_menu_orders'));
			add_action('wp_ajax_reorder',array($this,'custom_menu_reorders'));
			add_filter('custom_menu_order', array($this,'custom_order'));
			add_filter('menu_order', array($this,'custom_order'),999);
			add_action('plugins_loaded',array($this,'wb_dashboard_setup_textdomain'));
		}

		public function wb_dashboard_setup_textdomain() {
		  $domain  = 'wb-dashboard-menu-editor';
		  $locale  = apply_filters( 'plugin_locale', get_locale(), $domain );
		  //first try to load from wp-content/languages/plugins/ directory
		  load_textdomain( $domain, WP_LANG_DIR . '/plugins/' . $domain . '-' . $locale . '.mo' );
		  //if not load from languages directory of plugin
		  load_plugin_textdomain( 'wb-dashboard-menu-editor', false, basename( dirname( __FILE__ ) ) . '/languages' );
		}

		/** Adds admin menu to view settings of plugin
     *  @since   1.0.0
     *  @access   public
     *  @author  Wbcom Designs
     */

		public function admin_menu_editor_page(){
			add_menu_page( 'Menu Editor', 'Menu Editor', 'manage_options', 'menu-editor', array($this,'menu_editor_settings') );
		}

    /** settings for plugin
     *  @since   1.0.0
     *  @access   public
     *  @author  Wbcom Designs
     */

		public function menu_editor_settings(){
			include_once('includes/admin-options.php');
		}

    /** Enqueue scripts
     *  @since   1.0.0
     *  @access   public
     *  @author  Wbcom Designs
     */

		public function enqueue_plugin_scripts(){
      if ( !wp_script_is( 'jquery-ui-sortable', 'enqueued' ) ) {
          wp_enqueue_script( 'jquery-ui-sortable' );
      }
			wp_enqueue_script('menu-editor-js',DBME_PLUGIN_URL.'/assets/js/menu-editor.js',array(), $this->version, false);
			wp_localize_script( 'menu-editor-js', 'ajax_object',array( 'ajax_url' => admin_url('admin-ajax.php')));
			wp_enqueue_style('menu-editor-css',DBME_PLUGIN_URL.'/assets/css/menu-editor.css',array(), $this->version, 'all');
		}

    /** saves details for menu orderings
     *  @since   1.0.0
     *  @access   public
     *  @author  Wbcom Designs
     */

		public function custom_menu_orders() {
			$subids = array();
      $subids = filter_var_array( $_POST['subid'], FILTER_SANITIZE_STRING );
			$ids = array();
			$ids = filter_var_array( $_POST['id'], FILTER_SANITIZE_STRING );
			update_option('custom_ordering', $ids );
			$custom_menu = get_option('custom_ordering');
			update_option('custom_subordering', $subids );
      _e( 'inserted', WP_DME_DOMAIN );
			die;
		}

		public function custom_menu_reorders(){
			update_option('custom_ordering', '' );
			update_option('custom_subordering', '' );
			die;
		}

    /** Send out custom menu as well as submenu order
     *  @since   1.0.0
     *  @access   public
     *  @author  Wbcom Designs
     */
		public function custom_order($menu_ord) {
			global $submenu;
			if (!$menu_ord) {
      	return true;
      }
			$custom_menu = array();
			$custom_menu = get_option('custom_ordering');
			$custom_sub = array();
			if( !empty( $custom_menu ) ) {
      	$menu_ord = $custom_menu;
			}
			$arr = array();
			$custom_sub = get_option('custom_subordering');
			if( !empty( $custom_sub ) ) {
				foreach( $submenu as $s_key => $s_val ) {
					$submenu_sequence = array();
					if( array_key_exists( $s_key, $custom_sub ) ) {
						$custom_key_val = $custom_sub[$s_key];
						foreach( $custom_key_val as $custom_sub_val ) {
							foreach ( $s_val as $s_val_key => $s_val_val ) {
								if( esc_attr($custom_sub_val) === esc_attr($s_val_val[2] )) {
									array_push( $submenu_sequence, $s_val_key );
								}
							}
						}
						if( !empty( $submenu_sequence ) ) {
							uksort($s_val, function($key1, $key2) use ( $submenu_sequence ) {
								return (array_search($key1, $submenu_sequence ) > array_search($key2, $submenu_sequence ));
							});
							$submenu[ $s_key ] = $s_val;
						}
					}
				}
			}
			return $menu_ord;
		}
	}
	new Menu_Editor();
}
