<?php
/*
Plugin Name: Easy Popular Posts
Plugin URI: http://thisismyurl.com/downloads/easy-popular-posts/
Description: An easy to use WordPress function to add Popular Posts to any theme.
Author: Christopher Ross
Author URI: http://thisismyurl.com/
Tags: future, upcoming posts, upcoming post, upcoming, draft, Post, popular, preview, plugin, post, posts
Version: 15.01.12
*/


/**
 *
 * Easy Popular Posts core file
 *
 * This file contains all the logic required for the plugin
 *
 * @link		http://wordpress.org/extend/plugins/easy-popular-posts/
 *
 * @package 	Easy Popular Posts
 * @copyright	Copyright (c) 2008, Chrsitopher Ross
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		Easy Popular Posts 1.0
 *
 *
 */

/* if the plugin is called directly, die */
if ( ! defined( 'WPINC' ) )
	die;
	
	
define( 'THISISMYURL_EPP_NAME', 'Easy Popular Posts' );
define( 'THISISMYURL_EPP_SHORTNAME', 'Easy Popular Posts' );

define( 'THISISMYURL_EPP_FILENAME', plugin_basename( __FILE__ ) );
define( 'THISISMYURL_EPP_FILEPATH', dirname( plugin_basename( __FILE__ ) ) );
define( 'THISISMYURL_EPP_FILEPATHURL', plugin_dir_url( __FILE__ ) );

define( 'THISISMYURL_EPP_NAMESPACE', basename( THISISMYURL_EPP_FILENAME, '.php' ) );
define( 'THISISMYURL_EPP_TEXTDOMAIN', str_replace( '-', '_', THISISMYURL_EPP_NAMESPACE ) );

define( 'THISISMYURL_EPP_VERSION', '15.01' );

include_once( 'thisismyurl-common.php' );

/**
 * Creates the class required for Easy Popular Posts
 *
 * @author     Christopher Ross <info@thisismyurl.com>
 * @version    Release: @15.01@
 * @see        wp_enqueue_scripts()
 * @since      Class available since Release 14.11
 *
 */
if( ! class_exists( 'thisismyurl_EasyPopularPosts' ) ) {
class thisismyurl_EasyPopularPosts extends thisismyurl_Common_EPP {
	/**
	  * Standard Constructor
	  *
	  * @access public
	  * @static
	  * @uses http://codex.wordpress.org/Function_Reference/add_shortcode
	  * @since Method available since Release 15.01
	  *
	  */
	public function run() {
		
		add_action( 'widgets_init', array( $this, 'widget_init' ) );
		
		
		add_action( 'wp_head', array( $this, 'wp_head' ) );
		add_shortcode( 'thisismyurl_easy_popular_posts', array( $this, 'easy_popular_posts_shortcode' ) );
		
	}
	
	
	
	/**
	  * easy_popular_posts_shortcode helper function
	  *
	  * @access public
	  * @static
	  * @since Method available since Release 14.11
	  *
	  */
	 function easy_popular_posts_shortcode() {
	
		$popular_posts = $this->easy_popular_posts();
		
		if ( ! empty( $popular_posts ) )
			echo '<ul class="thisismyurl-easy-popular-posts">' . $popular_posts . '</ul>';
			
	} 
	
	
	/**
	  * wp_head 
	  *
	  * @access public
	  * @static
	  * @since Method available since Release 14.11
	  *
	  */
	 function wp_head() {
		
		/* only run this on single pages */
		if ( is_single() ) {
		
			global $post;
			
			/* check when it was last run */
			$last_run = get_transient( 'easy-popular-posts-' . $post->ID );
			$comment_array = get_comments( array( 'post_id'=>$post->ID ) );
			$pageviews = get_post_meta( $post->ID, '_easy-popular-posts-pageviews', true );
			
			/* only run when required */
			if ( isset( $last_run ) && empty( $last_run ) ) {
				$social_count = 0;
				
				$permalink = get_permalink( $post->ID );
				
				$twitter_api = wp_remote_get( 'http://urls.api.twitter.com/1/urls/count.json?url=' .$permalink );
				if ( isset( $twitter_api ) )
					$twitter_api = json_decode( $twitter_api['body'] );
				
				$facebook_api = wp_remote_get( 'http://graph.facebook.com/?id=' . $permalink );
				if ( isset( $facebook_api ) )
					$facebook_api = json_decode( $facebook_api['body'] );
				
				$linkedin_api = wp_remote_get( 'http://www.linkedin.com/countserv/count/share?url=' . $permalink .'&format=json' );
				if ( isset( $linkedin_api ) )
					$linkedin_api = json_decode( $linkedin_api['body'] );
				
				$stumbleupon_api = wp_remote_get( 'http://www.stumbleupon.com/services/1.01/badge.getinfo?url=' . $permalink );
				if ( isset( $stumbleupon_api ) )
					$stumbleupon_api = json_decode( $stumbleupon_api['body'] );
	
							
				if( isset( $twitter_api->count ) && is_int( $twitter_api->count ) ) {
					update_post_meta( $post->ID, '_easy-popular-posts-twitter', $twitter_api->count );
					$social_count = $social_count + $twitter_api->count;
				}
				
				if( isset( $facebook_api->shares ) && is_int( $facebook_api->shares ) ) {
					update_post_meta( $post->ID, '_easy-popular-posts-facebook', $facebook_api->shares );
					$social_count = $social_count + $facebook_api->shares;
				}
	
				
				if( isset( $linkedin_api->count ) && is_int( $linkedin_api->count ) ) {
					update_post_meta( $post->ID, '_easy-popular-posts-linkedin', $linkedin_api->count );
					$social_count = $social_count + $linkedin_api->count;
				}
				
				if( isset( $stumbleupon_api->result->views ) && is_int( $stumbleupon_api->result->views ) ) {
					update_post_meta( $post->ID, '_easy-popular-posts-stumbleupon', $stumbleupon_api->result->views );
					$social_count = $social_count + $stumbleupon_api->result->views;
				}
				
				if( isset( $social_count ) && is_int( $social_count ) )
					update_post_meta( $post->ID, '_easy-popular-posts-social', $social_count );
								
				/* set the last run transient */
				set_transient( 'easy-popular-posts-' . $post->ID , 1 , ( 60 * 60 * 3 ) );
			  
			  }	
				  
			  if( isset( $comment_array ) )
				  update_post_meta( $post->ID, '_easy-popular-posts-comments', count( $comment_array ) );
			  
			  if( isset( $pageviews ) )
				  update_post_meta( $post->ID, '_easy-popular-posts-pageviews', $pageviews + 1 );
			  else
				  update_post_meta( $post->ID, '_easy-popular-posts-pageviews', 1 );
		}
	
		
	}
	
	
	/**
	  * easy_popular_posts
	  *
	  * @access public
	  * @static
	  * @since Method available since Release 14.11
	  *
	  */
	function easy_popular_posts( $options = NULL ) {

		global $post;
		
		$options = wp_parse_args( $options, $this->popular_posts_defaults() );

		$args = array(
			'post_per_page' => $options['post_count'],
			'post_type'	=>	'post',
			'meta_key' => '_easy-popular-posts-' . $options['display_method'],
            'orderby'   => 'meta_value_num',
		);
	
		$popular_posts = get_posts( $args );
		
		if( isset( $popular_posts ) && ! empty( $popular_posts ) ) {
			foreach ( $popular_posts as $popular_post ) {
	
				/* place the post title */
				
				$popular_item = sprintf( '<span class="title">%s (%s)</span>', 
											esc_html( get_the_title( $popular_post->ID ) ),
											number_format( get_post_meta( $popular_post->ID, '_easy-popular-posts-' . $options['display_method'], true ) )
								);
				
				
				/* if there's a link, display it */
				if ( $options['include_link'] == 1 ) {
				
					if( $options['nofollow'] == 1 )
						$nofollow = 'nofollow';
					else
						$nofollow = '';
						
					$popular_item = sprintf( '<span class="title-link"><a href="%s" title="%s" %s >%s</a><span>',
											get_permalink( $popular_post->ID ),
											esc_attr( get_the_title( $popular_post->ID ) ),
											$nofollow,
											$popular_item
									);	
					
				}
				
				
				/* feature image, if there is one */
				if ( $options['feature_image'] == 1 && has_post_thumbnail( $popular_post->ID ) ) {
					$popular_item = sprintf( '<div class="thumbnail">%s</div>%s', 
											get_the_post_thumbnail($thepost->ID,'thumbnail'),
											$popular_item
											);
				}
				
				
				/* show the excerpt when it's required */
				if ( $options['show_excerpt'] == 1 && ! empty( $popular_post->post_excerpt ) ) {
					
					$popular_item = sprintf( '%s<div class="excerpt">%s</div>', 
											$popular_item,
											esc_html( $popular_post->post_excerpt )
											);
				}
				
	
				/* wrap the content in the proper tags */
				$popular[] =  $options['before'] . $popular_item . $options['after'];
		
			}
	
		}

		/* return in the proper format */
		if ( ! empty( $popular ) ) {

			if ( 0 != $options['show'] )
				echo implode( '', $popular );
			else
				return implode( '', $popular );
		
		}
	
	}
	
	/**
	  * popular_posts_defaults sets defaults for plugin
	  *
	  * @access public
	  * @static
	  * @since Method available since Release 14.11
	  *
	  */	 
	function popular_posts_defaults() {
	
		$default_options = array(
									'title'     		=> __( 'Easy Popular Posts', THISISMYURL_EPP_NAME ),
									'post_count'    	=> 10,
									'order'    			=> 'DESC',
									'include_link' 		=> 1,
									'before'   			=> '<li>',
									'after'    			=> '</li>',
									'nofollow' 			=> 0,
									'show_excerpt' 		=> 0,
									'feature_image' 	=> 0,
									'show_credit' 		=> 1,
									'display_method' 	=> 'pageviews',
									'show'     			=> 0,
									
								);
								
		return $default_options;						
								
	}
	
	
	
	/**
	  * widget_init activates the plugin widgets
	  *
	  * @access public
	  * @static
	  * @uses register_widget
	  * @since Method available since Release 15.01
	  *
	  */
	function widget_init() {
		
		include_once( 'widgets/thisismyurl_EasyPopularPosts_Widget.php' );
		register_widget( 'thisismyurl_EasyPopularPosts_Widget' );
	
	}

	  
	
}
}

global $thisismyurl_EasyPopularPosts;

$thisismyurl_EasyPopularPosts = new thisismyurl_EasyPopularPosts;

$thisismyurl_EasyPopularPosts->run();




/**
  * Allows theme authors to call 
  *
  * @access public
  * @static
  * @uses $thisismyurl_EasyPopularPosts->easy_popular_posts
  * @since Method available since Release 15.01
  *
  * @param  array see $thisismyurl_EasyPopularPosts->popular_posts_defaults() for accepted options
  *
  */
if ( ! function_exists( 'thisismyurl_easy_popular_posts' ) ) {
function thisismyurl_easy_popular_posts( $options = NULL ) {
	
	global $thisismyurl_EasyPopularPosts;

	if ( ! isset( $options ) ) 
		$options = wp_parse_args( array( 'show'=> 1 ), $thisismyurl_EasyPopularPosts->popular_posts_defaults() );

	
	$thisismyurl_EasyPopularPosts->easy_popular_posts( $options );

}
}