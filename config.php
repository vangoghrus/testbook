<?php

define("DB_HOST","localhost");
define("DB_LOGIN","php");
define("DB_PASSWORD","12345");
define("DB_NAME","book_imysite");
define("ADM_PAS","vano1234");

class DBConn{
    //создаем только одну точку подключения к БД
    private 	   $_db;
    static private $_instance = null;

    private function __construct(){
        $this->_db = new MySQLi(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_NAME);
        $this->_db->set_charset("utf8");
        /* далее для отладки*/
        if($this->_db->connect_errno) {
            echo "Не удалось подключиться к MySQL: " . $this->_db->connect_error;}
    }
    private function __clone(){ echo 'клонировать класс запрещено';}
    static function getInstance(){
        if(self::$_instance == null){
            self::$_instance = new DBConn();
        }
        //return self::$_instance;
        return self::$_instance->_db;
    }
}

class Content{


/*------книги------*/
    private function show_list_book(){
        //выдасть список книг
        $TextHtml = '<div >
            <p align="right"><a id="book_add_link" href="#" >Добавить книгу</a></p>
            <div id="book_add_form" class="add_form"> Книга: <input type="text" name="Textbook" id="TextbookId" size="20" maxlength="255" value="" > 
            <input id="SubmitbookId" type="button" name="Submitbook" value="Добавить"></div>
            </div>';

        $db = DBConn::getInstance(); // подключим бд
        //запрос где объеденяем все данные в одну таблицу, что бы отдельно не делать запросы по авторам книг
        $query = 'SELECT list_book.id AS id, book.id AS id_book, list_book.id_author AS id_author, author.author_name AS author_name, book.title AS title_book FROM
       list_book JOIN author ON list_book.id_author = author.id
       RIGHT OUTER JOIN book ON list_book.id_book = book.id ORDER BY book.title ASC';
        if ( !($e = $db->query($query) ) ) {echo '--SQl error запрос списка книг - '. $db->error;
        }else{
            $nowbook_id = 0;
            while($row = $e->fetch_assoc()){
                //ПРОВЕРИТЬ ВСЕ ИД !!!!!!!!!!!!!!!!
                if ($row['id_book'] == $nowbook_id){
                    //попали сюда значит, эта книга уже была и нужно вывести с row только её автора
                    $TextHtml .= '<span class="book_del_author">'. $row['author_name'].'<a class="book_author_del" metadata="'.$row['id'].'" title="Удалить" href="#"> X </a></span>';

                }else{
                    // попали сюда, значит книга встретилась первый раз
                    if ($nowbook_id != 0) $TextHtml .= '<span id="book_add_author'.$nowbook_id.'"><a class="book_add_author" metadata="'.$nowbook_id.'" title="Добавить автора книги" href="#">+</a>
<span id="blokforaddauthor'.$nowbook_id.'"></span> </span></div>';

                    $TextHtml .= '
                    <div id="book_'.$row['id_book'].'" metadata="'.$row['id_book'].'" class="book_line">
<p><span class="book" id="bookname'.$row['id_book'].'">'. $row['title_book']. '</span> <span id="blokforeditbook'.$row['id_book'].'"></span>
<a class="book_edit" metadata="'.$row['id_book'].'" href="#"> Редактировать</a> 
<a class="book_del" metadata="'.$row['id_book'].'" href="#"> Удалить</a>
</p>';
                    if ($row['id_author'] != NULL){
                        $TextHtml .= '<span class="book_del_author">'. $row['author_name'].'<a class="book_author_del" metadata="'.$row['id'].'" title="Удалить" href="#"> X </a></span>';

                    }
                    $nowbook_id = $row['id_book'];
                }
            }
            $TextHtml .= '<span id="book_add_author'.$nowbook_id.'"><a class="book_add_author" metadata="'.$nowbook_id.'" title="Добавить автора книги" href="#">+</a>
<span id="blokforaddauthor'.$nowbook_id.'"></span> </span></div>';
        }
        $e->close();
        return $TextHtml;
    }

    private function showlistauthorbook(){
        $idBook = 0;
        if ( ( (int)$_GET['idbook'] ) > 0){ $idBook = (int)$_GET['idbook'];} else {exit('отсутствует id книги');}

        $data = null;
        $db = DBConn::getInstance(); // подключим бд
        $query = 'SELECT * FROM author WHERE NOT author.id IN (SELECT list_book.id_author FROM list_book WHERE list_book.id_book = '.$idBook.')';
        if ( !($e = $db->query($query) ) ) {echo '--SQl error запрос аворов - '. $db->error;
        }else{
            $data = $e->fetch_all(MYSQLI_ASSOC);
            return json_encode( $data);
        }
        //вернем ответ
        return '0';
    }

    private function addauthorforbook()
    {
        //проверим данные
        if ( (int)$_POST['idbook']>0 && (int)$_POST['idauthor']>0)
        {
            $idBook = (int)$_POST['idbook'];
            $idAuthor = (int)$_POST['idauthor'];
        }else{
            exit ("Проверте входные данные.");
        }
        $db = DBConn::getInstance(); // подключим бд
        /* создаем подготавливаемый запрос */
        $query = 'INSERT INTO list_book (id_author, id_book) VALUES (?, ?)';
        if ($otvet = $db->prepare($query)){
            /* связываем параметры с метками */
            $otvet->bind_param('ii',$idAuthor, $idBook); // s Остальные типы. i Все INT типы. d DOUBLE и FLOAT. b BLOB'ы
            /* запускаем запрос */
            $otvet->execute();
            /* закрываем запрос */
            $otvet->close();
            //вернем ответ 1 значит все ОК
            return '1';
        }

        //вернем ответ
        return '0';
    }

    private function delauthorforbook(){
        if (isset($_POST['idlist_book']) and $_POST['idlist_book']>0)
        {
            $IdBookAuthor = (int)$_POST['idlist_book'];
        }else{
            exit ("Проверте входные данные. Нужно число");
        }
        $db = DBConn::getInstance(); // подключим бд
        /* создаем подготавливаемый запрос */
        $query = 'DELETE FROM list_book WHERE id=? LIMIT 1';
        if ($otvet = $db->prepare($query)){
            /* связываем параметры с метками */
            $otvet->bind_param('i',$IdBookAuthor); // s Остальные типы. i Все INT типы. d DOUBLE и FLOAT. b BLOB'ы
            /* запускаем запрос */
            $otvet->execute();
            /* закрываем запрос */
            $otvet->close();
            //вернем ответ 1 значит все ОК
            return '1';
        }
        return '0';
    }

    private function addbook()
    {
        //проверим данные
        if (isset($_POST['namebook']) and preg_match("#^[а-я\w\s]+$#iu",$_POST['namebook']))
        {
            $NameBook = $_POST['namebook'];
        }else{
            exit ("Проверте входные данные. Имя книги должно седержать только буквы и цифры, пробелы");
        }
        $db = DBConn::getInstance(); // подключим бд
        /* создаем подготавливаемый запрос */
        $query = 'INSERT INTO book (title) VALUES (?)';
        if ($otvet = $db->prepare($query)){
            /* связываем параметры с метками */
            $otvet->bind_param('s',$NameBook); // s Остальные типы. i Все INT типы. d DOUBLE и FLOAT. b BLOB'ы
            /* запускаем запрос */
            $otvet->execute();
            /* закрываем запрос */
            $otvet->close();
            //вернем ответ 1 значит все ОК
            return '1';
        }

        //вернем ответ
        return '0';
    }

    private function delbook(){
        if (isset($_POST['idbook']) and $_POST['idbook']>0)
        {
            $IdBook = (int)$_POST['idbook'];
        }else{
            exit ("Проверте входные данные. Нужно число");
        }
        $db = DBConn::getInstance(); // подключим бд
        /* создаем подготавливаемый запрос */
        $query = 'DELETE FROM book WHERE id=?';
        if ($otvet = $db->prepare($query)){
            /* связываем параметры с метками */
            $otvet->bind_param('s',$IdBook); // s Остальные типы. i Все INT типы. d DOUBLE и FLOAT. b BLOB'ы
            /* запускаем запрос */
            $otvet->execute();
            /* закрываем запрос */
            $otvet->close();
            //вернем ответ 1 значит все ОК
            return '1';
        }
        return '0';
    }

    private function editbook(){
        if (isset($_POST['idbook']) and $_POST['idbook']>0 and !empty($_POST['newnamebook']) and preg_match("#^[а-я\w\s]+$#iu",$_POST['newnamebook']))
        {
            $IdBook = (int)$_POST['idbook'];
            $NewName = $_POST['newnamebook'];
        }else{
            exit ("Проверте входные данные. ");
        }
        $db = DBConn::getInstance(); // подключим бд
        /* создаем подготавливаемый запрос */
        $query = 'UPDATE book SET title = ? WHERE id=? LIMIT 1';
        if ($otvet = $db->prepare($query)){
            /* связываем параметры с метками */
            $otvet->bind_param('si',$NewName, $IdBook); // s Остальные типы. i Все INT типы. d DOUBLE и FLOAT. b BLOB'ы
            /* запускаем запрос */
            $otvet->execute();
            /* закрываем запрос */
            $otvet->close();
            //вернем ответ 1 значит все ОК
            return '1';
        }
        return '0';
    }


/*---- авторы-----*/
    private function show_list_author(){
        //выдать список авторов
        $TextHtml = '<div >
            <p align="right"><a id="author_add_link" href="#" >Добавить автора</a></p>
            <div id="author_add_form" class="add_form"> Автор: <input type="text" name="TextAuthor" id="TextAuthorId" size="20" maxlength="255" value="" > 
            <input id="SubmitAuthorId" type="button" name="SubmitAuthor" value="Добавить"></div>
            </div>';

        $db = DBConn::getInstance(); // подключим бд
        $query = 'SELECT * FROM author ORDER BY author_name ASC';
        if ( !($e = $db->query($query) ) ) {echo '--SQl error запрос списка аворов - '. $db->error;
        }else{
            while($row = $e->fetch_assoc()){
                $TextHtml .= '<div id="author_'.$row['id'].'" metadata="'.$row['id'].'" class="author_line"><span class="author">'.$row['author_name'].'</span> 
<a class="author_edit" metadata="'.$row['id'].'" href="#"> Редактировать</a> 
<a class="author_del" metadata="'.$row['id'].'" href="#"> Удалить</a></div>';
            }
        }
        $e->close();
        return $TextHtml;
    }

    private function addauthor(){
        //проверим данные
        if (isset($_POST['nameauthor']) and preg_match("#^[а-я\w\s]+$#iu",$_POST['nameauthor']))
        {
            $NameAuthor = $_POST['nameauthor'];
        }else{
            exit ("Проверте входные данные. Имя автора должно седержать только буквы и цифры, пробелы");
        }
        $db = DBConn::getInstance(); // подключим бд
        /* создаем подготавливаемый запрос */
        $query = 'INSERT INTO author (author_name) VALUES (?)';
        if ($otvet = $db->prepare($query)){
            /* связываем параметры с метками */
            $otvet->bind_param('s',$NameAuthor); // s Остальные типы. i Все INT типы. d DOUBLE и FLOAT. b BLOB'ы
            /* запускаем запрос */
            $otvet->execute();
            /* закрываем запрос */
            $otvet->close();
            //вернем ответ 1 значит все ОК
            return '1';
        }

        //вернем ответ
        return '0';
    }

    private function delauthor(){
        if (isset($_POST['idauthor']) and $_POST['idauthor']>0)
        {
            $IdAuthor = (int)$_POST['idauthor'];
        }else{
            exit ("Проверте входные данные. Нужно число");
        }
        $db = DBConn::getInstance(); // подключим бд
        /* создаем подготавливаемый запрос */
        $query = 'DELETE FROM author WHERE id=?';
        if ($otvet = $db->prepare($query)){
            /* связываем параметры с метками */
            $otvet->bind_param('s',$IdAuthor); // s Остальные типы. i Все INT типы. d DOUBLE и FLOAT. b BLOB'ы
            /* запускаем запрос */
            $otvet->execute();
            /* закрываем запрос */
            $otvet->close();
            //вернем ответ 1 значит все ОК
            return '1';
        }
        return '0';
    }

    private function editauthor(){
        if (isset($_POST['idauthor']) and $_POST['idauthor']>0 and !empty($_POST['newnameauthor']) and preg_match("#^[а-я\w\s]+$#iu",$_POST['newnameauthor']))
        {
            $IdAuthor = (int)$_POST['idauthor'];
            $NewName = $_POST['newnameauthor'];
        }else{
            exit ("Проверте входные данные. ");
        }
        $db = DBConn::getInstance(); // подключим бд
        /* создаем подготавливаемый запрос */
        $query = 'UPDATE author SET author_name = ? WHERE id=? LIMIT 1';
        if ($otvet = $db->prepare($query)){
            /* связываем параметры с метками */
            $otvet->bind_param('si',$NewName, $IdAuthor); // s Остальные типы. i Все INT типы. d DOUBLE и FLOAT. b BLOB'ы
            /* запускаем запрос */
            $otvet->execute();
            /* закрываем запрос */
            $otvet->close();
            //вернем ответ 1 значит все ОК
            return '1';
        }
        return '0';
    }

    public function showmecontent($modul_content){

        switch ($modul_content) {
            case 'author':
                return $this->show_list_author();
                break;
            case 'book':
                return $this->show_list_book();
                break;
            case 'addauthor':
                return $this->addauthor();
                break;
            case 'delauthor':
                return $this->delauthor();
                break;
            case 'editauthor':
                return $this->editauthor();
                break;
            case 'addbook':
                 return $this->addbook();
                 break;
            case 'delbook':
                return $this->delbook();
                break;
            case 'editbook':
                return $this->editbook();
                break;
            case 'showlistauthorbook':
                return $this->showlistauthorbook();
                break;
            case 'addauthorforbook':
                return $this->addauthorforbook();
                break;
            case 'delauthorforbook':
                return $this->delauthorforbook();
                break;
            default:
                return "Нет информации для отображения";
        }
    }


}


?>