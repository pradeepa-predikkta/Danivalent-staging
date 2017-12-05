<?php
    
        // include 'popups.php';
    
	global $post, $wp_roles;
	$user_role = "";
	require_once( ABSPATH . 'wp-includes/pluggable.php' );
	
	if(!class_exists('Vimeo')) {
		include_once(get_stylesheet_directory() . "/classes/src/Vimeo/Vimeo.php");
	}
	
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

	$subheader = get_post_meta($post->ID, 'subheader', true);
	$videos = get_post_meta($post->ID, 'video', true);
	$images = get_post_meta($post->ID, 'featimg_url', true);
	$pdf = get_post_meta($post->ID, 'pdf', true);	
			
	$client_id = "f2b90a73c34c72d7fdbb4864fdd2d087d568d12e";
	$client_secret = "PN1IMoG/X4WnoGFjDikekzMBusAKwfircSIyuBO/4MjDy3WF+it1rPPPnUvKDV5pDIa9T3XrfEOJb47NXYXOlQTV+L26dzPrpxyCfk2Wxdtr4ukvuXSD2AcMHlMWo4yK";
	$access_token = "2ce5de15f9b36ebeb670fa9c1239797f";
	$video_data = "";
	
	//use Vimeo\Vimeo;
	$vimeo = new Vimeo($client_id, $client_secret, $access_token);
?>
<?php get_header(); ?>
	<?php
    	$terms = get_the_terms( $post->ID, 'media_categories' );
		$category_list = get_terms( 'media_categories', array( 'parent' => 0 ) );
		
		if(count($terms) > 0) {
			foreach ($terms as $term) {
				if($term->parent == 0) {
					$title = $term->name;
				} else {
					if(count($category_list) > 0) {
						foreach($category_list as $cat) {
							if($cat->term_id == $term->parent) {
								$title = $cat->name;	
							}
						}	
					}
				}
			}
		}
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$(".entry-title").show();
			// $(".entry-title").text("<?php echo $title?>");
		});
    </script>

    <!-- Fix Style Issue for some headings -->
    <style>
        .title-sml-style2 {
            color: #f36523 !important;
            font-weight: 800;
        }
    </style>
  
    <div id="media-posts-container" class="clearfix">
        <div class="media-listing-container?>">        	
        	<!-- If this user doesn't have full access & the recipe is not free, display the blurry images -->
        	<?php if(!$paid && !$free){ ?>
            	<h3 class="sub-header"><?php echo $subheader; ?></h3>
                <p><?php the_excerpt(); ?></p>
                
        		<img class="img-responsive paidRecipeImage" src="https://danivalent.com/wp-content/uploads/2017/09/dani-valent-paid-recipe.jpg"/>
        		<div class="paidRecipeOverlay">
        			<h2 style="color:#fff;">Become a member for instant access
        			<br /> to this great recipe and loads more.</h2>                    

        			<p>You'll be amazed what you can create.<br /><br /></p>
        			<p>
        				<a class="btn btn-clear" href="https://danivalent.com/cook/membership">Join</a>
        			</p>        			
        		</div>
                <div class="freeRecipeFooter">
                    <div class="row">
                        <div class="col-md-4">
                            <span class="fusion-imageframe imageframe-none imageframe-1 hover-type-none"><img src="https://danivalent.com/wp-content/themes/Avada-Child-Theme/images/dani-on-set-portrait.jpg" width="952" height="1200" alt="I love my Elk top &amp; Obus pants" title="Dani on set at Laneway Studio, wearing Elk top and Obus pants" class="img-responsive wp-image-16617" srcset="https://danivalent.com/wp-content/themes/Avada-Child-Theme/images/dani-on-set-portrait-200x252.jpg 200w, https://danivalent.com/wp-content/themes/Avada-Child-Theme/images/dani-on-set-portrait-400x504.jpg 400w, https://danivalent.com/wp-content/themes/Avada-Child-Theme/images/dani-on-set-portrait-600x756.jpg 600w, https://danivalent.com/wp-content/themes/Avada-Child-Theme/images/dani-on-set-portrait-800x1008.jpg 800w, https://danivalent.com/wp-content/themes/Avada-Child-Theme/images/dani-on-set-portrait.jpg 952w" sizes="(max-width: 800px) 100vw, 1100px"></span>
                        </div>
                        <div class="col-md-8">
                            <h2>What's this site all about?</h2>
                            <p>My business card says ‘Writer. Eater. Traveller. Cook.’ I do all these things with equal passion, which is why I’m sometimes sitting at my laptop with an apron on! This is where I share all my best bits of writing, recipes and videos. There are free areas of the site where you can stay up to date with my journalism and get a taste of my cooking adventures. Sign up as a member and you’ll get access to my awesome and ever-growing library of cooking videos and recipes, focusing on Thermomix.</p>

                            <p>This is a place for inspiration, chefs’ secrets, practical tuition and happy creativity. If you like cooking and eating delicious food and basking in the compliments of family and friends, this site is for you. You’ll be amazed what you can create with my recipes and videos as your guide.</p>

                            <p>See what recipes are available <a href="https://danivalent.com/cook/recipes/">here</a>.</p>

                            <p>What do members <a href="https://danivalent.com/testimonials/">love about the site</a>?</p>

                            <p><a class="fusion-button button-flat fusion-button-round button-large button-default button-2" target="_self" href="https://danivalent.com/cook/membership/"><span class="fusion-button-text">Become A Member</span></a></p>
                        </div>
                    </div>
                </div>
        	<?php } else {?>       		
            <h3 class="sub-header"><?php echo $subheader; ?></h3>            
            <?php echo get_favorites_button() ?>
            <div class="feature-header">
				<?php /* Display Feature Video */ 
                    $args = array(
                        "query" => "",
                        "sort" => "date",
                        "direction" => "desc"
                    );
                ?>
                <?php
                    if($videos) {
                        foreach ($videos as $video) {							
							if(isset($video['article_feature'])) {
								$video_id = $video['id']; 
								$user_data = $vimeo->request("/me/videos/" . $video_id, $args, 'GET', true);
								$video_data = $user_data['body']['embed']['html'];
								//echo $video_data;
								echo '<iframe src="//player.vimeo.com/video/'.$video_id.'?autoplay=true&title=0&byline=0&badge=0" width="1280" height="720" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
                        	}	
                        }
                    }
                ?>
                
                <?php /* Display Feature Image */ 
                    if($images) {
                        foreach ($images as $image) {							
							if(isset($image['article_feature']))	 {
								$alt = get_post_meta($image['id'], '_wp_attachment_image_alt', true);
								echo '<div class="banner_image"><img src="'. $image['url'] .'" alt="' . $alt . '"></div>';
							}							
                        }
                    }
                ?>
            </div>
            
            <?php /*
				$user_data = $vimeo->request("/me/videos/65479040", $args, 'GET', true);
				echo "<pre>";
                print_r($user_data);
				echo "</pre>";*/
			?>
            <!--
            <video src="https://player.vimeo.com/external/65479040.mobile.mp4?s=f4852702c1fff7b08109f6654fc8a202&profile_id=116&oauth2_token_id=75982015" controls></video>
            -->
            
            <div class="post-content">
            <?php /* Display Post Content */ 
				$posts = get_post($post->ID); 
				$content = apply_filters('the_content', $post->post_content); 
				echo $content;
			?>
           	</div>
            
            <?php
            	$pdf = get_post_meta($post->ID, 'pdf_url', true);
				if($pdf != "") {
					echo '<a href="'. $pdf.'" class="btn" target="_blank">View PDF</a>';
				}
            ?>
            
            <?php
            	$thumbnail_data = get_post_meta($post->ID, 'additional_video', true);
            	function cmp($a, $b)
				{
					return strcmp($a["order"], $b["order"]);
				}
				
				if ($thumbnail_data) {
			?>
                    <div class="media-content">
                        <div class="content-header">Additional Videos</div>
                        <ul class="grid-3 media-list">
						<?php /* Display Additional Media */
                            usort($thumbnail_data, "cmp");
							$i = 0;
                            foreach($thumbnail_data as $thumbnail) {
								$i++;
                            	if(($registered && $user_role == "customer") && isset($thumbnail['has_registered'])) 
								{
									?>
                                    <li class="video">
                                        <?php
                                            $video_clips = $vimeo->request("/me/videos/" . $thumbnail['id'], $args, 'GET', true);
                                            $video_clip = $video_clips['body']['embed']['html'];
                                            echo '<div class="mobile-videos">' . $video_clip . '</div>';
                                        ?>
                                        <div class="desktop-videos">
                                            <div class="item-container">
                                                <div class="item-thumbnail">
                                                    <a href="<?php echo $thumbnail['link'] ?>" class="play_video" id="video_<?php echo $i; ?>"><img class="thumbnail" src="<?php echo $thumbnail['thumbnail_3'] ?>" /><div class="btn-layer"><span class="inner"><img class="play-button" src="<?php echo get_stylesheet_directory_uri() ?>/images/play.svg"></span></div></a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
									<?php
								}
								elseif(($paid && $user_role == "subscriber") && isset($thumbnail['has_paid'])) 
								{
									?>
                                    <li class="video">
                                        <?php
                                            $video_clips = $vimeo->request("/me/videos/" . $thumbnail['id'], $args, 'GET', true);
                                            $video_clip = $video_clips['body']['embed']['html'];
                                            echo '<div class="mobile-videos">' . $video_clip . '</div>';
                                        ?>
                                        <div class="desktop-videos">
                                            <div class="item-container">
                                                <div class="item-thumbnail">
                                                    <a href="<?php echo $thumbnail['link'] ?>" class="play_video" id="video_<?php echo $i; ?>"><img class="thumbnail" src="<?php echo $thumbnail['thumbnail_3'] ?>" /><div class="btn-layer"><span class="inner"><img class="play-button" src="<?php echo get_stylesheet_directory_uri() ?>/images/play.svg"></span></div></a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
									<?php
								}
								elseif(($paid && $user_role == "customer") && isset($thumbnail['has_paid'])) 
								{
									?>
                                    <li class="video">
                                        <?php
                                            $video_clips = $vimeo->request("/me/videos/" . $thumbnail['id'], $args, 'GET', true);
                                            $video_clip = $video_clips['body']['embed']['html'];
                                            echo '<div class="mobile-videos">' . $video_clip . '</div>';
                                        ?>
                                        <div class="desktop-videos">
                                            <div class="item-container">
                                                <div class="item-thumbnail">
                                                    <a href="<?php echo $thumbnail['link'] ?>" class="play_video" id="video_<?php echo $i; ?>"><img class="thumbnail" src="<?php echo $thumbnail['thumbnail_3'] ?>" /><div class="btn-layer"><span class="inner"><img class="play-button" src="<?php echo get_stylesheet_directory_uri() ?>/images/play.svg"></span></div></a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
									<?php
								}
								elseif(isset($thumbnail['is_free'])) 
								{
									?>
                                    <li class="video">
                                        <?php
                                            $video_clips = $vimeo->request("/me/videos/" . $thumbnail['id'], $args, 'GET', true);
                                            $video_clip = $video_clips['body']['embed']['html'];
                                            echo '<div class="mobile-videos">' . $video_clip . '</div>';
                                        ?>
                                        <div class="desktop-videos">
                                            <div class="item-container">
                                                <div class="item-thumbnail">
                                                    <a href="<?php echo $thumbnail['link'] ?>" class="play_video" id="video_<?php echo $i; ?>"><img class="thumbnail" src="<?php echo $thumbnail['thumbnail_3'] ?>" /><div class="btn-layer"><span class="inner"><img class="play-button" src="<?php echo get_stylesheet_directory_uri() ?>/images/play.svg"></span></div></a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
									<?php
								}
                            }
                        	?>
                        </ul>
                    </div>
            <?php
            	}	 
			?>
            
            <?php
            	$thumbnail_data = get_post_meta($post->ID, 'additional_image', true);
				function pmp($a, $b)
				{
					return strcmp($a["order"], $b["order"]);
				}
				
				if ($thumbnail_data) {
			?>
                    <div class="media-content">
                        <div class="content-header">Gallery</div>
                        <ul class="grid-3 media-list">
                        <?php /* Display Additional Media */
                            usort($thumbnail_data, "pmp");
							$i = 0;
                            foreach($thumbnail_data as $thumbnail) {
                            	if($i == 0) { $first_img = "first"; } else { $first_img = ""; }
								
								if(($registered && $user_role == "customer") && isset($thumbnail['has_registered'])) {
									?>
                                    <li>
                                        <div class="item-container">
                                            <div class="item-thumbnail">
                                                <a href="<?php echo $thumbnail['full']; ?>" class="zoom <?php echo $first; ?> open-dialog" title="" data-rel="prettyPhoto[gallery]" data-caption="" data-title=""><img class="thumbnail" src="<?php echo $thumbnail['medium']; ?>" /> <div class="btn-layer"><span class="inner"><img class="image-button" src="<?php echo get_stylesheet_directory_uri() ?>/images/icon_gallery01a.svg"></span></div></a>	
                                            </div>
                                        </div>
                                    </li>
                                    <?php
								}
								elseif(($paid && $user_role == "subscriber") && isset($thumbnail['has_paid'])) {
									?>
                                    <li>
                                        <div class="item-container">
                                            <div class="item-thumbnail">
                                                <a href="<?php echo $thumbnail['full']; ?>" class="zoom <?php echo $first; ?> open-dialog" title="" data-rel="prettyPhoto[gallery]" data-caption="" data-title=""><img class="thumbnail" src="<?php echo $thumbnail['medium']; ?>" /> <div class="btn-layer"><span class="inner"><img class="image-button" src="<?php echo get_stylesheet_directory_uri() ?>/images/icon_gallery01a.svg"></span></div></a>	
                                            </div>
                                        </div>
                                    </li>
                                    <?php
								}
								elseif(($paid && $user_role == "customer") && isset($thumbnail['has_paid'])) {
									?>
                                    <li>
                                        <div class="item-container">
                                            <div class="item-thumbnail">
                                                <a href="<?php echo $thumbnail['full']; ?>" class="zoom <?php echo $first; ?> open-dialog" title="" data-rel="prettyPhoto[gallery]" data-caption="" data-title=""><img class="thumbnail" src="<?php echo $thumbnail['medium']; ?>" /> <div class="btn-layer"><span class="inner"><img class="image-button" src="<?php echo get_stylesheet_directory_uri() ?>/images/icon_gallery01a.svg"></span></div></a>	
                                            </div>
                                        </div>
                                    </li>
                                    <?php
								}
								elseif(isset($thumbnail['is_free'])) {
									?>
                                    <li>
                                        <div class="item-container">
                                            <div class="item-thumbnail">
                                                <a href="<?php echo $thumbnail['full']; ?>" class="zoom <?php echo $first; ?> open-dialog" title="" data-rel="prettyPhoto[gallery]" data-caption="" data-title=""><img class="thumbnail" src="<?php echo $thumbnail['medium']; ?>" /> <div class="btn-layer"><span class="inner"><img class="image-button" src="<?php echo get_stylesheet_directory_uri() ?>/images/icon_gallery01a.svg"></span></div></a>	
                                            </div>
                                        </div>
                                    </li>
                                    <?php
								}
								$i++;
                            }
                        ?>
                        </ul>
                    </div>
            <?php
				}
			?>
            <div class="related-posts">
            	<div class="content-header">Related Posts</div>
                    <ul class="grid-3 media-list">
                    <?php
                        if ($registered || $paid || $user_role == "customer" || $user_role == "subscriber") {
                            $orig_post = $post;
                            global $post;
        
                            $tags = wp_get_post_tags($post->ID);
                            $tag_ids = array();
                            foreach($tags as $individual_tag) {
                                $tag_ids[] = $individual_tag->term_id;
                            }
                            
                            $args = array(
                                'tag__in' => $tag_ids,
                                'post__not_in' => array($post->ID),
                                'posts_per_page' => 1, // Number of related posts to display.
                                'caller_get_posts' => 1,
                                'post_type' => 'media'
                            );
                         
                            $loop = new WP_Query( $args );
                            if ( have_posts() ) :
                                while($loop->have_posts()): $loop->the_post();
                                    $videos = get_post_meta($post->ID, 'video', true); 
                                    $images = get_post_meta($post->ID, 'featimg_url', true);
                                    $sneakpeek = get_post_meta($post->ID, 'sneakpeek', true);
                                    $icons = get_post_meta($post->ID, 'icon', true);
                                    $listfeature = get_post_meta($post->ID, 'listfeature', true);
                                    
									if(!$sneakpeek) {
										if(!$listfeature) {
                                            if($videos) {
                                                foreach($videos as $video) {
                                                    if ($registered && $user_role == "customer") {
                                                        if(isset($video['article_thumb'])) {
															if(isset($video['has_registered'])) {
																echo '<li>';
																	echo '<div class="item-container">';
																		echo '<div class="item-thumbnail">';
																			echo '<a href="'. get_permalink() .'"><img class="thumbnail" src="'.$video['thumbnail_3'].'" /><div class="btn-layer"><span class="inner"><img class="play-button" src="'. get_stylesheet_directory_uri() .'/images/play.svg"></span></div></a>';	
																		echo '</div>';
																		echo '<div class="item-desc-content">';
																			echo '<div class="item-name">';
																				echo '<a href="'. get_the_permalink() .'">'. get_the_title() .'</a>';
																			echo '</div>';
																			echo '<div class="item-description">'. get_the_excerpt() .'</div>';
																			echo '<div class="item-view">';
																				echo '<a class="view-more btn" href="'. get_the_permalink() .'" class="btn">View</a>';
																				if($icons != "") {
																					echo '<img src="'. get_stylesheet_directory_uri() .'/images/'.$icons.'.png">';
																				}
																			echo '</div>';
																		echo '</div>';
																	echo '</div>';
																echo '</li>';
															}
                                                        }
                                                    }
                                                    elseif ($paid && $user_role == "subscriber") {
                                                        if(isset($video['article_thumb'])) {
															if(isset($video['has_paid'])) {
																echo '<li>';
																	echo '<div class="item-container">';
																		echo '<div class="item-thumbnail">';
																			echo '<a href="'. get_permalink() .'"><img class="thumbnail" src="'.$video['thumbnail_3'].'" /><div class="btn-layer"><span class="inner"><img class="play-button" src="'. get_stylesheet_directory_uri() .'/images/play.svg"></span></div></a>';	
																		echo '</div>';
																		echo '<div class="item-desc-content">';
																			echo '<div class="item-name">';
																				echo '<a href="'. get_the_permalink() .'">'. get_the_title() .'</a>';
																			echo '</div>';
																			echo '<div class="item-description">'. get_the_excerpt() .'</div>';
																			echo '<div class="item-view">';
																				echo '<a class="view-more btn" href="'. get_the_permalink() .'" class="btn">View</a>';
																				if($icons != "") {
																					echo '<img src="'. get_stylesheet_directory_uri() .'/images/'.$icons.'.png">';
																				}
																			echo '</div>';
																		echo '</div>';
																	echo '</div>';
																echo '</li>';	
															}
                                                        }
                                                    }
													elseif ($paid && $user_role == "customer") {
                                                        if(isset($video['article_thumb'])) {
															if(isset($video['has_paid'])) {
																echo '<li>';
																	echo '<div class="item-container">';
																		echo '<div class="item-thumbnail">';
																			echo '<a href="'. get_permalink() .'"><img class="thumbnail" src="'.$video['thumbnail_3'].'" /><div class="btn-layer"><span class="inner"><img class="play-button" src="'. get_stylesheet_directory_uri() .'/images/play.svg"></span></div></a>';	
																		echo '</div>';
																		echo '<div class="item-desc-content">';
																			echo '<div class="item-name">';
																				echo '<a href="'. get_the_permalink() .'">'. get_the_title() .'</a>';
																			echo '</div>';
																			echo '<div class="item-description">'. get_the_excerpt() .'</div>';
																			echo '<div class="item-view">';
																				echo '<a class="view-more btn" href="'. get_the_permalink() .'" class="btn">View</a>';
																				if($icons != "") {
																					echo '<img src="'. get_stylesheet_directory_uri() .'/images/'.$icons.'.png">';
																				}
																			echo '</div>';
																		echo '</div>';
																	echo '</div>';
																echo '</li>';	
															}
                                                        }
                                                    }
                                                }
                                            }
                                            
                                            if($images) {
                                                foreach($images as $image) {
                                                    if ($registered && $user_role == "customer") {
                                                        if(isset($image['article_thumb'])) {
															if(isset($image['has_registered'])) {
																echo '<li>';
																	echo '<div class="item-container">';
																		echo '<div class="item-thumbnail">';
																			echo '<a href="'. get_permalink() .'"><img class="thumbnail" src="'.$image['medium'].'"><div class="btn-layer"><span class="inner"><img class="image-button" src="'. get_stylesheet_directory_uri() .'/images/icon_gallery01a.svg"></span></div></a>';	
																		echo '</div>';
																		echo '<div class="item-desc-content">';
																			echo '<div class="item-name">';
																				echo '<a href="'. get_the_permalink() .'">'. get_the_title() .'</a>';
																			echo '</div>';
																			echo '<div class="item-description">'. get_the_excerpt() .'</div>';
																			echo '<div class="item-view">';
																				echo '<a class="view-more btn" href="'. get_the_permalink() .'" class="btn">View</a>';
																				if($icons != "") {
																					echo '<img src="'. get_stylesheet_directory_uri() .'/images/'.$icons.'.png">';
																				}
																			echo '</div>';
																		echo '</div>';
																	echo '</div>';
																echo '</li>';
															}
                                                        }
                                                    }
                                                    elseif ($paid && $user_role == "subscriber") {
                                                        if(isset($image['article_thumb'])) {
															if(isset($image['has_paid'])) {
																echo '<li>';
																	echo '<div class="item-container">';
																		echo '<div class="item-thumbnail">';
																			echo '<a href="'. get_permalink() .'"><img class="thumbnail" src="'.$image['medium'].'"><div class="btn-layer"><span class="inner"><img class="image-button" src="'. get_stylesheet_directory_uri() .'/images/icon_gallery01a.svg"></span></div></a>';	
																		echo '</div>';
																		echo '<div class="item-desc-content">';
																			echo '<div class="item-name">';
																				echo '<a href="'. get_the_permalink() .'">'. get_the_title() .'</a>';
																			echo '</div>';
																			echo '<div class="item-description">'. get_the_excerpt() .'</div>';
																			echo '<div class="item-view">';
																				echo '<a class="view-more btn" href="'. get_the_permalink() .'" class="btn">View</a>';
																				if($icons != "") {
																					echo '<img src="'. get_stylesheet_directory_uri() .'/images/'.$icons.'.png">';
																				}
																			echo '</div>';
																		echo '</div>';
																	echo '</div>';
																echo '</li>';
															}
                                                        }
                                                    }
													elseif ($paid && $user_role == "customer") {
                                                        if(isset($image['article_thumb'])) {
															if(isset($image['has_paid'])) {
																echo '<li>';
																	echo '<div class="item-container">';
																		echo '<div class="item-thumbnail">';
																			echo '<a href="'. get_permalink() .'"><img class="thumbnail" src="'.$image['medium'].'"><div class="btn-layer"><span class="inner"><img class="image-button" src="'. get_stylesheet_directory_uri() .'/images/icon_gallery01a.svg"></span></div></a>';	
																		echo '</div>';
																		echo '<div class="item-desc-content">';
																			echo '<div class="item-name">';
																				echo '<a href="'. get_the_permalink() .'">'. get_the_title() .'</a>';
																			echo '</div>';
																			echo '<div class="item-description">'. get_the_excerpt() .'</div>';
																			echo '<div class="item-view">';
																				echo '<a class="view-more btn" href="'. get_the_permalink() .'" class="btn">View</a>';
																				if($icons != "") {
																					echo '<img src="'. get_stylesheet_directory_uri() .'/images/'.$icons.'.png">';
																				}
																			echo '</div>';
																		echo '</div>';
																	echo '</div>';
																echo '</li>';
															}
                                                        }
                                                    }
                                                }
                                            }
										}
									}                               
                                endwhile;
                            else :
                                echo '<img src="">';	
                            endif;
                        }
                    ?>
                	</ul>
  		        </div>
            </div>
            <?php } ?>
            <?php if($free){ ?>
                <div class="freeRecipeFooter hideForSubscriber">
                    <div class="row">
                        <div class="col-md-4">
                            <span class="fusion-imageframe imageframe-none imageframe-1 hover-type-none"><img src="https://danivalent.com/wp-content/themes/Avada-Child-Theme/images/dani-on-set-portrait.jpg" width="952" height="1200" alt="I love my Elk top &amp; Obus pants" title="Dani on set at Laneway Studio, wearing Elk top and Obus pants" class="img-responsive wp-image-16617" srcset="https://danivalent.com/wp-content/themes/Avada-Child-Theme/images/dani-on-set-portrait-200x252.jpg 200w, https://danivalent.com/wp-content/themes/Avada-Child-Theme/images/dani-on-set-portrait-400x504.jpg 400w, https://danivalent.com/wp-content/themes/Avada-Child-Theme/images/dani-on-set-portrait-600x756.jpg 600w, https://danivalent.com/wp-content/themes/Avada-Child-Theme/images/dani-on-set-portrait-800x1008.jpg 800w, https://danivalent.com/wp-content/themes/Avada-Child-Theme/images/dani-on-set-portrait.jpg 952w" sizes="(max-width: 800px) 100vw, 1100px"></span>
                        </div>
                        <div class="col-md-8">
                            <h2>What's this site all about?</h2>
                            <p>My business card says ‘Writer. Eater. Traveller. Cook.’ I do all these things with equal passion, which is why I’m sometimes sitting at my laptop with an apron on! This is where I share all my best bits of writing, recipes and videos. There are free areas of the site where you can stay up to date with my journalism and get a taste of my cooking adventures. Sign up as a member and you’ll get access to my awesome and ever-growing library of cooking videos and recipes, focusing on Thermomix.</p>

                            <p>This is a place for inspiration, chefs’ secrets, practical tuition and happy creativity. If you like cooking and eating delicious food and basking in the compliments of family and friends, this site is for you. You’ll be amazed what you can create with my recipes and videos as your guide.</p>

                            <p>See what recipes are available <a href="https://danivalent.com/cook/recipes/">here</a>.</p>

                            <p>What do members <a href="https://danivalent.com/testimonials/">love about the site</a>?</p>

                            <p><a class="fusion-button button-flat fusion-button-round button-large button-default button-2" target="_self" href="https://danivalent.com/cook/membership/"><span class="fusion-button-text">Become A Member</span></a></p>
                            <!-- <button class="addtocart">Add to Cart</button> -->
                        </div>
                    </div>
                </div>
            <?php }?>
		</div>
    </div>
<?php if(!is_user_logged_in()){ ?>
    <script>
        jQuery(document).ready(function($) {
            var isFreeRecipe = <?php echo $free? "true":"false" ?>;
            var freeRecipesVisitTime = checkVisitFreeRecipesTime(isFreeRecipe);
            var close = GetCookie("close");
            if(freeRecipesVisitTime > 4 && !close){
                $(".freeRecipeBox").show();
                $('.coverPage').fadeIn();
            }

            $(".addtocart").on("click",function(){
            	$.ajax({
				    type: 'POST',
				    url: '/wp-admin/admin-ajax.php',
				    data: {'action' : 'add_coupon_action','couponcode' : 'cta_from_button','product_id' : '240'},
				    beforeSend: function () {
				         $(".addtocart").html("Adding ...");
				    },
				    success: function (data){
						var expdate = new Date();
				        // 24*60*60*1000 = 1 day , adjust accordingly
				        expdate.setTime(expdate.getTime() +  (24 * 60 * 60 * 1000 * 365));
						SetCookie("close", 1, expdate, "/", null, false);
				    	window.location.href="/cart";
				    	// console.log(data);
				    },
				    error: function () {}
				});       
        	});
        });
    </script>
<?php } ?>
<?php get_footer(); ?>