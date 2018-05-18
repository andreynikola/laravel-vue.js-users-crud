$(document).ready( function(){

    /*$(function(){

        var 
            $online = $('.online'),
            $offline = $('.offline');

        Offline.on('confirmed-down', function () {
            $online.fadeOut(function () {
                $offline.fadeIn();
            });
        });

        Offline.on('confirmed-up', function () {
            $offline.fadeOut(function () {
                $online.fadeIn();
            });
        });

    });*/

	// Мультиселект для ответственных
	$('.select2').select2({
		// placeholder: 'Select an option'
	});

	// Мультиселект для соисполнителей
	// $('select[name="task-executor[]"]').multiSelect()

	// Удаляем ошибочные классы при изменении полей
	$('input[type="text"].form-control,textarea.form-control').keyup(function(){
		$(this).parents('.form-group').removeClass('has-error');
		$(this).siblings('.help-block').text('');
	});

	$('[data-toggle="tooltip"]').tooltip();

	// Локализация datepicker
	var dateToday = new Date(); 
	
	$(function(){
		$("#datepicker,.datepicker").datepicker({
			dateFormat: 'dd.mm.yy',
			language: 'ru',
			minDate: dateToday
		}).on("input change", function (e) {
			$(this).parents('.form-group').removeClass('has-error');
			$(this).siblings('.help-block').text('');
    	});

		$("input[name='from'],input[name='to']").datepicker({
			dateFormat: 'dd.mm.yy',
			language: 'ru',
		});
        $("input[name='analit_dateto'],input[name='analit_datefrom']").datepicker({
            dateFormat: 'yy',
            language: 'ru'
        });
		$.datepicker.setDefaults( $.datepicker.regional[ "ru" ] );
	});	
	
	// Выбор всех записей
	$('input[name="select_all"]').change(function(event){
	  // If checkbox is not checked
	    var checkboxes = $(event.target).parents('#datatable').find('tbody').find(':checkbox');
	    if($(this).prop('checked')) {
	      checkboxes.prop('checked', true);
	    } else {
	      checkboxes.prop('checked', false);
	    }
	    checkRowAmount();
	});

	// Количество записей
	$('#datatable tbody input[type="checkbox"]').change(function(event){
		checkRowAmount();
	})

	new subMenu();
	
	// if ($("aside.left-panel:not(.collapsed)").length != 0) {
	// 	$("aside.left-panel:not(.collapsed)").niceScroll({
	// 			cursorcolor : "#8e909a",
	// 			cursorborder : "0px solid #fff",
	// 			cursoropacitymax : "0.5",
	// 			cursorborderradius : "0px"
	// 	});		
	// }
	
	$('.navbar-toggle').click(function(){
		$("aside.left-panel").toggleClass("collapsed");
		if($("aside.left-panel nav.navigation > ul > li.active > ul").is(":visible")){
			$("aside.left-panel nav.navigation > ul > li.active > ul").hide();
			$('.content').css('margin-left','80px')
			// $('.logo').show();
		}else{
			$("aside.left-panel nav.navigation > ul > li.active > ul").show();
			// $('.logo').hide();
		}
		$(".nav-text").toggle();
		$(".logo").toggle();
		$(".navigation ul li a .caret").toggle();
	});
	// $('[data-toggle="remove"]').click(function(){
	// 	$(this).closest('.portlet').remove();
	// });
	// $('[data-toggle="reload"]').click(function(){
		
	// 	$(this).closest('.portlet').append('<div class="panel-reload"><div class="loader-1"></div></div>');
	// 	var t = $(this).closest('.portlet').find(".panel-reload");
	// 	setTimeout(function() {
	// 			t.fadeOut("fast", function() {
	// 				t.remove()
	// 			})
	// 		}, 500 + 1500 * Math.random())
			
	// });
	// $('[data-toggle="expand"]').click(function(){
		
	// 	$(this).closest('.portlet').toggleClass('expand');
	// 	if($('.blackground').is(":visible")){
	// 		$('.blackground').remove();
	// 	}else{
	// 		$('body').append('<div class="blackground"></div>');
	// 	}	
	// });
	// $(document).on("click", ".blackground" , function() {
	// 	$('.portlet.expand').removeClass('expand');
	// 	$('.blackground').remove();
	// });
	
	// $('.btn-message').click(function(){
	// 	sendMessage();
	// });
	// $('.chat-input').keypress(function(e) {
 //    if (e.which == 13) {
 //      		//e.preventDefault();
 //      		sendMessage();
 //    	}
 //  	});
	
	// if ($(".nicescroll").length != 0) {
	// 	$(".nicescroll").niceScroll({
	// 			cursorcolor : "#8e909a",
	// 			cursorborder : "0px solid #fff",
	// 			cursoropacitymax : "0.5",
	// 			cursorborderradius : "0px"
	// 	});
	// }



	

});

// Сброс пароля пользователя
function resetPassword(login,name,email){

    $.ajax({
        type: 'POST',
        dataType: "json",
        data: {
            'action' : 'resetPassword',
            'login' : login,
            'name' : name,
            'email' : email
        },
        url: "/assets/functions.php",
        cache: false,
        success: function(data){
        	console.log(data);
            // Обновление информации по комментариям через ajax
            autoHideNotify('success', 'top right', data['message'],data['message2']);
        },
        error: function(data) {
            var error = data.responseJSON;
            console.log(data);
            autoHideNotify('error', 'top right', error['message'],error['message2']);
            return false;
        }
    });

}

// Проверяем существование документа
function checkDocument(event){

	var symbol =  $(event.target).parents('form').find('input[name="demand-onload-document"]').val();

	if ( symbol == '' ){
		$('input[name="demand-onload-document"]').closest('.form-group').addClass('has-error');
		$('input[name="demand-onload-document"]').closest('.form-group').find('.help-block').text('Поле обязательно для заполнения');
		return false;	
	}	

	console.log(symbol);

	$.ajax({
		type: 'POST',
		dataType: "json",
		data: {
			"action": "checkDocument",
			"symbol": symbol,
		},
		url: "/assets/functions.php",
		cache: false,
		async: false,
		success: function(data){
			if (data['type'] == 'success') {
				$(event.target).parents('form').submit();
			};
			if (data['type'] == 'error') {
		      $('input[name="demand-onload-document"]').closest('.form-group').addClass('has-error');
		      $('input[name="demand-onload-document"]').closest('.form-group').find('.help-block').text('Данного документа нет в системе');
				return false;
			};
		},
        error: function() {
            console.log('Данные не получены');
        }
	})

}

function subMenu(){
	$("aside.left-panel nav.navigation > ul > li:has(ul) > a").click(function(){

		if(!$("aside.left-panel").hasClass("collapsed")){
			if($(this).parent().hasClass('active')){
				$(this).parent().removeClass("active");
				$(this).next().slideUp(300);
			}else{
				$("aside.left-panel nav.navigation > ul > li > ul").slideUp(300),
				$("aside.left-panel nav.navigation > ul > li").removeClass("active");
				$(this).next().slideToggle(300, function() {
					// $("aside.left-panel:not(.collapsed)").getNiceScroll().resize()
				}),
				$(this).closest("li").addClass("active");
			}
		}
	
		// $("aside.left-panel:not(.collapsed)").niceScroll({
		// 	cursorcolor : "#8e909a",
		// 	cursorborder : "0px solid #fff",
		// 	cursoropacitymax : "0.5",
		// 	cursorborderradius : "0px"
		// });
		
	});
}
function sendMessage(){
	var message = $('.chat-input').val();
	var now = new Date();
   	var outStr = now.getHours()+':'+now.getMinutes()+':'+now.getSeconds();
	var msg = '<li class="clearfix odd">'+
                 '<div class="chat-avatar">'+
                 	'<img src="assets/img/avatar-3.jpg" alt="male">'+
                 	'<i>'+outStr+'</i>'+
                  '</div>'+
                  '<div class="conversation-text">'+
                 	'<div class="chat-message">'+
                 	'<i>Ray Shannon</i>'+
                 	'<p>'+
                  		message+
                  	' </p>'+
                  	'</div>'+
                  '</div>'+
              '</li>';
         $('.conversation-list').append(msg);
         $(".conversation-list").scrollTop($(".conversation-list").prop("scrollHeight"));
         $('.chat-input').val('');
}

function checkRowAmount(){

	if ( $('#datatable tbody input[type="checkbox"]:checked').length > 0 ) {
		$('.panel-heading .ion-trash-a').parent().addClass('btn-danger');
		$('.panel-heading .ion-close').parent().addClass('btn-danger');
		$('.panel-heading .ion-unlocked').parent().addClass('btn-danger');
		$('.panel-heading .ion-edit').parent().removeClass('btn-info');
		$('.panel-heading .ion-eye').parent().removeClass('btn-primary');
		$('.panel-heading .ion-clipboard').parent().addClass('btn-purple');
		$('.panel-heading .ion-document').parent().removeClass('btn-warning');
		$('.panel-heading .ion-alert-circled').parent().removeClass('btn-danger');
		$('.panel-heading .ion-checkmark').parent().addClass('btn-success');
	}
	if ( $('#datatable tbody input[type="checkbox"]:checked').length == 1 ) {
		$('.panel-heading .ion-close').parent().addClass('btn-danger');
		$('.panel-heading .ion-unlocked').parent().addClass('btn-danger');
		$('.panel-heading .ion-edit').parent().addClass('btn-info');
		$('.panel-heading .ion-eye').parent().addClass('btn-primary');
		$('.panel-heading .ion-document').parent().addClass('btn-warning');
		$('.panel-heading .ion-alert-circled').parent().addClass('btn-danger');
	}
	if ( $('#datatable tbody input[type="checkbox"]:checked').length <= 0 ) {
		$('.panel-heading .ion-edit').parent().removeClass('btn-info');
		$('.panel-heading .ion-eye').parent().removeClass('btn-primary');
		$('.panel-heading .ion-trash-a').parent().removeClass('btn-danger');
		$('.panel-heading .ion-close').parent().removeClass('btn-danger');
		$('.panel-heading .ion-unlocked').parent().removeClass('btn-danger');
		$('.panel-heading .ion-clipboard').parent().removeClass('btn-purple');
		$('.panel-heading .ion-document').parent().removeClass('btn-warning');
		$('.panel-heading .ion-checkmark').parent().removeClass('btn-success');
		$('.panel-heading .ion-alert-circled').parent().removeClass('btn-danger');
	};

	var rowAmount = $('#datatable tbody input[type="checkbox"]:checked').length;

	// console.log(rowAmount);

	return rowAmount;
}

function checkArchive(){

	if ( $('#datatable tbody input[type="checkbox"]:checked').parents('tr').hasClass('archive') ){
		return true;
	}

}

// Получение EHS
function getEHS(){
	var attr = 'action=getEHS';
	
	$.ajax({
		type: 'POST',
		data: attr,
		url: "/assets/functions.php",
		cache: false,
		async: false,
		success: function(data){
			ehsInfo = $.parseJSON(data);
		},
        error: function() {
            console.log('Данные не получены');
        }
	})

	return ehsInfo;
}

// Получение видов работ
function getJOB(){
	var attr = 'action=getJOB';

	$.ajax({
		type: 'POST',
		data: attr,
		url: "/assets/functions.php",
		cache: false,
		async: false,
		success: function(data){
			jobInfo = $.parseJSON(data);
		},
        error: function() {
            console.log('Данные не получены');
        }
	})

	return jobInfo;
}

// Получение значения поля по значению другого поля
function getFieldByField(table,field,value,needle){
	var attr = 'action=getFieldByField' + '&table=' + table + '&field=' + field + '&value=' + value + '&needle=' + needle;

	var value;

	$.ajax({
		type: 'POST',
		data: attr,
		url: "/assets/functions.php",
		cache: false,
		async: false,
		success: function(data){
			value = data;
		},
        error: function() {
            console.log('Данные не получены');
        }
	})

	return value;
}

// Получение записи по полю
function getNodeByField(table,field,value){

	var attr = 'table=' + table + '&field=' + field + '&value=' + value + '&action=get';

	$.ajax({
		type: 'POST',
		data: attr,
		url: "/assets/functions.php",
		cache: false,
		async: false,
		success: function(data){
			// console.log(data);
			node = $.parseJSON(data);
		},
        error: function() {
            console.log('Данные не получены');
        }
	})

	return node;

}

// Получение максимального значения поля
function getMaxFieldValue(table,field){

	var attr = 'table=' + table + '&field=' + field + '&action=getMaxFieldValue';

	$.ajax({
		type: 'POST',
		data: attr,
		url: "/assets/functions.php",
		cache: false,
		async: false,
		success: function(data){
			maxValue = data;
		},
        error: function() {
            console.log('Данные не получены');
        }
	})

	return maxValue;

}

function validateField(input){

	if ( input.val() == '' ) {
		input.parents('.form-group').addClass('has-error');
		input.parents('.form-group').find('.help-block').text('Поле обязательно для заполнения');
		return false
	}else{
		return true;
	};

}

function getUsers(){
	var attr = 'action=getUsers';

	$.ajax({
		type: 'POST',
		data: attr,
		url: "/assets/functions.php",
		cache: false,
		async: false,
		success: function(data){
			userInfo = $.parseJSON(data);
			// console.log('Данные о пользователях получены');
			// console.log(userInfo);
		},
        error: function() {
            console.log('Данные не получены');
        }
	})

	return userInfo;
}
// Получение актуальных документов из реестра
function getDocuments(){
	var attr = 'action=getDocuments';
	
	$.ajax({
		type: 'POST',
		data: attr,
		url: "/assets/functions.php",
		cache: false,
		async: false,
		success: function(data){
			docs = $.parseJSON(data);
		},
        error: function() {
            console.log('Данные не получены');
        }
	})

	return docs;
}

// Получение видов работ по EHS
function getJOBbyEHS(ehs){
	var attr = 'action=getJOBbyEHS&ehs=' + ehs;

	$.ajax({
		type: 'POST',
		data: attr,
		url: "/assets/functions.php",
		cache: false,
		async: false,
		success: function(data){
			jobInfo = $.parseJSON(data);
		},
        error: function() {
            console.log('Данные не получены');
        }
	})

	return jobInfo;
}

// Проверка платформ пользователей
function checkUsersPlafrom(){

	$.ajax({
		type: 'POST',
		// dataType: "json",
		data: {
			"action" : "checkUsersPlafrom"
		},
		success: function(data){
			console.log(data);
		},
        error: function(data) {

        }
    });	

}

// Добавление элемента
function addObj(){

	if ( validateField( $(event.target).parents('.obj').find('input[type="text"]') ) ) {
		saveObj();
	};

}

// Сохранение элемента
function saveObj( id, action = 'saveObj' ){

	var data = {};

	$(event.target).parents('.obj').find('input[type="text"],input[type="hidden"]').each(function(index,item){
		data[ $(item).attr('name') ] = $(item).val();
	})

	var url = "/app/views/settings/ajax-" + data['table'] + ".php";

	$.ajax({
		type: 'POST',
		dataType: "json",
		data: {
			"id" : id,
			"action" : action,
			"data" : JSON.stringify(data)
		},
		url: "/assets/functions.php",
		cache: false,
		async: false,
		success: function(data){
			if (data['type'] == 'success') {
				autoHideNotify('success', 'top right', data['message'], data['message2']);
				location.reload();
				// $("#list").empty();
				// $("#list").load(url);	
			};
			if (data['type'] == 'delete') {
				autoHideNotify('success', 'top right', data['message'], data['message2']);	
				location.reload();
				// $("#list").empty();
				// $("#list").load(url);
			};
			console.log(data);
		},
        error: function(data) {
            var error = data.responseJSON;
            console.log(error);
            autoHideNotify('error', 'top right', error['message'],error['message2']);
            return false;
        }
	})
}

// Удаление элемента
function removeObj(id){

	$.ajax({
		type: 'POST',
		dataType: "json",
		data: {
			"action": "removeObj",
			"id": id,
		},
		url: "/assets/functions.php",
		cache: false,
		async: false,
		success: function(data){
			if (data['type'] == 'success') {
				autoHideNotify('success', 'top right', data['message']);	
				$("#job-list").empty();
				$("#job-list").load("/app/views/demand/ajax-job.php");	
			};
		},
        error: function() {
            var error = data.responseJSON;
            console.log(error);
            autoHideNotify('error', 'top right', error['message'],error['message2']);
            return false;
        }
	})
}

// Удаление элемента
function removeBunch(id){

	confirm('info','top right', 'Данные чек-листов удаляться безвозвратно!');

    $(document).on('click', '.notifyjs-metro-base .no', function() {
        $(this).trigger('notify-hide');
    });

    $(document).on('click', '.notifyjs-metro-base .yes', function() {

    	$(this).trigger('notify-hide');

		$.ajax({
			type: 'POST',
			dataType: "json",
			data: {
				"action": "removeBunch",
				"id": id,
			},
			url: "/assets/functions.php",
			cache: false,
			async: false,
			success: function(data){
				if (data['type'] == 'success') {
					// autoHideNotify('success', 'top right', data['message']);	
					location.reload();	
				};
			},
	        error: function() {
	            console.log('Данные не получены');
	        }
		})

    });

}

// NEW selector
jQuery.expr[':'].containsi = function(a, i, m) {
  return jQuery(a).text().toUpperCase()
      .indexOf(m[3].toUpperCase()) >= 0;
};

(function($){
    var proxy = $.fn.serializeArray;
    $.fn.serializeArray = function(){
        var inputs = this.find(':disabled');
        inputs.prop('disabled', false);
        var serialized = proxy.apply( this, arguments );
        inputs.prop('disabled', true);
        return serialized;
    };
})(jQuery);

// OVERWRITES old selecor
// jQuery.expr[':'].contains = function(a, i, m) {
//   return jQuery(a).text().toUpperCase()
//       .indexOf(m[3].toUpperCase()) >= 0;
// };