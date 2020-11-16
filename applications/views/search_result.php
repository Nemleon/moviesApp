<form class="search-form" action="/search/result" method="get" id="searchField">
    <div class="search-type-div">
        <span><strong>Выберите способ поиска: </strong></span>
        <label><input type="radio" name="type" value="actors" id="actorSearch">По актеру</label>
        <label><input type="radio" name="type" value="movies" id class="movieSearch" checked>По названию фильма</label>
    </div>
    <div class="search-input-div">
        <label for="searchInput" id="searchTitle">Поиск по названию:</label>
        <input class="search-input-text" type="search" name="name[]" placeholder="Название фильма" id="searchInput">
        <input type="submit" value="Найти фильм!" id="goSearch">
    </div>
</form>
<?php if (isset($errors)) { ?>
    <div class="error-div" style="display: flex">
        <p><?= $errors ?></p>
    </div>
<?php } ?>

<?php if (isset($movies)) {
    foreach ($movies as $movie) { ?>
        <div class="item">
            <button class="delete-button" id="deleteItem" value="<?=$movie->name?>">X</button>
            <p>Название: <a href="/movies/item?name=<?= $movie->name ?>"><?= $movie->name ?></a></p>
            <?php if ($movie->release_year == 0) { ?>
                <p>Год релиза: Неизвестен</p>
            <?php }else { ?>
                <p>Год релиза: <?= $movie->release_year ?></p>
            <?php } ?>
        </div>
    <?php }
} ?>
<div class="delete-bg-box" id="deleteBG">
    <div class="delete-item-box">
        <div class="error-div" id="deleteErrorDiv"></div>
        <div class="delete-item-text" id="deleteBox"></div>
    </div>
</div>

