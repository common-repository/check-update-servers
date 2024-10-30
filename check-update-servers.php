<?php
/**
 * Plugin Name:         Check Update Servers
 * Description:         Check whether is wordpress.org plugin library is reachable by your website host. This plugin sends a small curl request to check that your website can communicate with api.wordpress.org.
 * Version:             1.0.0
 * Requires at least:   5.2
 * Requires PHP:        7.4
 * Author:              Gergo Simko
 * Author URI:          https://simko.me/
 * License:             GPLv2 or later
 * License URI:         https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain:         check-update-servers
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.
 
if( ! class_exists( 'CheckUpdateServers' ) ) :

  class CheckUpdateServers {
    
    /**
     * Singleton instance.
     *
     * @since 1.0.0
     * @access private
     * @var CheckUpdateServers $instance Instance of the class.
     */
    private static $instance = null;
    
    /**
     * Plugin's name.
     *
     * @since 1.0.0
     * @access private
     * @var string $plugin_name Plugin's full name.
     */
    private $plugin_name;
    
    /**
     * Plugin's slug.
     *
     * @since 1.0.0
     * @var string $plugin_slug Plugin's slug.
     */
    private $plugin_slug;
    
    /**
     * Plugin's version.
     *
     * @since 1.0.0
     * @access private
     * @var string $version Plugin's actual version string.
     */
    private $version;
    
    /**
     * Constructor.
     *
     * @since 1.0.0
     * @access private
     *
     * @return void
     */
    private function __construct() {
      
      $this->plugin_name = "Check Update Servers";
      $this->plugin_slug = "check-update-servers";
      $this->version = "1.0.0";
      
      add_action( 'admin_menu', array( $this, 'add_submenu' ) );
      add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
      
    }
    
    /**
     * Singleton initializer. 
     *
     * @since 1.0.0
     *
     * @return CheckUpdateServers Instance of the class.
     */
    public static function init() {
      if( self::$instance === null ) {
        self::$instance = new self();
      }
      
      return self::$instance;
    }
    
    /**
     * Adds submenu under Plugins.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function add_submenu() {
      add_submenu_page(
        'plugins.php',
        esc_html__( $this->plugin_name, "check-update-servers" ),
        esc_html__( $this->plugin_name, "check-update-servers" ),
        'manage_options',
        esc_html__( $this->plugin_slug, "check-update-servers" ),
        array( $this, 'display_admin_page_content' ),
      );
    }
    
    /**
     * Adds a new link below the plugin's name. 
     *
     * @since 1.0.0
     *
     * @param string[] $actions An array of plugin action links. 
     * @return string[] A modified array of plugin action links. 
     */
    public function plugin_action_links( $actions ) {
      $actions = array_merge( array(
        '<a href="' . esc_url( admin_url( 'plugins.php?page=check-update-servers' ) ) . '">' . esc_html__( 'Check Status', 'check-update-servers' ) . '</a>'
      ), $actions );
      
      return $actions;
    }
    
    /**
     * Renders the plugin's admin page.
     *
     * @since 1.0.0
     * 
     * @return void
     */
    public function display_admin_page_content() {
      ?>
      <div class="wrap">
        <h2><?php echo esc_html_e( $this->plugin_name, "check-update-servers" ); ?></h2>
        
        <div class="card" style="display:flex;align-items:center;justify-content:space-between;">
          <p>
            <?php esc_html_e( "Checking if wordpress.org is reachable", "check-update-servers" ); ?>
          </p>
          <p>
            <span class="spinner is-active" style="margin:0"></span>
            <span class="dashicons dashicons-yes success" style="color:#00ba37;display:none;"></span>
            <span class="dashicons dashicons-dismiss error" style="color:#f86368;display:none;"></span>
          </p>
        </div>
      </div>
      
      <script>
      const jq = jQuery.noConflict();
      jq( document ).ready(function( $ ){
        $.ajax({
          url: "https://api.wordpress.org/plugins/info/1.0/hello-dolly.json",
          success: function() {
            $('.spinner').hide();
            $('.success').show();
          },
          error: function() {
            $('.spinner').hide();
            $('.error').show();
          }
        });
      });
      </script>
      <?php
    }
  }
  
  // run the plugin
  $check_update_servers = CheckUpdateServers::init();
  
endif; // class_exists
