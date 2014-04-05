<?php
/**
 * Copyright 2014 Michael Cannon (email: mc@aihr.us)
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

require_once AIHR_DIR_INC . 'class-aihrus-common.php';

if ( class_exists( 'Custom_Bulkquick_Edit_Edit_Flow' ) )
	return;


class Custom_Bulkquick_Edit_Edit_Flow extends Aihrus_Common {
	const BASE    = CBQE_EF_BASE;
	const ID      = 'cbqe-edit-flow';
	const SLUG    = 'cbqe_ef_';
	const VERSION = CBQE_EF_VERSION;

	public static $custom_fields = array();
	public static $ef_checkbox;
	public static $ef_date;
	public static $ef_fields   = array();
	public static $ef_metadata = 'editorial-metadata';
	public static $ef_number;
	public static $ef_taxonomy;

	public static $class = __CLASS__;
	public static $notice_key;


	public function __construct() {
		parent::__construct();

		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		add_action( 'init', array( __CLASS__, 'init' ) );
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.LongVariable)
	 */
	public static function admin_init() {
		add_filter( 'plugin_action_links', array( __CLASS__, 'plugin_action_links' ), 10, 2 );
		add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_row_meta' ), 10, 2 );

		self::update();

		if ( ! Custom_Bulkquick_Edit::do_load() )
			return;

		add_action( 'cbqe_save_post', array( __CLASS__, 'save_post' ) );
		add_action( 'cbqe_validate_settings', array( __CLASS__, 'validate_settings' ), 10, 2 );
		add_filter( 'cbqe_configuration_default', array( __CLASS__, 'configuration_default' ), 10, 3 );
		add_filter( 'cbqe_manage_posts_custom_column_field_type', array( __CLASS__, 'manage_posts_custom_column_field_type' ), 10, 4 );
		add_filter( 'cbqe_posts_custom_column', array( __CLASS__, 'posts_custom_column' ), 10, 4 );
		add_filter( 'cbqe_quick_edit_custom_box_field', array( __CLASS__, 'quick_edit_custom_box_field' ), 10, 5 );
		add_filter( 'cbqe_quick_scripts_bulk', array( __CLASS__, 'scripts_bulk' ), 10, 6 );
		add_filter( 'cbqe_quick_scripts_extra', array( __CLASS__, 'scripts_extra' ), 10, 6 );
		add_filter( 'cbqe_quick_scripts_quick', array( __CLASS__, 'scripts_quick' ), 10, 6 );
		add_filter( 'cbqe_settings_as_types', array( __CLASS__, 'settings_as_types' ) );
		add_filter( 'cbqe_settings_fields', array( __CLASS__, 'settings_fields' ), 10, 2 );
		add_filter( 'cbqe_settings_taxonomies', array( __CLASS__, 'settings_taxonomies' ) );
	}


	public static function init() {
		load_plugin_textdomain( self::ID, false, 'cbqe-edit-flow/languages' );
	}


	public static function plugin_action_links( $links, $file ) {
		if ( self::BASE == $file )
			array_unshift( $links, Custom_Bulkquick_Edit::$settings_link );

		return $links;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.LongVariable)
	 */
	public static function plugin_row_meta( $input, $file ) {
		if ( self::BASE != $file )
			return $input;

		$disable_donate = cbqe_get_option( 'disable_donate' );
		if ( $disable_donate )
			return $input;

		$links = array(
			self::$donate_link,
		);

		global $Custom_Bulkquick_Edit_Premium;
		if ( ! isset( $Custom_Bulkquick_Edit_Premium ) )
			$links[] = CBQE_PREMIUM_LINK;

		$input = array_merge( $input, $links );

		return $input;
	}


	public static function activation() {
		if ( ! current_user_can( 'activate_plugins' ) )
			return;
	}


	public static function deactivation() {
		if ( ! current_user_can( 'activate_plugins' ) )
			return;
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


	public static function version_check() {
		$valid_ext = true;
		if ( ! defined( 'EDIT_FLOW_VERSION' ) ) {
			$valid_ext = false;
		} elseif ( ! version_compare( EDIT_FLOW_VERSION, CBQE_EF_EXT_VERSION, '>=' ) ) {
			$valid_ext = false;
		}

		$valid_version = true;
		if ( ! $valid_ext ) {
			$valid_version = false;
			self::set_notice( 'cbqe_ef_notice_version_ef' );
		}

		if ( ! $valid_version ) {
			$deactivate_reason = esc_html__( 'Failed version check' );
			aihr_deactivate_plugin( self::BASE, CBQE_EF_NAME, $deactivate_reason );
			self::check_notices();
		}

		return $valid_version;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function posts_custom_column( $current, $column, $post_id ) {
		if ( false === strstr( $column, self::$ef_metadata ) )
			return $current;

		if ( ! in_array( $column, array_keys( self::$custom_fields ) ) )
			return $current;

		$meta_key = self::$custom_fields[ $column ];
		$result   = get_post_meta( $post_id, $meta_key, true );

		if ( false !== strstr( $meta_key, self::$ef_checkbox ) ) {
			$post_type = get_post_type( $post_id );
			$options   = Custom_Bulkquick_Edit::get_field_config( $post_type, $column );
			if ( ! empty( $options ) )
				$options = array( $options );

			$result = Custom_Bulkquick_Edit::column_checkbox_radio( $column, $result, $options, 'checkbox' );
		} elseif ( false !== strstr( $meta_key, self::$ef_date ) ) {
			$result = date( get_option( 'date_format' ) . ' H:i', $result );
		} elseif ( false !== strstr( $meta_key, self::$ef_number ) ) {
			$result = intval( $result );
		}

		return $result;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
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

		if ( ! class_exists( 'EF_Editorial_Metadata' ) )
			return $fields;

		$options = get_option( 'edit_flow_editorial_metadata_options' );
		if ( empty( $options ) || empty( $options->post_types[ $post_type ] ) || 'on' != $options->post_types[ $post_type ] ) 
			return $fields;

		$efem  = new EF_Editorial_Metadata();
		$terms = $efem->get_editorial_metadata_terms();
		foreach ( $terms as $term ) {
			if ( is_null( self::$ef_taxonomy ) )
				self::build_edit_flow_structures( $term );

			$show_key = self::$ef_metadata . '-' . $term->slug;
			$meta_key = '_' . self::$ef_taxonomy . '_' . $term->type . '_' . $term->slug;

			$fields[ $show_key ]              = $term->name;
			self::$custom_fields[ $show_key ] = $meta_key;
		}

		return $fields;
	}


	public static function build_edit_flow_structures( $term ) {
		self::$ef_taxonomy = $term->taxonomy;
		self::$ef_checkbox = '_' . self::$ef_taxonomy . '_checkbox_';
		self::$ef_date     = '_' . self::$ef_taxonomy . '_date_';
		self::$ef_number   = '_' . self::$ef_taxonomy . '_number_';
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function save_post( $post_id ) {
		foreach ( self::$custom_fields as $show_key => $meta_key ) {
			$post_key = Custom_Bulkquick_Edit::SLUG . $show_key;
			if ( ! isset( $_POST[ $post_key ] ) )
				continue;

			$value = self::clean_string( $_POST[ $post_key ] );
			if ( '' == $value && Custom_Bulkquick_Edit::$bulk_edit_save )
				continue;

			if ( false !== strstr( $meta_key, self::$ef_date ) ) {
				$value = strtotime( $value );
			} elseif ( false !== strstr( $meta_key, self::$ef_number ) ) {
				$value = intval( $value );
			}

			update_post_meta( $post_id, $meta_key, $value );
			delete_post_meta( $post_id, $show_key );
		}
	}


	public static function update() {
		$prior_version = cbqe_get_option( self::SLUG . 'admin_notices' );
		if ( $prior_version ) {
			if ( $prior_version < '0.0.1' )
				add_action( 'admin_notices', array( __CLASS__, 'notice_0_0_1' ) );

			if ( $prior_version < self::VERSION ) {
				do_action( 'cbqe_ef_update' );
			}

			cbqe_set_option( self::SLUG . 'admin_notices' );
		}

		// display donate on major/minor version release
		$donate_version = cbqe_get_option( self::SLUG . 'donate_version', false );
		if ( ! $donate_version || ( $donate_version != self::VERSION && preg_match( '#\.0$#', self::VERSION ) ) ) {
			self::set_notice( 'notice_donate' );
			cbqe_set_option( self::SLUG . 'donate_version', self::VERSION );
		}
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function notice_donate( $disable_donate = null, $item_name = null ) {
		$disable_donate = cbqe_get_option( 'disable_donate' );

		parent::notice_donate( $disable_donate, CBQE_EF_NAME );
	}


	public static function validate_settings( $input, $errors = array(), $do_errors = false ) {
		$input[ self::SLUG . 'donate_version' ] = self::VERSION;

		if ( empty( $do_errors ) ) {
			$validated = $input;
		} else {
			$validated = array(
				'input' => $input,
				'errors' => $errors,
			);
		}

		return $validated;
	}


	public static function settings_as_types( $as_types ) {
		$as_types['ef_date'] = esc_html__( 'As EF date', 'custom-bulkquick-edit-premium', 'cbqe-edit-flow' );

		return $as_types;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function configuration_default( $default, $id, $type ) {
		switch ( $type ) {
			case 'ef_date':
				$default = 'M dd yy';
				break;
		}

		return $default;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function scripts_bulk( $scripts_bulk, $post_type, $column_name, $field_name, $field_type, $field_name_var ) {
		switch ( $field_type ) {
			case 'ef_date':
				$scripts_bulk[ $column_name ] = "'{$field_name}': bulk_row.find( 'input[name={$field_name}]' ).val()";
				break;
		}

		return $scripts_bulk;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function scripts_extra( $scripts_extra, $post_type, $column_name, $field_name, $field_type, $field_name_var ) {
		switch ( $field_type ) {
			case 'ef_date':
				$js = self::get_js_datetimepicker( $post_type, $field_name, 'bulk' );

				$scripts_extra[ $column_name ] = $js;
				break;
		}

		return $scripts_extra;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function scripts_quick( $scripts_quick, $post_type, $column_name, $field_name, $field_type, $field_name_var ) {
		switch ( $field_type ) {
			case 'ef_date':
				$js = self::get_js_datetimepicker( $post_type, $field_name );

				$scripts_quick[ $column_name . '1' ] = "var {$field_name_var} = jQuery( '.column-{$column_name}', post_row ).text();";
				$scripts_quick[ $column_name . '2' ] = "jQuery( ':input[name={$field_name}]', edit_row ).val( {$field_name_var} );";
				$scripts_quick[ $column_name . '3' ] = $js;
				break;
		}

		return $scripts_quick;
	}


	public static function get_js_datetimepicker( $post_type, $field_name, $mode = 'quick' ) {
		$key         = Custom_Bulkquick_Edit::get_field_key( $post_type, $field_name );
		$key        .= Custom_Bulkquick_Edit_Settings::CONFIG;
		$date_format = cbqe_get_option( $key );
		$week_start  = get_option( 'start_of_week' );

		if ( 'quick' == $mode  ) {
			$js = "jQuery( '.datetimepicker', edit_row ).datetimepicker({
				dateFormat: '{$date_format}',
				firstDay: '{$week_start}',
			});";
		} else {
			$js = "jQuery( '#bulk-edit .datetimepicker' ).datetimepicker({
				dateFormat: '{$date_format}',
				firstDay: '{$week_start}',
			});";
		}

		return $js;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function quick_edit_custom_box_field( $input, $field_type, $field_name, $options, $bulk_mode ) {
		$result = $input;
		switch ( $field_type ) {
			case 'ef_date':
				$result = '<input type="text" class="datetimepicker" name="' . $field_name . '" autocomplete="off" />';
				break;
		}

		return $result;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function manage_posts_custom_column_field_type( $current, $field_type, $column, $post_id ) {
		$result = $current;
		switch ( $field_type ) {
			case 'ef_date':
				$result = $current;
				break;
		}

		return $result;
	}
}

?>
