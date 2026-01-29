<template>
    <div class="app">
        <header class="header">
            <div class="header-top">
                <div>
                    <h1>📖 Моя коллекция книг</h1>
                    <p>Здесь собраны мои любимые произведения</p>
                </div>
                <div v-if="$page.url === '/'" class="header-actions">
                    <SButton outlined @click="resetAllStars">Сбросить рейтинги</SButton>
                    <SButton @click="showForm = true">Добавить книгу</SButton>
                </div>
            </div>

            <nav class="header-nav">
                <Link href="/" class="nav-link" :class="{ active: $page.url === '/' }">
                    Все книги
                </Link>
                <Link href="/about" class="nav-link" :class="{ active: $page.url === '/about' }">
                    О нас
                </Link>

                <Link href="/dashboard" class="nav-link" :class="{ active: $page.url === '/dashboard' }">
                    Дашборд
                </Link>

                <Link href="/profile" class="nav-link" :class="{ active: $page.url === '/profile' }">
                    Профиль
                </Link>

                <Link href="/scrollpage" class="nav-link" :class="{ active: $page.url === '/scrollpage' }">
                    Scrollpage
                </Link>
            </nav>
        </header>

        <SDialog v-if="showForm" v-model="showForm" :title="editingBookId ? 'Редактировать книгу' : 'Новая книга'"
            width="700" @update:modelValue="onDialogClose">
            <BookForm style="width:700px" :key="formKey" :modelValue="newBook" :errors="errors"
                :submitText="editingBookId ? 'Сохранить' : 'Добавить'" @update:modelValue="newBook = $event"
                @submit="editingBookId ? updateBook() : addBook()" @cancel="cancelForm" />
        </SDialog>

        <div v-if="$page.url === '/'" class="stat-block">
            <SStat title="Всего книг:">{{ totalBooks }}</SStat>
            <SStat title="Средний рейтинг:">{{ averageRating.toFixed(2) }}</SStat>
            <SStat title="Макс. рейтинг:">{{ maxRating }}/5</SStat>
        </div>

        <slot :books="books" @edit-book="editBook" @delete-book="deleteBook" @update-stars="updateStars" />
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { SButton, SDialog, SStat, SConfirm, SAlert } from 'startup-ui'
import BookForm from '../components/BookForm.vue'

const props = defineProps({
    books: {
        type: Array,
        required: true,
        default: () => []
    }
})

const showForm = ref(false)
const editingBookId = ref(null)
const formKey = ref(0)
const errors = ref({
    title: '',
    genres: ''
})

// Новая книга для формы
const newBook = ref({
    title: '',
    description: '',
    genres: [],
    img: '',
    isAdult: false,
    stars: 0
})

// Вычисляемые свойства
const totalBooks = computed(() => props.books.length)

const averageRating = computed(() => {
    if (props.books.length === 0) return 0
    const sum = props.books.reduce((total, book) => total + (book.stars || 0), 0)
    return sum / props.books.length
})

const maxRating = computed(() => {
    if (props.books.length === 0) return 0
    return Math.max(...props.books.map(book => book.stars || 0))
})

// Функция сброса рейтингов (как в оригинале)
function resetAllStars() {
    if (props.books.length === 0) return

    SConfirm.open('Вы действительно хотите сбросить рейтинги всех книг?', {
        title: 'Подтверждение сброса',
        type: 'warning',
        acceptText: 'Сбросить',
        cancelText: 'Отмена',
        onAccept: () => {
            SAlert.success('Рейтинги всех книг сброшены!')
        }
    })
}

// Редактирование книги
function editBook(book) {
    editingBookId.value = book.id
    newBook.value = {
        title: book.title,
        description: book.description,
        genres: [...book.genres],
        img: book.img,
        isAdult: book.isAdult,
        stars: book.stars
    }
    showForm.value = true
}

// Удаление книги
function deleteBook(id) {
    SConfirm.open(`Вы уверены, что хотите удалить книгу?`, {
        title: 'Подтверждение удаления',
        type: 'danger',
        acceptText: 'Удалить',
        cancelText: 'Отмена',
        onAccept: () => {
            SAlert.success(`Книга удалена`)
        }
    })
}

// Обновление рейтинга
function updateStars(bookId, newStars) {
    console.log(`Update book ${bookId} to ${newStars} stars`)
}

// Функции формы
function validateForm() {
    errors.value = { title: '', genres: '' }

    if (!newBook.value.title.trim()) {
        errors.value.title = 'Введите название книги'
        return false
    }

    if (newBook.value.genres.length === 0) {
        errors.value.genres = 'Выберите хотя бы один жанр'
        return false
    }

    return true
}

function addBook() {
    if (!validateForm()) return
    console.log('Adding book:', newBook.value)
    cancelForm()
}

function updateBook() {
    if (!validateForm()) return
    console.log('Updating book:', newBook.value)
    cancelForm()
}

function cancelForm() {
    newBook.value = {
        title: '',
        description: '',
        genres: [],
        img: '',
        isAdult: false,
        stars: 0
    }
    editingBookId.value = null
    errors.value = { title: '', genres: '' }
    showForm.value = false
    formKey.value += 1
}

function onDialogClose(value) {
    showForm.value = value
    if (!value) {
        cancelForm()
    }
}
</script>

<style>
.app {
    min-height: 100vh;
    background: #f5f8fa;
    font-family: Verdana, Geneva, Tahoma, sans-serif;
}

.header {
    background: white;
    border-bottom: 1px solid #e1e8ed;
    padding: 16px 40px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 100;
}

.header-top {
    display: flex;
    flex-direction: row;
    align-items: center;
    width: 100%;
    justify-content: space-between;
}

.header-nav {
    width: 100%;
    display: flex;
    gap: 20px;
    margin-top: 20px;
    padding: 10px 0;
    border-top: 1px solid #e1e8ed;
}

.nav-link {
    text-decoration: none;
    background-color: #F04F0A;
    color: #FFFFFF;
    font-weight: 500;
    padding: 8px 16px;
    border-radius: 4px;
    transition: all 0.3s ease;
    position: relative;
}

.nav-link:hover {
    background-color: #F1905F;
    color: #FFFFFF;
}

.nav-link.active {
    color: #F04F0A;
    background-color: #FFFFFF;
    border: 1px solid #F04F0A;
}

.header h1 {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: #F04F0A;
}

.header p {
    margin: 0;
    color: #657786;
    font-size: 14px;
}

.stat-block {
    display: flex;
    flex-direction: column;
    justify-content: center;
    margin: 20px auto;
    max-width: 260px;
    padding: 30px 30px 10px 30px;
    background-color: #FFFFFF;
    border: 1px solid #E1E8ED;
    color: #F04F0A;
}

.booksContainer {
    max-width: 1200px;
    margin: 30px auto;
    padding: 0 40px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
}

.header-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}
</style>