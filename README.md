# VilleConnect - Modules Événements, Signalements & Administration

## Overview
This project was developed as part of the Projet Web - 2nd Year Engineering Program at **Esprit School of Engineering** (Academic Year 2026-2027).
VilleConnect is an intelligent platform for urban services. This repository contains the **Events & Participation Module** (Mohamed), the **Urban Reports Module** (Adem) and the **Administration, Moderation & Statistics Module** (Ela).

## Features
- **Events**: View city events, register online (Front Office), manage events (Back Office).
- **Reports**: Submit urban issues (Front Office), track and update their status (Back Office).
- **Administration**: Statistics dashboard, user moderation (block/unblock), removal of inappropriate reports, admin management and an action log (Back Office).
- MVC Architecture using PHP Object-Oriented Programming.

## Tech Stack
### Frontend
- HTML5, CSS3, JavaScript, Bootstrap 5 (eLEARNING Template)
### Backend
- PHP (POO), PDO, MySQL

## Architecture
- `Models/`: data classes (Evenement, Lieu, Inscription, Signalement, Admin, JournalAction)
- `Controllers/`: business logic and database access
- `Views/front/`: Front Office pages, with shared components in `Views/includes/`
- `Views/back/`: Back Office pages, with shared components in `Views/back/partials/` and `layout.php`

## Contributors
- **Mohamed Houas Rhaiem** - *Events Module*
- **Adem Touati** - *Reports Module*
- **Ela Beitah** - *Administration, Moderation & Statistics Module*

## Academic Context
Developed at **Esprit School of Engineering** - Tunisia (PW - 2A8 | 2026-2027)

## Getting Started
1. Clone the repository into `htdocs`.
2. Import `database.sql` (for Events) into phpMyAdmin.
3. Import `database_adem.sql` (for Reports) into phpMyAdmin.
4. Import `database_ela.sql` (for Administration) into phpMyAdmin.
5. Open `http://localhost/VilleConnect/Views/front/events.php` (Events) or `http://localhost/VilleConnect/Views/front/signalements.php` (Reports).
6. Back Office: `http://localhost/VilleConnect/Views/back/admin_dashboard.php` (Statistics), `admin_events.php`, `admin_signalements.php`, `admin_moderation.php`, `admin_admins.php` and `admin_journal.php`.

## Acknowledgments
- Our supervisors at **Esprit School of Engineering** for their guidance.
- [ThemeWagon](https://themewagon.com/) for the eLEARNING template.
