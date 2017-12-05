<?php
	// Template Name: Recipes
?>
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

	//check if the recipe is free
	$free = false;
	$ispaid = get_post_meta($post->ID, 'ispaid', true);
	if(!isset($ispaid) || isset($ispaid['free'])){
		$free = true;
	}
	
	//get query string	
    $value= get_query_var('value');
       
?>
<?php get_header(); ?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$(".entry-title").show();
		});
    </script>
	<?php if(category_description()): ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
        <div class="post-content">
            <?php echo category_description(); ?>
        </div>
    </div>
    <?php endif; ?>    
    <?php 
		$filters = get_terms( 
		   'media_categories', 
		   array('parent' => 0)
		);
	 ?>
	 <div class="slide-out-sidebar">
        		<div>
        			<img class="close" src="/wp-content/themes/Avada-Child-Theme/images/btn-x-white@2x.png">
        			<ul>
	        			<li class="filter-by">Filter By:</li>
	        			<?php foreach ($filters as $f){ 
	        				if($f->name != "Sneak Peeks") {?>
			        			<li class="closed-level-1">
			        				<span class="pointer"><?php echo $f->name?></span>
		        				<?php 
					        		$args = array(
										'orderby' => 'name',
										'order' => 'ASC',
										'child_of' => $f->term_id,
										'hide_empty' => 0,
										'hierarchical' => 1,
										'taxonomy' => 'media_categories',
										'hide_empty' => false,
									);
									$subCat = get_categories($args);
									echo "<ul>";
									foreach ($subCat as $s) {?>
		        						<li><a class="filter-item" data-parent="<?php echo $f->name?>" data-offset="0" data-type="<?php echo $s->slug?>" style="color:#f36523" ><?php echo $s->name ?></a></li>
		        				<?php }
		        					echo "</ul>";
		        				?>
			        			</li>
	        			<?php }} ?>
        			</ul>
        		</div>
        	</div>
    <div id="media-posts-container" class="clearfix">
        <div class="media-listing-container media-intro">
        	
        	<div class="intro-text">
            	<?php
					
					$args = array(
						'orderby' => 'name',
						'order' => 'ASC',
						'child_of' => 1003,	 //everyday cooking categories
						'hide_empty' => 0,
						'hierarchical' => 1,
						'taxonomy' => 'media_categories',
						'hide_empty' => false,
					);
					$everyday = get_categories($args);
				?>
            </div>

                <div class="category-dropdown-container">
                	<div class="item-search">
						<input type="text" class="search-query" value='<?php echo empty($value) ? "" : ucfirst($value) ?>' placeholder="Search Recipes" />
                    </div>
                    <div class="item-submit">
						<a href="#" class="submit-query">Submit</a>
						<p class="filterBy">Filter By:</p>
						<img width="43px" id="filter" src='/wp-content/themes/Avada-Child-Theme/images/filter.png'>
						<?php if(is_user_logged_in()){ ?>
	                    	<div class="recipeFavBtn">
	                    		<a href="#" class="btn get-favs"><span class="btnTxt">My Favourites </span><i class="sf-icon-star-empty"></i></a>
	                    	</div>
                    	<?php }?>
                    </div>
                    
                    <div class="clr"></div>
                    <div class="tag-filter">
                    <div class="popular">Everyone's Cooking:</div>
                    	<?php foreach($everyday as $e) { ?>
                    		<a class="recipe-tag filter-item" data-parent="recipes" data-offset="0" data-type="<?php echo $e->slug?>""><?php echo $e->name; ?></a>
                    	<?php } ?>
                    </div>
                </div>
         
       </div>
       <div class="media-listing-container media-content">
       			<div class="title-container">
       				<?php if(empty($value)){ ?>
       					<h1 class="section-header text-center">All Recipes</h1>
       				<?php }else {?>
       					<h1 class="section-header text-center"><?php echo ucfirst(str_replace("-", " ", $value)); ?> 
       						<a><img src="/wp-content/themes/Avada-Child-Theme/images/btn-x-dark@2x.png" data-offset="0" data-type="all" class="clear-search"></a>
       					</h1>
       				<?php }?>
       			</div>
                <ul class="grid-3 media-list">
                    <?php
                    	// no type specified , display all recipe
                    	if(empty($value)){
							// $args = array( 'post_type' => 'media','post_status'=>'publish', 'posts_per_page' => 9, 'offset' => 0);
							$args = array(
							  'post_type' => 'media',
							  'post_status'=>'publish', 
							  'posts_per_page' => 9, 
							  'offset' => 0,
							  'tax_query' => array(
							     array(
							       'taxonomy' => 'media_categories',
							       'field'    => 'slug',
							       'terms'    => 'sneak-peeks',
							       'operator' => 'NOT IN' 
							     )
							   )
							);
						}else{
							$args = array(
							  'post_type' => 'media',
							  'post_status'=>'publish',
							  'posts_per_page' => 9, 
							  'offset' => 0,
							  'media_categories' => $value,
							  'tax_query' => array(
							     array(
							       'taxonomy' => 'media_categories',
							       'field'    => 'slug',
							       'terms'    => 'sneak-peeks',
							       'operator' => 'NOT IN' 
							     )
							   )
							);
						}
                		$loop = new WP_Query( $args );
                		$count = 0;
						while($loop->have_posts()): $loop->the_post();
							// echo get_the_ID();
							$videos = get_post_meta(get_the_ID(), 'video');
							$images = get_post_meta(get_the_ID(), 'featimg_url');
							$icons = get_post_meta(get_the_ID(), 'icon', true);
							$icon_color = get_post_meta(get_the_ID(), 'icon-color', true);
							$default_color = get_post_meta(get_the_ID(), 'default-color', true);
						
							//check if the recipe is free
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
						endwhile;
						 if ($loop->found_posts == 0 ){
						 	echo "<li>No Result !</li>";
						 }
                    ?>
                </ul>
                <div class="load-more-container">
                    <?php if ($loop->found_posts > 9) { ?>
                        <a href="#" <?php echo (empty($value)) ? "class='btn load-more' data-type='all'" : "class='btn load-more' data-type=$value" ?> data-offset="9" >Load more - Total <?php echo $loop->found_posts; ?> Recipes</a>
                    <?php } ?>
                </div>
		</div>
    </div>
<?php get_footer(); ?>