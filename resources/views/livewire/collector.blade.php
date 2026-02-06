<div class="min-h-screen bg-zinc-50 px-6 py-10 text-zinc-900 transition dark:bg-zinc-950 dark:text-zinc-100" x-data="collectorApp(@js($campaignId))" x-init="init()">
    <div class="mx-auto max-w-3xl">
        <div class="rounded-3xl border border-zinc-200/70 bg-white/85 p-6 shadow-sm ring-1 ring-transparent backdrop-blur transition dark:border-zinc-800 dark:bg-zinc-900/80 dark:hover:ring-zinc-700">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-semibold tracking-tight">Share your experience</h1>
                <button type="button" class="rounded-full border border-zinc-300 px-4 py-2 text-sm transition hover:ring-2 hover:ring-zinc-200 dark:border-zinc-700 dark:hover:ring-zinc-700" @click="darkMode = !darkMode; syncTheme()">
                    <span x-text="darkMode ? 'Light' : 'Dark'"></span>
                </button>
            </div>

            <p class="mb-6 text-sm text-zinc-500 dark:text-zinc-400">Camera permission → 3-2-1 countdown → record → preview → upload</p>

            <div class="relative overflow-hidden rounded-2xl bg-zinc-100 ring-1 ring-zinc-200 transition dark:bg-zinc-800 dark:ring-zinc-700">
                <video x-ref="liveVideo" class="aspect-video w-full" autoplay muted playsinline></video>
                <video x-ref="previewVideo" class="aspect-video w-full" controls x-show="previewUrl" :src="previewUrl"></video>

                <div x-show="showGuideOverlay" class="absolute inset-0 flex items-end bg-black/35 backdrop-blur-[1px] transition" x-transition>
                    <div class="m-4 w-full rounded-2xl border border-white/20 bg-black/40 p-4 text-white ring-1 ring-white/20">
                        <p class="text-xs uppercase tracking-[0.18em] text-white/70">Recording Tips</p>
                        <ul class="mt-2 space-y-1 text-sm">
                            <li>• Ürünü kameraya gösterin.</li>
                            <li>• Gülümseyin ve doğal konuşun.</li>
                            <li>• 20-30 saniyede faydayı anlatın.</li>
                        </ul>
                    </div>
                </div>

                <div x-show="countdown > 0" class="absolute inset-0 flex items-center justify-center bg-black/45 text-7xl font-bold text-white transition" x-text="countdown"></div>
            </div>

            <div class="mt-6 grid gap-3 sm:grid-cols-2">
                <button class="rounded-2xl bg-zinc-900 px-4 py-3 text-sm font-medium text-white ring-1 ring-zinc-900/20 transition hover:-translate-y-0.5 dark:bg-white dark:text-zinc-900" @click="requestCamera" :disabled="loading">Enable Camera</button>
                <button class="rounded-2xl border border-zinc-300 px-4 py-3 text-sm font-medium transition hover:ring-2 hover:ring-zinc-200 dark:border-zinc-700 dark:hover:ring-zinc-700" @click="startRecordingFlow" :disabled="!stream || recording || loading">Start 3-2-1 Record</button>
                <button class="rounded-2xl border border-zinc-300 px-4 py-3 text-sm font-medium transition hover:ring-2 hover:ring-zinc-200 dark:border-zinc-700 dark:hover:ring-zinc-700" @click="stopRecording" :disabled="!recording">Stop Recording</button>
                <label class="cursor-pointer rounded-2xl border border-zinc-300 px-4 py-3 text-center text-sm font-medium transition hover:ring-2 hover:ring-zinc-200 dark:border-zinc-700 dark:hover:ring-zinc-700">
                    Upload Existing Video
                    <input type="file" class="hidden" accept="video/webm,video/mp4" @change="onFilePicked">
                </label>
            </div>

            <div class="mt-6" x-show="uploadProgress > 0">
                <div class="mb-2 flex justify-between text-xs text-zinc-500 dark:text-zinc-400"><span>Uploading...</span><span x-text="`${uploadProgress}%`"></span></div>
                <div class="h-2 rounded-full bg-zinc-200 dark:bg-zinc-800"><div class="h-2 rounded-full bg-zinc-900 transition-all dark:bg-white" :style="`width: ${uploadProgress}%`"></div></div>
            </div>

            <label class="mt-6 flex items-start gap-3 rounded-2xl border border-zinc-200 bg-zinc-50/90 px-4 py-3 text-sm text-zinc-700 ring-1 ring-transparent backdrop-blur transition hover:ring-zinc-200 dark:border-zinc-800 dark:bg-zinc-900/70 dark:text-zinc-300 dark:hover:ring-zinc-700">
                <input type="checkbox" class="mt-0.5 h-4 w-4 rounded border-zinc-300" x-model="isConsentGiven">
                <span>Görüntümün pazarlama amaçlı kullanılmasına ve kullanıcı sözleşmesine izin veriyorum.</span>
            </label>

            <div class="mt-4 flex items-center justify-between">
                <p class="text-sm text-zinc-500 dark:text-zinc-400" x-text="statusText"></p>
                <button class="rounded-2xl bg-zinc-900 px-4 py-3 text-sm font-medium text-white ring-1 ring-zinc-900/20 transition hover:-translate-y-0.5 disabled:opacity-50 dark:bg-white dark:text-zinc-900" @click="uploadVideo" :disabled="!videoBlob || loading || !isConsentGiven">Upload to Cloud</button>
            </div>
        </div>
    </div>

    <script>
        function collectorApp(campaignId) {
            return {
                campaignId, stream: null, mediaRecorder: null, recordedChunks: [], videoBlob: null, previewUrl: null,
                countdown: 0, recording: false, loading: false, uploadProgress: 0, statusText: 'Ready',
                darkMode: false, isConsentGiven: false, showGuideOverlay: true,

                init() { this.darkMode = window.matchMedia('(prefers-color-scheme: dark)').matches; this.syncTheme(); },
                syncTheme() { document.documentElement.classList.toggle('dark', this.darkMode); },
                async requestCamera() {
                    try { this.stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true }); this.$refs.liveVideo.srcObject = this.stream; this.statusText = 'Camera is ready.'; this.showGuideOverlay = true; }
                    catch (error) { this.statusText = 'Camera permission denied.'; }
                },
                async startRecordingFlow() {
                    if (!this.stream) return;
                    this.countdown = 3; this.showGuideOverlay = true;
                    while (this.countdown > 0) { await new Promise((r) => setTimeout(r, 1000)); this.countdown -= 1; }
                    this.startRecording();
                },
                startRecording() {
                    this.recordedChunks = [];
                    const mimeType = MediaRecorder.isTypeSupported('video/webm;codecs=vp9') ? 'video/webm;codecs=vp9' : 'video/mp4';
                    this.mediaRecorder = new MediaRecorder(this.stream, { mimeType });
                    this.mediaRecorder.ondataavailable = (event) => { if (event.data.size > 0) this.recordedChunks.push(event.data); };
                    this.mediaRecorder.onstop = () => { this.videoBlob = new Blob(this.recordedChunks, { type: this.mediaRecorder.mimeType }); this.previewUrl = URL.createObjectURL(this.videoBlob); this.$refs.previewVideo.load(); this.statusText = 'Preview ready.'; this.showGuideOverlay = true; };
                    this.mediaRecorder.start(250); this.recording = true; this.showGuideOverlay = true; this.statusText = 'Recording...';
                },
                stopRecording() { if (!this.mediaRecorder || !this.recording) return; this.mediaRecorder.stop(); this.recording = false; },
                onFilePicked(event) { const file = event.target.files[0]; if (!file) return; this.videoBlob = file; this.previewUrl = URL.createObjectURL(file); this.$refs.previewVideo.load(); this.showGuideOverlay = false; this.statusText = 'File selected.'; },
                async uploadVideo() {
                    if (!this.videoBlob || !this.isConsentGiven) { this.statusText = 'Please accept consent to continue.'; return; }
                    this.loading = true; this.uploadProgress = 0; this.statusText = 'Preparing upload...';
                    const extension = this.videoBlob.type.includes('mp4') ? 'mp4' : 'webm';
                    const presignResponse = await fetch('/api/v1/testimonials/presign', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ campaign_id: this.campaignId, extension, file_size_bytes: this.videoBlob.size }), });
                    if (!presignResponse.ok) { this.statusText = 'Presign failed.'; this.loading = false; return; }
                    const presignPayload = await presignResponse.json(); const uploadData = presignPayload.data;
                    await this.uploadToR2(uploadData.url, uploadData.headers || {}, this.videoBlob);
                    await fetch('/api/v1/testimonials', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ campaign_id: this.campaignId, storage_disk: 'r2', storage_path: uploadData.path, source: 'widget_record', status: 'pending_review', is_consent_given: this.isConsentGiven, file_size_bytes: this.videoBlob.size, mime_type: this.videoBlob.type, meta: { uploaded_via: 'collector_ui', user_agent: navigator.userAgent }, }), });
                    this.statusText = 'Uploaded successfully. Pending review.'; this.loading = false;
                },
                uploadToR2(url, headers, file) {
                    return new Promise((resolve, reject) => {
                        const xhr = new XMLHttpRequest(); xhr.open('PUT', url); Object.entries(headers).forEach(([key, value]) => xhr.setRequestHeader(key, value));
                        if (!headers['Content-Type']) xhr.setRequestHeader('Content-Type', file.type || 'video/webm');
                        xhr.upload.onprogress = (event) => { if (!event.lengthComputable) return; this.uploadProgress = Math.round((event.loaded / event.total) * 100); };
                        xhr.onload = () => { if (xhr.status >= 200 && xhr.status < 300) { resolve(true); return; } this.loading = false; this.statusText = 'Upload failed.'; reject(new Error('Upload failed')); };
                        xhr.onerror = () => { this.loading = false; this.statusText = 'Network error during upload.'; reject(new Error('Network error')); };
                        xhr.send(file);
                    });
                },
            };
        }
    </script>
</div>
