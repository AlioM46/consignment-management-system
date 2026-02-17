<script setup>
import { Link } from '@inertiajs/vue3';
import Layout from '../Layouts/Layout.vue';

defineOptions({ layout: Layout });

const props = defineProps({
    stats: Object,
    recentDeliveries: Array,
    recentSales: Array,
});

const statCards = [
    { label: 'Vendors', key: 'vendors', href: '/vendors', color: 'violet', icon: 'M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z' },
    { label: 'Drivers', key: 'drivers', href: '/drivers', color: 'blue', icon: 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z' },
    { label: 'Vehicles', key: 'vehicles', href: '/vehicles', color: 'emerald', icon: 'M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0H21M3.375 14.25h.008M21 14.25v-2.625c0-.621-.504-1.125-1.125-1.125h-3l-2.25-4.5H8.25L6 10.5H3.375c-.621 0-1.125.504-1.125 1.125V14.25' },
    { label: 'Products', key: 'products', href: '/products', color: 'amber', icon: 'M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9' },
    { label: 'Deliveries', key: 'deliveries', href: '/deliveries', color: 'cyan', icon: 'M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0H21M3.375 14.25V3.75h7.5l3.75 3.75h3a1.125 1.125 0 011.125 1.125V14.25m-17.25 0h17.25' },
    { label: 'Sales', key: 'sales', href: '/sales', color: 'rose', icon: 'M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z' },
    { label: 'Invoices', key: 'invoices', href: '/invoices', color: 'purple', icon: 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z' },
];

const colorMap = {
    violet: { bg: 'bg-violet-500/10', text: 'text-violet-400', border: 'border-violet-500/20' },
    blue: { bg: 'bg-blue-500/10', text: 'text-blue-400', border: 'border-blue-500/20' },
    emerald: { bg: 'bg-emerald-500/10', text: 'text-emerald-400', border: 'border-emerald-500/20' },
    amber: { bg: 'bg-amber-500/10', text: 'text-amber-400', border: 'border-amber-500/20' },
    cyan: { bg: 'bg-cyan-500/10', text: 'text-cyan-400', border: 'border-cyan-500/20' },
    rose: { bg: 'bg-rose-500/10', text: 'text-rose-400', border: 'border-rose-500/20' },
    purple: { bg: 'bg-purple-500/10', text: 'text-purple-400', border: 'border-purple-500/20' },
};

function formatCurrency(val) {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val ?? 0);
}
</script>

<template>
    <div>
        <div class="mb-8">
            <h1 class="text-2xl font-bold tracking-tight text-white">Dashboard</h1>
            <p class="mt-1 text-sm text-gray-400">Overview of your consignment system</p>
        </div>

        <!-- Stat Cards -->
        <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <Link
                v-for="card in statCards"
                :key="card.key"
                :href="card.href"
                class="group rounded-2xl border border-gray-800/60 bg-gray-900/50 p-5 transition-all hover:border-gray-700 hover:shadow-lg"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-400">{{ card.label }}</p>
                        <p class="mt-1 text-2xl font-bold text-white">{{ stats[card.key] ?? 0 }}</p>
                    </div>
                    <div :class="['flex h-12 w-12 items-center justify-center rounded-xl', colorMap[card.color].bg]">
                        <svg :class="['h-6 w-6', colorMap[card.color].text]" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" :d="card.icon" />
                        </svg>
                    </div>
                </div>
            </Link>
        </div>

        <!-- Total Sales Highlight -->
        <div class="mb-8 rounded-2xl border border-gray-800/60 bg-gradient-to-r from-violet-500/5 to-indigo-500/5 p-6">
            <p class="text-sm font-medium text-gray-400">Total Sales Revenue</p>
            <p class="mt-1 text-3xl font-bold text-white">{{ formatCurrency(stats.totalSales) }}</p>
        </div>

        <!-- Recent Activity -->
        <div class="grid gap-6 lg:grid-cols-2">
            <!-- Recent Deliveries -->
            <div class="rounded-2xl border border-gray-800/60 bg-gray-900/50">
                <div class="border-b border-gray-800/60 px-6 py-4">
                    <h2 class="font-semibold text-white">Recent Deliveries</h2>
                </div>
                <div class="divide-y divide-gray-800/40">
                    <div v-for="d in recentDeliveries" :key="d.id" class="flex items-center justify-between px-6 py-3">
                        <div>
                            <p class="text-sm font-medium text-white">{{ d.name }}</p>
                            <p class="text-xs text-gray-500">{{ d.vehicle?.plate_number ?? '—' }}</p>
                        </div>
                        <span class="rounded-lg bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300">
                            {{ d.delivery_date }}
                        </span>
                    </div>
                    <div v-if="!recentDeliveries?.length" class="px-6 py-8 text-center text-sm text-gray-500">
                        No recent deliveries
                    </div>
                </div>
            </div>

            <!-- Recent Sales -->
            <div class="rounded-2xl border border-gray-800/60 bg-gray-900/50">
                <div class="border-b border-gray-800/60 px-6 py-4">
                    <h2 class="font-semibold text-white">Recent Sales</h2>
                </div>
                <div class="divide-y divide-gray-800/40">
                    <div v-for="s in recentSales" :key="s.id" class="flex items-center justify-between px-6 py-3">
                        <div>
                            <p class="text-sm font-medium text-white">{{ formatCurrency(s.total_amount) }}</p>
                            <p class="text-xs text-gray-500">{{ s.vehicle?.plate_number ?? '—' }} · {{ s.payment_method }}</p>
                        </div>
                        <span class="rounded-lg bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-300">
                            {{ s.sale_date }}
                        </span>
                    </div>
                    <div v-if="!recentSales?.length" class="px-6 py-8 text-center text-sm text-gray-500">
                        No recent sales
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
