# Student adventure overlay: review and release

This change is prepared for `hankareamol07-oss/spf` as a review branch, not
a live release. Do not merge or deploy until the portal owner approves.

## Included

- The 19 application, Blade, CSS and JavaScript files listed in the overlay
  README, including teacher-gated assessments and game completion retry.
- Nine WebP images under `public/images/student-adventure/`.
- The original Laravel authentication, routes, schema and scoring services
  remain in use. There is no React replacement or database migration.

No environment files, credentials, database exports, existing sessions,
student uploads, seeders, deployment scripts or account records are included.
No production data operations are part of applying these files.

The selected Class 4 adaptations are intentionally **not imported**. Teacher
review and explicit approval are required before any separate content import.
MCQs must not be treated as oral-reading or dictation rubric scores.

## Verification performed before pushing

- Compared the overlay against the repository source at
  `aa52f441ad4ed2ecc47cd60d99b4c47a80ab5765`.
- PHP syntax validation of the overlay's application files.
- JavaScript syntax validation of both standalone scripts.
- Checked that the payload contains only the explicit overlay files, nine
  image assets and this document.

These checks do not establish that authenticated Laravel flows work.
No Laravel test suite or database was used, and no live portal was changed.

## Staging acceptance before release

Use a non-production installation with independently configured staging
credentials, isolated sessions and synthetic accounts/data. Do not reuse the
uploaded archive's credentials or connect staging to the live database.

1. Keep a backup of the deployment being replaced. Review this branch against
   the current release before applying it, especially if the main branch has
   changed since the source comparison above.
2. Apply the branch through the portal's normal staging process. Preserve the
   original PHP dependencies and built `@vite` assets. Do not run database
   migrations, seeders, Composer's `setup` script or account-reset scripts as
   part of this overlay.
3. Clear the compiled view cache on **staging only** with
   `php artisan view:clear`. Run the project's existing tests if available.
4. With synthetic Student, Mentor and Admin accounts, verify that only Students
   receive the adventure shell and that other roles retain their layout.
5. In Marathi and mathematics, check completed, current, locked and unavailable
   skills. Complete and replay practice using the original scoring service;
   check that the resulting mastery and saved results are retained.
6. Exercise all original games. Confirm completion failures offer a retry and
   never display an unsaved result as saved.
7. Grant and revoke pre-test and post-test access independently. Check another
   student cannot start an unassigned test, and a revoked, already-open attempt
   cannot submit. Retain previously saved teacher-visible results.
8. Check mobile Safari on a physical iPhone: keyboard, dialogs, touch targets
   and safe-area navigation.

The owner must separately approve release after staging acceptance. A draft
pull request or passing syntax check is not that approval.
