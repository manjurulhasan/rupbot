@once

@push('header')
<link rel="stylesheet" href="{{ asset('assets/notify/css/simple-notify.min.css') }}">
@endpush

@push('footer')
<script src="{{ asset('assets/notify/js/simple-notify.min.js') }}"></script>
<script>
    window.addEventListener('notify', function(event) {
        new Notify({
            status: event.detail[0].type,
            title: event.detail[0].title,
            text: event.detail[0].message,
            effect:'slide',
            showIcon: true,
            position:'right top',
            autoclose: true,
            autotimeout: 5000
        });
    });
</script>
@endpush
@endonce
