jQuery(document).ready(function($) {
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
	
	
	
	
	if($(".media-latest-post").hasClass("has_offset")) {
		var offset = 1;
	} else {
		var offset = 1;	
	}
	
	$(".load-more").click(function(e){
		e.preventDefault();
		offset = offset + 9;
		if($.active == 0) {
			$.ajax({
				type: "POST",
				url: post_list.template_url + "/webservice.php",
				data: {
					action: "get_posts",
					offset: offset,
					type: $(this).data('type'),
					level: $(this).data('level')
				},
				success: function(data) {
					$(".media-list").append(data);
					$(window).resize();
					
					if(data == "" || data.split("<li>").length < 9) {
						$(".media-list").css("margin-bottom", "0px");
						$(".load-more").hide();	
					}
				}
			});
		}
	});
	
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
        post($(this).text().toLowerCase());
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
        
        post('addEventListener', 'pause');
        post('addEventListener', 'finish');
        post('addEventListener', 'playProgress');
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
});