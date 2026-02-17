<script setup>
import { ref } from 'vue';
import { router, useForm, Link } from '@inertiajs/vue3';
import Layout from '../../Layouts/Layout.vue';
defineOptions({ layout: Layout });
const props = defineProps({ saleItem: Object, sales: Array, products: Array });
const editing = ref(false);
const form = useForm({ sale_id: props.saleItem.sale_id ?? '', product_id: props.saleItem.product_id ?? '', quantity: props.saleItem.quantity ?? '', price: props.saleItem.price ?? '' });
function startEdit() { form.sale_id = props.saleItem.sale_id ?? ''; form.product_id = props.saleItem.product_id ?? ''; form.quantity = props.saleItem.quantity ?? ''; form.price = props.saleItem.price ?? ''; form.clearErrors(); editing.value = true; }
function cancelEdit() { editing.value = false; form.reset(); }
function submit() { form.put(`/sale-items/${props.saleItem.id}`, { onSuccess: () => { editing.value = false; } }); }
function destroy() { if (confirm('Delete this sale item?')) router.delete(`/sale-items/${props.saleItem.id}`); }
function fmt(v) { return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(v ?? 0); }
</script>
<template>
    <div>
        <nav class="mb-6 flex items-center gap-2 text-sm text-gray-500"><Link href="/sale-items" class="hover:text-violet-400">Sale Items</Link><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg><span class="text-gray-300">#{{ saleItem.id }}</span></nav>
        <div class="overflow-hidden rounded-2xl border border-gray-800/60 bg-gray-900/50 shadow-xl">
            <div class="flex items-center justify-between border-b border-gray-800/60 px-6 py-5">
                <div><h1 class="text-xl font-bold text-white">Sale Item #{{ saleItem.id }}</h1></div>
                <div v-if="!editing" class="flex items-center gap-2">
                    <button @click="startEdit" class="rounded-xl border border-gray-700 px-4 py-2 text-sm font-medium text-gray-300 hover:border-violet-500/50 hover:bg-gray-800 hover:text-white">Edit</button>
                    <button @click="destroy" class="rounded-xl border border-gray-700 px-4 py-2 text-sm font-medium text-gray-300 hover:border-red-500/50 hover:bg-red-950/30 hover:text-red-400">Delete</button>
                </div>
            </div>
            <div class="p-6">
                <div v-if="!editing" class="grid gap-6 sm:grid-cols-2">
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Sale</dt><dd class="mt-1 text-sm text-gray-200">Sale #{{ saleItem.sale_id }}</dd></div>
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Product</dt><dd class="mt-1 text-sm text-gray-200">{{ products?.find(p => p.id === saleItem.product_id)?.name ?? '#' + saleItem.product_id }}</dd></div>
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Quantity</dt><dd class="mt-1 text-sm text-gray-200">{{ saleItem.quantity }}</dd></div>
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Price</dt><dd class="mt-1 text-sm text-gray-200">{{ fmt(saleItem.price) }}</dd></div>
                </div>
                <form v-else @submit.prevent="submit" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="mb-1.5 block text-sm font-medium text-gray-300">Sale *</label><select v-model="form.sale_id" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500"><option value="" disabled>Select</option><option v-for="s in sales" :key="s.id" :value="s.id">Sale #{{ s.id }}</option></select></div>
                        <div><label class="mb-1.5 block text-sm font-medium text-gray-300">Product *</label><select v-model="form.product_id" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500"><option value="" disabled>Select</option><option v-for="p in products" :key="p.id" :value="p.id">{{ p.name }}</option></select></div>
                        <div><label class="mb-1.5 block text-sm font-medium text-gray-300">Quantity *</label><input v-model="form.quantity" type="number" min="1" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" /></div>
                        <div><label class="mb-1.5 block text-sm font-medium text-gray-300">Price *</label><input v-model="form.price" type="number" step="0.01" min="0" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" /></div>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" @click="cancelEdit" class="rounded-xl px-4 py-2.5 text-sm font-medium text-gray-400 hover:bg-gray-800 hover:text-white">Cancel</button>
                        <button type="submit" :disabled="form.processing" class="rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:brightness-110 disabled:opacity-50">{{ form.processing ? 'Saving...' : 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
