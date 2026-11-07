# Marathi and Mathematics Learning Portal

A Laravel platform for personalized remedial Marathi and Mathematics learning, mentor intervention, and holistic student progress.

The production application includes authentication, tenant-aware administration, Marathi and Mathematics curriculum, adaptive practice, assessments, diagnosis, games, simulations, recommendations, mentor interventions, holistic observations, private portfolios, printable reports, database notifications, and shared-hosting deployment support.

## Windows local testing

This package includes the compatible PHP 8.4 x64 runtime, production
dependencies, compiled assets, and a seeded SQLite database. Extract the
complete ZIP and double-click `START-PORTAL.bat`.

Do not run the portal with XAMPP PHP 8.0. See `WINDOWS-SETUP.txt` for complete
instructions and troubleshooting.

All seeded development accounts use the password `password`:

| Role | Email |
| --- | --- |
| Super administrator | `admin@example.test` |
| School administrator | `school-admin@example.test` |
| Mentor | `mentor@example.test` |
| Students | `student1@example.test` through `student15@example.test` |

These accounts are development fixtures only.

Authentication rejects inactive users, users attached to inactive/deleted schools, roleless users, and student or mentor accounts without a matching school-owned profile. Login attempts are rate-limited by normalized email and IP address.

## Documentation

- [Architecture and implementation plan](docs/project-plan.md)
- [Local development](docs/local-development.md)
- [Testing strategy](docs/testing.md)
- [Security foundation](docs/security.md)
- [Shared-hosting deployment](docs/deployment.md)
