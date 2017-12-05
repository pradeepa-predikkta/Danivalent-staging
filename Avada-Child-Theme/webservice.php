<?php
	require(dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php');
	
	$action = $_REQUEST['action'];
	$search = $_REQUEST['s'];
	$offset = $_REQUEST['offset'];
	$type = $_REQUEST['type'];
	$page = $_REQUEST['page'];
	if ( $paid_group = Groups_Group::read_by_name( 'Full Access' ) ) {
		$paid = Groups_User_Group::read( get_current_user_id() , $paid_group->group_id );
	}
	switch ($action) { 
		case "get_videos": 
			if(!class_exists('Vimeo')) {
				include_once(get_stylesheet_directory() . "/classes/src/Vimeo/Vimeo.php");
			}	
			
			$client_id = "f2b90a73c34c72d7fdbb4864fdd2d087d568d12e";
			$client_secret = "PN1IMoG/X4WnoGFjDikekzMBusAKwfircSIyuBO/4MjDy3WF+it1rPPPnUvKDV5pDIa9T3XrfEOJb47NXYXOlQTV+L26dzPrpxyCfk2Wxdtr4ukvuXSD2AcMHlMWo4yK";
			$access_token = "2ce5de15f9b36ebeb670fa9c1239797f";
			
			//use Vimeo\Vimeo;
			$vimeo = new Vimeo($client_id, $client_secret, $access_token);
			
			if($search == "all") {
				//$args = '';
				$args = array(
					"per_page" => 50,
					"page" => $page
				);
			} else {
				$args = array(
					"query" => $search,
					"sort" => "date",
					"direction" => "desc",
					"per_page" => 50,
					"page" => $page
				);
			}
			
			$user_data = $vimeo->request("/me/videos", $args, 'GET', true);
			$video_data = $user_data['body']['data'];
			
			$html = '';
			$html .= '<a class="btn-pagination button button-large button-1" href="#">1</a> <a class="btn-pagination button button-large button-2" href="#">2</a> <a class="btn-pagination button button-large button-3" href="#">3</a><br><br>';
			if(count($video_data) > 0) 
			{
				foreach($video_data as $video_datae)
				{
					$uri = explode("/", $video_datae['uri']);
					$id = end($uri);
					$title = $video_datae['name'];
					$duration = $video_datae['duration'];
					$link = $video_datae['link'];
					$thumbnail_url = $video_datae['pictures']['sizes'][1]['link'];
					$thumbnail_url_2 = $video_datae['pictures']['sizes'][2]['link'];
					$thumbnail_url_3 = $video_datae['pictures']['sizes'][3]['link'];
					$thumbnail_url_4 = $video_datae['pictures']['sizes'][4]['link'];
					
					$html .= '<div class="vimeo_item">';
						$html .= '<a class="add_vimeo_item" href="#" data-id="'. $id .'" data-title="'. $title .'" data-duration="'. gmdate("H:i:s", $duration) .'" data-link="'. $link .'" data-thumbnail="'. $thumbnail_url .'" data-thumbnail-2="'. $thumbnail_url_2 .'" data-thumbnail-3="'. $thumbnail_url_3 .'" data-thumbnail-4="'. $thumbnail_url_4 .'">';
							$html .= '<img src="' . $thumbnail_url . '">';
						$html .= '</a>';
						$html .= '<div class="item_title">' . $title. '</div>';
						$html .= '<div class="item_duration">(' . gmdate("H:i:s", $duration) . ')</div>';
					$html .= '</div>';
				}
				$html .= '<br><br><a class="btn-pagination button button-large button-1" href="#">1</a> <a class="btn-pagination button button-large button-2" href="#">2</a> <a class="btn-pagination button button-large button-3" href="#">3</a>';
			echo $html;
			}
			else {
				echo '<div class="no-videos">No videos found.</div>';	
			}
		break;
		case "get_posts": 
			if($type == "all"){
				$args = array( 
					'post_type' => 'media', 
					'posts_per_page' => 9, 
					'offset' => $offset ,
					'tax_query' => array(
					    array(
					      'taxonomy' => 'media_categories',
					      'field'    => 'slug',
					      'terms'    => 'sneak-peeks',
					      'operator' => 'NOT IN' 
					    )
				));
			}else{
				$args = array( 'post_type' => 'media', 
					'media_categories' => $type, 
					'posts_per_page' => 9,
					'offset' => $offset,
					'tax_query' => array(
					    array(
					      'taxonomy' => 'media_categories',
					      'field'    => 'slug',
					      'terms'    => 'sneak-peeks',
					      'operator' => 'NOT IN' 
					     )
				));
			}
			
			$loop = new WP_Query( $args );
			
			while($loop->have_posts()): $loop->the_post();
				$listfeature = get_post_meta(get_the_ID(), 'listfeature', true);
				
				//check if the recipe is free
				$free = false;
				$ispaid = get_post_meta(get_the_ID(), 'ispaid', true);
				if(!isset($ispaid) || isset($ispaid['free'])){
					$free = true;
				}
				print_posts($paid,$free);
			endwhile;	
			wp_reset_query();
		break;
		case "get_query": 
			$sq = $_REQUEST['squery'];
			$filter_offset = $_REQUEST['filter_offset'];
			
			outputSearchResults($sq, $filter_offset);
			wp_reset_query();
		break;
		case "get_favs":
			getUserFavouriteRecipes($paid);
		break;
	}
	
	function getUserFavouriteRecipes($paid){
		$arrFavIds = get_user_favorites();
		$videos = get_post_meta($arrFavIds[0], 'video'); 
		// echo json_encode($videos);
		$html = '';
		foreach ($arrFavIds as $f){
			$free = false;
			$ispaid = get_post_meta($f, 'ispaid', true);
			if(!isset($ispaid) || isset($ispaid['free'])){
				$free = true;
			}
			$html .= '<li>';
			$html .= '<div class="item-container">';
				$html .= '<div class="item-thumbnail">';
				$videos = get_post_meta($f, 'video'); 
				$images = get_post_meta($f, 'featimg_url');
				$url = empty($videos[0]) ? $images[0][key($images[0])]['medium'] : $videos[0][key($videos[0])]['thumbnail_3'] ;
						// $url = str_replace("http://", "https://", subject)
						if(empty($url)){
							$url = "/wp-content/themes/Avada-Child-Theme/images/Dani-on-set.jpg";
						}
						// echo json_encode($videos) ;exit();
						$html .=  '<a target="_blank" href="'. get_the_permalink($f) .'"><img class="thumbnail" src="'.$url.'" />';
						$html .=  '<div class="btn-layer">';
							if(!$paid && !$free){ 
								$html .=  '<span class="">';
								$html .=  '<img class="img-responsive" src="/wp-content/themes/Avada-Child-Theme/images/lined-overlay-hover.png" alt="subscribe dani valent" sizes="(max-width: 800px) 100vw, 200px" height="667" width="1000" >';
							}else{
								// $html .=  '<span class="inner">';
								// $html .=  '<img class="play-button" src="'. get_stylesheet_directory_uri() .'/images/play.svg">';
							}
						$html .=  '</span></div></a>';
					$html .=  '</div>';

					$html .=  '<div class="item-desc-content">';
						$html .=  '<div class="item-name">';
							$html .=  '<a href="'. get_the_permalink($f) .'">'. get_the_title($f) .'</a>';
						$html .=  '</div>';
						$html .=  '<div class="item-description">'. get_the_excerpt($f) .'</div>';
						$html .=  '<div class="item-view">';
							$html .=  '<a class="view-more btn" href="'. get_the_permalink($f) .'" class="button">View</a>';
							if($icons != "") {
								$html .=  '<span class="icon-'. $icons .'" style="color: '. $icon_color .'"></span>';
							}
						$html .=  '</div>';
					$html .=  '</div>';
				$html .=  '</div>';
			$html .=  '</li>';
		}
		echo $html;
	}

	function print_posts($paid,$free) {
		$html = '';
		$html .= '<li>';
			$html .= '<div class="item-container">';
				$html .= '<div class="item-thumbnail">';
					$videos = get_post_meta(get_the_ID(), 'video'); 
					$images = get_post_meta(get_the_ID(), 'featimg_url');
					

					$url = empty($videos[0]) ? $images[0][key($images[0])]['medium'] : $videos[0][key($videos[0])]['thumbnail_3'] ;
					// $url = str_replace("http://", "https://", subject)
					if(empty($url)){
						$url = "/wp-content/themes/Avada-Child-Theme/images/Dani-on-set.jpg";
					}
					// echo json_encode($videos) ;exit();
					$html .=  '<a target="_blank" href="'. get_permalink() .'"><img class="thumbnail" src="'.$url.'" />';
					$html .=  '<div class="btn-layer">';
						if(!$paid && !$free){ 
							$html .=  '<span class="">';
							$html .=  '<img class="img-responsive" src="/wp-content/themes/Avada-Child-Theme/images/lined-overlay-hover.png" alt="subscribe dani valent" sizes="(max-width: 800px) 100vw, 200px" height="667" width="1000" >';
						}else{
							// $html .=  '<span class="inner">';
							// $html .=  '<img class="play-button" src="'. get_stylesheet_directory_uri() .'/images/play.svg">';
						}
					$html .=  '</span></div></a>';
				$html .=  '</div>';

				$html .=  '<div class="item-desc-content">';
					$html .=  '<div class="item-name">';
						$html .=  '<a target="_blank" href="'. get_the_permalink() .'">'. get_the_title() .'</a>';
					$html .=  '</div>';
					$html .=  '<div class="item-description">'. get_the_excerpt() .'</div>';
					$html .=  '<div class="item-view">';
						$html .=  '<a class="view-more btn" href="'. get_the_permalink() .'" class="button">View</a>';
						if($icons != "") {
							$html .=  '<span class="icon-'. $icons .'" style="color: '. $icon_color .'"></span>';
						}
					$html .=  '</div>';
				$html .=  '</div>';
			$html .=  '</div>';
		$html .=  '</li>';
						
		echo $html;
	}
	
	
	
	function outputSearchResults($sq, $foffset) {
		/* For search */
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
		/* end */
		$args = array( 
			'post_type' => 'media', 
			// 'media_categories' => 'recipes', 
			'posts_per_page' => 9,
			'offset' => $foffset,
			's' => $sq,
			'sentence' => false,
			'tax_query' => array(
			    array(
			      'taxonomy' => 'media_categories',
			      'field'    => 'slug',
			      'terms'    => 'sneak-peeks',
			      'operator' => 'NOT IN' 
			    )
			)
		);
		// echo json_encode($args);exit();
		$loop = new WP_Query( $args );
		while($loop->have_posts()): $loop->the_post();
			$videos = get_post_meta(get_the_ID(), 'video');
			$images = get_post_meta(get_the_ID(), 'featimg_url');
			$icons = get_post_meta(get_the_ID(), 'icon', true);
			$icon_color = get_post_meta(get_the_ID(), 'icon-color', true);
			$default_color = get_post_meta(get_the_ID(), 'default-color', true);
			$free = false;
			$ispaid = get_post_meta(get_the_ID(), 'ispaid', true);
			if(!isset($ispaid) || isset($ispaid['free'])){
				$free = true;
			}
			if(!$icon_color) {
				$icon_color = $default_color;
			}				
			$url = empty($videos[0]) ? $images[0][key($images[0])]['medium'] : $videos[0][key($videos[0])]['thumbnail_3'] ;

			if(empty($url)){
				$url = "/wp-content/themes/Avada-Child-Theme/images/Dani-on-set.jpg";
			}			
			echo '<li>';
				echo '<div class="item-container">';
					echo '<div class="item-thumbnail">';
						echo '<a target="_blank" href="'. get_permalink() .'"><img class="thumbnail" src="'.$url.'" />';
						echo '<div class="btn-layer">';
							if(!$paid && !$free){ 
								echo '<span class="">';
								echo '<img class="img-responsive" src="/wp-content/themes/Avada-Child-Theme/images/lined-overlay-hover.png" alt="subscribe dani valent" sizes="(max-width: 800px) 100vw, 200px" height="667" width="1000" >';
							}else{
								// echo '<span class="inner">';
								// echo '<img class="play-button" src="'. get_stylesheet_directory_uri() .'/images/play.svg">';
							}
						echo '</span></div></a>';
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
		endwhile;
	}
?>