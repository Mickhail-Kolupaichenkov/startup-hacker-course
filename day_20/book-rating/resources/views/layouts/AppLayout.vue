<template>
    <div class="app">
        <header class="header">
            <div class="header-top">
                <div>
                    <h1>📖 Моя коллекция книг</h1>
                    <p>Здесь собраны мои любимые произведения</p>
                </div>

                <div class="header-actions">
                    <div v-if="$page.props.auth.user" class="user-info">
                        <span class="user-name">{{ $page.props.auth.user.name }}</span>
                        <span class="user-role" :class="{ 'admin': $page.props.auth.user.role === 'admin' }">
                            {{ $page.props.auth.user.role === 'admin' ? 'Админ' : 'Пользователь' }}
                        </span>
                        <form @submit.prevent="logout" class="logout-form">
                            <button type="submit" class="logout-button">Выйти</button>
                        </form>
                    </div>

                    <div v-else class="auth-buttons">
                        <Link href="/login" class="nav-link">Войти</Link>
                        <Link href="/register" class="nav-link secondary">Регистрация</Link>
                    </div>
                </div>
            </div>

            <nav class="header-nav">
                <div class="nav-links">
                    <Link href="/" class="nav-link" :class="{ active: $page.url === '/' }">
                        Все книги
                    </Link>
                    <Link href="/about" class="nav-link" :class="{ active: $page.url === '/about' }">
                        О нас
                    </Link>

                    <template v-if="$page.props.auth.user">
                        <Link href="/profile" class="nav-link" :class="{ active: $page.url === '/profile' }">
                            Мои книги
                        </Link>

                        <Link v-if="$page.props.auth.user.role === 'admin'" href="/admin/dashboard" class="nav-link"
                            :class="{ active: $page.url.startsWith('/admin') }">
                            Админка
                        </Link>
                    </template>
                </div>

                <div class="nav-actions">
                    <SButton v-if="($page.url === '/' || $page.url === '/profile') && $page.props.auth.user"
                        @click="goToCreateBook" class="add-book-btn">
                        Добавить книгу
                    </SButton>
                </div>
            </nav>
        </header>

        <slot />
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { SButton, SStat, SConfirm, SAlert } from 'startup-ui'

function logout() {
    router.post('/logout')
}

// Функция перехода на страницу создания книги
function goToCreateBook() {
    router.get('/books/create')
}

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

// Функция сброса рейтингов
function resetAllStars() {
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
</script>

<style scoped>
.app {
    min-height: 100vh;
    background: #f5f8fa;
    font-family: Verdana, Geneva, Tahoma, sans-serif;
}

.header {
    background: white;
    border-bottom: 1px solid #e1e8ed;
    padding: 16px 40px;
    position: sticky;
    top: 0;
    z-index: 100;
}

.header-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
}

.header h1 {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: #F04F0A;
}

.header p {
    margin: 4px 0 0 0;
    color: #657786;
    font-size: 14px;
}

.header-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.user-name {
    font-weight: 500;
    color: #333;
}

.user-role {
    font-size: 12px;
    padding: 4px 8px;
    border-radius: 12px;
    background: #e1e8ed;
    color: #657786;
}

.user-role.admin {
    background: #F04F0A;
    color: white;
}

.logout-form {
    margin: 0;
}

.logout-button {
    background: transparent;
    border: 1px solid #F04F0A;
    color: #F04F0A;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s;
}

.logout-button:hover {
    background: #F04F0A;
    color: white;
}

.auth-buttons {
    display: flex;
    gap: 10px;
    align-items: center;
}

.auth-buttons .nav-link {
    text-decoration: none;
    background-color: #F04F0A;
    color: #FFFFFF;
    font-weight: 500;
    padding: 8px 16px;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.auth-buttons .nav-link:hover {
    background-color: #F1905F;
    color: #FFFFFF;
}

.auth-buttons .nav-link.secondary {
    background: white;
    color: #F04F0A;
    border: 1px solid #F04F0A;
}

.auth-buttons .nav-link.secondary:hover {
    background: #f8f8f8;
}

/* Навигационная панель */
.header-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #e1e8ed;
}

.nav-links {
    display: flex;
    gap: 10px;
    align-items: center;
}

.nav-actions {
    display: flex;
    align-items: center;
}

/* Стили для ссылок в навигации */
.nav-link {
    text-decoration: none;
    background-color: #F04F0A;
    color: #FFFFFF;
    font-weight: 500;
    padding: 8px 16px;
    border-radius: 4px;
    transition: all 0.3s ease;
    font-size: 14px;
    border: 1px solid #F04F0A;
}

.nav-link:hover {
    background-color: #F1905F;
    color: #FFFFFF;
    border-color: #F1905F;
}

.nav-link.active {
    background-color: #FFFFFF;
    color: #F04F0A;
    border-color: #F04F0A;
}

.nav-link.active:hover {
    background-color: #FFFFFF;
    color: #F04F0A;
}

/* Кнопка добавления книги */
.add-book-btn {
    background-color: #F04F0A;
    color: white;
    border: 1px solid #F04F0A;
    transition: all 0.3s ease;
}

.add-book-btn:hover {
    background-color: #F1905F;
    border-color: #F1905F;
}

/* Адаптивность */
@media (max-width: 768px) {
    .header {
        padding: 12px 20px;
    }

    .header-top {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .header-actions {
        width: 100%;
        justify-content: space-between;
    }

    .header-nav {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }

    .nav-links {
        flex-wrap: wrap;
    }

    .nav-actions {
        width: 100%;
        justify-content: flex-end;
    }
}

@media (max-width: 480px) {
    .user-info {
        flex-direction: column;
        align-items: flex-end;
        gap: 8px;
    }

    .nav-link {
        padding: 6px 12px;
        font-size: 13px;
    }
}
</style>