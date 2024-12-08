<x-app-layout>
    <div class="card" style="margin: 20px;">
        <div class="card-header">Créer Nouveau Service</div>
        <div class="card-body">
            <!-- Start of the form -->
            <form action="{{ url('services') }}" method="post" enctype="multipart/form-data">
                {!! csrf_field() !!}
                
                <div class="mb-3">
                    <label for="name" class="form-label">Nom</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="entrer nom de service">
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" placeholder="ajouter description au service" rows="4"></textarea>
                </div>

                <div class="mb-3">
                    <label for="department" class="form-label">Département</label>
                    <select name="department_id" id="department" class="form-control">
                        <option value="">Choisissez un département</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Save</button>
            </form>
            <!-- End of the form -->
        </div>
    </div>

</x-app-layout>