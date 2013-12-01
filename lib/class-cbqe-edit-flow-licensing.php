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

require_once CBQE_PLUGIN_DIR_LIB . '/aihrus/class-aihrus-licensing.php';


class Custom_Bulkquick_Edit_Edit_Flow_Licensing extends Aihrus_Licensing{
	public function __construct() {
		parent::__construct( Custom_Bulkquick_Edit_Edit_Flow::SLUG, Custom_Bulkquick_Edit_Edit_Flow::ITEM_NAME );

		add_filter( 'cbqe_settings', array( $this, 'settings' ), 5 );
	}


	public function settings( $settings ) {
		$title = esc_html__( 'License Key for %1$s', 'cbqe-edit-flow' );

		$settings[ Custom_Bulkquick_Edit_Edit_Flow::SLUG . 'license_key' ] = array(
			'section' => 'premium',
			'title' => sprintf( $title, Custom_Bulkquick_Edit_Edit_Flow::ITEM_NAME ),
			'desc' => esc_html__( 'Required to enable premium plugin updating. Activation is automatic. Use `0` to deactivate.', 'cbqe-edit-flow' ),
			'validate' => 'cbqe_ef_update_license',
			'widget' => 0,
		);

		return $settings;
	}


}


/**
 *
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */


function cbqe_ef_update_license( $license ) {
	global $CBQE_EF_Licensing;

	$result = $CBQE_EF_Licensing->update_license( $license );
	if ( 32 !== strlen( $result ) )
		Custom_Bulkquick_Edit_Edit_Flow::set_notice( 'notice_license' );

	return $result;
}


?>
