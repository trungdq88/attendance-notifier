/**
 * Created with JetBrains PhpStorm.
 * User: Nhan
 * Date: 9/11/12
 * Time: 9:35 PM
 * To change this template use File | Settings | File Templates.
 */
$(document).ready(function() {
    //parse jSon, append data to index.php
    var filterItem = new Array();
    $.getJSON('js/subjects.json',function(data) {
        var $html = '';
        $.each(data, function(index, category) {
            $html += '<div class="cat-box '+ category.category_name +'"><h1>'+ category.category_name +'</h1><ul>';
            //get category name to make a item for filter panel
            filterItem.push(category.category_name+'');
            $.each(category.subjects, function(index, subject) {
                $html += '<li title="Trong mục '+ category.category_name +'" id="'+ subject.id +'">'+ subject.name +'<span class="add">Chọn</span></li>';
            });
            $html += '</ul></div><!--end cat-->';
        });
        $('#select-item').append($html);

        //add filter panel
		var $filter_html="<span class='filter-title'>Lọc Môn Học: </span><select name='select-subject' id='select-subject'><option>All</option>";
        $.each(filterItem, function(index, value) {
			$filter_html += "<option>" + value + "</option>";
        });
		$filter_html += "</select>";
        $('#filter-category').html($filter_html);

        //trigger filter action by click!
		$('#select-subject').change(function() {
            var text = $(this).val();
            $('.cat-box').css('display','block');
            if(text != 'All') {
                $('.cat-box').not(function() {
                    return $(this).find('h1').text() == text;
                }).hide();
            }
            return false;
        });
        //select subject affect
        var count = 0;//count numbers of subject selected
        $('.add').live('click',function() {
            $(this).parent().appendTo('#select-item-content ul').children('.add').text('Bỏ').removeClass('add').addClass('remove');
            count++;
            checkAdded(count);
        });

        $('.remove').live('click',function() {
           var parent = $(this).parent().attr('title').split(' ')[2];
           var $elem = $(this).removeClass('remove').addClass('add').text('Chọn').parent();
           $('.'+parent+' ul').append($elem);
           count--;
           checkAdded(count);
        });

    });

    function checkAdded(count) {
        if(count == 0) {
            $('#select-item-content p').html("Chưa có môn nào được chọn").removeClass('not-empty').addClass('empty');
        } else {
            $('#select-item-content p').html("Bạn đã chọn "+ count +" môn").removeClass('empty').addClass('not-empty');
        }
    }
});
