<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 09.06.2019
 * Time: 15:56
 */

include_once("config.php");
if(isset($_GET['modul']) && $_GET['modul']==='author') $modul='author'; else $modul='book';

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Тестовое задание - Контакт центр</title>
<meta name="description" content="">
<meta name="keywords" content="">
<link href="style/style.css" rel="stylesheet" type="text/css">
<script src="js/jquery-3.4.1.min.js"></script>
<script src="js/js_site.js"></script>

</head>
<body>
<div id="menu">
    <a  href="index.php?modul=book">Книги</a>
    <a href="index.php?modul=author">Авторы</a>
</div>
<div id="sys_msg">
    <!--для отладки -->
    <?php echo @$_GET['sys_msg']; ?>
</div>
<div id="content">
<?php

   $content = new Content;
   echo $content->showmecontent($modul);


?>
</div>
</body>
</html>