<template>
    <div class="about-page">
        <div class="about-header">
            <h1>О нас</h1>
            <p>Немного информации о нашем проекте "Моя коллекция книг"</p>
        </div>

        <div class="about-content">
            <div class="about-card">
                <div class="card-body">
                    <p>
                        "Моя коллекция книг" — это простое и удобное веб-приложение для учета
                        личной библиотеки. Здесь вы можете хранить информацию о прочитанных книгах,
                        оценивать их и организовывать по жанрам.
                    </p>

                    <p>
                        Проект создан с использованием Vue.js и представляет собой демонстрацию
                        возможностей современной фронтенд-разработки.
                    </p>

                </div>
            </div>

        </div>
    </div>
</template>

<script setup>

</script>

<style scoped>
.about-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px 40px 60px;
}

.about-header {
    text-align: center;
    margin-bottom: 40px;
    padding: 30px 0;
    border-bottom: 1px solid #e1e8ed;
}

.about-header h1 {
    margin: 0 0 15px 0;
    font-size: 28px;
    font-weight: 700;
    color: #1da1f2;
}

.about-header p {
    margin: 0;
    color: #657786;
    font-size: 16px;
}

.about-content {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.about-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    padding: 0;
}

.card-body {
    padding: 30px;
}

.card-body p {
    margin: 0 0 20px 0;
    line-height: 1.6;
    color: #333;
    font-size: 16px;
}

.card-body p:last-child {
    margin-bottom: 0;
}
</style>