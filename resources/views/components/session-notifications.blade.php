@if(session('success') || session('error') || session('warning') || session('info'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        if (window.showSuccess) {
            window.showSuccess('{{ addslashes(session('success')) }}');
        }
    @endif

    @if(session('error'))
        if (window.showError) {
            window.showError('{{ addslashes(session('error')) }}');
        }
    @endif

    @if(session('warning'))
        if (window.showWarning) {
            window.showWarning('{{ addslashes(session('warning')) }}');
        }
    @endif

    @if(session('info'))
        if (window.showInfo) {
            window.showInfo('{{ addslashes(session('info')) }}');
        }
    @endif
});
</script>
@endif

