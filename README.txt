# Документация не полная. Успел написать то, что посчитал главным
#
#
#
# ЗАПУСК ПРИЛОЖЕНИЯ
#   
#   Скачать приложение
#   В корневой папке создать файл .htaccess и поместить туда содержимое файла htaccess.txt
#   Запустить локальный сервер и БД
#   Создать БД с любым названием, тип "InnoDB", кодировка "utf8_general_ci"
#   Импортировать таблицы из дампа (db_dump.sql) в созданную БД
#   Установить в файле "applications/models/DB/DB.php" параметры созданной БД, в приватные переменные (название бд, пароль, юзер и т.п.)
#   Всё, приложение готово к использованию!
#
#
#
# О приложении
#
#   Приложение написано на базе MVC паттерна
#   Все входящие запросы идут к index.php где подключается файл application/bootstrap.php .
#   Там находится автозагрузчик файлов и подключается роутер (applications/code/router.php), который редактирует URL запрос и подключает необходимые классы
#   Подключаемые классы определеяются по URL строке. Допустим, есть строка /movies/item?name=asd
#       movies - первая часть - всегда будет являться основным контроллером
#           Должен находиться в корневой папке контроллеров (здесь это applications/controllers/MoviesController)
#       item - вторая и последующие части - непосредственно экшн контроллен, который будет запускаться в основном
#           Можно поместить куда угодно. Путь, откуда он запускается, можно определить в основном контроллере (мой находится в applications/controllers/movies/ItemController)
#
#       Фраза "вторая часть и последующие" - означает, что если путь будет /movies/items/qwe/asd, то
#           movies - так же основной контроллер
#           items/qwe/ - путь к экшн контроллеру
#           asd - название экшн контроллера
#
#   applications/core/controller - кор контроллер, который непосредственно загружает экшн контроллер, путь к которому он получает из основного
#        и подключает вью класс applications/core/view . В метод view() подключаемого класса, передаются поключаемая вьюшка, название страницы,
#        код состояния, передаваемый браузеру и непосредственно Data - её может и не быть (не обязательный параметр).
#
#    При создании нового обьекта View('мой_темплейт.пхп'), можно передать путь к свему темплейту. Если этого не сделать, будет использоваться стандартный
#       из папки (applications/views/templates). Новые, кстати, тоже размещать здесь.
#       Вьюхи, куда будут передаваться данные, можно располагать как угодно в папке (applications/views/).
#       При их подключении, необходимо указывать полный путь от папки applications/views/
#
#    Все исключения перехватываются в файле applications/bootstrap и передаются напрямую в ErrorController
#       Туда передаются код и сообщение перехваченного исключения. По умолчанию, в браузере будет отображаться только код.
#       Для отладки можно включить отображение сообщений. Для этого необходиимо перейти в  applications/controllers/ErrorController
#       и в последний параметр передать закомметированную справа от метода переменную, вместо той, что стоит по умолчанию.
#       Перед этим рекомендую поменять размер шрифта в css, у класса .error-code , иначе сообщение растянется за экран
#
#
#
#   РАБОТА С БД
#
#   Для работы с бд, необходимо вызвать класс applications/models/DB/Models/Model (так запутанно, потому что совершил ошибку, а отрефакторить директорию не хватило времени)
#       В зависимости от целей, выбирайте необходимые методы, но, так как нету огранчений, чтобы не получить исключение на выходе, не меняйте местами логику выполнения
#       Т.е. не ставьте метод where() вперед select() или delete() (Да, так можно, потому что не успел разобраться, как можно ограничивать доступ к методам. Был вариант, но пришлось выбирать между роллбэками и им. Я выбрал роллбеки, поэтому директория получилась такая запутанная (та самая ошибка))
#
#   метод select()
#       Первый параметр - таблица('movies'), второй - элементы выборки ('*' or ('name, format' и т.п.)).
#       Условия по типу "DISTINCT" ставятся во второй параметр, перед выбираемыми элементами - ('DISTINCT name, format')
#
#   метод where()
#       первый параметр - название колонки, второй - логический оператор, последний - параметр, по которому будет происходить выборка
#       Последний параметр должен выглядесь так ['name'] => 'йцу'. Так, потому что выпоняется подготовленный запрос и name будет использоваться как плейсхолдер
#       Логический оператор between не поддерживается. Но его можно использовать в customRequest()
#
#   метод insert ()
#       первый параметр - таблица, второй - сами параметры
#       Вид второго параметра - [[name] => ['qwe', 'asd', 'zxc'], ['format'] => ['qwe', 'asd', 'zxc']] и т.п.
#       Ключи name и format являются колонками, куда будут записываться данные.
#       ВАЖНО! В каждом массиве должно быть одинаковое кол-во параметров. Если не выполнить это условие, произойдет фиаско
#
#   метод customRequest()
#       единственный параметр - весь запрос, начиная с любого экшена (SELECT ... и т.д.)
#
#
