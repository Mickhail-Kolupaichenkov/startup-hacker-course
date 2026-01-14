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

    <div v-if="showForm" class="form-overlay">
      <div class="form">
        <h3>{{ editingBookId ? 'Редактировать книгу' : 'Новая книга' }}</h3>
        <form @submit.prevent="editingBookId ? updateBook() : addBook()">
          <input placeholder="Название" v-model="newBook.title">

          <textarea placeholder="Описание" v-model="newBook.description"></textarea>

          <input placeholder="URL обложки" v-model="newBook.img">

          <select v-model="newBook.genres" multiple>
            <option value="">Жанр</option>
            <option value="Роман">Роман</option>
            <option value="Фантастика">Фантастика</option>
            <option value="Детектив">Детектив</option>
          </select>

          <div class="checkbox">
            <input type="checkbox" id="adult" v-model="newBook.isAdult">
            <label for="adult">18+</label>
          </div>

          <div v-if="hasErrors" class="errors-block">
            <p v-if="errors.title">{{ errors.title }}</p>
            <p v-if="errors.genres">{{ errors.genres }}</p>
          </div>

          <div class="form-buttons">
            <button type="submit">{{ editingBookId ? 'Сохранить' : 'Добавить' }}</button>
            <button type="button" @click="cancelForm">Отменить</button>
          </div>
        </form>
      </div>
    </div>

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
      <div class="cardItem" v-for="book in books" :key="book.id">
        <div class="cardItem_info">
          <h3>{{ book.title }}</h3>
          <p>{{ book.description }}</p>
          <span>Жанр:
            <span v-for="genre in book.genres" :key="genre">
              {{ genre }}
            </span>
            <b v-if="book.isAdult">18+</b>
          </span>
          <div class="rating-stars">
            <span>Рейтинг: </span>
            <span v-for="n in 5" :key="n" class="star" :class="{
              'active': n <= book.stars,
              'inactive': n > book.stars
            }" @click="setStars(book, n)">
              <FontAwesomeIcon icon="star" />
            </span>
          </div>
        </div>

        <div class="cardItem_img">
          <div class="big-rating-star" :class="{ 'no-rating': book.stars === 0 }">
            <span class="star-value">
              {{ book.stars > 0 ? book.stars : '—' }}
            </span>
            <FontAwesomeIcon icon="star" class="star-icon" />
          </div>
          <img :src="book.img" :alt="book.title" width="200">
        </div>

        <div class="book-actions">
          <button @click="editBook(book)" class="btn-edit">Редактировать</button>
          <button @click="deleteBook(book.id)" class="btn-delete">Удалить</button>
        </div>

      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import bookImg1 from '@/assets/images/book1.png'
import bookImg2 from '@/assets/images/book2.png'
import bookImg3 from '@/assets/images/book3.png'

const editingBookId = ref(null)
const showForm = ref(false)

const totalBooks = computed(() => books.value.length)

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
    genres: ['бизнес-книга'],
    img: bookImg3,
    isAdult: false,
    stars: 0
  }
])

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

const deleteBook = (id) => {
  if (confirm('Вы уверены, что хотите удалить эту книгу?')) {
    books.value = books.value.filter(book => book.id !== id)
  }
}

const setStars = (book, stars) => {
  book.stars = stars
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

select[multiple] {
  height: 100px;
}

.form input,
.form textarea,
.form select {
  width: 100%;
  margin: 8px 0;
  padding: 8px;
  border: 1px solid #ddd;
  box-sizing: border-box;
}

.booksContainer {
  max-width: 1200px;
  margin: 30px auto;
  padding: 0 40px;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 24px;
}

.cardItem {
  background: white;
  border: 1px solid #e1e8ed;
  border-radius: 4px;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.cardItem_info {
  padding: 20px;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
}

.cardItem_info h3 {
  margin: 0 0 12px 0;
  font-size: 18px;
  font-weight: 700;
  color: #14171a;
  line-height: 1.3;
  overflow: hidden;
  min-height: 46px;
}

.cardItem_info p {
  margin: 0 0 16px 0;
  color: #657786;
  font-size: 14px;
  line-height: 1.5;
  overflow: hidden;
  flex-grow: 1;
}

.cardItem_info>span {
  display: block;
  margin-bottom: 8px;
  font-size: 14px;
  color: #657786;
}

.cardItem_info span span {
  display: inline !important;
  color: #1da1f2;
}

.cardItem_info span span:not(:last-child)::after {
  content: ', ';
  color: #657786;
}

.cardItem_img {
  position: relative;
  height: 280px;
  background: #f5f8fa;
  border-bottom: 1px solid #e1e8ed;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;
}

.cardItem_img img {
  width: 190px;
  height: 270px;
  object-fit: cover;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* .cardItem_info span svg {
  color: #ffad1f;
  margin-left: 4px;
} */

.rating-stars {
  display: flex;
  align-items: center;
  margin-top: 10px;
  flex-wrap: nowrap;
  overflow: visible;
}

.rating-stars>span:first-child {
  color: #657786;
  font-size: 14px;
  margin-right: 8px;
  flex-shrink: 0;
  white-space: nowrap;
}

.rating-stars>span.star {
  cursor: pointer;
  transition: all 0.2s ease;
  font-size: 18px;
  flex-shrink: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 20px;
  height: 20px;
  margin: 0 2px;
}

.rating-stars>span.star svg {
  width: 100%;
  height: 100%;
  display: block;
}

.rating-stars>span.star.active svg {
  color: #FFD700 !important;
}

.rating-stars>span.star.inactive svg {
  color: #e0e0e0 !important;
}

.rating-stars>span.star:hover svg {
  transform: scale(1.3);
  color: #ffcc00 !important;
}

.big-rating-star {
  position: absolute;
  top: 12px;
  right: 12px;
  width: 40px;
  height: 40px;
  z-index: 10;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  background: none !important;
  border-radius: 0 !important;
  box-shadow: none !important;
}

.big-rating-star:not(.no-rating) .star-icon {
  color: #FFD700 !important;
  filter: drop-shadow(0 0 5px rgba(255, 215, 0, 0.5));
}

.big-rating-star.no-rating .star-icon {
  color: #b0b0b0 !important;
}

.big-rating-star .star-icon {
  position: absolute;
  width: 100%;
  height: 100%;
  font-size: 40px;
}

.big-rating-star .star-value {
  position: relative;
  z-index: 11;
  font-size: 14px;
  font-weight: bold;
  color: white;
  pointer-events: none;
}

.big-rating-star.no-rating .star-value {
  color: #666;
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

.form-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 100;
}

.form {
  background: white;
  padding: 20px;
  border-radius: 8px;
  min-width: 450px;
}


.form-buttons {
  display: flex;
  gap: 10px;
  margin-top: 16px;
}

.form-buttons button {
  flex: 1;
  padding: 8px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.form-buttons button:first-child {
  background: #1da1f2;
  color: white;
}

.form-buttons button:last-child {
  background: #ddd;
}

.checkbox {
  display: flex;
  align-items: center;
  gap: 8px;
  margin: 8px 0;
}

.checkbox input[type="checkbox"] {
  width: auto;
  margin: 0;
}

.errors-block {
  background: #fee;
  border: 1px solid #e0245e;
  border-radius: 4px;
  padding: 10px;
  margin: 16px 0;
}

.errors-block p {
  color: #e0245e;
  margin: 4px 0;
  font-size: 12px;
}

.errors-block p:first-child {
  margin-top: 0;
}

.errors-block p:last-child {
  margin-bottom: 0;
}

.book-actions {
  display: flex;
  gap: 8px;
  padding: 15px 20px;
  border-top: 1px solid #e1e8ed;
  background: #f9f9f9;
}

.btn-edit {
  background: #1da1f2;
  color: white;
  border: none;
  padding: 8px;
  border-radius: 4px;
  font-size: 14px;
  cursor: pointer;
  flex: 1;
}

.btn-delete {
  background: #e0245e;
  color: white;
  border: none;
  padding: 8px;
  border-radius: 4px;
  font-size: 14px;
  cursor: pointer;
  flex: 1;
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