$(function () {
  // 判断是否关闭网页行为的标志
  var flag = false;

  var params = location.search;
  // 获取缓存的图片及文字
  var system_src = localStorage.getItem('system_src');
  var avatar_src = localStorage.getItem('avatar_src');
  var username = localStorage.getItem('username');

  // 恢复已填写的用户名并删除缓存
  localStorage.removeItem('username');

  // 进入页面时判断一下是否已填写用户名，已填写则确定按钮可点击
  if ($('#codename').val() !== '') {
    $('.btn-confirm').css('background-color', '#01bfb3');
  }

  // 填入已选择的系统头像
  if (system_src) {
    $('.a-box > img').attr('src', system_src);
    $('#img-src').attr('value', system_src);
  }

  // 填入已裁剪的相册头像
  if (avatar_src) {
    $('.a-box > img').attr('src', avatar_src);
    $('#img-src').attr('value', avatar_src);
  }

  // 如果头像为空则填入默认头像
  // if ($('.a-box > img').attr('src') == '') {
  //   $('.a-box > img').attr('src', 'https://img.dangcdn.com/Public/static/headportrait/icon_default.png');
  // }

  // 验证表单填写完整性
  $('#codename').on('keyup', function () {
    if ($(this).val() !== '') {
      $('.btn-confirm').css('background-color', '#01bfb3');
    } else {
      $('.btn-confirm').css('background-color', '#bfc3c3');
    }
  })

  // 点击删除图标删除已填写的数据
  $('#btn-clear').on('click', function () {
    $(this).siblings().val('');
  })

  // 点击头像区域弹出选项
  $('#edit-info > .avatar').on('click', function () {
    $('#avatar-tab').fadeIn();

    // 点击弹出框以外的地方隐藏
    $("#avatar-tab").click(function () {
      $("#avatar-tab").fadeOut();
    });
    $(".box").click(function (event) {
      event.stopPropagation();
    });

    // 点击系统头像跳转到相应页面，并将用户填写的名称以参数形式传递
    $('.a-system').on('click', function () {
      var username = $('#codename').val();
      localStorage.setItem('username', username);

      // 如果相册头像已存在，清除，以便加载系统头像      
      if (avatar_src !== null) {
        localStorage.removeItem('avatar_src');
      }
      location.replace('systemHeadPortrait' + params);
    })

    // 点击取消隐藏
    $('#avatar-tab > .box > .btn-cancel').on('click', function () {
      $(this).parent().parent().fadeOut();
    })

    // 点击从相册获取本地图片
    $('.a-album > input').on('change', function () {
      // 获取已填写的用户名
      var username = $('#codename').val();
      // console.log(username);
      // 获取图片
      var imgFile = this.files[0];
      // console.log(imgFile);
      // if ((Math.round(imgFile.size / 1024 * 100) / 100) > 1024) {
      //   $("#avatar-tab").fadeOut();
      //   $('#too-large').fadeIn();
      //   var timer1 = setTimeout(function () {
      //     $('#too-large').fadeOut();
      //     clearTimeout(timer1);
      //   }, 1000);
      //   return;
      // }

      // 将角度错位的图片修正
      var Orientation = null;

      EXIF.getData(imgFile, function () {
        EXIF.getAllTags(this);
        Orientation = EXIF.getTag(this, 'Orientation');
        // return;
      });

      // 初始化api
      var reader = new FileReader();
      reader.readAsDataURL(imgFile);
      reader.onload = function (e) {
        var img = new Image();
        img.src = e.target.result;
        img.onload = function () {
          // canvas修正角度
          var expectWidth = this.naturalWidth;
          var expectHeight = this.naturalHeight;

          if (this.naturalWidth > this.naturalHeight && this.naturalWidth > 400) {
            expectWidth = 400;
            expectHeight = expectWidth * this.naturalHeight / this.naturalWidth;
          } else if (this.naturalHeight > this.naturalWidth && this.naturalHeight > 600) {
            expectHeight = 600;
            expectWidth = expectHeight * this.naturalWidth / this.naturalHeight;
          }
          var canvas = document.createElement("canvas");
          var ctx = canvas.getContext("2d");
          canvas.width = expectWidth;
          canvas.height = expectHeight;
          ctx.drawImage(this, 0, 0, expectWidth, expectHeight);
          var baseImg = null;

          console.log(Orientation);
          if (Orientation == 6) {
            rotateImg(this, 'left', canvas);
            baseImg = canvas.toDataURL("image/png", 0.8);
          } else if (Orientation == 8) {
            rotateImg(this, 'right', canvas);
            baseImg = canvas.toDataURL("image/png", 0.8);
          } else {
            baseImg = canvas.toDataURL("image/png", 0.8);
          }

          // 将图片数据存储到localstorage中
          localStorage.setItem('album_src', baseImg);
          localStorage.setItem('username', username);

          // 关闭遮罩层
          $('#avatar-tab').fadeOut();

          // 如果系统头像已存在，清除，以便加载相册头像
          if (system_src !== null) {
            localStorage.removeItem('system_src');
          }
          // 跳转到裁剪页面
          flag = true;
          location.replace('uploadHeadPortrait' + params);
        }
      }
    })
  })

  /***** 修正图片角度 *****/
  function rotateImg(img, direction, canvas) {
    //alert(img);  
    //最小与最大旋转方向，图片旋转4次后回到原方向    
    var min_step = 0;
    var max_step = 3;
    //var img = document.getElementById(pid);    
    if (img == null) return;
    //img的高度和宽度不能在img元素隐藏后获取，否则会出错    
    var height = img.height/5;
    var width = img.width/5;
    //var step = img.getAttribute('step');    
    var step = 2;
    if (step == null) {
      step = min_step;
    }
    if (direction == 'right') {
      step++;
      //旋转到原位置，即超过最大值    
      step > max_step && (step = min_step);
    } else {
      step--;
      step < min_step && (step = max_step);
    }
    //img.setAttribute('step', step);    
    /*var canvas = document.getElementById('pic_' + pid);   
    if (canvas == null) {   
        img.style.display = 'none';   
        canvas = document.createElement('canvas');   
        canvas.setAttribute('id', 'pic_' + pid);   
        img.parentNode.appendChild(canvas);   
    }  */
    //旋转角度以弧度值为参数    
    var degree = step * 90 * Math.PI / 180;
    var ctx = canvas.getContext('2d');
    switch (step) {
      case 0:
        canvas.width = width;
        canvas.height = height;
        ctx.drawImage(img, 0, 0, width, height);
        break;
      case 1:
        canvas.width = height;
        canvas.height = width;
        ctx.rotate(degree);
        ctx.drawImage(img, 0, -height, width, height);
        break;
      case 2:
        canvas.width = width;
        canvas.height = height;
        ctx.rotate(degree);
        ctx.drawImage(img, -width, -height, width, height);
        break;
      case 3:
        canvas.width = height;
        canvas.height = width;
        ctx.rotate(degree);
        ctx.drawImage(img, -width, 0, width, height);
        break;
    }
  }

  /***** 清空未设置的头像缓存 *****/
  var u = navigator.userAgent;
  var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
  var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
  window.onpagehide = function () {
    if (flag == false) {
      localStorage.removeItem('avatar_src');
      localStorage.removeItem('system_src');
    }
  }
  // 用户退出网页前清除localstorage
  window.onbeforeunload = function () {
    if (flag == false) {
      localStorage.removeItem('avatar_src');
      localStorage.removeItem('system_src');
    }
  }
})