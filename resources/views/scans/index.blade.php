<x-app-layout>
    <div class="container" x-data="scanHandler({{ $scan_with_pages->toJson() }}, {{ $categories->toJson() }})">
        <div class="d-flex p-2 gap-2">
            <div>
                <label>search by service</label>
                <select name="servs_id" id="servs_id" multiple multiselect-hide-x="true" x-model="servs"
                    multiselect-search="true" multiselect-select-all="true" multiselect-max-items="3">
                    @foreach ($all_services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach

                </select>
            </div>
            <div>
                <label>search by category</label>
                <select name="categs_id" id="categs_id" multiple x-model="ctegs" multiselect-hide-x="true"
                    multiselect-search="true" multiselect-select-all="true" multiselect-max-items="3"
                    >
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="input-group" id="searchByReference">
                <div class="form-outline" data-mdb-input-init>
                    <input type="search" id="form1" class="form-control" x-model="scan_ref" />
                    <label class="form-label" for="form1">reference</label>
                </div>
                <button type="button" class="btn btn-primary" data-mdb-ripple-init @click="imagesearch()">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-12" style="padding:20px;">
                <div class="d-inline-flex p-2 gap-2">
                    <a href="{{ url('/scans/create') }}" class="btn btn-success btn-sm"
                        title="Ajout Nouveau Image" x-bind:hidden="categories.length === 0">
                        <i class="fa fa-plus" aria-hidden="true"></i> Ajout Nouveau image
                    </a>
                    <a type="button" class="btn btn-success btn-sm" title="Nouveaus categories"
                        @click="addCateg()">
                        <i class="fa fa-plus" aria-hidden="true"></i> config categories
                    </a>
                </div>
            </div>
            <div class="col-lg-6 col-md-12" style="padding:20px;">
            <div class="me-2">
                            <p x-text="'nombres totale images: ' + total" :style="{'font-weight': 'bold'}"></p>
                            <div>
                                <p x-show="images.length > 0" x-text="'Nombre images sur cette page: ' + images.length"
                                :style="{'font-weight': 'bold'}">
                                </p>
                                <p x-show="images.length === 0"
                                 x-text="'Aucune image trouvé sur cette page'" :style="{'font-weight': 'bold'}">
                                </p>
                            </div>
                        </div>
            </div>
        </div>
        <div class="row">
            
            <div class="col-lg-9 col-md-12" style="padding:20px;">
           
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        Liste des images medicals
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                            <li class="page-item" :class="{ 'disabled': prev_buton_disabled }" >
                            
                                <a class="page-link" href="#"
                                    @click="prevImage(prev_page_url, next_page_url)">Previous</a>
                            </li>
                            <li class="page-item" :class="{ 'disabled': next_buton_disabled }">
                                <a class="page-link" href="#"
                                    @click="nextImage(prev_page_url, next_page_url)">Next</a>
                            </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="card-body">
                        
                        <template x-if="images.length > 0">
                            <div class="d-flex flex-wrap gap-6">

                                <template x-for="(image, index) in images" :key="`${image.id}-${index}`">
                                    <div class="card" style="width: 21rem;">
                                        <img class="card-img-top" :src="'/storage/' + image.path_name"
                                            alt="Card image cap" style="height: 60%">

                                        <div id="contentdico"></div>
                                        <div class="card-header" x-text="image.image_name"></div>

                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item" x-text="'reference: ' + image.scan_reference" :style="{'font-weight': 'bold'}">
                                            </li>
                                            <li class="list-group-item" x-text="'service: ' + image.service.name"
                                            :style="{'font-weight': 'bold'}">

                                            </li>
                                            <li class="list-group-item" x-text="'Category: ' + image.category.name"
                                            :style="{'font-weight': 'bold'}">
                                            >
                                            </li>
                                        </ul>
                                        <div class="card-footer text-muted">
                                            <a type="button" class="btn btn-outline-danger"
                                                @click="deleteImage(image.id)"> delete</a>
                                            <a type="button" class="btn btn-outline-primary"
                                                :href="'/scans/' + image.id + '/edit'">update</a>
                                        </div>
                                    </div>

                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div> <!-- first column -->
            <!--  controle categories-->
            <!-- Sidebar -->
            <div x-show="showSidebar" class="col-lg-3 col-md-12" style="padding:20px;">
                <div class="card">
                    <div class="card-header">config categories images medicales</div>
                    <div class="card-body">
                        <template x-if="categories.length === 0">
                            <p class="text-danger">aucun categorie configuré!!</p>
                        </template>
                        <template x-if="categories.length > 0">
                            <div>
                                <h2 x-text="categories.length + ' categories trouvés'"></h2>
                                <br></br>
                                <ul>
                                    <template x-for="(categ, index) in categories" :key="`${categ.id}-${index}`">
                                        <li x-ref="'categitem' + index" class="categ-container" x-text="categ.name">
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </template>
                        <br>
                        <div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="categ"
                                    aria-describedby="categhelp" x-model="newcateg">
                                <div id="categhelp" class="form-text">saisir liste separés par virgule : categ1,categ2
                                </div>
                            </div>

                            <div class="d-flex d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-outline-primary"
                                    @click="categService('add', newcateg)"
                                    x-bind:disabled="newcateg.trim() === ''"> add</button>
                                <button type="button" class="btn btn-outline-danger"
                                    @click="categService('del' , newcateg)"
                                    x-bind:disabled="newcateg.trim() === ''"> delete</button>
                                <button type="button" class="btn btn-outline-primary"
                                    @click="closeModal()">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!--end side bar column2-->
    </div> <!-- end row -->
    </div> <!--end container-->
</x-app-layout>
