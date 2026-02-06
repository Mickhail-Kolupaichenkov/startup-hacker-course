<template>
    <AppLayout>
        <div class="edit-book-page">
            <div class="page-header">
                <h1>Редактировать книгу</h1>
                <Link href="/" class="back-link">← Назад к списку</Link>
            </div>

            <div v-if="book" class="book-form-container">
                <BookForm :initial-data="bookData" :errors="errors"
                    :submit-text="form.processing ? 'Сохранение...' : 'Сохранить изменения'"
                    :processing="form.processing" @submit="submitForm" @cancel="cancel" />
            </div>

            <div v-else class="loading">
                Загрузка книги...
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '../../layouts/AppLayout.vue'
import BookForm from '../../components/BookForm.vue'
import { Link, useForm, router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    book: Object,
    errors: Object
})

// Создаем реактивную форму с useForm
const form = useForm({
    title: props.book?.title || '',
    description: props.book?.description || '',
    genres: props.book?.genres || [],
    isAdult: props.book?.isAdult || false,
    cover: null,
    removeCover: false
})

// Вычисляем данные для формы
const bookData = computed(() => ({
    title: props.book?.title || '',
    description: props.book?.description || '',
    genres: props.book?.genres || [],
    isAdult: props.book?.isAdult || false,
    existingCover: props.book?.img || null,
    cover: null,
    removeCover: false
}))

// Обработка отправки формы
function submitForm(formData) {
    console.log('Editing book with data:', formData)

    // Создаем FormData для корректной отправки файла
    const formDataObj = new FormData()

    // Добавляем текстовые поля
    formDataObj.append('_method', 'PUT')
    formDataObj.append('title', formData.title)
    formDataObj.append('description', formData.description || '')
    formDataObj.append('isAdult', formData.isAdult ? '1' : '0')
    formDataObj.append('removeCover', formData.removeCover ? '1' : '0')

    // Добавляем жанры
    if (Array.isArray(formData.genres)) {
        formData.genres.forEach((genre, index) => {
            formDataObj.append(`genres[${index}]`, genre)
        })
    } else {
        // Если genres не массив, добавляем как пустой массив
        formDataObj.append('genres[]', '')
    }

    // Добавляем файл, если он есть
    if (formData.cover instanceof File) {
        formDataObj.append('cover', formData.cover)
    }

    form.processing = true

    router.post(`/books/${props.book.id}`, formDataObj, {
        preserveScroll: true,
        preserveState: true,
        forceFormData: true,
        onSuccess: () => {
            console.log('Книга успешно обновлена!')
            form.processing = false
        },
        onError: (errors) => {
            console.log('Ошибки при обновлении:', errors)
            form.processing = false
        },
        onFinish: () => {
            form.processing = false
        }
    })
}

function cancel() {
    window.history.back()
}
</script>

<style scoped>
.edit-book-page {
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

.loading {
    text-align: center;
    padding: 40px;
    color: #666;
    font-size: 16px;
}
</style>