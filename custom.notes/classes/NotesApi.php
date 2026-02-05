<?php

namespace Custom\Notes;

use Bitrix\Main\Loader;
use Bitrix\Main\Rest\RestException;

Loader::includeModule('custom.notes');

class NotesApi
{
    public static function OnRestServiceBuildDescription()
    {
        return [
            'notes.list' => ['callback' => [__CLASS__, 'list']],
            'notes.get' => ['callback' => [__CLASS__, 'get']],
            'notes.create' => ['callback' => [__CLASS__, 'create']],
            'notes.update' => ['callback' => [__CLASS__, 'update']],
            'notes.delete' => ['callback' => [__CLASS__, 'delete']],
        ];
    }

    public static function list($params)
    {
        return NotesTable::getList(['order'=>['ID'=>'DESC']])->fetchAll();
    }

    public static function get($params)
    {
        if(empty($params['ID'])) throw new RestException("ID required");
        return NotesTable::getById($params['ID'])->fetch();
    }

    public static function create($params)
    {
        if(empty($params['TITLE'])) throw new RestException("TITLE required");
        $res = NotesTable::add([
            'TITLE' => $params['TITLE'],
            'CONTENT' => $params['CONTENT'] ?? ''
        ]);
        return ['ID'=>$res->getId()];
    }

    public static function update($params)
    {
        if(empty($params['ID'])) throw new RestException("ID required");
        NotesTable::update($params['ID'], [
            'TITLE' => $params['TITLE'],
            'CONTENT' => $params['CONTENT'] ?? ''
        ]);
        return true;
    }

    public static function delete($params)
    {
        if(empty($params['ID'])) throw new RestException("ID required");
        NotesTable::delete($params['ID']);
        return true;
    }
}
