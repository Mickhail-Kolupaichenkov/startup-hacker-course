<template>
    <AppLayout>
        <div class="edit-user-page">
            <div class="page-header">
                <h1>Редактирование пользователя</h1>
                <Link href="/admin/dashboard" class="back-link">← Назад к списку</Link>
            </div>

            <div class="user-form-container">
                <div class="user-form">
                    <div class="form-group">
                        <label for="name">Имя:</label>
                        <input id="name" v-model="form.name" type="text" class="form-input"
                            :class="{ 'error': form.errors.name }">
                        <div v-if="form.errors.name" class="error-message">{{ form.errors.name }}</div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input id="email" v-model="form.email" type="email" class="form-input"
                            :class="{ 'error': form.errors.email }">
                        <div v-if="form.errors.email" class="error-message">{{ form.errors.email }}</div>
                    </div>

                    <div class="form-group">
                        <label for="role">Роль:</label>
                        <select id="role" v-model="form.role" class="form-select" :class="{ 'error': form.errors.role }"
                            :disabled="user.id === auth?.user?.id">
                            <option value="user">Пользователь</option>
                            <option value="admin">Администратор</option>
                        </select>
                        <div v-if="form.errors.role" class="error-message">{{ form.errors.role }}</div>
                        <div v-if="user.id === auth?.user?.id" class="form-hint">
                            Вы не можете изменить свою роль
                        </div>
                    </div>

                    <div class="form-buttons">
                        <SButton @click="handleSubmit" :disabled="form.processing">
                            {{ form.processing ? 'Сохранение...' : 'Сохранить' }}
                        </SButton>
                        <SButton outlined @click="cancel">Отмена</SButton>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '../../layouts/AppLayout.vue'
import { SButton } from 'startup-ui'
import { Link, router } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    user: Object,
    auth: Object,
    errors: Object
})

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    role: props.user.role,
})

function handleSubmit() {
    if (!form.name || !form.name.trim()) {
        alert('Пожалуйста, введите имя пользователя')
        return
    }

    if (!form.email || !form.email.trim()) {
        alert('Пожалуйста, введите email')
        return
    }

    if (!form.role) {
        alert('Пожалуйста, выберите роль')
        return
    }

    // Используйте router.put()
    router.put(`/admin/users/${props.user.id}`, form, {
        preserveScroll: true
    })
}

function cancel() {
    window.history.back()
}
</script>

<style scoped>
.edit-user-page {
    max-width: 600px;
    margin: 30px auto;
    padding: 0 40px;
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

.user-form-container {
    background: white;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.user-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
}

.form-input,
.form-select {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.form-input.error,
.form-select.error {
    border-color: #e0245e;
}

.error-message {
    color: #e0245e;
    font-size: 12px;
    margin-top: 5px;
}

.form-hint {
    font-size: 12px;
    color: #666;
    margin-top: 5px;
    font-style: italic;
}

.form-buttons {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}
</style>