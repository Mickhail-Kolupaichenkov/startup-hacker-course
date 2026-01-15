<template>
  <div class="app">
    <div class="header">
      <div>
        <h1>📖 Моя коллекция книг</h1>
        <p>Здесь собраны мои любимые произведения</p>
      </div>
      <div class="header-actions">
        <button @click="resetAllStars" class="reset-btn">
          Сбросить рейтинги
        </button>
        <button class="add-btn" @click="showForm = true">
          Добавить книгу
        </button>
      </div>
    </div>

    <Dialog v-if="showForm" :show="showForm" @close="cancelForm">
      <template #title>
        <h3 style="color: #1da1f2;">
          {{ editingBookId ? 'Редактировать книгу' : 'Новая книга' }}
        </h3>
      </template>
      <BookForm :modelValue="newBook" :errors="errors" :submitText="editingBookId ? 'Сохранить' : 'Добавить'"
        @update:modelValue="newBook = $event" @submit="editingBookId ? updateBook() : addBook()" @cancel="cancelForm" />
    </Dialog>

    <div class="stats" v-if="books.length > 0">
      <div class="stat-item">
        <span class="stat-label">Всего книг:</span>
        <span class="stat-value">{{ totalBooks }}</span>
      </div>
      <div class="stat-item">
        <span class="stat-label">Средний рейтинг:</span>
        <span class="stat-value">{{ averageRating.toFixed(2) }}</span>
      </div>
      <div class="stat-item">
        <span class="stat-label">Макс. рейтинг:</span>
        <span class="stat-value">{{ maxRating }}/5</span>
      </div>
    </div>

    <div class="booksContainer">
      <BookCard v-for="book in books" :book="book" @edit="editBook(book)" @delete="deleteBook(book.id)"
        @update:stars="updateStars(book.id, $event)" />
    </div>

  </div>
</template>

<script setup>

import { ref, computed } from 'vue'
import bookImg1 from '@/assets/images/book1.png'
import bookImg2 from '@/assets/images/book2.png'
import bookImg3 from '@/assets/images/book3.png'
import BookCard from './BookCard.vue'
import BookForm from './BookForm.vue'
import Dialog from './Dialog.vue'

const editingBookId = ref(null)
const showForm = ref(false)

const totalBooks = computed(() => books.value.length)


const deleteBook = (id) => {
  if (confirm('Вы уверены, что хотите удалить эту книгу?')) {
    books.value = books.value.filter(book => book.id !== id)
  }
}

const books = ref([
  {
    id: 1,
    title: 'Мастер и Маргарита',
    description: '«Мастер и Маргарита» — роман Михаила Афанасьевича Булгакова, работа над которым началась, по одним данным, в 1928 году, по другим — в 1929-м, и продолжалась вплоть до смерти писателя в марте 1940 года.',
    genres: ['Роман'],
    img: bookImg1,
    isAdult: false,
    stars: 0
  },
  {
    id: 2,
    title: 'Бэтман Аполло',
    description: 'Главный герой — Рама, сравнительно недавно ставший вампиром. Ему предстоит разобраться в вампирском мироздании и подняться по иерархической лестнице, став Кавалером Ночи и ныряльщиком.',
    genres: ['Роман'],
    img: bookImg2,
    isAdult: true,
    stars: 0
  },
  {
    id: 3,
    title: 'Стратегическое управление на основе маркетингового анализа. Инструменты, проблемы, ситуации',
    description: 'Книга в которой автор описывает классические и собственные инструменты стратегического управления и маркетингового анализа.',
    genres: ['Бизнес-книга'],
    img: bookImg3,
    isAdult: false,
    stars: 0
  }
])

const averageRating = computed(() => {
  if (books.value.length === 0) return 0

  const sum = books.value.reduce((total, book) => total + book.stars, 0)
  return sum / books.value.length
})

const maxRating = computed(() => {
  if (books.value.length === 0) return 0

  return Math.max(...books.value.map(book => book.stars))
})

const newBook = ref({
  title: '',
  description: '',
  genres: [],
  img: '',
  isAdult: false,
  stars: 0
})

const errors = ref({
  title: '',
  genres: ''
})

const hasErrors = computed(() => {
  return errors.value.title || errors.value.genres
})



const resetAllStars = () => {
  if (books.value.length === 0) return

  if (confirm('Сбросить рейтинги всех книг?')) {
    books.value.forEach(book => {
      book.stars = 0
    })
  }
}

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
}

const updateStars = (bookId, newStars) => {
  const book = books.value.find(b => b.id === bookId)
  if (book) {
    book.stars = newStars
  }
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
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: sticky;
  top: 0;
  z-index: 100;
}

.header h1 {
  margin: 0;
  font-size: 20px;
  font-weight: 700;
  color: #1da1f2;
}

.header p {
  margin: 0;
  color: #657786;
  font-size: 14px;
}

.booksContainer {
  max-width: 1200px;
  margin: 30px auto;
  padding: 0 40px;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 24px;
}

.header button {
  background: #1da1f2;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  margin-right: 15px;
}

.stats {
  max-width: 1200px;
  margin: 20px auto;
  padding: 0 40px;
  display: flex;
  gap: 30px;
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.stat-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  flex: 1;
}

.stat-label {
  font-size: 14px;
  color: #657786;
  margin-bottom: 8px;
}

.stat-value {
  font-size: 24px;
  font-weight: bold;
  color: #1da1f2;
}

.reset-btn {
  background: #ff9800;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  margin-right: 10px;
}

.reset-btn:hover {
  background: #f57c00;
}

.header-actions {
  display: flex;
  align-items: center;
}
</style>