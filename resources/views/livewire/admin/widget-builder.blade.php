<div class="min-h-screen bg-zinc-950 text-zinc-100" x-data="widgetBuilder()" x-init="renderPreview()">
    <div class="mx-auto flex min-h-screen max-w-[1500px]">
        <aside class="w-72 border-r border-white/10 bg-zinc-900/70 p-5 backdrop-blur-xl">
            <div class="mb-8 flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3"><div class="text-2xl">⟫</div><div class="text-3xl font-semibold tracking-tight">Deswu</div></div>
            <nav class="space-y-2 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-zinc-300 transition hover:bg-white/5"><span>🏠</span><span>Dashboard</span></a>
                <a href="{{ route('admin.inbox') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-zinc-300 transition hover:bg-white/5"><span>📥</span><span>Inbox</span></a>
                <a href="{{ route('admin.widget-builder') }}" class="flex items-center gap-3 rounded-xl border border-cyan-400/40 bg-cyan-500/15 px-4 py-3 text-cyan-200 ring-1 ring-cyan-300/40"><span>🧩</span><span>Widget Builder</span></a>
                <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-zinc-500"><span>💳</span><span>Billing</span></a>
                <a href="#" class="flex items-center gap-3 rounded-xl px-4 py-3 text-zinc-500"><span>⚙️</span><span>Settings</span></a>
            </nav>
        </aside>

        <main class="flex-1 p-7">
            <div class="mb-6 flex items-center justify-between">
                <div><h1 class="text-4xl font-semibold tracking-tight">Widget Builder</h1><p class="mt-1 text-sm text-zinc-400">Customize your widget and preview instantly.</p></div>
                <a href="{{ route('admin.inbox') }}" class="rounded-full bg-white px-5 py-2 text-sm font-medium text-zinc-900">Open Inbox</a>
            </div>

            <div class="grid gap-6 xl:grid-cols-[340px_1fr]">
                <section class="rounded-3xl border border-white/10 bg-white/[0.04] p-6 ring-1 ring-white/10 backdrop-blur">
                    <div class="space-y-5">
                        <label class="block"><span class="mb-2 block text-sm font-medium">Primary Color</span><input type="color" x-model="primaryColor" @input="renderPreview" class="h-11 w-20 cursor-pointer rounded-xl border border-white/20 bg-transparent p-1"></label>
                        <label class="block"><span class="mb-2 block text-sm font-medium">Border Radius</span><input type="range" min="8" max="28" step="1" x-model="borderRadius" @input="renderPreview" class="w-full accent-white"><p class="mt-1 text-xs text-zinc-500" x-text="`${borderRadius}px`"></p></label>
                        <label class="block"><span class="mb-2 block text-sm font-medium">Position</span><select x-model="position" @change="renderPreview" class="w-full rounded-xl border border-white/20 bg-zinc-900 px-3 py-2 text-sm"><option value="bottom_right">Bottom Right</option><option value="bottom_left">Bottom Left</option></select></label>
                    </div>
                </section>
                <section class="rounded-3xl border border-white/10 bg-white/[0.04] p-4 ring-1 ring-white/10 backdrop-blur">
                    <h2 class="mb-3 px-2 text-sm font-medium text-zinc-400">Live Preview</h2>
                    <iframe class="h-[620px] w-full rounded-2xl border border-white/10 bg-zinc-950" :srcdoc="previewHtml"></iframe>
                </section>
            </div>
        </main>
    </div>

    <script>
        function widgetBuilder() {
            return {
                primaryColor: '#18181b',
                borderRadius: 16,
                position: 'bottom_right',
                previewHtml: '',
                renderPreview() {
                    const bubbleSide = this.position === 'bottom_left' ? 'left: 20px;' : 'right: 20px;';
                    this.previewHtml = `<!doctype html><html><head><meta charset="utf-8"><style>
                        body{font-family:Inter,system-ui;background:#0b0d10;margin:0;padding:18px;color:#fff}
                        .wrap{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;margin-top:14px}
                        .card{position:relative;height:220px;border-radius:${this.borderRadius}px;overflow:hidden;background:#111827;box-shadow:0 10px 24px rgba(0,0,0,.28);transition:transform .2s ease}
                        .card:hover{transform:translateY(-2px)}
                        .play{position:absolute;right:8px;bottom:8px;background:${this.primaryColor};color:#fff;border:0;border-radius:999px;padding:6px 10px;font-size:12px}
                        .bubble{position:fixed;bottom:20px;${bubbleSide}width:84px;height:84px;border-radius:999px;overflow:hidden;background:${this.primaryColor};box-shadow:0 14px 32px rgba(0,0,0,.25)}
                    </style></head><body>
                        <h3 style="margin:0">Widget Preview</h3><p style="margin:6px 0 0;color:#a1a1aa;font-size:13px">Grid + bubble simulation</p>
                        <div class="wrap"><div class="card"><button class="play">▶</button></div><div class="card"><button class="play">▶</button></div><div class="card"><button class="play">▶</button></div></div>
                        <div class="bubble"></div>
                    </body></html>`;
                },
            };
        }
    </script>
</div>
