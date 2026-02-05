<?php
use Custom\Notes\NotesApi;
\Bitrix\Main\Loader::includeModule('custom.notes');
\Bitrix\Main\Rest\RestServer::onBuildDescriptionRegister(NotesApi::OnRestServiceBuildDescription());
