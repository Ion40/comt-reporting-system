
<x-app-layout>
    
    <livewire:iframes-listener/>

    @push('scripts')
        <script>
            const csrf_token = "{{ csrf_token() }}";
            const deleteIframe = "{{ route('iframes.deleteIframe', ':url_path') }}";
        </script>
        <script src="{{asset("/js/custom/iframes.js")}}"></script>
    @endpush
</x-app-layout>
