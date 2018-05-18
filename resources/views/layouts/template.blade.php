<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@section('head')
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

	<!-- Favicon -->
	<link rel="shortcut icon" href="assets/admina/img/icon.png">

	<!-- Google Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>

	<!--Icon fonts css-->
	<link href="{{ asset('assets/admina/plugins/font-awesome/css/font-awesome.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/admina/plugins/ionicon/css/ionicons.min.css') }}" rel="stylesheet" />

	<!-- Bootstrap CSS -->
	<link href="{{ asset('assets/admina/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/admina/css/bootstrap-reset.css') }}" rel="stylesheet">

	<!--bootstrap-wysihtml5-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/admina/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}" />

	<!-- Plugins css -->
    <link href="{{ asset('assets/admina/plugins/notifications/notification.css') }}" rel="stylesheet" />

	 <!-- Basic Plugins -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="{{ asset('assets/admina/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('assets/admina/js/pace.min.js') }}"></script>

	<!-- jQuery UI -->
	<link rel="stylesheet" href="{{ asset('assets/jquery-ui/jquery-ui.min.css') }}">
	<script type="text/javascript" src="{{ asset('assets/jquery-ui/jquery-ui.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/datepicker-ru.js') }}"></script>

	<!-- jQuery cookie -->
	<script type="text/javascript" src="{{ asset('assets/jquery.cookie.js') }}"></script>

	<!-- Vue.js') }} -->
	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

	<!-- Offline JS -->
	<script type="text/javascript" src="{{ asset('assets/offline/offline.min.js') }}"></script>
	<link rel="stylesheet" href="{{ asset('assets/offline/offline-theme-chrome.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/offline/offline-language-russian.css') }}">

	<!-- Notification -->	
	<script src="{{ asset('assets/admina/plugins/notifications/notify.min.js') }}"></script>
    <script src="{{ asset('assets/admina/plugins/notifications/notify-metro.js') }}"></script>
    <script src="{{ asset('assets/admina/plugins/notifications/notifications.js') }}"></script>

	<!-- DataTables -->
    <script src="{{ asset('assets/admina/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <link href="{{ asset('assets/admina/plugins/datatables/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
	<script src="{{ asset('assets/admina/plugins/datatables/dataTables.bootstrap.js') }}"></script>

    <script src="{{ asset('assets/admina/plugins/datatables/dataTables.fixedHeader.min.js') }}"></script>
    <link href="{{ asset('assets/admina/plugins/datatables/fixedHeader.bootstrap.min.css') }}" rel="stylesheet" />

	<!-- Select2 -->
    <script src="{{ asset('assets/admina/plugins/select2/select2.min.js') }}"></script>
    <link href="{{ asset('assets/admina/plugins/select2/select2.css') }}" rel="stylesheet" />

	<!--Morris Chart CSS -->
	<link rel="stylesheet" href="{{ asset('assets/admina/plugins/morris/morris.css') }}">

	<!-- Custom styles -->
	<link href="{{ asset('assets/admina/css/style.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/admina/css/helper.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/admina/css/style-responsive.css') }}" rel="stylesheet" />

	<!-- Main js -->
	<script src="{{ asset('assets/admina/js/app.js') }}"></script>

    <script src="{{ asset('assets/admina/plugins/jquery-multi-select/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('assets/admina/plugins/select2/select2.min.js') }}" type="text/javascript"></script>

	<!-- Yandex.Metrika counter -->
	<script type="text/javascript" >
	    (function (d, w, c) {
	        (w[c] = w[c] || []).push(function() {
	            try {
	                w.yaCounter48548555 = new Ya.Metrika2({
	                    id:48548555,
	                    clickmap:true,
	                    trackLinks:true,
	                    accurateTrackBounce:true,
	                    webvisor:true
	                });
	            } catch(e) { }
	        });

	        var n = d.getElementsByTagName("script")[0],
	            s = d.createElement("script"),
	            f = function () { n.parentNode.insertBefore(s, n); };
	        s.type = "text/javascript";
	        s.async = true;
	        s.src = "https://mc.yandex.ru/metrika/tag.js";

	        if (w.opera == "[object Opera]") {
	            d.addEventListener("DOMContentLoaded", f, false);
	        } else { f(); }
	    })(document, window, "yandex_metrika_callbacks2");
	</script>
	<noscript><div><img src="https://mc.yandex.ru/watch/48548555" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!-- /Yandex.Metrika counter -->
        
</head>
@show

@section('header')
<header class="top-head container-fluid navbar-fixed-top">

	<div class="logo hidden-xs">
		<a href="/tasks" class="logo-expanded img-responsive">
			<span class="nav-text">Well Compliance</span>
		</a>
	</div>

	<button type="button" class="navbar-toggle pull-left">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-toggle ion-navicon-round"></span>
	</button>

	<!-- Right navbar -->
	<ul class="list-inline navbar-right top-menu top-right-menu">

		<!-- Notification(calendar) -->
		<li class="dropdown">
			<a target="_blank" href="/reports/matrix?type=calendar&user=<?//=$_COOKIE['login'];?>">
				<i class="fa fa-calendar-o"></i>
				<span class="badge badge-sm up bg-pink count"><?//=$notification->getCalendarNotice($link,$_COOKIE['login']); ?></span>
			</a>
		</li>

		<!-- Notification(tasks) -->
		<li class="dropdown">
			<a href="/tasks">
				<i class="fa fa-bell-o"></i>
				<span class="badge badge-sm up bg-pink count"><?//=$notification->getNotice($link,$_COOKIE['login']); ?></span>
			</a>
		</li>

		<!-- User Menu Dropdown -->
		<li class="dropdown text-center">
			<a data-toggle="dropdown" class="dropdown-toggle" href="#">
			<span class="username">{{ Auth::user()->name }} {{ Auth::user()->surname }} </span> <span class="caret"></span> </a>
			<ul class="dropdown-menu extended pro-menu fadeInUp animated" tabindex="5003" style="overflow: hidden; outline: none;">

                <li>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                        Выход
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>

				<!-- <li>
					<a href="/logout"><i class="fa fa-sign-out"></i> Выход</a>
				</li> -->
			</ul>
		</li>

	</ul>

</header>
@show


@section('aside')
<aside class="left-panel">

	<!-- Navbar -->
	<nav class="navigation">
		<ul class="list-unstyled">
			
			<? //if ( $access['tasks']['read'] == 1 ){ ?>
			<li>
				<a href="/tasks"><i class="ion-home"></i> <span class="nav-label">Рабочий стол</span></a>
			</li>
			<? //} ?>

			<? //if ( $access['documentation']['read'] == 1 ){ ?>
			<li class="has-submenu">
				<a href="#"><i class="fa ion-clipboard m-t-5"></i> <span class="nav-label">Нормативно-техническая документация</span> <span class="caret pull-right"></span></a>
				<ul class="list-unstyled">
					<li>
						<a href="#">Локальная нормативная документация</a>
					</li>
					<li>
						<a target="_blank" href="http://pepsico.company-dis.ru/docs/">Нормативно-техническая документация</a>
					</li>
				</ul>
			</li>
			<? //} ?>

			<? //if ( $access['evaluation']['read'] == 1 || $access['calendar']['read'] == 1 ){ ?>
			<li class="has-submenu">
				<a href="#">
					<i class="ion-settings m-t-5"></i>
					<span class="nav-label">Enterprise monitoring platform</span>
					<span class="caret pull-right"></span>
				</a>
				<ul class="list-unstyled">
					<? //if ( $access['evaluation']['create'] == 1 ){ ?>
					<li>
						<a href="/reports/add">Создать чек-лист</a>
					</li>
					<? //} ?>
					<? //if ( $access['evaluation']['read'] == 1 ){ ?>
					<li>
						<a href="/reports/all">Список чек-листов</a>
					</li>
					<? //} ?>
					<? //if ( $access['calendar']['read'] == 1 ){ ?>
					<li>
						<a href="/reports/calendar">Календарь мероприятий</a>
					</li>
					<? //} ?>
				</ul>
			</li>
			<? //} ?>

			<? //if ( $access['analytics']['read'] == 1 ){ ?>
			<li>
				<a href="/analytics">
					<i class="fa ion-stats-bars"></i>
					<span class="nav-label">Аналитика</span>
				</a>
			</li>
			<? //} ?>

			<? //if ( $access['register']['read'] == 1 ){ ?>
			<li class="has-submenu">
				<a href="#">
					<i class="ion-funnel"></i>
					<span class="nav-label">Реестры</span>
					<span class="caret pull-right"></span>
				</a>
				<ul class="list-unstyled">
					<li>
						<a href="/register">Список документов</a>
					</li>
					<? /*if ( $access['register']['update'] == 1 ){ ?>
					<li>
						<a href="/register/reload">Обновить базу</a>
					</li>
					<? }*/ ?>
				</ul>
			</li>
			<? //} ?>

			<? //if ( $access['standart-editor']['read'] == 1 ){ ?>
			<li>
				<a href="/demand/editor"><i class="ion-shuffle"></i> <span class="nav-label">Редактор стандартов</span></a>
			</li>
			<? //} ?>

			<? //if ( $access['demand-editor']['read'] == 1 ){ ?>
			<li>
				<a href="/demand"><i class="ion-gear-b"></i> <span class="nav-label">Редактор требований</span></a>
			</li>
			<? //} ?>

			<? //if ( $access['settings']['read'] == 1 ){ ?>
			<li>
				<a href="/settings"><i class="ion-network"></i> <span class="nav-label">Настройки</span></a>
			</li>
			<? //} ?>

			<? //if ( $access['settings']['read'] == 1 ){ ?>
			<li>
				<a href="/help"><i class="ion-help-buoy"></i> <span class="nav-label">Помощь</span></a>
			</li>
			<? //} ?>

		</ul>
	</nav>
	<!-- End Navbar -->

</aside>
@show

@yield('container')

@yield('modals')