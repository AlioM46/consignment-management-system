<script setup>
import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import Layout from '../../Layouts/Layout.vue';

defineOptions({ layout: Layout });

const props = defineProps({
    vendor: Object,
});

const editing = ref(false);

const form = useForm({
    name: props.vendor.name,
    email: props.vendor.email ?? '',
    phone: props.vendor.phone ?? '',
    address: props.vendor.address ?? '',
});

function startEdit() {
    form.name = props.vendor.name;
    form.email = props.vendor.email ?? '';
    form.phone = props.vendor.phone ?? '';
    form.address = props.vendor.address ?? '';
    form.clearErrors();
    editing.value = true;
}

function cancelEdit() {
    editing.value = false;
    form.reset();
}

function submit() {
    form.put(`/vendors/${props.vendor.id}`, {
        onSuccess: () => { editing.value = false; },
    });
}

function destroy() {
    if (confirm(`Delete "${props.vendor.name}"?`)) {
        router.delete(`/vendors/${props.vendor.id}`);
    }
}
</script>

<template>
    <div>
        <!-- Breadcrumb -->
        <nav class="mb-6 flex items-center gap-2 text-sm text-gray-500">
            <Link href="/vendors" class="transition-colors hover:text-violet-400">Vendors</Link>
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
            <span class="text-gray-300">{{ vendor.name }}</span>
        </nav>

        <!-- Card -->
        <div class="overflow-hidden rounded-2xl border border-gray-800/60 bg-gray-900/50 shadow-xl">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-gray-800/60 px-6 py-5">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500/20 to-indigo-500/20 text-lg font-bold text-violet-400">
                        {{ vendor.name?.charAt(0)?.toUpperCase() }}
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white">{{ vendor.name }}</h1>
                        <p class="text-sm text-gray-400">Vendor #{{ vendor.id }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2" v-if="!editing">
                    <button
                        @click="startEdit"
                        class="inline-flex items-center gap-2 rounded-xl border border-gray-700 px-4 py-2 text-sm font-medium text-gray-300 transition-colors hover:border-violet-500/50 hover:bg-gray-800 hover:text-white"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                        Edit
                    </button>
                    <button
                        @click="destroy"
                        class="inline-flex items-center gap-2 rounded-xl border border-gray-700 px-4 py-2 text-sm font-medium text-gray-300 transition-colors hover:border-red-500/50 hover:bg-red-950/30 hover:text-red-400"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                        Delete
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="p-6">
                <!-- View Mode -->
                <div v-if="!editing" class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-200">{{ vendor.email || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-200">{{ vendor.phone || '—' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Address</dt>
                        <dd class="mt-1 text-sm text-gray-200">{{ vendor.address || '—' }}</dd>
                    </div>
                </div>

                <!-- Edit Mode -->
                <form v-else @submit.prevent="submit" class="space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="mb-1.5 block text-sm font-medium text-gray-300">Name *</label>
                            <input
                                v-model="form.name"
                                type="text"
                                required
                                class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white placeholder-gray-500 outline-none transition-colors focus:border-violet-500 focus:ring-1 focus:ring-violet-500"
                            />
                            <p v-if="form.errors.name" class="mt-1 text-xs text-red-400">{{ form.errors.name }}</p>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-300">Email</label>
                            <input
                                v-model="form.email"
                                type="email"
                                class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white placeholder-gray-500 outline-none transition-colors focus:border-violet-500 focus:ring-1 focus:ring-violet-500"
                            />
                            <p v-if="form.errors.email" class="mt-1 text-xs text-red-400">{{ form.errors.email }}</p>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-300">Phone</label>
                            <input
                                v-model="form.phone"
                                type="text"
                                class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white placeholder-gray-500 outline-none transition-colors focus:border-violet-500 focus:ring-1 focus:ring-violet-500"
                            />
                            <p v-if="form.errors.phone" class="mt-1 text-xs text-red-400">{{ form.errors.phone }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1.5 block text-sm font-medium text-gray-300">Address</label>
                            <textarea
                                v-model="form.address"
                                rows="2"
                                class="w-full rounded-xl border border-gray-700 bg-gray-800/50 px-4 py-2.5 text-sm text-white placeholder-gray-500 outline-none transition-colors focus:border-violet-500 focus:ring-1 focus:ring-violet-500"
                            ></textarea>
                            <p v-if="form.errors.address" class="mt-1 text-xs text-red-400">{{ form.errors.address }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button
                            type="button"
                            @click="cancelEdit"
                            class="rounded-xl px-4 py-2.5 text-sm font-medium text-gray-400 transition-colors hover:bg-gray-800 hover:text-white"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 transition-all hover:shadow-violet-500/40 hover:brightness-110 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Saving...' : 'Save Changes' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
