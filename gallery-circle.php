<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Gallery Circle
 * Plugin URI:        http://
 * Description:       Plugin to show the post you want in a nice gallery circles. These circles have a link to the post. These circles have a nice hover effect. Some customizable parameters such as size, quantity, category, order and some more ...
 * Version:           0.2
 * Author: Gabriel Valero
 * Author URI:        https://www.linkedin.com/in/gabrielvaleroperez
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       gallery-circle
 */

wp_register_style('Gallery-Circle', '/wp-content/plugins/Gallery-Circle/css/style-gallery.css', array(), '', 'all' );
wp_enqueue_style( 'Gallery-Circle' );

function shortcode_showGallery( $atts ) {
	extract(shortcode_atts(array(
	'category'=>'',
	'post' => 20,
	'postextra' => 0,		
	'circlesrow'=> 0,
	'size'=> 200,
	'default_image'=>'',
	'post_thumbnail'=> true,
	'orderby'          => 'date',
	'order'            => 'DESC'
    ), $atts));
	$args = array(
	'category_name'    => $category,
	'posts_per_page'   => $post,
	'orderby'          => $orderby,
	'order'            => $order,
	'category_name'    => '',
	);
	query_posts( $args );

	$contPost=1;
	$default_image='default-'.$default_image.'.png';
	while ( have_posts() ) : the_post();
	$image = wp_get_attachment_url(get_post_thumbnail_id( $post->ID ));
	$title =get_the_title();
	echo '<div class="circle effect1" style="width:'.$size.'px;height:'.$size.'px;"><a href="'.get_permalink() .'">';
	echo '<div class="circleMove" style="width:'.($size+10).'px;height:'.($size+10).'px;"></div>';
	if($post_thumbnail==='false' || $image=='') {
		echo '<div class="circleImg"><img src="/wp-content/plugins/Gallery-Circle/images/'.$default_image.'" title="Read more about '.$title.'" alt="'.$title.'"/></div>';
	}
	else{
		echo '<div class="circleImg"><img src="'.$image.'" title="Read more about '.$title.'" alt="'.$title.'"></div>';
	}
	echo '<div class="circleInfo">';
	echo '<div class="circleInfo2"><span>'.$title.'</span></div>';
	echo '</div></a>';
	echo '</div>';
	if($circlesrow!=0 && $contPost%$circlesrow==0)echo '<br class="clearfix">';
	$contPost++;
	endwhile;
	wp_reset_query();
	
	
	/********* More Post************/	
	$args = array(
	'category_name'    => $category,
	'posts_per_page'   => $post+$postextra,
	'orderby'          => $orderby,
	'order'            => $order,
	'category_name'    => '',
	);
	query_posts( $args );

	if ( have_posts() && $postextra!=0) : 
	$contPost=1;
	$firstPost=true;
		while ( have_posts() ) : the_post();
			if($contPost>$post) {
				if($firstPost) {
					echo '<h1 id="hMore">More post...</h1>';
					echo '<div class="divMore">';
					$firstPost=false;
				}
				$image = wp_get_attachment_url(get_post_thumbnail_id( $post->ID ));
				$title =get_the_title();
				echo '<div class="divPost"><a href="'.get_permalink() .'">';
				echo '<div class="divPostcircleInfo">';
				if($post_thumbnail==='false' || $image=='') {
					echo '<img src="/wp-content/plugins/Gallery-Circle/images/'.$default_image.'" title="Read more about '.$title.'" alt="'.$title.'"/>';
				}
				else{
					echo '<img src="'.$image.'" title="Read more about '.$title.'" alt="'.$title.'"/>';
				}
				echo '<span>'.$title .'</span></div>';
				echo '</a></div>';
			}
		$contPost++;
		endwhile;
		echo '</div>';
	endif;
	wp_reset_query();
}

 add_shortcode( 'gallery-circle', 'shortcode_showGallery' );
?>