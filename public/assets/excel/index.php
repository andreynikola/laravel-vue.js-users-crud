<?php
if ($_POST['upload'] == true){
    // Проверяем загружен ли файл
   if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
   {
        // Если файл загружен успешно, перемещаем его
     // из временной директории в конечную
     move_uploaded_file($_FILES["filename"]["tmp_name"], "files/".$_FILES["filename"]["name"]);
   } else {
       echo '<br><br><br><b>Ошибка!</b><br><u> Не указан файл для загрузки. Сначала выберите Excel файл, а потом нажмите на кнопку "Загрузить"</u>';
	  echo '<meta http-equiv="refresh" content="20">';
	 
   }
  include_once 'excel_in.php'; 
   
   }
?>

<html>
<body>
 <form name="download" method="post" action="">
 <p><input type="submit" value="Скачать файл в Excel формате" name="download">
   </form>
  <form name="upload" method="post" action="" enctype="multipart/form-data">
 <p>
  <input type="file" name="filename"><br><br> 
	  После нажатия на кнопку "Загрузить" будет обработан Excel файл<br><br>
      <input type="submit" value="Загрузить" name="upload">
 </form>
</body>
</html>

