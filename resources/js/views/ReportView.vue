<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { getReport, type ReportSummary } from '../services/api'

const date = ref(new Date().toISOString().split('T')[0])
const report = ref<ReportSummary | null>(null)

async function fetchReport() {
    const res = await getReport(date.value)
    report.value = res.data
}

onMounted(fetchReport)
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Daily Report</h1>

        <div class="flex items-center gap-4 mb-6">
            <input v-model="date" type="date"
                class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
            <button @click="fetchReport" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Load Report
            </button>
        </div>

        <div v-if="report" class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-gray-600">Priority</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-600">Pending</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-600">In Progress</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-600">Done</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(counts, priority) in report.summary" :key="priority" class="border-b border-gray-100">
                        <td class="px-4 py-3 font-medium capitalize">{{ priority }}</td>
                        <td class="px-4 py-3 text-center text-gray-600">{{ counts.pending }}</td>
                        <td class="px-4 py-3 text-center text-blue-600">{{ counts.in_progress }}</td>
                        <td class="px-4 py-3 text-center text-green-600">{{ counts.done }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
