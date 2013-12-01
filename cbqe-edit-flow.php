<?php
/**
 * Plugin Name: Custom Bulk/Quick Edit - Edit Flow
 * Plugin URI: http://wordpress.org/plugins/edit-flow/
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

if ( ! defined( 'CBQEP_PLUGIN_DIR' ) )
	define( 'CBQEP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) . '/../custom-bulkquick-edit-premium' );

if ( ! defined( 'CBQEP_PLUGIN_DIR_LIB' ) )
	define( 'CBQEP_PLUGIN_DIR_LIB', CBQEP_PLUGIN_DIR . '/lib' );

if ( ! defined( 'CBQE_EF_PLUGIN_DIR' ) )
	define( 'CBQE_EF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

if ( ! defined( 'CBQE_EF_PLUGIN_DIR_LIB' ) )
	define( 'CBQE_EF_PLUGIN_DIR_LIB', CBQE_EF_PLUGIN_DIR . '/lib' );

require_once CBQE_PLUGIN_DIR_LIB . '/aihrus/class-aihrus-common.php';


class Custom_Bulkquick_Edit_Edit_Flow extends Aihrus_Common {
	const ID               = 'cbqe-edit-flow';
	const ITEM_NAME        = 'Edit Flow for Custom Bulk/Quick Edit';
	const KEY              = '_yoast_wpseo_';
	const PLUGIN_BASE      = 'cbqe-edit-flow/cbqe-edit-flow.php';
	const PREM_PLUGIN_BASE = 'custom-bulkquick-edit-premium/custom-bulkquick-edit-premium.php';
	const PREM_VERSION     = '1.3.0';
	const SLUG             = 'cbqe_ef_';
	const VERSION          = '1.0.2';
	const EXT_BASE         = 'edit-flow/wp-seo.php';
	const EXT_VERSION      = '1.4.19';

	public static $class = __CLASS__;
	public static $notice_key;
	public static $parts;


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

		global $CBQE_EF_Licensing;
		if ( ! $CBQE_EF_Licensing->valid_license() ) {
			self::set_notice( 'notice_license', DAY_IN_SECONDS );
			self::check_notices();
		}

		add_filter( 'plugin_action_links', array( __CLASS__, 'plugin_action_links' ), 10, 2 );

		if ( ! Custom_Bulkquick_Edit::do_load() )
			return;

		self::load_parts();

		add_filter( 'cbqe_configuration_default', array( __CLASS__, 'configuration_default' ), 10, 3 );
		add_filter( 'cbqe_manage_posts_custom_column_field_type', array( __CLASS__, 'manage_posts_custom_column_field_type' ), 10, 4 );
		add_filter( 'cbqe_quick_edit_custom_box_field', array( __CLASS__, 'quick_edit_custom_box_field' ), 10, 5 );
		add_filter( 'cbqe_settings', array( __CLASS__, 'settings' ) );
		add_filter( 'cbqe_settings_config_script', array( __CLASS__, 'config_script' ), 10, 7 );
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

		if ( ! is_plugin_active( Custom_Bulkquick_Edit_Edit_Flow::PREM_PLUGIN_BASE ) ) {
			deactivate_plugins( Custom_Bulkquick_Edit_Edit_Flow::PLUGIN_BASE );
			add_action( 'admin_notices', array( 'Custom_Bulkquick_Edit_Edit_Flow', 'notice_version' ) );
			return;
		}

		if ( ! is_plugin_active( Custom_Bulkquick_Edit_Edit_Flow::EXT_BASE ) ) {
			deactivate_plugins( Custom_Bulkquick_Edit_Edit_Flow::PLUGIN_BASE );
			add_action( 'admin_notices', array( 'Custom_Bulkquick_Edit_Edit_Flow', 'notice_version_wpseo' ) );
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

		$CBQE_EF_Licensing = new Custom_Bulkquick_Edit_Edit_Flow_Licensing();
		$CBQE_EF_Licensing->deactivate_license();
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

		if ( is_plugin_inactive( self::PREM_PLUGIN_BASE ) || Custom_Bulkquick_Edit::VERSION < self::PREM_VERSION )
			$good_version = false;

		if ( ! $good_version && is_plugin_active( $base ) ) {
			deactivate_plugins( $base );
			self::set_notice( 'notice_version' );
		}

		if ( is_plugin_inactive( self::EXT_BASE ) || WPSEO_VERSION < self::EXT_VERSION && is_plugin_active( $base ) ) {
			deactivate_plugins( $base );
			self::set_notice( 'notice_version_wpseo' );

			$good_version = false;
		}

		if ( ! $good_version )
			self::check_notices();

		return $good_version;
	}



	public static function notice_license( $post_type = null, $settings_id = null, $free_name = null, $purchase_url = null, $item_name = null ) {
		$post_type    = null;
		$settings_id  = Custom_Bulkquick_Edit_Settings::ID;
		$free_name    = 'Custom Bulk/Quick Edit';
		$purchase_url = 'http://wordpress.org/plugins/edit-flow/';
		$item_name    = self::ITEM_NAME;

		parent::notice_license( $post_type, $settings_id, $free_name, $purchase_url, $item_name );
	}


	public static function notice_version( $free_base = null, $free_name = null, $free_slug = null, $free_version = null, $item_name = null ) {
		$free_base    = self::PREM_PLUGIN_BASE;
		$free_name    = 'Custom Bulk/Quick Edit';
		$free_slug    = 'custom-bulkquick-edit-premium';
		$free_version = self::PREM_VERSION;
		$item_name    = self::ITEM_NAME;

		parent::notice_version( $free_base, $free_name, $free_slug, $free_version, $item_name );
	}


	public static function notice_version_wpseo( $free_base = null, $free_name = null, $free_slug = null, $free_version = null, $item_name = null ) {
		$free_base    = self::EXT_BASE;
		$free_name    = 'Edit Flow';
		$free_slug    = 'edit-flow';
		$free_version = self::EXT_VERSION;
		$item_name    = self::ITEM_NAME;

		parent::notice_version( $free_base, $free_name, $free_slug, $free_version, $item_name );
	}


	public static function load_parts() {
		$wpseo_options = get_wpseo_options();

		self::$parts = array(
			'meta-robots-noindex' => array(
				'title' => esc_html__( 'Meta Robots Index', 'cbqe-edit-flow' ),
				'options' => array(
					0 => esc_html__( 'Default for post type', 'cbqe-edit-flow' ),
					2 => 'index',
					1 => 'noindex',
				),
				'type' => 'select',
			),

			// fixme
			// 'meta-robots-nofollow' => array(
			// 'title' => __( 'Meta Robots Follow' ),
			// 'type' => 'radio',
			// 'options' => array(
			// '0' => __( 'Follow' ),
			// '1' => __( 'Nofollow' ),
			// ),
			// ),

			'meta-robots-adv' => array(
				'title' => __( 'Meta Robots Advanced', 'cbqe-edit-flow' ),
				'type' => 'multiple',
				'options' => array(
					'noodp' => __( 'NO ODP', 'cbqe-edit-flow' ),
					'noydir' => __( 'NO YDIR', 'cbqe-edit-flow' ),
					'noarchive' => __( 'No Archive', 'cbqe-edit-flow' ),
					'nosnippet' => __( 'No Snippet', 'cbqe-edit-flow' ),
				),
			),
		);

		if ( isset( $wpseo_options['breadcrumbs-enable'] ) && $wpseo_options['breadcrumbs-enable'] ) {
			self::$parts['bctitle'] = array(
				'type' => 'text',
				'title' => __( 'Breadcrumbs title', 'cbqe-edit-flow' ),
			);
		}

		if ( isset( $wpseo_options['enablexmlsitemap'] ) && $wpseo_options['enablexmlsitemap'] ) {
			self::$parts['sitemap-include'] = array(
				'type' => 'select',
				'title' => __( 'Include in Sitemap', 'cbqe-edit-flow' ),
				'options' => array(
					'-' => __( 'Auto detect', 'cbqe-edit-flow' ),
					'always' => __( 'Always include', 'cbqe-edit-flow' ),
					'never' => __( 'Never include', 'cbqe-edit-flow' ),
				),
			);

			self::$parts['sitemap-prio'] = array(
				'type' => 'select',
				'title' => __( 'Sitemap Priority', 'cbqe-edit-flow' ),
				'options' => array(
					'-' => __( 'Automatic prioritization', 'cbqe-edit-flow' ),
					'1' => __( '1 - Highest priority', 'cbqe-edit-flow' ),
					'0.9' => '0.9',
					'0.8' => '0.8 - ' . __( 'Default for first tier pages', 'cbqe-edit-flow' ),
					'0.7' => '0.7',
					'0.6' => '0.6 - ' . __( 'Default for second tier pages and posts', 'cbqe-edit-flow' ),
					'0.5' => '0.5 - ' . __( 'Medium priority', 'cbqe-edit-flow' ),
					'0.4' => '0.4',
					'0.3' => '0.3',
					'0.2' => '0.2',
					'0.1' => '0.1 - ' . __( 'Lowest priority', 'cbqe-edit-flow' ),
				),
			);
		}

		self::$parts['sitemap-html-include'] = array(
			'type' => 'select',
			'title' => __( 'Include in HTML Sitemap', 'cbqe-edit-flow' ),
			'options' => array(
				'-' => __( 'Auto detect', 'cbqe-edit-flow' ),
				'always' => __( 'Always include', 'cbqe-edit-flow' ),
				'never' => __( 'Never include', 'cbqe-edit-flow' ),
			),
		);

		self::$parts['canonical'] = array(
			'type' => 'text',
			'title' => __( 'Canonical URL', 'cbqe-edit-flow' ),
		);

		self::$parts['redirect'] = array(
			'type' => 'text',
			'title' => __( '301 Redirect', 'cbqe-edit-flow' ),
		);
	}


	public static function settings( $settings ) {
		$desc_conf  = esc_html__( 'Configuration values for %s. In general, do NOT edit these.', 'cbqe-edit-flow' );
		$heading    = esc_html__( 'Edit Flow Attributes for %s', 'cbqe-edit-flow' );
		$title_conf = esc_html__( '%s Configuration', 'cbqe-edit-flow' );

		$wpseo_options = get_wpseo_options();

		$post_types = Custom_Bulkquick_Edit::get_post_types();
		foreach ( $post_types as $post_type => $label ) {
			$hide_seo_key = 'hideeditbox-' . $post_type;
			if ( isset( $wpseo_options[ $hide_seo_key ] ) && Custom_Bulkquick_Edit_Settings::is_true( $wpseo_options[ $hide_seo_key ] ) )
				continue;

			$post_key = $post_type . Custom_Bulkquick_Edit_Settings::ENABLE . self::KEY;

			$settings[ $post_key ] = array(
				'section' => $post_type,
				'desc' => sprintf( $heading, $label ),
				'type' => 'heading',
			);

			foreach ( self::$parts as $field => $item ) {
				$key   = $post_key . $field;
				$title = $item['title'];
				$type  = isset( $item['type'] ) ? $item['type'] : 'select';

				$settings[ $key ] = array(
					'section' => $post_type,
					'title' => $title,
					'label' => $title,
					'type' => 'select',
					'choices' => array(
						'' => esc_html__( 'Hide', 'cbqe-edit-flow' ),
						self::KEY . $type => esc_html__( 'Show', 'cbqe-edit-flow' ),
					),
				);

				$settings[ $key . Custom_Bulkquick_Edit_Settings::CONFIG ] = array(
					'section' => $post_type,
					'title' => sprintf( $title_conf, $title ),
					'desc' => sprintf( $desc_conf, $title ),
					'type' => 'textarea',
					'validate' => 'trim',
				);
			}
		}

		return $settings;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function configuration_default( $default, $id, $type ) {
		if ( false === strstr( $type, self::KEY ) )
			return $default;

		$field   = self::get_field_name( $id );
		$options = ! empty( self::$parts[ $field ]['options'] ) ? self::$parts[ $field ]['options'] : null;

		if ( is_null( $options ) )
			return $default;

		if ( is_array( $options ) ) {
			$parts = array();
			foreach ( $options as $key => $value )
				$parts[] = $key . '|' . $value;

			$default = implode( "\n", $parts );
		}

		return $default;
	}


	public static function get_field_name( $id ) {
		$field = preg_replace( '#^.*' . self::KEY . '#', '', $id );
		$field = str_replace( Custom_Bulkquick_Edit_Settings::CONFIG, '', $field );

		return $field;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function quick_edit_custom_box_field( $input, $field_type, $field_name, $options, $bulk_mode ) {
		$column_name    = str_replace( Custom_Bulkquick_Edit::SLUG, '', $field_name );
		$field_name_var = str_replace( '-', '_', $field_name );

		$result = $input;
		switch ( $field_type ) {
		case self::KEY . 'multiple':
			$result = Custom_Bulkquick_Edit::custom_box_select_multiple( $column_name, $field_name, $field_name_var, $options, $bulk_mode );
			break;

		case self::KEY . 'radio':
			if ( ! $bulk_mode )
				$result = Custom_Bulkquick_Edit::custom_box_radio( $column_name, $field_name, $field_name_var, $options );
			else
				$result = Custom_Bulkquick_Edit::custom_box_select( $column_name, $field_name, $field_name_var, $options, $bulk_mode );
			break;

		case self::KEY . 'select':
			$result = Custom_Bulkquick_Edit::custom_box_select( $column_name, $field_name, $field_name_var, $options, $bulk_mode );
			break;

		case self::KEY . 'text':
			$result = Custom_Bulkquick_Edit::custom_box_input( $column_name, $field_name, $field_name_var );
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
		global $post;

		$details = Custom_Bulkquick_Edit::get_field_config( $post->post_type, $column );
		$options = explode( "\n", $details );

		$type = str_replace( self::KEY, '', $field_type );

		$result = $current;
		switch ( $field_type ) {
		case self::KEY . 'multiple':
		case self::KEY . 'select':
			$result = Custom_Bulkquick_Edit::column_select( $column, $current, $options, $type );
			break;
			break;

		case self::KEY . 'radio':
			$result = Custom_Bulkquick_Edit::column_checkbox_radio( $column, $current, $options, $type );
			break;

		case self::KEY . 'text':
			$result = $current;
			break;
		}

		return $result;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function config_script( $script, $args, $id, $field, $f, $c, $hide ) {
		$replace = $hide . " || '" . self::KEY . "text' == val";
		$script  = str_replace( $hide, $replace, $script );

		return $script;
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
	require_once CBQE_EF_PLUGIN_DIR_LIB . '/class-cbqe-edit-flow-licensing.php';

	global $CBQE_EF_Licensing;
	if ( is_null( $CBQE_EF_Licensing ) )
		$CBQE_EF_Licensing = new Custom_Bulkquick_Edit_Edit_Flow_Licensing();

	if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) )
		require_once CBQEP_PLUGIN_DIR_LIB . '/EDD_SL_Plugin_Updater.php';

	$CBQE_EF_Updater = new EDD_SL_Plugin_Updater(
		$CBQE_EF_Licensing->store_url,
		__FILE__,
		array(
			'version' => Custom_Bulkquick_Edit_Edit_Flow::VERSION,
			'license' => $CBQE_EF_Licensing->get_license(),
			'item_name' => Custom_Bulkquick_Edit_Edit_Flow::ITEM_NAME,
			'author' => $CBQE_EF_Licensing->author,
		)
	);

	if ( Custom_Bulkquick_Edit_Edit_Flow::version_check() ) {
		global $Custom_Bulkquick_Edit_Edit_Flow;
		if ( is_null( $Custom_Bulkquick_Edit_Edit_Flow ) )
			$Custom_Bulkquick_Edit_Edit_Flow = new Custom_Bulkquick_Edit_Edit_Flow();

		do_action( 'cbqe_ef_init' );
	}
}


?>
