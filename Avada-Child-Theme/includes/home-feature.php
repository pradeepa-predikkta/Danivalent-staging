
<?php
	global $post, $wp_roles;
	$user_role = "";
	require_once( ABSPATH . 'wp-includes/pluggable.php' );

	// if registered user (1)
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

	$count_posts = wp_count_posts('media');
	$published_posts = $count_posts->publish;

	$args = array( 'post_type' => 'media', 'posts_per_page' => $published_posts );
	$loop = new WP_Query( $args );
	echo '<div class="feature_articles">';
	echo '<div class="fusion-builder-row fusion-row"><h2 class="headingDashed" style="margin:0;margin-top:50px;font-size:24px;">Recipes</h2></div>';
	echo '<div class="fusion-builder-row fusion-row"><h3 class="subHeading" style="margin:0;margin-top:10px;">TOP VIDEOS & SNEAK PEEKS</h3></div>';
		// loop through the posts
		if ( have_posts() ) :
			while($loop->have_posts()): $loop->the_post();
				$farticles = get_post_meta(get_the_ID(), 'farticle', true);
				$videos = get_post_meta(get_the_ID(), 'video', true);
				$images = get_post_meta(get_the_ID(), 'featimg_url', true);

				if ($farticles) {
					if($videos) {
						foreach($videos as $video) {
							if($registered || $user_role == "customer") {
								if(isset($video['home_feature']) && isset($farticles['registered'])) {
									echo '<div class="feature_article">';
										// article title
										echo '<div class="feature_article_title"><a href="'. get_permalink() .'">' . get_the_title() . '</a></div>';
										// home feature image
										if(isset($video['has_registered'])) {
											echo '<div class="feature_article_image">';
												echo '<a href="'. get_permalink() .'"><img class="video_feature" src="'.$video['thumbnail_3'].'" /><div class="btn-layer feature"><span class="inner"><img class="play-button" src="'. get_stylesheet_directory_uri() .'/images/play.svg"></span></div></a>';
											echo '</div>';
										}
									echo '</div>';
								}
							}
							elseif($paid || $user_role == "subscriber") {
								if(isset($video['home_feature']) && isset($farticles['paid'])) {
									echo '<div class="feature_article">';
										// article title
										echo '<div class="feature_article_title"><a href="'. get_permalink() .'">' . get_the_title() . '</a></div>';
										// home feature image
										if(isset($video['has_paid'])) {
											echo '<div class="feature_article_image">';
												echo '<a href="'. get_permalink() .'"><img class="video_feature" src="'.$video['thumbnail_3'].'" /><div class="btn-layer feature"><span class="inner"><img class="play-button" src="'. get_stylesheet_directory_uri() .'/images/play.svg"></span></div></a>';
											echo '</div>';
										}
									echo '</div>';
								}
							}
							else {
								if(isset($video['home_feature']) && isset($farticles['free'])) {
									echo '<div class="feature_article">';
										// article title
										echo '<div class="feature_article_title"><a href="'. get_permalink() .'">' . get_the_title() . '</a></div>';
										echo "<span> - view for free</span>";
										// home feature image
										if(isset($video['is_free'])) {
											echo '<div class="feature_article_image">';
												echo '<a href="'. get_permalink() .'"><img class="video_feature" src="'.$video['thumbnail_3'].'" /><div class="btn-layer feature"><span class="inner"><img class="play-button" src="'. get_stylesheet_directory_uri() .'/images/play.svg"></span></div></a>';
											echo '</div>';
										}
									echo '</div>';
								}
							}
						}
					}

					if($images) {
						foreach($images as $image) {
							if( isset($image['home_feature']) && ( isset($farticles['registered']) || isset($farticles['paid']) || isset($farticles['free']) ) ) {
								echo '<div class="feature_article">';
									// article title
									echo '<div class="feature_article_title" style="display:inline;">' . get_the_title() . '</div>';

									if(isset($image['has_paid'])) {
										if(!$paid || !$user_role == "subscriber") {
											echo "<span> paid</span>";
										}

									} else if(isset($image['has_registered'])) {
										if(!$paid || !$user_role == "subscriber" || !$registered || !$user_role == "customer" ) {
											echo "<span> - register to view</span>";
										}

									} else {
										if(!$paid || !$user_role == "subscriber") {
											echo "<span> - view for free</span>";
										}
									}

									// home feature image
									echo '<div class="feature_article_image">';
										echo '<a href="'. get_permalink() .'"><img class="thumbnail" src="'.$image['medium'].'"><div class="btn-layer"><span class="inner"><img class="image-button" src="'. get_stylesheet_directory_uri() .'/images/icon_gallery01a.svg"></span></div></a>';
									echo '</div>';
								echo '</div>';
							}
						}
					}

				}
			endwhile;
		endif;
	echo '</div>';
	echo '<div style="text-align:center;"><a class="fusion-button button-flat fusion-button-pill button-medium button-default button-1" href="/cook/recipes/"><span class="fusion-button-text">View More Recipes</span></a></div>';
?>