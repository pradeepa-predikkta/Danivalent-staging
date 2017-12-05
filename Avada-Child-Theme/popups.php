<style>
	.beforeUnloadBox, .freeRecipeBox{
		background-color: #fff;
		border-top: solid 5px #00adef;
		left: 50%;
	    padding: 20px;
	    position: fixed;
	    max-width: 48em;
	    text-align: center;
	    top: 50%;
	    z-index: 999;
	    transform: translate(-50%,-50%);
	    -o-transform: translate(-50%,-50%);
	    -ms-transform: translate(-50%,-50%);
	    -moz-transform: translate(-50%,-50%);
	    -webkit-transform: translate(-50%,-50%);
	}
	.beforeUnloadBox h3, .freeRecipeBox h3{
		font-size: 20px;
		text-transform: uppercase;
	}
	.freeRecipeBoxContent, .beforeUnloadBox{
		text-align: center;
	}
	.beforeUnloadBoxContent{
		padding: 30px 30px;
		padding-bottom: 5px;
	}
	.coverPage{
	    position:fixed;
	    top:0;
	    left:0;
	    background:rgba(0,0,0,0.8);
	    z-index:5;
	    width:100%;
	    height:100%;
	    display:none;
	    z-index: 998;
	}
	.bannerImg{
		margin-bottom: 20px;
	}
	.closeBeforeUnload, .closefreeRecipeBox{
		color: #000 !important;
	}
	.closeBeforeUnloadText, .closefreeRecipeBoxText{
		margin-left: 10px;
		font-size: 13px;
		float: none;
	}
</style>


<!-- 4th Free Recipe -->
<div class="freeRecipeBox hidden-xs" style="display: none;">
	<button type="button" class="close closefreeRecipeBox" aria-label="Close"><span aria-hidden="true">×</span></button>
	<div class="freeRecipeBoxContent">
		<img src="/wp-content/themes/Avada-Child-Theme/images/banner.jpg" class="img-responsive bannerImg" alt="chciken egg cheesecake tart ribs Dani on set montage">
		<h3>Enjoying the content so far?</h3>
		<h4>There's so much more on the inside.</h4>
		<p>Try a month for $10</p>
		<a class="fusion-button button-flat fusion-button-round button-large button-default button-2" href="#"><span class="fusion-button-text addtocart">Yes, please</span></a>
		<button type="button" class="close closefreeRecipeBox closefreeRecipeBoxText" aria-label="Close"><span aria-hidden="true">No Thanks</span></button>
	</div>
</div>


<!-- Intent to exit -->
<div class="beforeUnloadBox hidden-xs" style="display: none;">
	<button type="button" class="close closeBeforeUnload" aria-label="Close"><span aria-hidden="true">×</span></button>
	<div class="beforeUnloadBoxContent">
		<img src="/wp-content/themes/Avada-Child-Theme/images/banner.jpg" class="img-responsive bannerImg" alt="chciken egg cheesecake tart ribs Dani on set montage">
		<h3>Wait! Before you go...</h3>
		<h4>There's so much more on the inside.</h4>
		<p>Try a month for $10</p>
		<a class="fusion-button button-flat fusion-button-round button-large button-default button-2" href="#"><span class="fusion-button-text addtocart">Yes, please</span></a>
		<button type="button" class="close closeBeforeUnload closeBeforeUnloadText" aria-label="Close"><span aria-hidden="true">No Thanks</span></button>
	</div>
</div>
<div class="coverPage hidden-xs"></div>


<script type="text/javascript">
	function getCookieVal (offset) {
        var endstr = document.cookie.indexOf (";", offset);
        if (endstr == -1)
            endstr = document.cookie.length;
            return unescape(document.cookie.substring(offset, endstr));
    }
    function GetCookie (name) {
        var arg = name + "=";
        var alen = arg.length;
        var clen = document.cookie.length;
        var i = 0;
        while (i < clen) {
            var j = i + alen;
            if (document.cookie.substring(i, j) == arg)
            return getCookieVal (j);
            i = document.cookie.indexOf(" ", i) + 1;
            if (i == 0)
            break;
        }
        return null;
    }
    function SetCookie (name, value) {
        var argv = SetCookie.arguments;
        var argc = SetCookie.arguments.length;
        var expires = (2 < argc) ? argv[2] : null;
        var path = (3 < argc) ? argv[3] : null;
        var domain = (4 < argc) ? argv[4] : null;
        var secure = (5 < argc) ? argv[5] : false;
        document.cookie = name + "=" + escape (value) +
        ((expires == null) ? "" : ("; expires=" + expires.toGMTString())) +
        ((path == null) ? "" : ("; path=" + path)) +
        ((domain == null) ? "" : ("; domain=" + domain)) +
        ((secure == true) ? "; secure" : "");
    }

    function ResetCounts() {
        var expdate = new Date();
        expdate.setTime(expdate.getTime() +  (24 * 60 * 60 * 1000 * 5));
        visit = 0;
        SetCookie("visit", visit, expdate , "/", null, false);
        history.go(0);
    }

    function checkVisitFreeRecipesTime(isFreeRecipe) {
        var expdate = new Date();
        var visit;
        // 24*60*60*1000 = 1 day , adjust accordingly
        expdate.setTime(expdate.getTime() +  (24 * 60 * 60 * 1000 * 5));
        if(!(visit = GetCookie("visit")))
            visit = 0;
            if(isFreeRecipe){
                visit++;
                SetCookie("visit", visit, expdate, "/", null, false);
            }
        return visit;
    }


	jQuery(document).ready(function($) {
		// Session
		function session() {
		    if (document.cookie.indexOf("visited") >= 0) {
		    } else {
		    	firstVisit();
		        document.cookie = "visited";
		    }
		}

		// if first visit
		function firstVisit() {
			// Load Before Unload
			$(document).one('mouseleave', leaveFromTop);
			function leaveFromTop(e){
				// console.log(e.clientY);
		    	if(e.clientY < 0){
		    		$('.coverPage').fadeIn();
		      		$('.beforeUnloadBox').fadeIn();
		    	}
			}

		}

		// TODO: check if viewed recipe pages 4 times
		// TODO: check if not closed this popup already
		// $('.coverPage').fadeIn();
		// $('.freeRecipeBox').fadeIn();

		// Close Before Unload
		$('.coverPage, .closeBeforeUnload').click(function(){
			$('.coverPage').fadeOut();
	      	$('.beforeUnloadBox').fadeOut();
		});

		// Close Free Recipe Box
		$('coverPage, .closefreeRecipeBox').click(function(){
			$('.coverPage').fadeOut();
			$('.freeRecipeBox').fadeOut();
			var expdate = new Date();
	        // 24*60*60*1000 = 1 day , adjust accordingly
	        expdate.setTime(expdate.getTime() +  (24 * 60 * 60 * 1000 * 365));
			SetCookie("close", 1, expdate, "/", null, false);
		});

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

		session();

		});
</script>