<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    organization: Object,
    reviews: Object,
});

const syncing = ref(false);

const syncReviews = () => {
    syncing.value = true;
    router.post(route('yandex.reviews.sync'), {}, {
        onFinish: () => {
            syncing.value = false;
        },
    });
};

const formatDate = (date) => {
    if (!date) return '';
    const d = new Date(date);
    const day = String(d.getDate()).padStart(2, '0');
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const year = d.getFullYear();
    const hours = String(d.getHours()).padStart(2, '0');
    const minutes = String(d.getMinutes()).padStart(2, '0');
    return `${day}.${month}.${year} ${hours}:${minutes}`;
};

const formatPhone = (phone) => {
    return '+7 900 540 40 40';
};

defineOptions({
    layout: (h, page) => h(AppLayout, { organization: page.props.organization, activePage: 'reviews' }, () => page)
});
</script>

<template>
    <Head title="Отзывы - Яндекс Карты" />

    <div class="flex gap-8">
        <!-- Reviews List -->
        <div class="flex-1">
            <!-- Yandex Maps label -->
            <div class="flex items-center space-x-2 mb-6">
                <div class="w-2 h-2 bg-[#FC5230] rounded-full"></div>
                <span class="text-[12px] font-normal text-[#252733]" style="font-family: 'Mulish', sans-serif;">Яндекс Карты</span>
            </div>

            <!-- Reviews -->
            <div class="space-y-4">
                <div
                    v-for="review in reviews.data"
                    :key="review.id"
                    class="bg-[#FFF8E6] rounded-[8px] p-5"
                >
                    <!-- Header row: date, branch, marker, stars -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-2">
                            <span class="text-[12px] font-bold text-[#252733]" style="font-family: 'Mulish', sans-serif;">{{ formatDate(review.published_at) }}</span>
                            <span class="text-[12px] font-bold text-[#252733]" style="font-family: 'Mulish', sans-serif;">Филиал 1</span>
                            <div class="w-2 h-2 bg-[#FC5230] rounded-full"></div>
                        </div>
                        <div class="flex items-center space-x-0.5">
                            <svg v-for="i in 5" :key="i" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M8 1L10.163 5.27865L15 5.95082L11.5 9.29508L12.326 14L8 11.7787L3.674 14L4.5 9.29508L1 5.95082L5.837 5.27865L8 1Z" :fill="i <= review.rating ? '#FFCB45' : '#E0E0E0'"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Author row -->
                    <div class="flex items-center space-x-3 mb-3">
                        <span class="text-[12px] font-bold text-[#252733]" style="font-family: 'Mulish', sans-serif;">{{ review.author_name }}</span>
                        <span class="text-[12px] font-normal text-[#A4A6B3]" style="font-family: 'Mulish', sans-serif;">{{ formatPhone() }}</span>
                    </div>

                    <!-- Review text -->
                    <p class="text-[12px] font-normal text-[#252733] leading-[18px]" style="font-family: 'Mulish', sans-serif;">
                        {{ review.text || 'Без текста' }}
                    </p>
                </div>

                <!-- Empty State -->
                <div v-if="reviews.data.length === 0" class="bg-white rounded-[8px] shadow-sm p-12 text-center">
                    <p class="text-[#6C757D] text-[14px]" style="font-family: 'Mulish', sans-serif;">Отзывов пока нет</p>
                    <button 
                        @click="syncReviews"
                        :disabled="syncing"
                        class="mt-4 px-6 py-2 bg-[#339AF0] text-white text-[14px] font-semibold rounded-[6px] hover:bg-[#228be6] disabled:opacity-50"
                        style="font-family: 'Inter', sans-serif;"
                    >
                        Загрузить отзывы
                    </button>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="reviews.links && reviews.links.length > 3" class="mt-6 flex justify-center space-x-2">
                <template v-for="link in reviews.links" :key="link.label">
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        class="px-3 py-2 text-[12px] rounded-[6px]"
                        :class="link.active ? 'bg-[#339AF0] text-white' : 'bg-white text-[#6C757D] hover:bg-gray-100'"
                        style="font-family: 'Mulish', sans-serif;"
                        v-html="link.label"
                    />
                    <span
                        v-else
                        class="px-3 py-2 text-[12px] text-[#A4A6B3]"
                        style="font-family: 'Mulish', sans-serif;"
                        v-html="link.label"
                    />
                </template>
            </div>
        </div>

        <!-- Rating Sidebar -->
        <div class="w-[180px]">
            <div>
                <!-- Rating number with stars -->
                <div class="flex items-center space-x-3">
                    <span class="text-[40px] font-normal text-[#A4A6B3] leading-none" style="font-family: 'Mulish', sans-serif;">{{ organization?.rating || '4.7' }}</span>
                    <div class="flex items-center space-x-0.5">
                        <svg v-for="i in 5" :key="i" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 16 16" fill="none">
                            <path d="M8 1L10.163 5.27865L15 5.95082L11.5 9.29508L12.326 14L8 11.7787L3.674 14L4.5 9.29508L1 5.95082L5.837 5.27865L8 1Z" :fill="i <= Math.round(organization?.rating || 4.7) ? '#FFCB45' : '#E0E0E0'"/>
                        </svg>
                    </div>
                </div>
                
                <!-- Total reviews -->
                <p class="text-[12px] font-normal text-[#6C757D] mt-1 text-right" style="font-family: 'Mulish', sans-serif;">
                    Всего отзывов: <span class="font-bold">{{ organization?.total_reviews?.toLocaleString('ru-RU') || '1 145' }}</span>
                </p>
            </div>
        </div>
    </div>
</template>
