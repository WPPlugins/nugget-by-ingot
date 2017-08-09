<?php
/**
Plugin Name: Nugget by Ingot: Easy, automated and native A/B testing for everyone.
Version: 1.0.0
Plugin URI:  http://IngotHQ.com
Description: A nugget of Ingot, the easy A/B testing solution for WordPress
Author:      Ingot LLC
Author URI:  http://IngotHQ.com
Text Domain: ingot
Domain Path: /languages
 */
/**
 * Copyright 2015-2015 Ingot LLC
 *
 * Licensed under the terms of the GNU General Public License version 2 or later
 */

if( ! defined( 'INGOT_VER' ) ){
	define( 'INGOT_VER', '1.0.0' );
	define( 'INGOT_TYPE', 'NUGGET' );
	define( 'INGOT_URL', plugin_dir_url( __FILE__ ) );
	define( 'INGOT_DIR', dirname( __FILE__ ) );
	define( 'INGOT_ASSETS_DIR', trailingslashit( INGOT_DIR ) . 'vendor/ingot/ingot-core/assets' );
	define( 'INGOT_ASSETS_URL', trailingslashit( INGOT_URL ) . 'vendor/ingot/ingot-core/assets' );
	define( 'INGOT_UI_PARTIALS_DIR', dirname( __FILE__ ) . 'vendor/ingot/ingot-core/classes/ui/admin/partials/' );
	define( 'INGOT_ROOT', basename( dirname( __FILE__ ) ) );


	/**
	 * Actions to boot up plugin
	 *
	 * @since 1.0.0
	 */
	add_action( 'plugins_loaded', 'ingot_maybe_load', 0 );

	/**
	 * Load plugin if possible
	 *
	 * @since 1.0.0
	 */
	function ingot_maybe_load() {
		$fail = false;
		if ( ! version_compare( PHP_VERSION, '5.4.0', '>=' ) ) {
			$fail = true;
			if ( is_admin() ) {
				include_once( dirname( __FILE__ ) . '/vendor/calderawp/dismissible-notice/src/functions.php' );
				$message = esc_html__( sprintf( 'Nugget by Ingot requires PHP version 5.4.0 or later. Current version is %s.', PHP_VERSION ), 'ingot' );
				echo caldera_warnings_dismissible_notice( $message, true, 'activate_plugins' );
			}

		}
		global $wp_version;
		if ( ! version_compare( $wp_version, '4.4', '>=' ) ) {
			$fail = true;
			if ( is_admin() ) {
				include_once( dirname( __FILE__ ) . '/vendor/calderawp/dismissible-notice/src/functions.php' );
				$message = esc_html__( sprintf( 'Ingot requires WordPress version 4.4 or later. Current version is %s.', $wp_version ), 'ingot' );
				echo caldera_warnings_dismissible_notice( $message, true, 'activate_plugins' );
			}

		}
		if( false == $fail ){

			/**
			 * Runs before Ingot is loaded
			 *
			 * NOTE: Only runs if version checks pass
			 *
			 * @since 1.2.0
			 */
			do_action( 'ingot_before' );

			include_once dirname( __FILE__ ) . '/vendor/ingot/ingot-core/includes/functions.php';
			include_once dirname( __FILE__ ) . '/vendor/ingot/ingot-core/includes/licensing.php';
			include_once dirname( __FILE__ ) . '/vendor/ingot/ingot-core/includes/active_check.php';
			include_once( dirname(__FILE__ ) . '/ingot_bootstrap.php' );
			add_action( 'plugins_loaded', array( 'ingot_bootstrap', 'maybe_load' ) );

		}

	}



	/**
	 * Load translations
	 *
	 * @since 1.0.0
	 */
	add_action( 'plugins_loaded', 'ingot_load_textdomain' );

	/**
	 * Load plugin textdomain.
	 *
	 * @since 1.1.0
	 */
	function ingot_load_textdomain() {
		load_plugin_textdomain( 'ingot', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}


}


if ( ! function_exists( 'ingot_fs' ) ) {
	/**
	 * Load Freemius
	 *
	 * @since 1.0.0
	 *
	 * @return \Freemius
	 */
	function ingot_fs() {
		global $ingot_fs;

		if ( ! isset( $ingot_fs ) ) {

			require_once dirname( __FILE__ ) . '/vendor/freemius/wordpress-sdk/start.php';

			$ingot_fs = fs_dynamic_init( array(
				'id'               => '210',
				'slug'             => 'nugget-by-ingot',
				'public_key'       => 'pk_e6a19a3508bdb9bdc91a7182c8e0c',
				'is_live'          => true,
				'is_premium'       => false,
				'has_addons'       => true,
				'has_paid_plans'   => true,
				'is_org_compliant' => true,
				'menu'             => array(
					'slug'       => 'ingot-admin-app',
					'support'    => false,
					'first-path' => 'admin.php?page=ingot-admin-app#',
				),

			) );

		}

		return $ingot_fs;
	}

	ingot_fs();

}



