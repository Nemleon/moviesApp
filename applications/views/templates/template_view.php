<!DOCTYPE HTML>
<html lang='ru'>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src="/js/app.js"></script>
    <title><?= $title ?></title>
</head>
<body>
<main>
    <header class="header">
        <a class="header-link" href="/movies">Главная/Все фильмы</a>
        <a class="header-link" href="/movies/add">Добавить фильм</a>
        <a class="header-link" href="/search">Поиск фильмов</a>
    </header>
    <div class="main-content">
        <?= $contentView ?>
    </div>
</main>
</body>
</html>