# YandexXml

Пакет для работы с поисковым сервисом Яндекс.XML.

1) Установка
----------------------------------

    composer require hardworm/yandex-xml-library

2) Использование
-------------------------------------
```php
<?php
require_once 'vendor/autoload.php';

use hardworm\YandexXml\YandexXmlClient;
use hardworm\YandexXml\Exceptions\YandexXmlException;

/**
 * @link http://search.yaca.yandex.ru/geo.c2n
 */
$lr = 2; // id региона в Яндекс

$yandexXml = new YandexXmlClient('your-user-in-yandex-xml', 'your-key-yandex-xml');

/**
 * $results является массивом из stdClass
 * Каждый элемент содержит поля:
 * url
 * domain
 * title
 * headline
 */
try {
    $results = $yandexXml
        ->setQuery('What is github query') //запрос к поисковику
        ->setLr($lr) //id региона в Яндекс
        ->setPage('Начать со страницы. По умолчанию 0 (первая страница)')
        ->setLimit(100) //Количество результатов на странице (макс 100)
        ->setProxy('host или ip', 'port', 'user, если требуется авторизация', 'pass, если требуется авторизация') //Если требуется проксирование запроса
        ->request()
        ->getResults() //Возвращает массив из stdClass
    ;
}
catch (YandexXmlException $e) {
    echo "\nВозникло исключение YandexXmlException:\n";
    echo $e->getMessage() . "\n";
}
catch (Exception $e) {
    echo "\nВозникло неизвестное исключение:\n";
    echo $e->getMessage() . "\n";
}

/**
 * Возвращает строку "Нашлось 12 млн. результатов"
 */
$total = $yandexXml->getTotalHuman();

/**
 * Возвращает integer с общим количеством страниц результатов
 */
$pages = $yandexXml->getPages();
```