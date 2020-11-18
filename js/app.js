document.addEventListener('click', function () {
    let elem = document.activeElement;

    switch (elem.id) {
        case 'actorSearch':
            changeSearchMethod(elem.value);
            break;

        case 'movieSearch':
            changeSearchMethod(elem.value);
            break;

        case 'addMovieRadio' :
            changeAddMethod(elem.id);
            break;

        case 'addFileRadio' :
            changeAddMethod(elem.id);
            break;

        case 'showHelper':
            openHelpBox(elem.value);
            break;

        case 'closeHelper':
            closeHelpBox(elem.value);
            break;

        case 'deleteItem' :
            prepareToDelete(elem.value);
            break;

        case 'cancelDelete':
            cancelDeleteMovie();
            break;

        case 'deleteIt':
            event.preventDefault();
            deleteMovie(elem.value);
            break;
    }

});

document.addEventListener('submit', function () {
    let elem = document.activeElement;

    switch (elem.id) {
        case 'sendFile':
            event.preventDefault();
            sendFile();
            break;

        case 'sendMovie':
            event.preventDefault();
            sendMovie();
            break;
    }

});

function changeSearchMethod(value) {
    let titleElem = document.getElementById('searchTitle');
    let input = document.getElementById('searchInput');

    switch (value) {
        case 'actors':
            titleElem.innerText = 'Поиск по актеру:';
            input.placeholder = 'Имя актера';
            break;

        case 'movies':
            titleElem.innerText = 'Поиск по названию:';
            input.placeholder = 'Название фильма';
            break;
    }
}

function changeAddMethod(chooseType) {
    let fileForm = document.getElementById('addFile');
    let movieForm = document.getElementById('addMovie');

    switch (chooseType) {
        case 'addMovieRadio':
            fileForm.style.display = 'none';
            movieForm.style.display = 'flex';
            break;
        case 'addFileRadio':
            fileForm.style.display = 'flex';
            movieForm.style.display = 'none';
            break;
    }
}

function openHelpBox(id) {
    document.getElementById(id).style.display = 'flex';
}

function closeHelpBox(id) {
    document.getElementById(id).style.display = 'none';
}

function sendMovie() {
    let dataFromForm = $("#addMovie").serializeArray();
    let dataToSend = {movies: {}, actors: {}};

    for (let i = 0; i < dataFromForm.length; i++) {

        switch (dataFromForm[i]['name']) {
            case 'actors':
                dataToSend[dataFromForm[i]['name']]['name'] = [dataFromForm[i]['value']];
                break;

            case 'format':
                if (dataFromForm[i]['value']) {
                    dataToSend['movies'][dataFromForm[i]['name']] = [dataFromForm[i]['value']];
                }
                break;

            case 'release_year':
                if (dataFromForm[i]['value']) {
                    dataToSend['movies'][dataFromForm[i]['name']] = [dataFromForm[i]['value']];
                }
                break;

            case 'name':
                dataToSend['movies'][dataFromForm[i]['name']] = [dataFromForm[i]['value']];
                break;
        }
    }

    $.ajax({
        type:'POST',
        url: "/movies/AddItem",
        data:{
            data: dataToSend
        },

        success:function(data){
            successMovieAjax(data);
        },

        error:function(errData) {
            errorAjax(errData);
        }
    });
}

function sendFile() {
    let $input = $("#file");
    let fd = new FormData;

    fd.append('text', $input.prop('files')[0]);

    $.ajax({
        url: "/movies/AddMoviesFromFile",
        data: fd,
        processData: false,
        contentType: false,
        type: 'POST',

        success:function(data){
            successFileAjax(data);
        },

        error:function(errData) {
            errorAjax(errData);
        }
    });
}

function successMovieAjax(data) {
    let successDiv = $('.success-div');
    let errorDiv = $('.error-div');
    let message = '';
    data = JSON.parse(data);
    console.log(data);

    for (let key in data) {
        message += '<p>' + data[key] + '</p>';
    }

    errorDiv.css('display', 'none');
    successDiv.css('display', 'flex');
    successDiv.html(message);
}

function successFileAjax(data) {
    data = JSON.parse(data);

    let successDiv = $('.success-div');
    let errorDiv = $('.error-div');
    let message = '';
    let added = '<p><strong>Были добалены в базу:</strong> ';
    let disallow = '<p><strong>Данные фильмы уже находятся в базе:</strong> ';

    for (let key in data['message']) {
        for (let i = 0; i <= data['message'][key].length - 1; i++) {
            switch (key) {
                case 'added':
                    added += data['message'][key][i];
                    if (i !== data['message'][key].length - 1) {
                        added += ', '
                    }
                    break;

                case 'disallow':
                    disallow += data['message'][key][i];
                    if (i !== data['message'][key].length - 1) {
                        disallow += ', '
                    }
                    break;
            }
        }
    }

    added += '</p>\n';
    disallow += '</p>\n';

    message = added + disallow;
    message += '<p><strong>Если в списке отсутствует какой-то из фильмов, добавленный Вами, значит вы ошиблись при заполнении. Отформатируйте шаблон по примеру и попробуйте еще раз!</strong></p>';

    errorDiv.css('display', 'none');
    successDiv.css('display', 'flex');
    successDiv.html(message);
}

function errorAjax(errData) {
    let successDiv = $('.success-div');
    let errorDiv = $('.error-div');
    let data = JSON.parse(errData.responseText);
    let message = '';

    for (let key in data) {
        message += '<p>' + data[key] + '</p>';
    }

    successDiv.css('display', 'none');
    errorDiv.css('display', 'flex');
    errorDiv.html(message);
}

function prepareToDelete(itemName) {
    let deleteBox = document.getElementById('deleteBox');
    let deleteBG = document.getElementById('deleteBG');
    let message = '';

    message += '<p>Вы действительно хотите удалить фильм "'+ itemName +"\"?</p>\n";
    message += '<div class="delete-button-box"><button class="delete-buttons-in-box" id="deleteIt" value="'+ itemName +'">Да</button>';
    message += '<button class="delete-buttons-in-box" id="cancelDelete" value="delete-bg-box">Нет</button></div>';

    deleteBox.innerHTML = message;
    deleteBG.style.display = 'flex';
}

function cancelDeleteMovie() {
    document.getElementById('deleteBG').style.display = 'none';
}

function deleteMovie(movieName) {
    let deleteBox = document.getElementById('deleteBox');
    let deleteErrorDiv = document.getElementById('deleteErrorDiv');
    let message = '';
    let data = movieName;

    $.ajax({
        type:'post',
        url: "/movies/Delete",
        data:{
            name: movieName
        },

        success:function(data){
            data = JSON.parse(data);
            deleteErrorDiv.style.display = 'none';
            message += '<p>'+data['deleteSuccess']+'</p>\n';
            message += '<p>Страница перезагрузится через 3 секунды</p>';
            deleteBox.style.color = 'green';
            deleteBox.innerHTML = message;

            setTimeout(function() {
                location.reload(true);
            }, 3000);
        },

        error:function(errData) {
            data = JSON.parse(errData.responseText);
            deleteErrorDiv.style.display = 'flex';
            message = data['deleteErrors'];
            deleteErrorDiv.innerText = message;
        }
    });
}
