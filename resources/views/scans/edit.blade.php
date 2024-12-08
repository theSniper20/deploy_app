<x-app-layout>
    <div class="card" style="margin: 20px;">
        <div class="card-header">modification Image Medical</div>
        <div class="card-body">
            <!-- Start of the form -->
            <form action="{{ url('scans/' . $scan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Simulate PUT method -->
            <!-- Form fields go here -->
                
                <div class="mb-3">
                    <label for="produced_at" class="form-label" >Date production image </label>
                    <input id="produced_at" class="form-control" placeholder="Pick a date" name="produced_at"
                    value="{{ old('produced_at', $scan->produced_at ? $scan->produced_at->format('d/m/Y') : '') }}"
                    />
                </div>
                <div class="mb-3">
                    <label for="display_method" class="form-label">methode d'affichage</label>
                    <select name="display_method" id="display_method" class="form-control">
                        <option value="">Choisissez une methode d'affichage</option>
                        <option value="paper" {{ old('display_method', $scan->display_method) == 'paper' ? 'selected' : '' }}>Paper</option>
                        <option value="screen" {{ old('display_method', $scan->display_method) == 'screen' ? 'selected' : '' }}>Screen</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="service_id" class="form-label">Servise utilisé</label>
                    <select name="service_id" id="service_id" class="form-control">
                        <option value="">Choisissez un service</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" {{ $service->id == $scan->service_id ? 'selected' : '' }}>
                                {{$service->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="category_id" class="form-label">categorie d'image</label>
                    <select name="category_id" id="category_id" class="form-control">
                        <option value="">Choisissez categorie d'image </option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $category->id == $scan->category_id ? 'selected' : '' }}>
                                {{$category->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                <input 
                        class="form-control" 
                        name="image_name" 
                        type="file" 
                        id="image_name"
                    />
                    @if ($scan->image_name)
                        <p class="mt-2">Current Image: <a href="{{ asset('/storage/'.$scan->path_name) }}" target="_blank">View</a></p>
                    @endif
                </div>
                <button type="submit" class="btn btn-success">Save</button>
            </form>
            <!-- End of the form -->
        </div>
    </div>
    <!--
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const elem = document.getElementById('produced_at');
            const datepicker = new Datepicker(elem, {
                buttonClass: 'btn',
                format: 'mm/dd/yyyy',
            });
        });
    </script>
    -->
</x-app-layout>