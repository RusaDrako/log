# RusaDrako\\log

[![Version](http://poser.pugx.org/rusadrako/log/version)](https://packagist.org/packages/rusadrako/log)
[![Total Downloads](http://poser.pugx.org/rusadrako/log/downloads)](https://packagist.org/packages/rusadrako/log/stats)
[![License](http://poser.pugx.org/rusadrako/log/license)](./LICENSE)

Логирование

## Установка (composer)
```sh
composer require 'rusadrako/log'
```


## Установка (manual)
- Скачать и распоковать библиотеку.
- Добавить в код инструкцию:
```php
require_once('/log/src/autoload.php')
```


## Пример выполнения запроса
```PHP
use RusaDrako\log\log;
// Полный путь к файлу логирования
$file = __DIR__ . '/test.log';
// Активируем объект логирования
$objLog = new log($file, log::SEPARATION_HOUR);
// Добавляем запись без даты
$objLog->addLog('test', 0);
// Добавляем запись с датой
$objLog->addLog('test with date');
// Выводим реальное имя файла лога
echo $objLog->getFile();
// Выводим последние 20 строк файла лога
var_dump($objLog->getLastRows(20));
```
