<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\Web\Json;
use Custom\Notes\NotesTable;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include.php';

header('Content-Type: application/json; charset=utf-8');

if (!Loader::includeModule('custom.notes')) {
    http_response_code(500);
    echo Json::encode(['error' => 'Module not loaded']);
    die();
}

$request = Application::getInstance()->getContext()->getRequest();
$method  = $request->getRequestMethod();

// Получаем ID из query string ?ID=123
$id = (int) $request->getQuery("ID");

try {
    switch ($method) {

        /** 📥 GET /api/rest-notes или /api/rest-notes?ID=123 */
        case 'GET':
            if ($id) {
                $note = NotesTable::getById($id)->fetch();
                if (!$note) {
                    http_response_code(404);
                    echo Json::encode(['error' => 'Not found']);
                    break;
                }
                echo Json::encode($note);
            } else {
                $list = NotesTable::getList([
                    'order' => ['ID' => 'DESC']
                ])->fetchAll();
                echo Json::encode($list);
            }
            break;

        /** ➕ POST /api/rest-notes */
        case 'POST':
            $data = Json::decode($request->getInput());

            if (empty($data['title'])) {
                http_response_code(400);
                echo Json::encode(['error' => 'Title is required']);
                break;
            }

            $result = NotesTable::add([
                'TITLE' => $data['title'],
                'CONTENT' => $data['content'] ?? ''
            ]);

            http_response_code(201);
            echo Json::encode(['id' => $result->getId()]);
            break;

        /** ✏️ PUT /api/rest-notes?ID=123 */
        case 'PUT':
            if (!$id) {
                http_response_code(400);
                echo Json::encode(['error' => 'ID required']);
                break;
            }

            $data = Json::decode($request->getInput());

            NotesTable::update($id, [
                'TITLE' => $data['title'] ?? '',
                'CONTENT' => $data['content'] ?? ''
            ]);

            echo Json::encode(['status' => 'updated']);
            break;

        /** ❌ DELETE /api/rest-notes?ID=123 */
        case 'DELETE':
            if (!$id) {
                http_response_code(400);
                echo Json::encode(['error' => 'ID required']);
                break;
            }

            NotesTable::delete($id);
            echo Json::encode(['status' => 'deleted']);
            break;

        default:
            http_response_code(405);
            echo Json::encode(['error' => 'Method not allowed']);
    }

} catch (\Throwable $e) {
    http_response_code(500);
    echo Json::encode(['error' => $e->getMessage()]);
}
