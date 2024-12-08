export function scanHandler(data_scan_with_pages = [], input_categories = []) {
    console.log(input_categories);
    //console.log(data_scan_with_pages);
    return {
        total: data_scan_with_pages.total || 0,
        context: '',
        servs: [],
        ctegs: [],
        scan_ref: '',
        show_btn_add_image: false,
        showSidebar: false,
        newcateg: '',
        images: data_scan_with_pages.data || [], // images data,
        categories: input_categories || [],
        prev_buton_disabled: !data_scan_with_pages.prev_page_url,
        next_buton_disabled: !data_scan_with_pages.next_page_url,
        prev_page_url: data_scan_with_pages.prev_page_url,
        next_page_url: data_scan_with_pages.next_page_url,
        nextImage(prev_page_url, next_page_url) {
            console.log(next_page_url);
            if (next_page_url !== null) {
                axios.get(next_page_url, {
                        params: {
                            srv: JSON.stringify(this.servs),
                            ctg: JSON.stringify(this.ctegs),
                            ref: this.scan_ref,
                            ctx: this.context
                        }
                    })
                    .then(response => {
                        this.images = response.data.data;
                        this.next_page_url = response.data.next_page_url;
                        this.prev_page_url = response.data.prev_page_url;
                        this.next_buton_disabled = !this.next_page_url;
                        this.prev_buton_disabled = !this.prev_page_url;
                        this.total = response.data.total;
                    })
                    .catch(error => {
                        console.error("Error fetching next page:", error);
                    });
            }
        },
        prevImage(prev_page_url, next_page_url) {
            if (prev_page_url !== null) {
                axios.get(prev_page_url, {
                        params: {
                            srv: JSON.stringify(this.servs),
                            ctg: JSON.stringify(this.ctegs),
                            ref: this.scan_ref,
                            ctx: this.context
                        }
                    })
                    .then(response => {
                        this.images = response.data.data;
                        this.next_page_url = response.data.next_page_url;
                        this.prev_page_url = response.data.prev_page_url;
                        this.next_buton_disabled = !this.next_page_url;
                        this.prev_buton_disabled = !this.prev_page_url;
                        this.total = response.data.total;
                    })
                    .catch(error => {
                        console.error("Error fetching previous page:", error);
                    });
            }
        },
        formatDate(dateString) {
            if (!dateString) return '';
            const [year, month, day] = dateString.split('-');
            return `${day}/${month}/${year}`;
        },
        deleteImage(image_id) {
            axios.delete(`/scans/${image_id}`)
                .then(response => {
                    Toastify({
                        text: response.data.success,
                        duration: 3000, // Notification duration in milliseconds
                        gravity: "top", // "top" or "bottom"
                        position: "right", // "left", "center", or "right"
                        backgroundColor: "#4caf50", // Success green
                    }).showToast();
                    this.images = this.images.filter(image => image.id !== image_id);
                    this.total = this.total - 1;
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
        addCateg() {
            console.log("click categ ");
            this.showSidebar = true;
            console.log(this.categories);

        },
        closeModal() {
            this.showSidebar = false;
        },
        categService(context, newcateg) {
            console.log(newcateg);
            const categs = newcateg.split(',');
            axios.post(`/categ/${context}/config`, {
                    newcateg: categs
                })
                .then(response => {
                    Toastify({
                        text: response.data.message,
                        duration: 3000, // Notification duration in milliseconds
                        gravity: "top", // "top" or "bottom"
                        position: "right", // "left", "center", or "right"
                        backgroundColor: "#4caf50", // Success green
                    }).showToast();
                    this.newcateg = '';
                    if (context === "del") {
                        this.categories = this.categories.filter(obj => !categs.includes(obj.name));
                    }
                    if (context === "add") {
                        categs.forEach((item) => {
                            this.categories.push({
                                name: item
                            })

                        });
                    }
                })
                .catch(error => {
                    let errorMessage = "An error occurred on the server.";
                    if (error.response) {
                        // Only access error.response if it exists
                        errorMessage = error.response.data.error || errorMessage;
                    }
                    Toastify({
                        text: errorMessage,
                        duration: 3000, // Notification duration in milliseconds
                        gravity: "top", // "top" or "bottom"
                        position: "right", // "left", "center", or "right"
                        backgroundColor: "#f44336", // red 
                    }).showToast();
                });
        },
        imagesearch() {
            axios.get('/scans', {
                    params: {
                        srv: JSON.stringify(this.servs),
                        ctg: JSON.stringify(this.ctegs),
                        ref: this.scan_ref,
                        ctx: 'search'
                    }
                })
                .then(response => {
                   
                    console.log(response.data);
                    this.images = response.data.data;
                    this.context = 'search';
                    this.next_page_url = response.data.next_page_url;
                    this.prev_page_url = response.data.prev_page_url;
                    this.next_buton_disabled = !this.next_page_url;
                    this.prev_buton_disabled = !this.prev_page_url;
                    this.total = response.data.total;
                    if (this.total > 0) {
                        Toastify({
                            text: this.total + " images trouvés",
                            duration: 3000, // Notification duration in milliseconds
                            gravity: "top", // "top" or "bottom"
                            position: "right", // "left", "center", or "right"
                            backgroundColor: "#4caf50", // Success green
                        }).showToast();
                    } else {
                        Toastify({
                            text: "Acune image trouvée",
                            duration: 3000, // Notification duration in milliseconds
                            gravity: "top", // "top" or "bottom"
                            position: "right", // "left", "center", or "right"
                            backgroundColor: "#f44336", // red 
                        }).showToast();
                    }
                })
                .catch(error => {
                    let errorMessage = "An error occurred on the server.";
                    if (error.response) {
                        // Only access error.response if it exists
                        errorMessage = error.response.data.error || errorMessage;
                    }
                    Toastify({
                        text: errorMessage,
                        duration: 3000, // Notification duration in milliseconds
                        gravity: "top", // "top" or "bottom"
                        position: "right", // "left", "center", or "right"
                        backgroundColor: "#f44336", // red 
                    }).showToast();
                    
                    console.error("Error fetching search results:", error);
                });
        }
    }
}
document.addEventListener('DOMContentLoaded', function() {
    const elem = document.getElementById('produced_at');
    if (document.getElementById('produced_at')) {
        const datepicker = new Datepicker(elem, {
            buttonClass: 'btn',
            format: 'mm/dd/yyyy',
        });
    } else {
        console.log("no elelement is loaded datapicker")
    }

});
