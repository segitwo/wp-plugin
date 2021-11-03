<?php

/*
Plugin Name: Simple Plugin
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Simple plugin
Version: 1.0
Author: segi
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/

defined( "ABSPATH" ) || exit;

class SimplePlugin {

	static function register() {
		add_action( "init",
			[ SimplePlugin::class, "register_clothes_cpt" ] );
		add_action( "init",
			[ SimplePlugin::class, "register_clothes_type" ] );
		add_action( 'wp_ajax_add_clothes',
			[ SimplePlugin::class, 'add_clothes_ajax' ] );
		add_filter( "template_include",
			[ SimplePlugin::class, "template_include_filter" ] );
		add_filter( 'option_posts_per_page',
			[ SimplePlugin::class, 'clothes_type_posts_per_page' ] );
	}

	static function add_clothes_ajax() {
//				var_dump( $_POST );
		if ( ! isset( $_POST["nonce"] ) ||
		     ! wp_verify_nonce( $_POST["nonce"], "interview-plugin" ) ) {
			wp_die();
		}

		$post_id = wp_insert_post( [
			"post_author"  => esc_attr( $_POST["user_id"] ),
			"post_title"   => esc_attr( $_POST["title"] ),
			"post_content" => esc_attr( $_POST["description"] ),
			"post_type"    => "clothes",
			"post_status"  => "publish",
		] );

		update_field( "size", esc_attr( $_POST["size"] ), $post_id );
		update_field( "color", esc_attr( $_POST["color"] ), $post_id );
		update_field( "sex", esc_attr( $_POST["sex"] ), $post_id );
		wp_set_object_terms( $post_id, explode( ",", esc_attr( $_POST["type"] ) ), "clothes-type" );

		if ( $_FILES ) {
			require_once( ABSPATH . "wp-admin" . "/includes/image.php" );
			require_once( ABSPATH . "wp-admin" . "/includes/file.php" );
			require_once( ABSPATH . "wp-admin" . "/includes/media.php" );
			$file_handler = "thumbnail";
			$attach_id    = media_handle_upload( $file_handler, 0 );
			set_post_thumbnail( $post_id, $attach_id );
		}
		wp_die();
	}

	static function clothes_type_posts_per_page() {
		global $option_posts_per_page;
		if ( is_tax( "clothes-type" ) ) {
			return 4;
		}

		return $option_posts_per_page;
	}

	static function template_include_filter( $template ) {
		$plugin_dir = dirname( __FILE__ );
		if ( get_query_var( "post_type" ) == "clothes" ) {
			$template_filename = 'single-clothes.php';
			if ( file_exists( TEMPLATEPATH . '/' . $template_filename ) ) {
				$return_template = TEMPLATEPATH . '/' . $template_filename;
			} else {
				$return_template = $plugin_dir . '/templates/'
				                   . $template_filename;
			}

			return $return_template;
		}

		if ( is_tax( "clothes-type" ) ) {
			$template_filename = 'taxonomy-clothes-type.php';
			if ( file_exists( TEMPLATEPATH . "/" . $template_filename ) ) {
				return TEMPLATEPATH . "/" . $template_filename;
			} else {
				return $plugin_dir . "/templates/" . $template_filename;
			}
		}

		if ( is_archive() ) {
			$template_filename = 'archive-clothes.php';
			if ( file_exists( TEMPLATEPATH . "/" . $template_filename ) ) {
				return TEMPLATEPATH . "/" . $template_filename;
			} else {
				return $plugin_dir . "/templates/" . $template_filename;
			}
		}
		if ( is_front_page() ) {
			$template_filename = 'front-page.php';
			$user              = wp_get_current_user();

			if ( count( array_intersect( [ "administrator", "editor" ],
				(array) $user->roles ) )
			) {
				wp_enqueue_script( 'add-clothes-ajax',
					plugins_url( '/js/add_clothes.js', __FILE__ ) );
				wp_localize_script( 'add-clothes-ajax', 'ACAjax',
					array(
						'ajaxurl' => admin_url( 'admin-ajax.php' ),
						'nonce'   => wp_create_nonce( 'interview-plugin' )
					) );
			}
			if ( file_exists( TEMPLATEPATH . "/" . $template_filename ) ) {
				return TEMPLATEPATH . "/" . $template_filename;
			} else {
				return $plugin_dir . "/templates/" . $template_filename;
			}
		}

		return $template;
	}

	static function register_clothes_cpt() {
		register_post_type( "clothes", [
			"label"       => "Clothes",
			"public"      => true,
			"has_archive" => true,
			"rewrite"     => [ "slug" => "clothes" ],
			"supports"    => [ "title", "editor", "thumbnail" ],
		] );
	}

	static function register_clothes_type() {
		register_taxonomy( "clothes-type", "clothes", [
				"label"             => "Clothes Type",
				"show_ui"           => true,
				"show_admin_column" => true,
				"query_var"         => true,
				"rewrite"           => [ "slug" => "clothes-type" ],
			]
		);
	}

	static function activation() {
		flush_rewrite_rules();
	}

	static function deactivation() {
		flush_rewrite_rules();
	}

}

SimplePlugin::register();

register_activation_hook( __FILE__, [ SimplePlugin::class, "activation" ] );
register_activation_hook( __FILE__,
	[ SimplePlugin::class, "deactivation" ] );