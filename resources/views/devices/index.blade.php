<x-app-layout>
    <head>    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"></head>
    
    <div class="container"  x-data="deviceHandler({{$devices->toJson()}}, {{$services->toJson()}})">
        <div class="row">
            <div class="col-12" style="padding:20px;">
                <div class="card">
                    <div class="card-header">Liste des matériels</div>
                    <div class="card-body">
                        <a href="{{ url('/devices/create') }}" class="btn btn-success btn-sm" title="Ajout Nouveau Materiel">
                            <i class="fa fa-plus" aria-hidden="true"></i> Ajout Nouveau Materiel
                        </a>
                        <br/>
                        <br/>
                        <template x-if="devices.length === 0">
                            <p>Aucun matériel trouvé!!</p>
                        </template>
                        <template x-if="devices.length > 0">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nom Materiel</th>
                                            <th>Description Materiel</th> 
                                            <th>Nom Service </th> 
                                            <th></th> 
                                    </thead>
                                    </thead>
                                    <tbody>
                                        <template x-for="(device, index) in devices" :key="device.id">
                                            <tr>
                                                <td x-text="index + 1"></td>
                                                 <!-- Editable name Field -->
                                                <td>
                                                    <input 
                                                    type="text" 
                                                    class="form-control" 
                                                    x-model="device.name" 
                                                    >
                                                </td>
                                                <!-- Editable Description Field -->
                                                <td>
                                                    <input 
                                                    type="text" 
                                                    class="form-control" 
                                                    x-model="device.description" 
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
                                                <select class="form-control" x-model="device.service.name">
                                                    <template x-for="service in services" :key="service.id">
                                                    <option :value="service.name" :selected="service.name === device.service.name" x-text="service.name"></option>
                                                    </template>
                                                </select>
                                                <td> 
                                                    <button type="button" class="btn btn-outline-primary"
                                                    @click="updateDevice(device)"
                                                    >
                                                        update
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger"
                                                    @click="deleteDevice(device.id)"
                                                    >
                                                        delete
                                                    </button>
                                                    <button type="button" class="btn btn-outline-info">details</button>
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
        </div>
    </div>
    <script>
        function deviceHandler(initialDevices = [], initialServices = []) {
            return {
                showSidebar: false,
                devices: initialDevices , // Array to hold department data,
                services: initialServices ,
                deleteDevice(device_id) {
                    axios.delete(`/devices/${device_id}`)
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
                        this.devices = this.devices.filter(device => device.id !== device_id);
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
                updateDevice(device) {
                    const data_to_update = {
                        service_name : device.service.name,
                        name: device.name,
                        description: device.description,
                    };
                    axios.put(`/devices/${device.id}`,data_to_update)
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
                        //this.loadDepartments();
                    });
                }
            }
        }
    </script>
</x-app-layout>



