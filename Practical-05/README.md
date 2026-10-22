# Practical 5: Registration Form with Frontend Validation & Error Handling

## 1. Overview
This practical implements robust client-side validation for a student registration form using HTML5 input elements, regular expressions (RegEx), real-time input event listeners, and a dynamic visual password strength meter.

---

## 2. Validation Test Cases Table

| Test Field | Input Tested | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- |
| **Full Name** | `R` (Too short) | Error: Min 3 characters required | Error displayed in red | Passed |
| **Full Name** | `Rahul123` (Contains digits) | Error: Letters and spaces only | Error displayed in red | Passed |
| **Full Name** | `Rahul Patel` | Accepted (Green border) | Field valid | Passed |
| **Email** | `rahulpatel` (Missing @ and domain) | Error: Invalid email format | Error displayed in red | Passed |
| **Email** | `rahul@charusat.edu.in` | Accepted | Field valid | Passed |
| **Mobile** | `98765` (5 digits) | Error: 10 digits required starting with 6-9 | Error displayed in red | Passed |
| **Mobile** | `3456789012` (Starts with 3) | Error: Must start with 6, 7, 8, or 9 | Error displayed in red | Passed |
| **Mobile** | `9876543210` | Accepted | Field valid | Passed |
| **Password** | `abc` (3 chars) | Weak indicator (Red bar, 30% width) | Meter updates | Passed |
| **Password** | `Abc@1234` | Strong indicator (Green bar, 100% width) | Meter updates | Passed |
| **Confirm Pass** | `DifferentPassword` | Error: Passwords do not match | Error displayed in red | Passed |
| **Terms Checkbox**| Unchecked on submit | Error: Acceptance mandatory | Error displayed | Passed |

---

## 3. Answers to Key Interpretation Questions

### Q1: Are correct input types and attributes used?
Yes:
- `type="text"` for name with `autocomplete="name"`.
- `type="email"` for email address.
- `type="tel"` with `maxlength="10"` for mobile number.
- `type="password"` for secure password masking.
- `type="radio"` grouped by `name="gender"`.
- `type="checkbox"` for terms agreement.

### Q2: Are validation errors displayed near the relevant fields?
Yes. Each form group contains a `<span class="error-msg" id="...Error">` element positioned immediately below the respective input field. In addition, `is-invalid` (red border/background) and `is-valid` (green border/background) classes give clear visual feedback.

### Q3: Is password strength checked?
Yes. As the student types, `checkPasswordStrength()` tests against 5 criteria: length $\ge$ 6, length $\ge$ 8, uppercase letters, numeric digits, and special characters (`!@#$%^&*`). It smoothly animates a colored progress bar between Weak (Red), Medium (Orange), and Strong (Green).
