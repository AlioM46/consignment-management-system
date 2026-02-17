<script setup>
import { ref } from 'vue';
import { router, useForm, Link } from '@inertiajs/vue3';
import Layout from '../../Layouts/Layout.vue';
defineOptions({ layout: Layout });
const props = defineProps({ vehicle: Object, drivers: Array, vendors: Array });
const editing = ref(false);
const form = useForm({ plate_number: props.vehicle.plate_number, Vehicle_model: props.vehicle.Vehicle_model ?? '', driver_id: props.vehicle.driver_id ?? '', vendor_id: props.vehicle.vendor_id ?? '' });
function startEdit() { form.plate_number = props.vehicle.plate_number; form.Vehicle_model = props.vehicle.Vehicle_model ?? ''; form.driver_id = props.vehicle.driver_id ?? ''; form.vendor_id = props.vehicle.vendor_id ?? ''; form.clearErrors(); editing.value = true; }
function cancelEdit() { editing.value = false; form.reset(); }
function submit() { form.put(`/vehicles/${props.vehicle.id}`, { onSuccess: () => { editing.value = false; } }); }
function destroy() { if (confirm(`Delete "${props.vehicle.plate_number}"?`)) router.delete(`/vehicles/${props.vehicle.id}`); }
</script>
<template>
    <div>
        <nav class="mb-6 flex items-center gap-2 text-sm text-gray-500">
            <Link href="/vehicles" class="transition-colors hover:text-violet-400">Vehicles</Link>
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            <span class="text-gray-300">{{ vehicle.plate_number }}</span>
        </nav>
        <div class="overflow-hidden rounded-2xl border border-gray-800/60 bg-gray-900/50 shadow-xl">
            <div class="flex items-center justify-between border-b border-gray-800/60 px-6 py-5">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500/20 to-teal-500/20 text-lg font-bold text-emerald-400">🚗</div>
                    <div><h1 class="text-xl font-bold text-white">{{ vehicle.plate_number }}</h1><p class="text-sm text-gray-400">Vehicle #{{ vehicle.id }}</p></div>
                </div>
                <div v-if="!editing" class="flex items-center gap-2">
                    <button @click="startEdit" class="inline-flex items-center gap-2 rounded-xl border border-gray-700 px-4 py-2 text-sm font-medium text-gray-300 hover:border-violet-500/50 hover:bg-gray-800 hover:text-white">Edit</button>
                    <button @click="destroy" class="inline-flex items-center gap-2 rounded-xl border border-gray-700 px-4 py-2 text-sm font-medium text-gray-300 hover:border-red-500/50 hover:bg-red-950/30 hover:text-red-400">Delete</button>
                </div>
            </div>
            <div class="p-6">
                <div v-if="!editing" class="grid gap-6 sm:grid-cols-2">
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Plate Number</dt><dd class="mt-1 text-sm text-gray-200">{{ vehicle.plate_number }}</dd></div>
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Model</dt><dd class="mt-1 text-sm text-gray-200">{{ vehicle.Vehicle_model || '—' }}</dd></div>
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Driver</dt><dd class="mt-1 text-sm text-gray-200">{{ drivers?.find(d => d.id === vehicle.driver_id)?.name ?? '—' }}</dd></div>
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Vendor</dt><dd class="mt-1 text-sm text-gray-200">{{ vendors?.find(v => v.id === vehicle.vendor_id)?.name ?? '—' }}</dd></div>
                </div>
                <form v-else @submit.prevent="submit" class="space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div><label class="mb-1.5 block text-sm font-medium text-gray-300">Plate Number *</label><input v-model="form.plate_number" type="text" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" /></div>
                        <div><label class="mb-1.5 block text-sm font-medium text-gray-300">Model *</label><input v-model="form.Vehicle_model" type="text" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500" /></div>
                        <div><label class="mb-1.5 block text-sm font-medium text-gray-300">Driver *</label><select v-model="form.driver_id" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500"><option value="" disabled>Select</option><option v-for="d in drivers" :key="d.id" :value="d.id">{{ d.name }}</option></select></div>
                        <div><label class="mb-1.5 block text-sm font-medium text-gray-300">Vendor *</label><select v-model="form.vendor_id" required class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500"><option value="" disabled>Select</option><option v-for="vn in vendors" :key="vn.id" :value="vn.id">{{ vn.name }}</option></select></div>
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
