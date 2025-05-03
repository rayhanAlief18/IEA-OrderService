<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
defineProps({
    orders: Array,
    user_id: Number
});
</script>

<template>

    <Head title="Detail Order" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Order Karyawan #{{ user_id }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-md rounded my-6 overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">Pelayanan</th>
                                <th class="py-3 px-6 text-left">Biaya</th>
                                <th class="py-3 px-6 text-left">Durasi</th>
                                <th class="py-3 px-6 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm font-light">
                            <tr v-for="(order, index) in orders" :key="index" class="border-b hover:bg-gray-100">
                                <td class="py-3 px-6 text-left">{{ order.pelayanan }}</td>
                                <td class="py-3 px-6 text-left">Rp{{ order.biaya }}</td>
                                <td class="py-3 px-6 text-left">{{ order.durasi_pengerjaan }} menit</td>
                                <td class="py-3 px-6 text-left">{{ order.status }}</td>
                            </tr>
                            <tr v-if="orders.length === 0">
                                <td colspan="4" class="text-center py-6 text-gray-400">Tidak ada data order</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
