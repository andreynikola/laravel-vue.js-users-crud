$('button[data-dismiss="modal"]').click(function () {
        $('.update').css("display", "none");
        $('.save').css("display", "inline-block");
        $('#signupForm input').prop("value", "");
    });

    $('#usertables tbody input[type="checkbox"]').click(function () {
        checkRowAmount1();
    });

    $('#login').on('blur', function () {
        proverka('login');
    });
    $('#name1').on('blur', function () {
        proverka('name1');
    });
    $('#surname').on('blur', function () {
        proverka('surname');
    });
    $('#father_name').on('blur', function () {
        proverka('father_name');
    });
    $('#emaill').on('blur', function () {
        if ($(this).val() != '') {
            var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
            if (pattern.test($(this).val())) {
                $(".save").attr("disabled", false);
                $(this).closest('.form-group').removeClass('has-error');
                $(this).closest('.form-group').find('.help-block').text('');
            } else {
                $(".save").attr("disabled", true);
                $(this).closest('.form-group').addClass('has-error');
                $(this).closest('.form-group').find('.help-block').text('укажите действующую почту ');
            }
        } else {
            $(".save").attr("disabled", true);
            $(this).closest('.form-group').addClass('has-error');
            $(this).closest('.form-group').find('.help-block').text('Поле обязательно для заполнения');
        }
    });
    $('#phone').on('blur', function () {
        proverka('phone');
    });
    $('#city').on('blur', function () {
        proverka('city');
    });
    if (isEmpty($('#platform').val())) {
        $(".save").attr("disabled", false);
        $('#platform').closest('.form-group').removeClass('has-error');
        $('#platform').closest('.form-group').find('.help-block').text('');
    } else {
        $(".save").attr("disabled", true);
        $('#platform').closest('.form-group').addClass('has-error');
        $('#platform').closest('.form-group').find('.help-block').text('укажите действующую почту ');
    }


    $('#password_new').on('blur', function () {
        proverka('password_new');
        proverka('password_repeat');
    });
    $('#password_repeat').on('blur', function () {
        proverka('password_repeat');
        if (!isEmpty($('#password_repeat').val())) {
            var pasw = $('#password_new').val();
            var paswconf = $(this).val();
            if (pasw == paswconf) {
                $(".save").attr("disabled", false);
                $(this).closest('.form-group').removeClass('has-error');
            } else {
                $(this).closest('.form-group').addClass('has-error');
                $(this).closest('.form-group').find('.help-block').text('Пароли не совпадают');
                $(".save").attr("disabled", true);
            }
        }
    });
    /*отправка аватара (доделать обрезаник 100x100)*/

    $('.avatar').click(function () {
        $("input[name='image_file']").trigger("click");

    });
    $('.test').click(function () {
        var $input = $('#image_file').prop('files')[0];
        var fd = new FormData;
        fd.append('img', $input);
        $.ajax({
            url: '/updatetobase.php',
            data: fd,
            processData: false,
            contentType: false,
            type: 'post',
            datatype: 'text',
            success: function (data) {
                alert(data);
            }
        });
        console.log($("#image_file").val());
    });
    /*------------------------------------------------------------------*/

    function proverka(selected) {
        var value = $('#' + selected).val();
        if (value != '' && value.length > 2) {
            $('#' + selected).closest('.form-group').removeClass('has-error');
            $('#' + selected).closest('.form-group').find('.help-block').text('');
            $(".save").attr("disabled", false);
        } else if (value.length < 3 && value.length > 0) {
            $(".save").attr("disabled", true);
            $('#' + selected).closest('.form-group').addClass('has-error');
            $('#' + selected).closest('.form-group').find('.help-block').text('укажите более 2 знаков');
        } else {
            $(".save").attr("disabled", true);
            $('#' + selected).closest('.form-group').addClass('has-error');
            $('#' + selected).closest('.form-group').find('.help-block').text('Поле обязательно для заполнения');
        }
    }

    function forminput() {
        var proba = {};
        var ind = true;
        $('#signupForm').find('input').each(function () {
            if (!isEmpty($(this).val())) {
                proba[$(this).attr("id")] = $(this).val();
            } else {
                ind = false;
            }
        });
        $('#signupForm').find('select').each(function () {
            if (!isEmpty($(this).val())) {
                proba[$(this).attr("id")] = $(this).val();
            } else {
                ind = false;
            }
        });
        if (ind) {
            return proba;
        } else {
            return false;
        }
    }

    $(".update").click(function () {
        formin = forminput();
        formin.id = $('#usertables tbody input[type="checkbox"]:checked').parents("tr").attr("data-id");
        if (formin) {
            var data = 'update_user=' + JSON.stringify(formin);
            console.log(data);
            $.ajax({
                type: 'POST',
                url: "/updatetobase.php",
                dataType: 'json',
                cache: false,
                data: data,
                success: function (data1) {
                    console.log(data1);
                    if (data1 == true) {
                        console.log(data1);
                        $('button[data-dismiss="modal"]').click();
                        notify('success', 'top center', 'Notification', 'Сохраненино');
                        /*location.reload();*/
                    } else {
                        console.log(data1)
                    }
                },
                error: function error(jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    console.log(msg);
                }
            });
        }
    });
    /*получить информацию о пользователе*/
    $('button[data-action="edit_user"]').click(function () {
        $('.save').css("display", "none");
        $('.update').css("display", "inline-block");
        var id = $('#usertables tbody input[type="checkbox"]:checked').parents("tr").attr("data-id");
        var data = "infousers=" + JSON.stringify(id);
        $.ajax({
            type: 'POST',
            url: "/updatetobase.php",
            cache: false,
            dataType: 'json',
            data: data,
            success: function (data) {
                $('#login').val(data['login']);
                $('#name1').val(data['name']);
                $('#surname').val(data['surname']);
                $('#father_name').val(data['father_name']);
                $('#emaill').val(data['email']);
                $('#phone').val(data['phone']);
                $('#platform').val(data['platform']);
                $('#level').val(data['level']);
                $('#city').val(data['city']);
                $('#update_user').modal('show');
            },
            error: function error(jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                console.log(msg);
            }
        });

    });
    /*Сохранить пользователя*/
    $(".save").click(function () {
        if (forminput()) {
            var data = 'insert_user=' + JSON.stringify(forminput());
            $.ajax({
                type: 'POST',
                url: "/updatetobase.php",
                dataType: 'json',
                cache: false,
                data: data,
                success: function (data1) {
                    console.log(data1);
                    if (data1 == true) {

                        $('button[data-dismiss="modal"]').click();
                        notify('success', 'top center', 'Notification', 'Сохраненино');
                        /*location.reload();*/
                    } else {
                        console.log(data1)
                    }
                },
                error: function error(jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    console.log(msg);
                }
            });
        }
    });
    function removeusers() {
        $.ajax({
            type: 'POST',
            url: "/updatetobase.php",
            cache: false,
            data: {
                "remove_users": $('#usertables tbody input[type="checkbox"]:checked').parents("tr").attr("data-id")
            },
            success: function (msg) {
                // console.log(msg);
                location.reload();
            }
        });

    }

    function isEmpty(str) {
        return (typeof str === "undefined" || str === null || str === "");
    }

    function checkRowAmount1() {
        if ($('#usertables tbody input[type="checkbox"]:checked').length > 1) {

            /*$('.panel-heading .ion-trash-a').parent().addClass('btn-danger');*/
            $('.panel-heading .ion-edit').parent().removeClass('btn-info');
            $('.panel-heading .ion-edit').parent().eq(0).attr("disabled", true);
            $('.panel-heading .ion-trash-a').parent().removeClass('btn-danger');
            $('.panel-heading .ion-trash-a').parent().eq(0).attr("disabled", true);
        }
        if ($('#usertables tbody input[type="checkbox"]:checked').length == 1) {

            $('.panel-heading .ion-edit').parent().addClass('btn-info');
            $('.panel-heading .ion-edit').parent().eq(0).attr("disabled", false);
            $('.panel-heading .ion-trash-a').parent().addClass('btn-danger');
            $('.panel-heading .ion-trash-a').parent().eq(0).attr("disabled", false);

        }
        if ($('#usertables tbody input[type="checkbox"]:checked').length <= 0) {
            $('.panel-heading .ion-edit').parent().removeClass('btn-info');
            $('.panel-heading .ion-trash-a').parent().removeClass('btn-danger');
        }


        var rowAmount = $('#datatable tbody input[type="checkbox"]:checked').length;

        return rowAmount;
    }

    /*------------------------------------------------------------------*/
    /*Площадку удалить*/
    function removeplatform(idp) {
        $.ajax({
            type: 'POST',
            data: {
                "removeplatform":idp
            },
            url: "/updatetobase.php",
            cache: false,
            async: false,
            success: function (data) {
                if (data != 0) {
                    $("#platform-list").empty();
                    $("#platform-list").load("/app/views/settings/ajax-platform.php");
                } else {
                    $('.platform').addClass('has-error');
                    $('.platform').find('.help-block').text('Должна быть не менее одной площадки');
                }
            },
            error: function () {
                console.log('Данные не получены');
            }
        })
    }
    /*Площадку добавить*/
    function addplatform() {
        if ($('select[name="select_platfotm"]').val() != "") {
            $.ajax({
                type: 'POST',
                data: {
                    "action": "addplatform",
                    "id": $('select[name="select_platfotm"]').val(),
                },
                url: "/updatetobase.php",
                cache: false,
                async: false,
                success: function (data) {
                    $("#platform-list").empty();
                    $('.platform_add').removeClass('has-error');
                    $('.platform_add').find('.help-block').text('');
                    $("#platform-list").load("/app/views/settings/ajax-platform.php");
                },
                error: function () {
                    console.log('Данные не получены');
                }
            })
        } else {
            $('.platform_add').addClass('has-error');
            $('.platform_add').find('.help-block').text('Укажите площадку');
        }
    }
