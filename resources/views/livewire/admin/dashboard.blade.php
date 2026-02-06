<div class="min-h-screen bg-zinc-50 px-6 py-10 text-zinc-900 dark:bg-zinc-950 dark:text-zinc-100">
    <div class="mx-auto max-w-6xl space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Admin Dashboard</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Operational overview for your testimonial engine.</p>
            </div>
            <a href="{{ route('admin.inbox') }}" class="rounded-2xl bg-zinc-900 px-4 py-2 text-sm font-medium text-white dark:bg-white dark:text-zinc-900">
                Open Inbox
            </a>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-3xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <p class="text-xs uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Total Videos</p>
                <p class="mt-2 text-3xl font-semibold">{{ number_format($stats['total_videos']) }}</p>
            </div>

            <div class="rounded-3xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <p class="text-xs uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Pending Videos</p>
                <p class="mt-2 text-3xl font-semibold">{{ number_format($stats['pending_videos']) }}</p>
            </div>

            <div class="rounded-3xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <p class="text-xs uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Active Campaigns</p>
                <p class="mt-2 text-3xl font-semibold">{{ number_format($stats['active_campaigns']) }}</p>
            </div>
        </div>
    </div>
</div>
