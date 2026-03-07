
<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header justify-content-between d-sm-flex gap-2">
                    <a href="{{ route("iframes.create")  }}" class="btn btn-primary mb-sm-0 mb-2">
                        <i class="ti ti-circle-plus fs-20 me-2"></i> Agregar nuevo IFrame
                    </a>

                    <form class="row g-2 align-items-center">
                        <div class="col-auto">
                            <div class="d-flex">
                                <label class="d-flex align-items-center fw-semibold">Módulo </label>
                                <select class="form-select d-inline-block ms-2">
                                    <option>All Projects(6)</option>
                                    <option>Complated</option>
                                    <option>Progress</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex">
                                <label class="d-flex align-items-center fw-semibold">Ordenar</label>
                                <select class="form-select d-inline-block ms-2">
                                    <option>Date</option>
                                    <option>Name</option>
                                    <option>End date</option>
                                    <option>Start Date</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-auto">
                            <!-- Search Input -->
                            <div class="d-flex align-items-start flex-wrap">
                                <label for="membersearch-input" class="visually-hidden">Search</label>
                                <input type="search" class="form-control border-light bg-light bg-opacity-50" id="membersearch-input" placeholder="Search...">
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <livewire:iframes-listener/>

    @push('scripts')
        <script>
            const csrf_token = "{{ csrf_token() }}";
            const deleteIframe = "{{ route('iframes.deleteIframe', ':url_path') }}";
        </script>
        <script src="{{asset("/js/custom/iframes.js")}}"></script>
    @endpush
</x-app-layout>
