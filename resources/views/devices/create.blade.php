<x-app-layout>
    <div class="card" style="margin: 20px;">
        <div class="card-header">Créer Nouveau Matériel</div>
        <div class="card-body">
            <!-- Start of the form -->
            <form action="{{ url('devices') }}" method="post" enctype="multipart/form-data">
                {!! csrf_field() !!}
                
                <div class="mb-3">
                    <label for="name" class="form-label">Nom</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="entrer nom de matériel">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" placeholder="ajouter description au matériel" rows="4"></textarea>
                </div>

                <div class="mb-3">
                    <label for="service" class="form-label">Service</label>
                    <select name="service_id" id="service" class="form-control">
                        <option value="">Choisissez un Service</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Save</button>
            </form>
            <!-- End of the form -->
        </div>
    </div>

</x-app-layout>