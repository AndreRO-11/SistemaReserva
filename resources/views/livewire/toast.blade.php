<div>

    <div class="toast align-items-center border-0 top-0 end-0 text-bg-{{ $color }}" role="alert"
        aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                {{ $message }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>

    {{-- <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('showToast', (type, message, color) => {
                Livewire.emit('showToast', type, message, color);
            });
        });
    </script> --}}

</div>
