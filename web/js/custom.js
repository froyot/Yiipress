$(document).ready(function(){


  $(".submenu > a").click(function(e) {
    e.preventDefault();
    var $li = $(this).parent("li");
    var $ul = $(this).next("ul");

    if($li.hasClass("open")) {
      $ul.slideUp(350);
      $li.removeClass("open");
    } else {
      $(".nav > li > ul").slideUp(350);
      $(".nav > li").removeClass("open");
      $ul.slideDown(350);
      $li.addClass("open");
    }
  });

$('.search-button').click(function(){
  event.type === 'keydown'
  event.keyCode = 13;
});

if($('#show_add_category').length >0 )
    $('#show_add_category').click(function()
    {
        if( $('#add_category_content').hasClass('show') )
        {
            $('#add_category_content').removeClass('show');
            $('#add_category_content').addClass('hidden');
            $('#add_category_content input[name="category"]').val('');
        }
        else
        {
            $('#add_category_content').removeClass('hidden');
            $('#add_category_content').addClass('show');
        }
    });

});

/**********************************新增文章*****************************/
//添加文章中添加分类
$('#add_category_content').on('click','#add_category',function(){

  var name = $('#add_category_content input[name="category"]').val();
  var pid = $('#categorys').val();
  var data = {name:name,pid:pid};
  $.post(addCategoryUrl,data,function(data){
      //添加成功之后，刷新分类数据
      if(data.status == false)
      {
        alert(data.message);return false;
      }
      $('#add_category_content input[name="category"]').val('');
      //更新分类数据
      data = data.data;
      var select_html = "";
      var check_html = "";
      if( data )
      {
        select_html += '<option value="0">无</option>';
      }
      $.each(data,function(index,item){
        select_html += '<option value="'+item['term_taxonomy_id']+'">'+item['name']+'</option>';
        check_html += '<div class="checkbox"><label>'+
                      '<input type="checkbox" value="'+item['term_taxonomy_id']+'">'+item['name']+'</laebl></div>';
      });
      if( check_html.length > 0 )
      {
        $('#category').html(select_html);
        $('#category_check_content').html(check_html);
      }
  });
});


$('#collapseTags').on('click','#add_tag_button',function(){
  //点击，添加tag,只是前端添加，不上传到服务器

  var tags = [];
  var add_tag = $('#collapseTags input[name="tag"]').val();
  var isAdded = false;
  //判断和现有的tag是否重复
  $.each($('.tag-item'),function(index,item){

    if( add_tag == $(item).find("label").text() )
    {
      isAdded = true;
      return false;
    }
  });

  if( isAdded == false )
  {
    //没有重复，添加
    var html ="<span class='tag-item'><a class='delete-tag'>X</a><label>"+add_tag+"</label></span>";
    $('#tag_check').append(html);
    //删除输入框
    $('#collapseTags input[name="tag"]').val('');
  }

});

$('#collapseTags').on('click','.delete-tag',function(){
  $(this).parent().remove();
});

$('#publish').click(function(){
  //判断数据是否完整
  var formContent = $('#post_form');
  if( formContent.find('input[name="post_title"]').val().length < 1 )
  {
    alert("标题必须大于1个字");return false;
  }

  // if( formContent.find('#txtContent').val().length < 6 )
  // {
  //   alert("标题必须大于6个字");return false;
  // }

  //设置category
  var category = '';
  $.each( $('#category_check_content').find('input[type="checkbox"]'), function(index,item){
    if($(item).prop("checked") == true)
    {
      category += $(item).val()+',';
    }
  });
  if( category == '' )
  {
    //没选，默认1
    category = '1';
  }
  else
  {
    category = category.substr(0,category.length-1);
  }

  var tags = '';
  $.each( $('#tag_check').find('.tag-item'), function(index,item){

      tags += $(item).find('label').text()+',';
  });
if( tags != '' )
  {
    tags = tags.substr(0,tags.length-1);
  }
  formContent.find('input[name="tag_strings"]').val(tags);
  formContent.find('input[name="category_ids"]').val(category);
  $('#post_form').submit();


});

/*******************************分类管理*************************/

if ( $('#page_add_category').length > 0 )
{

$('#add_category_button').click(function(){
  //添加分类，检查数据是否完整
  var postForm = $('#post_form');
  var name = postForm.find('input[name="name"]').val();

  //检查name
  if( name.length == '' )
  {
    alert( "name can't allow empty!");return false;
  }
  postForm.submit();

});

}

$(document).ready(function(){
/****************************主题管理*****************/
if( $('.themelists').length > 0 )
{

$('.themeName').on('click','.selectTheme',function(){
  var data = {};
  var that = this;
  data['name'] = $(this).attr('data-name');
  $.get(_url,data).success(function(data){
    if( data.status == true)
    {
      var parent = $(that).parent();
      $(that).remove();
      var name = $('.themelists').find('.current').html();
      $('.themelists').find('.current').append('<button class="selectTheme" data-name="'+name+'" style="float:right;">选择</button>');
      $('.themelists').find('.current').removeClass('current');
      parent.addClass('current');
    }
  });
});


  function setDialog(obj)
  {
    var str = '';
    var parent = $(obj).parent();
    var name = parent.attr('data-name');
    var version = parent.attr('data-version');
    var imgsrc = $(obj).find('img').attr('src');
    var description = parent.attr('data-desc');
    var authorurl = parent.attr('data-authorurl');
    var author = parent.attr('data-author');
    var html = '';
    html += "<div class='col-md-6 screenshort'><img src='"+imgsrc+"' style='max-width:90%;' ></div>";
    html +="<div class='col-md-3'><div><h3>"+name+"</h3><span class='version'>version:"+version+"</span></div>";
    html +='<br/>';
    html += "<div>PowereBy:<a href='"+authorurl+"'>"+author+"</a></div>"
    html +='<br/><br/>';
    html += "<div><p>"+description+"</p></div>";

    html += "</div>";
    $('#myModal').find('.title').html(name);
    $('#myModal').find('.rs-dialog-body').html(html);
  }


    var trigger   = $('.showDetail');
    var rs_dialog   = $('#' + trigger.data('target'));
    var rs_box    = rs_dialog.find('.rs-dialog-box');
    var rs_close  = rs_dialog.find('.close');
    var rs_overlay  = $('.rs-overlay');
    if( !rs_dialog.length ) return true;
    // Open dialog
    trigger.click(function(){
      setDialog(this);
      //Get the scrollbar width and avoid content being pushed
      var w1 = $(window).width();
      $('html').addClass('dialog-open');
      var w2 = $(window).width();
      c = w2-w1 + parseFloat($('body').css('padding-right'));
      if( c > 0 ) $('body').css('padding-right', c + 'px' );
      rs_overlay.fadeIn('fast');
      rs_dialog.show( 'fast', function(){
        rs_dialog.addClass('in');
      });
      return false;
    });
    // Close dialog when clicking on the close button
    rs_close.click(function(e){
      rs_dialog.removeClass('in').delay(150).queue(function(){
        rs_dialog.hide().dequeue();
        rs_overlay.fadeOut('slow');
        $('html').removeClass('dialog-open');
        $('body').css('padding-right', '');
      });
      return false;
    });
    // Close dialog when clicking outside the dialog
    rs_dialog.click(function(e){
      rs_close.trigger('click');
    });
    rs_box.click(function(e){
      e.stopPropagation();
    });
}
});

if( $('.menu-table').length > 0 )
{
  $('#select_type').change(function(){
    $('.inputholder').hide();
    if($(this).val() == 'category')
    {
      $('#category_selector').show();
    }
    else if($(this).val() == 'page')
    {
      $('#page_selector').show();
    }
    else
    {
        $('#coustorm_selector').show();
    }
  });
  $('.selectchange').change(function(){
      var type = $(this).attr('name');
      var key_id = $(this).val();
      var post_id = $(this).attr('data-post');
      var data = {};
      data['type'] = type;
      data['key_id'] = key_id;
      data['post_id'] = post_id;
      $.post(_edit_url,data).success(function(data){
          if(data.status == true)
          {
            alert('hehe');
          }
      });
  });

  $('.inputchange').blur(function(){
    var that = this;
    var type = $(this).attr('name');
    var post_id = $(this).parents('tr').attr('data-post');
    var value = $(this).val();
    var data = {};
    if( $(this).attr('data-value') == value)
      return false;
    data['post_id'] = post_id;
    data['type'] = type;
    data['value'] = value;
    $.post(_edit_url,data).success(function(data){
        if(data.status == true)
        {
          $(that).attr('data-value',value);
        }
    });
  });

}


