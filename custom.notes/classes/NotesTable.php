<?php
namespace Custom\Notes;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\TextField;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Type\DateTime;

class NotesTable extends DataManager
{
    public static function getTableName() { return 'b_custom_notes'; }

    public static function getMap()
    {
        return [
            new IntegerField('ID', ['primary' => true, 'autocomplete' => true]),
            new StringField('TITLE', ['required' => true]),
            new TextField('CONTENT'),
            new DatetimeField('CREATED_AT', ['default_value' => new DateTime()]),
            new DatetimeField('UPDATED_AT', ['default_value' => new DateTime()]),
        ];
    }
}
