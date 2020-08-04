// JavaScript Document
var thishost = '';
$( document ).ready(function() {

    //определим, что активна страница книг
    if( $('a').is('#book_add_link') ) {

        $('#book_add_link').click(function(){
            //отобразить или закрыть форму
            $('#book_add_form').toggle();
        });

        $('#SubmitbookId').click(function(){
            //проверим что поле не пустое и отправим запрос на добавление в БД
            if ($('#TextbookId').val() != ''){
                var NameBook = $('#TextbookId').val();
                $.ajax({
                    type: 'POST',
                    url: thishost+'scriptjs.php?func=addbook',
                    data: 'namebook='+NameBook,
                    success: function(data){
                        if(data=='1'){ window.location.replace(thishost+'index.php?modul=book&sys_msg=Книга добавлена');
                        }else{
                            $('#sys_msg').html('Неверно! Попробуйте снова, возможно вы ошиблись при вводе. '+data);
                            return false;
                        }
                    },
                    error: function(data){alert('not intenet conection');}
                });
            }else{
                alert('Введите имя книги.');
            }
        });

        $('.book_del').click(function(){
            if (confirm("Действительно удалить?") ){
                var NowId = $(this).attr("metadata");
                $.ajax({
                    type: 'POST',
                    url: thishost+'scriptjs.php?func=delbook',
                    data: 'idbook='+NowId,
                    success: function(data){
                        if(data=='1'){ window.location.replace(thishost+'index.php?modul=book&sys_msg=Книга удалена');
                        }else{
                            $('#sys_msg').html('Что то пошло не так. '+data);
                            return false;
                        }
                    },
                    error: function(data){alert('not intenet conection');}
                });
            }
            return false;
        });

        $('.book_edit').click(function(){

            var NowId = $(this).attr("metadata");
            //alert(NowId);
            var ThisBlockRow = $('#book_' + NowId);
            //var AutorHtmlRow = ThisBlockRow.html();
            var HTMLFormEdit = '<span id="book_edit_' + NowId + '">' +
                '<input type="text" name="TextBook" id="TextBookId' + NowId + '" size="20" maxlength="255" value="' + $(".book",ThisBlockRow).text() + '" > ' +
                '<input id="SubmitBookId" type="button" onclick="SendEditBook(' + NowId + ')"   name="SubmitBook" value="Изменить">' +
                '<input metadata="' + NowId + '" id="Cancel" type="button" onclick="CancelEditBook(' + NowId + ')" name="CancelBook" value="Отменить"></span>';
            //alert(HTMLFormEdit);
            $("#blokforeditbook"+NowId,ThisBlockRow).html(HTMLFormEdit);
            $(".book, .book_edit, .book_del",ThisBlockRow).hide();

            return false;
        });

        $('.book_add_author').click(function() {
            var NowId = $(this).attr("metadata");
            var blok = $('#book_' + NowId);
            $(".book_add_author",blok).hide();
            //alert(NowId);
            var html = '';
           $.getJSON(thishost + 'scriptjs.php?func=showlistauthorbook&idbook=' + NowId, function (datajson) {
               html += '<select id="selectauthor'+ NowId +'" onchange="changelist('+ NowId +')"><option value="0">Выбрать автора</option>';
               for (i = 0; i < datajson.length; i++) {
                   html += '<option value="'+ datajson[i].id +'">'+ datajson[i].author_name +'</option>';
               }
               html +='</select><input id="SubmitAddAuthorId'+ NowId +'" type="button" onclick="SendAddAuthor(' + NowId + ')"   name="SubmitIdAuthor" value="Добавить" disabled="disabled">' +
               '<input metadata="' + NowId + '" id="Cancel" type="button" onclick="CancelAddAuthor(' + NowId + ')" name="CancelAddAuthor" value="Отменить">';
               $('#blokforaddauthor' + NowId).html(html);
           });
        });

        $('.book_author_del').click(function() {
            var NowId = $(this).attr("metadata");
            $.ajax({
                type: 'POST',
                url: thishost+'scriptjs.php?func=delauthorforbook',
                data: 'idlist_book='+NowId,
                success: function(data){
                    if(data=='1'){ window.location.replace(thishost+'index.php?modul=book&sys_msg=Автор книги удален');
                    }else{
                        $('#sys_msg').html('Что то пошло не так. '+data);
                        return false;
                    }
                },
                error: function(data){alert('not intenet conection');}
            });
        });


    }//конец страиницы книги

	//определим, что активна страница авторов
    if( $('a').is('#author_add_link') ) {

        $('#author_add_link').click(function(){
            //отобразить или закрыть форму
            $('#author_add_form').toggle();
        });

        $('#SubmitAuthorId').click(function(){
            //проверим что поле не пустое и отправим запрос на добавление в БД
            if ($('#TextAuthorId').val() != ''){
                var NameAuthor = $('#TextAuthorId').val();
                $.ajax({
                    type: 'POST',
                    url: thishost+'scriptjs.php?func=addauthor',
                    data: 'nameauthor='+NameAuthor,
                    success: function(data){
                        if(data=='1'){ window.location.replace(thishost+'index.php?modul=author&sys_msg=Автор добавлен');
                        }else{
                            $('#sys_msg').html('Неверно! Попробуйте снова, возможно вы ошиблись при вводе. '+data);
                            return false;
                        }
                    },
                    error: function(data){alert('not intenet conection');}
                });
            }else{
                alert('Введите имя автора.');
            }
        });

        $('.author_del').click(function(){
            if (confirm("Действительно удалить?") ){
                var NowId = $(this).attr("metadata");
                $.ajax({
                    type: 'POST',
                    url: thishost+'scriptjs.php?func=delauthor',
                    data: 'idauthor='+NowId,
                    success: function(data){
                        if(data=='1'){ window.location.replace(thishost+'index.php?modul=author&sys_msg=Автор удален');
                        }else{
                            $('#sys_msg').html('Что то пошло не так. '+data);
                            return false;
                        }
                    },
                    error: function(data){alert('not intenet conection');}
                });
            }
            return false;
        });

        $('.author_edit').click(function(){

            var NowId = $(this).attr("metadata");
            //alert(NowId);
            var ThisBlockRow = $('#author_' + NowId);
            //var AutorHtmlRow = ThisBlockRow.html();
            var HTMLFormEdit = '<div id="author_edit_' + NowId + '"> Изменить:' +
                '<input type="text" name="TextAuthor" id="TextAuthorId' + NowId + '" size="20" maxlength="255" value="' + $(".author",ThisBlockRow).text() + '" > ' +
                '<input id="SubmitAuthorId" type="button" onclick="SendEditAuthor(' + NowId + ')"   name="SubmitAuthor" value="Изменить">' +
                '<input metadata="' + NowId + '" id="Cancel" type="button" onclick="CancelEditAuthor(' + NowId + ')" name="CancelAuthor" value="Отменить"></div>';
            //alert(HTMLFormEdit);
            ThisBlockRow.append(HTMLFormEdit);
            $("a",ThisBlockRow).hide();

            return false;
        });


    }//конец страница авторы

    //конец документ реди
});

function CancelEditAuthor(idAuthor){
    $('#author_edit_' + idAuthor).remove();
    $('#author_' + idAuthor + ' a').show();
}

function CancelEditBook(idBook){
    var ThisBlockRow = $('#book_' + idBook);
    $(".book, .book_edit, .book_del",ThisBlockRow).show();
    $("#blokforeditbook"+ idBook,ThisBlockRow).empty();
}

function CancelAddAuthor(idBook){
    var ThisBlockRow = $('#book_' + idBook);
    $(".book_add_author",ThisBlockRow).show();
    $("#blokforaddauthor"+ idBook,ThisBlockRow).empty();
}

function SendEditAuthor(idAuthor){
    if( $('#TextAuthorId' + idAuthor).val().length <= 0){
        alert ('Введите имя автора');
        return false;
    }
    $.ajax({
        type: 'POST',
        url: thishost+'scriptjs.php?func=editauthor',
        data: 'idauthor='+idAuthor+'&newnameauthor='+ $('#TextAuthorId' + idAuthor).val(),
        success: function(data){
            if(data=='1'){ window.location.replace(thishost+'index.php?modul=author&sys_msg=Автор отредактирован');
            }else{
                $('#sys_msg').html('Что то пошло не так. '+data);
                return false;
            }
        },
        error: function(data){alert('not intenet conection');}
    });
    $('#author_edit_' + idAuthor).remove();
    $('#author_' + idAuthor + ' a').show();
}

function SendEditBook(idBook){
    if( $('#TextBookId' + idBook).val().length <= 0){
        alert ('Введите имя книги');
        return false;
    }
    $.ajax({
        type: 'POST',
        url: thishost+'scriptjs.php?func=editbook',
        data: 'idbook='+idBook+'&newnamebook='+ $('#TextBookId' + idBook).val(),
        success: function(data){
            if(data=='1'){ window.location.replace(thishost+'index.php?modul=book&sys_msg=Книга отредактирована');
            }else{
                $('#sys_msg').html('Что то пошло не так. '+data);
                return false;
            }
        },
        error: function(data){alert('not intenet conection');}
    });
    //$('#author_edit_' + idBook).remove();
    //$('#author_' + idBook + ' a').show();
}

function changelist(idlist) {
    if ($("#selectauthor"+ idlist).val() > 0){
        $("#SubmitAddAuthorId"+ idlist).attr("disabled",false);

    }else{
        $("#SubmitAddAuthorId"+ idlist).attr("disabled",true);
    }
}

function SendAddAuthor(idBook){
    idAuthor = $("#selectauthor" + idBook).val();

    $.ajax({
        type: 'POST',
        url: thishost+'scriptjs.php?func=addauthorforbook',
        data: 'idauthor='+idAuthor+'&idbook='+ idBook,
        success: function(data){
            if(data=='1'){ window.location.replace(thishost+'index.php?modul=book&sys_msg=Автор книги добавлен');
            }else{
                $('#sys_msg').html('Что то пошло не так. '+data);
                return false;
            }
        },
        error: function(data){alert('not intenet conection');}
    });
    $('#author_edit_' + idAuthor).remove();
    $('#author_' + idAuthor + ' a').show();
}