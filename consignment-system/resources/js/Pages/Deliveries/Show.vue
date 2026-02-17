<script setup>
import { ref, computed } from 'vue';
import { router, useForm, Link } from '@inertiajs/vue3';
import Layout from '../../Layouts/Layout.vue';

defineOptions({ layout: Layout });
const props = defineProps({ delivery: Object, vehicles: Array, products: Array });

const editing = ref(false);
const statusLabels = { 1: 'Arrived', 2: 'Cancelled', 3: 'Refunded' };

// getStatusVal helper to handle enum objects or raw values
function getStatusVal(s) { return typeof s === 'object' ? s?.value ?? s : s; }

const form = useForm({
    name: props.delivery.name,
    delivery_date: props.delivery.delivery_date?.split('T')[0] ?? '',
    status: getStatusVal(props.delivery.status),
    vehicle_id: props.delivery.vehicle_id ?? '',
    products: props.delivery.items?.map(i => ({
        product_id: i.product_id,
        quantity: i.quantity,
        price: i.price
    })) || []
});

// Calculate total value from form products
const calculateTotal = computed(() => {
    return form.products.reduce((sum, item) => {
        const product = props.products.find(p => p.id === item.product_id);
        const price = product ? parseFloat(product.default_price) : 0;
        return sum + (price * (item.quantity || 0));
    }, 0);
});

function addProductRow() {
    form.products.push({ product_id: '', quantity: 1, price: 0 });
}

function removeProductRow(index) {
    form.products.splice(index, 1);
}

function updateRowPrice(index) {
    const item = form.products[index];
    const product = props.products.find(p => p.id === item.product_id);
    if (product) {
        item.price = product.default_price;
    }
}

function startEdit() {
    form.name = props.delivery.name;
    form.delivery_date = props.delivery.delivery_date?.split('T')[0] ?? '';
    form.status = getStatusVal(props.delivery.status);
    form.vehicle_id = props.delivery.vehicle_id ?? '';
    form.products = props.delivery.items?.map(i => ({
        product_id: i.product_id,
        quantity: i.quantity,
        price: i.price
    })) || [];
    
    if (form.products.length === 0) {
        addProductRow();
    }

    form.clearErrors();
    editing.value = true;
}

function cancelEdit() {
    editing.value = false;
    form.reset();
}

function submit() {
    form.put(`/deliveries/${props.delivery.id}`, { onSuccess: () => { editing.value = false; } });
}

function destroy() {
    if (confirm(`Delete "${props.delivery.name}"?`)) router.delete(`/deliveries/${props.delivery.id}`);
}

function fmt(v) {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(v ?? 0);
}
</script>

<template>
    <div>
        <nav class="mb-6 flex items-center gap-2 text-sm text-gray-500">
            <Link href="/deliveries" class="hover:text-violet-400">Deliveries</Link>
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            <span class="text-gray-300">{{ delivery.name }}</span>
        </nav>

        <div class="overflow-hidden rounded-2xl border border-gray-800/60 bg-gray-900/50 shadow-xl">
            <div class="flex items-center justify-between border-b border-gray-800/60 px-6 py-5">
                <div>
                    <h1 class="text-xl font-bold text-white">{{ delivery.name }}</h1>
                    <p class="text-sm text-gray-400">Delivery #{{ delivery.id }}</p>
                </div>
                <div v-if="!editing" class="flex items-center gap-2">
                    <button @click="startEdit" class="rounded-xl border border-gray-700 px-4 py-2 text-sm font-medium text-gray-300 hover:border-violet-500/50 hover:bg-gray-800 hover:text-white">Edit</button>
                    <button @click="destroy" class="rounded-xl border border-gray-700 px-4 py-2 text-sm font-medium text-gray-300 hover:border-red-500/50 hover:bg-red-950/30 hover:text-red-400">Delete</button>
                </div>
            </div>

            <div class="p-6">
                <!-- READ ONLY VIEW -->
                <div v-if="!editing" class="space-y-8">
                    <div class="grid gap-6 sm:grid-cols-2">
                        <div><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Date</dt><dd class="mt-1 text-sm text-gray-200">{{ delivery.delivery_date }}</dd></div>
                        <div><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Status</dt><dd class="mt-1 text-sm text-gray-200">{{ statusLabels[getStatusVal(delivery.status)] ?? delivery.status }}</dd></div>
                        <div><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Vehicle</dt><dd class="mt-1 text-sm text-gray-200">{{ vehicles?.find(v => v.id === delivery.vehicle_id)?.plate_number ?? '—' }}</dd></div>
                        <div><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Value</dt><dd class="mt-1 text-sm text-gray-200">{{ fmt(delivery.total_value) }}</dd></div>
                        <div><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Items</dt><dd class="mt-1 text-sm text-gray-200">{{ delivery.total_items }}</dd></div>
                    </div>

                    <div>
                        <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500">Delivery Items</h3>
                        <div class="overflow-hidden rounded-xl border border-gray-700/50">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-800/50">
                                    <tr>
                                        <th class="px-4 py-3 font-medium text-gray-300">Product</th>
                                        <th class="px-4 py-3 font-medium text-gray-300">Quantity</th>
                                        <th class="px-4 py-3 font-medium text-gray-300">Unit Price</th>
                                        <th class="px-4 py-3 text-right font-medium text-gray-300">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700/30">
                                    <tr v-for="item in delivery.items" :key="item.id">
                                        <td class="px-4 py-3 text-white">{{ item.product?.name ?? '#' + item.product_id }}</td>
                                        <td class="px-4 py-3 text-gray-400">{{ item.quantity }}</td>
                                        <td class="px-4 py-3 text-gray-400">{{ fmt(item.price) }}</td>
                                        <td class="px-4 py-3 text-right text-gray-300">{{ fmt(item.price * item.quantity) }}</td>
                                    </tr>
                                    <tr v-if="!delivery.items?.length"><td colspan="4" class="px-4 py-6 text-center text-gray-500">No items found.</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- EDIT FORM -->
                <form v-else @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-300">Name *</label>
                            <input v-model="form.name" type="text" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" />
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-300">Date *</label>
                            <input v-model="form.delivery_date" type="date" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" />
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-300">Status *</label>
                            <select v-model="form.status" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500">
                                <option :value="1">Arrived</option>
                                <option :value="2">Cancelled</option>
                                <option :value="3">Refunded</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-300">Vehicle *</label>
                            <select v-model="form.vehicle_id" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500">
                                <option value="" disabled>Select</option>
                                <option v-for="v in vehicles" :key="v.id" :value="v.id">{{ v.plate_number }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-medium text-gray-300">Products</h3>
                            <button type="button" @click="addProductRow" class="text-xs font-semibold text-violet-400 hover:text-violet-300">
                                + Add Product
                            </button>
                        </div>
                        
                        <div v-for="(item, index) in form.products" :key="index" class="flex items-start gap-3 rounded-xl bg-gray-800/30 p-3">
                            <div class="flex-1">
                                <select v-model="item.product_id" @change="updateRowPrice(index)" required class="w-full rounded-lg border border-gray-700 bg-gray-800/50 px-3 py-2 text-sm text-white outline-none focus:border-violet-500">
                                    <option value="" disabled>Select Product</option>
                                    <option v-for="p in products" :key="p.id" :value="p.id">{{ p.name }} ({{ fmt(p.default_price) }})</option>
                                </select>
                            </div>
                            <div class="w-24">
                                <input v-model="item.quantity" type="number" min="1" placeholder="Qty" required class="w-full rounded-lg border border-gray-700 bg-gray-800/50 px-3 py-2 text-sm text-white outline-none focus:border-violet-500" />
                            </div>
                            <div class="w-24 pt-2 text-sm text-gray-400 text-right">
                                {{ fmt((products.find(p => p.id === item.product_id)?.default_price || item.price || 0) * item.quantity) }}
                            </div>
                            <button type="button" @click="removeProductRow(index)" class="pt-2 text-gray-500 hover:text-red-400">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Computed Total -->
                    <div class="flex items-center justify-between rounded-xl bg-gray-800/50 p-4">
                        <span class="font-medium text-gray-300">Total Value</span>
                        <span class="text-xl font-bold text-white">{{ fmt(calculateTotal) }}</span>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" @click="cancelEdit" class="rounded-xl px-4 py-2.5 text-sm font-medium text-gray-400 hover:bg-gray-800 hover:text-white">Cancel</button>
                        <button type="submit" :disabled="form.processing" class="rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:brightness-110 disabled:opacity-50">
                            {{ form.processing ? 'Saving...' : 'Save Changes' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
