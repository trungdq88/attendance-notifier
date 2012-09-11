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
            $html += '<div class="cat-box"><h1>'+ category.category_name +'</h1>';
            //get category name to make a item for filter panel
            filterItem.push(category.category_name+'');
            $.each(category.subjects, function(index, subject) {
                $html += '<p><input type="checkbox" name="" value="'+ subject.id +'" id="'+ subject.id +'" />';
                $html += '<label for="'+ subject.id +'">'+ subject.name +'</label></p>';
            });
            $html += '</div><!--end cat-->';
        });
        $('#select-item form').html($html);

        //add filter panel
        var $filter_html="<h3 class='filter-title'>Lọc Môn Học</h3><a href='#'>All</a>";
        $.each(filterItem, function(index, value) {
            $filter_html += '<a href="#">'+ value +'</a>';
        });
        $('#filter-category').html($filter_html);

        //trigger filter action by click!
        $('#filter-category a').click(function() {
            var text = $(this).text();
            $('.cat-box').css('display','block');
            if(text != 'All') {
                $('.cat-box').not(function() {
                    return $(this).find('h1').text() == text;
                }).hide();
            }
            return false;
        })

    });


});
