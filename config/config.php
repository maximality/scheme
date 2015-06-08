<?php
/** The name of the database */
define('DB_NAME', 'u0067206_building');

/** MySQL database username */
define('DB_USER', 'u0067_user');

/** MySQL database password */
define('DB_PASSWORD', 'J4d038S1zuH5QgY');

/** MySQL hostname */
define('DB_HOST', '127.0.0.1');

/** MySQL table prefix_ */
define('DB_PREFIX_', "cs_");

/** режим отладки */
define('DEBUG_MODE', false);

/** использовать кеш */
define('USE_CACHE', false);

/**
 * тип кеша, если auto, то будет выбран лучший из доступных и настроеный (memcache)
 */
define('CACHE_TYPE', 'auto');

/** директория для хранения файлового кеша */
define('CACHE_DIR', dirname(dirname(__FILE__))."/cache/");

/** директория для хранения связей тегов с ключами кэша */
define('CACHE_TAG_DIR', 'tag_storage/');

/** префикс кеша */
define('CACHE_PREFIX', "engine_");

/** соль, уникальная для каждого сайта */
define('SALT', '62Hdf;lh&568');

/** директория для хранения изображений */
define('ROOT_DIR_IMAGES', dirname(dirname(__FILE__))."/img/");

/** директория для хранения изображений */
define('ROOT_DIR_FILES', dirname(dirname(__FILE__))."/files/");

/** директория для хранения ceрвисных файлов */
define("ROOT_DIR_SERVICE", dirname(dirname(__FILE__))."/admin/service/");

/** директория для хранения изображений */
define('URL_IMAGES', "img/");

/** директория для хранения файлов */
define('URL_FILES', "files/");

/** обрабатывать изображения с помощью Imagick? Иначе GD */
define('USE_IMAGICK', true);

/** директория и файл для Яндекс.Маркет */
define('YML_FILE', dirname(dirname(__FILE__))."/files/yamarket.xml");

/** обработка ошибок в режиме разработки **/
define("ERROR_HANDLER_DEVELOPMENT", true);

/**Сервер разработчиков **/
define("DEVELOPMENT_SERVER_ADDRESS", "http://".$_SERVER['HTTP_HOST']."/ajax/reciever_fict.php");

/**
 * Настройка memcache
 */
$CONFIG['memcache']['servers'][0]['host'] = 'localhost';
$CONFIG['memcache']['servers'][0]['port'] = '11211';
$CONFIG['memcache']['servers'][0]['persistent'] = true;
$CONFIG['memcache']['compression'] = true;

$CONFIG['product_statuses'] = array("Доступен к заказу", "По запросу", "Снят с производства", "В разработке", "Поставка прекращена");