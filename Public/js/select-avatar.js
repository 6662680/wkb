$(function () {
  var params = location.search;

  // 选中打勾
  $('.a-list').on('click', 'li', function () {
    $(this).addClass('avatar-selected').siblings().removeClass('avatar-selected');
    $('.preview').attr('src', $(this).children('img').attr('src'));
    $('.btn-confirm').css('background-color', '#01bfb3');
  })
  // 未选中禁止点及确定，已选择将头像地址传回上一页
  var lis = $('.a-list > li');
  $('.btn-confirm').on('click', function () {

    for (var i = 0; i < lis.length; i++) {
      if ($(lis[i]).hasClass('avatar-selected')) {
        // 获取选中图片的地址
        var system_src = $(lis[i]).children('img').attr('src');
        localStorage.setItem('system_src', system_src);
        // location.href = 'editShow' + params;
        location.replace('editShow' + params);
      }
    }
  })
})