import './bootstrap';
import './multiselect-dropdown'
import { scanHandler } from './scanhandler'; // Import the function
import axios from 'axios';
//import { init as coreInit } from '@cornerstonejs/core';
//import { init as dicomImageLoaderInit } from '@cornerstonejs/dicom-image-loader';

// Set up Axios defaults (optional)
axios.defaults.baseURL = 'http://localhost:8000'; // Adjust the base URL as needed
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'; // Common header for AJAX requests

// Make Axios available globally
window.axios = axios;
import Alpine from 'alpinejs';

window.Alpine = Alpine;
window.scanHandler = scanHandler; 

Alpine.start();

//import './scanhandler.js';

// Initialize CornerstoneJS and DICOM Image Loader
/*async function initializeCornerstone() {
    await coreInit();
    await dicomImageLoaderInit();
}*/

//initializeCornerstone();

//const url = 'http://localhost:8080/dicomweb';
//const client = new DICOMwebClient.api.DICOMwebClient({url});
//client.searchForStudies().then(studies => {
  //console.log(studies)
//});

