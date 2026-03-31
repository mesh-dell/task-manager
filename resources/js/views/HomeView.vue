<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { getTasks, advanceStatus, deleteTask, type Task } from '../services/api'

const tasks = ref<Task[]>([])
const message = ref('')
const statusFilter = ref('')

async function fetchTasks() {
    const res = await getTasks(statusFilter.value || undefined)
    if (Array.isArray(res.data)) {
        tasks.value = res.data
    } else {
        tasks.value = res.data.data ?? []
        message.value = res.data.message ?? ''
    }
}

async function advance(id: number) {
    await advanceStatus(id)
    fetchTasks()
}

async function remove(id: number) {
    await deleteTask(id)
    fetchTasks()
}

const statusColor = (status: string) => ({
    'pending': 'bg-gray-100 text-gray-600',
    'in_progress': 'bg-blue-100 text-blue-600',
    'done': 'bg-green-100 text-green-600',
}[status])

const priorityColor = (priority: string) => ({
    'high': 'bg-red-100 text-red-600',
    'medium': 'bg-yellow-100 text-yellow-600',
    'low': 'bg-gray-100 text-gray-600',
}[priority])

onMounted(fetchTasks)
</script>

<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Tasks</h1>

            <!-- Status Filter -->
            <select v-model="statusFilter" @change="fetchTasks"
                class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="">All</option>
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="done">Done</option>
            </select>
        </div>

        <!-- Empty State -->
        <div v-if="tasks.length === 0" class="text-center py-16 text-gray-400">
            <p class="text-lg">{{ message || 'No tasks found.' }}</p>
        </div>

        <!-- Task Cards -->
        <div v-for="task in tasks" :key="task.id" class="bg-white border border-gray-200 rounded-lg p-5 mb-4 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="font-semibold text-gray-800 text-lg">{{ task.title }}</h3>
                    <p class="text-gray-400 text-sm mt-1">Due: {{ task.due_date }}</p>
                </div>
                <div class="flex gap-2">
                    <span :class="priorityColor(task.priority)"
                        class="px-2 py-1 rounded text-xs font-medium capitalize">
                        {{ task.priority }}
                    </span>
                    <span :class="statusColor(task.status)" class="px-2 py-1 rounded text-xs font-medium capitalize">
                        {{ task.status.replace('_', ' ') }}
                    </span>
                </div>
            </div>

            <div class="flex gap-3 mt-4">
                <button v-if="task.status !== 'done'" @click="advance(task.id)"
                    class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-1 rounded">
                    Advance Status
                </button>
                <button v-if="task.status === 'done'" @click="remove(task.id)"
                    class="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-1 rounded">
                    Delete
                </button>
            </div>
        </div>
    </div>
</template>
