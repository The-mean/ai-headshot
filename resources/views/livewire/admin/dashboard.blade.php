<div class="min-h-screen bg-zinc-50 px-6 py-10 text-zinc-900 transition-colors dark:bg-zinc-950 dark:text-zinc-100">
    <div class="mx-auto max-w-6xl space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Admin Dashboard</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Operational overview for your testimonial engine.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.widget-builder') }}" class="rounded-2xl border border-zinc-300 bg-white/80 px-4 py-2 text-sm font-medium text-zinc-800 ring-1 ring-transparent backdrop-blur transition hover:-translate-y-0.5 hover:ring-zinc-300 dark:border-zinc-700 dark:bg-zinc-900/80 dark:text-zinc-100">
                    Widget Builder
                </a>
                <a href="{{ route('admin.inbox') }}" class="rounded-2xl bg-zinc-900 px-4 py-2 text-sm font-medium text-white ring-1 ring-zinc-900/20 transition hover:-translate-y-0.5 dark:bg-white dark:text-zinc-900">
                    Open Inbox
                </a>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-3xl border border-zinc-200 bg-white/85 p-5 shadow-sm ring-1 ring-transparent backdrop-blur transition hover:ring-zinc-300 dark:border-zinc-800 dark:bg-zinc-900/80 dark:hover:ring-zinc-700">
                <p class="text-xs uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Total Videos</p>
                <p class="mt-2 text-3xl font-semibold">{{ number_format($stats['total_videos']) }}</p>
            </div>

            <div class="rounded-3xl border border-zinc-200 bg-white/85 p-5 shadow-sm ring-1 ring-transparent backdrop-blur transition hover:ring-zinc-300 dark:border-zinc-800 dark:bg-zinc-900/80 dark:hover:ring-zinc-700">
                <p class="text-xs uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Pending Videos</p>
                <p class="mt-2 text-3xl font-semibold">{{ number_format($stats['pending_videos']) }}</p>
            </div>

            <div class="rounded-3xl border border-zinc-200 bg-white/85 p-5 shadow-sm ring-1 ring-transparent backdrop-blur transition hover:ring-zinc-300 dark:border-zinc-800 dark:bg-zinc-900/80 dark:hover:ring-zinc-700">
                <p class="text-xs uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Active Campaigns</p>
                <p class="mt-2 text-3xl font-semibold">{{ number_format($stats['active_campaigns']) }}</p>
            </div>
            <div class="rounded-3xl border border-zinc-200 bg-white/85 p-5 shadow-sm ring-1 ring-transparent backdrop-blur transition hover:ring-zinc-300 dark:border-zinc-800 dark:bg-zinc-900/80 dark:hover:ring-zinc-700">
                <p class="text-xs uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Monthly Upload Usage</p>
                <p class="mt-2 text-3xl font-semibold">{{ number_format($stats['monthly_upload_mb'], 2) }} <span class="text-base font-medium text-zinc-500">MB</span></p>
            </div>
        </div>
    </div>
</div>
