$(function () {
  var params = location.search;
  // 从localStorage中获取传来的图片
  var localSrc = localStorage.getItem('album_src');
  $('#cropper > img').attr('src', localSrc);

  var crop_result = $('.pre-box');

  var cropper_src = $('#cropper > img').attr('src');

  // 隐藏原本图层
  function hidePannel() {
    $('#cropper > img').hide();
    $('#cropper > .txt').hide();
  }

  // 将canvas画布转化为图片
  function convertCanvasToImage(canvas) {
    var image = new Image();
    return canvas.toDataURL("image/png");
  }

  new AlloyFinger($('#cropper > img')[0], {
    tap: function () {
      hidePannel();
      var  clientWidth = document.documentElement.clientWidth;
      new AlloyCrop({
        image_src: cropper_src,
        circle: true,
        width: clientWidth * 0.7,
        height: clientWidth * 0.7,
        output: 1,
        ok_text: '确定',
        cancel_text: '取消',
        ok: function (base64, canvas) {
          console.log(canvas);
          crop_result[0].appendChild(canvas);
          crop_result[0].querySelector("canvas").style.borderRadius = "50%";

          // 将截取的图片转化为png
          var avatar_src = convertCanvasToImage(canvas);
          // 将头像存入localStorage
          localStorage.setItem('avatar_src', avatar_src);
          // 清除原图
          localStorage.removeItem('album_src');
          // 点击完成，跳转回编辑页面
          location.replace('editShow' + params);
        },
        cancel: function () {
          $('#cropper > img').show();
          $('#cropper > .txt').show();
        }
      });
    }
  });

})