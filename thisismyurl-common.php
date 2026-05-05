<?php
/**
 * Easy Popular Posts — common base class.
 *
 * @package   Easy_Popular_Posts
 * @copyright Copyright (c) 2008, Christopher Ross
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, v2 (or later)
 * @since     14.11
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Thisismyurl_Common_EPP' ) ) {
	/**
	 * Base class: text domain, enqueue, admin menu, settings page.
	 *
	 * @since 14.11
	 */
	class Thisismyurl_Common_EPP {

		/**
		 * Constructor — register all core hooks.
		 *
		 * @since 14.11
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
		 * Since WP 4.6 the path argument is ignored; WP auto-discovers translations
		 * from wp-content/languages/plugins/ and the Domain Path plugin header.
		 *
		 * @since 14.11
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'easy-popular-posts' );
		}

		/**
		 * Enqueue front-end stylesheet if present.
		 *
		 * @since 14.11
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
		 * @since 14.11
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
		 * @since 14.11
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
		 * Add Settings link to the plugin row on Plugins > Installed Plugins.
		 *
		 * @since 14.11
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
		 * Render the settings/about page.
		 *
		 * @since 14.11
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
	}
}
