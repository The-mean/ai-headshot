<div class="min-h-screen bg-zinc-950 text-zinc-100">
    <div class="mx-auto flex min-h-screen max-w-[1500px]">
        <aside class="w-72 border-r border-white/10 bg-zinc-900/70 p-5 backdrop-blur-xl">
            <div class="mb-8 flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3"><div class="text-2xl">⟫</div><div class="text-3xl font-semibold tracking-tight">Deswu</div></div>
            <nav class="space-y-2 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-zinc-300 transition hover:bg-white/5"><span>🏠</span><span>Dashboard</span></a>
                <a href="{{ route('admin.inbox') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-zinc-300 transition hover:bg-white/5"><span>📥</span><span>Inbox</span></a>
                <a href="{{ route('admin.widget-builder') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-zinc-300 transition hover:bg-white/5"><span>🧩</span><span>Widget Builder</span></a>
                <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-zinc-500"><span>💳</span><span>Billing</span></a>
                <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-zinc-500"><span>⚙️</span><span>Settings</span></a>
            </nav>
        </aside>

        <main class="flex-1 p-7">
            <div class="mb-5 flex items-center justify-between">
                <div><h1 class="text-4xl font-semibold tracking-tight">Campaign Analytics</h1><p class="mt-1 text-sm text-zinc-400">{{ $campaign->name ?? 'Campaign' }} için izlenme/tıklama görünümü</p></div>
                <a href="{{ route('admin.inbox') }}" class="rounded-full bg-white px-5 py-2 text-sm font-medium text-zinc-900">Open Inbox</a>
            </div>

            <div class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-white/10 bg-white/[0.04] p-4 ring-1 ring-white/10 backdrop-blur"><p class="text-xs text-zinc-400">Total Views</p><p class="mt-1 text-3xl font-semibold">{{ number_format($totalViews) }}</p></div>
                <div class="rounded-2xl border border-white/10 bg-white/[0.04] p-4 ring-1 ring-white/10 backdrop-blur"><p class="text-xs text-zinc-400">Total Clicks</p><p class="mt-1 text-3xl font-semibold">{{ number_format($totalClicks) }}</p></div>
                <div class="rounded-2xl border border-white/10 bg-white/[0.04] p-4 ring-1 ring-white/10 backdrop-blur"><p class="text-xs text-zinc-400">CTR</p><p class="mt-1 text-3xl font-semibold">{{ number_format($ctr, 2) }}%</p></div>
            </div>

            <div class="mt-6 rounded-3xl border border-white/10 bg-white/[0.04] p-5 ring-1 ring-white/10 backdrop-blur">
                <p class="mb-4 text-sm font-medium text-zinc-400">Top testimonial performance</p>
                <div class="space-y-4">
                    @forelse($series as $item)
                        @php
                            $max = max(1, $totalViews);
                            $width = min(100, round(($item->views_count / $max) * 100));
                        @endphp
                        <div>
                            <div class="mb-1 flex items-center justify-between text-xs text-zinc-400"><span>{{ $item->author_name ?: $item->id }}</span><span>{{ number_format($item->views_count) }} views / {{ number_format($item->click_count) }} clicks</span></div>
                            <div class="h-2 rounded-full bg-white/10"><div class="h-2 rounded-full bg-cyan-400 transition-all" style="width: {{ $width }}%"></div></div>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-500">No analytics data yet.</p>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
</div>
