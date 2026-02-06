<template>
    <AppLayout>
        <div class="create-book-page">
            <div class="page-header">
                <h1>Добавить новую книгу</h1>
                <Link href="/" class="back-link">← Назад к списку</Link>
            </div>

            <div class="book-form-container">
                <BookForm :errors="form.errors" :submit-text="form.processing ? 'Сохранение...' : 'Добавить книгу'"
                    :processing="form.processing" @submit="submit" @cancel="cancel" />
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '../../layouts/AppLayout.vue'
import BookForm from '../../components/BookForm.vue'
import { Link, useForm } from '@inertiajs/vue3'

const form = useForm({
    title: '',
    description: '',
    genres: [],
    isAdult: false,
    cover: null,
})

function submit(formData) {
    console.log('Creating book with data:', formData)

    // Обновляем данные формы
    form.title = formData.title
    form.description = formData.description
    form.genres = formData.genres
    form.isAdult = formData.isAdult
    form.cover = formData.cover

    // Отправляем POST запрос
    form.post('/books', {
        preserveScroll: true,
        onSuccess: () => {
            console.log('Книга успешно создана!')
        },
        onError: (errors) => {
            console.log('Ошибки при создании:', errors)
        }
    })
}

function cancel() {
    window.history.back()
}
</script>

<style scoped>
.create-book-page {
    max-width: 800px;
    margin: 30px auto;
    padding: 0 20px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e1e8ed;
}

.page-header h1 {
    margin: 0;
    font-size: 24px;
    color: #333;
}

.back-link {
    color: #F04F0A;
    text-decoration: none;
    font-size: 14px;
}

.back-link:hover {
    text-decoration: underline;
}

.book-form-container {
    background: white;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
</style>