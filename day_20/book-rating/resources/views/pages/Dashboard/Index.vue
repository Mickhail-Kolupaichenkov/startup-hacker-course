<template>
    <AppLayout>
        <div class="admin-page">
            <div class="admin-header">
                <h1>👑 Управление пользователями</h1>
                <p>Административная панель управления пользователями системы</p>
            </div>

            <div class="users-table-container">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Роль</th>
                            <th>Книг</th>
                            <th>Дата регистрации</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users" :key="user.id">
                            <td>{{ user.id }}</td>
                            <td>{{ user.name }}</td>
                            <td>{{ user.email }}</td>
                            <td>
                                <span class="role-badge" :class="user.role">
                                    {{ user.role === 'admin' ? 'Админ' : 'Пользователь' }}
                                </span>
                            </td>
                            <td>{{ user.books_count }}</td>
                            <td>{{ user.created_at }}</td>
                            <td class="actions">
                                <SButton size="small" outlined @click="editUser(user)">
                                    Редактировать
                                </SButton>
                                <SButton size="small" color="danger" @click="deleteUser(user)"
                                    :disabled="user.id === auth?.user?.id">
                                    Удалить
                                </SButton>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div v-if="users.length === 0" class="empty-table">
                    <p>Пользователей пока нет</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '../../layouts/AppLayout.vue'
import { SButton } from 'startup-ui'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    users: Array,
    auth: Object
})

function editUser(user) {
    router.get(`/admin/users/${user.id}/edit`)
}

function deleteUser(user) {
    if (confirm(`Удалить пользователя ${user.name}? Все его книги также будут удалены.`)) {
        router.delete(`/admin/users/${user.id}`)
    }
}
</script>

<style scoped>
.admin-page {
    max-width: 1200px;
    margin: 30px auto;
    padding: 0 40px;
}

.admin-header {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e1e8ed;
}

.admin-header h1 {
    font-size: 24px;
    color: #333;
    margin-bottom: 10px;
}

.admin-header p {
    color: #666;
    font-size: 16px;
}

.users-table-container {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.users-table {
    width: 100%;
    border-collapse: collapse;
}

.users-table th {
    background: #f8f9fa;
    padding: 15px;
    text-align: left;
    font-weight: 600;
    color: #333;
    border-bottom: 1px solid #e1e8ed;
}

.users-table td {
    padding: 15px;
    border-bottom: 1px solid #f1f1f1;
}

.users-table tr:last-child td {
    border-bottom: none;
}

.role-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.role-badge.admin {
    background: #F04F0A;
    color: white;
}

.role-badge.user {
    background: #e1e8ed;
    color: #657786;
}

.actions {
    display: flex;
    gap: 8px;
}

.empty-table {
    text-align: center;
    padding: 40px;
    color: #666;
}
</style>