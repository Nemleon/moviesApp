<?php if (isset($errors)) { ?>
    <div class="error-div" style="display: flex">
        <p><?= $errors ?></p>
    </div>
<?php } ?>

<?php if (isset($films)) {
    foreach ($films as $movie) { ?>
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


