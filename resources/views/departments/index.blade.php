<x-app-layout>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    </head>
    <div class="container" x-data="departmentHandler({{$departments->toJson()}})">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-9 col-md-12" style="padding:20px;">
                <div class="card">
                    <div class="card-header">Liste des départements</div>
                    <div class="card-body">
                        <a href="{{ url('/departments/create') }}" class="btn btn-success btn-sm" title="Ajout nouveau département">
                            <i class="fa fa-plus" aria-hidden="true"></i> Ajout Nouveau Departement
                        </a>
                        <br/>
                        <br/>
                        <template x-if="departments.length === 0">
                            <p>Aucun département trouvé!!</p>
                        </template>
                        <template x-if="departments.length > 0">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nom Departement</th>
                                        <th>Description Departement</th>
                                        <th>Nombre Services</th>
                                        <!--<th>Services Associés</th> -->
                                        <th></th> 
                                    </tr>
                                </thead>
                                <tbody>
                                 <template x-for="(department, index) in departments" :key="department.id">
                                    <tr>
                                        <td x-text="index + 1"></td>
                                        <!-- Editable Name Field -->
                                        <td>
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                x-model="department.name" 
                                            >
                                        </td>
                                        <!-- Editable Description Field -->
                                        <td>
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                x-model="department.description" 
                                            >
                                        </td>
                                        <td x-text="department.services.length"></td>
                                        <td > 
                                            <button type="button" class="btn btn-outline-primary"
                                            @click="updateDepartment(department)"
                                            >
                                                Update
                                            </button>
                                            <button type="button" class="btn btn-outline-danger"
                                             @click="deleteDepartment(department.id)"
                                            >Delete</button>
                                            <template x-if="department.services.length > 0">
                                                <button class="btn btn-outline-info" 
                                                @click="showServices(department.name ,  department.services)"
                                                :disabled="showSidebar"
                                                :id=`show-services${department.id}` 
                                                >
                                                Services
                                                </button>
                                            </template>
                                        </td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </template>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div x-show="showSidebar" class="col-lg-3 col-md-12" style="padding:20px;" >
                <div class="card">
                    <div class="card-header">Services Associés</div>
                    <div class="card-body">
                        <h3 x-text="'Departement: ' + departmentName"></h3>
                            <!--
                            <template x-for="service in services || []" :key="service.id">
                                <li x-text="getServiceName(service)"></li>
                            </template>
                            -->
                            <div>
                                <template x-if="services && services.length > 0">
                                    <template x-for="(service, index) in services" :key="index">
                                        <p x-text="service.name || 'Unknown Service'"></p>
                                    </template>
                                </template>
                                <template x-else>
                                    <p>Aucun service associé.</p>
                                </template>
                            </div>
                        <button type="button" class="btn btn-primary" @click="closeModal()">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function departmentHandler(initialDepartments = []) {
            return {
                showSidebar: false,
                departmentName: '',
                services: [],
                departments: initialDepartments , // Array to hold department data,
                // Method to load departments (you may want to call this on page load)
                loadDepartments() {
                    // Fetch departments from the server (you might want to implement this)
                    axios.get('/all-departments').then(response => {
                        console.log(response.data);
                        this.departments = response.data; // Assuming response.data contains an array of departments
                    });
                },
            showServices(name, services = []) {
                    this.departmentName = name;
                    this.services = []; // Clear previous services;
                    this.services = Array.isArray(services) ? services : []; // Ensure services is an array
                    this.showSidebar = true;
                },

            closeModal() {
                    this.showSidebar = false;
                    this.services = [];
                    this.departmentName = '';
                },
            deleteDepartment(department_id) {
                axios.delete(`/departments/${department_id}`)
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
                    this.departments = this.departments.filter(department => department.id !== department_id);
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
            // Update department details on the backend
            updateDepartment(department) {
                const data_to_update = {
                    name: department.name,
                    description: department.description,
                };
                axios.put(`/departments/${department.id}`,data_to_update)
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
