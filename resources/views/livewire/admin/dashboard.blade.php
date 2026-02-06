<div class="min-h-screen bg-zinc-950 text-zinc-100" x-data="adminDashboard()" x-init="initBuilder()">
    <div class="mx-auto flex min-h-screen max-w-[1500px]">
        <aside class="w-72 border-r border-white/10 bg-zinc-900/70 p-5 backdrop-blur-xl">
            <div class="mb-8 flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                <div class="text-2xl">⟫</div>
                <div class="text-3xl font-semibold tracking-tight">Deswu</div>
            </div>

            <nav class="space-y-2 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl border border-cyan-400/40 bg-cyan-500/15 px-4 py-3 text-cyan-200 ring-1 ring-cyan-300/40 transition">
                    <span>🏠</span><span>Dashboard</span>
                </a>
                <a href="{{ route('admin.inbox') }}" class="flex items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-zinc-300 transition hover:border-white/10 hover:bg-white/5">
                    <span>📥</span><span>Inbox</span>
                </a>
                <a href="{{ route('admin.widget-builder') }}" class="flex items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-zinc-300 transition hover:border-white/10 hover:bg-white/5">
                    <span>🧩</span><span>Widget Builder</span>
                </a>
                <a href="#" class="flex items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-zinc-500 transition hover:bg-white/5">
                    <span>💳</span><span>Billing</span>
                </a>
                <a href="#" class="flex items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-zinc-500 transition hover:bg-white/5">
                    <span>⚙️</span><span>Settings</span>
                </a>
            </nav>
        </aside>

        <main class="flex-1 p-7">
            <div class="mb-5 flex items-start justify-between">
                <div>
                    <h1 class="text-4xl font-semibold tracking-tight">Admin Dashboard</h1>
                    <p class="mt-1 text-sm text-zinc-400">Operational overview for your testimonial engine.</p>
                </div>
                <a href="{{ route('admin.inbox') }}" class="rounded-full bg-white px-5 py-2 text-sm font-medium text-zinc-900 transition hover:-translate-y-0.5 hover:shadow-lg">Open Inbox</a>
            </div>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-5 ring-1 ring-white/10 backdrop-blur transition hover:bg-white/[0.07]"><p class="text-xs uppercase text-zinc-400">Total Videos</p><p class="mt-2 text-4xl font-semibold">{{ number_format($stats['total_videos']) }}</p></div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-5 ring-1 ring-white/10 backdrop-blur transition hover:bg-white/[0.07]"><p class="text-xs uppercase text-zinc-400">Pending Videos</p><p class="mt-2 text-4xl font-semibold">{{ number_format($stats['pending_videos']) }}</p></div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-5 ring-1 ring-white/10 backdrop-blur transition hover:bg-white/[0.07]"><p class="text-xs uppercase text-zinc-400">Active Campaigns</p><p class="mt-2 text-4xl font-semibold">{{ number_format($stats['active_campaigns']) }}</p></div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-5 ring-1 ring-white/10 backdrop-blur transition hover:bg-white/[0.07]"><p class="text-xs uppercase text-zinc-400">Monthly Upload Usage</p><p class="mt-2 text-4xl font-semibold">{{ number_format($stats['monthly_upload_mb'], 2) }}<span class="text-lg text-zinc-400"> MB</span></p></div>
            </div>

            <div class="mt-8 flex gap-2 border-b border-white/10 pb-3">
                <button @click="tab='home'" :class="tab==='home' ? 'bg-white text-zinc-900' : 'bg-white/5 text-zinc-200'" class="rounded-2xl px-6 py-2.5 text-sm font-semibold transition">Home</button>
                <button @click="tab='builder'" :class="tab==='builder' ? 'bg-white text-zinc-900' : 'bg-white/5 text-zinc-200'" class="rounded-2xl px-6 py-2.5 text-sm font-semibold transition">Widget Builder</button>
                <button @click="tab='analytics'" :class="tab==='analytics' ? 'bg-white text-zinc-900' : 'bg-white/5 text-zinc-200'" class="rounded-2xl px-6 py-2.5 text-sm font-semibold transition">Campaign Analytics</button>
            </div>

            <section class="mt-5" x-show="tab==='home'" x-transition>
                @if($stats['show_onboarding'])
                    <div class="rounded-3xl border border-dashed border-white/20 bg-white/[0.03] p-10 text-center ring-1 ring-white/10 backdrop-blur">
                        <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-white/10 text-3xl">🎬</div>
                        <h2 class="text-2xl font-semibold tracking-tight">Henüz video yok ama başlamak çok kolay!</h2>
                        <p class="mx-auto mt-2 max-w-xl text-sm text-zinc-400">İlk kampanyanı oluştur, müşterilerinden videolu yorum topla ve birkaç dakika içinde sitende sosyal kanıt göstermeye başla.</p>
                        <a href="/admin/campaigns/create" class="mt-5 inline-flex rounded-2xl bg-white px-5 py-2.5 text-sm font-semibold text-zinc-900 transition hover:-translate-y-0.5">İlk Kampanyanı Oluştur</a>
                    </div>
                @else
                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-8 text-sm text-zinc-300 ring-1 ring-white/10 backdrop-blur">Harika! Kampanyaların aktif. Sol menüden Inbox veya Widget Builder adımlarına geçebilirsin.</div>
                @endif
            </section>

            <section class="mt-5" x-show="tab==='builder'" x-transition>
                <div class="grid gap-5 xl:grid-cols-[340px_1fr]">
                    <div class="rounded-3xl border border-white/10 bg-white/[0.04] p-5 ring-1 ring-white/10 backdrop-blur">
                        <h3 class="mb-4 text-lg font-semibold">Widget Controls</h3>
                        <div class="space-y-4 text-sm">
                            <label class="block"><span class="mb-2 block text-zinc-300">Primary Color</span><input type="color" x-model="primaryColor" @input="renderBuilder" class="h-11 w-20 rounded-xl border border-white/15 bg-transparent"></label>
                            <label class="block"><span class="mb-2 block text-zinc-300">Border Radius</span><input type="range" min="8" max="28" step="1" x-model="borderRadius" @input="renderBuilder" class="w-full accent-white"><p class="mt-1 text-xs text-zinc-500" x-text="`${borderRadius}px`"></p></label>
                            <label class="block"><span class="mb-2 block text-zinc-300">Position</span><select x-model="position" @change="renderBuilder" class="w-full rounded-xl border border-white/15 bg-zinc-900 px-3 py-2"><option value="bottom_right">Bottom Right</option><option value="bottom_left">Bottom Left</option></select></label>
                        </div>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/[0.04] p-4 ring-1 ring-white/10 backdrop-blur">
                        <h3 class="mb-3 text-sm text-zinc-400">Live Preview</h3>
                        <iframe class="h-[520px] w-full rounded-2xl border border-white/10 bg-zinc-950" :srcdoc="builderPreview"></iframe>
                    </div>
                </div>
            </section>

            <section class="mt-5" x-show="tab==='analytics'" x-transition>
                <div class="rounded-3xl border border-white/10 bg-white/[0.04] p-5 ring-1 ring-white/10 backdrop-blur">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                        <h3 class="text-lg font-semibold">Campaign Analytics</h3>
                        <select wire:model.live="analyticsCampaignId" class="rounded-xl border border-white/15 bg-zinc-900 px-3 py-2 text-sm">
                            @forelse($campaigns as $campaign)
                                <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
                            @empty
                                <option value="">No campaigns yet</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="mb-5 grid gap-3 md:grid-cols-3">
                        <div class="rounded-2xl border border-white/10 bg-black/20 p-4"><p class="text-xs text-zinc-400">Total Views</p><p class="mt-1 text-2xl font-semibold">{{ number_format($analyticsTotals['views']) }}</p></div>
                        <div class="rounded-2xl border border-white/10 bg-black/20 p-4"><p class="text-xs text-zinc-400">Total Clicks</p><p class="mt-1 text-2xl font-semibold">{{ number_format($analyticsTotals['clicks']) }}</p></div>
                        <div class="rounded-2xl border border-white/10 bg-black/20 p-4"><p class="text-xs text-zinc-400">CTR</p><p class="mt-1 text-2xl font-semibold">{{ number_format($analyticsTotals['ctr'], 2) }}%</p></div>
                    </div>

                    <div class="space-y-3">
                        @forelse($series as $item)
                            @php
                                $max = max(1, $analyticsTotals['views']);
                                $width = min(100, round(($item->views_count / $max) * 100));
                            @endphp
                            <div>
                                <div class="mb-1 flex items-center justify-between text-xs text-zinc-400">
                                    <span>{{ $item->author_name ?: $item->id }}</span>
                                    <span>{{ number_format($item->views_count) }} views / {{ number_format($item->click_count) }} clicks</span>
                                </div>
                                <div class="h-2 rounded-full bg-white/10"><div class="h-2 rounded-full bg-cyan-400 transition-all" style="width: {{ $width }}%"></div></div>
                            </div>
                        @empty
                            <p class="text-sm text-zinc-500">No analytics data yet.</p>
                        @endforelse
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        function adminDashboard() {
            return {
                tab: 'home',
                primaryColor: '#18181b',
                borderRadius: 16,
                position: 'bottom_right',
                builderPreview: '',
                initBuilder() { this.renderBuilder(); },
                renderBuilder() {
                    const bubbleSide = this.position === 'bottom_left' ? 'left:20px;' : 'right:20px;';
                    this.builderPreview = `<!doctype html><html><head><meta charset="utf-8"><style>
                    body{margin:0;padding:16px;background:#0b0d10;color:#fff;font-family:Inter,system-ui}
                    .grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;margin-top:12px}
                    .card{height:220px;background:#111827;border-radius:${this.borderRadius}px;box-shadow:0 8px 20px rgba(0,0,0,.35)}
                    .bubble{position:fixed;bottom:20px;${bubbleSide}width:84px;height:84px;border-radius:999px;background:${this.primaryColor}}
                    </style></head><body><h3 style="margin:0">Widget Preview</h3><div class="grid"><div class="card"></div><div class="card"></div><div class="card"></div></div><div class="bubble"></div></body></html>`;
                },
            };
        }
    </script>
</div>
