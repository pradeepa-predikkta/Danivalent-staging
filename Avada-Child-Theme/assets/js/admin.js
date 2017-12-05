jQuery(document).ready(function($) {
	/* scripts only apply to media only */
	/* prevent form submit when pressing enter */
	if($("body").hasClass("post-type-media")) {
		/*$(window).keydown(function(event){
			if(event.keyCode == 13) {
				event.preventDefault();
				return false;
			}
		});
		
		$(".search_vimeo").keyup(function(e) {
			if(e.keyCode == 13) {
				$(".button-search-vimeo").click(); 	
			}
		});
		*/
	}
	
	$(".add_video, a.show_all_videos, a.show_all_videos_extra").click(function(e) {
		e.preventDefault();
		$class = e.currentTarget.className;
		
		if($class == "show_all_videos") {
			$(".search_vimeo").val("all");
			$(".button-search-vimeo").click();
		} else if ($class == "show_all_videos_extra") {
			$(".search_vimeo_extras").val("all");
			$(".button-search-vimeo-extras").click();
		}
	});
	
	
	/* BIND VIDEO CONFIG LINKS ON LOAD */
	$(".item_remove").bind("click", function(e) {
		e.preventDefault();
		$(this).parent().parent().remove();
	});
	
	/* on load */
	$(".chk_display_thumb").bind("click", function() {
		if($(this).is(":checked")) {
			$(".chk_display_thumb").removeAttr("checked");
			$(this).attr("checked","checked");
		}
	});
	
	$(".chk_display_feature").bind("click", function() {
		if($(this).is(":checked")) {
			$(".chk_display_feature").removeAttr("checked");
			$(this).attr("checked","checked");
		}
	});
	
	$(".chk_display_home").bind("click", function() {
		if($(this).is(":checked")) {
			$(".chk_display_home").removeAttr("checked");
			$(this).attr("checked","checked");
		}
	});
	
	$(".chk_display_sneak").bind("click", function() {
		if($(this).is(":checked")) {
			$(".chk_display_sneak").removeAttr("checked");
			$(this).attr("checked","checked");
		}
	});
	
	/*
	$(".primary").each(function() {
		if($(this).attr("checked") == "checked") {
			$(".media_" + $(this).data("id")).css("outline","5px double gold");
			$(".media_" + $(this).data("id")).css("outline-offset","-5px");
		}
	});
	
	$(".primary").bind("click", function() {
		// clear all borders
		$(".gallery_image").css("outline","none");
		$("img").css("outline","none");
		
		// border this item
		$(".media_" + $(this).data("id")).css("outline","5px double gold");
		$(".media_" + $(this).data("id")).css("outline-offset","-5px");
	});
	*/
	
	$(".vimeo_item .item_options").bind("click", function(e) {
		e.preventDefault();
		if($("#video_" + $(this).data('id') + " .item_settings").css("display") == "none") {
			$("#video_" + $(this).data('id') + " .item_settings").slideDown("fast");
		} else if($("#video_" + $(this).data('id') + " .item_settings").css("display") == "block") {
			$("#video_" + $(this).data('id') + " .item_settings").slideUp("fast");	
		}
	});
	
	$(".vimeo_item .item_displays").bind("click", function(e) {
		e.preventDefault();
		if($("#video_" + $(this).data('id') + " .item_settings_display").css("display") == "none") {
			$("#video_" + $(this).data('id') + " .item_settings_display").slideDown("fast");
		} else if($("#video_" + $(this).data('id') + " .item_settings_display").css("display") == "block") {
			$("#video_" + $(this).data('id') + " .item_settings_display").slideUp("fast");	
		}
	});
	
	$(".featimg_item .item_options").bind("click", function(e) {
		e.preventDefault();
		if($("#featimg_" + $(this).data('id') + " .item_settings").css("display") == "none") {
			$("#featimg_" + $(this).data('id') + " .item_settings").slideDown("fast");
		} else if($("#featimg_" + $(this).data('id') + " .item_settings").css("display") == "block") {
			$("#featimg_" + $(this).data('id') + " .item_settings").slideUp("fast");	
		}
	});
	
	$(".featimg_item .item_displays").bind("click", function(e) {
		e.preventDefault();
		if($("#featimg_" + $(this).data('id') + " .item_settings_display").css("display") == "none") {
			$("#featimg_" + $(this).data('id') + " .item_settings_display").slideDown("fast");
		} else if($("#featimg_" + $(this).data('id') + " .item_settings_display").css("display") == "block") {
			$("#featimg_" + $(this).data('id') + " .item_settings_display").slideUp("fast");	
		}
	});
	
	$(".extra_item .item_options").bind("click", function(e) {
		e.preventDefault();
		if($("#extra_" + $(this).data('id') + " .item_settings").css("display") == "none") {
			$("#extra_" + $(this).data('id') + " .item_settings").slideDown("fast");
		} else if($("#extra_" + $(this).data('id') + " .item_settings").css("display") == "block") {
			$("#extra_" + $(this).data('id') + " .item_settings").slideUp("fast");	
		}
	});
	
	$(".extra_item .item_displays").bind("click", function(e) {
		e.preventDefault();
		if($("#extra_" + $(this).data('id') + " .item_settings_display").css("display") == "none") {
			$("#extra_" + $(this).data('id') + " .item_settings_display").slideDown("fast");
		} else if($("#extra_" + $(this).data('id') + " .item_settings_display").css("display") == "block") {
			$("#extra_" + $(this).data('id') + " .item_settings_display").slideUp("fast");	
		}
	});
	
	/* VIDEO SEARCH BAR */
	$(".button-search-vimeo, .button-search-vimeo-extras").click(function(e) {
		e.preventDefault();
		$active_element = e.currentTarget.id;
		
		if($.active == 0) {
			if($active_element == "button-search-vimeo") {
				$(".vimeo_browse_container").empty();
				$(".cssload-square").show();
				$search = $(".search_vimeo").val();
				$element = $(".vimeo_browse_container");
			}		
			if($active_element == "button-search-vimeo-extras") {
				$(".vimeo_browse_container_extra").empty();
				$(".load-extra").show();
				$search = $(".search_vimeo_extras").val();
				$element = $(".vimeo_browse_container_extra");
			}
			
			$.ajax({
				type: "POST",
				url: media_list.template_url + "/webservice.php",
				data: {
					action: "get_videos",
					s: $search
				},
				success: function(data) {
					$element.append(data);
					
					if($active_element == "button-search-vimeo") {
						/* ADD VIDEO ITEM TO THE ABOVE */
						$(".add_vimeo_item").bind("click", function(e) {
							e.preventDefault();
							
							$exists = $(".add_video_container #video_" + $(this).data('id')).length;
							
							if ($exists == 0) {
								$html = '<div class="vimeo_item" id="video_' + $(this).data('id') + '">';
									$html += '<img class="media_' + $(this).data('id') + '" src="' + $(this).data("thumbnail") + '">';
									$html += '<div class="item_title">' + $(this).data("title") + '</div>';
									$html += '<div class="item_duration">(' + $(this).data("duration") + ')</div>';
									$html += '<div class="item_config">';
										$html += '<a class="item_displays" data-id="' + $(this).data('id') + '" href="#">Display Options</a>';
										$html += '<div class="item_settings_display">';
											$html += '<div class="item_setting_display"><input type="checkbox" class="item_checkbox chk_display_thumb" data-id="' +$(this).data("id")+ '" name="video[' +$(this).data("id")+ '][article_thumb]" /> Article - Thumb  </div>';
											$html += '<div class="item_setting_display"><input type="checkbox" class="item_checkbox chk_display_feature" name="video[' +$(this).data("id")+ '][article_feature]" /> Article - Feature	</div>';
											$html += '<div class="item_setting_display"><input type="checkbox" class="item_checkbox chk_display_home" name="video[' +$(this).data("id")+ '][home_feature]" /> Home - Feature <input type="text" class="priority" name="video[' +$(this).data("id")+ '][home_priority]" />	</div>';
											$html += '<div class="item_setting_display"><input type="checkbox" class="item_checkbox chk_display_sneak" name="video[' +$(this).data("id")+ '][home_sneak_peek]" /> Home - Sneak Peek	<input type="text" class="priority" name="video[' +$(this).data("id")+ '][peek_priority]" /></div>';
										$html += '</div>';
										$html += '<a class="item_options" data-id="' + $(this).data('id') + '" href="#">Restrict Access</a>';
										$html += '<div class="item_settings">';
											$html += '<div class="item_setting"><input type="checkbox" class="item_checkbox" name="video[' + $(this).data("id") + '][is_free]" value="" /> Free	</div>';
											$html += '<div class="item_setting"><input type="checkbox" class="item_checkbox" name="video[' + $(this).data("id") + '][has_registered]" value="" /> Registered </div>';
											$html += '<div class="item_setting"><input type="checkbox" class="item_checkbox" name="video[' + $(this).data("id") + '][has_paid]" value="" /> Paid </div>';
											$html += '<input type="hidden" name="video[' + $(this).data("id") + '][id]" value="' + $(this).data('id') + '">';
											$html += '<input type="hidden" name="video[' + $(this).data("id") + '][title]" value="' + $(this).data('title') + '">';
											$html += '<input type="hidden" name="video[' + $(this).data("id") + '][duration]" value="' + $(this).data('duration') + '">';
											$html += '<input type="hidden" name="video[' + $(this).data("id") + '][thumbnail]" value="' + $(this).data('thumbnail') + '">';
											$html += '<input type="hidden" name="video[' + $(this).data("id") + '][thumbnail_2]" value="' + $(this).data('thumbnail-2') + '">';
											$html += '<input type="hidden" name="video[' + $(this).data("id") + '][thumbnail_3]" value="' + $(this).data('thumbnail-3') + '">';
											$html += '<input type="hidden" name="video[' + $(this).data("id") + '][thumbnail_4]" value="' + $(this).data('thumbnail-4') + '">';
											$html += '<input type="hidden" name="video[' + $(this).data("id") + '][link]" value="' + $(this).data('link') + '">';
										$html += '</div>';
										$html += '<a class="item_remove" href="#"><i class="fa fa-times"></i></a>';
									$html += '</div>';
								$html += '</div>';
								 
								$(".add_video_container").append($html);
								
								rebindcontrols($(this).data('id'));
							}
							else {
								alert("You have already added this video.");	
							}
						});
						
						$(".cssload-square").hide();
					}
					
					if($active_element == "button-search-vimeo-extras") {
						$(".add_vimeo_item").bind("click", function(e) {
							e.preventDefault();
							
							$exists = $(".extras_container #extras_" + $(this).data('id')).length;
							
							if ($exists == 0) {
								$html = '<div class="extra_item" id="extras_' +$(this).data('id')+ '">';
									$html += '<div href="#" class="gallery_image media_'+$(this).data('id')+'" style="background: url('+$(this).data('thumbnail')+');"></div>';
									$html += '<input type="hidden" name="extras['+$(this).data('id')+'][id]" value="'+$(this).data('id')+'" />';
									$html += '<input type="hidden" name="extras['+$(this).data('id')+'][url]" value="'+$(this).data('link')+'" />';
									$html += '<input type="hidden" name="extras['+$(this).data('id')+'][thumb]" value="'+$(this).data('thumbnail')+'" />';
									$html += '<input type="hidden" name="extras['+$(this).data('id')+'][medium]" value="'+$(this).data('thumbnail-3')+'" />';
									$html += '<input type="hidden" name="extras['+$(this).data('id')+'][full]" value="'+$(this).data('thumbnail-4')+'" />';
									$html += '<input type="hidden" name="extras['+$(this).data('id')+'][type]" value="video" />';
									
									$html += '<div class="item_config">';
										$html += 'Order: <input type="text" class="txtMediaOrdering" name="extras[' + $(this).data('id') + '][priority]" /><br>' ;
										$html += '<a class="item_options" data-id="' +$(this).data('id')+ '" href="#">Restrict Access</a>';
										$html += '<div class="item_settings">';
											$html += '<div class="item_setting"><input type="checkbox" class="item_checkbox" name="extras[' +$(this).data('id')+ '][is_free]" /> Free	</div>';
											$html += '<div class="item_setting"><input type="checkbox" class="item_checkbox" name="extras[' +$(this).data('id')+ '][has_registered]" /> Registered </div>';
											$html += '<div class="item_setting"><input type="checkbox" class="item_checkbox" name="extras[' +$(this).data('id')+ '][has_paid]" /> Paid </div>';
										$html += '</div>';
										
										$html += '<a class="item_remove" href="#"><i class="fa fa-times"></i></a>';
									$html += '</div>';
								$html += '</div>';
								 
								$(".extras_container").append($html);
								
								rebindcontrols($(this).data('id'));
							} else {
								alert("You have already added this video.");	
							}
						});
						
						$(".load-extra").hide();
						
					}
				}
			});
		}
	});
	
	/* PDF */
	$('#upload-pdf').click(function(e) {
		e.preventDefault();
		var image = wp.media({ 
			title: 'Upload PDF',
			multiple: false
		}).open()
		.on('select', function(e){
			var uploaded_pdf = image.state().get('selection').first();
			var pdf_url = uploaded_pdf.toJSON().url;
			$('#pdf_url').val(pdf_url);
			var path = pdf_url.split("/");
			var file = path[path.length-1] 
			$(".pdf_container").append('<a href="'+ pdf_url +'" target="_blank">'+file+'</a>');
			$('#upload-pdf').hide();
			$("#remove-pdf").show();
		});
	});
	
	$("#remove-pdf").click(function(e) {
		e.preventDefault();
		$('#pdf_url').val("");
		$('.pdf_container').empty();
		$(this).hide();
		$('#upload-pdf').show();
	});
	
	$("#upload-featimg, #upload-extras").click(function(e) {
		e.preventDefault();
		$active_element = e.currentTarget.id;
		
		var image = wp.media({ 
			title: 'Upload Image',
			multiple: false
		}).open()
		.on('select', function(e){
			var uploaded_img = image.state().get('selection').first();
			var id = uploaded_img.toJSON().id;
			var img_url = uploaded_img.toJSON().url;
			var thumbnail = uploaded_img.toJSON().sizes.thumbnail.url;
			var medium = uploaded_img.toJSON().sizes.medium.url;
			var full = uploaded_img.toJSON().sizes.full.url;
			
			if($active_element == "upload-featimg") {
				$('#featimg_url').val(img_url);
				$html = '<div class="featimg_item" id="featimg_' +id+ '">';
					$html += '<div class="featured_image">';
						$html += '<a href="#" class="gallery_image media_'+id+'" style="background: url('+thumbnail+');"></a>';
						$html += '<input type="hidden" name="featimg_url['+id+'][id]" value="'+id+'" />';
						$html += '<input type="hidden" name="featimg_url['+id+'][url]" value="'+img_url+'" />';
						$html += '<input type="hidden" name="featimg_url['+id+'][thumb]" value="'+thumbnail+'" />';
						$html += '<input type="hidden" name="featimg_url['+id+'][medium]" value="'+medium+'" />';
						$html += '<input type="hidden" name="featimg_url['+id+'][full]" value="'+full+'" />';
					$html += '</div>';
					$html += '<div class="item_config">';
						$html += '<a class="item_displays" data-id="' +id+ '" href="#">Display Options</a>';
						$html += '<div class="item_settings_display">';
							$html += '<div class="item_setting_display"><input type="checkbox" class="item_checkbox chk_display_thumb" data-id="' +id+ '" name="featimg_url[' +id+ '][article_thumb]" /> Article - Thumb  </div>';
							$html += '<div class="item_setting_display"><input type="checkbox" class="item_checkbox chk_display_feature" name="featimg_url[' +id+ '][article_feature]" /> Article - Feature	</div>';
							$html += '<div class="item_setting_display"><input type="checkbox" class="item_checkbox chk_display_home" name="featimg_url[' +id+ '][home_feature]" /> Home - Feature	<input type="text" class="priority" name="featimg_url[' +id+ '][home_priority]" /></div>';
							$html += '<div class="item_setting_display"><input type="checkbox" class="item_checkbox chk_display_sneak" name="featimg_url[' +id+ '][home_sneak_peek]" /> Home - Sneak Peek	<input type="text" class="priority" name="featimg_url[' +id+ '][peek_priority]" /></div>';
						$html += '</div>';
						
						$html += '<a class="item_options" data-id="' +id+ '" href="#">Restrict Access</a>';
						$html += '<div class="item_settings">';
							$html += '<div class="item_setting"><input type="checkbox" class="item_checkbox" name="featimg_url[' +id+ '][is_free]" /> Free	</div>';
							$html += '<div class="item_setting"><input type="checkbox" class="item_checkbox" name="featimg_url[' +id+ '][has_registered]" /> Registered </div>';
							$html += '<div class="item_setting"><input type="checkbox" class="item_checkbox" name="featimg_url[' +id+ '][has_paid]" /> Paid </div>';
						$html += '</div>';
						
						$html += '<a class="item_remove" href="#"><i class="fa fa-times"></i></a>';
					$html += '</div>';
				$html += '</div>';
				
				$(".featimg_container").append($html);
			}
			
			if($active_element == "upload-extras") {
				$html = '<div class="extra_item" id="extras_' +id+ '">';
					
					$html += '<div href="#" class="gallery_image media_'+id+'" style="background: url('+thumbnail+');"></div>';
					$html += '<input type="hidden" name="extras['+id+'][id]" value="'+id+'" />';
					$html += '<input type="hidden" name="extras['+id+'][url]" value="'+img_url+'" />';
					$html += '<input type="hidden" name="extras['+id+'][thumb]" value="'+thumbnail+'" />';
					$html += '<input type="hidden" name="extras['+id+'][medium]" value="'+medium+'" />';
					$html += '<input type="hidden" name="extras['+id+'][full]" value="'+full+'" />';
					$html += '<input type="hidden" name="extras['+id+'][type]" value="image" />';
					
					$html += '<div class="item_config">';
						$html += 'Order: <input type="text" class="txtMediaOrdering" name="extras[' + id + '][priority]" /><br>' ;
						$html += '<a class="item_options" data-id="' +id+ '" href="#">Restrict Access</a>';
						$html += '<div class="item_settings">';
							$html += '<div class="item_setting"><input type="checkbox" class="item_checkbox" name="extras[' +id+ '][is_free]" /> Free	</div>';
							$html += '<div class="item_setting"><input type="checkbox" class="item_checkbox" name="extras[' +id+ '][has_registered]" /> Registered </div>';
							$html += '<div class="item_setting"><input type="checkbox" class="item_checkbox" name="extras[' +id+ '][has_paid]" /> Paid </div>';
						$html += '</div>';
						
						$html += '<a class="item_remove" href="#"><i class="fa fa-times"></i></a>';
					$html += '</div>';
				$html += '</div>';
				
				$(".extras_container").append($html);
			}
			rebindcontrols(id);
		});
	});
	
	function rebindcontrols($id) {
		/* vimeo browser -> restrict access */
		$("#video_" + $id + " .item_options").bind("click", function(e) {
			e.preventDefault();
			if($("#video_" + $id + " .item_settings").css("display") == "none") {
				$("#video_" + $id + " .item_settings").slideDown("fast");
			} else if($("#video_" + $id + " .item_settings").css("display") == "block") {
				$("#video_" + $id + " .item_settings").slideUp("fast");	
			}
		});
		
		/* vimeo browser -> display options */
		$("#video_" + $id + " .item_displays").bind("click", function(e) {
			e.preventDefault();
			if($("#video_" + $id + " .item_settings_display").css("display") == "none") {
				$("#video_" + $id + " .item_settings_display").slideDown("fast");
			} else if($("#video_" + $id + " .item_settings_display").css("display") == "block") {
				$("#video_" + $id + " .item_settings_display").slideUp("fast");	
			}
		});
		
		/* image gallery -> restrict access */
		$("#featimg_" + $id + " .item_options").bind("click", function(e) {
			e.preventDefault();
			if($("#featimg_" + $id + " .item_settings").css("display") == "none") {
				$("#featimg_" + $id + " .item_settings").slideDown("fast");
			} else if($("#featimg_" + $id + " .item_settings").css("display") == "block") {
				$("#featimg_" + $id + " .item_settings").slideUp("fast");	
			}
		});
		
		/* image gallery -> display options */
		$("#featimg_" + $id + " .item_displays").bind("click", function(e) {
			e.preventDefault();
			if($("#featimg_" + $id + " .item_settings_display").css("display") == "none") {
				$("#featimg_" + $id + " .item_settings_display").slideDown("fast");
			} else if($("#featimg_" + $id + " .item_settings_display").css("display") == "block") {
				$("#featimg_" + $id + " .item_settings_display").slideUp("fast");	
			}
		});
		
		/* additional media gallery -> restrict access */
		$("#extra_" + $id + " .item_options").bind("click", function(e) {
			e.preventDefault();
			if($("#extra_" + $id + " .item_settings").css("display") == "none") {
				$("#extra_" + $id + " .item_settings").slideDown("fast");
			} else if($("#extra_" + $id + " .item_settings").css("display") == "block") {
				$("#extra_" + $id + " .item_settings").slideUp("fast");	
			}
		});
		
		/* additional media gallery -> display options */
		$("#extra_" + $id + " .item_displays").bind("click", function(e) {
			e.preventDefault();
			if($("#extra_" + $id + " .item_settings_display").css("display") == "none") {
				$("#extra_" + $id + " .item_settings_display").slideDown("fast");
			} else if($("#extra_" + $id + " .item_settings_display").css("display") == "block") {
				$("#extra_" + $id + " .item_settings_display").slideUp("fast");	
			}
		});
		
		$(".chk_display_thumb").bind("click", function() {
			if($(this).is(":checked")) {
				$(".chk_display_thumb").removeAttr("checked");
				$(this).attr("checked","checked");
			}
		});
		
		$(".chk_display_feature").bind("click", function() {
			if($(this).is(":checked")) {
				$(".chk_display_feature").removeAttr("checked");
				$(this).attr("checked","checked");
			}
		});
		
		$(".chk_display_home").bind("click", function() {
			if($(this).is(":checked")) {
				$(".chk_display_home").removeAttr("checked");
				$(this).attr("checked","checked");
			}
		});
		
		$(".chk_display_sneak").bind("click", function() {
			if($(this).is(":checked")) {
				$(".chk_display_sneak").removeAttr("checked");
				$(this).attr("checked","checked");
			}
		});
		
		/*
		$(".primary").bind("click", function() {
			// clear all borders
			$(".gallery_image").css("outline","none");
			$("img").css("outline","none");
			
			// border this item
			$(".media_" + $id).css("outline","5px double gold");
			$(".media_" + $id).css("outline-offset","-5px");
		});
		*/
		
		// all remove buttons
		$(".item_remove").bind("click", function(e) {
			e.preventDefault();
			$(this).parent().parent().remove();
		});
	}
});