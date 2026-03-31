import axios from "axios";

export interface Task {
    id: number;
    title: string;
    due_date: string;
    priority: "low" | "medium" | "high";
    status: "pending" | "in_progress" | "done";
    created_at: string;
    updated_at: string;
}

export interface ReportSummary {
    date: string;
    summary: {
        high: { pending: number; in_progress: number; done: number };
        medium: { pending: number; in_progress: number; done: number };
        low: { pending: number; in_progress: number; done: number };
    };
}

export const getTasks = (status?: string) =>
    axios.get<{ data: Task[]; message: string }>("/api/tasks", {
        params: { status },
    });

export const createTask = (data: {
    title: string;
    due_date: string;
    priority: string;
}) => axios.post<{ data: Task }>("/api/tasks", data);

export const advanceStatus = (id: number) =>
    axios.patch<{ data: Task }>(`/api/tasks/${id}/status`);

export const deleteTask = (id: number) => axios.delete(`/api/tasks/${id}`);

export const getReport = (date?: string) =>
    axios.get<ReportSummary>("/api/tasks/report", { params: { date } });
