<!-- Floating Pop-up Toast Notification Container -->
<div id="popup-toast-container" class="fixed top-5 right-4 sm:right-6 z-[9999] pointer-events-none flex flex-col gap-3 max-w-md w-full px-2 sm:px-0" aria-live="polite">

    @if(session('success'))
    <div class="popup-toast-item pointer-events-auto transform translate-x-full opacity-0 transition-all duration-300 ease-out bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border border-emerald-500/40 shadow-2xl shadow-emerald-500/15 rounded-3xl p-4 sm:p-5 flex flex-col gap-3 relative overflow-hidden group hover:scale-[1.01]"
         role="alert"
         data-auto-dismiss="4500">
        <div class="flex items-start gap-3.5">
            <!-- Icon Badge -->
            <div class="w-10 h-10 rounded-2xl bg-emerald-500/15 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 flex items-center justify-center font-black text-lg shrink-0 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <!-- Text Content -->
            <div class="flex-1 min-w-0 pr-2">
                <h4 class="text-xs sm:text-sm font-black text-slate-900 dark:text-white flex items-center gap-1.5">
                    <span>Aksi Berhasil!</span>
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                </h4>
                <p class="text-xs text-slate-600 dark:text-slate-300 font-medium mt-0.5 leading-relaxed">
                    {{ session('success') }}
                </p>
            </div>
            <!-- Dismiss Button -->
            <button type="button" onclick="closePopupToast(this.closest('.popup-toast-item'))" 
                    class="p-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition shrink-0"
                    title="Tutup Notifikasi">
                ✕
            </button>
        </div>
        <!-- Progress countdown bar -->
        <div class="w-full bg-slate-100 dark:bg-slate-800 h-1 rounded-full overflow-hidden">
            <div class="popup-toast-progress bg-gradient-to-r from-emerald-500 to-teal-400 h-full w-full rounded-full transition-all duration-[4500ms] ease-linear"></div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="popup-toast-item pointer-events-auto transform translate-x-full opacity-0 transition-all duration-300 ease-out bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border border-rose-500/40 shadow-2xl shadow-rose-500/15 rounded-3xl p-4 sm:p-5 flex flex-col gap-3 relative overflow-hidden group hover:scale-[1.01]"
         role="alert"
         data-auto-dismiss="5500">
        <div class="flex items-start gap-3.5">
            <!-- Icon Badge -->
            <div class="w-10 h-10 rounded-2xl bg-rose-500/15 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-500/30 flex items-center justify-center font-black text-lg shrink-0 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <!-- Text Content -->
            <div class="flex-1 min-w-0 pr-2">
                <h4 class="text-xs sm:text-sm font-black text-slate-900 dark:text-white flex items-center gap-1.5">
                    <span>Terjadi Kesalahan</span>
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                </h4>
                <p class="text-xs text-slate-600 dark:text-slate-300 font-medium mt-0.5 leading-relaxed">
                    {{ session('error') }}
                </p>
            </div>
            <!-- Dismiss Button -->
            <button type="button" onclick="closePopupToast(this.closest('.popup-toast-item'))" 
                    class="p-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition shrink-0"
                    title="Tutup Notifikasi">
                ✕
            </button>
        </div>
        <!-- Progress countdown bar -->
        <div class="w-full bg-slate-100 dark:bg-slate-800 h-1 rounded-full overflow-hidden">
            <div class="popup-toast-progress bg-gradient-to-r from-rose-500 to-pink-500 h-full w-full rounded-full transition-all duration-[5500ms] ease-linear"></div>
        </div>
    </div>
    @endif

    @if(session('warning'))
    <div class="popup-toast-item pointer-events-auto transform translate-x-full opacity-0 transition-all duration-300 ease-out bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border border-amber-500/40 shadow-2xl shadow-amber-500/15 rounded-3xl p-4 sm:p-5 flex flex-col gap-3 relative overflow-hidden group hover:scale-[1.01]"
         role="alert"
         data-auto-dismiss="5000">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-amber-500/15 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/30 flex items-center justify-center font-black text-lg shrink-0 shadow-sm">
                ⚠
            </div>
            <div class="flex-1 min-w-0 pr-2">
                <h4 class="text-xs sm:text-sm font-black text-slate-900 dark:text-white flex items-center gap-1.5">
                    <span>Perhatian</span>
                </h4>
                <p class="text-xs text-slate-600 dark:text-slate-300 font-medium mt-0.5 leading-relaxed">
                    {{ session('warning') }}
                </p>
            </div>
            <button type="button" onclick="closePopupToast(this.closest('.popup-toast-item'))" 
                    class="p-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition shrink-0">
                ✕
            </button>
        </div>
        <div class="w-full bg-slate-100 dark:bg-slate-800 h-1 rounded-full overflow-hidden">
            <div class="popup-toast-progress bg-gradient-to-r from-amber-500 to-yellow-400 h-full w-full rounded-full transition-all duration-[5000ms] ease-linear"></div>
        </div>
    </div>
    @endif

</div>

<script>
    function openPopupToast(el) {
        if (!el) return;
        requestAnimationFrame(() => {
            el.classList.remove('translate-x-full', 'opacity-0');
            el.classList.add('translate-x-0', 'opacity-100');
            
            const progress = el.querySelector('.popup-toast-progress');
            if (progress) {
                requestAnimationFrame(() => {
                    progress.style.width = '0%';
                });
            }
        });

        const duration = parseInt(el.getAttribute('data-auto-dismiss') || '4500', 10);
        let timer = setTimeout(() => {
            closePopupToast(el);
        }, duration);

        el.addEventListener('mouseenter', () => {
            clearTimeout(timer);
        });
        el.addEventListener('mouseleave', () => {
            timer = setTimeout(() => {
                closePopupToast(el);
            }, 1800);
        });
    }

    function closePopupToast(el) {
        if (!el) return;
        el.classList.remove('translate-x-0', 'opacity-100');
        el.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            el.remove();
        }, 350);
    }

    window.showToast = function(message, type = 'success', title = null) {
        const container = document.getElementById('popup-toast-container');
        if (!container) return;

        const isSuccess = type === 'success';
        const isError = type === 'error';
        const isWarning = type === 'warning';

        const borderColor = isSuccess ? 'border-emerald-500/40 shadow-emerald-500/15' : (isError ? 'border-rose-500/40 shadow-rose-500/15' : 'border-amber-500/40 shadow-amber-500/15');
        const iconBg = isSuccess ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border-emerald-500/30' : (isError ? 'bg-rose-500/15 text-rose-600 dark:text-rose-400 border-rose-500/30' : 'bg-amber-500/15 text-amber-600 dark:text-amber-400 border-amber-500/30');
        const iconHtml = isSuccess ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>' : (isError ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>' : '⚠');
        const defaultTitle = isSuccess ? 'Aksi Berhasil!' : (isError ? 'Terjadi Kesalahan' : 'Perhatian');
        const progressGrad = isSuccess ? 'from-emerald-500 to-teal-400' : (isError ? 'from-rose-500 to-pink-500' : 'from-amber-500 to-yellow-400');

        const toastEl = document.createElement('div');
        toastEl.className = `popup-toast-item pointer-events-auto transform translate-x-full opacity-0 transition-all duration-300 ease-out bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border ${borderColor} shadow-2xl rounded-3xl p-4 sm:p-5 flex flex-col gap-3 relative overflow-hidden group hover:scale-[1.01]`;
        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('data-auto-dismiss', '4500');

        toastEl.innerHTML = `
            <div class="flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-2xl ${iconBg} border flex items-center justify-center font-black text-lg shrink-0 shadow-sm">
                    ${iconHtml}
                </div>
                <div class="flex-1 min-w-0 pr-2">
                    <h4 class="text-xs sm:text-sm font-black text-slate-900 dark:text-white flex items-center gap-1.5">
                        <span>${title || defaultTitle}</span>
                        ${isSuccess ? '<span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>' : ''}
                    </h4>
                    <p class="text-xs text-slate-600 dark:text-slate-300 font-medium mt-0.5 leading-relaxed">
                        ${message}
                    </p>
                </div>
                <button type="button" onclick="closePopupToast(this.closest('.popup-toast-item'))" 
                        class="p-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition shrink-0"
                        title="Tutup Notifikasi">
                    ✕
                </button>
            </div>
            <div class="w-full bg-slate-100 dark:bg-slate-800 h-1 rounded-full overflow-hidden">
                <div class="popup-toast-progress bg-gradient-to-r ${progressGrad} h-full w-full rounded-full transition-all duration-[4500ms] ease-linear"></div>
            </div>
        `;

        container.appendChild(toastEl);
        openPopupToast(toastEl);
    };

    document.addEventListener('DOMContentLoaded', () => {
        const toasts = document.querySelectorAll('.popup-toast-item');
        toasts.forEach((t, idx) => {
            setTimeout(() => {
                openPopupToast(t);
            }, idx * 150);
        });
    });
</script>
