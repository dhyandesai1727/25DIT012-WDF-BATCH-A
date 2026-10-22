# Practical 4: JavaScript DOM Manipulation, Event Handling, and UI Interactivity

## 1. Overview
This practical implements 6 dynamic user-interface components using vanilla JavaScript (ES6+):
1. Collapsible FAQ Accordion
2. Light / Dark Mode Switcher with `localStorage`
3. Mobile Hamburger Menu
4. Image/Content Slider with next/prev buttons and auto-rotation
5. Dismissible Alert Banner
6. Accessible Modal Popup with background click & Escape key dismissal

## 2. Answers to Key Interpretation Questions

### Q1: How are DOM elements selected and modified?
- **Selection:** Using standard DOM methods:
  - `document.getElementById('id')`: For fast, single-element lookups.
  - `document.querySelectorAll('.class')`: To select collections of elements (e.g. accordion triggers, slider dots).
- **Modification:**
  - `element.classList.toggle('dark-mode')`, `classList.add('open')`, `classList.remove('active')` to update visual states cleanly without messy inline styles.
  - `element.style.display = 'none'` for instant banner dismissals.
  - `element.textContent = '...'` to safely update button labels.

### Q2: Are event listeners attached correctly?
Yes, all interactive components use the modern `element.addEventListener('click', handlerFunction)` syntax inside a `DOMContentLoaded` event listener, guaranteeing that HTML nodes exist before JS executes.

### Q3: Is localStorage used for remembering theme choice?
Yes. On page load, `localStorage.getItem('studenthub_theme')` is evaluated. If the value equals `'dark'`, the `dark-mode` class is automatically applied to `<body>`. When the toggle button is clicked, `localStorage.setItem('studenthub_theme', ...)` writes the new preference to the browser.

### Q4: Does interactivity improve usability without breaking accessibility?
Yes:
- The modal dialog includes `role="dialog"`, `aria-modal="true"`, and can be closed using the keyboard `Escape` key.
- The banner includes `role="alert"`.
- Focus outlines are preserved across all buttons and clickable accordion headers.
