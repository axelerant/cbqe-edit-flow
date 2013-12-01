<?php
/**
 * Plugin Name: Custom Bulk/Quick Edit - Edit Flow
 * Plugin URI: http://wordpress.org/plugins/cbqe-edit-flow/
 * Description: Modify Edit Flow options via bulk and quick edit panels in conjunction with Custom Bulk/Quick Edit.
 * Version: 1.0.2
 * Author: Michael Cannon
 * Author URI: http://aihr.us/resume/
 * License: GPLv2 or later
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
if ( ! defined( 'CBQE_PLUGIN_DIR' ) )
	define( 'CBQE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) . '/../custom-bulkquick-edit' );

if ( ! defined( 'CBQE_PLUGIN_DIR_LIB' ) )
	define( 'CBQE_PLUGIN_DIR_LIB', CBQE_PLUGIN_DIR . '/lib' );

if ( ! defined( 'CBQE_EF_PLUGIN_DIR' ) )
	define( 'CBQE_EF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

if ( ! defined( 'CBQE_EF_PLUGIN_DIR_LIB' ) )
	define( 'CBQE_EF_PLUGIN_DIR_LIB', CBQE_EF_PLUGIN_DIR . '/lib' );

require_once CBQE_PLUGIN_DIR_LIB . '/aihrus/class-aihrus-common.php';


class Custom_Bulkquick_Edit_Edit_Flow extends Aihrus_Common {
	const BASE_PLUGIN_BASE = 'custom-bulkquick-edit/custom-bulkquick-edit.php';
	const BASE_VERSION     = '1.3.2';
	const EXT_BASE         = 'edit-flow/edit_flow.php';
	const EXT_VERSION      = '0.7.6';
	const ID               = 'cbqe-edit-flow';
	const ITEM_NAME        = 'Edit Flow for Custom Bulk/Quick Edit';
	const PLUGIN_BASE      = 'cbqe-edit-flow/cbqe-edit-flow.php';
	const SLUG             = 'cbqe_ef_';
	const VERSION          = '1.0.2';

	public static $ef_date;
	public static $ef_fields = array();
	public static $ef_number;
	public static $ef_taxonomy;

	public static $class = __CLASS__;
	public static $notice_key;


	public function __construct() {
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		add_action( 'init', array( __CLASS__, 'init' ) );
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.LongVariable)
	 */
	public static function admin_init() {
		if ( ! self::version_check() )
			return;

		add_filter( 'plugin_action_links', array( __CLASS__, 'plugin_action_links' ), 10, 2 );

		if ( ! Custom_Bulkquick_Edit::do_load() )
			return;

		add_action( 'cbqe_save_post', array( __CLASS__, 'save_post' ) );
		add_filter( 'cbqe_manage_posts_custom_column_field_type', array( __CLASS__, 'manage_posts_custom_column_field_type' ), 10, 4 );
		add_filter( 'cbqe_settings_fields', array( __CLASS__, 'settings_fields' ), 10, 2 );
		add_filter( 'cbqe_settings_taxonomies', array( __CLASS__, 'settings_taxonomies' ) );
	}


	public static function init() {
		load_plugin_textdomain( self::ID, false, 'cbqe-edit-flow/languages' );
	}


	public static function plugin_action_links( $links, $file ) {
		if ( self::PLUGIN_BASE == $file )
			array_unshift( $links, Custom_Bulkquick_Edit::$settings_link );

		return $links;
	}


	public static function activation() {
		if ( ! current_user_can( 'activate_plugins' ) )
			return;

		if ( ! is_plugin_active( Custom_Bulkquick_Edit_Edit_Flow::BASE_PLUGIN_BASE ) ) {
			deactivate_plugins( Custom_Bulkquick_Edit_Edit_Flow::PLUGIN_BASE );
			add_action( 'admin_notices', array( 'Custom_Bulkquick_Edit_Edit_Flow', 'notice_version' ) );
			return;
		}

		if ( ! is_plugin_active( Custom_Bulkquick_Edit_Edit_Flow::EXT_BASE ) ) {
			deactivate_plugins( Custom_Bulkquick_Edit_Edit_Flow::PLUGIN_BASE );
			add_action( 'admin_notices', array( 'Custom_Bulkquick_Edit_Edit_Flow', 'notice_version_ef' ) );
			return;
		}
	}


	public static function deactivation() {
		if ( ! current_user_can( 'activate_plugins' ) )
			return;

		Custom_Bulkquick_Edit_Edit_Flow::delete_notices();
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.LongVariable)
	 */
	public static function uninstall() {
		if ( ! current_user_can( 'activate_plugins' ) )
			return;
	}


	public static function notice_0_0_1() {
		$text = sprintf( __( 'If your Edit Flow for Custom Bulk/Quick Edit display has gone to funky town, please <a href="%s">read the FAQ</a> about possible CSS fixes.', 'cbqe-edit-flow' ), 'https://aihrus.zendesk.com/entries/23722573-Major-Changes-Since-2-10-0' );

		self::notice_updated( $text );
	}


	public static function version_check() {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		$base         = self::PLUGIN_BASE;
		$good_version = true;

		if ( ! is_plugin_active( $base ) )
			$good_version = false;

		if ( is_plugin_inactive( self::BASE_PLUGIN_BASE ) || Custom_Bulkquick_Edit::VERSION < self::BASE_VERSION )
			$good_version = false;

		if ( ! $good_version && is_plugin_active( $base ) ) {
			deactivate_plugins( $base );
			self::set_notice( 'notice_version' );
		}

		if ( is_plugin_inactive( self::EXT_BASE ) || EDIT_FLOW_VERSION < self::EXT_VERSION && is_plugin_active( $base ) ) {
			deactivate_plugins( $base );
			self::set_notice( 'notice_version_ef' );

			$good_version = false;
		}

		if ( ! $good_version )
			self::check_notices();

		return $good_version;
	}



	public static function notice_version( $free_base = null, $free_name = null, $free_slug = null, $free_version = null, $item_name = null ) {
		$free_base    = self::BASE_PLUGIN_BASE;
		$free_name    = 'Custom Bulk/Quick Edit';
		$free_slug    = 'custom-bulkquick-edit';
		$free_version = self::BASE_VERSION;
		$item_name    = self::ITEM_NAME;

		parent::notice_version( $free_base, $free_name, $free_slug, $free_version, $item_name );
	}


	public static function notice_version_ef( $free_base = null, $free_name = null, $free_slug = null, $free_version = null, $item_name = null ) {
		$free_base    = self::EXT_BASE;
		$free_name    = 'Edit Flow';
		$free_slug    = 'edit-flow';
		$free_version = self::EXT_VERSION;
		$item_name    = self::ITEM_NAME;

		parent::notice_version( $free_base, $free_name, $free_slug, $free_version, $item_name );
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function manage_posts_custom_column_field_type( $current, $field_type, $column, $post_id ) {
		$result = $current;
		switch ( $field_type ) {
		case 'float':
			if ( false !== strstr( $column, self::$ef_number ) && is_numeric( $result ) )
				$result = intval( $current );
			break;

		case 'date':
			if ( false !== strstr( $column, self::$ef_date ) && is_numeric( $result ) )
				$result = date( get_option( 'date_format' ), $result );
			break;
		}

		return $result;
	}


	public static function settings_taxonomies( $taxonomies ) {
		$ignore = array(
			EF_User_Groups::taxonomy_key,
			EF_Editorial_Metadata::metadata_taxonomy,
		);

		foreach ( $taxonomies as $key => $taxonomy ) {
			if ( in_array( $key, $ignore ) )
				unset( $taxonomies[ $key ] );
		}

		return $taxonomies;
	}


	public static function settings_fields( $fields, $post_type ) {
		if ( ! is_array( $fields ) )
			return $fields;

		if ( class_exists( 'EF_Editorial_Metadata' )  ) {
			$options = get_option( 'edit_flow_editorial_metadata_options' );
			if ( empty( $options ) || empty( $options->post_types[ $post_type ] ) || 'on' != $options->post_types[ $post_type ] ) 
				return $fields;

			$efem  = new EF_Editorial_Metadata();
			$terms = $efem->get_editorial_metadata_terms();
			foreach ( $terms as $term ) {
				if ( is_null( self::$ef_taxonomy ) )
					self::build_edit_flow_structures( $term );

				$meta_key = '_' . self::$ef_taxonomy . '_' . $term->type . '_' . $term->slug;
				$fields[ $meta_key ] = $term->name;
			}
		}

		return $fields;
	}


	public static function build_edit_flow_structures( $term ) {
		self::$ef_taxonomy = $term->taxonomy;
		self::$ef_date     = '_' . self::$ef_taxonomy . '_date_';
		self::$ef_number   = '_' . self::$ef_taxonomy . '_number_';
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function save_post( $post_id ) {
		foreach ( $_POST as $field => $value ) {
			if ( '' == $value && Custom_Bulkquick_Edit::$bulk_edit_save )
				continue;

			$field = str_replace( Custom_Bulkquick_Edit::SLUG, '', $field );
			if ( false !== strstr( $field, self::$ef_date ) ) {
				$date = strtotime( $value );
				update_post_meta( $post_id, $field, $date );
			}

			if ( false !== strstr( $field, self::$ef_number ) ) {
				$number = intval( $value );
				update_post_meta( $post_id, $field, $number );
			}
		}
	}


}


register_activation_hook( __FILE__, array( 'Custom_Bulkquick_Edit_Edit_Flow', 'activation' ) );
register_deactivation_hook( __FILE__, array( 'Custom_Bulkquick_Edit_Edit_Flow', 'deactivation' ) );
register_uninstall_hook( __FILE__, array( 'Custom_Bulkquick_Edit_Edit_Flow', 'uninstall' ) );


add_action( 'plugins_loaded', 'cbqe_ef_init', 99 );


/**
 *
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
function cbqe_ef_init() {
	if ( ! is_admin() )
		return;

	require_once CBQE_PLUGIN_DIR_LIB . '/class-custom-bulkquick-edit-settings.php';

	if ( Custom_Bulkquick_Edit_Edit_Flow::version_check() ) {
		global $Custom_Bulkquick_Edit_Edit_Flow;
		if ( is_null( $Custom_Bulkquick_Edit_Edit_Flow ) )
			$Custom_Bulkquick_Edit_Edit_Flow = new Custom_Bulkquick_Edit_Edit_Flow();

		do_action( 'cbqe_ef_init' );
	}
}


?>
