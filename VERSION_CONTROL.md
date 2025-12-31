# Version Control – MedCenter

## Project Description
MedCenter is a Laravel-based Medical Center Management System. It supports role-based access (Admin, Receptionist, Doctor) and provides staff management, patient registration, appointment scheduling, medical notes, and basic payment/report features.

## Git Repository Structure
Repository: https://github.com/lemsdo-03/medcenter
Single repository used for the full Laravel application (backend + Blade frontend).

Main folders:
- app/ – controllers, models, middleware (application logic)
- routes/ – web routes for modules (staff, patients, appointments, notes)
- resources/views/ – Blade UI pages (admin/receptionist/doctor)
- database/migrations, database/seeders – database schema and test data
- public/ – public assets (images, entry file)

## Branching and Merging Strategy
Branch model:
- main: stable version for release/submission
- dev: integration branch for features before release
- feature/*: feature branches for isolated changes

Merge flow:
feature/* ? Pull Request ? dev ? (release) ? main

## Development Workflow
1. Create a task/feature (based on requirements/FR list).
2. Create a feature branch from dev.
3. Implement changes and commit in small increments.
4. Push branch to GitHub.
5. Open Pull Request into dev.
6. Review changes, fix issues, and merge.
7. Tag milestone versions on main.

## Team Members and Responsibilities
- Taher Al Ibrahim (Solo Developer): planning, UI, backend, database, testing, and code integration.
Pull Requests were used to merge feature branches into dev. Reviews were done through PR comments before merging.

## Tags and Versioning (Milestones)
- v0.1 – Authentication + base layout
- v0.2 – Staff management (admin)
- v0.3 – Patient management (receptionist)
- v0.4 – Appointments + medical notes (doctor)
- v1.0-final – Final submission release
