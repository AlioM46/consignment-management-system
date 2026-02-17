<script setup>
import { ref } from 'vue';
import { router, useForm, Link } from '@inertiajs/vue3';
import Layout from '../../Layouts/Layout.vue';
defineOptions({ layout: Layout });
const props = defineProps({ product: Object });
const editing = ref(false);
const form = useForm({ name: props.product.name, description: props.product.description ?? '', sku: props.product.sku ?? '', default_price: props.product.default_price ?? '', cost_price: props.product.cost_price ?? '' });
function startEdit() { form.name = props.product.name; form.description = props.product.description ?? ''; form.sku = props.product.sku ?? ''; form.default_price = props.product.default_price ?? ''; form.cost_price = props.product.cost_price ?? ''; form.clearErrors(); editing.value = true; }
function cancelEdit() { editing.value = false; form.reset(); }
function submit() { form.put(`/products/${props.product.id}`, { onSuccess: () => { editing.value = false; } }); }
function destroy() { if (confirm(`Delete "${props.product.name}"?`)) router.delete(`/products/${props.product.id}`); }
function fmt(v) { return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(v ?? 0); }
</script>
<template>
    <div>
        <nav class="mb-6 flex items-center gap-2 text-sm text-gray-500"><Link href="/products" class="hover:text-violet-400">Products</Link><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg><span class="text-gray-300">{{ product.name }}</span></nav>
        <div class="overflow-hidden rounded-2xl border border-gray-800/60 bg-gray-900/50 shadow-xl">
            <div class="flex items-center justify-between border-b border-gray-800/60 px-6 py-5">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500/20 to-orange-500/20 text-lg font-bold text-amber-400">{{ product.name?.charAt(0)?.toUpperCase() }}</div>
                    <div><h1 class="text-xl font-bold text-white">{{ product.name }}</h1><p class="text-sm text-gray-400">SKU: {{ product.sku }}</p></div>
                </div>
                <div v-if="!editing" class="flex items-center gap-2">
                    <button @click="startEdit" class="rounded-xl border border-gray-700 px-4 py-2 text-sm font-medium text-gray-300 hover:border-violet-500/50 hover:bg-gray-800 hover:text-white">Edit</button>
                    <button @click="destroy" class="rounded-xl border border-gray-700 px-4 py-2 text-sm font-medium text-gray-300 hover:border-red-500/50 hover:bg-red-950/30 hover:text-red-400">Delete</button>
                </div>
            </div>
            <div class="p-6">
                <div v-if="!editing" class="grid gap-6 sm:grid-cols-2">
                    <div class="sm:col-span-2"><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Description</dt><dd class="mt-1 text-sm text-gray-200">{{ product.description || '—' }}</dd></div>
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Default Price</dt><dd class="mt-1 text-sm text-gray-200">{{ fmt(product.default_price) }}</dd></div>
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Cost Price</dt><dd class="mt-1 text-sm text-gray-200">{{ fmt(product.cost_price) }}</dd></div>
                </div>
                <form v-else @submit.prevent="submit" class="space-y-4">
                    <div><label class="mb-1.5 block text-sm font-medium text-gray-300">Name *</label><input v-model="form.name" type="text" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" /></div>
                    <div><label class="mb-1.5 block text-sm font-medium text-gray-300">Description *</label><textarea v-model="form.description" rows="2" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500"></textarea></div>
                    <div><label class="mb-1.5 block text-sm font-medium text-gray-300">SKU *</label><input v-model="form.sku" type="text" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" /></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="mb-1.5 block text-sm font-medium text-gray-300">Default Price *</label><input v-model="form.default_price" type="number" step="0.01" min="0" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" /></div>
                        <div><label class="mb-1.5 block text-sm font-medium text-gray-300">Cost Price *</label><input v-model="form.cost_price" type="number" step="0.01" min="0" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" /></div>
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
