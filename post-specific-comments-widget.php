<?php
/*
Plugin Name: Post-Specific Comments Widget (PSCW)
Description: Allows you to specify which post/page ID to display recent comments for (or show them all). Simple options for display format as well. 
Author: Little Package
Version: 1.0.2
Author URI: http://little-package.com
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=FK28Y6ZBG93X6
Plugin URI: 
License: GPLv2 or later
*/

add_action( 'widgets_init', 'pscw_init' );

function pscw_init() {
	register_widget( "Post_Specific_Comments" );
}

class Post_Specific_Comments extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_post_specific_comments', 'description' => __( 'The most recent comments for a specific post' ) );
		parent::__construct('post-specific-comments', __('Post-Specific Comments'), $widget_ops);
		$this->alt_option_name = 'widget_post_specific_comments';

		if ( is_active_widget(false, false, $this->id_base) )
			add_action( 'wp_head', array(&$this, 'recent_comments_style') );

		add_action( 'comment_post', array(&$this, 'flush_widget_cache') );
		add_action( 'transition_comment_status', array(&$this, 'flush_widget_cache') );
	}

	function recent_comments_style() {
		if ( ! current_theme_supports( 'widgets' ) // Temp hack #14876
			|| ! apply_filters( 'show_recent_comments_widget_style', true, $this->id_base ) )
			return;
		?>
	<style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
<?php
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_post_specific_comments', 'widget');
	}

	function widget( $args, $instance ) {
		global $comments, $comment;

		$cache = wp_cache_get('widget_post_specific_comments', 'widget');

		if ( ! is_array( $cache ) )
			$cache = array();

		if ( ! isset( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

 		extract($args, EXTR_SKIP);
 		$output = '';
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Recent Comments' ) : $instance['title'], $instance, $this->id_base );

		if ( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) )
 			$number = 5;

		if ( empty( $instance['postID'] ) || ! $postID = $instance['postID'] )
 			$postID = 0;
		
		if ($instance['postID'] != "0") {
			$comments = get_comments( apply_filters( 'widget_comments_args', array( 'number' => $number, 'post_id' => $postID, 'status' => 'approve', 'post_status' => 'publish') ) );
		} else {
			$comments = get_comments( apply_filters( 'widget_comments_args', array( 'number' => $number, 'status' => 'approve', 'post_status' => 'publish') ) );
		}
		
		if ( empty( $instance['excerpt_length'] ) || ! $excerpt_length = absint( $instance['excerpt_length'] ) )
 			$excerpt_length = 60;

		if ( empty( $instance['excerpt_trail'] ) || ! $excerpt_trail = $instance['excerpt_trail'] )
 			$excerpt_trail = "...";

		$output .= $before_widget;
		if ( $title )
			$output .= $before_title . $title . $after_title;

		$output .= '<ul id="recentcomments">';
		if ( $comments ) {
			foreach ( (array) $comments as $comment) {
				$aRecentComment = get_comment($comment->comment_ID);
				$aRecentCommentTxt = trim( mb_substr( strip_tags( apply_filters( 'comment_text', $aRecentComment->comment_content )), 0, $excerpt_length ));
				if(strlen($aRecentComment->comment_content)>$excerpt_length){
					$aRecentCommentTxt .= $excerpt_trail;
				}
	
				if ($instance['comment_format'] == "author-post") {
					$output .=  '<li class="recentcomments">' . /* translators: comments widget: 1: comment author, 2: post link */ sprintf(_x('%1$s on %2$s', 'widgets'), get_comment_author_link(), '<a href="' . esc_url( get_comment_link($comment->comment_ID) ) . '" class="' . $classes . '">' . get_the_title($comment->comment_post_ID) . '</a>') . '</li>';
				}

				if ($instance['comment_format'] == "author-excerpt") {
					$output .= '<li class="recentcomments">' . /* translators: comments widget: 1: comment author, 2: post link */ sprintf(_x('<span class="recentcommentsauthor">%1$s</span> said %2$s', 'widgets'), get_comment_author_link(), '<a href="' . esc_url( get_comment_link($comment->comment_ID) ) . '">' . $aRecentCommentTxt . '</a>') . '</li>';	
				}
			}
 		}
		$output .= '</ul>';
		$output .= $after_widget;

		echo $output;
		$cache[$args['widget_id']] = $output;
		wp_cache_set('widget_post_specific_comments', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = absint( $new_instance['number'] );
		$instance['postID'] = absint( $new_instance['postID'] );
		$instance['comment_format'] = $new_instance['comment_format'];
		$instance['excerpt_length'] = absint( $new_instance['excerpt_length'] );
		$instance['excerpt_trail'] = strip_tags( $new_instance['excerpt_trail'] );

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_post_specific_comments']) )
			delete_option('widget_post_specific_comments');

		return $instance;
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
		$postID = isset($instance['postID']) ? $instance['postID'] : all;
		$comment_format = isset($instance['comment_format']) ? $instance['comment_format'] : 'author-post';
		$excerpt_length = isset($instance['excerpt_length']) ? $instance['excerpt_length'] : '60';
		$excerpt_trail = isset($instance['excerpt_trail']) ? $instance['excerpt_trail'] : '...';

?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of Comments to Show:'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

		<p><label for="<?php echo $this->get_field_id('postID'); ?>"><?php _e('Post/Page ID Number:'); ?></label>
		<input id="<?php echo $this->get_field_id('postID'); ?>" name="<?php echo $this->get_field_name('postID'); ?>" type="text" value="<?php echo $postID; ?>" size="6" /></p>

		<p><legend><?php _e('Comment Format:'); ?></legend>
			<fieldset>
				<p style="text-indent: 20px"><input type="radio" name="<?php echo $this->get_field_name('comment_format'); ?>" <?php if (isset($comment_format) && $comment_format == "author-post") echo "checked"; ?> value="author-post"><label for="author-post"> (Author) on (Post Title)</label></p>
				<p style="text-indent: 20px"><input type="radio" name="<?php echo $this->get_field_name('comment_format'); ?>" <?php if (isset($comment_format) && $comment_format == "author-excerpt") echo "checked"; ?> value="author-excerpt"><label for="author-excerpt"> (Author) says (Excerpt)</label></p>
			</fieldset>
		</p>

		<p><label for="<?php echo $this->get_field_id('excerpt_length'); ?>"><?php _e('Excerpt Length:'); ?></label>
            	<input id="<?php echo $this->get_field_id('excerpt_length'); ?>" name="<?php echo $this->get_field_name('excerpt_length'); ?>" type="text" value="<?php echo $excerpt_length; ?>" size="3" />
            	<p><label for="<?php echo $this->get_field_id('excerpt_trail'); ?>"><?php _e('Excerpt Trailing:'); ?></label>
            	<input style="width: 100px;" id="<?php echo $this->get_field_id('excerpt_trail'); ?>" name="<?php echo $this->get_field_name('excerpt_trail'); ?>" type="text" value="<?php echo $excerpt_trail; ?>" size="3" />
            	</p>

<?php
	}
}


?>
