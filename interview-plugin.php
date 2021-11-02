<?php

/*
Plugin Name: Interview Plugin
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Simple plugin
Version: 1.0
Author: segi
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/

defined( "ABSPATH" ) || exit;

class InterviewPlugin {

	static function register() {
		add_action( "init",
			[ InterviewPlugin::class, "register_clothes_cpt" ] );
		add_action( "init",
			[ InterviewPlugin::class, "register_clothes_type" ] );
		add_action( 'wp_ajax_add_clothes',
			[ InterviewPlugin::class, 'add_clothes_ajax' ] );
		add_filter( "template_include",
			[ InterviewPlugin::class, "template_include_filter" ] );
		add_filter( 'option_posts_per_page',
			[ InterviewPlugin::class, 'clothes_type_posts_per_page' ] );
	}

	static function add_clothes_ajax() {
		#TODO Сделать добавление товара через AJAX
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
					array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
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

InterviewPlugin::register();

register_activation_hook( __FILE__, [ InterviewPlugin::class, "activation" ] );
register_activation_hook( __FILE__,
	[ InterviewPlugin::class, "deactivation" ] );
