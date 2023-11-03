@props([
    'hasButton' => true,
    'buttonText' => '',
    'hasFooter' => true,
    'hasForm' => true,
    "modalId" => '',
    'hasHeader' => true,
    'title' => '',
    'on' => '',
    'onHide' => 'hideModal',
    'size' => 'md',
    'modalContentClass' => '',
    'closeFromServer' => false,
    'backDrop' => true,
    'functionCall' => ''
])

@if($hasButton)
<button type="button" {{ $attributes->merge(['class' => 'btn btn-secondary']) }}>{{ $buttonText }}</button>
@endif
<div  wire:ignore.self id="{{ $modalId }}" class="modal modal-blur fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true" @if($backDrop) data-bs-backdrop="static" data-bs-keyboard="false" @endif >
    <div class="modal-dialog modal-{{ $size }} modal-dialog-centered" role="document">
        <div class="modal-content">
            @if ($hasHeader)
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                @if($closeFromServer)
                    <button type="button" class="btn-close" wire:click="hideViewModal" aria-label="Close"></button>
                @else
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                @endif
            </div>
            @endif
            <div class="modal-body">
                {{ $slot }}
            </div>
           @if($hasFooter)
            <div class="modal-footer">
                <button class="btn btn-link link-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button wire:click.prevent="{{$functionCall}}" class="btn btn-primary ms-auto">
                    Submit
                </button>
            </div>
            @endif
        </div>
    </div>
</div>




@push('footer')
<script type="text/javascript">
    window.addEventListener('{{ $on }}', (event) => {
        $('#{{ $modalId }}').modal('show');
    } );
    window.addEventListener('{{ $onHide }}', (event) => {
          $('#{{ $modalId }}').modal('hide');
      } );
</script>
@endpush
