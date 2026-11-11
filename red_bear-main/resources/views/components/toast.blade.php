@if (session()->has('toast'))
    @php
        $toast = session('toast');
        $type = $toast['type'] ?? 'success';
        $message = $toast['message'] ?? '';

        $styles = match ($type) {
            'error' => 'border-red-500/30 bg-red-500/10 text-red-600',
            'warning' => 'border-amber-500/30 bg-amber-500/10 text-amber-600',
            default => 'border-green-500/30 bg-green-500/10 text-green-600',
        };

        $icons = [
            'error' => '✕',
            'warning' => '!',
            'success' => '✓',
        ];
    @endphp

    <div
        id="toast"
        role="status"
        aria-live="polite"
        class="fixed right-4 top-4 z-[100] flex max-w-sm items-center gap-3
               rounded-xl border px-4 py-3 shadow-lg backdrop-blur-md
               transition-all duration-300 {{ $styles }}"
    >

        <p class="flex-1 text-sm font-medium text-gray-900 dark:text-white">
            {{ $message }}
        </p>

        <button
            type="button"
            onclick="document.getElementById('toast')?.remove()"
            class="ml-2 opacity-60 transition hover:opacity-100"
            aria-label="Закрыть уведомление"
        >
            ✕
        </button>
    </div>

    <script>
        (() => {
            const toast = document.getElementById('toast');

            if (!toast) return;

            setTimeout(() => {
                toast.classList.add('translate-x-4', 'opacity-0');

                setTimeout(() => toast.remove(), 300);
            }, 4000);
        })();
    </script>
@endif
