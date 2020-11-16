<?php if (isset($errData)) { ?>
    <div class="error-box">
        <div class="error-code">
            <?= $errData ?>
        </div>
        <div class="errors-link">
            <a href="/">Вернуться на главную</a>
            <a href="/search">Поиск</a>
            <a href="/movies">К фильмам</a>
        </div>

    </div>

<?php } ?>
