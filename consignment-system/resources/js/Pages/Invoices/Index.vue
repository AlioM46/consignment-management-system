<script setup>
import { ref, computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import Layout from '../../Layouts/Layout.vue';

defineOptions({ layout: Layout });
const props = defineProps({ invoices: Array, vehicles: Array, vendors: Array });

const showModal = ref(false);
// Form only contains required fields for auto-calculation
const form = useForm({
    vehicle_id: '',
    start_date: '',
    end_date: '',
    commission_rate: 0,
    expenses: 0
});

const statusLabels = { 1: 'Pending', 2: 'Paid', 3: 'Cancelled' };
const statusColors = { 1: 'bg-amber-500/10 text-amber-400', 2: 'bg-emerald-500/10 text-emerald-400', 3: 'bg-red-500/10 text-red-400' };

function openCreate() {
    form.reset();
    form.commission_rate = 0;
    form.expenses = 0;
    form.clearErrors();
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
    form.reset();
}

function submit() {
    // Post to /invoices which maps to createInvoicePerVehicle
    form.post('/invoices', { onSuccess: () => closeModal() });
}

function destroy(i) {
    if (confirm('Delete this invoice?')) router.delete(`/invoices/${i.id}`);
}

function fmt(v) {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(v ?? 0);
}

function getStatusVal(s) {
    return typeof s === 'object' ? s?.value ?? s : s;
}
</script>

<template>
    <div>
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white">Invoices</h1>
                <p class="mt-1 text-sm text-gray-400">Manage financial invoices</p>
            </div>
            <button @click="openCreate" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:brightness-110 active:scale-[0.98]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Create Invoice
            </button>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-800/60 bg-gray-900/50 shadow-xl">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-800/60 bg-gray-900/80">
                        <th class="px-6 py-4 font-semibold text-gray-300">#</th>
                        <th class="px-6 py-4 font-semibold text-gray-300">Vendor</th>
                        <th class="px-6 py-4 font-semibold text-gray-300">Vehicle</th>
                        <th class="px-6 py-4 font-semibold text-gray-300">Date Range</th>
                        <th class="px-6 py-4 font-semibold text-gray-300">Net Amount</th>
                        <th class="px-6 py-4 font-semibold text-gray-300">Status</th>
                        <th class="px-6 py-4 text-right font-semibold text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/40">
                    <tr v-for="i in invoices" :key="i.id" class="transition-colors hover:bg-gray-800/30">
                        <td class="px-6 py-4 text-gray-500">{{ i.id }}</td>
                        <td class="px-6 py-4 text-gray-400">{{ vendors?.find(v => v.id === i.vendor_id)?.name ?? '—' }}</td>
                        <td class="px-6 py-4 text-gray-400">{{ vehicles?.find(v => v.id === i.vehicle_id)?.plate_number ?? '—' }}</td>
                        <td class="px-6 py-4 font-medium text-white">
                            <a :href="`/invoices/${i.id}`" class="hover:text-violet-400">
                                {{ i.start_date }} - {{ i.end_date }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-gray-400">{{ fmt(i.net_amount) }}</td>
                        <td class="px-6 py-4">
                            <span :class="['rounded-lg px-2.5 py-1 text-xs font-medium', statusColors[getStatusVal(i.status)] ?? 'bg-gray-800 text-gray-400']">
                                {{ statusLabels[getStatusVal(i.status)] ?? i.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a :href="`/invoices/${i.id}`" class="rounded-lg p-2 text-gray-400 hover:bg-gray-800 hover:text-violet-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </a>
                                <button @click="destroy(i)" class="rounded-lg p-2 text-gray-400 hover:bg-red-950/50 hover:text-red-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!invoices?.length">
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">No invoices found.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Create Modal -->
        <Teleport to="body">
            <Transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition duration-150 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" @click.self="closeModal">
                    <div class="w-full max-w-lg rounded-2xl border border-gray-800/60 bg-gray-900 p-6 shadow-2xl">
                        <h2 class="mb-6 text-lg font-semibold text-white">Create Invoice</h2>
                        <form @submit.prevent="submit" class="space-y-4">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-300">Vehicle *</label>
                                <select v-model="form.vehicle_id" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500">
                                    <option value="" disabled>Select</option>
                                    <option v-for="v in vehicles" :key="v.id" :value="v.id">{{ v.plate_number }}</option>
                                </select>
                                <p v-if="form.errors.vehicle_id" class="mt-1 text-xs text-red-400">{{ form.errors.vehicle_id }}</p>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-300">Start Date *</label>
                                    <input v-model="form.start_date" type="date" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" />
                                    <p v-if="form.errors.start_date" class="mt-1 text-xs text-red-400">{{ form.errors.start_date }}</p>
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-300">End Date *</label>
                                    <input v-model="form.end_date" type="date" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" />
                                    <p v-if="form.errors.end_date" class="mt-1 text-xs text-red-400">{{ form.errors.end_date }}</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-300">Commission (%) *</label>
                                    <input v-model="form.commission_rate" type="number" step="0.01" min="0" max="100" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" />
                                    <p v-if="form.errors.commission_rate" class="mt-1 text-xs text-red-400">{{ form.errors.commission_rate }}</p>
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-300">Expenses *</label>
                                    <input v-model="form.expenses" type="number" step="0.01" min="0" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" />
                                    <p v-if="form.errors.expenses" class="mt-1 text-xs text-red-400">{{ form.errors.expenses }}</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-2">
                                <button type="button" @click="closeModal" class="rounded-xl px-4 py-2.5 text-sm font-medium text-gray-400 hover:bg-gray-800 hover:text-white">Cancel</button>
                                <button type="submit" :disabled="form.processing" class="rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:brightness-110 disabled:opacity-50">
                                    {{ form.processing ? 'Calculating & Creating...' : 'Create Invoice' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>
