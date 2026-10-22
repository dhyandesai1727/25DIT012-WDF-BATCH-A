# Practical 3: Responsive UI Design using CSS Grid and Flexbox

## 1. Overview
This practical demonstrates responsive, mobile-first web interface development using pure CSS3 without heavy external libraries. It features layouts across 5 primary pages: Home, About, Registration, Dashboard, and Events.

## 2. Answers to Key Interpretation Questions

### Q1: How does the layout adapt for mobile, tablet, and desktop screens?
- **Mobile (< 640px):** Single-column layout. Navigation items stack vertically or wrap compactly. Card grids render in `1fr` width with touch-friendly spacing.
- **Tablet (640px - 899px):** Grids split into two equal columns (`repeat(2, 1fr)`). Form input fields split into 2 parallel columns for first and last names.
- **Desktop (&ge; 900px):** Grids expand into 3 balanced columns (`repeat(3, 1fr)`). Header navigation switches to a horizontal inline Flexbox row with `justify-content: space-between`.

### Q2: Which layout technique is used and why?
- **Flexbox (`display: flex`):** Used for **1-dimensional layout problems**, such as distributing menu links along a horizontal axis, centering hero call-to-action buttons, and aligning profile avatar badges.
- **CSS Grid (`display: grid`):** Used for **2-dimensional layout problems**, such as the event listing cards and dashboard metric summaries where items need alignment across both columns and rows simultaneously.

### Q3: Are typography, spacing, colors, and contrast readable?
Yes:
- Standard system typography with 1.6 line height ensures optimal readability.
- Navy Blue (`#1e3a8a`) and White (`#ffffff`) have a contrast ratio of > 10:1, well above the WCAG 4.5:1 requirement.
- Spacing is uniformly managed with CSS variables (`padding: 20px`, `gap: 20px`).
