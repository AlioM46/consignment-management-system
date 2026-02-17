<script setup>
import { ref } from 'vue';
import { router, useForm, Link } from '@inertiajs/vue3';
import Layout from '../../Layouts/Layout.vue';
defineOptions({ layout: Layout });
const props = defineProps({ driver: Object });
const editing = ref(false);
const form = useForm({ name: props.driver.name, phone: props.driver.phone ?? '' });
function startEdit() { form.name = props.driver.name; form.phone = props.driver.phone ?? ''; form.clearErrors(); editing.value = true; }
function cancelEdit() { editing.value = false; form.reset(); }
function submit() { form.put(`/drivers/${props.driver.id}`, { onSuccess: () => { editing.value = false; } }); }
function destroy() { if (confirm(`Delete "${props.driver.name}"?`)) router.delete(`/drivers/${props.driver.id}`); }
</script>
<template>
    <div>
        <nav class="mb-6 flex items-center gap-2 text-sm text-gray-500">
            <Link href="/drivers" class="transition-colors hover:text-violet-400">Drivers</Link>
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            <span class="text-gray-300">{{ driver.name }}</span>
        </nav>
        <div class="overflow-hidden rounded-2xl border border-gray-800/60 bg-gray-900/50 shadow-xl">
            <div class="flex items-center justify-between border-b border-gray-800/60 px-6 py-5">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 text-lg font-bold text-blue-400">{{ driver.name?.charAt(0)?.toUpperCase() }}</div>
                    <div><h1 class="text-xl font-bold text-white">{{ driver.name }}</h1><p class="text-sm text-gray-400">Driver #{{ driver.id }}</p></div>
                </div>
                <div v-if="!editing" class="flex items-center gap-2">
                    <button @click="startEdit" class="inline-flex items-center gap-2 rounded-xl border border-gray-700 px-4 py-2 text-sm font-medium text-gray-300 transition-colors hover:border-violet-500/50 hover:bg-gray-800 hover:text-white">Edit</button>
                    <button @click="destroy" class="inline-flex items-center gap-2 rounded-xl border border-gray-700 px-4 py-2 text-sm font-medium text-gray-300 transition-colors hover:border-red-500/50 hover:bg-red-950/30 hover:text-red-400">Delete</button>
                </div>
            </div>
            <div class="p-6">
                <div v-if="!editing" class="grid gap-6 sm:grid-cols-2">
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Name</dt><dd class="mt-1 text-sm text-gray-200">{{ driver.name }}</dd></div>
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Phone</dt><dd class="mt-1 text-sm text-gray-200">{{ driver.phone || '—' }}</dd></div>
                </div>
                <form v-else @submit.prevent="submit" class="space-y-4">
                    <div><label class="mb-1.5 block text-sm font-medium text-gray-300">Name *</label><input v-model="form.name" type="text" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" /><p v-if="form.errors.name" class="mt-1 text-xs text-red-400">{{ form.errors.name }}</p></div>
                    <div><label class="mb-1.5 block text-sm font-medium text-gray-300">Phone *</label><input v-model="form.phone" type="text" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" /><p v-if="form.errors.phone" class="mt-1 text-xs text-red-400">{{ form.errors.phone }}</p></div>
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" @click="cancelEdit" class="rounded-xl px-4 py-2.5 text-sm font-medium text-gray-400 hover:bg-gray-800 hover:text-white">Cancel</button>
                        <button type="submit" :disabled="form.processing" class="rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:brightness-110 disabled:opacity-50">{{ form.processing ? 'Saving...' : 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
