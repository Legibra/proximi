<?php
/**
 * Single content
 *
 * @package Jobify
 * @since 1.0.0
 * @version 3.8.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="col-md-12 col-xs-12 entry-content<?php echo has_shortcode( $post->post_content, 'jobs' ) ? ' has-jobs' : null; ?>">
		<?php the_content(); ?>
	</div>
	<!-- <div class="col-md-4 col-xs-12">
	</div>	 -->
</article><!-- #post -->
