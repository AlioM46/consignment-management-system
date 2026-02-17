<script setup>
import { ref } from 'vue';
import { router, useForm, Link } from '@inertiajs/vue3';
import Layout from '../../Layouts/Layout.vue';

defineOptions({ layout: Layout });
const props = defineProps({ invoice: Object, vehicles: Array, vendors: Array });

const editing = ref(false);
const statusLabels = { 1: 'Pending', 2: 'Paid', 3: 'Cancelled' };

function getStatusVal(s) { return typeof s === 'object' ? s?.value ?? s : s; }

const form = useForm({
    vehicle_id: props.invoice.vehicle_id ?? '',
    invoice_date: props.invoice.invoice_date?.split('T')[0] ?? '',
    start_date: props.invoice.start_date?.split('T')[0] ?? '', // Add start_date
    end_date: props.invoice.end_date?.split('T')[0] ?? '',     // Add end_date
    commission_rate: props.invoice.commission_rate ?? '',
    expenses: props.invoice.expenses ?? '',
    status: getStatusVal(props.invoice.status),
});

function startEdit() {
    form.vehicle_id = props.invoice.vehicle_id ?? '';
    form.invoice_date = props.invoice.invoice_date?.split('T')[0] ?? '';
    form.start_date = props.invoice.start_date?.split('T')[0] ?? '';
    form.end_date = props.invoice.end_date?.split('T')[0] ?? '';
    form.commission_rate = props.invoice.commission_rate ?? '';
    form.expenses = props.invoice.expenses ?? '';
    form.status = getStatusVal(props.invoice.status);
    
    form.clearErrors();
    editing.value = true;
}

function cancelEdit() {
    editing.value = false;
    form.reset();
}

function submit() {
    form.put(`/invoices/${props.invoice.id}`, { onSuccess: () => { editing.value = false; } });
}

function destroy() {
    if (confirm('Delete this invoice?')) router.delete(`/invoices/${props.invoice.id}`);
}

function fmt(v) {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(v ?? 0);
}
</script>

<template>
    <div>
        <nav class="mb-6 flex items-center gap-2 text-sm text-gray-500">
            <Link href="/invoices" class="hover:text-violet-400">Invoices</Link>
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            <span class="text-gray-300">Invoice #{{ invoice.id }}</span>
        </nav>

        <div class="overflow-hidden rounded-2xl border border-gray-800/60 bg-gray-900/50 shadow-xl">
            <div class="flex items-center justify-between border-b border-gray-800/60 px-6 py-5">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500/20 to-pink-500/20 text-lg font-bold text-purple-400">📄</div>
                    <div>
                        <h1 class="text-xl font-bold text-white">Invoice #{{ invoice.id }}</h1>
                        <p class="text-sm text-gray-400">{{ statusLabels[getStatusVal(invoice.status)] ?? 'Unknown' }}</p>
                    </div>
                </div>
                <div v-if="!editing" class="flex items-center gap-2">
                    <button @click="startEdit" class="rounded-xl border border-gray-700 px-4 py-2 text-sm font-medium text-gray-300 hover:border-violet-500/50 hover:bg-gray-800 hover:text-white">Edit</button>
                    <button @click="destroy" class="rounded-xl border border-gray-700 px-4 py-2 text-sm font-medium text-gray-300 hover:border-red-500/50 hover:bg-red-950/30 hover:text-red-400">Delete</button>
                </div>
            </div>

            <div class="p-6">
                <!-- READ ONLY VIEW -->
                <div v-if="!editing" class="grid gap-6 sm:grid-cols-2">
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Vendor</dt><dd class="mt-1 text-sm text-gray-200">{{ vendors?.find(v => v.id === invoice.vendor_id)?.name ?? '—' }}</dd></div>
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Vehicle</dt><dd class="mt-1 text-sm text-gray-200">{{ vehicles?.find(v => v.id === invoice.vehicle_id)?.plate_number ?? '—' }}</dd></div>
                    
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Date Range</dt><dd class="mt-1 text-sm text-gray-200">{{ invoice.start_date }} to {{ invoice.end_date }}</dd></div>
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Invoice Date</dt><dd class="mt-1 text-sm text-gray-200">{{ invoice.invoice_date }}</dd></div>

                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Commission Rate</dt><dd class="mt-1 text-sm text-gray-200">{{ invoice.commission_rate }}%</dd></div>
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Expenses</dt><dd class="mt-1 text-sm text-gray-200">{{ fmt(invoice.expenses) }}</dd></div>

                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Sales</dt><dd class="mt-1 text-sm text-gray-200">{{ fmt(invoice.total_sales) }}</dd></div>
                    
                    <div class="sm:col-span-2 rounded-xl bg-gradient-to-r from-violet-500/5 to-indigo-500/5 border border-violet-500/10 p-4">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Net Amount</dt>
                        <dd class="mt-1 text-2xl font-bold text-white">{{ fmt(invoice.net_amount) }}</dd>
                    </div>
                </div>

                <!-- EDIT FORM -->
                <form v-else @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-300">Vehicle *</label>
                            <select v-model="form.vehicle_id" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500">
                                <option value="" disabled>Select</option>
                                <option v-for="v in vehicles" :key="v.id" :value="v.id">{{ v.plate_number }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-300">Invoice Date *</label>
                            <input v-model="form.invoice_date" type="date" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-300">Start Date *</label>
                            <input v-model="form.start_date" type="date" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" />
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-300">End Date *</label>
                            <input v-model="form.end_date" type="date" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-300">Commission (%) *</label>
                            <input v-model="form.commission_rate" type="number" step="0.01" min="0" max="100" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" />
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-300">Expenses *</label>
                            <input v-model="form.expenses" type="number" step="0.01" min="0" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" />
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-300">Status *</label>
                        <select v-model="form.status" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500">
                            <option :value="1">Pending</option>
                            <option :value="2">Paid</option>
                            <option :value="3">Cancelled</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" @click="cancelEdit" class="rounded-xl px-4 py-2.5 text-sm font-medium text-gray-400 hover:bg-gray-800 hover:text-white">Cancel</button>
                        <button type="submit" :disabled="form.processing" class="rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:brightness-110 disabled:opacity-50">
                            {{ form.processing ? 'Recalculating & Saving...' : 'Save Changes' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
