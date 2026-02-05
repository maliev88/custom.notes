import { createApp, ref, onMounted } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.prod.js';
import axios from 'https://cdn.jsdelivr.net/npm/axios@1.5.0/dist/esm/axios.min.js';

const API_URL = '/api/rest-notes/index.php';

export function initNotesApp(selector = '#notes-app') {
    const App = {
        setup() {
            const notes = ref([]);
            const title = ref('');
            const content = ref('');
            const loading = ref(false);

            /** 📥 GET /api/rest-notes */
            const loadNotes = async () => {
                loading.value = true;
                try {
                    const res = await axios.get(API_URL);
                    notes.value = res.data;
                } finally {
                    loading.value = false;
                }
            };

            /** ➕ POST /api/rest-notes */
            const addNote = async () => {
                if (!title.value.trim()) return;

                await axios.post(API_URL, {
                    title: title.value,
                    content: content.value
                });

                title.value = '';
                content.value = '';
                await loadNotes();
            };

            /** ✏️ PUT /api/rest-notes?ID=123 */
            const updateNote = async (note) => {
                if (!note.ID) return;

                await axios.put(`${API_URL}?ID=${note.ID}`, {
                    title: note.TITLE,
                    content: note.CONTENT
                });
            };

            /** ❌ DELETE /api/rest-notes?ID=123 */
            const deleteNote = async (id) => {
                if (!id) return;

                await axios.delete(`${API_URL}?ID=${id}`);
                await loadNotes();
            };

            onMounted(loadNotes);

            return {
                notes,
                title,
                content,
                loading,
                addNote,
                updateNote,
                deleteNote
            };
        },

        template: `
        <div class="notes_form_container">
            <h2>Notes</h2>

            <div class="note-form">
                <input
                    v-model="title"
                    placeholder="Title"
                />
                <textarea
                    v-model="content"
                    placeholder="Content"
                ></textarea>
                <button @click="addNote">Add Note</button>
            </div>

            <div v-if="loading">Loading...</div>

            <ul class="notes-list">
                <li
                    v-for="note in notes"
                    :key="note.ID"
                    class="item_notes"
                >
                    <b
                        contenteditable
                        @blur="updateNote({ ...note, TITLE: $event.target.innerText.trim() })"
                    >
                        {{ note.TITLE }}
                    </b>

                    <span
                        contenteditable
                        @blur="updateNote({ ...note, CONTENT: $event.target.innerText.trim() })"
                    >
                        {{ note.CONTENT }}
                    </span>

                    <button @click="deleteNote(note.ID)">
                        Delete
                    </button>
                </li>
            </ul>
        </div>
        `
    };

    createApp(App).mount(selector);
}
