$(window).load(function(){
	var scheme = $('img.image-scheme');
	var w = scheme.width();
	var h = scheme.height();
	var ip = scheme.position();
	if(scheme.length){
		$.each($('.scheme-block .area'), function (i, v){
			var area = $(this);
			var ss = area.data('area');
			var show_as_img = parseInt(area.data('show-as-img'));
			if(ss){
				var s = ss.split('|');
				area.css({left: s[0] * w + ip.left, top: s[1] * h + ip.top, width: s[2] * w, height: s[3] * h});
				area.show();
				area.on('click', function(){
					if(!show_as_img){
						$('div.panellum-block').html('<iframe  class="js-pannellum" title="pannellum panorama viewer" width="460" height="380" webkitAllowFullScreen mozallowfullscreen allowFullScreen style="border-style:none;"  accesskey="" src="' + 
						dir_js + 'panellum/pannellum.htm?panorama=' + area.data('panorama') + '&title='+ area.data('title') + '&autoLoad=true"></iframe>');
					}
					else{
						$('div.panellum-block').html('<a href="' + area.data('panorama-light') + '" class=" fb-gallery"><img src="' + area.data('panorama-light')  + '" class="js-pannellum" alt="' + area.data('title') + '" width = "460"/></a>');
					}
						
					$('.js-point-info').html((area.find('.js-descr').html()));
				});
			}
		});
	}
	
	
});