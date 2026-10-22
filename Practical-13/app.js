/* ============================================================
   Practical 13: Asynchronous CRUD with Fetch API & PHP JSON Endpoints
   No full page reload on List, Add, Update, or Delete
   ============================================================ */

document.addEventListener("DOMContentLoaded", function () {
    const apiEndpoint = "api/events_api.php";

    let eventsData = [];
    const eventsTableBody = document.getElementById("eventsTableBody");
    const toastNotice = document.getElementById("toastNotice");
    const searchInput = document.getElementById("searchEventInput");
    const modalBackdrop = document.getElementById("modalBackdrop");
    const modalTitle = document.getElementById("modalTitle");
    const eventForm = document.getElementById("eventForm");
    const openAddModalBtn = document.getElementById("openAddModalBtn");
    const closeModalBtn = document.getElementById("closeModalBtn");

    // Form Inputs
    const eventIdInput = document.getElementById("eventId");
    const formActionInput = document.getElementById("formAction");
    const titleInput = document.getElementById("title");
    const categoryInput = document.getElementById("category");
    const eventDateInput = document.getElementById("event_date");
    const eventTimeInput = document.getElementById("event_time");
    const venueInput = document.getElementById("venue");
    const coordinatorInput = document.getElementById("coordinator");
    const maxSeatsInput = document.getElementById("max_seats");

    // ------------------------------------------------------------
    // 1. Toast Notification Helper
    // ------------------------------------------------------------
    function showToast(message, isSuccess = true) {
        toastNotice.textContent = message;
        toastNotice.className = "toast-msg " + (isSuccess ? "toast-success" : "toast-error");
        toastNotice.style.display = "block";
        setTimeout(function () {
            toastNotice.style.display = "none";
        }, 4000);
    }

    // ------------------------------------------------------------
    // 2. Load Events via GET Request (Fetch API)
    // ------------------------------------------------------------
    function loadEvents() {
        eventsTableBody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: #64748b; padding: 25px;">Loading events from database...</td></tr>';

        fetch(apiEndpoint)
            .then(function (res) { return res.json(); })
            .then(function (response) {
                if (response.status === "success") {
                    eventsData = response.data;
                    renderEventsTable(eventsData);
                } else {
                    showToast(response.message || "Failed to load events", false);
                }
            })
            .catch(function (err) {
                console.error(err);
                eventsTableBody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: red; padding: 25px;">Failed to connect to API endpoint.</td></tr>';
            });
    }

    // ------------------------------------------------------------
    // 3. Render Table HTML
    // ------------------------------------------------------------
    function renderEventsTable(items) {
        eventsTableBody.innerHTML = "";

        if (items.length === 0) {
            eventsTableBody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: #64748b; padding: 25px;">No events found in database.</td></tr>';
            return;
        }

        items.forEach(function (ev) {
            const tr = document.createElement("tr");
            tr.id = "event-row-" + ev.id;
            tr.innerHTML = `
                <td><strong>#${ev.id}</strong></td>
                <td><strong>${escapeHtml(ev.title)}</strong></td>
                <td><span style="background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px; font-size: 11px; font-weight: bold;">${escapeHtml(ev.category)}</span></td>
                <td>${ev.event_date} <small style="color: #64748b;">${ev.event_time}</small></td>
                <td>${escapeHtml(ev.venue)}</td>
                <td>${escapeHtml(ev.coordinator || 'N/A')}</td>
                <td style="text-align: center; white-space: nowrap;">
                    <button class="btn btn-warning edit-btn" data-id="${ev.id}">Edit</button>
                    <button class="btn btn-danger delete-btn" data-id="${ev.id}">Delete</button>
                </td>
            `;
            eventsTableBody.appendChild(tr);
        });

        // Attach action listeners
        document.querySelectorAll(".edit-btn").forEach(function (btn) {
            btn.addEventListener("click", function () {
                const id = parseInt(this.getAttribute("data-id"));
                openEditModal(id);
            });
        });

        document.querySelectorAll(".delete-btn").forEach(function (btn) {
            btn.addEventListener("click", function () {
                const id = parseInt(this.getAttribute("data-id"));
                deleteEvent(id);
            });
        });
    }

    function escapeHtml(text) {
        if (!text) return "";
        return text.replace(/[&<>"']/g, function (m) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
        });
    }

    // ------------------------------------------------------------
    // 4. Modal Open & Close
    // ------------------------------------------------------------
    function openModal() {
        modalBackdrop.style.display = "flex";
    }

    function closeModal() {
        modalBackdrop.style.display = "none";
        eventForm.reset();
        eventIdInput.value = "";
    }

    openAddModalBtn.addEventListener("click", function () {
        modalTitle.textContent = "Create New Event (AJAX)";
        formActionInput.value = "add";
        openModal();
    });

    closeModalBtn.addEventListener("click", closeModal);
    modalBackdrop.addEventListener("click", function (e) {
        if (e.target === modalBackdrop) closeModal();
    });

    function openEditModal(id) {
        const ev = eventsData.find(function (item) { return parseInt(item.id) === id; });
        if (!ev) return;

        modalTitle.textContent = "Edit Event #" + id + " (AJAX)";
        formActionInput.value = "update";
        eventIdInput.value = ev.id;
        titleInput.value = ev.title;
        categoryInput.value = ev.category;
        eventDateInput.value = ev.event_date;
        eventTimeInput.value = ev.event_time;
        venueInput.value = ev.venue;
        coordinatorInput.value = ev.coordinator;
        maxSeatsInput.value = ev.max_seats;
        openModal();
    }

    // ------------------------------------------------------------
    // 5. Submit Form (Add or Update via Fetch API POST)
    // ------------------------------------------------------------
    eventForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const payload = {
            action: formActionInput.value,
            id: eventIdInput.value,
            title: titleInput.value.trim(),
            category: categoryInput.value,
            event_date: eventDateInput.value,
            event_time: eventTimeInput.value,
            venue: venueInput.value.trim(),
            coordinator: coordinatorInput.value.trim(),
            max_seats: parseInt(maxSeatsInput.value) || 100
        };

        fetch(apiEndpoint, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        })
            .then(function (res) { return res.json(); })
            .then(function (result) {
                if (result.status === "success") {
                    showToast(result.message, true);
                    closeModal();
                    loadEvents(); // Reload live list without browser refresh!
                } else {
                    showToast(result.message || "Operation failed", false);
                }
            })
            .catch(function (err) {
                console.error(err);
                showToast("Request failed: " + err.message, false);
            });
    });

    // ------------------------------------------------------------
    // 6. Delete Event via Fetch API POST
    // ------------------------------------------------------------
    function deleteEvent(id) {
        if (!confirm("Are you sure you want to delete event #" + id + "?")) return;

        fetch(apiEndpoint, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ action: "delete", id: id })
        })
            .then(function (res) { return res.json(); })
            .then(function (result) {
                if (result.status === "success") {
                    showToast(result.message, true);
                    // Dynamically remove row from DOM immediately!
                    const row = document.getElementById("event-row-" + id);
                    if (row) row.remove();
                    // Update cache array
                    eventsData = eventsData.filter(function (e) { return parseInt(e.id) !== id; });
                } else {
                    showToast(result.message || "Failed to delete", false);
                }
            })
            .catch(function (err) {
                console.error(err);
                showToast("Error deleting: " + err.message, false);
            });
    }

    // ------------------------------------------------------------
    // 7. Live Filter / Search with Debounce
    // ------------------------------------------------------------
    let debounceTimer;
    searchInput.addEventListener("input", function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            const query = searchInput.value.toLowerCase().trim();
            const filtered = eventsData.filter(function (e) {
                return (
                    e.title.toLowerCase().includes(query) ||
                    e.venue.toLowerCase().includes(query) ||
                    e.category.toLowerCase().includes(query)
                );
            });
            renderEventsTable(filtered);
        }, 200);
    });

    // Initial Load
    loadEvents();
});
