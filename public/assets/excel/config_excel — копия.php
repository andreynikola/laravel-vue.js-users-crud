<?php
/* Самооценка*/
$start_ex = "A";  //буква первого столбца
$end_ex = "N";   // буква последнего столбца

/* Названия столбцов */
$name_column[1] = "EHS №" ;
$name_column[2] = "Виды работ" ;
$name_column[3] = "Необходимые мероприятия" ;
$name_column[4] = "Тип требования по срокам" ;
$name_column[5] = "Статус соответствия" ;
$name_column[6] = "Фамилия, имя ответственного за выполнение" ;
$name_column[7] = "Плановый Срок приведения в соответстие" ;
$name_column[8] = "Мероприятие" ;
$name_column[9] = "Пункт нормативного правового акта" ;
$name_column[10] = "Источник информации" ;
$name_column[11] = "Наименование НПА" ;
$name_column[12] = "Номер и дата утверждения" ;
$name_column[13] = "Текст матрицы актуализирован на соответствие оригинальному документу (дата)" ;
$name_column[14] = "ID";

/* Ширина столбцов от начвала до конца таблицы */
$column_width = array(10, 18, 58, 25, 14 , 17, 13, 24, 11, 14, 30, 13, 16, 0);

/*Названия статуса соответсвия*/
$stats_match[0] = "N/A"; 
$stats_match[1] = "Менее 40%";
$stats_match[2] = "От 40% до 70%";
$stats_match[3] = "От 71% до 90% ";
$stats_match[4] = "Более 90%";

/* Вставляемые подсказки и комментарии для таблицы в excel*/
$xml_title1 = "Соответствие:"; // Оглавление подсказки при выборе статуса соответсвия
$xml_title2 = "Выберите из списка нужное значение";

/* --------------------------------------------------------*/

/* Календарь*/
$start_ex_cal = "A";  //буква первого столбца
$end_ex_cal = "L";   // буква последнего столбца

/* Названия столбцов в Календаре */
$name_column_cal[1] = "EHS №" ;
$name_column_cal[2] = "Виды работ" ;
$name_column_cal[3] = "Необходимые мероприятия" ;
$name_column_cal[4] = "Тип требования по срокам" ;
$name_column_cal[5] = "Статус соответствия" ;
$name_column_cal[6] = "Фамилия, имя ответственного за выполнение" ;
$name_column_cal[7] = "Плановый Срок приведения в соответстие" ;
$name_column_cal[8] = "Мероприятие" ;
$name_column_cal[9] = "Фактический срок приведения в соответстие" ;
$name_column_cal[10] = "Текущий статус соответствия	" ;
$name_column_cal[11] = "Причина не выполнения" ;
$name_column_cal[12] = "ID" ;
/* Ширина столбцов от начвала до конца таблицы */
$column_width_cal = array(10, 18, 50, 25, 14 , 17, 13, 24, 13, 14, 24, 0);

/* Вставляемые подсказки и комментарии для таблицы в excel*/
$xml_title1 = "Соответствие:"; // Оглавление подсказки при выборе статуса соответсвия
$xml_title2 = "Выберите из списка нужное значение";

/* Каталог для загрузки xml файлов */

$path_file_excel = $_SERVER['DOCUMENT_ROOT']."/assets/excel/files/";
$maxsize = 10485760; // Максимальный размер загружаемого файла в байтах
?>