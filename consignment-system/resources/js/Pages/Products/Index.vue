<script setup>
import { ref, computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import Layout from '../../Layouts/Layout.vue';
defineOptions({ layout: Layout });
const props = defineProps({ products: Array });
const showModal = ref(false);
const editingProduct = ref(null);
const form = useForm({ name: '', description: '', sku: '', default_price: '', cost_price: '' });
const isEditing = computed(() => !!editingProduct.value);
function openCreate() { editingProduct.value = null; form.reset(); form.clearErrors(); showModal.value = true; }
function openEdit(p) { editingProduct.value = p; form.name = p.name; form.description = p.description ?? ''; form.sku = p.sku ?? ''; form.default_price = p.default_price ?? ''; form.cost_price = p.cost_price ?? ''; form.clearErrors(); showModal.value = true; }
function closeModal() { showModal.value = false; editingProduct.value = null; form.reset(); }
function submit() {
    if (isEditing.value) form.put(`/products/${editingProduct.value.id}`, { onSuccess: () => closeModal() });
    else form.post('/products', { onSuccess: () => closeModal() });
}
function destroy(p) { if (confirm(`Delete "${p.name}"?`)) router.delete(`/products/${p.id}`); }
function fmt(v) { return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(v ?? 0); }
</script>
<template>
    <div>
        <div class="mb-8 flex items-center justify-between">
            <div><h1 class="text-2xl font-bold tracking-tight text-white">Products</h1><p class="mt-1 text-sm text-gray-400">Manage your product catalog</p></div>
            <button @click="openCreate" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 transition-all hover:shadow-violet-500/40 hover:brightness-110 active:scale-[0.98]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>Add Product
            </button>
        </div>
        <div class="overflow-hidden rounded-2xl border border-gray-800/60 bg-gray-900/50 shadow-xl">
            <table class="w-full text-left text-sm">
                <thead><tr class="border-b border-gray-800/60 bg-gray-900/80">
                    <th class="px-6 py-4 font-semibold text-gray-300">#</th>
                    <th class="px-6 py-4 font-semibold text-gray-300">Name</th>
                    <th class="px-6 py-4 font-semibold text-gray-300">SKU</th>
                    <th class="px-6 py-4 font-semibold text-gray-300">Price</th>
                    <th class="px-6 py-4 font-semibold text-gray-300">Cost</th>
                    <th class="px-6 py-4 text-right font-semibold text-gray-300">Actions</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-800/40">
                    <tr v-for="p in products" :key="p.id" class="transition-colors hover:bg-gray-800/30">
                        <td class="px-6 py-4 text-gray-500">{{ p.id }}</td>
                        <td class="px-6 py-4 font-medium text-white"><a :href="`/products/${p.id}`" class="hover:text-violet-400 transition-colors">{{ p.name }}</a></td>
                        <td class="px-6 py-4 text-gray-400"><span class="rounded bg-gray-800 px-2 py-0.5 font-mono text-xs">{{ p.sku }}</span></td>
                        <td class="px-6 py-4 text-gray-400">{{ fmt(p.default_price) }}</td>
                        <td class="px-6 py-4 text-gray-400">{{ fmt(p.cost_price) }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button @click="openEdit(p)" class="rounded-lg p-2 text-gray-400 hover:bg-gray-800 hover:text-violet-400"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg></button>
                                <button @click="destroy(p)" class="rounded-lg p-2 text-gray-400 hover:bg-red-950/50 hover:text-red-400"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg></button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!products?.length"><td colspan="6" class="px-6 py-12 text-center text-gray-500">No products found.</td></tr>
                </tbody>
            </table>
        </div>
        <Teleport to="body">
            <Transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition duration-150 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" @click.self="closeModal">
                    <div class="w-full max-w-lg rounded-2xl border border-gray-800/60 bg-gray-900 p-6 shadow-2xl">
                        <h2 class="mb-6 text-lg font-semibold text-white">{{ isEditing ? 'Edit Product' : 'Add Product' }}</h2>
                        <form @submit.prevent="submit" class="space-y-4">
                            <div><label class="mb-1.5 block text-sm font-medium text-gray-300">Name *</label><input v-model="form.name" type="text" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white placeholder-gray-500 outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" placeholder="Product name" /><p v-if="form.errors.name" class="mt-1 text-xs text-red-400">{{ form.errors.name }}</p></div>
                            <div><label class="mb-1.5 block text-sm font-medium text-gray-300">Description *</label><textarea v-model="form.description" rows="2" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white placeholder-gray-500 outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" placeholder="Product description"></textarea><p v-if="form.errors.description" class="mt-1 text-xs text-red-400">{{ form.errors.description }}</p></div>
                            <div><label class="mb-1.5 block text-sm font-medium text-gray-300">SKU *</label><input v-model="form.sku" type="text" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white placeholder-gray-500 outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" placeholder="PROD-001" /><p v-if="form.errors.sku" class="mt-1 text-xs text-red-400">{{ form.errors.sku }}</p></div>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="mb-1.5 block text-sm font-medium text-gray-300">Default Price *</label><input v-model="form.default_price" type="number" step="0.01" min="0" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" /><p v-if="form.errors.default_price" class="mt-1 text-xs text-red-400">{{ form.errors.default_price }}</p></div>
                                <div><label class="mb-1.5 block text-sm font-medium text-gray-300">Cost Price *</label><input v-model="form.cost_price" type="number" step="0.01" min="0" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" /><p v-if="form.errors.cost_price" class="mt-1 text-xs text-red-400">{{ form.errors.cost_price }}</p></div>
                            </div>
                            <div class="flex items-center justify-end gap-3 pt-2">
                                <button type="button" @click="closeModal" class="rounded-xl px-4 py-2.5 text-sm font-medium text-gray-400 hover:bg-gray-800 hover:text-white">Cancel</button>
                                <button type="submit" :disabled="form.processing" class="rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:brightness-110 disabled:opacity-50">{{ form.processing ? 'Saving...' : (isEditing ? 'Update' : 'Create') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>
