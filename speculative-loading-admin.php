<?php
/**
 * Plugin Name: Speculative Loading Admin
 * Plugin URI: https://github.com/westonruter/speculative-loading-admin
 * Description: Adds speculative loading to the WP Admin for prerendering links with moderate eagerness in the Admin Bar and Admin Menu. Use at your own risk.
 * Requires at least: 6.8
 * Requires PHP: 8.1
 * Version: 0.1.0
 * Author: Weston Ruter
 * Author URI: https://weston.ruter.net/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Update URI: https://github.com/westonruter/speculative-loading-admin
 * GitHub Plugin URI: https://github.com/westonruter/speculative-loading-admin
 * Primary Branch: main
 *
 * @package WestonRuter\SpeculativeLoadingAdmin
 */

namespace WestonRuter\SpeculativeLoadingAdmin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // @codeCoverageIgnore
}

/**
 * Plugin version.
 *
 * @since 0.1.0
 * @var string
 */
const VERSION = '0.1.0';

/**
 * Prints Speculation Rules for the WP Admin.
 *
 * @since 0.1.0
 */
function print_admin_speculation_rules(): void {
	$rules = array(
		'prerender' => array(
			array(
				'eagerness' => 'moderate',
				'where'     => array(
					'and' => array(
						array(
							'selector_matches' => '#wpadminbar a, #adminmenu a',
						),
						array(
							'not' => array(
								'href_matches' => wp_parse_url( admin_url( '/post-new.php' ), PHP_URL_PATH ),
							),
						),
						array(
							'not' => array(
								'href_matches' => '/*\\?*(^|&)_wpnonce=*',
							),
						),
					),
				),
			),
		),
	);

	wp_print_inline_script_tag(
		(string) wp_json_encode( $rules ),
		array( 'type' => 'speculationrules' )
	);
}

add_action( 'admin_print_footer_scripts', __NAMESPACE__ . '\print_admin_speculation_rules' );
