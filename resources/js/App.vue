<script setup lang="ts">
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <nav class="bg-white border-b border-gray-200 px-6 py-4 flex gap-6">
            <router-link to="/" class="font-semibold text-gray-700 hover:text-blue-500">Tasks</router-link>
            <router-link to="/create" class="font-semibold text-gray-700 hover:text-blue-500">Create</router-link>
            <router-link to="/report" class="font-semibold text-gray-700 hover:text-blue-500">Report</router-link>
        </nav>

        <main class="max-w-4xl mx-auto px-6 py-8">
            <router-view />
        </main>
    </div>
</template>
