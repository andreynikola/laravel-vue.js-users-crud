function notify(style, position, title, text) {
	var icon = 'fa fa-adjust';
	if (style == "error") {
		icon = "fa fa-exclamation";
	} else if (style == "warning") {
		icon = "fa fa-warning";
	} else if (style == "success") {
		icon = "fa fa-check";
	} else if (style == "info") {
		icon = "fa fa-question";
	} else {
		icon = "fa fa-adjust";
	}
	$.notify({
		title : title,
		text : text,
		image : "<i class='" + icon + "'></i>"
	}, {
		style : 'metro',
		className : style,
		globalPosition : position,
		showAnimation : "show",
		showDuration : 0,
		hideDuration : 0,
		autoHide : false,
		clickToHide : true
	});
}

/*function autoHideNotify(style, position, title, text) {
	var icon = "fa fa-adjust";
	if (style == "error") {
		icon = "fa fa-exclamation";
	} else if (style == "warning") {
		icon = "fa fa-warning";
	} else if (style == "success") {
		icon = "fa fa-check";
	} else if (style == "info") {
		icon = "fa fa-question";
	} else {
		icon = "fa fa-adjust";
	}
	$.notify({
		title : title,
		text : text,
		image : "<i class='" + icon + "'></i>"
	}, {
		style : 'metro',
		className : style,
		globalPosition : position,
		showAnimation : "show",
		showDuration : 0,
		hideDuration : 0,
		autoHideDelay : 5000,
		autoHide : true,
		clickToHide : true
	});
}*/

function autoHideNotify(style, position, title, text) {
	var icon = "fa fa-adjust";
	if (style == "error") {
		icon = "fa fa-exclamation";
	} else if (style == "warning") {
		icon = "fa fa-warning";
	} else if (style == "success") {
		icon = "fa fa-check";
	} else if (style == "info") {
		icon = "fa fa-question";
	} else {
		icon = "fa fa-adjust";
	}
	$.notify({
		title : title,
		text : text,
		image : "<i class='" + icon + "'></i>"
	}, {
		style : 'metro',
		className : style,
		globalPosition : position,
		showAnimation : "show",
		showDuration : 0,
		hideDuration : 0,
		autoHideDelay : 3000,
		autoHide : true,
		clickToHide : true
	});
}

function confirm(style, position, title) {


	var icon = "fa fa-adjust";
	if (style == "error") {
		icon = "fa fa-exclamation";
	} else if (style == "warning") {
		icon = "fa fa-warning";
	} else if (style == "success") {
		icon = "fa fa-check";
	} else if (style == "info") {
		icon = "fa fa-question";
	} else {
		icon = "fa fa-adjust";
	}
	$.notify({
		title : title,
		text : 'Вы уверены, что хотите выполнить действие?<div class="clearfix"></div><br><a class="btn btn-sm btn-default yes">Да</a> <a class="btn btn-sm btn-danger no">Нет</a>',
		image : "<i class='" + icon + "'></i>"
	}, {
		style : 'metro',
		className : style,
		globalPosition : position,
		showAnimation : "show",
		showDuration : 0,
		hideDuration : 0,
		autoHide : false,
		clickToHide : false
	});

    // $(document).on('click', '.notifyjs-metro-base .yes', function() {
    //     $(this).trigger('notify-hide');
    // });
    // $(document).on('click', '.notifyjs-metro-base .no', function() {
    //     $(this).trigger('notify-hide');
    // });


}