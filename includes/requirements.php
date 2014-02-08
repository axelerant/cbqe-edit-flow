<?php
/*
	Copyright 2013 Michael Cannon (email: mc@aihr.us)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

require_once ABSPATH . 'wp-admin/includes/plugin.php';

if ( ! function_exists( 'aihr_notice_version' ) ) {
	function aihr_notice_version( $required_base, $required_name, $required_slug, $required_version, $item_name ) {
		$is_active = is_plugin_active( $required_base );
		if ( $is_active )
			$link = sprintf( __( '<a href="%1$s">update to</a>', 'gc-testimonials-to-testimonials' ), self_admin_url( 'update-core.php' ) );
		else {
			$plugins = get_plugins();
			if ( empty( $plugins[ $required_base ] ) ) {
				$install = esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $required_slug ), 'install-plugin_' . $required_slug ) );
				$link    = sprintf( __( '<a href="%1$s">install</a>', 'gc-testimonials-to-testimonials' ), $install );
			} else {
				$activate = esc_url( wp_nonce_url( admin_url( 'plugins.php?action=activate&plugin=' . $required_base ), 'activate-plugin_' . $required_base ) );
				$link     = sprintf( __( '<a href="%1$s">activate</a>', 'gc-testimonials-to-testimonials' ), $activate );
			}
		}

		$text = sprintf( __( 'Plugin "%3$s" has been deactivated. Please %1$s "%4$s" version %2$s or newer before activating "%3$s".', 'gc-testimonials-to-testimonials' ), $link, $required_version, $item_name, $required_name );

		$content  = '<div class="error"><p>';
		$content .= $text;
		$content .= '</p></div>';

		echo $content;
	}
}


function cbqe_ef_requirements_check() {
	$valid_requirements = true;
	if ( ! is_plugin_active( CBQE_EF_REQ_BASE ) && ! is_plugin_active( CBQE_EF_REQ_BASE_PREM ) ) {
		$valid_requirements = false;
		add_action( 'admin_notices', 'cbqe_ef_notice_version' );
	}

	if ( ! is_plugin_active( CBQE_EF_EXT_BASE ) ) {
		$valid_requirements = false;
		add_action( 'admin_notices', 'cbqe_ef_notice_version_ef' );
	}

	if ( ! $valid_requirements ) {
		deactivate_plugins( CBQE_EF_BASE );
	}

	return $valid_requirements;
}


function cbqe_ef_notice_version() {
	aihr_notice_version( CBQE_EF_REQ_BASE, CBQE_EF_REQ_NAME, CBQE_EF_REQ_SLUG, CBQE_EF_REQ_VERSION, CBQE_EF_NAME );
}


function cbqe_ef_notice_version_ef() {
	aihr_notice_version( CBQE_EF_EXT_BASE, CBQE_EF_EXT_NAME, CBQE_EF_EXT_SLUG, CBQE_EF_EXT_VERSION, CBQE_EF_NAME );
}

?>
