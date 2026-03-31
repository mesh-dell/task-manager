<script setup lang="ts">
import { ref } from 'vue'
import { createTask } from '../services/api'

const form = ref({
    title: '',
    due_date: '',
    priority: 'medium'
})

const error = ref<Record<string, string[]>>({})
const success = ref('')

async function submit() {
    error.value = {}
    success.value = ''
    try {
        await createTask(form.value)
        success.value = 'Task created successfully!'
        form.value = { title: '', due_date: '', priority: 'medium' }
    } catch (e: any) {
        if (e.response?.status === 422) {
            error.value = e.response.data.errors
        }
    }
}
</script>

<template>
    <div class="max-w-lg">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Create Task</h1>

        <div v-if="success" class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">
            {{ success }}
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm space-y-4">
            <!-- Title -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input v-model="form.title" placeholder="Task title"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                <p v-if="error.title" class="text-red-500 text-xs mt-1">{{ error.title[0] }}</p>
            </div>

            <!-- Due Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                <input v-model="form.due_date" type="date"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                <p v-if="error.due_date" class="text-red-500 text-xs mt-1">{{ error.due_date[0] }}</p>
            </div>

            <!-- Priority -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                <select v-model="form.priority"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
                <p v-if="error.priority" class="text-red-500 text-xs mt-1">{{ error.priority[0] }}</p>
            </div>

            <button @click="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 rounded">
                Create Task
            </button>
        </div>
    </div>
</template>
