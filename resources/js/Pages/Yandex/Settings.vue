<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    organization: Object,
});

const form = useForm({
    yandex_url: props.organization?.yandex_url || '',
});

const submit = () => {
    form.post(route('yandex.settings.store'));
};

defineOptions({
    layout: (h, page) => h(AppLayout, { organization: page.props.organization, activePage: 'settings' }, () => page)
});
</script>

<template>
    <Head title="Настройки - Яндекс Карты" />

    <h2 class="text-[16px] font-semibold text-[#252733] mb-4 tracking-[0.2px] leading-5" style="font-family: 'Mulish', sans-serif;">Подключить Яндекс</h2>
    
    <p class="text-[12px] font-semibold text-[#6C757D] mb-2 tracking-[0.2px] leading-5" style="font-family: 'Mulish', sans-serif;">
        Укажите ссылку на Яндекс, пример 
        <a 
            href="https://yandex.ru/maps/org/samoye_populyarnoye_kafe/1010501395/reviews/" 
            target="_blank"
            class="text-[12px] font-normal text-[#788397] underline leading-[15px]"
            style="font-family: 'Mulish', sans-serif;"
        >
            https://yandex.ru/maps/org/samoye_populyarnoye_kafe/1010501395/reviews/
        </a>
    </p>

    <form @submit.prevent="submit">
        <div class="mb-4">
            <input
                v-model="form.yandex_url"
                type="url"
                placeholder="Ссылка"
                class="w-full h-[24px] max-w-xl px-3 py-2 bg-white border border-[#DCE4EA] rounded-[6px] text-[12px] font-normal text-[#788397] leading-[15px] focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'border-red-500': form.errors.yandex_url }"
                style="font-family: 'Mulish', sans-serif;"
            />
            <p v-if="form.errors.yandex_url" class="mt-1 text-sm text-red-500">
                {{ form.errors.yandex_url }}
            </p>
        </div>

        <button
            type="submit"
            :disabled="form.processing"
            class="px-6 py-1 bg-[#339AF0] text-white text-xs font-semibold rounded-[6px] leading-[17px] hover:bg-[#228be6] focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
            style="font-family: 'Inter', sans-serif;"
        >
            <span v-if="form.processing">Сохранение...</span>
            <span v-else>Сохранить</span>
        </button>
    </form>

  
</template>
