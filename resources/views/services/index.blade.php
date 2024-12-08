<x-app-layout>
    <head>    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"></head>
    
    <div class="container"  x-data="serviceHandler({{$services->toJson()}})">
        <div class="row">
            <div class="col-lg-9 col-md-12" style="padding:20px;">
                <div class="card">
                    <div class="card-header">Liste des services</div>
                    <div class="card-body">
                        <a href="{{ url('/services/create') }}" class="btn btn-success btn-sm" title="Ajout Nouveau Service">
                            <i class="fa fa-plus" aria-hidden="true"></i> Ajout Nouveau Service 
                        </a>
                        <br/>
                        <br/>
                        <template x-if="services.length === 0">
                            <p>Aucun service trouvé!!</p>
                        </template>
                        <template x-if="services.length > 0">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nom Service</th>
                                            <th>Description Service</th> 
                                            <th>Nom Departement</th>
                                            <th>Nombre Images</th> 
                                            <th></th> 
                                    </thead>
                                    </thead>
                                    <tbody>
                                        <template x-for="(service, index) in services" :key="service.id">
                                            <tr>
                                                <td x-text="index + 1"></td>
                                                 <!-- Editable name Field -->
                                                <td>
                                                    <input 
                                                    type="text" 
                                                    class="form-control" 
                                                    x-model="service.name" 
                                                    >
                                                </td>
                                                <!-- Editable Description Field -->
                                                <td>
                                                    <input 
                                                    type="text" 
                                                    class="form-control" 
                                                    x-model="service.description" 
                                                    >
                                                </td>
                                                <!-- Editable department Field -->
                                                <!-- 
                                                <td>
                                                    <input 
                                                    type="text" 
                                                    class="form-control" 
                                                    x-model="service.department.name" 
                                                    >
                                                </td>
                                                -->
                                                <td>
                                                <select class="form-control" x-model="service.department.name">
                                                    <template x-for="department in service.all_departments" :key="department.id">
                                                    <option :value="department.name" :selected="department.name === service.department.name" x-text="department.name"></option>
                                                    </template>
                                                </select>
                                                <td x-text="service.scans.length"></td>
                                                <td> 
                                                    <button type="button" class="btn btn-outline-primary"
                                                    @click="updateService(service)"
                                                    >
                                                        update
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger"
                                                    @click="deleteService(service.id)"
                                                    >
                                                        delete
                                                    </button>
                                                    <template x-if="service.scans.length > 0">
                                                        <button type="button" class="btn btn-outline-info"
                                                        @click="showScans(service.name , service.scans)"
                                                        :disabled="showSidebar"
                                                        :id=`show-services${service.id}` 
                                                        >
                                                            images assciées
                                                        </button>
                                                    </template>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            <!-- Sidebar -->
            <div x-show="showSidebar" class="col-lg-3 col-md-12" style="padding:20px;" >
                <div class="card">
                    <div class="card-header">Images Associés</div>
                    <div class="card-body">
                        <h3 x-text="'Service: ' + serviceName"></h3>
                        <template x-if="scans && scans.length > 0">
                            <template x-for="(scan, index) in scans" :key="index">
                                <div>
                                    <h5 x-text="'infos image' + (index + 1) + ':'"></h5>
                                    <ul>  
                                        <li x-text="'name: ' + scan.image_name || 'Unknown scan.image_name'"></li> 
                                        <li x-text="'ref: ' + scan.scan_reference || 'Unknown rescan.scan_reference'"></li>
                                        <li x-text="'categ: ' + scan.category.name || 'Unknown catscan.category.name'"></li>
                                    </ul>
                                </div>
                            </template> 
                        </template>
                        <template x-else>
                            <p>Aucune image trouvé.</p>
                        </template>
                        <button type="button" class="btn btn-primary" @click="closeModal()">Close</button>
                    </div>
                </div>
            </div>
        </div>
        </div> <!--row-->
    </div> <!-- container -->
    <script>
        function serviceHandler(initialServices = []) {
            return {
                showSidebar: false,
                services: initialServices , // Array to hold department data,
                serviceName: '',
                scans: [],
                showScans(name, scans = []) {
                    console.log(scans);
                    this.serviceName = name;
                    this.scans = []; // Clear previous services;
                    this.scans = Array.isArray(scans) ? scans : []; // Ensure services is an array
                    console.log("########here");
                    console.log(this.scans);
                    this.showSidebar = true;
                },
                closeModal() {
                    this.showSidebar = false;
                    this.scans = [];
                    this.serviceName = '';
                },
                //departments: initialDepartments ,
                deleteService(service_id) {
                    axios.delete(`/services/${service_id}`)
                    .then(response => {
                        Toastify({
                        text: response.data.success,
                        duration: 3000, // Notification duration in milliseconds
                        gravity: "top", // "top" or "bottom"
                        position: "right", // "left", "center", or "right"
                        backgroundColor: "#4caf50", // Success green
                        }).showToast();
                        // Optionally refresh the list or update the UI
                        // Remove the deleted department from the local array
                        this.services = this.services.filter(service => service.id !== service_id);
                        })
                    .catch(error => {
                        const errorMessage = error.response.data.error || "An error occurred on the server.";
                        Toastify({
                        text: errorMessage,
                        duration: 3000, // Notification duration in milliseconds
                        gravity: "top", // "top" or "bottom"
                        position: "right", // "left", "center", or "right"
                        backgroundColor: "#f44336", // red 
                        }).showToast();
                    });
                },
                // updateService details on the backend
                updateService(service) {
                    const data_to_update = {
                        department_name : service.department.name,
                        name: service.name,
                        description: service.description,
                    };
                    axios.put(`/services/${service.id}`,data_to_update)
                    .then(response => {
                        Toastify({
                            text: response.data.success,
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#4caf50",
                        }).showToast();
                    })
                    .catch(error => {
                        const errorMessage = error.response.data.error || "An error occurred on the server.";
                        Toastify({
                            text: errorMessage,
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#f44336",
                        }).showToast();
                        this.loadDepartments();
                    });
                }
            }
        }
    </script>
</x-app-layout>



