<template>
  <div class="app">
    <!--<FontAwesomeIcon icon="star" />-->
    <div class="booksContainer">
      <div class="cardItem" v-for="book in books" :key="book.id">
        <h3>{{ book.title }}</h3>
        <img :src="book.img" :alt="book.title" width="200">
        <p>{{ book.description }}</p>
        <span>Жанр: {{ book.genre }}</span>
        <span v-if="book.isAdult"> 18+</span>
        <span>Рейтинг: {{ book.stars }}
          <FontAwesomeIcon icon="star" />
        </span>
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
