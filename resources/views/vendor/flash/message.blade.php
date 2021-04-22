
@foreach (session('flash_notification', collect())->toArray() as $message)
    @if ($message['overlay'])
        @include('flash::modal', [
            'modalClass' => 'flash-modal',
            'title'      => $message['title'],
            'body'       => $message['message']
        ])
    @else
        <script>
        $.notify({
            message: '{!! $message['message'] !!}'
        },{
            type: '{{ $message['level'] }}',
            offsetx: 1000,
            delay: 5000,
            z_index: 1031,
            timer: 1000,
            allow_dismiss: true,
            newest_on_top: true,
            placement: {
                from: "top",
                align: "center"
            },
            animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
            }
        });
        </script>
    @endif
@endforeach

{{ session()->forget('flash_notification') }}
