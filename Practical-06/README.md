# Practical 6: Rendering External JSON Data using Fetch API, Search & Filter

## 1. Overview
This practical demonstrates fetching and parsing external JSON data asynchronously using the modern JavaScript `fetch()` API. It supports switching between 3 independent datasets (`events.json`, `students.json`, and `faqs.json`—each with $\ge 15$ records), applying multi-attribute search, category filtering, dual-order sorting, and slice-based client-side pagination.

---

## 2. Answers to Key Interpretation Questions

### Q1: How is JSON fetched, parsed, and rendered?
1. **Fetching:** `fetch('data/events.json')` returns a Promise resolving to the HTTP Response.
2. **Parsing:** `.then(response => response.json())` parses the raw JSON string stream into native JavaScript arrays of objects.
3. **Rendering:** The parsed items are dynamically converted to HTML cards using template literals and appended to the `#cardsGrid` container.

### Q2: Which array methods are used for search, filter, sort, and pagination?
- **Filtering & Search:** `Array.prototype.filter()` iterates through each item and checks if `title`, `name`, or `description` includes the query string via `.includes()`.
- **Sorting:** `Array.prototype.sort()` sorts strings using `localeCompare()` or numbers by numeric subtraction (`a.id - b.id`).
- **Pagination:** `Array.prototype.slice(startIndex, endIndex)` extracts exactly 6 records corresponding to the current active page index.

### Q3: How are loading and error states handled?
- While the Promise is pending, a loading indicator (`#statusMsg`) is rendered.
- If the network fails or the JSON file is missing, the `.catch()` block catches the error and displays a red error banner (`#errorBanner`).
- In addition, successful payloads are cached into `localStorage`, so if the server goes offline, cached records are restored gracefully.
