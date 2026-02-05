<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

<div id="notes-app"></div>
<script type="module">
    import { createApp, ref, onMounted } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.prod.js';
    import axios from 'https://cdn.jsdelivr.net/npm/axios@1.5.0/dist/esm/axios.min.js';
    const App = {
        setup() {
            const notes = ref([]);
            const title = ref('');
            const content = ref('');

            const loadNotes = async () => {
                const res = await axios.get('/api/notes_list.php');
                notes.value = res.data;
            };

            const addNote = async () => {
                await axios.post('/api/notes_create.php', new URLSearchParams({
                    title: title.value,
                    content: content.value
                }));
                title.value = content.value = '';
                await loadNotes();
            };

            const updateNote = async (note) => {
                await axios.post('/api/notes_update.php', new URLSearchParams({
                    ID: note.ID,
                    title: note.TITLE,
                    content: note.CONTENT
                }));
                await loadNotes();
            };

            const deleteNote = async (id) => {
                await axios.post('/api/notes_delete.php', new URLSearchParams({ ID: id }));
                await loadNotes();
            };

            onMounted(loadNotes);

            return { notes, title, content, addNote, updateNote, deleteNote };
        },
        template: `
        <div class="notes_form_container">
            <h2>Notes</h2>
            <div class="note-form">
                <input v-model="title" type="text" name="notes_title" placeholder="Title" />
                <textarea v-model="content" name="notes_content" placeholder="Content"></textarea>
                <button @click="addNote">Add Note</button>
            </div>
            <div class="form_result">
                <ul class="notes-list">
                    <li class="item_notes" v-for="note in notes" :key="note.ID">
                        <b contenteditable @blur="updateNote({...note, TITLE: $event.target.innerText})">{{ note.TITLE }}</b>
                        <span contenteditable @blur="updateNote({...note, CONTENT: $event.target.innerText})">{{ note.CONTENT }}</span>
                        <button @click="deleteNote(note.ID)">Delete</button>
                    </li>
                </ul>
            </div>
        </div>
    `
    };

    createApp(App).mount('#notes-app');

</script>
