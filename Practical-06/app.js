/* ============================================================
   Practical 6: Fetch API, Search, Filter, Sort & Pagination
   Vanilla JavaScript with external JSON file consumption
   ============================================================ */

document.addEventListener("DOMContentLoaded", function () {
    // Current Active Dataset & State
    let currentDataType = "events"; // "events", "students", or "faqs"
    let allData = [];
    let filteredData = [];
    let currentPage = 1;
    const itemsPerPage = 6;

    // DOM Elements
    const searchInput = document.getElementById("searchInput");
    const categoryFilter = document.getElementById("categoryFilter");
    const sortSelect = document.getElementById("sortSelect");
    const cardsGrid = document.getElementById("cardsGrid");
    const statusMsg = document.getElementById("statusMsg");
    const errorBanner = document.getElementById("errorBanner");
    const pageButtons = document.getElementById("pageButtons");
    const pageInfo = document.getElementById("pageInfo");
    const tabButtons = document.querySelectorAll(".tab-btn");

    // ------------------------------------------------------------
    // 1. Fetch JSON Data
    // ------------------------------------------------------------
    function fetchData(dataType) {
        statusMsg.style.display = "block";
        statusMsg.textContent = "Loading " + dataType + " data...";
        cardsGrid.innerHTML = "";
        errorBanner.style.display = "none";

        const filePath = "data/" + dataType + ".json";

        fetch(filePath)
            .then(function (response) {
                if (!response.ok) {
                    throw new Error("HTTP Error " + response.status + ": Failed to load " + filePath);
                }
                return response.json();
            })
            .then(function (data) {
                allData = data;
                // Cache response in localStorage for offline access
                try {
                    localStorage.setItem("cache_" + dataType, JSON.stringify(data));
                } catch (e) {
                    console.warn("Storage quota exceeded", e);
                }

                statusMsg.style.display = "none";
                populateCategoryDropdown();
                applyFilterAndRender();
            })
            .catch(function (error) {
                console.error("Fetch error:", error);
                statusMsg.style.display = "none";

                // Fallback to cache if available
                const cachedData = localStorage.getItem("cache_" + dataType);
                if (cachedData) {
                    allData = JSON.parse(cachedData);
                    errorBanner.style.display = "block";
                    errorBanner.textContent = "Offline notice: Loaded cached data (" + error.message + ")";
                    populateCategoryDropdown();
                    applyFilterAndRender();
                } else {
                    errorBanner.style.display = "block";
                    errorBanner.textContent = "Error loading data: " + error.message;
                }
            });
    }

    // ------------------------------------------------------------
    // 2. Populate Category Filter Dropdown Dynamically
    // ------------------------------------------------------------
    function populateCategoryDropdown() {
        categoryFilter.innerHTML = '<option value="all">All Categories</option>';
        const categories = new Set();

        allData.forEach(function (item) {
            if (item.category) categories.add(item.category);
            else if (item.department) categories.add(item.department);
        });

        categories.forEach(function (cat) {
            const opt = document.createElement("option");
            opt.value = cat;
            opt.textContent = cat;
            categoryFilter.appendChild(opt);
        });
    }

    // ------------------------------------------------------------
    // 3. Search, Filter & Sort Logic
    // ------------------------------------------------------------
    function applyFilterAndRender() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedCat = categoryFilter.value;
        const sortValue = sortSelect.value;

        // Filtering using Array.prototype.filter()
        filteredData = allData.filter(function (item) {
            // Search term match across multiple fields
            let matchesSearch = false;
            if (item.title && item.title.toLowerCase().includes(searchTerm)) matchesSearch = true;
            if (item.name && item.name.toLowerCase().includes(searchTerm)) matchesSearch = true;
            if (item.question && item.question.toLowerCase().includes(searchTerm)) matchesSearch = true;
            if (item.description && item.description.toLowerCase().includes(searchTerm)) matchesSearch = true;
            if (item.roll && item.roll.toLowerCase().includes(searchTerm)) matchesSearch = true;

            // Category match
            let matchesCategory = false;
            if (selectedCat === "all") {
                matchesCategory = true;
            } else if (item.category && item.category === selectedCat) {
                matchesCategory = true;
            } else if (item.department && item.department === selectedCat) {
                matchesCategory = true;
            }

            return matchesSearch && matchesCategory;
        });

        // Sorting using Array.prototype.sort()
        filteredData.sort(function (a, b) {
            const nameA = (a.title || a.name || a.question || "").toLowerCase();
            const nameB = (b.title || b.name || b.question || "").toLowerCase();

            if (sortValue === "name_asc") return nameA.localeCompare(nameB);
            if (sortValue === "name_desc") return nameB.localeCompare(nameA);
            if (sortValue === "id_asc") return a.id - b.id;
            if (sortValue === "id_desc") return b.id - a.id;
            return 0;
        });

        currentPage = 1;
        renderPage();
    }

    // ------------------------------------------------------------
    // 4. Render Paginated Cards
    // ------------------------------------------------------------
    function renderPage() {
        cardsGrid.innerHTML = "";

        if (filteredData.length === 0) {
            cardsGrid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #64748b;">No records found matching your query.</div>';
            pageInfo.textContent = "Showing 0 of 0";
            pageButtons.innerHTML = "";
            return;
        }

        const totalPages = Math.ceil(filteredData.length / itemsPerPage);
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;

        // Slice current page records
        const pageItems = filteredData.slice(startIndex, endIndex);

        // Render each card
        pageItems.forEach(function (item) {
            const card = document.createElement("div");
            card.className = "data-card";

            if (currentDataType === "events") {
                card.innerHTML = `
                    <div>
                        <span class="card-badge">${item.category}</span>
                        <h3 class="card-title">${item.title}</h3>
                        <div class="card-meta">
                            📅 <strong>Date:</strong> ${item.date} | 📍 <strong>Venue:</strong> ${item.venue}<br>
                            👤 <strong>Coordinator:</strong> ${item.coordinator} | 🎟️ <strong>Seats:</strong> ${item.seats}
                        </div>
                        <p class="card-body">${item.description}</p>
                    </div>
                `;
            } else if (currentDataType === "students") {
                card.innerHTML = `
                    <div>
                        <span class="card-badge">${item.department} Department</span>
                        <h3 class="card-title">${item.name}</h3>
                        <div class="card-meta">
                            🆔 <strong>Roll No:</strong> ${item.roll} | 🎓 <strong>Year:</strong> ${item.year} Year<br>
                            ✉️ <strong>Email:</strong> ${item.email}
                        </div>
                        <p class="card-body"><strong>Cumulative GPA:</strong> ${item.cgpa} / 10.00</p>
                    </div>
                `;
            } else if (currentDataType === "faqs") {
                card.innerHTML = `
                    <div>
                        <span class="card-badge">${item.category}</span>
                        <h3 class="card-title">${item.question}</h3>
                        <p class="card-body">${item.answer}</p>
                    </div>
                `;
            }

            cardsGrid.appendChild(card);
        });

        // Update Page Info & Pagination Controls
        const currentCountStart = startIndex + 1;
        const currentCountEnd = Math.min(endIndex, filteredData.length);
        pageInfo.textContent = `Showing ${currentCountStart} - ${currentCountEnd} of ${filteredData.length} records`;

        renderPaginationControls(totalPages);
    }

    // ------------------------------------------------------------
    // 5. Pagination Buttons
    // ------------------------------------------------------------
    function renderPaginationControls(totalPages) {
        pageButtons.innerHTML = "";

        // Prev Button
        const prevBtn = document.createElement("button");
        prevBtn.className = "page-btn";
        prevBtn.innerHTML = "&laquo; Prev";
        prevBtn.disabled = currentPage === 1;
        prevBtn.addEventListener("click", function () {
            if (currentPage > 1) {
                currentPage--;
                renderPage();
            }
        });
        pageButtons.appendChild(prevBtn);

        // Numeric Buttons
        for (let i = 1; i <= totalPages; i++) {
            const numBtn = document.createElement("button");
            numBtn.className = "page-btn" + (i === currentPage ? " active" : "");
            numBtn.textContent = i;
            numBtn.addEventListener("click", function () {
                currentPage = i;
                renderPage();
            });
            pageButtons.appendChild(numBtn);
        }

        // Next Button
        const nextBtn = document.createElement("button");
        nextBtn.className = "page-btn";
        nextBtn.innerHTML = "Next &raquo;";
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.addEventListener("click", function () {
            if (currentPage < totalPages) {
                currentPage++;
                renderPage();
            }
        });
        pageButtons.appendChild(nextBtn);
    }

    // ------------------------------------------------------------
    // 6. Tab Event Listeners
    // ------------------------------------------------------------
    tabButtons.forEach(function (tab) {
        tab.addEventListener("click", function () {
            tabButtons.forEach(function (t) { t.classList.remove("active"); });
            tab.classList.add("active");
            currentDataType = tab.getAttribute("data-type");
            searchInput.value = "";
            fetchData(currentDataType);
        });
    });

    // Control Listeners
    searchInput.addEventListener("input", applyFilterAndRender);
    categoryFilter.addEventListener("change", applyFilterAndRender);
    sortSelect.addEventListener("change", applyFilterAndRender);

    // Initial Load
    fetchData(currentDataType);
});
