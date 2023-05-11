<div>
    <div wire:ignore class="w-full" >
        <select class="select2 w-full" data-placeholder="Select your option" {{ $attributes }}>
            @if(!isset($attributes['multiple']))
                <option></option>
            @endif
            @foreach($options as $key=> $value)
            <option value="{{$key}}">{{ $value}}</option>
            @endforeach
        </select>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener("livewire:load",()=>{
            let element = $('#{{ $attributes['id'] }}')
            function initSelect(){
                element.select2({
                    placeholder: "Select option",
                    allowClear: !element.attr('required')
                })
            }
            initSelect()
            Livewire.hook('message.processed',(message,component)=>{
                initSelect()
            })
            element.on('change',function(e){
                let data = $(this).select2("val");
                if(data === ""){
                    data = null
                }
                @this.set('{{$attributes['wire:model']}}',data)
            })
        });
    </script>
@endpush