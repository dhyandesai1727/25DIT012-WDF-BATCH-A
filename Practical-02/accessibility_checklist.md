# Practical 2: HTML5 Semantic & Accessibility Checklist

This checklist confirms compliance with standard Web Content Accessibility Guidelines (WCAG 2.1 Level AA) across all 11 static StudentHub HTML5 pages.

| Checkpoint | Requirement | Status | Implementation in Code |
| :--- | :--- | :--- | :--- |
| **1. Semantic Landmarks** | Use `<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<aside>`, `<footer>` | Passed | Implemented across all 11 pages cleanly. |
| **2. Document Language** | Specify language on root element | Passed | `<html lang="en">` present on every document. |
| **3. Skip to Content Link** | Bypass repetitive navigation blocks | Passed | `<a href="#main-content" class="skip-link">` allows instant keyboard skipping to `<main>`. |
| **4. Heading Hierarchy** | Single `<h1>` per page, followed logically by `<h2>` and `<h3>` | Passed | All pages have structured headers with unique page titles. |
| **5. Form Labels** | Every form control must have an associated `<label>` | Passed | All inputs contain explicit `id` attributes matched with `<label for="...">`. |
| **6. Color Contrast** | High contrast text vs background | Passed | `#1e3a8a` navy blue on white (`#ffffff`) exceeds the 4.5:1 ratio requirement. |
| **7. Keyboard Navigation** | All links, buttons, and form inputs must be reachable via `Tab` | Passed | Interactive elements retain focus rings and tab stops. |
| **8. Breadcrumbs** | Provide clear orientation within hierarchy | Passed | Added `<nav aria-label="Breadcrumb">` on all interior subpages. |
| **9. Viewport Tag** | Mobile accessibility and scaling | Passed | `<meta name="viewport" content="width=device-width, initial-scale=1.0">` included in head. |
