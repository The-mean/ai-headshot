<div class="min-h-screen bg-zinc-50 px-6 py-10 text-zinc-900 dark:bg-zinc-950 dark:text-zinc-100">
    <div class="mx-auto max-w-6xl space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Campaign Analytics</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $campaign->name ?? 'Campaign' }} için izlenme/tıklama görünümü</p>
            </div>
            <a href="{{ route('admin.inbox') }}" class="rounded-2xl border border-zinc-300 px-4 py-2 text-sm transition hover:ring-2 hover:ring-zinc-200 dark:border-zinc-700 dark:hover:ring-zinc-700">Back</a>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-zinc-200 bg-white/85 p-4 shadow-sm ring-1 ring-transparent backdrop-blur dark:border-zinc-800 dark:bg-zinc-900/80"><p class="text-xs text-zinc-500">Total Views</p><p class="mt-1 text-2xl font-semibold">{{ number_format($totalViews) }}</p></div>
            <div class="rounded-2xl border border-zinc-200 bg-white/85 p-4 shadow-sm ring-1 ring-transparent backdrop-blur dark:border-zinc-800 dark:bg-zinc-900/80"><p class="text-xs text-zinc-500">Total Clicks</p><p class="mt-1 text-2xl font-semibold">{{ number_format($totalClicks) }}</p></div>
            <div class="rounded-2xl border border-zinc-200 bg-white/85 p-4 shadow-sm ring-1 ring-transparent backdrop-blur dark:border-zinc-800 dark:bg-zinc-900/80"><p class="text-xs text-zinc-500">CTR</p><p class="mt-1 text-2xl font-semibold">{{ number_format($ctr, 2) }}%</p></div>
        </div>

        <div class="rounded-3xl border border-zinc-200 bg-white/85 p-5 shadow-sm ring-1 ring-transparent backdrop-blur dark:border-zinc-800 dark:bg-zinc-900/80">
            <p class="mb-4 text-sm font-medium text-zinc-500">Top testimonial performance</p>
            <div class="space-y-3">
                @forelse($series as $item)
                    @php
                        $max = max(1, $totalViews);
                        $width = min(100, round(($item->views_count / $max) * 100));
                    @endphp
                    <div>
                        <div class="mb-1 flex items-center justify-between text-xs text-zinc-500">
                            <span>{{ $item->author_name ?: $item->id }}</span>
                            <span>{{ number_format($item->views_count) }} views / {{ number_format($item->click_count) }} clicks</span>
                        </div>
                        <div class="h-2 rounded-full bg-zinc-200 dark:bg-zinc-800">
                            <div class="h-2 rounded-full bg-zinc-900 transition-all dark:bg-white" style="width: {{ $width }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-zinc-500">No analytics data yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
