# Bitrix Notes Module

## Установка
1. Скопировать модуль в `/local/modules/custom.notes/`
2. Установить через админку Битрикс
3. Добавь компонент в любую страницу Bitrix

```
<?php
$APPLICATION->IncludeComponent(
    "custom-notes:notes",
    "",
    []
);
```


### PHPUnit (через PHAR)

```
php phpunit.phar local/modules/custom.notes/tests
```
