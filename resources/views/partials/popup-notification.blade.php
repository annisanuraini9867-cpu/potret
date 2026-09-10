{{-- Centered Pop-up Notification Modal Container (Tengah Layar) --}}
@php
    $hasSessionNotif = session('success') || session('error') || session('warning');
    $notifType = session('success') ? 'success' : (session('error') ? 'error' : (session('warning') ? 'warning' : null));
    $notifMessage = session('success') ?? session('error') ?? session('warning');
@endphp

<!-- Backdrop & Centered Notification Modal -->
<div id="popup-toast-container" class="contents">
<div id="centered-popup-overlay" 
     class="fixed inset-0 z-[9999] {{ $hasSessionNotif ? 'flex' : 'hidden' }} items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm transition-opacity duration-300 opacity-0 pointer-events-none"
     aria-modal="true" 
     role="dialog"
     onclick="handlePopupBackdropClick(event)">

    <div id="centered-popup-card" 
         class="popup-toast-item relative w-full max-w-sm sm:max-w-md bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl shadow-2xl p-6 sm:p-7 text-center flex flex-col items-center gap-4 transform transition-all duration-300 ease-out scale-90 opacity-0 pointer-events-auto overflow-hidden">
        
        <!-- Subtle Top Glow bar -->
        <div id="popup-glow-bar" class="absolute top-0 left-0 right-0 h-1.5 {{ $notifType === 'error' ? 'bg-gradient-to-r from-rose-500 to-pink-500' : ($notifType === 'warning' ? 'bg-gradient-to-r from-amber-500 to-yellow-400' : 'bg-gradient-to-r from-emerald-500 to-teal-400') }}"></div>

        <!-- Close Button Top-Right -->
        <button type="button" 
                onclick="closeCenteredPopup()" 
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition flex items-center justify-center text-xs font-bold"
                title="Tutup">
            ✕
        </button>

        <!-- Icon Badge Container -->
        <div id="popup-icon-container" class="mt-2">
            @if($notifType === 'error')
                <div class="w-16 h-16 rounded-3xl bg-rose-500/15 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-500/30 flex items-center justify-center font-black text-2xl shrink-0 shadow-lg shadow-rose-500/10 ring-8 ring-rose-500/5 animate-pulse">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            @elseif($notifType === 'warning')
                <div class="w-16 h-16 rounded-3xl bg-amber-500/15 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/30 flex items-center justify-center font-black text-2xl shrink-0 shadow-lg shadow-amber-500/10 ring-8 ring-amber-500/5">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            @else
                <div class="w-16 h-16 rounded-3xl bg-emerald-500/15 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 flex items-center justify-center font-black text-2xl shrink-0 shadow-lg shadow-emerald-500/10 ring-8 ring-emerald-500/5">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            @endif
        </div>

        <!-- Title & Message Text -->
        <div class="space-y-1.5 px-2">
            <h3 id="popup-title" class="text-base sm:text-lg font-black text-slate-900 dark:text-white">
                {{ $notifType === 'error' ? 'Terjadi Kesalahan' : ($notifType === 'warning' ? 'Perhatian' : 'Aksi Berhasil!') }}
            </h3>
            <p id="popup-message" class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 font-medium leading-relaxed">
                {{ $notifMessage }}
            </p>
        </div>

        <!-- Progress Auto-Dismiss Bar -->
        <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full overflow-hidden mt-1">
            <div id="popup-progress-bar" 
                 class="h-full w-full rounded-full transition-all duration-[4000ms] ease-linear {{ $notifType === 'error' ? 'bg-gradient-to-r from-rose-500 to-pink-500' : ($notifType === 'warning' ? 'bg-gradient-to-r from-amber-500 to-yellow-400' : 'bg-gradient-to-r from-emerald-500 to-teal-400') }}">
            </div>
        </div>

        <!-- Action Button (Tutup / Oke) -->
        <button type="button" 
                id="popup-action-btn"
                onclick="closeCenteredPopup()"
                class="w-full py-3 px-6 rounded-2xl font-extrabold text-xs sm:text-sm shadow-lg transition active:scale-95 text-white {{ $notifType === 'error' ? 'bg-rose-600 hover:bg-rose-700 shadow-rose-600/25' : ($notifType === 'warning' ? 'bg-amber-500 hover:bg-amber-600 shadow-amber-500/25' : 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-600/25') }}">
            Oke, Mengerti
        </button>
    </div>
</div>
</div>

<!-- Centered Confirmation Modal (Tengah Layar) -->
<div id="centered-confirm-overlay" 
     class="fixed inset-0 z-[10000] hidden items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm transition-opacity duration-300 opacity-0 pointer-events-none"
     aria-modal="true" 
     role="dialog"
     onclick="handleConfirmBackdropClick(event)">

    <div id="centered-confirm-card" 
         class="relative w-full max-w-sm sm:max-w-md bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl shadow-2xl p-6 sm:p-7 text-center flex flex-col items-center gap-4 transform transition-all duration-300 ease-out scale-90 opacity-0 pointer-events-auto overflow-hidden">
        
        <!-- Subtle Top Glow bar -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-rose-500 via-amber-500 to-rose-600"></div>

        <!-- Close Button Top-Right -->
        <button type="button" 
                onclick="closeConfirmModal()" 
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition flex items-center justify-center text-xs font-bold"
                title="Batal">
            ✕
        </button>

        <!-- Danger/Warning Icon Badge -->
        <div class="mt-2">
            <div class="w-16 h-16 rounded-3xl bg-rose-500/15 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-500/30 flex items-center justify-center font-black text-2xl shrink-0 shadow-lg shadow-rose-500/15 ring-8 ring-rose-500/5">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </div>
        </div>

        <!-- Title & Message -->
        <div class="space-y-1.5 px-2">
            <h3 id="confirm-modal-title" class="text-base sm:text-lg font-black text-slate-900 dark:text-white">
                Konfirmasi Tindakan
            </h3>
            <p id="confirm-modal-message" class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 font-medium leading-relaxed">
                Apakah Anda yakin ingin melakukan tindakan ini?
            </p>
            <p class="text-[11px] text-rose-500 font-semibold flex items-center justify-center gap-1 mt-1">
                <span>⚠</span>
                <span>Tindakan ini permanen dan tidak dapat dibatalkan</span>
            </p>
        </div>

        <!-- Action Buttons (Batal & Ya, Lanjutkan) -->
        <div class="w-full flex items-center gap-3 pt-2">
            <button type="button" 
                    id="confirm-modal-cancel-btn"
                    onclick="closeConfirmModal()"
                    class="flex-1 py-3 px-5 rounded-2xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs sm:text-sm transition active:scale-95">
                Batal
            </button>
            <button type="button" 
                    id="confirm-modal-submit-btn"
                    class="flex-1 py-3 px-5 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs sm:text-sm shadow-lg shadow-rose-600/30 transition active:scale-95 flex items-center justify-center gap-1.5">
                <span>🗑</span>
                <span id="confirm-modal-submit-text">Ya, Hapus</span>
            </button>
        </div>
    </div>
</div>

<script>
(function() {
    let popupTimer = null;
    let pendingConfirmCallback = null;

    // --- CENTERING POPUP NOTIFICATION ---
    function openCenteredPopup() {
        const overlay = document.getElementById('centered-popup-overlay');
        const card = document.getElementById('centered-popup-card');
        const progress = document.getElementById('popup-progress-bar');
        if (!overlay || !card) return;

        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        
        requestAnimationFrame(() => {
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            overlay.classList.add('opacity-100', 'pointer-events-auto');

            card.classList.remove('scale-90', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');

            if (progress) {
                progress.style.width = '100%';
                setTimeout(() => {
                    progress.style.width = '0%';
                }, 50);
            }
        });

        // Auto dismiss after 4 seconds
        clearTimeout(popupTimer);
        popupTimer = setTimeout(() => {
            closeCenteredPopup();
        }, 4000);

        // Hover pause
        card.addEventListener('mouseenter', () => clearTimeout(popupTimer));
        card.addEventListener('mouseleave', () => {
            clearTimeout(popupTimer);
            popupTimer = setTimeout(() => closeCenteredPopup(), 1500);
        });
    }

    window.closeCenteredPopup = function() {
        const overlay = document.getElementById('centered-popup-overlay');
        const card = document.getElementById('centered-popup-card');
        if (!overlay || !card) return;

        clearTimeout(popupTimer);
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-90', 'opacity-0');

        overlay.classList.remove('opacity-100');
        overlay.classList.add('opacity-0');

        setTimeout(() => {
            overlay.classList.remove('flex', 'pointer-events-auto');
            overlay.classList.add('hidden', 'pointer-events-none');
        }, 300);
    };

    window.handlePopupBackdropClick = function(event) {
        if (event.target === document.getElementById('centered-popup-overlay')) {
            closeCenteredPopup();
        }
    };

    // Public method to trigger centered popup programmatically
    window.showPopupNotification = function(message, type = 'success', title = null) {
        const overlay = document.getElementById('centered-popup-overlay');
        const card = document.getElementById('centered-popup-card');
        const titleEl = document.getElementById('popup-title');
        const msgEl = document.getElementById('popup-message');
        const iconCont = document.getElementById('popup-icon-container');
        const glowBar = document.getElementById('popup-glow-bar');
        const btn = document.getElementById('popup-action-btn');
        const progress = document.getElementById('popup-progress-bar');
        if (!overlay || !card) return;

        const isSuccess = type === 'success';
        const isError = type === 'error';
        const isWarning = type === 'warning';

        if (titleEl) {
            titleEl.textContent = title || (isSuccess ? 'Aksi Berhasil!' : (isError ? 'Terjadi Kesalahan' : 'Perhatian'));
        }
        if (msgEl) {
            msgEl.textContent = message;
        }

        if (glowBar) {
            glowBar.className = `absolute top-0 left-0 right-0 h-1.5 ${isError ? 'bg-gradient-to-r from-rose-500 to-pink-500' : (isWarning ? 'bg-gradient-to-r from-amber-500 to-yellow-400' : 'bg-gradient-to-r from-emerald-500 to-teal-400')}`;
        }

        if (iconCont) {
            if (isError) {
                iconCont.innerHTML = `
                    <div class="w-16 h-16 rounded-3xl bg-rose-500/15 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-500/30 flex items-center justify-center font-black text-2xl shrink-0 shadow-lg shadow-rose-500/10 ring-8 ring-rose-500/5 animate-pulse">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>`;
            } else if (isWarning) {
                iconCont.innerHTML = `
                    <div class="w-16 h-16 rounded-3xl bg-amber-500/15 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/30 flex items-center justify-center font-black text-2xl shrink-0 shadow-lg shadow-amber-500/10 ring-8 ring-amber-500/5">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>`;
            } else {
                iconCont.innerHTML = `
                    <div class="w-16 h-16 rounded-3xl bg-emerald-500/15 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 flex items-center justify-center font-black text-2xl shrink-0 shadow-lg shadow-emerald-500/10 ring-8 ring-emerald-500/5">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>`;
            }
        }

        if (btn) {
            btn.className = `w-full py-3 px-6 rounded-2xl font-extrabold text-xs sm:text-sm shadow-lg transition active:scale-95 text-white ${isError ? 'bg-rose-600 hover:bg-rose-700 shadow-rose-600/25' : (isWarning ? 'bg-amber-500 hover:bg-amber-600 shadow-amber-500/25' : 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-600/25')}`;
        }

        if (progress) {
            progress.className = `h-full w-full rounded-full transition-all duration-[4000ms] ease-linear ${isError ? 'bg-gradient-to-r from-rose-500 to-pink-500' : (isWarning ? 'bg-gradient-to-r from-amber-500 to-yellow-400' : 'bg-gradient-to-r from-emerald-500 to-teal-400')}`;
            progress.style.width = '100%';
        }

        openCenteredPopup();
    };

    // Backwards compatibility alias
    window.showToast = function(msg, type = 'success', title = null) {
        window.showPopupNotification(msg, type, title);
    };

    // --- CENTERED CONFIRMATION MODAL ---
    window.openConfirmModal = function(options) {
        const overlay = document.getElementById('centered-confirm-overlay');
        const card = document.getElementById('centered-confirm-card');
        const titleEl = document.getElementById('confirm-modal-title');
        const msgEl = document.getElementById('confirm-modal-message');
        const submitBtn = document.getElementById('confirm-modal-submit-btn');
        const submitText = document.getElementById('confirm-modal-submit-text');
        if (!overlay || !card) return;

        if (titleEl && options.title) titleEl.textContent = options.title;
        if (msgEl && options.message) msgEl.textContent = options.message;
        if (submitText && options.confirmText) submitText.textContent = options.confirmText;

        pendingConfirmCallback = options.onConfirm || null;

        overlay.classList.remove('hidden');
        overlay.classList.add('flex');

        requestAnimationFrame(() => {
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            overlay.classList.add('opacity-100', 'pointer-events-auto');

            card.classList.remove('scale-90', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        });
    };

    window.closeConfirmModal = function() {
        const overlay = document.getElementById('centered-confirm-overlay');
        const card = document.getElementById('centered-confirm-card');
        if (!overlay || !card) return;

        pendingConfirmCallback = null;

        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-90', 'opacity-0');

        overlay.classList.remove('opacity-100');
        overlay.classList.add('opacity-0');

        setTimeout(() => {
            overlay.classList.remove('flex', 'pointer-events-auto');
            overlay.classList.add('hidden', 'pointer-events-none');
        }, 300);
    };

    window.handleConfirmBackdropClick = function(event) {
        if (event.target === document.getElementById('centered-confirm-overlay')) {
            closeConfirmModal();
        }
    };

    // Setup submit action on confirmation modal
    document.addEventListener('DOMContentLoaded', () => {
        const confirmSubmitBtn = document.getElementById('confirm-modal-submit-btn');
        if (confirmSubmitBtn) {
            confirmSubmitBtn.addEventListener('click', () => {
                if (typeof pendingConfirmCallback === 'function') {
                    const cb = pendingConfirmCallback;
                    pendingConfirmCallback = null;
                    closeConfirmModal();
                    cb();
                } else {
                    closeConfirmModal();
                }
            });
        }

        // Auto trigger session notification if present on page load
        @if($hasSessionNotif)
            openCenteredPopup();
        @endif
    });

    // Global listener: intercept any form submission with data-confirm
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (!form || !form.getAttribute) return;
        const confirmMsg = form.getAttribute('data-confirm');
        if (confirmMsg && !form.dataset.confirmed) {
            e.preventDefault();
            openConfirmModal({
                title: form.getAttribute('data-confirm-title') || 'Konfirmasi Tindakan',
                message: confirmMsg,
                confirmText: form.getAttribute('data-confirm-btn') || 'Ya, Hapus',
                onConfirm: function() {
                    form.dataset.confirmed = 'true';
                    form.submit();
                }
            });
        }
    }, true);

    // Global listener: intercept clicks on links/buttons with data-confirm-click
    document.addEventListener('click', function(e) {
        const trigger = e.target.closest('[data-confirm-click]');
        if (trigger) {
            e.preventDefault();
            const confirmMsg = trigger.getAttribute('data-confirm-click');
            openConfirmModal({
                title: trigger.getAttribute('data-confirm-title') || 'Konfirmasi Tindakan',
                message: confirmMsg,
                confirmText: trigger.getAttribute('data-confirm-btn') || 'Ya, Lanjutkan',
                onConfirm: function() {
                    if (trigger.tagName === 'A') {
                        window.location.href = trigger.href;
                    } else if (trigger.onclick) {
                        trigger.onclick();
                    }
                }
            });
        }
    });

    // Keyboard navigation: Escape closes open modals
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeCenteredPopup();
            closeConfirmModal();
        }
    });
})();
</script>
