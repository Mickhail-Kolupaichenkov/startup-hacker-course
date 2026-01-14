<template>
<form @submit.prevent="savebook" class="book-form">
    <input type="hidden" name="id" v-model="form.id" />
    <div class="book-form-input">
        <label for="name">Название</label>
        <input type="text" name="name" v-model="form.name"/>
        <span class="book-form-error">{{ errors.name }}</span>
    </div>
    <div class="book-form-input">
        <label for="description">Описание</label>
        <textarea type="text" name="description" v-model="form.description" class="book-form-textarea" />
        <span class="book-form-error">{{ errors.description }}</span>
    </div>
    <div class="book-form-input">
        <label for="image">Обложка</label>
        <input type="text" name="image" v-model="form.image" />
        <span class="book-form-error">{{ errors.image }}</span>
    </div>
    <div class="book-form-input">
        <label for="genre">Жанры</label>
        <select name="genre" v-model="form.genres" multiple>
            <option v-for="option in genres" :key="option.value" :value="option.value">
                {{ option.text }}
            </option>
        </select>
        <span class="book-form-error">{{ errors.genres }}</span>
    </div>
    <div class="book-form-input">
        <label for="genre" class="book-form-checkbox-label">
            <input type="checkbox" v-model="form.inTheaters" :true-value="true" :false-value="false" class="book-form-checkbox" />
            <span>18+</span>
        </label>
        <span class="book-form-error">{{ errors.inTheaters }}</span>
    </div>
    <div class="book-form-actions">
        <button type="button" class="button" @click="cancelForm">Отменить</button>
        <button type="submit">
            <span v-if="form.id">Сохранить</span>
            <span v-else>Создать</span>
        </button>
    </div>
</form>
</template>
<script setup>
import { reactive } from "vue";

const emit = defineEmits(["update:modelValue", "cancel"]);
const props = defineProps({
    modelValue: { type: Object, default: null },
});

const genres = reactive([
    { text: "Драма", value: "Драма" },
    { text: "Детектив", value: "Детектив" },
    { text: "Приключения", value: "Приключения" },
    { text: "Философия", value: "Философия" },
]);
const errors = reactive({
    name: null,
    description: null,
    image: null,
    inTheaters: null,
    genres: null,
    rating: null,
});

const form = reactive({
    id: props.modelValue?.id,
    name: props.modelValue?.name,
    description: props.modelValue?.description,
    image: props.modelValue?.image,
    inTheaters: props.modelValue?.inTheaters || false,
    genres: props.modelValue?.genres || [],
    rating: props.modelValue?.rating || null,
});

const validations = reactive({
    name: "required",
    genres: "required",
});

const validationRules = (rule) => {
    if (rule === "required") return /^ *$/;

    return null;
};

function validate() {
    let valid = true;
    clearErrors();
    for (const [field, rule] of Object.entries(validations)) {
    const validation = validationRules(rule);
    if (validation) {
        if (validation.test(form[field] || "")) {
        errors[field] = `${field} is ${rule}`;
        valid = false;
        }
    }
    }

    return valid;
}
function cancelForm() {
    cleanUpForm();
    emit("cancel");
}

function savebook() {
    if (validate()) {
    const data = {
        id: form.id || Number(Date.now()),
        name: form.name,
        description: form.description,
        image: form.image,
        genres: form.genres,
        inTheaters: form.inTheaters,
        rating: form.rating,
    };
    emit("update:modelValue", data);
    }
}

function cleanUpForm() {
    form.name = null;
    form.description = null;
    form.image = null;
    form.genres = null;
    form.inTheaters = false;
    clearErrors();
}

function clearErrors() {
    errors.name = null;
    errors.description = null;
    errors.image = null;
    errors.genres = null;
    errors.inTheaters = null;
}
</script>
<style lang="scss">
.book-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding: 10px;

    label {
        display: inline-block;
        width: 100px;
    }

    &-actions {
        display: flex;
        gap: 10px;
    }
}
</style>