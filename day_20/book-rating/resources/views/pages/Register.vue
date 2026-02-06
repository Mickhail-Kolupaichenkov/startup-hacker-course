<template>
    <div class="auth-page">
        <div class="auth-container">
            <div class="auth-logo">
                <h1>📚</h1>
                <h2>Коллекция книг</h2>
            </div>

            <div class="auth-card">
                <h3 class="auth-title">Создание аккаунта</h3>
                <p class="auth-subtitle">Заполните форму для регистрации</p>

                <form @submit.prevent="submit" class="auth-form">
                    <div class="form-group">
                        <label for="name" class="form-label">Имя</label>
                        <input id="name" v-model="form.name" type="text" required placeholder="Ваше имя"
                            class="form-input" :class="{ 'input-error': form.errors.name }">
                        <div v-if="form.errors.name" class="error-text">
                            {{ form.errors.name }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" v-model="form.email" type="email" required placeholder="example@mail.com"
                            class="form-input" :class="{ 'input-error': form.errors.email }">
                        <div v-if="form.errors.email" class="error-text">
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Пароль</label>
                        <input id="password" v-model="form.password" type="password" required
                            placeholder="Минимум 8 символов" class="form-input"
                            :class="{ 'input-error': form.errors.password }">
                        <div v-if="form.errors.password" class="error-text">
                            {{ form.errors.password }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
                        <input id="password_confirmation" v-model="form.password_confirmation" type="password" required
                            placeholder="Повторите пароль" class="form-input"
                            :class="{ 'input-error': form.errors.password_confirmation }">
                        <div v-if="form.errors.password_confirmation" class="error-text">
                            {{ form.errors.password_confirmation }}
                        </div>
                    </div>

                    <button type="submit" class="submit-button" :class="{ 'button-loading': form.processing }"
                        :disabled="form.processing">
                        <span class="button-text" v-if="!form.processing">Зарегистрироваться</span>
                        <span class="button-loading-text" v-else>Регистрация...</span>
                    </button>

                    <div class="auth-divider">
                        <span>или</span>
                    </div>

                    <div class="auth-footer">
                        <p class="footer-text">
                            Уже есть аккаунт?
                            <Link href="/login" class="footer-link">
                                Войдите
                            </Link>
                        </p>
                    </div>
                </form>
            </div>

            <div class="auth-copyright">
                <p>© 2026 Коллекция книг. Все права защищены.</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post('/register')
}
</script>

<style scoped>
.auth-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.auth-container {
    width: 100%;
    max-width: 440px;
}

.auth-logo {
    text-align: center;
    margin-bottom: 30px;
}

.auth-logo h1 {
    font-size: 48px;
    margin: 0 0 10px 0;
}

.auth-logo h2 {
    font-size: 24px;
    color: #2c3e50;
    margin: 0;
    font-weight: 600;
}

.auth-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    padding: 40px;
    margin-bottom: 20px;
}

.auth-title {
    font-size: 24px;
    color: #2c3e50;
    margin: 0 0 8px 0;
    font-weight: 600;
    text-align: center;
}

.auth-subtitle {
    font-size: 14px;
    color: #7f8c8d;
    margin: 0 0 30px 0;
    text-align: center;
}

.auth-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    font-size: 14px;
    font-weight: 500;
    color: #2c3e50;
    margin-bottom: 8px;
}

.form-input {
    padding: 14px 16px;
    border: 2px solid #e8ecef;
    border-radius: 8px;
    font-size: 15px;
    color: #2c3e50;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.form-input:focus {
    outline: none;
    border-color: #F04F0A;
    background: white;
    box-shadow: 0 0 0 3px rgba(240, 79, 10, 0.1);
}

.form-input.input-error {
    border-color: #e74c3c;
}

.form-input.input-error:focus {
    box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
}

.error-text {
    font-size: 13px;
    color: #e74c3c;
    margin-top: 6px;
}

.submit-button {
    padding: 16px;
    background: linear-gradient(135deg, #F04F0A 0%, #e0450a 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.submit-button:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(240, 79, 10, 0.3);
}

.submit-button:active:not(:disabled) {
    transform: translateY(0);
}

.submit-button:disabled {
    background: #bdc3c7;
    cursor: not-allowed;
}

.button-text {
    display: block;
}

.button-loading-text {
    display: block;
}

.auth-divider {
    display: flex;
    align-items: center;
    margin: 10px 0;
}

.auth-divider::before,
.auth-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e8ecef;
}

.auth-divider span {
    padding: 0 15px;
    color: #7f8c8d;
    font-size: 13px;
}

.auth-footer {
    text-align: center;
}

.footer-text {
    font-size: 14px;
    color: #7f8c8d;
    margin: 0;
}

.footer-link {
    color: #F04F0A;
    text-decoration: none;
    font-weight: 500;
    margin-left: 5px;
    transition: color 0.3s;
}

.footer-link:hover {
    color: #e0450a;
    text-decoration: underline;
}

.auth-copyright {
    text-align: center;
}

.auth-copyright p {
    font-size: 13px;
    color: #7f8c8d;
    margin: 0;
}

@keyframes buttonLoading {
    0% {
        transform: translateX(-100%);
    }

    100% {
        transform: translateX(100%);
    }
}

.submit-button.button-loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg,
            transparent,
            rgba(255, 255, 255, 0.2),
            transparent);
    animation: buttonLoading 1.5s infinite;
}
</style>