
---

## `FEATURES.md`

```md
# Land-Linker Features (F1–F20)

This file maps each faculty feature requirement (F1–F20) to the project modules.

---

## F1. Authentication System
- F1.1 Login integration (`View/login.php`, `Controller/loginCheck.php`)
- F1.2 Signup flow (`View/signup.php`, `Controller/signupCheck.php`)
- F1.3 Logout (`Controller/logout.php`)
- F1.4 Session init/destroy (`Model/init.php`, `Model/auth.php`)

---

## F2. Authorization & Role Management
- F2.1 Role-based access control (`Model/helpers.php`, `Controller/authCheck.php`)
- F2.2 Roles: Admin/Manager/Employee/User (session role values)
- F2.3 Route/page protection (dashboard-start files + helper)
- F2.4 Unauthorized redirect (login or safe route)

---

## F3. MVC Routing & Front Controller
- F3.1 Single entry point (`index.php`)
- F3.2 Query-string routing (`?route=...`)
- F3.3 Controller-action mapping (`Controller/router.php`)
- F3.4 Default route handling (fallback route)

---

## F4. Layout & Template Management
- F4.1 Central header (`View/layout/header.php`)
- F4.2 Central footer (`View/layout/footer.php`)
- F4.3 Reusable dashboards (dashboard start/end includes)
- F4.4 Common UI components (nav/sidebar in layout)

---

## F5. Static Asset Management
- F5.1 CSS organization (`css/style.css`)
- F5.2 JS organization (`js/main.js`)
- F5.3 Image handling (`View/assets/...` or your existing folder)
- F5.4 Asset path normalization (BASE_URL usage)

---

## F6. User Dashboard Module
- F6.1 Dashboard MVC integration (`View/*-dashboard.php`)
- F6.2 Role-aware rendering (admin/manager/employee/user dashboards)
- F6.3 Dashboard navigation controls (layout sidebar/topnav)

---

## F7. User Profile Management
- F7.1 Profile view (`View/profile.php`)
- F7.2 Update simulation (POST handler or simulated in same view)
- F7.3 Account info display (session + DB query)
- F7.4 Profile validation (basic/simulated rules)

---

## F8. Property Creation Module
- F8.1 Create form (`View/property-create.php`)
- F8.2 Validation simulation (Controller/Model checks)
- F8.3 Submission handling (`View/add-property.php` or Controller handler)
- F8.4 Success/failure feedback (message/redirect)

---

## F9. Property Listing & Details
- F9.1 Listing page (`View/my-properties.php`)
- F9.2 Detail page (`View/property-details.php`)
- F9.3 Ownership display (owner info from session/DB)

---

## F10. Property Update & Deletion
- F10.1 Edit form (`View/property-edit.php`, `View/admin/property-edit.php`)
- F10.2 Update handling (Controller + Model queries)
- F10.3 Deletion workflow (`View/property-delete.php`, admin variants)
- F10.4 Delete confirmation logic (confirm page/button)

---

## F11. Favorites Management
- F11.1 Add to favorites (toggle action + DB update)
- F11.2 Remove from favorites (toggle action + DB update)
- F11.3 Favorites listing (favorites section on listing)

---

## F12. Property Search & Filter
- F12.1 Keyword search (query string)
- F12.2 Filter by type (dropdown/filter param)
- F12.3 Sort price/date (sort param)
- F12.4 Combined search/filter (multi params)

---

## F13. Map View Integration
- F13.1 Map page (`View/map-view.php`)
- F13.2 Marker simulation (JS)
- F13.3 Location visualization (lat/long dummy data or stored values)

---

## F14. Inquiry Management System
- F14.1 Inquiry submission (from property details or inquiry form)
- F14.2 Admin listing (`View/admin/inquiries.php`)
- F14.3 Status tracking (pending/approved/closed simulation)

---

## F15. Deal Management System
- F15.1 Deal creation workflow (`View/admin/deals.php`)
- F15.2 Deal status lifecycle (pending/active/completed)
- F15.3 Deal summary display (`View/deal-statistics.php`)

---

## F16. Task & Schedule Management
- F16.1 Task creation/assignment (`View/admin/tasks.php`)
- F16.2 Employee task view (`View/employee-tasks.php`)
- F16.3 Schedule management (`View/admin/schedules.php`)
- F16.4 Task status updates (pending/in progress/done)

---

## F17. Subscription & Plan Management
- F17.1 Plan create/list (`View/admin/plans.php`)
- F17.2 Subscription workflow (`View/subscription.php`)
- F17.3 Subscription status handling (`View/admin/subscriptions.php`)

---

## F18. Payment Processing Module
- F18.1 Payment initiation simulation (`View/payment.php`)
- F18.2 Payment record creation (Model query insert)
- F18.3 Payment history (`View/admin/payments.php` or user history page)

---

## F19. Database Layer Modularization
- F19.1 User queries separated (in `Model/db_queries.php` section)
- F19.2 Property queries separated
- F19.3 Deal & inquiry queries separated
- F19.4 Payment & subscription queries separated

---

## F20. Release & Version Management
- F20.1 `CHANGELOG.md` maintained
- F20.2 Version tagging (`git tag -a v1.0.0 -m ...`)
- F20.3 dev → stage → main promotion (merge flow)
- F20.4 Release documentation (this file + changelog)
