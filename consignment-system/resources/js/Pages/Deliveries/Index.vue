<script setup>
import { ref, computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import Layout from '../../Layouts/Layout.vue';

defineOptions({ layout: Layout });
const props = defineProps({ deliveries: Array, vehicles: Array, products: Array });

const showModal = ref(false);
const editingDelivery = ref(null);
const form = useForm({
    name: '',
    delivery_date: '',
    status: 1,
    vehicle_id: '',
    products: []
});

const isEditing = computed(() => !!editingDelivery.value);
const statusLabels = { 1: 'Arrived', 2: 'Cancelled', 3: 'Refunded' };
const statusColors = { 1: 'bg-emerald-500/10 text-emerald-400', 2: 'bg-red-500/10 text-red-400', 3: 'bg-amber-500/10 text-amber-400' };

// Calculate total value from products
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

function openCreate() {
    editingDelivery.value = null;
    form.reset();
    form.status = 1;
    form.products = [];
    addProductRow();
    form.clearErrors();
    showModal.value = true;
}

function openEdit(d) {
    editingDelivery.value = d;
    form.name = d.name;
    form.delivery_date = d.delivery_date?.split('T')[0] ?? '';
    form.status = typeof d.status === 'object' ? d.status.value ?? d.status : d.status;
    form.vehicle_id = d.vehicle_id ?? '';
    form.products = []; // No items available in index props usually
    form.clearErrors();
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
    editingDelivery.value = null;
    form.reset();
}

function submit() {
    if (isEditing.value) {
        form.put(`/deliveries/${editingDelivery.value.id}`, { onSuccess: () => closeModal() });
    } else {
        form.post('/deliveries', { onSuccess: () => closeModal() });
    }
}

function destroy(d) {
    if (confirm(`Delete "${d.name}"?`)) router.delete(`/deliveries/${d.id}`);
}

function getStatusVal(s) {
    return typeof s === 'object' ? s?.value ?? s : s;
}

function fmt(v) {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(v ?? 0);
}
</script>

<template>
    <div>
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white">Deliveries</h1>
                <p class="mt-1 text-sm text-gray-400">Track consignment deliveries</p>
            </div>
            <button @click="openCreate" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:brightness-110 active:scale-[0.98]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Add Delivery
            </button>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-800/60 bg-gray-900/50 shadow-xl">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-800/60 bg-gray-900/80">
                        <th class="px-6 py-4 font-semibold text-gray-300">#</th>
                        <th class="px-6 py-4 font-semibold text-gray-300">Name</th>
                        <th class="px-6 py-4 font-semibold text-gray-300">Date</th>
                        <th class="px-6 py-4 font-semibold text-gray-300">Status</th>
                        <th class="px-6 py-4 font-semibold text-gray-300">Vehicle</th>
                        <th class="px-6 py-4 text-right font-semibold text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/40">
                    <tr v-for="d in deliveries" :key="d.id" class="transition-colors hover:bg-gray-800/30">
                        <td class="px-6 py-4 text-gray-500">{{ d.id }}</td>
                        <td class="px-6 py-4 font-medium text-white">
                            <a :href="`/deliveries/${d.id}`" class="hover:text-violet-400">{{ d.name }}</a>
                        </td>
                        <td class="px-6 py-4 text-gray-400">{{ d.delivery_date }}</td>
                        <td class="px-6 py-4">
                            <span :class="['rounded-lg px-2.5 py-1 text-xs font-medium', statusColors[getStatusVal(d.status)] ?? 'bg-gray-800 text-gray-400']">
                                {{ statusLabels[getStatusVal(d.status)] ?? d.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-400">{{ vehicles?.find(v => v.id === d.vehicle_id)?.plate_number ?? '—' }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button @click="openEdit(d)" class="rounded-lg p-2 text-gray-400 hover:bg-gray-800 hover:text-violet-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                </button>
                                <button @click="destroy(d)" class="rounded-lg p-2 text-gray-400 hover:bg-red-950/50 hover:text-red-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!deliveries?.length">
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">No deliveries found.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <Teleport to="body">
            <Transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition duration-150 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" @click.self="closeModal">
                    <div class="w-full max-w-2xl rounded-2xl border border-gray-800/60 bg-gray-900 p-6 shadow-2xl overflow-y-auto max-h-[90vh]">
                        <h2 class="mb-6 text-lg font-semibold text-white">{{ isEditing ? 'Edit Delivery' : 'Add Delivery' }}</h2>
                        
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Delivery Details -->
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-300">Name *</label>
                                    <input v-model="form.name" type="text" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" placeholder="Delivery Name" />
                                    <p v-if="form.errors.name" class="mt-1 text-xs text-red-400">{{ form.errors.name }}</p>
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-300">Date *</label>
                                    <input v-model="form.delivery_date" type="date" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" />
                                    <p v-if="form.errors.delivery_date" class="mt-1 text-xs text-red-400">{{ form.errors.delivery_date }}</p>
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-300">Status *</label>
                                    <select v-model="form.status" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500">
                                        <option :value="1">Arrived</option>
                                        <option :value="2">Cancelled</option>
                                        <option :value="3">Refunded</option>
                                    </select>
                                    <p v-if="form.errors.status" class="mt-1 text-xs text-red-400">{{ form.errors.status }}</p>
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-300">Vehicle *</label>
                                    <select v-model="form.vehicle_id" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500">
                                        <option value="" disabled>Select vehicle</option>
                                        <option v-for="v in vehicles" :key="v.id" :value="v.id">{{ v.plate_number }}</option>
                                    </select>
                                    <p v-if="form.errors.vehicle_id" class="mt-1 text-xs text-red-400">{{ form.errors.vehicle_id }}</p>
                                </div>
                            </div>

                            <!-- Products Section -->
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-medium text-gray-300">Products</h3>
                                    <button type="button" @click="addProductRow" class="text-xs font-semibold text-violet-400 hover:text-violet-300">
                                        + Add Product
                                    </button>
                                </div>
                                
                                <div v-if="form.products.length === 0" class="rounded-xl border border-dashed border-gray-700 p-4 text-center text-sm text-gray-500">
                                    No products added.
                                </div>

                                <div v-for="(item, index) in form.products" :key="index" class="flex items-start gap-3 rounded-xl bg-gray-800/30 p-3">
                                    <div class="flex-1">
                                        <select v-model="item.product_id" @change="updateRowPrice(index)" required class="w-full rounded-lg border border-gray-700 bg-gray-800/50 px-3 py-2 text-sm text-white outline-none focus:border-violet-500">
                                            <option value="" disabled>Select Product</option>
                                            <option v-for="p in products" :key="p.id" :value="p.id">{{ p.name }} ({{ fmt(p.default_price) }})</option>
                                        </select>
                                        <p v-if="form.errors[`products.${index}.product_id`]" class="mt-1 text-xs text-red-400">Required</p>
                                    </div>
                                    <div class="w-24">
                                        <input v-model="item.quantity" type="number" min="1" placeholder="Qty" required class="w-full rounded-lg border border-gray-700 bg-gray-800/50 px-3 py-2 text-sm text-white outline-none focus:border-violet-500" />
                                        <p v-if="form.errors[`products.${index}.quantity`]" class="mt-1 text-xs text-red-400">Min 1</p>
                                    </div>
                                    <div class="w-24 pt-2 text-sm text-gray-400 text-right">
                                        {{ fmt((products.find(p => p.id === item.product_id)?.default_price || 0) * item.quantity) }}
                                    </div>
                                    <button type="button" @click="removeProductRow(index)" class="pt-2 text-gray-500 hover:text-red-400">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Total -->
                            <div class="flex items-center justify-between rounded-xl bg-gray-800/50 p-4">
                                <span class="font-medium text-gray-300">Total Value</span>
                                <span class="text-xl font-bold text-white">{{ fmt(calculateTotal) }}</span>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-end gap-3 pt-2">
                                <button type="button" @click="closeModal" class="rounded-xl px-4 py-2.5 text-sm font-medium text-gray-400 hover:bg-gray-800 hover:text-white">Cancel</button>
                                <button type="submit" :disabled="form.processing" class="rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:brightness-110 disabled:opacity-50">
                                    {{ form.processing ? 'Saving...' : (isEditing ? 'Update' : 'Create') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>
