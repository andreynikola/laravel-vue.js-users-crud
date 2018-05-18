$(document).ready(function () {

	// Отображение панели редактирования
	$('.report tbody tr').hover(
	  function() {
	    $(this).find('.matrix-edit').show();
	  }, function() {
	    $(this).find('.matrix-edit').hide();
	  }
	);

	// Окраска статусов при загрузке страницы
	$('[data-status]').each(function () {
	  switch(parseInt($(this).attr("data-status"))){
	    case 1:
	      $(this).css("background","#1ca8dd");
	      break;
	    case 2:
	      $(this).css("background","#00FF33");
	      break;
	    case 3:
	      $(this).css("background","#FFFF33");
	      break;
	    case 4:
	      $(this).css("background","#FF3300");	    
	      break;
	  }
	});	

	// Кнопка редактирования записи
	$('*[data-action="edit-data"]').click(function(){

		var id = $(this).parents('tr').attr('id');
		var node = getNodeByField('collector','id',id);

		// Номер строки
		$('#row').text(id);

		// Текст требования
		$('#demand').text( node['data'][0]['demand'].replace(/<(?:.|\n)*?>/gm, '') );

		// Плановый срок
		var date = new Date( node['data'][0]['date-plan'] * 1000 );
		$('input[name="date-plan"]').datepicker();
		$('input[name="date-plan"]').datepicker( 'setDate', date );

		// ФИО ответственного за выполнение
		$('select[name="fio"]').val( node['data'][0]['fio'] );

		if (report != 'calendar') {
			$('select[name="status"]').val( node['data'][0][statusCompliance] );
			$('textarea[name="actions"]').val( node['data'][0][actionsType] );
		};

		if (report == 'calendar') {
			$('select[name="status"]').val( node['data'][0]['current-status-compliance'] );
			$('textarea[name="reason-failure"]').val( node['data'][0]['reason-failure'] );		
			$('textarea[name="recommendations"]').val( node['data'][0]['recommendations'] );		

			// Фактический срок
			var date = new Date( node['data'][0]['fact-date'] * 1000 );
			$('input[name="fact-date"]').datepicker();
			$('input[name="fact-date"]').datepicker( 'setDate', date );

		};

		$('#selfModal').modal('show');
	})

})