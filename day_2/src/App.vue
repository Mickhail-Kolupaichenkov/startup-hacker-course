<template>
  <div class="app">
    <div class="header">
      <h1>📖 Моя коллекция книг</h1>
      <p>Здесь собраны мои любимые произведения</p>
    </div>

    <div class="booksContainer">
      <div class="cardItem" v-for="book in books" :key="book.id">
        <div class="cardItem_info">
          <h3>{{ book.title }}</h3>
          <p>{{ book.description }}</p>
          <span>Жанр: {{ book.genre }} <b v-if="book.isAdult">18+</b></span>
          <span>Рейтинг: {{ book.stars }}
            <FontAwesomeIcon icon="star" />
          </span>
        </div>
        <div class="cardItem_img">
          <img :src="book.img" :alt="book.title" width="200">
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import bookImg1 from '@/assets/images/book1.png'
import bookImg2 from '@/assets/images/book2.png'
import bookImg3 from '@/assets/images/book3.png'

const books = ref([
  {
    id: 1,
    title: 'Мастер и Маргарита',
    description: '«Мастер и Маргарита» — роман Михаила Афанасьевича Булгакова, работа над которым началась, по одним данным, в 1928 году, по другим — в 1929-м, и продолжалась вплоть до смерти писателя в марте 1940 года.',
    genre: 'Роман',
    img: bookImg1,
    isAdult: false,
    stars: 5
  },
  {
    id: 2,
    title: 'Бэтман Аполло',
    description: 'Главный герой — Рама, сравнительно недавно ставший вампиром. Ему предстоит разобраться в вампирском мироздании и подняться по иерархической лестнице, став Кавалером Ночи и ныряльщиком.',
    genre: 'Роман',
    img: bookImg2,
    isAdult: true,
    stars: 4
  },
  {
    id: 3,
    title: 'Стратегическое управление на основе маркетингового анализа. Инструменты, проблемы, ситуации',
    description: 'Книга в которой автор описывает классические и собственные инструменты стратегического управления и маркетингового анализа.',
    genre: 'бизнес-книга',
    img: bookImg3,
    isAdult: false,
    stars: 4
  }
])
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

.cardItem_info span {
  display: block;
  margin-bottom: 8px;
  font-size: 14px;
  color: #657786;
}

.cardItem_info span b {
  background: #1da1f2;
  color: white;
  padding: 2px 8px;
  border-radius: 2px;
  font-size: 12px;
  margin-left: 8px;
}

.cardItem_info span:last-child {
  color: #ffad1f;
  font-weight: 600;
  margin-bottom: 0;
}

.cardItem_img {
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

.cardItem_info span svg {
  color: #ffad1f;
  margin-left: 4px;
}
</style>