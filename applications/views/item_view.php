<?php if (isset($errors)) { ?>
    <div class="error-div" style="display: flex">
        <p><?= $errors ?></p>
    </div>
<?php } ?>

<?php if (isset($film)) { ?>
    <div class="item">
        <button class="delete-button" id="deleteItem" value="<?=$film->name?>">X</button>
        <p>Название: <a href="/movies/item?name=<?= $film->name ?>"><?= $film->name ?></a></p>
        <p>Формат записи: <?= $film->format ?></p>
        <?php if ($film->release_year == 0) { ?>
            <p>Год релиза: Неизвестен</p>
        <?php }else { ?>
            <p>Год релиза: <?= $film->release_year ?></p>
        <?php } ?>
        <p style="text-align: start">Актеры: <?= $film->actors ?></p>
    </div>
<?php } ?>

<div class="delete-bg-box" id="deleteBG">
    <div class="delete-item-box">
        <div class="error-div" id="deleteErrorDiv"></div>
        <div class="delete-item-text" id="deleteBox"></div>
    </div>
</div>
