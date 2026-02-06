<div class="min-h-screen bg-zinc-950 text-zinc-100" x-data="{ toast: null }" @toast.window="toast = $event.detail; setTimeout(() => toast = null, 3000)">
    <div class="mx-auto flex min-h-screen max-w-[1500px]">
        <aside class="w-72 border-r border-white/10 bg-zinc-900/70 p-5 backdrop-blur-xl">
            <div class="mb-8 flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3"><div class="text-2xl">⟫</div><div class="text-3xl font-semibold tracking-tight">Deswu</div></div>
            <nav class="space-y-2 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-zinc-300 transition hover:bg-white/5"><span>🏠</span><span>Dashboard</span></a>
                <a href="{{ route('admin.inbox') }}" class="flex items-center gap-3 rounded-xl border border-cyan-400/40 bg-cyan-500/15 px-4 py-3 text-cyan-200 ring-1 ring-cyan-300/40"><span>📥</span><span>Inbox</span></a>
                <a href="{{ route('admin.widget-builder') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-zinc-300 transition hover:bg-white/5"><span>🧩</span><span>Widget Builder</span></a>
                <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-zinc-500"><span>💳</span><span>Billing</span></a>
                <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-zinc-500"><span>⚙️</span><span>Settings</span></a>
            </nav>
        </aside>

        <main class="flex-1 p-7">
            <div class="mb-5 flex items-start justify-between">
                <div><h1 class="text-4xl font-semibold tracking-tight">Testimonial Inbox</h1><p class="mt-1 text-sm text-zinc-400">Review incoming videos and publish only quality social proof.</p></div>
                <a href="{{ route('admin.dashboard') }}" class="rounded-full bg-white px-5 py-2 text-sm font-medium text-zinc-900 transition hover:-translate-y-0.5">Back Dashboard</a>
            </div>

            <div class="rounded-3xl border border-white/10 bg-white/[0.04] p-5 ring-1 ring-white/10 backdrop-blur">
                <div class="mb-4 flex items-center gap-2">
                    <label for="statusFilter" class="text-sm text-zinc-400">Filter:</label>
                    <select id="statusFilter" wire:model.live="statusFilter" class="rounded-xl border border-white/15 bg-zinc-900 px-3 py-2 text-sm">
                        <option value="all">All</option><option value="uploaded">Uploaded</option><option value="pending_review">Pending Review</option><option value="approved">Approved</option><option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead><tr class="border-b border-white/10 text-zinc-400"><th class="py-3 pr-4">Campaign</th><th class="py-3 pr-4">Author</th><th class="py-3 pr-4">Source</th><th class="py-3 pr-4">Status</th><th class="py-3 pr-4">Received</th><th class="py-3 text-right">Actions</th></tr></thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr class="border-b border-white/5">
                                    <td class="py-3 pr-4 font-medium"><a href="{{ route('admin.campaign.analytics', $item->campaign_id) }}" class="underline decoration-zinc-500 underline-offset-4 hover:decoration-cyan-300">{{ $item->campaign_name }}</a></td>
                                    <td class="py-3 pr-4">{{ $item->author_name ?: 'Anonymous' }}</td>
                                    <td class="py-3 pr-4">{{ $item->source }}</td>
                                    <td class="py-3 pr-4"><span class="rounded-full bg-white/10 px-2.5 py-1 text-xs capitalize">{{ str_replace('_', ' ', $item->status) }}</span></td>
                                    <td class="py-3 pr-4 text-zinc-400">{{ $item->created_at }}</td>
                                    <td class="py-3 text-right"><div class="flex justify-end gap-2"><button wire:click="openPreview('{{ $item->id }}')" class="rounded-xl border border-white/15 px-3 py-1.5 text-xs transition hover:ring-2 hover:ring-white/20">Oynat</button><button wire:click="approve('{{ $item->id }}')" class="rounded-xl bg-white px-3 py-1.5 text-xs font-semibold text-zinc-900 transition hover:-translate-y-0.5">Onayla</button><button wire:click="reject('{{ $item->id }}')" class="rounded-xl border border-red-500/40 px-3 py-1.5 text-xs text-red-300 transition hover:ring-2 hover:ring-red-400/20">Reddet</button></div></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="py-8 text-center text-zinc-500">No testimonials found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($previewUrl)
                <div class="mt-6 rounded-3xl border border-white/10 bg-white/[0.04] p-5 ring-1 ring-white/10 backdrop-blur">
                    <div class="mb-3 flex items-center justify-between"><h2 class="text-lg font-medium">Video Preview</h2><button wire:click="closePreview" class="rounded-xl border border-white/15 px-3 py-1.5 text-xs">Close</button></div>
                    <video class="aspect-video w-full rounded-2xl bg-black" controls preload="metadata" playsinline><source src="{{ $previewUrl }}" type="video/mp4"><source src="{{ $previewUrl }}" type="video/webm"></video>
                </div>
            @endif
        </main>
    </div>

    <div x-show="toast" x-cloak class="fixed top-6 right-6 rounded-2xl bg-white px-4 py-2 text-sm text-zinc-900 shadow-lg" x-text="toast?.message"></div>
</div>
