var app = new Vue({
	el: '#settings',
	data: {
		title: 'Добавить пользователя',
		action: 'POST'
	},
	methods: {
		
		// Кнопка добавления пользователя
		add_user: function(){

			// Меняем заголовок
			this.title = 'Добавить пользователя';
			this.action = 'POST';

			// Очищаем все поля
			$('.modal-body input,.modal-body textarea,.modal-body select').each(function(index){
				$(this).val('');
			})

			$('input[name="login"]').attr('disabled',false);
			$('input[name="operation"]').val('add');

			$('*[data-action="reset-password"]').hide();

			// Чистим селекты
			$('.edit-user .select2').val(null).trigger('change');

			$('#edit-user').modal('show');

		},

		// Кнопка редактирования пользователя
		edit_user: function(){

			this.action = 'PUT';

			var id = $('#datatable tbody input[type="checkbox"]:checked').closest("tr").attr('data-id');

			if ( checkRowAmount() != 1 ) {
				autoHideNotify('error', 'top right', 'Необходимо выбрать одну запись','Необходимо выбрать только одну запись');
				return false;
			};

			this.fillTaskForm(id);

		},

		// Заполнение формы
		fillTaskForm: function(id){

			this.title = 'Редактировать пользователя';

		    $.ajax({
			   	type: 'GET',
			   	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			   	dataType: "json",
			   	url: "/settings/users/" + id,
			   	cache: false,
			   	success: function(data){

		   			console.log(data[0]);

					$('input[name="name"]').val(data[0]['name']);
					$('input[name="surname"]').val(data[0]['surname']);
					$('input[name="father_name"]').val(data[0]['father_name']);

					$('input[name="email"]').val(data[0]['email']);
					$('input[name="phone"]').val(data[0]['phone']);

					// // Платфоры
					var platforms = data[0]['platform'].split(',');
					$('select[name="platform"]').val(platforms);
					$('select[name="platform"]').trigger('change');

					// // Группа пользователей
					var level = data[0]['role'];
					$('select[name="role"]').val([level]);
					$('select[name="role"]').trigger('change');

			    },
			    error: function(data) {
			    	console.log(data);
		        }
		    });

			$('#edit-user').modal('show');	

		},

		// Сохранение формы
		save_user: function(event){

			var error = '';

			var id = $('#datatable tbody input[type="checkbox"]:checked').closest("tr").attr('data-id');

		    // Проверяем заполненность полей
		    $('.edit-user input,.edit-user textarea,.edit-user select').each(function(index){

			   	var attr = $(this).attr('required');

			   	if (typeof attr !== typeof undefined && attr !== false) {

			   		if ( $(this).val() == '' ) {
			   			$(this).closest('.form-group').addClass('has-error');
			   			$(this).closest('.form-group').find('.help-block').text('Поле обязательно для заполнения');

			   			error = 'Обязательные поля не заполнены';
			   		};
			   	}

		    })

			if ( error != '' ) {
				console.log(error);
				return false;
			};

		    $('input[type="submit"]').val('Идет выполнение...');
			
		    $.ajax({
			   	type: this.action,
			   	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			   	dataType: "json",
			   	data: {
			   		'name' : $('input[name="name"]').val(),
			   		'surname' : $('input[name="surname"]').val(),
			   		'father_name' : $('input[name="father_name"]').val(),
			   		'email' : $('input[name="email"]').val(),
			   		'phone' : $('input[name="phone"]').val(),
			   		'platform' : '3',
			   		'role' : $('select[name="role"]').val()
			   	},
			   	url: "/settings/users/" + id,
			   	cache: false,
			   	success: function(data){
		   			console.log(data);
		   			window.location.href = "/settings/users";
			    },
			    error: function(data) {
			    	console.log(data);
		        }
		    });

		    $('input[type="submit"]').val('Сохранить');

		},

		// Удаление пользователя
		remove_user: function(event){

		    if ( checkRowAmount() < 1 ) {
		        autoHideNotify('error', 'top right', 'Не выбрано ни одной записи','Необходимо выбрать хотя бы одну запись');
		        return false;
		    };

		    confirm('info','top right', 'Пользователь(и) будут удалены');

		    $(document).on('click', '.notifyjs-metro-base .no', function() {
		        $(this).trigger('notify-hide');
		    });

		    $(document).on('click', '.notifyjs-metro-base .yes', function() {

		        $(this).trigger('notify-hide');

		        var removeId = [];

		        $('#datatable tbody input[type="checkbox"]:checked').each(function(index){
		            removeId.push($(this).closest("tr").attr('data-id'));
		        })

			    $.ajax({
				   	type: 'DELETE',
				   	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				   	url: "/settings/users/" + removeId,
				   	success: function(data){
				   		window.location.href = "/settings/users";
			   			console.log(data);
				    },
				    error: function(data) {
				    	console.log(data);
			        }
			    });	

		    });
		}
	}
})