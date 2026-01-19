<template>
  <div class="app">
    <header class="header">
      <div class="header-top">
        <div>
          <h1>📖 Моя коллекция книг</h1>
          <p>Здесь собраны мои любимые произведения</p>
        </div>
        <div v-if="$route.path === '/'" class="header-actions">
          <SButton outlined @click="resetAllStars">Сбросить рейтинги</SButton>
          <SButton @click="showForm = true">Добавить книгу</SButton>
        </div>
      </div>

      <nav class="header-nav">
        <RouterLink to="/" class="nav-link" :class="{ active: $route.path === '/' }">
          Все книги
        </RouterLink>
        <RouterLink to="/about" class="nav-link" :class="{ active: $route.path === '/about' }">
          О нас
        </RouterLink>
      </nav>
    </header>

    <SDialog v-if="showForm" v-model="showForm" :title="editingBookId ? 'Редактировать книгу' : 'Новая книга'"
      width="700" @update:modelValue="onDialogClose">
      <BookForm style="width:700px" :key="formKey" :modelValue="newBook" :errors="errors"
        :submitText="editingBookId ? 'Сохранить' : 'Добавить'" @update:modelValue="newBook = $event"
        @submit="editingBookId ? updateBook() : addBook()" @cancel="cancelForm" />
    </SDialog>

    <div v-if="$route.path === '/'" class="stat-block">
      <SStat title="Всего книг:">{{ totalBooks }}</SStat>
      <SStat title="Средний рейтинг:">{{ averageRating.toFixed(2) }}</SStat>
      <SStat title="Макс. рейтинг:">{{ maxRating }}/5</SStat>
    </div>

    <RouterView :books="books" @edit-book="editBook" @delete-book="deleteBook" @update-stars="updateStars" />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { SButton, SDialog, SConfirm, SAlert, SStat } from 'startup-ui';

//Импорты картинок
import bookImg1 from '@/assets/images/book1.png'
import bookImg2 from '@/assets/images/book2.png'
import bookImg3 from '@/assets/images/book3.png'
import bookImg4 from '@/assets/images/book4.png'
import bookImg5 from '@/assets/images/book5.png'
import bookImg6 from '@/assets/images/book6.png'
//Импорт компонентов
import BookForm from './components/BookForm.vue'


const editingBookId = ref(null)
const formKey = ref(0)
const showForm = ref(false)
const totalBooks = computed(() => books.value.length)
//Состояние для ошибок
const errors = ref({
  title: '',
  genres: ''
})
//Динамическое получение ошибок
const hasErrors = computed(() => {
  return errors.value.title || errors.value.genres
})

//Удаление книги
const deleteBook = (id) => {
  SConfirm.open(`Вы уверены, что хотите удалить книгу?`, {
    title: 'Подтверждение удаления',
    type: 'danger',
    acceptText: 'Удалить',
    cancelText: 'Отмена',

    onAccept: () => {
      books.value = books.value.filter(book => book.id !== id);
      SAlert.success(`Книга удалена`);
    }
  });
}

//Средний рейтинг
const averageRating = computed(() => {
  if (books.value.length === 0) return 0

  const sum = books.value.reduce((total, book) => total + book.stars, 0)
  return sum / books.value.length
})

//Максимальный рейтинг
const maxRating = computed(() => {
  if (books.value.length === 0) return 0

  return Math.max(...books.value.map(book => book.stars))
})

//Состояние для новой книги
const newBook = ref({
  title: '',
  description: '',
  genres: [],
  img: '',
  isAdult: false,
  stars: 0
})

//Сброс рейтингов
const resetAllStars = () => {
  if (books.value.length === 0) return

  SConfirm.open('Вы действительно хотите сбросить рейтинги всех книг?', {
    title: 'Подтверждение сброса',
    type: 'warning',
    acceptText: 'Сбросить',
    cancelText: 'Отмена',

    onAccept: () => {
      books.value.forEach(book => {
        book.stars = 0
      })
      SAlert.success('Рейтинги всех книг сброшены!')
    }
  })
}

//Редактирование книги
const editBook = (book) => {
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

//Валидация формы
const validateForm = () => {
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

//Добавление книги с валидацией
const addBook = () => {
  if (!validateForm()) return

  books.value.push({
    id: books.value.length + 1,
    title: newBook.value.title,
    description: newBook.value.description,
    genres: [...newBook.value.genres],
    img: newBook.value.img,
    isAdult: newBook.value.isAdult,
    stars: 0
  })

  cancelForm()
}

//Обновление данных книги с валидацией
const updateBook = () => {
  if (!validateForm()) return

  const index = books.value.findIndex(book => book.id === editingBookId.value)
  if (index !== -1) {
    books.value[index] = {
      ...books.value[index],
      title: newBook.value.title,
      description: newBook.value.description,
      genres: [...newBook.value.genres],
      img: newBook.value.img,
      isAdult: newBook.value.isAdult
    }
  }

  cancelForm()
}

//Закрытие формы и сброс значений
const cancelForm = () => {
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

const onDialogClose = (value) => {
  showForm.value = value
  if (!value) {
    cancelForm()
  }
}

//Обновление звезд в рейтинге
const updateStars = (bookId, newStars) => {
  const book = books.value.find(b => b.id === bookId)
  if (book) {
    book.stars = newStars
  }
}

//Data книг
const books = ref([
  {
    id: 1,
    title: 'Мастер и Маргарита',
    description: '«Мастер и Маргарита» — роман Михаила Афанасьевича Булгакова, работа над которым началась, по одним данным, в 1928 году, по другим — в 1929-м, и продолжалась вплоть до смерти писателя в марте 1940 года.',
    genres: ['Роман'],
    img: bookImg1,
    isAdult: false,
    stars: 5
  },
  {
    id: 2,
    title: 'Бэтман Аполло',
    description: 'Главный герой — Рама, сравнительно недавно ставший вампиром. Ему предстоит разобраться в вампирском мироздании и подняться по иерархической лестнице, став Кавалером Ночи и ныряльщиком.',
    genres: ['Роман'],
    img: bookImg2,
    isAdult: true,
    stars: 4
  },
  {
    id: 3,
    title: 'Стратегическое управление на основе маркетингового анализа. Инструменты, проблемы, ситуации',
    description: 'Книга в которой автор описывает классические и собственные инструменты стратегического управления и маркетингового анализа.',
    genres: ['Бизнес-книга'],
    img: bookImg3,
    isAdult: false,
    stars: 0
  },
  {
    id: 4,
    title: 'Чистый код: создание, анализ и рефакторинг. Библиотека программиста',
    description: 'Даже плохой программный код может работать. Однако если код не является «чистым», это всегда будет мешать развитию проекта и компании-разработчика, отнимая значительные ресурсы на его поддержку и «укрощение».',
    genres: ['Техническая-литература'],
    img: bookImg4,
    isAdult: true,
    stars: 3
  },
  {
    id: 5,
    title: 'Копирайтинг: как не съесть собаку. Создаем тексты, которые продают',
    description: 'Эта книга - набор методик, приемов и секретов по написанию продающих текстов (текстов для сайтов, полиграфии, коммерческих предложений и других рекламных целей).',
    genres: ['Бизнес-книга'],
    img: bookImg5,
    isAdult: false,
    stars: 0
  }
  , {
    id: 6,
    title: 'Магия утра. Как первый час дня определяет ваш успех',
    description: 'Книга, которая помогла тысячам людей изменить жизнь за счет правильного начала дня и утренних ритуалов.',
    genres: ['Бизнес-книга'],
    img: bookImg6,
    isAdult: false,
    stars: 0
  }
])
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