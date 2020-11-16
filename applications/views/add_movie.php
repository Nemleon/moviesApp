<div class="error-div"></div>
<div class="success-div"></div>
<form>
    <label><input type="radio" name="addType" id="addMovieRadio" checked>Добавить фильм</label>
    <label><input type="radio" name="addType" id="addFileRadio">Добавить фильмы файлом</label>
</form>

<form class="movie-add-form" action="/movies/additem" method="post" id="addMovie">
    <label class="input-label"><span>Название фильма</span><input type="text" name="name"></label>
    <label class="input-label"><span>Формат записи</span><input type="text" name="format"></label>
    <label class="input-label"><span>Год релиза</span><input type="text" name="release_year"></label>
    <label class="input-label"><span>Актеры (через запятую)</span><input type="text" name="actors"></label>
    <input type="submit" value="Добавить фильм" id="sendMovie">
</form>

<form class="file-add-form" action="/movies/addmoviesfromfile" method="post" enctype="multipart/form-data" id="addFile">
    <p>Перед использованием данной функции, прочитайте инструкцию <button type="button" id="showHelper" value="addHelper">Читать</button></p>
    <p>Так же необходимо скачать шаблон <a href="/others/examples/example.txt" download>Скачать шаблон (ссылка)</a></p>
    <p>Загрузите файл с фильмами</p>
    <input type="hidden" name="MAX_FILE_SIZE" value="30000">
    <input type="file" name="text" id="file">
    <input type="submit" value="Отправить файл" id="sendFile">
</form>

<div class="help-box-bg" id="addHelper">
    <button type="button" class="close-helper-button" id="closeHelper" value="addHelper">Х</button>
    <div class="file-add-help-box">
        <h3>Важная информация!</h3>
        <p>Чтобы успешно добавить фильмы с файла, необходимо <a href="/others/examples/example.txt" download>скачать шаблон</a> и следовать иснтрукциям:</p>
        <ul>
            <li>Копируйте текст шаблона для каждого фильма, заменяя примеры на свою информацию</li>
            <li>Между фильмами, необходимы минимум! два переноса строки (нажать на Enter 2 раза)</li>
            <li>Не изменяйте ключевые слова (Title: Release Year: Format: Stars: ). Никак. В том числе двоеточия. В противном случае, информация затеряется</li>
            <li>Актеров вписывайте только через запятую, не используя символы переноса строки и пр.</li>
        </ul>
        <p>После наполнения файла, загрузите его на сервер, нажмите отправить файл и вуаля - все готово!</p>
        <p>Из важной инфомарции все. Да прибудет с Вами сила!</p>
        <p><a href="/others/examples/example.txt" download>Скачать шаблон (ссылка)</a></p>
    </div>
</div>
