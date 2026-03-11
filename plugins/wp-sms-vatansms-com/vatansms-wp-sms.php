<?php
/**
 * Plugin Name: WP SMS VatanSMS.com
 * Plugin URI: https://vatansms.com/
 * Description: VatanSMS WordPress
 * Version: 1.01  
 * Author: VatanSMS
 * Author URI: https://vatansms.com/
 * Text Domain: WP SMS VatanSMS.com
 * Domain Path: https://vatansms.com/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Load Plugin Defines
 */
require_once 'includes/defines.php';

/**
 * Load plugin Special Functions
 */
require_once 'includes/functions.php';

/**
 * Get plugin options
 */
$wpsms_option = get_option( 'wpsms_settings' );

/**
 * Initial gateway
 */
require_once 'includes/class-wpsms-gateway.php';

$sms = wp_sms_initial_gateway();

/**
 * Load Plugin
 */
require 'includes/class-wpsms.php';

new WP_SMS();
