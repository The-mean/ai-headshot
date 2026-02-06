<div class="min-h-screen bg-zinc-50 px-6 py-10 text-zinc-900 dark:bg-zinc-950 dark:text-zinc-100" x-data="widgetBuilder()" x-init="renderPreview()">
    <div class="mx-auto grid max-w-7xl gap-6 lg:grid-cols-[1fr_1.3fr]">
        <section class="rounded-3xl border border-zinc-200 bg-white/85 p-6 shadow-sm ring-1 ring-transparent backdrop-blur transition dark:border-zinc-800 dark:bg-zinc-900/80">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight">Widget Builder</h1>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Customize your widget and preview instantly.</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-zinc-300 px-3 py-2 text-sm transition hover:ring-2 hover:ring-zinc-200 dark:border-zinc-700 dark:hover:ring-zinc-700">Dashboard</a>
            </div>

            <div class="space-y-5">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium">Primary Color</span>
                    <input type="color" x-model="primaryColor" @input="renderPreview" class="h-11 w-20 cursor-pointer rounded-xl border border-zinc-300 bg-white p-1 ring-1 ring-transparent transition hover:ring-zinc-300 dark:border-zinc-700 dark:bg-zinc-900">
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium">Border Radius</span>
                    <input type="range" min="8" max="28" step="1" x-model="borderRadius" @input="renderPreview" class="w-full accent-zinc-900">
                    <p class="mt-1 text-xs text-zinc-500" x-text="`${borderRadius}px`"></p>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium">Position</span>
                    <select x-model="position" @change="renderPreview" class="w-full rounded-xl border border-zinc-300 bg-white px-3 py-2 text-sm ring-1 ring-transparent transition focus:ring-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:focus:ring-zinc-700">
                        <option value="bottom_right">Bottom Right</option>
                        <option value="bottom_left">Bottom Left</option>
                    </select>
                </label>
            </div>
        </section>

        <section class="rounded-3xl border border-zinc-200 bg-white/85 p-4 shadow-sm ring-1 ring-transparent backdrop-blur dark:border-zinc-800 dark:bg-zinc-900/80">
            <h2 class="mb-3 px-2 text-sm font-medium text-zinc-500 dark:text-zinc-400">Live Preview</h2>
            <iframe class="h-[620px] w-full rounded-2xl border border-zinc-200 bg-white ring-1 ring-zinc-200/70 dark:border-zinc-800" :srcdoc="previewHtml"></iframe>
        </section>
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
                    this.previewHtml = `<!doctype html>
<html><head><meta charset="utf-8"><style>
body{font-family:Inter,system-ui;background:#f4f4f5;margin:0;padding:18px;color:#111827}
.wrap{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;margin-top:14px}
.card{position:relative;height:220px;border-radius:${this.borderRadius}px;overflow:hidden;background:#111827;box-shadow:0 10px 24px rgba(0,0,0,.14);transition:transform .2s ease}
.card:hover{transform:translateY(-2px)}
.card img{width:100%;height:100%;object-fit:cover}
.play{position:absolute;right:8px;bottom:8px;background:${this.primaryColor};color:#fff;border:0;border-radius:999px;padding:6px 10px;font-size:12px}
.bubble{position:fixed;bottom:20px;${bubbleSide}width:84px;height:84px;border-radius:999px;overflow:hidden;box-shadow:0 14px 32px rgba(0,0,0,.25)}
.bubble img{width:100%;height:100%;object-fit:cover}
</style></head><body>
<h3 style="margin:0">Widget Preview</h3><p style="margin:6px 0 0;color:#52525b;font-size:13px">Grid + bubble simulation</p>
<div class="wrap">
  <div class="card"><img src="https://picsum.photos/seed/wa/280/420"/><button class="play">▶</button></div>
  <div class="card"><img src="https://picsum.photos/seed/wb/280/420"/><button class="play">▶</button></div>
  <div class="card"><img src="https://picsum.photos/seed/wc/280/420"/><button class="play">▶</button></div>
</div>
<div class="bubble"><img src="https://picsum.photos/seed/wd/200/200"/></div>
</body></html>`;
                },
            };
        }
    </script>
</div>
