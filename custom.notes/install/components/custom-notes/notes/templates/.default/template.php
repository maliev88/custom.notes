<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

<div id="notes-app"></div>
<script type="module">
    import { initNotesApp } from '/local/modules/custom.notes/js/app.js';
    initNotesApp('#notes-app');
</script>