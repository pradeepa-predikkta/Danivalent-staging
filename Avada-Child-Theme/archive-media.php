<?php
	global $wp, $wp_roles;
	$user_role = "";
	$category_name = $wp->query_vars['category'];
	$subcategory = $wp->query_vars['subcategory'];
	$tag = false;
	if($category_name == "recipes") {
		$child_of = 3;	
	}else if($category_name == "chefs") {
		$child_of = 2;	
	}else if ($category_name == "tag"){
		$child_of = 1003;	
	}
	
	require_once( ABSPATH . 'wp-includes/pluggable.php' );
	
	if ( $registered_group = Groups_Group::read_by_name( 'Registered' ) ) {
		$registered = Groups_User_Group::read( get_current_user_id() , $registered_group->group_id );
	}
	
	// if paid user (3)
	if ( $paid_group = Groups_Group::read_by_name( 'Full Access' ) ) {
		$paid = Groups_User_Group::read( get_current_user_id() , $paid_group->group_id );
	}
	
	foreach ( $wp_roles->role_names as $role => $name ) :
		if ( current_user_can( $role ) ) {
			$user_role = strtolower($role);
		}
	endforeach;
	//check if the recipe is free
	$free = false;
	$ispaid = get_post_meta($post->ID, 'ispaid', true);
	if(!isset($ispaid) || isset($ispaid['free'])){
		$free = true;
	}
	$image_attributes = wp_get_attachment_image_src(16515);
?>
<?php get_header(); ?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$(".entry-title").show();
			$(".entry-title").text("<?php echo ucfirst($category_name); ?>");
		});
    </script>
	<?php if(category_description()): ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="post-content">
            <?php echo category_description(); ?>
        </div>
    </div>
    <?php endif; ?>
    
    <div id="media-posts-container" class="clearfix">
        <div class="media-listing-container media-intro">
        	<div class="intro-text">
            	<?php 
					// $term_id = 2;
					// $content_full = get_option("media_cat_full_" . $term_id);
					// $content_registered = get_option("media_cat_registered_" . $term_id);
					// $content_public = get_option("media_cat_public_" . $term_id);
					
					// if($paid || $user_role == "subscriber") {
					// 	echo stripslashes(wpautop($content_full));
					// }
					// else if($registered || $user_role == "customer") {
					// 	echo stripslashes(wpautop($content_registered));
					// }
					// else {
					// 	echo stripslashes(wpautop($content_public));
					// }
					
					// display categories
					$args = array( 
						'orderby' => 'ID',
						'order' => 'ASC',
						'child_of' => $child_of,	
						'hide_empty' => 0,
						'hierarchical' => 1,
						'taxonomy' => 'media_categories',
						'hide_empty' => false,
					);
					
					$categories = get_categories( $args );
				?>
            </div>
            <div class="category-dropdown-container">
            	<div class="category-section">You are now viewing <?php echo ucfirst($subcategory); ?></div>
                <div class="category-dropdown">
                    <a href="#" class="first-item">Category &nbsp;&nbsp;<i class="fa fa-chevron-down"></i></a>
                    <div class="category-dropdown-list">
                        <ul>
                            <li class="blank"></li>
                        <?php foreach($categories as $category) { ?>
                        	<?php if($category->count > 0) { ?>
                            	<li><a href="<?php site_url() ?>/recipe/<?php echo $category_name; ?>/<?php echo $category->slug; ?>"><?php echo $category->name; ?></a></li>
                            <?php } ?>
                        <?php } ?>
                            <li class="blank"><a href="<?php site_url() ?>/<?php echo $category_name; ?>/">All <?php echo ucfirst($category_name); ?></a></li>
                        </ul>
                	</div>
                </div>
                <div class="clr"></div>
            </div>
        </div>
		<div class="media-listing-container media-content">    
                <ul class="media-list grid-3">
                    <?php

                        $args = array( 'post_type' => 'media', 'media_categories' => $subcategory, 'posts_per_page' => 9, 'offset' => 0 );
                    	
                        $loop = new WP_Query( $args );

                        if ( have_posts() ) :
                            while($loop->have_posts()): $loop->the_post();
								$videos = get_post_meta(get_the_ID(), 'video', true); 
								foreach($videos as $video) {
									echo '<li>';
										echo '<div class="item-container">';
											echo '<div class="item-thumbnail">';
												echo '<a target="_blank" href="'. get_permalink() .'"><img class="thumbnail" src="'.$video['thumbnail_3'].'" />';
												echo '<div class="btn-layer">';
													if(!$paid && !$free){ 
														echo '<span class="">';
														echo '<img class="img-responsive" src="'.$image_attributes[0].'" alt="subscribe dani valent" sizes="(max-width: 800px) 100vw, 200px" height="667" width="1000" >';
														// print_r ($image_attributes);
													}else{
														// echo '<span class="inner">';
														// echo '<img class="play-button" src="'. get_stylesheet_directory_uri() .'/images/play.svg"></span>';
													}
												echo '</div></a>';
											echo '</div>';

											echo '<div class="item-desc-content">';
												echo '<div class="item-name">';
													echo '<a target="_blank" href="'. get_the_permalink() .'">'. get_the_title() .'</a>';
												echo '</div>';
												echo '<div class="item-description">'. get_the_excerpt() .'</div>';
												echo '<div class="item-view">';
													echo '<a class="view-more btn" href="'. get_the_permalink() .'" class="button">View</a>';
													if($icons != "") {
														echo '<span class="icon-'. $icons .'" style="color: '. $icon_color .'"></span>';
													}
												echo '</div>';
											echo '</div>';
										echo '</div>';
									echo '</li>';
								}
                            endwhile;
                        else :
                            echo '<img src="">';	
                        endif;
                        ?>
                </ul>
                
                <div class="load-more-container">
                	<?php if ($loop->found_posts > 9) { ?>
                		<a href="#" class="btn load-more" data-level="1" data-type="<?=$subcategory ?>">Load more - Total <?php echo $loop->found_posts; ?> <? echo ucfirst($category_name) ?></a>
                    <?php } ?>
                </div>
           
		</div>
    </div>
<?php get_footer();

// Omit closing PHP tag to avoid "Headers already sent" issues.
