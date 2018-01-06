$(function () {
  // 使用fastclick
  FastClick.attach(document.body);

  // 输入框获取焦点
  $('input').on('click', function () {
    $(this).trigger('focus');
  });
})