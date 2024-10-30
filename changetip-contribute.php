<?php

namespace ChangeTip_Contribute;

/**
 * ChangePay
 *
 * @link              http://wordpress.org/plugins/changepay-contribute/
 * @since             1.0.0
 * @package           ChangeTip Contribute
 *
 * @wordpress-plugin
 * Plugin Name:       ChangeTip Contribute
 * Plugin URI:        http://wordpress.org/plugins/changepay-contribute/
 * Description:       Recover lost ad revenue and create new revenue streams with ChangeTip Contribute alternatives to paywall and subscription. Offer your readers the choice between ads or to pay pennies per page to view your site without ads.
 * Version:           1.0.0
 * Author:            ChangeTip
 * Author URI:        https://www.changetip.com/
 * Text Domain:       changepay
 * Contributors:      Evan Nagle, Steve Sobel
 */

 // if this file is called directly, abort.
if ( ! defined( 'WPINC' ) )  die;

define( 'CHANGETIP_CONTRIBUTE_DIR', plugin_dir_path( __FILE__ ) );

/**
* The core plugin class that is used to define internationalization,
* admin-specific hooks, and public-facing site hooks.
*/
require CHANGETIP_CONTRIBUTE_DIR . 'autoload/class-changetip-contribute-plugin.php';

ChangeTip_Contribute_Plugin::load();
