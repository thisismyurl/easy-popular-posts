<?php

class thisismyurl_EasyPopularPosts_Widget extends WP_Widget {
	
	/**
	  * Standard Widget Constructor
	  *
	  * @access public
	  * @static
	  * @since Method available since Release 15.01
	  *
	  */
     function thisismyurl_EasyPopularPosts_Widget() {
		 
		$widget_ops = array( 
						'classname' => 'widget_thisismyurl_popular_posts', 
						'description' => __( 'A WordPress widget to add popular posts to any WordPress theme.', THISISMYURL_EPP_NAMESPACE ) 
					);
					
		$control_ops = array( 'width' => 300, 'height' => 300, 'id'=> 'widget_thisismyurl_popular_posts' );
		
		$this->WP_Widget( 	'thisismyurl_EasyPopularPosts_Widget', 
							__( 'Easy Popular Posts', THISISMYURL_EPP_NAMESPACE ), 
							$widget_ops, $control_ops
						);
						
	}



	/**
	  * Update Widget settings
	  *
	  * @access public
	  * @static
	  * @since Method available since Release 15.01
	  *
	  */
	function update( $new_instance, $old_instance ) {
		
		global $thisismyurl_EasyPopularPosts;
		
		$instance['title'] = $new_instance['title'];
		$instance['post_count'] = $new_instance['post_count'];
		$instance['include_link'] = $new_instance['include_link'];
		$instance['nofollow'] = $new_instance['nofollow'];
		$instance['show_excerpt'] = $new_instance['show_excerpt'];
		$instance['feature_image'] = $new_instance['feature_image'];
		$instance['show_credit'] = $new_instance['show_credit'];
		$instance['display_method'] = $new_instance['display_method'];
		
		$instance['show'] = 0;
		
		return $instance;
		
	}



	/**
	  * Display Widget settings
	  *
	  * @access public
	  * @static
	  * @since Method available since Release 15.01
	  *
	  */
	function form( $instance ) {
		
        global $thisismyurl_EasyPopularPosts;

		$instance = wp_parse_args( (array) $instance, $thisismyurl_EasyPopularPosts->popular_posts_defaults() );

		for ( $show_count = 5; $show_count <= 25; $show_count=$show_count+5 ) {
			
			$count_option = "<option value='$show_count' %s >$show_count</option>";
			
			if ( $instance['post_count'] == $show_count )
				$count_pulldown[] = sprintf( $count_option, 'selected' );
			else
				$count_pulldown[] = sprintf( $count_option, '' );
		}
		
		$count_pulldown = implode( THISISMYURL_EPP_NAMESPACE, $count_pulldown );
		
		$display_method[] = sprintf(	'<option value="%s" %s>%s</option>', 
										'comments', 
										selected( 'comments', $instance['display_method'], false ), 
										__( 'Comments', THISISMYURL_EPP_NAMESPACE ) 
							);
		$display_method[] = sprintf(	'<option value="%s" %s>%s</option>', 
										'pageviews', 
										selected( 'pageviews', $instance['display_method'], false ), 
										__( 'Pageviews', THISISMYURL_EPP_NAMESPACE ) 
							);
		
		$display_method_list = '<optgroup label="' . __( 'Local methods', THISISMYURL_EPP_NAMESPACE )  . '">' 
								. implode( '', $display_method ) . '</optgroup>';
		
		unset( $display_method );
		$display_method = array();
		
		
		$display_method[] = sprintf(	'<option value="%s" %s>%s</option>', 
										'facebook', 
										selected( 'facebook', $instance['display_method'], false ), 
										__( 'Facebook Posts', THISISMYURL_EPP_NAMESPACE ) 
							);
		$display_method[] = sprintf(	'<option value="%s" %s>%s</option>', 
										'linkedin', 
										selected( 'linkedin', $instance['display_method'], false ), 
										__( 'LinkedIn Shares', THISISMYURL_EPP_NAMESPACE ) 
							);
		$display_method[] = sprintf(	'<option value="%s" %s>%s</option>', 
										'twitter', 
										selected( 'twitter', $instance['display_method'], false ), 
										__( 'Twitter Shares', THISISMYURL_EPP_NAMESPACE ) 
							);
		$display_method[] = sprintf(	'<option value="%s" %s>%s</option>', 
										'stumbleupon', 
										selected( 'stumbleupon', $instance['display_method'], false ), 
										__( 'StumbleUpon Shares', THISISMYURL_EPP_NAMESPACE ) 
							);
		
		$display_method[] = sprintf(	'<option value="%s" %s>%s</option>', 
										'social', 
										selected( 'social', $instance['display_method'], false ), 
										__( 'All social shares', THISISMYURL_EPP_NAMESPACE ) 
							);
						
		$display_method_list .= '<optgroup label="' . __( 'Social counts', THISISMYURL_EPP_NAMESPACE )  . '">' 
								. implode( '', $display_method ) . '</optgroup>';
		?>
        
        <p><label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:', THISISMYURL_EPP_NAMESPACE );?></label><br/>
        	<input  class="widefat" 
            		id="<?php echo $instance['title']; ?>" 
                    name="<?php echo $this->get_field_name( 'title' ); ?>" 
                    value="<?php echo $instance['title'];?>" />
        </p>
        
        <p><input 	type="checkbox" 
        			id="<?php echo $this->get_field_name( 'include_link' ); ?>" 
                    name="<?php echo $this->get_field_name( 'include_link' ); ?>" 
                    value="1" <?php checked( 1, $instance['include_link'] );?> 
            />&nbsp;<label for="<?php echo $this->get_field_name( 'include_link' ); ?>"><?php _e( 'Include the links?', THISISMYURL_EPP_NAMESPACE );?></label>
        </p>
        
        <p><input 	type="checkbox" 
        			id="<?php echo $this->get_field_name( 'nofollow' ); ?>" 
                    name="<?php echo $this->get_field_name( 'nofollow' ); ?>" 
                    value="1" <?php checked( 1, $instance['nofollow'] );?> 
            />&nbsp;<label for="<?php echo $this->get_field_name( 'nofollow' ); ?>"><?php _e( 'Make <em>nofollow</em>?', THISISMYURL_EPP_NAMESPACE );?></label>
        </p>
        
        <p><input 	type="checkbox" 
        			id="<?php echo $this->get_field_name( 'show_excerpt' ); ?>" 
                    name="<?php echo $this->get_field_name( 'show_excerpt' ); ?>" 
                    value="1" <?php checked( 1, $instance['show_excerpt'] );?> 
            />&nbsp;<label for="<?php echo $this->get_field_name( 'show_excerpt' ); ?>"><?php _e( 'Include an excerpt?', THISISMYURL_EPP_NAMESPACE );?></label>
        </p>
        
        
        <p><input 	type="checkbox" 
        			id="<?php echo $this->get_field_name( 'feature_image' ); ?>" 
                    name="<?php echo $this->get_field_name( 'feature_image' ); ?>" 
                    value="1" <?php checked( 1, $instance['feature_image'] );?> 
            />&nbsp;<label for="<?php echo $this->get_field_name( 'feature_image' ); ?>"><?php _e( 'Include the image?', THISISMYURL_EPP_NAMESPACE );?></label>
        </p>
        
        <p><input 	type="checkbox" 
        			id="<?php echo $this->get_field_name( 'show_credit' ); ?>" 
                    name="<?php echo $this->get_field_name( 'show_credit' ); ?>" 
                    value="1" <?php checked( 1, $instance['show_credit'] );?> 
            />&nbsp;<label for="<?php echo $this->get_field_name( 'show_credit' ); ?>"><?php _e( 'Include credit link?', THISISMYURL_EPP_NAMESPACE );?></label>
        </p>
  

  
        <p><label for="<?php echo $instance['post_count']; ?>"><?php _e( 'How many posts to include?', THISISMYURL_EPP_NAMESPACE );?></label><br />
        <select id="<?php echo $instance['post_count']; ?>" name="<?php echo $this->get_field_name( 'post_count' ); ?>"><?php echo $count_pulldown;?></select>
        </p>
        
        <p><label for="<?php echo $instance['display_method']; ?>"><?php _e( 'How to determine popular posts?', THISISMYURL_EPP_NAMESPACE );?></label><br />
        <select id="<?php echo $instance['display_method']; ?>" name="<?php echo $this->get_field_name( 'display_method' ); ?>"><?php echo $display_method_list;?></select>
        </p>
        
        <?php
            
	
	}




	/**
	  * Display the Widget
	  *
	  * @access public
	  * @static
	  * @since Method available since Release 15.01
	  *
	  */
	function widget( $args, $instance ) {
		
        extract( $args );
		
        global $thisismyurl_EasyPopularPosts;

		$instance = wp_parse_args( (array) $instance, $thisismyurl_EasyPopularPosts->popular_posts_defaults() );
        
		$popular_posts = $thisismyurl_EasyPopularPosts->easy_popular_posts( $instance );
		
		if ( ! empty ( $popular_posts ) ) {
			/*  Before the widget */
			echo $before_widget;
			
			if ( ! empty ( $instance['title'] ) )
				$title = $instance['title'];
				
			if ( $instance['show_credit'] == 1 )	
				$title = sprintf( '<a 	href="http://thisismyurl.com/downloads/easy-popular-posts/" 
										title="' . esc_attr( __( 'Easy Popular Posts', THISISMYURL_EPP_NAME ) ). '">%s</a>',
								  $title
						  ); 
			
			if ( ! empty ( $title ) )
				echo $before_title . $title . $after_title;
				
			echo '<ul>' .  $popular_posts . '</ul>';
			echo $after_widget;
		}

	}

} // END class