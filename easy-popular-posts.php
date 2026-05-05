<?php
/**
 * Plugin Name: Easy Popular Posts
 * Plugin URI:  https://thisismyurl.com/downloads/easy-popular-posts/
 * Description: An easy-to-use WordPress widget and shortcode to add popular posts to any theme.
 * Author:      Christopher Ross
 * Author URI:  https://thisismyurl.com/
 * Version:     26.05.0
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: easy-popular-posts
 * Domain Path: /languages
 *
 * @package Easy_Popular_Posts
 * @copyright Copyright (c) 2008, Christopher Ross
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'THISISMYURL_EPP_NAME',      'Easy Popular Posts' );
define( 'THISISMYURL_EPP_SHORTNAME', 'Easy Popular Posts' );
define( 'THISISMYURL_EPP_VERSION',   '26.05.0' );
define( 'THISISMYURL_EPP_FILENAME',  plugin_basename( __FILE__ ) );
define( 'THISISMYURL_EPP_FILEPATH',  plugin_dir_path( __FILE__ ) );
define( 'THISISMYURL_EPP_URL',       plugin_dir_url( __FILE__ ) );
define( 'THISISMYURL_EPP_NAMESPACE', 'easy-popular-posts' );

require_once plugin_dir_path( __FILE__ ) . 'thisismyurl-common.php';

if ( ! class_exists( 'Thisismyurl_Easy_Popular_Posts' ) ) {
	/**
	 * Main plugin class.
	 *
	 * @since 15.01
	 */
	class Thisismyurl_Easy_Popular_Posts extends Thisismyurl_Common_EPP {

		/**
		 * Register hooks.
		 */
		public function run() {
			add_action( 'widgets_init', array( $this, 'widget_init' ) );
			add_action( 'wp_head', array( $this, 'wp_head' ) );
			add_shortcode( 'thisismyurl_easy_popular_posts', array( $this, 'easy_popular_posts_shortcode' ) );
		}

		/**
		 * Shortcode handler.
		 */
		public function easy_popular_posts_shortcode() {
			$popular_posts = $this->easy_popular_posts();
			if ( ! empty( $popular_posts ) ) {
				// Output is escaped per-element inside easy_popular_posts().
				echo '<ul class="thisismyurl-easy-popular-posts">' . $popular_posts . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		/**
		 * Track comment counts and pageviews on single posts.
		 *
		 * Social share-count API calls were removed in v26.05.0 — all of those
		 * third-party APIs (Twitter, Facebook, LinkedIn, StumbleUpon) are shut down.
		 */
		public function wp_head() {
			if ( ! is_single() ) {
				return;
			}
			global $post;

			$comment_count = (int) wp_count_comments( $post->ID )->approved;
			update_post_meta( $post->ID, '_easy-popular-posts-comments', $comment_count );

			$pageviews = (int) get_post_meta( $post->ID, '_easy-popular-posts-pageviews', true );
			update_post_meta( $post->ID, '_easy-popular-posts-pageviews', $pageviews + 1 );
		}

		/**
		 * Retrieve and format popular posts.
		 *
		 * @param array|null $options Override defaults. When $options['show'] === 0 (default)
		 *                            returns the HTML string; when non-zero, echoes directly.
		 * @return string|void
		 */
		public function easy_popular_posts( $options = null ) {
			$options = wp_parse_args( $options, $this->popular_posts_defaults() );

			$args = array(
				'posts_per_page' => absint( $options['post_count'] ),
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'meta_key'       => '_easy-popular-posts-' . $options['display_method'], // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'orderby'        => 'meta_value_num',
				'order'          => 'DESC',
			);

			$posts  = get_posts( $args );
			$output = array();

			foreach ( $posts as $popular_post ) {
				$count = (int) get_post_meta( $popular_post->ID, '_easy-popular-posts-' . $options['display_method'], true );

				$item = sprintf(
					'<span class="title">%s (%s)</span>',
					esc_html( get_the_title( $popular_post->ID ) ),
					number_format_i18n( $count )
				);

				if ( 1 === (int) $options['include_link'] ) {
					$rel  = ( 1 === (int) $options['nofollow'] ) ? ' rel="nofollow noopener noreferrer"' : '';
					$item = sprintf(
						'<span class="title-link"><a href="%s" title="%s"%s>%s</a></span>',
						esc_url( get_permalink( $popular_post->ID ) ),
						esc_attr( get_the_title( $popular_post->ID ) ),
						$rel,
						$item
					);
				}

				if ( 1 === (int) $options['feature_image'] && has_post_thumbnail( $popular_post->ID ) ) {
					$item = sprintf(
						'<div class="thumbnail">%s</div>%s',
						get_the_post_thumbnail( $popular_post->ID, 'thumbnail' ),
						$item
					);
				}

				if ( 1 === (int) $options['show_excerpt'] && ! empty( $popular_post->post_excerpt ) ) {
					$item = sprintf(
						'%s<div class="excerpt">%s</div>',
						$item,
						esc_html( $popular_post->post_excerpt )
					);
				}

				$output[] = $options['before'] . $item . $options['after'];
			}

			if ( ! empty( $output ) ) {
				$html = implode( '', $output );
				if ( 0 !== (int) $options['show'] ) {
					echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				} else {
					return $html;
				}
			}
		}

		/**
		 * Return default options.
		 *
		 * @return array
		 */
		public function popular_posts_defaults() {
			return array(
				'title'          => __( 'Easy Popular Posts', 'easy-popular-posts' ),
				'post_count'     => 10,
				'include_link'   => 1,
				'before'         => '<li>',
				'after'          => '</li>',
				'nofollow'       => 0,
				'show_excerpt'   => 0,
				'feature_image'  => 0,
				'show_credit'    => 1,
				'display_method' => 'pageviews',
				'show'           => 0,
			);
		}

		/**
		 * Register the widget.
		 */
		public function widget_init() {
			require_once plugin_dir_path( __FILE__ ) . 'widgets/class-thisismyurl-easy-popular-posts-widget.php';
			register_widget( 'Thisismyurl_Easy_Popular_Posts_Widget' );
		}
	}
}

global $thisismyurl_easy_popular_posts;
$thisismyurl_easy_popular_posts = new Thisismyurl_Easy_Popular_Posts();
$thisismyurl_easy_popular_posts->run();

if ( ! function_exists( 'thisismyurl_easy_popular_posts' ) ) {
	/**
	 * Template tag for theme files.
	 *
	 * @param array|null $options See Thisismyurl_Easy_Popular_Posts::popular_posts_defaults().
	 */
	function thisismyurl_easy_popular_posts( $options = null ) {
		global $thisismyurl_easy_popular_posts;
		$options = wp_parse_args(
			(array) $options,
			array_merge( $thisismyurl_easy_popular_posts->popular_posts_defaults(), array( 'show' => 1 ) )
		);
		$thisismyurl_easy_popular_posts->easy_popular_posts( $options );
	}
}
