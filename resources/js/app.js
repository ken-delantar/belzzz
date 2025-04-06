import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// Vehicle Management

document.addEventListener("DOMContentLoaded", () => {
    console.log("DOM fully loaded");
    console.log("sortSelect:", document.getElementById("sortSelect"));
    console.log(
        "shipmentCards:",
        document.querySelectorAll(".shipment-card").length
    );

    // Sort functionality
    const sortSelect = document.getElementById("sortSelect");
    if (sortSelect) {
        sortSelect.addEventListener("change", function () {
            const filter =
                document
                    .querySelector(".tab-button.bg-blue-600")
                    ?.getAttribute("data-filter") || "all";
            window.location.href = `/vehicles?filter=${filter}&sort=${this.value}`;
        });
    } else {
        console.warn("Sort select element not found");
    }

    // Vehicle details modal functionality
    const shipmentCards = document.querySelectorAll(".shipment-card");
    const modal = document.getElementById("default-modal");
    const modalRoute = document.getElementById("modal_route");
    const modalDatetime = document.getElementById("modal_datetime");
    const modalParts = document.getElementById("modal_parts");
    const modalMaintenance = document.getElementById("modal_maintenance");
    const modalFuel = document.getElementById("modal_fuel");
    const closeModalBtn = document.getElementById("closeModal");
    const modalCloseBtn = document.getElementById("modalCloseBtn");
    const editVehicleBtn = document.getElementById("editVehicleBtn");
    const deleteVehicleBtn = document.getElementById("deleteVehicleBtn");
    let currentVehicleId = null;

    if (shipmentCards && modal) {
        shipmentCards.forEach((card) => {
            card.addEventListener("click", (e) => {
                const from = card.getAttribute("data-from");
                const to = card.getAttribute("data-to");
                const datetime = card.getAttribute("data-datetime");
                if (modalRoute) modalRoute.value = `${from} — ${to}`;
                if (modalDatetime) modalDatetime.value = datetime;

                currentVehicleId = card.getAttribute("data-id");
                if (!currentVehicleId) {
                    console.error("Vehicle ID not found");
                    return;
                }

                fetch(`/vehicles/${currentVehicleId}`, {
                    headers: {
                        Accept: "application/json",
                    },
                })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error(
                                `HTTP error! status: ${response.status}`
                            );
                        }
                        return response.json();
                    })
                    .then((data) => {
                        if (modalParts)
                            modalParts.textContent =
                                data.available_parts || "No data available";
                        if (modalMaintenance)
                            modalMaintenance.textContent =
                                data.maintenance_record || "No data available";
                        if (modalFuel)
                            modalFuel.textContent =
                                data.fuel_consumption || "No data available";
                    })
                    .catch((error) => {
                        console.error("Error fetching vehicle details:", error);
                        if (modalParts)
                            modalParts.textContent = "Error loading data";
                        if (modalMaintenance)
                            modalMaintenance.textContent = "Error loading data";
                        if (modalFuel)
                            modalFuel.textContent = "Error loading data";
                    });

                modal.classList.remove("hidden");
            });
        });
    }

    function closeModal() {
        if (modal) modal.classList.add("hidden");
    }

    if (closeModalBtn) closeModalBtn.addEventListener("click", closeModal);
    if (modalCloseBtn) modalCloseBtn.addEventListener("click", closeModal);
    window.addEventListener("click", (e) => {
        if (e.target === modal) closeModal();
    });

    // Edit button in Vehicle Details modal
    if (editVehicleBtn) {
        editVehicleBtn.addEventListener("click", () => {
            if (!currentVehicleId) return;
            const vehicleModal = document.getElementById("vehicle-modal");
            const vehicleForm = document.getElementById("vehicleForm");
            if (vehicleModal && vehicleForm) {
                fetch(`/vehicles/${currentVehicleId}/edit`, {
                    headers: {
                        Accept: "application/json",
                    },
                })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error("Failed to fetch vehicle data");
                        }
                        return response.json();
                    })
                    .then((data) => {
                        vehicleForm.action = `/vehicles/${currentVehicleId}`;
                        vehicleForm.method = "POST";
                        if (
                            !vehicleForm.querySelector('input[name="_method"]')
                        ) {
                            const input = document.createElement("input");
                            input.type = "hidden";
                            input.name = "_method";
                            input.value = "PUT";
                            vehicleForm.appendChild(input);
                        }
                        document.getElementById("modalTitle").textContent =
                            "Edit Vehicle";
                        document.getElementById("vehicle_number").value =
                            data.vehicle_number || "";
                        document.getElementById("truck_type").value =
                            data.truck_type || "";
                        document.getElementById("route_from").value =
                            data.route_from || "";
                        document.getElementById("route_to").value =
                            data.route_to || "";
                        document.getElementById("total_capacity").value =
                            data.total_capacity || "";
                        document.getElementById("available_capacity").value =
                            data.available_capacity || "";
                        document.getElementById("status").value =
                            data.status || "ready";
                        vehicleModal.classList.remove("hidden");
                        closeModal(); // Close the details modal
                    })
                    .catch((error) =>
                        console.error("Error fetching vehicle:", error)
                    );
            }
        });
    }

    // Delete button and confirmation modal
    const deleteConfirmationModal = document.getElementById(
        "delete-confirmation-modal"
    );
    const closeDeleteModalBtn = document.getElementById("closeDeleteModal");
    const cancelDeleteBtn = document.getElementById("cancelDeleteBtn");
    const deleteVehicleForm = document.getElementById("deleteVehicleForm");

    if (deleteVehicleBtn) {
        deleteVehicleBtn.addEventListener("click", () => {
            if (!currentVehicleId) return;
            if (deleteConfirmationModal && deleteVehicleForm) {
                deleteVehicleForm.action = `/vehicles/${currentVehicleId}`;
                deleteConfirmationModal.classList.remove("hidden");
            }
        });
    }

    function closeDeleteModal() {
        if (deleteConfirmationModal)
            deleteConfirmationModal.classList.add("hidden");
    }

    if (closeDeleteModalBtn)
        closeDeleteModalBtn.addEventListener("click", closeDeleteModal);
    if (cancelDeleteBtn)
        cancelDeleteBtn.addEventListener("click", closeDeleteModal);
    window.addEventListener("click", (e) => {
        if (e.target === deleteConfirmationModal) closeDeleteModal();
    });

    // Create vehicle modal functionality
    const createVehicleBtn = document.getElementById("createVehicleBtn");
    const vehicleModal = document.getElementById("vehicle-modal");
    const closeVehicleModalBtn = document.getElementById("closeVehicleModal");
    const cancelVehicleModalBtn = document.getElementById("cancelVehicleModal");
    const vehicleForm = document.getElementById("vehicleForm");

    if (createVehicleBtn && vehicleModal && vehicleForm) {
        createVehicleBtn.addEventListener("click", () => {
            vehicleForm.action = "/vehicles";
            vehicleForm.method = "POST";
            const methodInput = vehicleForm.querySelector(
                'input[name="_method"]'
            );
            if (methodInput) methodInput.remove();
            document.getElementById("modalTitle").textContent =
                "Add New Vehicle";
            vehicleForm.reset();
            vehicleModal.classList.remove("hidden");
        });
    }

    function closeVehicleModal() {
        if (vehicleModal) vehicleModal.classList.add("hidden");
    }

    if (closeVehicleModalBtn)
        closeVehicleModalBtn.addEventListener("click", closeVehicleModal);
    if (cancelVehicleModalBtn)
        cancelVehicleModalBtn.addEventListener("click", closeVehicleModal);
    window.addEventListener("click", (e) => {
        if (e.target === vehicleModal) closeVehicleModal();
    });

    // Form submission with AJAX
    if (vehicleForm) {
        vehicleForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const isUpdate =
                vehicleForm.querySelector('input[name="_method"]')?.value ===
                "PUT";
            fetch(vehicleForm.action, {
                method: "POST",
                body: new FormData(vehicleForm),
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    Accept: "application/json",
                },
            })
                .then((response) => {
                    if (!response.ok) {
                        if (response.status === 403) {
                            alert(
                                "You do not have permission to perform this action."
                            );
                        } else if (response.status === 405) {
                            throw new Error(
                                "Method Not Allowed: Check route configuration"
                            );
                        }
                        return response.json().then((err) => {
                            throw new Error(err.message || "Request failed");
                        });
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                })
                .catch((error) => {
                    console.error("Error:", error.message);
                    alert("An error occurred: " + error.message);
                });
        });
    }

    if (deleteVehicleForm) {
        deleteVehicleForm.addEventListener("submit", (e) => {
            e.preventDefault();
            fetch(deleteVehicleForm.action, {
                method: "POST",
                body: new FormData(deleteVehicleForm),
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    Accept: "application/json",
                },
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Failed to delete vehicle");
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                })
                .catch((error) => {
                    console.error("Error:", error.message);
                    alert("An error occurred while deleting: " + error.message);
                });
        });
    }
});
