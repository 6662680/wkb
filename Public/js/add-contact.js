$(function () {
    if ($('#tel').attr('data-value')) {
        // 如果为添加联系人，则备注为选填项
        $('#codename').attr('placeholder', '请输入对方备注');
        // 验证表单填写完整性
        $('#tel').on('keyup', function () {
            if ($(this).val() !== '') {
                // $('#codename').on('keyup', function () {
                // if ($(this).val() !== '') {
                $('.btn-confirm').css('background-color', '#01bfb3');
                // } else {
                // $('.btn-confirm').css('background-color', '#bfc3c3');
                // }
                // })
            }
        })

        // $('#codename').on('keyup', function () {
        //     if ($(this).val() !== '') {
        //         $('#tel').on('keyup', function () {
        //             if ($(this).val() !== '') {
        //                 $('.btn-confirm').css('background-color', '#01bfb3');
        //             } else {
        //                 $('.btn-confirm').css('background-color', '#bfc3c3');
        //             }
        //         })
        //     }
        // })

        // 验证信息是否完整
        // if ($('#tel').val() === '' || $('#codename').val() === '') {
        //     alert('请填入完整信息！');
        //     return false;
        // }
    } else {
        // console.log('编辑');
        $('#codename').on('keyup', function () {
            if ($(this).val() !== '') {
                $('.btn-confirm').css('background-color', '#01bfb3');
            } else {
                $('.btn-confirm').css('background-color', '#bfc3c3');
            }
        })

        // 验证信息是否完整
        // if ($('#codename').val() === '') {
        //     alert('请填入完整信息！');
        //     return false;
        // }
    }

})