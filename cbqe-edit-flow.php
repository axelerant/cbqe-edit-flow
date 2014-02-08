<?php
/**
 * Plugin Name: Custom Bulk/Quick Edit by Aihrus - Edit Flow
 * Plugin URI: http://wordpress.org/plugins/cbqe-edit-flow/
 * Description: Modify Edit Flow options via bulk and quick edit panels in conjunction with Custom Bulk/Quick Edit by Aihrus.
 * Version: 1.2.0-alpha
 * Author: Michael Cannon
 * Author URI: http://aihr.us/resume/
 * License: GPLv2 or later
 * Text Domain: cbqe-edit-flow
 * Domain Path: /languages
 */


/**
 * Copyright 2013 Michael Cannon (email: mc@aihr.us)
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

define( 'CBQE_EF_AIHR_VERSION', '1.0.2' );
define( 'CBQE_EF_BASE', plugin_basename( __FILE__ ) );
define( 'CBQE_EF_DIR', plugin_dir_path( __FILE__ ) );
define( 'CBQE_EF_DIR_INC', CBQE_EF_DIR . 'includes/' );
define( 'CBQE_EF_DIR_LIB', CBQE_EF_DIR_INC . 'libraries/' );
define( 'CBQE_EF_EXT_BASE', 'edit-flow/edit_flow.php' );
define( 'CBQE_EF_EXT_NAME', 'Edit Flow' );
define( 'CBQE_EF_EXT_SLUG', 'edit-flow' );
define( 'CBQE_EF_EXT_VERSION', '0.8' );
define( 'CBQE_EF_NAME', 'Edit Flow for Custom Bulk/Quick Edit by Aihrus' );
define( 'CBQE_EF_REQ_BASE', 'custom-bulkquick-edit/custom-bulkquick-edit.php' );
define( 'CBQE_EF_REQ_NAME', 'Custom Bulk/Quick Edit by Aihrus' );
define( 'CBQE_EF_REQ_SLUG', 'custom-bulkquick-edit' );
define( 'CBQE_EF_REQ_VERSION', '1.5.0' );
define( 'CBQE_EF_VERSION', '1.2.0-alpha' );

require_once CBQE_EF_DIR_INC . 'requirements.php';

if ( ! cbqe_ef_requirements_check() ) {
	return false;
}

require_once CBQE_EF_DIR_INC . 'class-cbqe-edit-flow.php';

add_action( 'after_setup_theme', 'cbqe_ef_init', 99 );


/**
 *
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
function cbqe_ef_init() {
	if ( ! is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) )
		return;

	if ( Custom_Bulkquick_Edit_Edit_Flow::version_check() ) {
		global $Custom_Bulkquick_Edit_Edit_Flow;
		if ( is_null( $Custom_Bulkquick_Edit_Edit_Flow ) )
			$Custom_Bulkquick_Edit_Edit_Flow = new Custom_Bulkquick_Edit_Edit_Flow();

		do_action( 'cbqe_ef_init' );
	}
}


register_activation_hook( __FILE__, array( 'Custom_Bulkquick_Edit_Edit_Flow', 'activation' ) );
register_deactivation_hook( __FILE__, array( 'Custom_Bulkquick_Edit_Edit_Flow', 'deactivation' ) );
register_uninstall_hook( __FILE__, array( 'Custom_Bulkquick_Edit_Edit_Flow', 'uninstall' ) );

?>
