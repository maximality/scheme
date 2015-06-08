

function redraw_sel_area(img, ss){
		var sel = img.closest('.floor-scheme-img').find('.floor-scheme-img-sel-area');
		
		if(!ss)
			sel.hide();
		else{
			s = ss.split("|");
			if(s.length == 4){
				var ip = img.position();
				var width = img.width();
				var height = img.height();
				if(!ip.top){
					width = img.data('width2');
					height = img.data('height2');
					ip.top = 21;
					ip.left = 0;
				}
				
				sel.css({left: s[0] * width + ip.left, top: s[1] * height + ip.top, width: s[2] * width, height: s[3] * height});
				sel.show();
			}
		}
}

$(function(){
	$('.js-add-floor').on('click', function(e){
            e.preventDefault();
            var li =  $('ul.bookmarks li:last').clone();
            var tab = $('div.js-floor-content:last').clone(true);
            $('ul.bookmarks li:last').before(li);
            $('div.js-floor-content:last').before(tab);
            li.show();
			var floor_number = parseInt($('div.js-floor-count').html()) + 1;
			
			$('div.js-floor-count').html(floor_number);
            li.find('a').html('Этаж ' + floor_number);
			li.find('a').attr('data-name', 'floor_' + floor_number);
			tab.find('input[name="floor_title[0]"]').val('Этаж ' + floor_number);
			tab.find('input.js-floor-id').val(floor_number);
            sD.enableTabs();
            li.find('a').trigger('click');
			
			//name changing
			tab.find('input[name="floor_title[0]"]').attr('name', 'floor_title[' + floor_number + ']');
			tab.find('input[name="floor_points[0][]"]').attr('name', 'floor_points[' + floor_number + '][]');
			tab.find('input[name="palennum_tour_img[0][]"]').attr('name', 'palennum_tour_img[' + floor_number + '][]');
			tab.find('input[name="palennum_tour_file[0][]"]').attr('name', 'palennum_tour_file[' + floor_number + '][]');
			tab.find('textarea[name="point_description[0][]"]').attr('name', 'point_description[' + floor_number + '][]');
			tab.find('input[name="floor_scheme_file[0]"]').attr('name', 'floor_scheme_file[' + floor_number + ']');
			tab.find('input[name="floor_scheme_img[0]"]').attr('name', 'floor_scheme_img[' + floor_number + ']');
			tab.find('input[name="floor_point_area[0][]"]').attr('name', 'floor_point_area[' + floor_number + '][]');
			tab.find('input[name="selected_point[0]"]').attr('name', 'selected_point[' + floor_number + ']');
    });
	
	$('.js-delete-floor').on('click', function(){
		$('ul.bookmarks li.active').remove();
		$(this).closest('div.tab-content').remove();
		sD.enableTabs();
		$('ul.bookmarks li').eq(0).trigger('click');
		$('.js-auto-saving').trigger('click');
	});
	
	$('input.js-floor-title').on('keyup', function(){
		$('ul.bookmarks li.active').find('a').html($(this).val() == ""?'Этаж без имени':$(this).val());
	});
	
	
	$('.floor-points a.js-add-point').on('click', function(e) {
		var tr = $(this).closest('.floor-points').find('table').find('tr:last');
		var new_point = tr.clone(true);
		tr.before(new_point);
		new_point.show();
		e.preventDefault();
		
		new_point.find('input.js-selected-point').attr('checked', 'checked');
		new_point.css({'background-color': '#F0F0F2'});
		$.each(new_point.siblings('tr'), function(i, v){
			//$(this).find('input.js-selected-point').attr('checked', '');
			$(this).css({'background-color': 'white'});
		});
		var num = new_point.siblings('tr').length;
		new_point.find('input.js-floor-point').val('Комната ' + num);
		
		var last_block = $(this).closest('.js-floor-content').find('.floor-point-info .js-point-info-item:last');
		var new_point_block = last_block.clone(true);
		last_block.before(new_point_block);
		
		last_block.closest('.js-floor-content').find('.floor-point-info .js-point-info-item').hide();
		new_point_block.show();
		
		new_point_block.find('.point-info-title').html('Комната ' + num);
		var file = new_point_block.closest('.js-floor-content').find('.floor-point-info').find('.js-point-info-item').eq(num-1).find('input[type=file]');
		
		file.attr('name', 'palennum_tour_file[' + new_point_block.closest('.js-floor-content').find('.js-floor-id').val() + '][' + (num - 1) + ']');
		var floor_content = $(this).closest('.js-floor-content');
		var inp = floor_content.find('.floor-point-info').find('.js-point-info-item').eq(num-1).find('input.js-point-area-val');
		redraw_sel_area(floor_content.find('img.scheme-image'), inp.val());
	});
	$('.floor-points').find('input.js-selected-point').on('change', function(){
		if($(this).prop('checked')){
			$.each($('.floor-points').find('input.js-selected-point'), function(i, v){
				if(!$(this).prop('checked')){
					$(this).closest('tr').css({'background-color': 'white'});
				}
			});
			$(this).closest('tr').css({'background-color': '#F0F0F2'});
			
			$(this).closest('.js-floor-content').find('.floor-point-info .js-point-info-item').hide();
			var num = $(this).closest('tr').index();
			$(this).closest('.js-floor-content').find('.floor-point-info .js-point-info-item').eq(num).show();
			
			var floor_content = $(this).closest('.js-floor-content');
			var inp = floor_content.find('.floor-point-info').find('.js-point-info-item').eq(num).find('input.js-point-area-val');
			redraw_sel_area(floor_content.find('img.scheme-image'), inp.val());
		}
	});
	$('.floor-points .js-delete-point').on('click', function(){
		var num = $(this).closest('tr').index();
		var len = $(this).closest('tr').siblings('tr').length - 1;
		$(this).closest('.js-floor-content').find('.floor-point-info .js-point-info-item').eq(num).remove();
		$(this).closest('.js-floor-content').find('.floor-scheme-img-sel-area').hide();
	});
	$('.floor-points .js-floor-point').on('keyup', function(){
		var num = $(this).closest('tr').index();
		$(this).closest('.js-floor-content').find('.floor-point-info .js-point-info-item').eq(num).find('.point-info-title').html($(this).val() != ""?$(this).val():'Точка без названия');
	});
	
	$('a.js-open-image-link').on('click', function(e){
		e.preventDefault();
		var img = $(this).closest('.floor-scheme').find('img.scheme-image');
		open_image(img);
	})
	
	
	$('.js-change-point-area').on('click', function(){
		var img = $(this).closest('.js-floor-content').find('.floor-scheme img.scheme-image');
		
		if(img.length){
			open_image(img);
			var floor_id = img.closest('.js-floor-content').find('.js-floor-id').val();
			var point_id = $(this).closest('.js-point-info-item').index();
			//var ss = $(this).closest('.js-point-info-item').find('input.js-point-area-val').val();
			selectedArea = $('div.image-container').find('img.js-i').imgAreaSelect({
				handles: true,
				instance: true,
				custom: [floor_id, point_id],
				enable: true
			});
		};
	});
	
	$('.js-clear-area').on('click', function(){
		$(this).closest('.js-point-info-item').find('input.js-point-area-val').val("");
		redraw_sel_area($(this).closest('.js-floor-content').find('img.scheme-image'), "");
	});
	
	function open_image(img){
	    var left = ($(document).width() - img.data('width')) / 2;
		var top = ($(document).height() - img.data('height')) / 2;
		if(top < 0)
			top = 0;
		if(left < 0)
			left = 0;
		$('div.image-container').css({width: img.data('width')+'px', height: img.data('height')+'px', top: top, left: left});
		$('div.image-container').find('img.js-i').attr('src', img.data('name'));
		$('div.image-container').show();
	}
});
