@props(['class'])
<div wire:ignore.self class="modal fade {{ $class }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div style="display: flex;justify-content: end;">
                <b style="padding: 0 20px;margin: 2px;" type="button" class="modal-close-icon" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </b>
            </div>
            {{ $slot }}
        </div>
    </div>
</div>
