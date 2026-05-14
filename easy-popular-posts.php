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

define( 'THISISMYURL_EPP_NAME',     'Easy Popular Posts' );
define( 'THISISMYURL_EPP_VERSION',  '26.05.0' );
define( 'THISISMYURL_EPP_FILENAME', plugin_basename( __FILE__ ) );
define( 'THISISMYURL_EPP_FILEPATH', plugin_dir_path( __FILE__ ) );
define( 'THISISMYURL_EPP_URL',      plugin_dir_url( __FILE__ ) );
define( 'THISISMYURL_EPP_NAMESPACE', 'easy-popular-posts' );

if ( ! class_exists( 'Thisismyurl_Easy_Popular_Posts' ) ) {
	/**
	 * Main plugin class.
	 *
	 * @since 15.01
	 */
	class Thisismyurl_Easy_Popular_Posts {

		/**
		 * Constructor — register shared plugin hooks.
		 *
		 * @since 26.05.0
		 */
		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_filter( 'plugin_action_links_' . THISISMYURL_EPP_FILENAME, array( $this, 'add_action_link' ), 10, 2 );
		}

		/**
		 * Load plugin text domain.
		 *
		 * @since 26.05.0
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'easy-popular-posts' );
		}

		/**
		 * Enqueue front-end stylesheet if present.
		 *
		 * @since 26.05.0
		 */
		public function enqueue_style() {
			$css_file = plugin_dir_path( __FILE__ ) . 'css/easy-popular-posts.css';
			if ( file_exists( $css_file ) ) {
				wp_enqueue_style(
					'easy-popular-posts',
					THISISMYURL_EPP_URL . 'css/easy-popular-posts.css',
					array(),
					THISISMYURL_EPP_VERSION
				);
			}
		}

		/**
		 * Enqueue admin stylesheet on the plugin settings page only.
		 *
		 * @since 26.05.0
		 */
		public function admin_enqueue_scripts() {
			$screen = get_current_screen();
			if ( ! $screen || 'settings_page_easy_popular_posts_settings' !== $screen->id ) {
				return;
			}
			wp_enqueue_style(
				'thisismyurl-common',
				THISISMYURL_EPP_URL . 'css/thisismyurl-common.css',
				array(),
				THISISMYURL_EPP_VERSION
			);
		}

		/**
		 * Register the options-page submenu (hidden — accessible via action link).
		 *
		 * @since 26.05.0
		 */
		public function admin_menu() {
			add_options_page(
				esc_html__( 'Easy Popular Posts', 'easy-popular-posts' ),
				esc_html__( 'Easy Popular Posts', 'easy-popular-posts' ),
				'manage_options',
				'easy_popular_posts_settings',
				array( $this, 'settings_page' )
			);
			remove_submenu_page( 'options-general.php', 'easy_popular_posts_settings' );
		}

		/**
		 * Add Settings link to plugin row.
		 *
		 * @since 26.05.0
		 * @param string[] $links Existing action links.
		 * @return string[]
		 */
		public function add_action_link( $links ) {
			$links[] = sprintf(
				'<a href="%s">%s</a>',
				esc_url( admin_url( 'options-general.php?page=easy_popular_posts_settings' ) ),
				esc_html__( 'Settings', 'easy-popular-posts' )
			);
			return $links;
		}

		/**
		 * Render settings/about page.
		 *
		 * @since 26.05.0
		 */
		public function settings_page() {
			?>
			<div id="thisismyurl-settings" class="wrap">
				<h1><?php echo esc_html( THISISMYURL_EPP_NAME ); ?></h1>

				<h2><?php esc_html_e( 'General settings', 'easy-popular-posts' ); ?></h2>
				<p>
					<?php
					echo wp_kses_post(
						sprintf(
							/* translators: 1: opening <a> tag linking to readme.txt, 2: closing </a> tag */
							__( 'The plugin has no settings. Once activated it works automatically. See the %1$sreadme.txt%2$s for full details.', 'easy-popular-posts' ),
							'<a href="' . esc_url( THISISMYURL_EPP_URL . 'readme.txt' ) . '">',
							'</a>'
						)
					);
					?>
				</p>
			</div>

			<div id="donate">
				<h2><?php esc_html_e( 'How to support the software', 'easy-popular-posts' ); ?></h2>
				<p><?php esc_html_e( 'Open source software only works through the hard work of community members volunteering their time. If you would like to show your support, here is how you can help:', 'easy-popular-posts' ); ?></p>
				<ul>
					<li><a href="<?php echo esc_url( 'https://wordpress.org/plugins/' . THISISMYURL_EPP_NAMESPACE . '/' ); ?>"><?php esc_html_e( 'Give it a great review on WordPress.org', 'easy-popular-posts' ); ?></a></li>
					<li><a href="<?php echo esc_url( 'https://wordpress.org/support/plugin/' . THISISMYURL_EPP_NAMESPACE ); ?>"><?php esc_html_e( 'Offer support in the plugin forums', 'easy-popular-posts' ); ?></a></li>
					<li><a href="<?php echo esc_url( 'https://github.com/thisismyurl/' . THISISMYURL_EPP_NAMESPACE . '/issues' ); ?>"><?php esc_html_e( 'Report an issue or suggest a feature', 'easy-popular-posts' ); ?></a></li>
					<li><a href="https://github.com/sponsors/thisismyurl"><?php esc_html_e( 'Sponsor the developer on GitHub', 'easy-popular-posts' ); ?></a></li>
				</ul>
				<p>&#8212;&nbsp;<a href="https://thisismyurl.com/"><?php esc_html_e( 'Christopher Ross', 'easy-popular-posts' ); ?></a></p>
			</div>
			<?php
		}

		/**
		 * Register hooks.
		 *
		 * @since 15.01
		 */
		public function run() {
			add_action( 'widgets_init', array( $this, 'widget_init' ) );
			add_action( 'wp_head', array( $this, 'wp_head' ) );
			add_shortcode( 'thisismyurl_easy_popular_posts', array( $this, 'easy_popular_posts_shortcode' ) );
		}

		/**
		 * Shortcode handler — must return, not echo, so WP places output inline.
		 *
		 * @since 15.01
		 * @return string
		 */
		public function easy_popular_posts_shortcode() {
			$popular_posts = $this->easy_popular_posts();
			if ( ! empty( $popular_posts ) ) {
				return '<ul class="thisismyurl-easy-popular-posts">' . $popular_posts . '</ul>';
			}
			return '';
		}

		/**
		 * Track pageviews on single posts.
		 *
		 * Comment counts are maintained by WP core on the post object and are
		 * queried directly in easy_popular_posts() — no sync needed here.
		 *
		 * Social share-count API calls were removed in v26.05.0 — all those
		 * third-party APIs (Twitter, Facebook, LinkedIn, StumbleUpon) are shut down.
		 *
		 * @since 15.01
		 */
		public function wp_head() {
			if ( ! is_single() ) {
				return;
			}
			global $post;

			$pageviews = (int) get_post_meta( $post->ID, '_easy-popular-posts-pageviews', true );
			update_post_meta( $post->ID, '_easy-popular-posts-pageviews', $pageviews + 1 );
		}

		/**
		 * Retrieve and format popular posts.
		 *
		 * @since 15.01
		 * @param array|null $options Override defaults. When $options['show'] === 0 (default)
		 *                            returns the HTML string; when non-zero, echoes directly.
		 *                            Note: 'before' and 'after' are developer-controlled values
		 *                            (widget update() never persists them).
		 * @return string
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
				$count = ( 'comments' === $options['display_method'] )
					? (int) $popular_post->comment_count
					: (int) get_post_meta( $popular_post->ID, '_easy-popular-posts-' . $options['display_method'], true );

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
						get_the_post_thumbnail( $popular_post->ID, 'thumbnail' ), // Escaped internally by WP core.
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
					return '';
				}
				return $html;
			}

			return '';
		}

		/**
		 * Return default options.
		 *
		 * @since 15.01
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
		 *
		 * @since 15.01
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
	 * @since 15.01
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
