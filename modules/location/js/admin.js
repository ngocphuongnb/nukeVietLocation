/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
 */



var ajaxBase = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=';
function remove_location(location_id)
{
	if( typeof location_id != 'undefined' )
	{
		if( confirm('Bạn có chắc chắn xóa địa điểm này?') )
		{
			$.ajax({
				type: 'POST',
				url: ajaxBase + 'remove_location',
				data: {location_id: location_id},
				dataType: 'json',
				success: function(d) {
					if(d.status == 'ok')
					{
						window.location = window.location;
					}
					else if(d.status == 'hasChild')
					{
						var hasChildMsg = '<div class="msg-content"><div class="msg-heading">Địa điểm này chứa các địa điểm trực thuộc, bạn muốn làm gì với các địa điểm trực thuộc này?</div><div class="msg-body">\
						<form id="childLocationsProccess">\
						<ul class="list-location">';
						for( _locID in d.childs )
						{
							 hasChildMsg += '<input type="hidden" name="childs[]" value="' + d.childs[_locID].location_id + '" />\
							<li>' + d.childs[_locID].location_name + '</li>';
						}
						hasChildMsg += '\
						</ul>\
						<select name="type">\
							<option value="delete">Xóa</option>\
							<option value="keep">Giữ nguyên ( parent_id = 0 )</option>\
							<option value="move">Chuyển</option>\
						</select>';
						hasChildMsg += '</div></div>';
						$('#page-msg').html(hasChildMsg);
					}
					else alert('Lỗi! Không thể xóa địa điểm này');
				}
			});
		}
	}
	else
	{
		alert('Invalid action!');
	}
}

function removeall(listIDs)
{
	if( typeof listIDs != 'undefined' )
	{
		if( confirm('Bạn có chắc chắn xóa địa điểm này?') )
		{
			$.ajax({
				type: 'POST',
				url: ajaxBase + 'remove_list',
				data: {listIDs: listIDs},
				dataType: 'json',
				success: function(d) {
					if(d.status == 'ok')
					{
						window.location = window.location;
					}
					else alert('Lỗi! Không thể xóa địa điểm này');
				}
			});
		}
	}
	else
	{
		alert('Invalid action!');
	}
}




function getUnique(obj){
   var u = {}, a = [];
   for(var i = 0, l = obj.length; i < l; ++i){
      if(u.hasOwnProperty(obj[i])) {
         continue;
      }
      a.push(obj[i]);
      u[obj[i]] = 1;
   }
   return a;
}

(function($){
	
    $.fn.InputToggle = function(options) {

        var settings = $.extend({
            childInput         	: '',
			storageVar			: 'checkedInputs',
			featureAction		: []
        }, options);
		
		var featureNums = settings.featureAction.length;
		for( var i = 0; i < featureNums; i++ )
		{
			var feature = settings.featureAction[i];
			$(feature.container).attr('onclick', feature.callback + ';return false;');
		}
		
		var toggleAllID = $(this).attr('id');
		$(this).click(function(e) {
            if( $('input#' + toggleAllID + ':checked').val() == 1 )
			{
				$(settings.childInput).each(function() {
                    $(this).attr('checked', 'checked');
                });
			}
			else
			{
				$(settings.childInput).each(function() {
                    $(this).removeAttr('checked');
                });
			}
			updateCheckedList();
        });
		$(settings.childInput).click(function(e) {
            if($('input:checkbox:checked' + settings.childInput).length === ($('input:checkbox' + settings.childInput)).length)
			{
				$('input#' + toggleAllID).attr('checked', 'checked');
			}
			else
			{
				$('input#' + toggleAllID).removeAttr('checked');
			}
			updateCheckedList();
        });
		
		function updateCheckedList()
		{
			var _checkedInputs = new Array();
			$(settings.childInput).each(function() {
				if( $(this).is(':checked') )
				{
					_checkedInputs.push( $(this).val() );
				}
			});
			window[settings.storageVar] = String( getUnique(_checkedInputs) );
		}
		return updateCheckedList();
    }
}(jQuery));