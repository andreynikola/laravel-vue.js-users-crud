<?
# Настраиваем подключение к базе
$db_host = '127.0.0.1';
$db_user = 'root';
$db_name = 'Matrix_test';
$db_password = 'sis1801emp';
$link = mysqli_connect($db_host, $db_user, "sis1801emp", $db_name) or die ("Невозможно подключиться к БД");


/* ---------- ОБЩИЕ ФУНКЦИИ ---------- */ 

$docsGroup = ['Пожарная безопасность','Охрана труда','Экология','Промышленная безопасность','ЛНД Газпром'];

# Проверяем сессию и лицензию пользователя
if ( !isset($_COOKIE['session']) ){
	if (strpos( $_SERVER['REQUEST_URI'],'cron') !== false) {
		
	}else if (strpos( $_SERVER['REQUEST_URI'],'login') == false) {
		header('Location: /login'); exit();
	}
}else{
	$userdata = mysqli_fetch_array(mysqli_query($link,"SELECT * FROM users WHERE id = '".intval($_COOKIE['id'])."' LIMIT 1"));

    if( ($userdata['hash'] !== $_COOKIE['session']) or ($userdata['id'] !== $_COOKIE['id']) )
    {
        setcookie('id', '', time() - 60*24*30*12, '/');
        setcookie('session', '', time() - 60*24*30*12, '/');
    	header('Location: /login'); exit();
    }
}

# Собираем массив прав доступа пользователя
if (isset($_COOKIE['session'])) {
	$accessLevel = mysqli_fetch_array(mysqli_query($link,"SELECT level.access FROM `users_group` AS level
								                          LEFT JOIN `users` AS users
								                          ON users.level = level.name
								                          WHERE users.login = '".$_COOKIE['login']."' 
								                          LIMIT 1
								                          "));

	$access = json_decode($accessLevel['access'],true);
}

# Перечень надзорных органов
if ( strpos($_SERVER['REQUEST_URI'], 'tasks') !== false ) {

	// Надзорные органы
	$authority = [
		'Ростехнадзор',
		'Министерство чрезвычайных ситуаций',
		'ГО ЧС',
		'Росприроднадзор',
		'Фонд социального страхования',
		'Государственная инспекция по труду',
		'Роснедра',
		'Министерство экологии и природопользования',
		'Роспотребнадзор',
		'Центр гигиены и эпидемиологии',
		'Санитарно эпидемиологическая станция',
		'Аудитор',
		'Прокуратора',
		'Управление федеральной миграционной службы',
		'Государственный автодорожный надзор',
		'Администрация субъекта РФ'
	];
}

# Валидация данных, полученных из форм
function sanitizeString($var){
	$var = stripslashes($var);
	$var = htmlspecialchars($var);
	$var = strip_tags($var);
	$var = trim($var);
	return $var;
}

# Генерация случайной строки
function generateCode($length=6) {
  $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
  $code = "";
  $clen = strlen($chars);  
  while (strlen($code) <= $length) {
    $code .= $chars[mt_rand(0,$clen)];  
  }
  return $code;
}

# Сортировка массива по ключу
function cmp($a, $b){
    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}

# Получение значения поля по значению другого поля
function getNodeByField($link,$table,$field,$value){
	$result = mysqli_query($link, "SELECT * FROM `$table` WHERE `$field` IN (".$value.")");

	while ($row = mysqli_fetch_assoc($result)) { 
		$object[] = $row;
	}

	return $object;

}

# Получение значения поля по значению другого поля
function getFieldByField($link,$table,$field,$value,$needle){
	$result = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM `$table` WHERE `$field`='".$value."'"));
	return $result[$needle];
}

# Получение максимального значения поля
function getMaxFieldValue($link,$table,$field){
	$result = mysqli_query($link, "SELECT MAX(`$field`) AS 'maxValue' FROM `$table` ");
	$row = mysqli_fetch_assoc($result);

	return intval($row['maxValue']);
}

# Получение значения поля по значению другого поля
if ($_POST['action'] == 'getFieldByField') {
	$table = sanitizeString($_POST['table']);
	$field = sanitizeString($_POST['field']);
	$value = sanitizeString($_POST['value']);
	$needle = sanitizeString($_POST['needle']);

	$result = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM `$table` WHERE `$field`='$value' "));
	echo $result[$needle];
}

# Получение записи(ей)
if ($_POST['action'] == 'get') {
	$table = $_POST['table'];
	$field = $_POST['field'];
	$value = $_POST['value'];
	$result = mysqli_query($link, "SELECT * FROM `$table` WHERE `$field`='$value' ");
	while ($row = mysqli_fetch_assoc($result)) { 
		$object['data'][] = $row;
	}

	echo json_encode($object,JSON_UNESCAPED_UNICODE);
}

# Удаление записи
if ($_POST['action'] == 'delete') {
	$table = sanitizeString($_POST['table']);
	$field = sanitizeString($_POST['field']);
	$value = sanitizeString($_POST['value']);

	echo $table.' '.$field.' '.$value;

	mysqli_query($link, "DELETE FROM `$table` WHERE `$field` IN ($value)");
}

# Получение максимального значения поля
if ($_POST['action'] == 'getMaxFieldValue') {
	$table = sanitizeString($_POST['table']);
	$field = sanitizeString($_POST['field']);
	//echo "SELECT MAX(`$field`) AS 'maxValue' FROM `$table` ";
	$result = mysqli_query($link, "SELECT MAX(`$field`) AS 'maxValue' FROM `$table` ");
	while ($row = mysqli_fetch_assoc($result)) {
		echo intval($row['maxValue']);
	} 
}


/* ---------- EHS и JOB ---------- */ 


# Получение EHS по id
function getEhsById($link,$id){
	// echo $link;
	$ehs = mysqli_fetch_array(mysqli_query($link,"SELECT * FROM ehs WHERE id = '".intval($id)."' LIMIT 1"));
	return $ehs['ehs_numbers'];
}

# Получение id EHS по Наименованию
function getEhsByName($link,$number){
	$ehs = mysqli_fetch_array(mysqli_query($link,"SELECT * FROM ehs WHERE ehs_numbers = '".$number."' LIMIT 1"));
	return $ehs['id'];
}

# Получение id EHS по Наименованию и Виду работ
function getEhsByFilter($link,$number,$job){

	$ehs = mysqli_fetch_array(mysqli_query($link,"SELECT `id` FROM ehs WHERE ehs_numbers = '".$number."' AND Parent_id_job IN ('".$job."') LIMIT 1"));
	return $ehs['id'];
}

# Получение вида работы по id
function getJobById($link,$id){
	$job = mysqli_fetch_array(mysqli_query($link,"SELECT * FROM types_jobs WHERE id = '".intval($id)."' LIMIT 1"));
	return $job['type'];
}

# Получение id вида работы по наименованию
function getJobByName($link,$name){
	$job = mysqli_fetch_array(mysqli_query($link,"SELECT * FROM types_jobs WHERE type = '".$name."' LIMIT 1"));
	return $job['id'];
}

# Собираем используемые виды работ в строку
function getJobString($arr){

	foreach ($arr as $key => $value) {
		$jobs[] = $value;
	}

	$jobs = implode("','", $jobs);

	return $jobs;
}

# Конвертируем виды работ в строку
function jobToString($string){

	$string = explode(",", $string);
	$string = implode("','", $string);
	$string = trim($string, ',');

	return $string;
}

# Тест для проверки магических EHS
function getSimilarEHSid($link,$id){

	$number = getEhsById($link,$id);

	$ehs = mysqli_query($link,"SELECT * FROM `ehs` WHERE `ehs_numbers` = '".$number."'");
	while ($row = mysqli_fetch_array($ehs)) { 
		$string .= $row['id'].",";
	}
	$string = trim($string, ',');
	return $string;
}

# Получение EHS
if ($_POST['action'] == 'getEHS') {
	$EHSInfo = mysqli_query($link, "SELECT DISTINCT `ehs_numbers` FROM `ehs` ");
	while ($row = mysqli_fetch_array($EHSInfo)) { 
		$ehs[] = $row['ehs_numbers'];
	} 
	echo json_encode($ehs,JSON_UNESCAPED_UNICODE) ;
}

# Получение JOB по EHS
if ($_POST['action'] == 'getJOBbyEHS') {
	$dataInfo = mysqli_query($link, 
		"SELECT * 
		 FROM `types_jobs` 
		 WHERE `id` IN (
			SELECT `Parent_id_job`
			FROM `ehs`
			WHERE `ehs_numbers` = '".$_POST['ehs']."'
		 )");
	while ($row = mysqli_fetch_array($dataInfo)) { 
		$data[] = $row['type'];
	}
	echo json_encode($data,JSON_UNESCAPED_UNICODE) ;
}

# Получение всех JOB
if ($_POST['action'] == 'getJOB') {
	$dataInfo = mysqli_query($link, 
		"SELECT * 
		 FROM `types_jobs` ");
	while ($row = mysqli_fetch_array($dataInfo)) { 
		$data[] = $row['type'];	}
	echo json_encode($data,JSON_UNESCAPED_UNICODE) ;
}


/* ---------- ПОЛЬЗОВАТЕЛИ ---------- */ 

# Проверка платформ у пользователей 
function checkUsersPlafrom($link){

	$users = getUsers($link);

	foreach ($users as $user) {

		$platforms = explode(',', $user['platform']);
		$newUserPlatforms = '';

		foreach ($platforms as $platform) {

			$exist = mysqli_query($link, "SELECT * FROM `platform` WHERE `id` = ".$platform);

			if (mysqli_num_rows($exist) > 0) {

				$newUserPlatforms .= $platform.",";

			}

		}

		mysqli_query($link, "UPDATE `users` SET `platform` = '".trim($newUserPlatforms, ',')."' WHERE `login`='".$user['login']."'");

	}	

}

# Отправка пароля
function sendPassword($name,$login,$email,$password){

    // Отправка пароля на почту
    $to = $email;
    $from = 'yakovlevda63@mail.ru';
	$tema = 'Регистрация нового пользователя в системе Well Comliance';

	$mess = '';	

	// Cообщение
	$mess = '
	<html>
	<head>
	 <title>Регистрация нового пользователя в системе Well Comliance</title>
	</head>
	<body>
	<p><b>Здравствуйте, '.$name.'!</b></p>
	<p>Вы были зарегистрированы в системе <a href="http://new.company-dis.ru/">Well Comliance</a>.</p>
	<p>Для входа в систему используйте следующие данные:</p>
	<p><b>Логин:</b> '.$login.'</p>
	<p><b>Пароль:</b> '.$password.'</p>
	</body>
	</html>
	';

	$headers= "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=UTF-8\r\n";
	$headers .= "From:".$from."\r\n";

	$send = mail($to, $tema, $mess, $headers);

	if (!$send) {
		header('HTTP/1.1 500 Forbidden');
		$status['type'] = 'error';
		$status['message'] = 'Операция не выполнена';
		$status['message2'] = 'Ошибка отправки письма пользователю';
		print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );
		return;
	}

}

# Сброс пароля пользователя
if ($_POST['action'] == 'resetPassword') {

	$login = $_POST['login'];
	$name = $_POST['name'];
	$email = $_POST['email'];
	$password = substr(uniqid(),0,7);
	$hash = md5(md5($password));
	$string .= " `password`='".$hash."' ";

    // Проверяем есть ли такой Логин
    $query_user = mysqli_query($link, "SELECT `id` FROM `users` WHERE `login` = '".$login."'");

	if (mysqli_num_rows($query_user) == 0) {
		header('HTTP/1.1 403 Forbidden');
		$status['type'] = 'error';
		$status['message'] = 'Операция не выполнена';
		$status['message2'] = 'Данный логин не зарегистрирован в системе';
		print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );
		return;
	}

    // Проверяем есть ли уже такой email
    $query_email = mysqli_query($link, "SELECT `id` FROM `users` WHERE `email` = '".$email."'");

	if (mysqli_num_rows($query_email) == 0) {
		header('HTTP/1.1 403 Forbidden');
		$status['type'] = 'error';
		$status['message'] = 'Операция не выполнена';
		$status['message2'] = 'Данный email не зарегистрирован в системе';
		print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );
		return;
	}

	$query = mysqli_query($link, "UPDATE `users` SET ".$string." WHERE `login`='".$login."'");

	sendPassword($name,$login,$email,$password);

    // Успешное выполнение
	$status['type'] = 'success';
	$status['message'] = 'Операция успешно выполнена';
	$status['message2'] = 'Информация пользователя изменена';
	print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );
	return;

}

# Добавление/редактирование пользователя
if ($_POST['action'] == 'updateUsers') {

    $data = explode( '&', $_POST['data'] );

    // Преобразуем в массив для работы
    foreach ($data as $key => $value) {

        $parts = explode('=', $value);

        $conditions[ $parts[0] ] = 	urldecode($parts[1]);

        if ( $parts[0] == 'platform' ) {
            $platform .= $parts[1].",";
        }

    }

    $operation = $conditions['operation'];
    unset($conditions['operation']);

    // Проверяем есть ли такой Логин
    $user = mysqli_query($link, "SELECT `id` FROM `users` WHERE `login` = '".$conditions['login']."'");

    if ( $operation == 'add' ) {

	    $email = mysqli_query($link, "SELECT `id` FROM `users` WHERE `email` = '".$conditions['email']."'");

		// Проверяем есть ли уже такой логин
		if (mysqli_num_rows($user) > 0) {
			header('HTTP/1.1 403 Forbidden');
			$status['type'] = 'error';
			$status['message'] = 'Операция не выполнена';
			$status['message2'] = 'Данный логин уже используется в системе';
			print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );
			return;
		}

	    // Проверяем есть ли уже такой email
		if (mysqli_num_rows($email) > 0) {
			header('HTTP/1.1 403 Forbidden');
			$status['type'] = 'error';
			$status['message'] = 'Операция не выполнена';
			$status['message2'] = 'Данный email уже используется в системе';
			print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );
			return;
		}

    }

	$conditions['platform'] = trim($platform, ',');
	$conditions['company'] = $_COOKIE['company'];

    foreach ($conditions as $key => $value) {
        $string .= " `".$key."`='".$value."',";
    }

    $string = trim($string, ',');

    if (mysqli_num_rows($user) == 0) {

		$password = substr(uniqid(),0,7);
		$hash = md5(md5($password));
		$string .= ", `password`='".$hash."'";

		$query = mysqli_query($link, "INSERT INTO `users` SET ".$string);

    	sendPassword($conditions['name'],$conditions['login'],$conditions['email'],$password);

    }else{
        $query = mysqli_query($link, "UPDATE `users` SET ".$string." WHERE `login`='".$conditions['login']."'");
    }

    // Успешное выполнение
	$status['type'] = 'success';
	$status['message'] = 'Операция выполнена';
	$status['message2'] = 'Информация пользователя изменена';
	print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );
	return;

}

# Удаление пользователей
if ($_POST['action'] == 'removeUsers') {
	$idArr = $_POST['ids'];
	mysqli_query($link, "DELETE FROM `users` WHERE `id` IN ($idArr)");
}

# Получение пользователей
if ($_POST['action'] == 'getUsers') {
	$userInfo = mysqli_query($link, "SELECT * FROM `users` WHERE `company`= '".$_COOKIE['company']."'");
	while ($uRow = mysqli_fetch_array($userInfo)) { 
		$fio[] = $uRow['surname'].' '.$uRow['name'].' '.$uRow['father_name'];
	} 
	echo json_encode($fio,JSON_UNESCAPED_UNICODE) ;
}

# Права доступа в системе
function getUsersGroup($link){

	$query = mysqli_query($link, "SELECT * FROM `users_group`");

	while ($row = mysqli_fetch_assoc($query)) { 
		$data[ $row['name'] ] = $row['translation'];
	}

	return $data;
}

# Получение всех платформ компании
function getPlatforms($link){

	$query = mysqli_query($link, "SELECT * FROM `platform` WHERE `company`= '".$_COOKIE['company']."'");

	while ($row = mysqli_fetch_assoc($query)) { 
		$data[ $row['id'] ]['id'] = $row['id'];
		$data[ $row['id'] ]['name'] = $row['name'];
		$data[ $row['id'] ]['settings'] = $row['settings'];
		$data[ $row['id'] ]['company'] = $row['company'];
	}

	return $data;
}

# Получение названия платформы по ее ID
function getPlatformsNames($link, $platform) {

$platform_name = mysqli_fetch_assoc(mysqli_query($link,"SELECT `name` FROM `platform` WHERE `id` = '".$platform."'")); 
return $platform_name;
}

# Получение пользователей
function getUsers($link,$initials=false){

	$userInfo = mysqli_query($link, "SELECT * FROM `users` WHERE `company`= '".$_COOKIE['company']."'");

	while ($uRow = mysqli_fetch_array($userInfo)) { 

		if ($initials == true) {
			$name = mb_substr($uRow['name'], 0,1,'UTF-8');
			$fatherName = mb_substr($uRow['father_name'], 0,1,'UTF-8');
			$data[ $uRow['id'] ]['name'] = $uRow['surname'].' '.$name.'. '.$fatherName.'.';	
		}else{
			$data[ $uRow['id'] ]['name'] = $uRow['surname'].' '.$uRow['name'].' '.$uRow['father_name'];			
		}

		$data[ $uRow['id'] ]['login'] = $uRow['login'];
		$data[ $uRow['id'] ]['first_name'] = $uRow['name'];
		$data[ $uRow['id'] ]['surname'] = $uRow['surname'];
		$data[ $uRow['id'] ]['father_name'] = $uRow['father_name'];
		$data[ $uRow['id'] ]['email'] = $uRow['email'];		
		$data[ $uRow['id'] ]['phone'] = $uRow['phone'];
		$data[ $uRow['id'] ]['level'] = $uRow['level'];
		$data[ $uRow['id'] ]['city'] = $uRow['city'];
		$data[ $uRow['id'] ]['company'] = $uRow['company'];
		$data[ $uRow['id'] ]['platform'] = $uRow['platform'];
	}

	return $data;
}

# Получение ФИО пользователя
function getUserName($link,$login){
	$userInfo = mysqli_query($link, "SELECT * FROM `users` WHERE `login`='".$login."'");
	while ($uRow = mysqli_fetch_array($userInfo)) { 
		$data['name'] = $uRow['surname'].' '.$uRow['name'].' '.$uRow['father_name'];
		// $data['login'] = $uRow['login'];
	}
	return $data['name'];
}

# Получение ответственного
function getResponse($link,$data){

	$data = explode(',', $data);

	foreach ($data as $key => $value) {
		$conditions .= "'".$value."',";
	}

	$conditions = trim($conditions, ',');

	$userInfo = mysqli_query($link, "SELECT * FROM `users` WHERE `login` IN (".$conditions.")");
	while ($uRow = mysqli_fetch_array($userInfo)) { 
		$fio[] = $uRow['surname'].' '.$uRow['name'].' '.$uRow['father_name'];
	} 

	foreach ($fio as $key => $value) {
		echo "<div>".$value."</div>";
	}
}

# Получение пользоватей по платформе
function getUsersByPlatform($link){

	$result = mysqli_query($link, "SELECT user.login FROM `users` AS user
	                               WHERE user.platform = (
          	                          SELECT `platform` FROM `users` WHERE `login` = '".$_COOKIE['login']."'
	                               )");

	while ($row = mysqli_fetch_assoc($result)) { 
		$data .= "'".$row['login']."',";
	} 

	$data = trim($data, ',');

	return $data;
}

# Счетчик заходов пользователя
function visitCount($link, $id, $visit){
    mysqli_query($link, "UPDATE users SET count='".$visit."' WHERE id='".$id."'") or die("MySQL Error: " .mysqli_error($link));
}


/* ---------- РЕДАКТОР ТРЕБОВАНИЙ ---------- */ 


# Проверяем критичность требования
function demandCriticalLevel($link,$amount){

	if ($amount >= 8 && $amount <= 9) {
		$level[] = 1;
		$level[] = 2;
		return $level;
	}else if ($amount >= 5 && $amount < 8) {
		$level[] = 2;
		$level[] = 3;
		return $level;
	}else if ($amount < 5) {
		$level[] = 4;
	}

	return $level;
}

# Цвет статуса в редакторе требований
function demandStatusColor($status){
	$status = str_replace("Статус:", "", $status);
	$status = trim($status);

	if (strpos($status, 'Недействующее') !== false) {
		echo "text-danger";
	}elseif (strpos($status, 'Действующее') !== false) {
		echo "text-success";
	}elseif (strpos($status, 'Проект') !== false) {
		echo "text-muted";
	}elseif (strpos($status, 'На утверждении') !== false) {
		echo "text-muted";
	}
	else{
		echo "text-success";
	}
}

# Добавление/редактирование требования
if ($_POST['action'] == 'addDemand' || $_POST['action'] == 'editDemand') {

	$demand['document'] = getDocumentBySymbol($link, $_POST['demand-document'] ) ;
	$demand['paragraph-name'] = sanitizeString($_POST['demand-paragraph-name']);
	$demand['paragraph-link'] = sanitizeString($_POST['demand-paragraph-link']);
	$demand['demand'] = $_POST['demand-demand'];
	$demand['user'] = $_COOKIE['login'];
	$demand['criticality1'] = sanitizeString($_POST['demand-criticality1']);
	$demand['criticality2'] = sanitizeString($_POST['demand-criticality2']);
	$demand['criticality3'] = sanitizeString($_POST['demand-criticality3']);

	// Если требование добавляется берем максимальное значение connected + 1
	if ($_POST['action'] == 'addDemand') {
		$demand['connected'] = getMaxFieldValue($link,'demands','connected')+1;
	}
	// Если требование редактируется оставляем его без изменнения
	if ($_POST['action'] == 'editDemand') {
		$demand['connected'] = $_POST['demand-connected'];
	}

	if ( sanitizeString($_POST['demand-privilege']) == 'on' ) {
		$demand['privilege'] = 1;
	}

	if ( isset($_POST['demand-status']) ) {
		$demand['status'] = sanitizeString($_POST['demand-status']);
	}else{
		$demand['status'] = 'На утверждении';
	}

	
	$demand['event'] = sanitizeString($_POST['demand-event']);
	$demand['source'] = $_POST['demand-source'];
	$now = new DateTime();
    $demand['date'] = $now->getTimestamp();

	foreach ($demand as $key => $value) {
		$recordPart1 .= " `".$key."`='".$value."',";
	}


	for ($i=0; $i < 20; $i++) {

		if ( isset($_POST['demand-ehs-'.$i]) ) {

			$currentNumber = $_POST['demand-ehs-'.$i];
			$currentJob = getJobByName($link, sanitizeString($_POST['demand-job-'.$i]));

			$recordPart2 = '';
			$recordPart2 .= " `ehs`='".getEhsByFilter($link,$currentNumber,$currentJob)."',";
			$recordPart2 .= " `job`='".getJobByName($link, sanitizeString($_POST['demand-job-'.$i]))."'";
			$record[$i] = $recordPart1.$recordPart2;

		}else{

			break;

		}

	}

	if ($_POST['action'] == 'addDemand') {

		foreach ($record as $key => $value) {
			$query = mysqli_query($link, "INSERT INTO `demands` SET ".$value);
		}

	}

	if ($_POST['action'] == 'editDemand') {

		mysqli_query($link, "DELETE FROM `demands` WHERE `connected` =".$_POST['demand-connected']);

		foreach ($record as $key => $value) {
			$query = mysqli_query($link, "INSERT INTO `demands` SET ".$value);
		}

		// Если цепочка требований была отредактирована просто удаляем её из коллектора(далее она зальется сама)
		mysqli_query($link,"DELETE FROM `collector` WHERE `connected` = ".$_POST['demand-connected']);
	}

}

# Находим все площадки компании
function getCompanyPlatforms($link){

	$query = mysqli_query($link,"SELECT `id` FROM platform WHERE `company` = '".$_COOKIE['company']."'");

	while ($row = mysqli_fetch_assoc($query)) {
		$platforms[] = $row['id'];
	}

	return $platforms;

}

# Обновление коллектора
function updateCollector($link,$connected = false){
	
	// Редактируем требования в коллекторе по идентификатуру связки
	if ($connected != false) {

	}

	// Находим в справочнике записи с новым `connected` (нет в коллекторе)
	$new = mysqli_query($link,"SELECT * FROM `demands` WHERE
							   `status` = 'Действующее' AND
		                       `connected` NOT IN 
		                       (SELECT DISTINCT `connected` FROM `collector`)");

	// Определяем кол-во чек-листов
	$reports = mysqli_query($link,"SELECT COUNT(*), `bunch`,`platform`,`creator`,`date`
	                           	   FROM `reports` 
	                               WHERE `platform` IN (".implode( ',' , getCompanyPlatforms($link) ).")
	                           	   GROUP BY `bunch`");

	// Для каждой группы чек-листов
	while ($rRow = mysqli_fetch_assoc($reports)) { 

		// Для каждого требования из цепочки
		mysqli_data_seek($new, 0); 
		while ($cRow = mysqli_fetch_assoc($new)) { 

			// Определяем идентификатор чек-листа в который будет добавлено требование
			$reportId = getReportIdByJob($link,$cRow['job'],$rRow['bunch'],$rRow['platform'],$rRow['creator'],$rRow['date']);

			// Добавляем запись в коллектор
			mysqli_query($link, "INSERT INTO `collector` SET
			`ehs`='".$cRow['ehs']."', 
			`job`='".$cRow['job']."', 
			`demand`='".$cRow['demand']."', 
			`event`='".$cRow['event']."', 
			`paragraph-name`='".$cRow['paragraph-name']."', 
			`paragraph-link`='".$cRow['paragraph-link']."', 
			`source`='".$cRow['source']."', 
			`document`='".$cRow['document']."', 
			`connected`='".$cRow['connected']."', 
			`privilege`='".$cRow['privilege']."', 
			`criticality1`='".$cRow['criticality1']."', 
			`criticality2`='".$cRow['criticality2']."', 
			`criticality3`='".$cRow['criticality3']."', 
			`demand-id`='".$cRow['id']."', 
			`user`='".$_COOKIE['login']."', 
			`date`='".$cRow['date']."', 
			`self-evaluation`=$reportId, 
			`calendar`= NULL,
			`platform`= ".$rRow['platform']
			);

		}	

	}
}

# Определяем идентификатор чек-листа в который будет добавлено требование
function getReportIdByJob($link,$job,$bunch,$platform,$creator,$date){

	$reports = mysqli_query($link,"SELECT `job`, `self-evaluation`
		                           FROM `collector`
                                   WHERE `job` = $job
                                   AND `self-evaluation` IN
                                   (
										SELECT `id` from `reports`
										WHERE `bunch` = $bunch
                                   )
		                           GROUP BY `self-evaluation`");

	// Если вид работы старый(уже есть в списке)
	if ( mysqli_num_rows($reports) > 0 ) {
		if ( $row = mysqli_fetch_assoc($reports) ) {
			return $row['self-evaluation'];
		}
	}
	// Если вид работы новый - добавляем новый отчет
	if ( mysqli_num_rows($reports) == 0 ) {

		$reportId = getMaxFieldValue($link,'reports','id')+1;

		// Добавляем запись в таблицу отчетов
		mysqli_query($link, "INSERT INTO `reports` SET 
			                `id`='".$reportId."',
			                `bunch`='".$bunch."',
			                `status`='0',
			                `creator`='".$creator."',
			                `platform`='".$platform."',
			                `date` = ".$date);

		return $reportId;

	}
}

# Удаление требований
if ($_POST['action'] == 'removeDemand') {
	$idArr = $_POST['ids'];
	$connectedArr = mysqli_query($link, "SELECT `connected` from `demands` WHERE `id` IN ($idArr)");
	while ($row = mysqli_fetch_array($connectedArr)) { 
		mysqli_query($link,"DELETE FROM `demands` WHERE `connected` = ".$row['connected']);
		mysqli_query($link,"DELETE FROM `collector` WHERE `connected` = ".$row['connected']);
	} 
}

# Подтверждение требований с одинаковым ID связок
if ($_POST['action'] == 'approveDemand') {
	$idArr = $_POST['ids'];

	mysqli_query($link,"UPDATE `demands` t1 SET t1.status = 'Действующее' 
		                WHERE t1.connected IN (
		  	            	SELECT DISTINCT t2.connected FROM (SELECT `id`,`connected` FROM `demands`) t2 WHERE t2.id IN (".$idArr.")
		                )");

	// Обновление коллектора
	updateCollector($link);
}


/* ---------- ЧЕК ЛИСТЫ ---------- */ 


# Идентификаторы активных отчетов
function getActiveReport($link){

	$result = mysqli_query($link, "SELECT `id` FROM `reports` WHERE `status` = '1' ");

	while ($row = mysqli_fetch_assoc($result)) { 
		$data .= "'".$row['id']."',";
	} 

	$data = trim($data, ',');

	return $data;
}

# Проверяем критичность требования
function isCritical(){
}

function getJobsNamesString($link,$ids){
	$jobs = mysqli_query($link,"SELECT DISTINCT types_jobs.`type` FROM `collector` 
	                            INNER JOIN `types_jobs`
	                            ON types_jobs.id = collector.job
	                            WHERE collector.`self-evaluation` IN (".$ids.")");
	while ($row = mysqli_fetch_assoc($jobs)) {
		$string .= $row['type'].",";
	}
	$string = trim($string, ',');
	return $string;
}

# Создание чек-листов
if ($_POST['action'] == 'collector') {

	// mysqli_query($link, "TRUNCATE TABLE `reports`");
	// mysqli_query($link, "TRUNCATE TABLE `collector`");

	// Проверяем есть уже ли по данной платформе отчеты
	$platform = mysqli_query($link, "SELECT * FROM `reports` 
		                             WHERE `platform` = (".$_POST['platform'].")");

	if (mysqli_num_rows($platform) > 0) {

		$status['type'] = 'error';
		$status['message'] = 'По данной платформе отчеты уже созданы';
		$status['message2'] = 'Нельзя создавать несколько групп отчетов в одном отчетном периоде';
		print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );
		return;

	}

	// Получаем максимальный идентификатор группы отчетов и увеличиваем его на 1
	$reportGroupId = getMaxFieldValue($link,'reports','bunch')+1;

	// Время создания отчета
	$now = strtotime("now");

	// Флаг для определения отчета
	$oldJob = '';



	// Собираем массив требований из справочника по ehs
	// Не забудь про 137 записей, которые находяться в справочнике с неправильным EHS
	$demands = mysqli_query($link, "SELECT * FROM `demands` 
		                            WHERE `ehs` IN (SELECT `id` FROM `ehs`) AND
		                            `document` IN (".implode( ',' , getCompanyDocuments($link) ).")
                                    ORDER BY `job`
		                            ");

	// $status['type'] = 'debug';
	// $status['object'] = "SELECT * FROM `demands` WHERE `ehs` NOT IN (".$ehs.") ";
	// print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );
	// return;

	// exit;

	if (mysqli_num_rows($demands) > 0) {

		while ($row = mysqli_fetch_assoc($demands)) {

			// Если новый вид работ, создаем новый отчет
			if ($oldJob != $row['job']){

				// Получаем максимальный идентификатор самооценки и увеличиваем его на 1
				$reportId = getMaxFieldValue($link,'reports','id')+1;

				// Добавляем запись в таблицу отчетов
				mysqli_query($link, "INSERT INTO `reports` SET 
					                `id`='".$reportId."',
					                `bunch`='".$reportGroupId."',
					                `status`='0',
					                `creator`='".$_COOKIE['login']."',
					                `platform`='".$_POST['platform']."',
					                `date` = ".$now);

			}

			$full[ $row['id'] ]['ehs-name'] = getFieldByField($link,'ehs','id',$row['ehs'],'ehs_name');
			$full[ $row['id'] ]['ehs'] = $row['ehs'];
			$full[ $row['id'] ]['job'] = $row['job'];
			$full[ $row['id'] ]['demand'] = $row['demand'];
			$full[ $row['id'] ]['event'] = $row['event'];
			$full[ $row['id'] ]['paragraph-name'] = $row['paragraph-name'];
			$full[ $row['id'] ]['paragraph-link'] = $row['paragraph-link'];
			$full[ $row['id'] ]['source'] = $row['source'];
			$full[ $row['id'] ]['document'] = $row['document'];
			$full[ $row['id'] ]['connected'] = $row['connected'];
			$full[ $row['id'] ]['privilege'] = $row['privilege'];
			$full[ $row['id'] ]['criticality1'] = $row['criticality1'];
			$full[ $row['id'] ]['criticality2'] = $row['criticality2'];
			$full[ $row['id'] ]['criticality3'] = $row['criticality3'];
			$full[ $row['id'] ]['date'] = $row['date'];
			$full[ $row['id'] ]['demand-id'] = $row['id'];
			$full[ $row['id'] ]['self-evaluation'] = $reportId;

			$oldJob = $row['job'];

		}

		// Добавляем записи в коллектор
		foreach ($full as $key => $value) {

			mysqli_query($link, "INSERT INTO `collector` SET 
			`ehs`='".$full[$key]['ehs']."', 
			`job`='".$full[$key]['job']."', 
			`demand`='".$full[$key]['demand']."', 
			`event`='".$full[$key]['event']."', 
			`paragraph-name`='".$full[$key]['paragraph-name']."', 
			`paragraph-link`='".$full[$key]['paragraph-link']."', 
			`source`='".$full[$key]['source']."', 
			`document`='".$full[$key]['document']."', 
			`connected`='".$full[$key]['connected']."', 
			`privilege`='".$full[$key]['privilege']."', 
			`criticality1`='".$full[$key]['criticality1']."', 
			`criticality2`='".$full[$key]['criticality2']."', 
			`criticality3`='".$full[$key]['criticality3']."', 
			`demand-id`='".$full[$key]['demand-id']."', 
			`self-evaluation`='".$full[$key]['self-evaluation']."', 
			`date`='".$full[$key]['date']."', 
			`platform`='".$_POST['platform']."', 
			`user`='".$_COOKIE['login']."', 
			`actual`='1', 
			`calendar`= NULL
			");

		}

		$status['type'] = 'success';
		$status['message'] = 'Успех';
		print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );
		return;

	}
}

# Обновление требований в чек-листе
if ($_POST['action'] == 'updateMatrix') {

	// Выбираем все неактуальные записи по определенной самооценке
	$collector = mysqli_query($link, "SELECT * FROM `collector`
		                              WHERE `self-evaluation`='".$_POST['id']."' 
		                              AND `actual`=0 ");

	while ($cRow = mysqli_fetch_assoc($collector)) {

		// Выбираем все требования из справочника по критериям
		$demand = mysqli_query($link, "SELECT * FROM `demands`
			                           WHERE `ehs`='".$cRow['ehs']."'
									   AND `job`='".$cRow['job']."'
									   AND `connected`='".$cRow['connected']."'"
			                           );

		while ($dRow = mysqli_fetch_assoc($demand)) {
			mysqli_query($link, "UPDATE `collector` 
				                 SET `demand`='".$dRow['demand']."',
				                 `event`='".$dRow['event']."',
				                 `paragraph-name`='".$dRow['paragraph-name']."',
				                 `paragraph-link`='".$dRow['paragraph-link']."',
				                 `source`='".$dRow['source']."',
				                 `document`='".$dRow['document']."',
				                 `privilege`='".$dRow['privilege']."',
				                 `criticality1`='".$dRow['criticality1']."',
				                 `criticality2`='".$dRow['criticality2']."',
				                 `criticality3`='".$dRow['criticality3']."',
				                 `date`='".$dRow['date']."',
				                 `actual`='1',
				                 `fio`='',
				                 `date-plan`=NULL,
				                 `actions`='',
				                 `status-compliance`='0'
				                 WHERE `id`='".$cRow['id']."'");
			// echo $dRow['id'];
		}

	}
}

# Удаление чек-листов
if ($_POST['action'] == 'removeBunch') {

	mysqli_query($link, "DELETE FROM `collector` WHERE `self-evaluation` IN (
	                     SELECT reports.id FROM `reports` WHERE `bunch` = ".$_POST['id']."
	                     )");

	mysqli_query($link, "DELETE FROM `reports` WHERE `bunch` = ".$_POST['id']);

	$status['type'] = 'success';
	print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );
	return;
}

# Удаление приложения к чек-листу
if ($_POST['action'] == 'removeAttachment') {

	$dir = $_SERVER['DOCUMENT_ROOT']."/uploads"."/".$_POST['type']."/".$_POST['id'];

	if ( is_dir( $dir ) ){
		rrmdir( $dir );
	}

}

# Удаление директории
function rrmdir($src) {
    $dir = opendir($src);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            $full = $src . '/' . $file;
            if ( is_dir($full) ) {
                rrmdir($full);
            }
            else {
                unlink($full);
            }
        }
    }
    closedir($dir);
    rmdir($src);
}

# Редактирование матрицы
if (isset($_POST['matrixData'])){

	$object = json_decode($_POST['matrixData'], TRUE);

	echo "<pre>";
	var_dump($object);
	echo "</pre>";

	// exit;

	// Переопределяем даты
	if ($object['date-plan']) {
		$object['date-plan'] = strtotime($object['date-plan']);
	}

	if ($object['fact-date']) {
		$object['fact-date'] = strtotime($object['fact-date']);
	}

	foreach ($object as $key => $value) {

		$params .= " `".$key."`='".$value."',";
	}

	$params = trim($params, ',');

	mysqli_query($link, "UPDATE `collector` SET ".$params." WHERE `id`=".$object['id']);
}

# Редактирование матрицы
if ( $_POST['action'] == 'editMatrix' ){

	$id = $_POST['id'];
	$key = $_POST['key'];
	$value = $_POST['value'];

	if ( preg_match('/(\d{2}\.\d{2}\.\d{4})/', $value) ) {
		$value = strtotime($value);
	}

	// echo "UPDATE `collector` SET `".$key."` = '".$value."' WHERE `id`=".$id;

	mysqli_query($link, "UPDATE `collector` SET `".$key."` = '".$value."' WHERE `id`=".$id);

	$status['type'] = 'success';
	$status['message'] = 'Запись успешно обновлена';
	$status['message2'] = 'Нужно придумать какой нибудь пояснительный текст';
	print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );	
	return;	

}

# Массовое проставление статуса соответствия
if ( $_POST['action'] == 'setSingleStatus' ){

	$evaluation = $_POST['evaluation'];
	$status = $_POST['status'];
	$statusType = $_POST['status-type'];

	mysqli_query($link, "UPDATE `collector` SET `".$statusType."` = '".$status."' WHERE `self-evaluation` = ".$evaluation);

}

/* ---------- ОРГАНАЙЗЕР ---------- */ 


# Удаление задачи
if ($_POST['action'] == 'remove') {
	$idArr = $_POST['ids'];
	mysqli_query($link, "DELETE FROM `tasks` WHERE `id` IN ($idArr)");
}

# Добавление задачи
if ($_POST['action'] == 'updateTask') {

    $data = explode( '&', str_replace('task-', '', $_POST['data']) );

    // Преобразуем в массив для работы
    foreach ($data as $key => $value) {

        $parts = explode('=', $value);

        $conditions[ $parts[0] ] = $parts[1];

        if ( $parts[0] == 'executors' ) {
            $executors .= $parts[1].",";
        }

    }

    // Проверяем есть ли такое задание
    $task = mysqli_query($link, "SELECT `id` FROM `tasks` WHERE `id` = ".$conditions['id']);
    if (mysqli_num_rows($task) == 0) {
        $now = new DateTime();
        $conditions['date'] = $now->getTimestamp();
    }

    if ( $conditions['id'] == '' ) {
        $conditions['id'] = getMaxFieldValue($link,'tasks','id')+1;
    }

    // Даты
    $conditions['deadline'] = strtotime($conditions['deadline']);
    $conditions['start-noty'] = strtotime($conditions['start-noty']);

    // Соисполнители
    $conditions['executors'] = trim($executors, ',');

    // Убираем комментарии
    unset( $conditions['comment'] );

    // Статус задачи
    if (!isset($conditions['status'])) {
        $conditions['status'] = 'Новая';
    }

    // Чек листы
    if (isset($_POST['subtask'])) {
        $conditions['subtask'] = json_encode($_POST['subtask'],JSON_UNESCAPED_UNICODE);
        $conditions['subtask'] = addslashes($conditions['subtask']);
    }

    foreach ($conditions as $key => $value) {
        $string .= " `".$key."`='".$value."',";
    }

    $string = trim($string, ',');

    // Записываем задание в базу
    if (mysqli_num_rows($task) == 0) {
        //echo "INSERT INTO `tasks` SET ".$string;
        $query = mysqli_query($link, "INSERT INTO `tasks` SET ".$string);
    }else{

        $query = mysqli_query($link, "UPDATE `tasks` SET ".$string." WHERE `id`=".$conditions['id']);
    }
}

# Изменение статуса
if ($_POST['action'] == 'changeTaskStatus') {
	$id = sanitizeString($_POST['task-id']);
	$status = sanitizeString($_POST['task-status']);
	$conditions = " `status`='".$status."'";
	if ($status == 'Выполнено') {
		$now = new DateTime();
		$conditions .= ", `finished` = ".$now->getTimestamp().", `closer` = '".$_COOKIE['login']."' ";
	}
	echo $conditions;
	$query = mysqli_query($link, "UPDATE `tasks` SET ".$conditions." WHERE `id`='$id' ");
}

# Добавление комментария к задаче
if ($_POST['action'] == 'addComment') {
	$id = $_POST['id'];
	$text = $_POST['text'];

	// Проверяем есть ли такое задание
	$task = mysqli_query($link, "SELECT `comments` FROM `tasks` WHERE `id` = ".$id);

	if (mysqli_num_rows($task) == 0) {

		$status['type'] = 'error';
		$status['message'] = 'Ошибка';
		$status['message2'] = 'Данного задания не существует';
		print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );
		return;

	}else{

		$comments = mysqli_fetch_array($task);
		$now = new DateTime();	

		// Если первая запись
		if ( empty( $comments['comments'] ) ) {

			$newComment = array (
				0 => array (
					"user" => $_COOKIE['login'],
					"date" => $now->getTimestamp(),
					"text" => $text
				)
			);

			$comments = json_encode($newComment,JSON_UNESCAPED_UNICODE) ;
			
		}else{

			$newComment = array (
				"user" => $_COOKIE['login'],
				"date" => $now->getTimestamp(),
				"text" => $text
			);

			

			$comments = json_decode( $comments['comments'], true );
			array_push($comments, $newComment);
			$comments = json_encode($comments,JSON_UNESCAPED_UNICODE) ;

		}

		mysqli_query($link, "UPDATE `tasks` SET `comments`='".$comments."' WHERE `id`='$id' ");

		$status['type'] = 'success';
		$status['message'] = 'Операция выполнена';
		$status['message2'] = 'Добавлен пользовательский комментарий к задаче';
		print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );
		return;

	}
}

/* ---------- РЕДАКТОР СТАНДАРТОВ ---------- */ 

# Редактирование связей ehs и видов работ
if ($_POST['action'] == 'setRelations') {

	$ehs_number = sanitizeString($_POST['ehs_numbers']);
	$ehs_name = sanitizeString($_POST['ehs_name']);
	$job_type = sanitizeString($_POST['type']);
	$job_id = getFieldByField($link,'types_jobs','type',$_POST['type'],'id');

	// Если данного вида работы не существует
	if ( !isset($job_id) ) {
		$status['type'] = 'error';
		$status['message'] = 'Данный вид работы не существует';
		print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );	
		return;
	}

	// Данная запись уже есть в таблице EHS
	$exist = mysqli_query($link, "SELECT * FROM `ehs` WHERE 
		     					 `ehs_numbers`='".$ehs_number."' AND 
		     					 `Parent_id_job`='".$job_id."'");

	if ( mysqli_num_rows($exist) > 0 ){
		$status['type'] = 'error';
		$status['message'] = 'Данная связка уже существует';
		print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );	
		return;	
	}

	// Если связки не существует добавляем новую
	mysqli_query($link, "INSERT INTO `ehs` 
		                 SET `Parent_id_job`='".$job_id."',
                         `ehs_numbers`='".$ehs_number."',
                         `ehs_name`='".$ehs_name."'
                         ");	
	$status['type'] = 'success';
	$status['message'] = 'Связка успешно добавлена';
	print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );	
	return;	
}

# Удаление связей ehs и видов работ
if ($_POST['action'] == 'removeRelations') {
	$number = sanitizeString($_POST['ehs_numbers']);
	$name = sanitizeString($_POST['ehs_name']);
	$type = sanitizeString($_POST['type']);
	$ehsId = sanitizeString($_POST['ehs_id']);
	$jobId = sanitizeString($_POST['job_id']);

	// echo "<pre>";
	// 	var_dump($_POST);
	// echo "</pre>";

	// echo $ehsId;
	//echo "DELETE FROM `ehs` WHERE `id` = $ehsId";
	mysqli_query($link, "DELETE FROM `ehs` WHERE `id` = $ehsId");
}

# Сохранение вида работы
if ( $_POST['action'] == 'saveJob' || $_POST['action'] == 'removeJob' ) {

	$id = $_POST['id'];
	if ( isset($_POST['type']) ) {
		$type = $_POST['type'];
	}

	if ( $_POST['action'] == 'saveJob' ) {

		// Проверяем есть ли данный вид работ в базе
		$job = mysqli_query($link, "SELECT * FROM `types_jobs` WHERE `type` ='".$type."'");

		if (mysqli_num_rows($job) > 0) {
			$status['type'] = 'error';
			$status['message'] = 'Данный вид работы уже существует';
			print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );
			return;
		}

		if ( $id != '' ) {
			mysqli_query($link, "UPDATE `types_jobs` SET `type`='$type' WHERE `id`='$id' ");
			$status['type'] = 'success';
			$status['message'] = 'Вид работ упешно обновлен';
			print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );
			return;
		}

		if ( $id == '' ) {
			mysqli_query($link, "INSERT INTO `types_jobs` SET `type`='$type' ");
			$status['type'] = 'success';
			$status['message'] = 'Новый вид работ упешно добавлен';
			print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );
			return;
		}

	}

	if ( $_POST['action'] == 'removeJob' ) {
		// Если обновляем запись
		if (isset($_POST['id'])) {
			mysqli_query($link,"DELETE FROM `types_jobs` WHERE `id`='$id' ");
			$status['type'] = 'success';
			$status['message'] = 'Вид работ упешно удален';
			print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );
			return;
		}
	}
}

# Сохранение/Удаление объекта
if ( $_POST['action'] == 'saveObj' || $_POST['action'] == 'removeObj' ) {

	$data = json_decode($_POST['data'], true);

	if ( isset($_POST['id']) ) {
		$id = $_POST['id'];
	}

	$name = $data['name'];
	$table = $data['table'];

	unset($data['table']);

	foreach ($data as $key => $value) {
		$string .= " `".$key."`='".$value."',";
	}

	$string = trim($string, ',');

	// print_r( json_encode($string,JSON_UNESCAPED_UNICODE) );

	// exit;

	if ( $_POST['action'] == 'saveObj' ) {

		// Проверяем есть ли данная запись в базе
		$node = mysqli_query($link, "SELECT * FROM `".$table."` WHERE `name` ='".$data['name']."'");

		if (mysqli_num_rows($node) > 0) {
			header('HTTP/1.1 500 Forbidden');
			$status['type'] = 'error';
			$status['message'] = 'Операция не выполнена';
			$status['message2'] = 'Данная запись уже присутствует в базе';
			print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );
			return;
		}
 
		if ( $id != '' ) { 
			mysqli_query($link, "UPDATE `".$table."` SET ".$string." WHERE `id`='$id' ");
			$status['type'] = 'success';
			$status['message'] = 'Операция успешно выполнена';
			$status['message2'] = 'Запись упешно обновлена';
			print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );
			return;
		}

		if ( $id == '' ) {
			mysqli_query($link, "INSERT INTO `".$table."` SET ".$string);
			$status['type'] = 'success';
			$status['message'] = 'Операция успешно выполнена';
			$status['message2'] = 'Новая запись упешно добавлена';
			print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );
			return;
		}

	}

	if ( $_POST['action'] == 'removeObj' ) {

		mysqli_query($link,"DELETE FROM `".$table."` WHERE `id`='$id' ");
		checkUsersPlafrom($link);

		$status['type'] = 'delete';
		$status['message'] = 'Операция успешно выполнена';
		$status['message2'] = 'Запись удалена';
		print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );
		return;

	}
}


/* ---------- РЕЕСТР ---------- */ 

# Находим все площадки компании
function getCompanyDocuments($link){

	$query = mysqli_query($link,"SELECT DISTINCT `nd` FROM `register` WHERE `company` LIKE '%".$_COOKIE['company']."%'");

	while ($row = mysqli_fetch_assoc($query)) {
		$nd[] = $row['nd'];
	}

	// var_dump($nd);
	return $nd;

}

# Цвет статуса в регистре
function registerStatusColor($status){
	$status = str_replace("Статус:", "", $status);
	$status = trim($status);

	if (strpos($status, 'Недействующий') !== false) {
		return "text-danger";
	}elseif (strpos($status, 'Действующий') !== false) {
		return "text-info";
	}elseif (strpos($status, 'Проект') !== false) {
		return "text-muted";
	}else{
		return "text-warning";
	}
}

# Парсинг реестра
if ($_POST['action'] == 'parseRegister') {
	
	$nd = intval(sanitizeString($_POST['nd']));
	$name = sanitizeString($_POST['name']);
	$status = sanitizeString($_POST['status']);
	$date = sanitizeString($_POST['date']);
	$type = sanitizeString($_POST['type']);
	$comment = sanitizeString($_POST['comment']);

	print_r($nd.' '.$name.' '.$status.' '.$type.' '.$comment);

	$query = mysqli_query($link, "SELECT `nd` FROM `register` WHERE `nd`='$nd' ") or die ("Проблема при подключении к БД");

	if ( mysqli_num_rows($query) == 0 ){
		mysqli_query($link, "INSERT INTO `register` SET `nd`='$nd', `name`='$name', `status`='$status', `date`='$date', `type`='$type', `comment`='$comment' ");
	}else{
		mysqli_query($link, "UPDATE `register` SET `name`='$name', `status`='$status', `date`='$date', `type`='$type', `comment`='$comment' WHERE `nd`='$nd'");
	}
}

# Добавление и редактирование записи в реестре
if ($_POST['action'] == 'editRegister' || $_POST['action'] == 'addRegister') {



	$nd_old = $_POST['nd_old'];
	$nd = $_POST['nd'];
	$symbol = $_POST['symbol'];
	$comment = sanitizeString($_POST['comment']);
	$group = implode(",", $_POST['group']);
	$company = sanitizeString($_POST['company']);

	// echo "<pre>";
	// 	var_dump($group);
	// echo "</pre>";
	
	// exit();

	if ($_POST['action'] == 'editRegister') {
		$query = mysqli_query($link, "UPDATE `register` SET 
			                         `nd`=$nd, 
			                         `symbol`='$symbol', 
			                         `comment`='$comment',
			                         `group`='$group',
			                         `company`='$company',
			                         `status`='Статус: Действующий (актуальный)' 
			                          WHERE `nd`='$nd_old' ");
	}

	if ($_POST['action'] == 'addRegister') {
		$query = mysqli_query($link, "SELECT `nd` FROM `register` WHERE `nd`='$nd' ") or die ("Проблема при подключении к БД");

		if ( mysqli_num_rows($query) == 0 ){
			mysqli_query($link, "INSERT INTO `register` SET 
				                `nd`=$nd, 
				                `status`='Статус: Действующий (актуальный)', 
				                `symbol`='$symbol', 
				                `group`='$group', 
				                `company`='$company', 
				                `comment`='$comment' 
				                ");
		}else{
			mysqli_query($link, "UPDATE `register` SET
			                    `nd`=$nd, 
			                    `symbol`='$symbol', 
			                    `comment`='$comment', 
			                    `group`='$group', 
			                    `company`='$company', 
			                    `status`='Статус: Действующий (актуальный)'
				                 WHERE `nd`='$nd_old'");
		}
	}
}

# Получение актуальных документов
if ($_POST['action'] == 'getDocuments') {
	$documentsInfo = mysqli_query($link, "SELECT DISTINCT `symbol` FROM `register` 
		                                  WHERE `status` LIKE '%(актуальный)%' AND
		                                  `nd` IN (".implode( ',' , getCompanyDocuments($link) ).")
		                                  ");
	while ($row = mysqli_fetch_array($documentsInfo)) { 
		$documents[] = $row['symbol'];
	} 
	echo json_encode($documents,JSON_UNESCAPED_UNICODE) ;
}

# Проверяем существование документа
if ( $_POST['action'] == 'checkDocument' ) {

	$document = mysqli_query($link,"SELECT * FROM register WHERE symbol = '".$_POST['symbol']."' LIMIT 1");
	
	if ( mysqli_num_rows($document) > 0 ) {
		$status['type'] = 'success';
		$status['message'] = 'Документ есть в системе';
		print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );	
		return;	
	}else{
		$status['type'] = 'error';
		$status['message'] = 'Документа нет в системе';
		print_r( json_encode($status,JSON_UNESCAPED_UNICODE) );	
		return;		
	}
}

# Получение документа по nd
function getDocumentByNd($link,$id){
	$document = mysqli_fetch_array(mysqli_query($link,"SELECT * FROM register WHERE nd = '".intval($id)."' LIMIT 1"));
	return $document['symbol'];
}

# Получение nd документа по symbol
function getDocumentBySymbol($link,$symbol){
	$document = mysqli_fetch_array(mysqli_query($link,"SELECT * FROM register WHERE symbol = '".$symbol."' LIMIT 1"));
	return $document['nd'];
}

# Проверка активности документа по nd
function getDocumentActivityByNd($link,$id){
	$document = mysqli_fetch_array(mysqli_query($link,"SELECT * FROM register WHERE nd = '".intval($id)."' LIMIT 1"));
	return $document['status'];
}

# Обрезка статуса в регистрах
function cropStatus($status){

	$status = str_replace("Статус:", "", $status);
	$status = trim($status);

	return $status;
}

# Если документа не существует удаляем требования и записи в коллекторе
if ($_POST['action'] == 'checkDocumentExistence') {
	
	mysqli_query($link,"DELETE FROM `demands` WHERE `document` NOT IN (SELECT DISTINCT `nd` FROM `register` )" );
	mysqli_query($link,"DELETE FROM `collector` WHERE `document` NOT IN (SELECT DISTINCT `nd` FROM `register` )" );

}


/* ---------- НАСТРОЙКИ ПРЕДРИЯТИЯ ---------- */ 


# Изменение настроек предприятия
if ($_POST['action'] == 'saveSettings') {

	$id = $_POST['id'];	
	$settings = $_POST['data'];	

	mysqli_query($link, "UPDATE `platform` SET `settings`='$settings' WHERE `id`=$id ");
}

# Получение настроек платформы
function getPlatformSettings($link,$platform){
	// Выбираем настройки предприятия
	$settings = mysqli_query($link, "SELECT platform.id, platform.name, platform.settings FROM `platform` AS platform
	                 WHERE platform.id = '".$platform."'
	                 ");
	$settingsRow = mysqli_fetch_assoc($settings);
	$settings = $settingsRow['settings'];
	return json_decode($settings, true);
}


/* ---------- НОТИФИКАЦИЯ ---------- */ 


$notification = new Notification;

class Notification {

	public $notice = array();
	public $calendarNotice = array();

	public static function getNotice($link, $user){
		
		$today = strtotime("now");	

		$tasks = mysqli_query($link, "SELECT * FROM `tasks` WHERE
			                         `status` IN ('Новая','В работе') AND
			                         `director` LIKE '%".$user."%' OR
			                         `responsible` LIKE '%".$user."%' OR
			                         `executors` LIKE '%".$user."%' 
			                          ORDER BY `deadline` ASC
			                          ");

		while ($row = mysqli_fetch_assoc($tasks)) { 

			# Просроченные задачи
			$diff = ($row['start-noty'] - $today);

			if ( $diff < 0 ) {

				$notice[ $row['id'] ]['name'] = $row['name'];
				$notice[ $row['id'] ]['deadline'] = $row['deadline'];
				$notice[ $row['id'] ]['type'] = 'text-danger';

				continue;

			}

			# Новые задачи
			if ( $row['status'] == 'Новая' ) {

				$notice[ $row['id'] ]['name'] = $row['name'];
				$notice[ $row['id'] ]['deadline'] = $row['deadline'];
				$notice[ $row['id'] ]['type'] = 'text-success';

			}

		}

		# Возвращаем кол-во уведомлений
		if ( isset($notice) ) {

			return count($notice);
		
		}

	}


	public static function getCalendarNotice($link, $user){

		$calendar = mysqli_query($link, "SELECT count(*) as `count` FROM `collector` WHERE
			                            `fio` LIKE '%".$user."%' AND
			                            `status-compliance` IN (2,3,4)
			                            ");

		$row = mysqli_fetch_assoc($calendar);

		if ( $row['count'] > 0 ){

			return $row['count'];

		}

	}


	public static function sendNoticeToMail($link){
	
		$users = getUsers($link);

		foreach ($users as $user) {

			// Сумма невыполненных заданий(органайзер и календарь)
			$notice = self::getNotice($link, $user['login']);
			$calendarNotice = self::getCalendarNotice($link, $user['login']);
			$noticeAmount = $notice + $calendarNotice;

			if ( $user['email'] != '' AND $noticeAmount > 0 ) {

			    $to = $user['email'];
			    $from = 'yakovlevda63@mail.ru';
				$tema = 'Уведомление о невыполненных задачах Well Comliance';

				$mess = '';	

				/* сообщение */
				$mess = '
				<html>
				<head>
				 <title>Уведомление о невыполненных задачах Well Comliance</title>
				</head>
				<body>
				<p><b>Здравствуйте, '.$user['name'].'!</b></p>
				<p>У вас есть '.$noticeAmount.' невыполненных заданий в системе Well Comliance.</p>
				<p>Перейдите по <a href="http://new.company-dis.ru/">ссылке</a>, чтобы узнать подробную информацию</p>
				</body>
				</html>
				';

				$headers= "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=UTF-8\r\n";
				$headers .= "From:".$from."\r\n";

				echo $user['login'].' - '.$noticeAmount."<br/>";
	    		mail($to, $tema, $mess, $headers);

			}
	
		}

	}
}

?>