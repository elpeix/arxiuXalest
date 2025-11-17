<?php

define('APP_NAME', 'Arxiu Xalest');

// /!\ WARNING - Never upload valid credentials!!!
define('DB_HOST','db');
define('DB_USER','root');
define('DB_PASS','secret');
define('DB_NAME','arxiu');
define('DB_PREFIX','');

define('DEBUG', true);

define('MAX_PER_PAGE', 1000);

define('DEFAULT_LANGUAGE', 'ca');

define('DEVEL', FALSE);

define('ROOT_PATH', __DIR__ . '/../..');
define('SRC_PATH', ROOT_PATH . '/src');
define('TEMPLATES_PATH', SRC_PATH .'/templates');
define('LANGUAGES_PATH', SRC_PATH .'/lang');
define('APP_FILTER_AND', '$');
define('APP_FILTER_OR', '|');
define('APP_FILTER_LIKE', '__icontains');
define('APP_ITEMS_KEY', 'items');

// Minimum eight characters, at least one letter and one number. Can contains special characters
define('PASSWORD_VALIDATOR', '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d\{\}@$!%*?¡¿&,:;\/\\\+\-\.\[\]]{8,}$/');
define('PASSWORD_VALIDATOR_MESSAGE', 'Minimum eight characters, at least one letter and one number.');

define('SQL_DATE_FORMAT', 'Y-m-d H:i:s');
define('SESSION_PATH', "/tmp");