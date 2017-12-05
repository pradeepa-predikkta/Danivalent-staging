<?php
	$count_posts = wp_count_posts('media');
	$published_posts = $count_posts->publish;
	
	$args = array( 'post_type' => 'media', 'posts_per_page' => $published_posts );
	$loop = new WP_Query( $args );
	
	function cmp($a, $b)
	{
		return strcmp($a["peek_priority"], $b["peek_priority"]);
	}
	echo '<div class="sneak_peek_container">';
		echo '<div class="sneak_peek_title">Sneak peek - coming soon</div>';
		echo '<ul class="grid-3 media-list">';
			while($loop->have_posts()): $loop->the_post();
				$sneakpeek = get_post_meta(get_the_ID(), 'sneakpeek', true);
				if ($sneakpeek) {
					echo '<li>';
						echo '<div class="item-container">';
                        	echo '<div class="item-thumbnail">';
								$videos = get_post_meta(get_the_ID(), 'video', true);
								$images = get_post_meta(get_the_ID(), 'featimg_url', true);
								
								if($videos) {
									foreach($videos as $video) {
										if(isset($video['home_sneak_peek'])) {
											echo '<a href="'. get_permalink() .'"><img class="thumbnail" src="'.$video['thumbnail_3'].'" /><div class="btn-layer"><span class="inner"><img class="play-button" src="'. get_stylesheet_directory_uri() .'/images/play.svg"></span></div></a>';	
										}
									}
								}
								
								if($images) {
									foreach($images as $image) {
										if(isset($image['home_sneak_peek'])) {
											echo '<a href="'. get_permalink() .'"><img class="thumbnail" src="'.$image['medium'].'"><div class="btn-layer"><span class="inner"><img class="image-button" src="'. get_stylesheet_directory_uri() .'/images/icon_gallery01a.svg"></span></div></a>';
										}
									}
								}
							echo '</div>';
							echo '<div class="item-desc-content">';
								echo '<div class="item-name">';
									echo '<a href="'. get_permalink() . '">' . get_the_title() . '</a>';
								echo '</div>';
								echo '<div class="item-description">';
									echo the_excerpt();
								echo '</div>';
							echo '</div>';
						echo '</div>';
					echo '</li>';
				}
			endwhile;
		echo '</ul>';
	echo '</div>';
?>