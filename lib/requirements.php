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

require_once CBQE_EF_DIR_LIB . '/aihrus/requirements.php';


function cbqe_ef_requirements_check() {
	$valid_requirements = true;
	if ( ! aihr_check_php( CBQE_EF_BASE, CBQE_EF_NAME ) ) {
		$valid_requirements = false;
	}

	if ( ! aihr_check_wp( CBQE_EF_BASE, CBQE_EF_NAME ) ) {
		$valid_requirements = false;
	}

	if ( ! is_plugin_active( CBQE_EF_REQ_BASE ) ) {
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
