<x-app-layout>
    <div class="card" style="margin: 20px;">
        <div class="card-header">Ajout Nouveau Image Medical</div>
        <div class="card-body">
            <!-- Start of the form -->
            <form action="{{ url('scans') }}" method="post" enctype="multipart/form-data">
                {!! csrf_field() !!}

                <div class="mb-3">
                    <label for="scan_reference" class="form-label">reference de scan </label>
                    <input id="scan_reference" class="form-control" placeholder="saisir identifiant unique de scan" name="scan_reference"/>
                </div>
                
                <div class="mb-3">
                    <label for="produced_at" class="form-label">Date production image </label>
                    <input id="produced_at" class="form-control" placeholder="Pick a date" name="produced_at"/>
                </div>
                <div class="mb-3">
                    <label for="display_method" class="form-label">methode d'affichage</label>
                    <select name="display_method" id="display_method" class="form-control">
                        <option value="">Choisissez une methode d'affichage</option>
                        <option value="paper">paper</option>
                        <option value="screen">screen</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="service_id" class="form-label">Service utilisé</label>
                    <select name="service_id" id="service_id" class="form-control">
                        <option value="">Choisissez un service</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}">{{$service->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="category_id" class="form-label">categorie d'image</label>
                    <select name="category_id" id="category_id" class="form-control">
                        <option value="">Choisissez categorie d'image </option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <input class="form-control" name="image_name" type="file" id="image_name">
                </div>
                <button type="submit" class="btn btn-success">Save</button>
            </form>
            <!-- End of the form -->
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const elem = document.getElementById('produced_at');
            const datepicker = new Datepicker(elem, {
                buttonClass: 'btn',
                format: 'mm/dd/yyyy',
            });
        });
    </script>
</x-app-layout>
