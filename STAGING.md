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

## Manual verification handoff

The original handoff assigned the staging and physical-iPhone checks to the
owner. On 2026-11-07, the owner responded "not require" when asked for staging
access or completed manual results. These checks are therefore **waived for
this verification task, not performed or passed**. No staging URL, manual
results or physical-device evidence was supplied, and no runtime failures
were verified or fixed as part of this task.

The unchecked checklist below remains available for future release acceptance;
it is not evidence of successful verification. The Replit preview is not the
authenticated Laravel application and cannot establish these outcomes. Closing
this verification task does not approve merging, deployment or live release.

Use two synthetic students (A and B), their assigned mentor and an admin.
Record the staging branch/commit, tester, date, browser/device and dummy record
identifiers. Keep credentials and real student information out of this document.

### Games

Inventory every published game and its engine on staging; do not check only
the themed examples. For each game record pass/fail and evidence for:

- [ ] Correct and incorrect answers produce the expected feedback.
- [ ] Timer expiry and exhausted lives end the session correctly.
- [ ] Disconnect during an answer request, reconnect and retry; verify saved
      answers and scores agree with the result, without duplicate awards.
- [ ] Disconnect during completion, confirm no unsaved success is displayed,
      reconnect and use the retry action; confirm the result is saved once.
- [ ] Scores and XP agree with the original scoring rules and saved records.
- [ ] Results survive refresh and appear in the teacher's progress screen.
- [ ] Replay creates the expected new session without changing earlier results
      or granting duplicate XP for the same completed session.

### Independent teacher access

Use the existing mentor student-progress screen for each grant and revocation.

| Action for student A | Expected student A access |
| --- | --- |
| Neither test granted | Neither pre-test nor post-test |
| Grant pre-test only | Pre-test only |
| Grant post-test too | Both tests |
| Revoke pre-test | Post-test only |
| Revoke post-test | Neither test |
| Grant post-test only from neither | Post-test only |

- [ ] Student B cannot start A's assigned test or open A's attempt/result URLs.
- [ ] Save direct attempt links, revoke access, then confirm old links cannot
      resume or submit a revoked attempt.
- [ ] Leave an attempt open, revoke access in the mentor session, then submit
      in the student session; access must be denied without saving new answers
      or awarding XP.
- [ ] Complete a test before revoking access; its teacher-visible answers,
      score, completion status and XP history must remain intact afterward.
- [ ] Re-grant access; existing attempt limits and completed records still apply.
- [ ] An unassigned mentor cannot grant or revoke access for either student.
- [ ] Publication, availability window, school, class and academic-year
      restrictions still apply even when the teacher grants access.

### Layout and physical iPhone

- [ ] Compare mentor and admin screens with the current release; retain the
      original layout, apart from the intentional mentor grant/revoke controls.
- [ ] On physical iPhone Safari, record model and iOS version and check game
      keyboard, audio after touch, dialogs, touch targets and safe-area navigation.
- [ ] Exercise answer submission, completion retry, results and replay on that
      device. Browser emulation does not count as physical-iPhone verification.

Any failure blocks release until resolved and rechecked. Manual acceptance
does not itself merge the pull request or authorize deployment.
