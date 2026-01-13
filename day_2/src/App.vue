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
  background: linear-gradient(135deg, #b5d622 0%, #e57030 100%);
  padding: 20px;
}

.booksContainer {
  box-sizing: border-box;
  width: 90%;
  max-width: 1400px;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 30px;
  padding: 40px;
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 24px;
  margin: 20px auto;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.cardItem {
  display: flex;
  flex-direction: column;
  gap: 20px;
  border: 1px solid #e0e0e0;
  border-radius: 20px;
  overflow: hidden;
  background: white;
  padding: 25px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
}

.cardItem:hover {
  transform: translateY(-8px);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
  border-color: #764ba2;
}

.cardItem_info {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.cardItem_info h3 {
  margin: 0;
  font-size: 1.4em;
  font-weight: 700;
  color: #2d3748;
  line-height: 1.3;
  min-height: 3.6em;
  display: -webkit-box;
  overflow: hidden;
}

.cardItem_info p {
  margin: 0;
  color: #4a5568;
  line-height: 1.6;
  font-size: 0.95em;
  display: -webkit-box;
  overflow: hidden;
  flex-grow: 1;
}

.cardItem_info>span {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px 0;
  border-top: 1px solid #f0f0f0;
  color: #718096;
  font-size: 0.9em;
}

.cardItem_info>span:first-of-type {
  border-top: none;
}

.cardItem_info>span b {
  background: #ff4757;
  color: white;
  padding: 4px 10px;
  border-radius: 12px;
  font-size: 0.8em;
  font-weight: 600;
  margin-left: auto;
}

.cardItem_info>span:last-child {
  color: #f6b93b;
  font-weight: 600;
  font-size: 1em;
}

.cardItem_img {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 220px;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  border-radius: 12px;
  overflow: hidden;
  position: relative;
}

.cardItem_img::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #667eea, #764ba2);
}

.cardItem_img img {
  width: 180px;
  height: 180px;
  object-fit: contain;
  border-radius: 8px;
  transition: transform 0.3s ease;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.cardItem:hover .cardItem_img img {
  transform: scale(1.05);
}

.cardItem_info span svg {
  color: #f6b93b;
  margin-left: 5px;
}

.header {
  text-align: center;
  margin-bottom: 40px;
  padding: 30px 20px;
}

.header h1 {
  color: white;
  font-size: 2.5em;
  margin: 0 0 10px 0;
  text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.header p {
  color: rgba(255, 255, 255, 0.9);
  font-size: 1.1em;
  margin: 0;
}
</style>