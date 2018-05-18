<? 
	header('Content-type: text/html; charset=utf-8'); 

    include_once $_SERVER['DOCUMENT_ROOT'].'/assets/functions.php';

    // Настраиваем подключение к базе
	$db_host = '127.0.0.1';
	$db_user = 'root';
	$db_name = 'Matrix_test';
	$db_password = 'sis1801emp';
	$db = mysqli_connect ($db_host, $db_user, "sis1801emp", $db_name) or die ("Невозможно подключиться к БД");




	// $query = mysqli_query($db, "SELECT `visits` FROM `views` WHERE `date`='$date' AND `page_id`='$page_id'") or die ("Проблема при подключении к БД");

	// if ( mysqli_num_rows($query) == 0 ){
	//     mysqli_query($db, "INSERT INTO `views` SET `page_id`='$page_id', `visits`=1, `date`='$date'");
	// }else{
	// 	mysqli_query($db, "UPDATE `views` SET `visits`=`visits`+1 WHERE `page_id`='$page_id' AND `date`='$date'");
	// }

	// $today_views = mysqli_query($db, "SELECT `visits` FROM `views` WHERE `page_id`='$page_id' AND `date`='$date'");
	// $today_views = mysqli_fetch_assoc($today_views);
	// // echo $today_views['visits'];

	// $all_views = mysqli_query($db, "SELECT `visits` FROM `views` WHERE `page_id`='$page_id'");
	// while ($row = mysqli_fetch_assoc($all_views)) { 
	//      $all_views_count = $all_views_count + $row["visits"];
	// } 
	// echo $all_views_count;
?>