jQuery(document).ready(function($) {
	$(window).load(function() {
		// hide subscribe
		if($(".u_type#registered").length) { 
		}
		else if($(".u_type#paid").length) {			
			$(".subscribe-banner").hide();
		}
	});
	
	$(window).resize(function() {
		
		$height_ratio = 360/640;
		$width_ratio = 640/360;
		
		if (window.innerWidth > 420) {
			$(".item-thumbnail a").each(function() {
				$width = $(this).width();
				$(this).css("height", $width * $height_ratio);
			});
		}
		
		/* Resize thumbnails */
		if(window.innerWidth > 800) {
			$(".media-list").removeClass("grid-1");
			$(".media-list").removeClass("grid-2");
			$(".media-list").addClass("grid-3");	
		} else if (window.innerWidth > 420 && window.innerWidth <= 800) {
			$(".media-list").removeClass("grid-1");
			$(".media-list").removeClass("grid-3");
			$(".media-list").removeClass("grid-4");
			$(".media-list").addClass("grid-2");
		} else if (window.innerWidth <= 420) {
			$(".media-list").removeClass("grid-2");
			$(".media-list").removeClass("grid-3");
			$(".media-list").addClass("grid-1");
		}
		
		if (window.innerWidth > 420) {
			$(".item-thumbnail").each(function() {
				$thumb_width = $(this).width();
				$(".item-thumbnail, .item-thumbnail a").css("height", $thumb_width * $height_ratio);
				$(".item-thumbnail .thumbnail").css("width", $thumb_width * $width_ratio );
			});
		}
		
		if (window.innerWidth <= 420) {
			$(".item-thumbnail a").each(function() {
				$thumbnail_width = $(this).width();
				$(".item-thumbnail a").css("height", $thumbnail_width * $height_ratio);
			});
		}
		
		$home_width = $(".feature_article_image").width();
		$(".feature_article_image, .feature_article_image a").css("height", $home_width * $height_ratio);
		
		$(".feature-header iframe").each(function() {
			$width = $(this).width();
			$(this).css("height", $width*$height_ratio);
		});
		
		/* resize feature item */
		$(".media-latest-post").each(function() {
			$feature_width = $(this).width();
			$(".media-latest-post, .media-latest-post .link-feature").css("height", $feature_width * $height_ratio);
		});
	}).resize();
	
	$(".category-dropdown .first-item").click(function(e) {
		e.preventDefault();
		if ($(".category-dropdown .category-dropdown-list").is(":visible")) {
			$(".category-dropdown .category-dropdown-list").slideUp("fast");
			$(".category-dropdown a.first-item i.fa").removeClass("rotate");
		} else {
			$(".category-dropdown .category-dropdown-list").slideDown("fast");
			$(".category-dropdown a.first-item i.fa").addClass("rotate");
		}
	});
	
	//=========================================================
	function bindClick(){
		$(".load-more").click(function(e){
			e.preventDefault();
			var offset = $(this).data('offset');
			var type = $(this).data('type');
			$.ajax({
				type: "POST",
				url: post_list.template_url + "/webservice.php",
				data: {
					action: "get_posts",
					offset: offset,
					type: type,
				},
				beforeSend:function(){
					$(".load-more").remove();
					$(".load-more-container").append("<div class='loader'></div>");
				},
				success: function(data) {
					$(".loader").remove();
					$(".media-list").append(data);
					$(window).resize();

					if(data == "" || data.split("<li>").length < 9) {
						$(".media-list").css("margin-bottom", "0px");
					}else{
						var new_offset = offset + 9 ;
						$(".load-more-container").append('<a href="#" class="btn load-more" data-offset =\"'+new_offset+'\" data-type=\"'+type+'\">Load more</a>');
						bindClick()
					}
				}
			});
		});		
	}
	function bindScroll(){
     	if($(window).scrollTop() + $(window).height() > $(document).height() - 400) {
         $(".load-more").click();
         $(".load-more-after-filter").click();
     	}
	 }
	bindClick();
	bindFilterClick();
  	$(window).scroll(bindScroll);

  	//=======================================================
	$(".play_video").click(function(e) {
		e.preventDefault();
		var videoData = [
		{
			'id': $(this).attr('id'),
			'videoURL': $(this).attr('href')
		}];
		
		$.getJSON('https://vimeo.com/api/oembed.json?url=' + encodeURIComponent(videoData[0]['videoURL']) + '&api=1&player_id='+ videoData[0]['id'] +'&callback=?&autoplay=true', function(data){
			$('.video-clip').html(data.html); //puts an iframe embed from vimeo's json
			$('.video-clip').show();
			
			$(document).bind("keyup", function(e) {
				 if (e.keyCode == 27) { // escape key maps to keycode `27`
					// <DO YOUR WORK HERE>
					$('.video-clip').click();
				}
			});
			
			$('.video-clip').click(function(e) {
				$('.video-clip').html("");
				$('.video-clip').hide();
			});
			
			/*
			$('.video-clip iframe').load(function(){
				player = document.querySelectorAll('iframe')[0];
				$('.video-clip iframe').attr('id', videoData[0]['id']);
				$f(player).addEvent('ready', function(id){
					var vimeoVideo = $f(id);
					console.log('success');
				});
			});*/
			
		});
	});
	
	$(".larger").click(function() {
		$url = $(this).data('url');
		window.location = $url;	
	});
	
	var player = $('iframe');
    var playerOrigin = '*';
    var status = $('.status');

    // Listen for messages from the player
    if (window.addEventListener) {
        window.addEventListener('message', onMessageReceived, false);
    }
    else {
        window.attachEvent('onmessage', onMessageReceived, false);
    }
	
	// Detect latest posts
	if($(".related-posts ul li").length > 0) {
		$(".related-posts .content-header").show();
	}
	
	if($(".media-content ul li").length > 0) {
		$(".media-content .content-header").show();
	}

    // Handle messages received from the player
    function onMessageReceived(event) {
        // Handle messages from the vimeo player only
        if (!(/^https?:\/\/player.vimeo.com/).test(event.origin)) {
            return false;
        }
        
        if (playerOrigin === '*') {
            playerOrigin = event.origin;
        }
        
        var data = JSON.parse(event.data);
        
        switch (data.event) {
            case 'ready':
                onReady();
                break;
               
            case 'playProgress':
                onPlayProgress(data.data);
                break;
                
            case 'pause':
                onPause();
                break;
               
            case 'finish':
                onFinish();
                break;
        }
    }

    // Call the API when a button is pressed
    $('button').on('click', function() {
		if($("body").hasClass("page-template-recipes") || $("body").hasClass("page-template-chef")) {
        	post($(this).text().toLowerCase());
		}
    });

    // Helper function for sending a message to the player
    function post(action, value) {
        var data = {
          method: action
        };
        
        if (value) {
            data.value = value;
        }
        
        var message = JSON.stringify(data);
        player[0].contentWindow.postMessage(data, playerOrigin);
    }

    function onReady() {
        status.text('ready');
        
		if($("body").hasClass("page-template-recipes") || $("body").hasClass("page-template-chef")) {
        	post('addEventListener', 'pause');
        	post('addEventListener', 'finish');
        	post('addEventListener', 'playProgress');
		}
    }

    function onPause() {
        status.text('paused');
    }

    function onFinish() {
        status.text('finished');
    }

    function onPlayProgress(data) {
        status.text(data.seconds + 's played');
    }
	
	$('ul.avada-myaccount-nav').append('<li class="btn-subscribe"><a href="/subscription/" class="btn">Subscribe</a></li>');
	$ ('.btn-subscribe a').bind ('click',function(){
		window.location = "/subscription/";
	});
	
	$(".view-more").click(function(e) {
		e.preventDefault();
	});
	
	/*
	$("#reg_password, #account_password").bind('keydown change', function() {
		if($(".woocommerce-password-strength").hasClass("bad") || $(".woocommerce-password-strength").hasClass("good") || $(".woocommerce-password-strength").hasClass("strong")) {
			$(this).addClass("input-valid");	
			$(this).removeClass("input-invalid");
		} else if ($(".woocommerce-password-strength").hasClass("short")) {
			$(this).addClass("input-invalid");
			$(this).removeClass("input-valid");
		}
	});
	*/
	var filter_offset = 0;
	
	$(".category-dropdown-list ul li a").click(function(e) {
		e.preventDefault();
		
		// remove any existing selected class
		$(".category-dropdown-list ul li a").each(function() {
			$(this).removeClass("selected");
		});
		
		// add class to reference for submit
		$(this).addClass("selected");
		
		// reverse toggle / close dropdown
		$(".category-dropdown .first-item").click();
		
		// populate drop down with description of text
		$(".category-dropdown .first-item").html($(this).text() + '&nbsp;&nbsp;<i class="fa fa-chevron-down"></i>');
		
		filter_offset = 0;
	});
	
	$(".get-favs").on("click",function(){
		$.ajax({
			type: "POST",
			url: post_list.template_url + "/webservice.php",
			data: {
				action: "get_favs",
			},
			beforeSend:function(){
				$(".media-latest-post").remove();
				$(".load-more-container").remove();
				$(".media-list").remove();
				$(".media-content").html("<div class='loader'></div>");
			},
			success: function(data) {
				$(".media-content").html('<div class="title-container"><h1 class="section-header text-center">My Favourite Recipes<a><img src="/wp-content/themes/Avada-Child-Theme/images/btn-x-dark@2x.png" data-offset="0" data-type="all" class="clear-search"></a></h1></div>');
				$(".media-content").append('<ul class="grid-3 media-list">'+data+'</ul>');
				
				if(data == ""){
					$(".media-content").html("<span>No result!</span>");
				}
				// else if(data.split("<li>").length < 9) {
				// 	$(".media-list").css("margin-bottom", "0px");
				// }else{
				// 	var new_offset = offset + 9 ;
				// 	$(".media-content").append('<div class="load-more-container"><a href="#" class="btn load-more" data-offset =\"'+new_offset+'\" data-type=\"'+type+'\">Load more</a></div>');
				// 	bindClick()
				// }
			}
		});
	});

	$(".filter-item").on("click",function(){
		$('html, body').animate({scrollTop : 0},800);
  		var offset = $(this).data('offset');
		var type = $(this).data('type');
		var parent = $(this).data('parent');
		var name = $(this).text();
		$.ajax({
			type: "POST",
			url: post_list.template_url + "/webservice.php",
			data: {
				action: "get_posts",
				offset: offset,
				type: type,
			},
			beforeSend:function(){
				$(".media-latest-post").remove();
				$(".load-more-container").remove();
				$(".media-list").remove();
				$(".media-content").html("<div class='loader'></div>");
			},
			success: function(data) {

				$(".media-content").html('<div class="title-container"><h1 class="section-header text-center">'+parent+' - '+name+'<a><img src="/wp-content/themes/Avada-Child-Theme/images/btn-x-dark@2x.png" data-offset="0" data-type="all" class="clear-search"></a></h1></div>');
				$(".media-content").append('<ul class="grid-3 media-list">'+data+'</ul>');
				
				if(data == ""){
					$(".media-content").html("<span>No result!</span>");
				}
				else if(data.split("<li>").length < 9) {
					$(".media-list").css("margin-bottom", "0px");
				}else{
					var new_offset = offset + 9 ;
					$(".media-content").append('<div class="load-more-container"><a href="#" class="btn load-more" data-offset =\"'+new_offset+'\" data-type=\"'+type+'\">Load more</a></div>');
					bindClick()
				}
			}
		});
  	});

 
	$(document).on("click",".clear-search",function(e){
		var offset = $(this).data('offset');
		var type = $(this).data('type');
		$.ajax({
			type: "POST",
			url: post_list.template_url + "/webservice.php",
			data: {
				action: "get_posts",
				offset: offset,
				type: type,
			},
			beforeSend:function(){
				$(".media-latest-post").remove();
				$(".load-more-container").remove();
				$(".media-list").remove();
				$(".media-content").html("<div class='loader'></div>");
			},
			success: function(data) {

				$(".media-content").html('<div class="title-container"><h1 class="section-header text-center">All Recipes</h1></div>');
				$(".media-content").append('<ul class="grid-3 media-list">'+data+'</ul>');
				
				if(data == ""){
					$(".media-content").html("<span>No result!</span>");
				}
				else if(data.split("<li>").length < 9) {
					$(".media-list").css("margin-bottom", "0px");
				}else{
					var new_offset = offset + 9 ;
					$(".media-content").append('<div class="load-more-container"><a href="#" class="btn load-more" data-offset =\"'+new_offset+'\" data-type=\"'+type+'\">Load more</a></div>');
					bindClick()
				}
			}
		});
	})
  	

	//=========================================================
	$(".search-query").on('keyup', function (e) {
	    if(e.which == 13) {
	        $(".submit-query").click();
	    }
	});
	function bindFilterClick(){
		$(".load-more-after-filter").bind("click", function(e){
			e.preventDefault();	
			var offset = $(".load-more-after-filter").data('offset');
			$.ajax({
				type: "POST",
				url: post_list.template_url + "/webservice.php",
				data: {
					action: "get_query",
					squery: $(".search-query").val(),
					filter_offset: offset,
				},
				beforeSend:function(){
					$(".load-more-after-filter").remove();
					$(".load-more-container").append("<div class='loader'></div>");
				},
				success: function(data) {
					$(".loader").remove();
					$(".media-list").append(data);
					if(data == "" || data.split("<li>").length < 9) {
						$(".media-list").css("margin-bottom", "0px");
					}else{
						var new_offset = offset + 9 ;
						$(".load-more-container").append('<a href="#" class="btn load-more-after-filter" data-offset =\"'+new_offset+'\" >Load more</a>');
						bindFilterClick()
					}
				}
			});	
		});
	}
	function submit(e){
		e.preventDefault();
		
		$.ajax({
			type: "POST",
			url: post_list.template_url + "/webservice.php",
			data: {
				action: "get_query",
				squery: $(".search-query").val(),
				filter_offset: filter_offset
			},
			beforeSend:function(){
				$(".load-more-container").remove();
				$(".media-list").remove();
				$(".media-content").html("<div class='loader'></div>");
			},
			success: function(data) {
				$(".media-content").html('<div class="title-container"><h1 class="section-header text-center">Search Result - '+$(".search-query").val()+'<a><img src="/wp-content/themes/Avada-Child-Theme/images/btn-x-dark@2x.png" data-offset="0" data-type="all" class="clear-search"></a></h1></div>');
				$(".media-content").append('<ul class="grid-3 media-list">'+data+'</ul>');
				
				if(data == "" ){
					$(".media-content").html("<span>No result!</span>");
				}
				else if( data.split("<li>").length < 9) {
					$(".media-list").css("margin-bottom", "0px");
				}else{
					$(".media-content").append('<div class="load-more-container"><a href="#" class="btn load-more-after-filter" data-offset ="10" >Load more</a></div>');
					bindFilterClick();
				}
			}
		});
	}

	$(".submit-query").click(function(e) {
		submit(e);
	});
	
	$(".search-query").keyup(function() {
		filter_offset = 0;
	});
	//===========================================



	$('#filter').on("click",function(){
    	$(".slide-out-sidebar").toggleClass('open');
    });

	$('.close').on("click",function(){
		$(".slide-out-sidebar").removeClass('open');
	});
    $('.pointer').on("click",function(){
  		if($(this).parent().hasClass('closed-level-1')){
  			$(this).parent('li').removeClass().addClass('open-level-1');
  			$(this).siblings('ul').show();
  		}else{
  			$(this).parent('li').removeClass().addClass('closed-level-1');
  			$(this).siblings('ul').hide();
  		}
    })
});