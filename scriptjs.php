<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 04.07.2019
 * Time: 22:08
 */


$func = '';

if (isset($_GET['func']) and $_GET['func']!=''){
    //$modul = strip_tags($_GET['func']);
    //$modul = htmlspecialchars($modul, ENT_QUOTES);
    $modul = preg_match("#^[a-z]+$#",$_GET['func']) ? $_GET['func'] : 'errorfunc';

}else{
    exit ('Ошибка в Get запросе. scriptjs.php');
}
include_once(dirname(__FILE__).'/config.php');
$content = new Content;
echo $content->showmecontent($modul);


?>
