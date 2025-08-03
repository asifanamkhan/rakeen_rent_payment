@props(['eventName','modalTitle'])

<div wire:ignore.self class="modal fade" id="{{ $eventName }}" tabindex="-1" role="dialog"
    aria-labelledby="{{ $eventName }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="justify-content: space-between">
                    <h4 class="text-center" style="color: #3C50E0">{{ $modalTitle }}</h4>
                    <b type="button" class="modal-close-icon" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </b>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
