<div class="min-h-screen bg-zinc-50 px-6 py-10 text-zinc-900 dark:bg-zinc-950 dark:text-zinc-100" x-data="{ toast: null }" @toast.window="toast = $event.detail; setTimeout(() => toast = null, 3000)">
    <div class="mx-auto max-w-6xl space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Testimonial Inbox</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Review incoming videos and publish only quality social proof.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="rounded-2xl border border-zinc-300 px-4 py-2 text-sm font-medium dark:border-zinc-700">Back to Dashboard</a>
        </div>

        <div class="rounded-3xl border border-zinc-200 bg-white/85 p-5 shadow-sm ring-1 ring-transparent backdrop-blur transition dark:bg-zinc-900/80 dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mb-4 flex items-center gap-2">
                <label for="statusFilter" class="text-sm text-zinc-500 dark:text-zinc-400">Filter:</label>
                <select id="statusFilter" wire:model.live="statusFilter" class="rounded-xl border border-zinc-300 bg-transparent px-3 py-2 text-sm dark:border-zinc-700">
                    <option value="all">All</option>
                    <option value="uploaded">Uploaded</option>
                    <option value="pending_review">Pending Review</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-zinc-200 text-zinc-500 dark:border-zinc-800 dark:text-zinc-400">
                            <th class="py-3 pr-4">Campaign</th>
                            <th class="py-3 pr-4">Author</th>
                            <th class="py-3 pr-4">Source</th>
                            <th class="py-3 pr-4">Status</th>
                            <th class="py-3 pr-4">Received</th>
                            <th class="py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr class="border-b border-zinc-100 dark:border-zinc-800/80">
                                <td class="py-3 pr-4 font-medium"><a href="{{ route('admin.campaign.analytics', $item->campaign_id) }}" class="underline decoration-zinc-300 underline-offset-4 hover:decoration-zinc-700">{{ $item->campaign_name }}</a></td>
                                <td class="py-3 pr-4">{{ $item->author_name ?: 'Anonymous' }}</td>
                                <td class="py-3 pr-4">{{ $item->source }}</td>
                                <td class="py-3 pr-4">
                                    <span class="rounded-full bg-zinc-100 px-2.5 py-1 text-xs capitalize dark:bg-zinc-800">{{ str_replace('_', ' ', $item->status) }}</span>
                                </td>
                                <td class="py-3 pr-4 text-zinc-500 dark:text-zinc-400">{{ $item->created_at }}</td>
                                <td class="py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button wire:click="openPreview('{{ $item->id }}')" class="rounded-xl border border-zinc-300 px-3 py-1.5 text-xs font-medium transition hover:ring-2 hover:ring-zinc-200 dark:border-zinc-700 dark:hover:ring-zinc-700">Oynat</button>
                                        <button wire:click="approve('{{ $item->id }}')" class="rounded-xl bg-zinc-900 px-3 py-1.5 text-xs font-medium text-white transition hover:-translate-y-0.5 dark:bg-white dark:text-zinc-900">Onayla</button>
                                        <button wire:click="reject('{{ $item->id }}')" class="rounded-xl border border-red-300 px-3 py-1.5 text-xs font-medium text-red-600 transition hover:ring-2 hover:ring-red-100 dark:border-red-800 dark:text-red-400">Reddet</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-zinc-500 dark:text-zinc-400">No testimonials found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($previewUrl)
            <div class="rounded-3xl border border-zinc-200 bg-white/85 p-5 shadow-sm ring-1 ring-transparent backdrop-blur transition dark:bg-zinc-900/80 dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mb-3 flex items-center justify-between">
                    <h2 class="text-lg font-medium">Video Preview</h2>
                    <button wire:click="closePreview" class="rounded-xl border border-zinc-300 px-3 py-1.5 text-xs dark:border-zinc-700">Close</button>
                </div>
                <video class="aspect-video w-full rounded-2xl bg-black" controls preload="metadata" playsinline>
                    <source src="{{ $previewUrl }}" type="video/mp4">
                    <source src="{{ $previewUrl }}" type="video/webm">
                </video>
                <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">Served via Cloudflare CDN / R2 URL.</p>
            </div>
        @endif
    </div>

    <div
        x-show="toast"
        x-cloak
        class="fixed top-6 right-6 rounded-2xl bg-zinc-900 px-4 py-2 text-sm text-white shadow-lg dark:bg-white dark:text-zinc-900"
        x-text="toast?.message"
    ></div>
</div>
