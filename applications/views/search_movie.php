<form class="search-form" action="/search/result" method="get" id="searchField">
    <div class="search-type-div">
        <span><strong>Выберите способ поиска: </strong></span>
        <label><input type="radio" name="type" value="actors" id="actorSearch">По актеру</label>
        <label><input type="radio" name="type" value="movies" id="movieSearch" checked>По названию фильма</label>
    </div>
    <div class="search-input-div">
        <label for="searchInput" id="searchTitle">Поиск по названию:</label>
        <input class="search-input-text" type="search" name="name[]" placeholder="Название фильма" id="searchInput">
        <input type="submit" value="Найти фильм!" id="goSearch">
    </div>
</form>