<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Game;
use App\Models\GameSession;
use App\Models\HpcEvaluation;
use App\Models\Student;
use App\Models\User;
use App\Services\HpcAutoCompleteService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HpcEvaluationController extends Controller
{
    /**
     * Display Class 4 HPC Roster
     */
    public function index(Request $request): View
    {
        $term = (int) $request->query('term', 1);
        $academicYear = AcademicYear::where('is_active', true)->first() ?? AcademicYear::first();

        $students = Student::query()
            ->with(['user', 'latestHpcEvaluation'])
            ->orderBy('student_number')
            ->orderBy('id')
            ->get();

        $totalStudents = $students->count();
        $evaluatedCount = $students->filter(fn ($s) => $s->latestHpcEvaluation !== null)->count();
        $proficientCount = $students->filter(fn ($s) => $s->latestHpcEvaluation?->overall_fln_level === 'NIPUN_PROFICIENT')->count();
        $progressingCount = $students->filter(fn ($s) => $s->latestHpcEvaluation?->overall_fln_level === 'PROGRESSING')->count();

        return view('hpc.index', [
            'students' => $students,
            'term' => $term,
            'academicYear' => $academicYear,
            'totalStudents' => $totalStudents,
            'evaluatedCount' => $evaluatedCount,
            'proficientCount' => $proficientCount,
            'progressingCount' => $progressingCount,
        ]);
    }

    /**
     * Display 360° Holistic Progress Card for a Class 4 Student
     */
    public function show(Request $request, Student $student): View
    {
        $term = (int) $request->query('term', 1);
        $academicYear = AcademicYear::where('is_active', true)->first() ?? AcademicYear::first();

        $evaluation = HpcEvaluation::where('student_id', $student->id)
            ->where('academic_year_id', $academicYear?->id ?? 1)
            ->where('term', $term)
            ->first();

        // If not exists yet, create default structure
        if (!$evaluation) {
            $evaluation = new HpcEvaluation([
                'student_id' => $student->id,
                'academic_year_id' => $academicYear?->id ?? 1,
                'term' => $term,
                'status' => 'draft',
                'overall_fln_level' => 'PROGRESSING',
                'self_assessment' => [
                    'likes_school' => 'yes',
                    'favorite_subject' => 'गणित',
                    'favorite_game' => 'AppleMath - सफरचंद गणित',
                    'helps_classmates' => 'always',
                    'expresses_ideas' => 'confident',
                    'my_goal' => 'गणितातील क्रिया खेळातून अचूक सोडवणे.',
                ],
                'peer_assessment' => [
                    'peer_name' => 'सहकारी मित्र',
                    'works_in_group' => 'yes',
                    'shares_resources' => 'yes',
                    'speaks_politely' => 'yes',
                    'best_quality' => 'खेळात छान साथ देतो.',
                ],
                'teacher_assessment' => [
                    'awareness_level' => 'praveen',
                    'sensitivity_level' => 'praveen',
                    'creativity_level' => 'praveen',
                    'cognitive_score' => 88,
                    'affective_score' => 90,
                    'psychomotor_score' => 89,
                    'fln_math_level' => 'NIPUN_PROFICIENT',
                    'fln_language_level' => 'PROGRESSING',
                    'strengths' => 'खेळात रस घेऊन स्वयंअध्ययन करतो.',
                    'areas_for_improvement' => 'मराठी व्याकरण सराव आवश्यक.',
                    'teacher_remarks' => 'प्रगती चांगली आहे.',
                ],
                'mentor_guidance' => [
                    'mentor_name' => 'श्री. सोमनाथ वाळके सर',
                    'observation' => 'खेळातून शिकण्याचा उत्तम उत्साह दिसून येतो.',
                    'recommended_action' => 'नियमित सराव चालू ठेवावा.',
                ],
                'parent_feedback' => [
                    'parent_name' => $student->guardian_name ?? 'पालक',
                    'home_reading_habit' => 'regular',
                    'digital_game_impact' => 'very_positive',
                    'parent_remarks' => 'घरी डिजिटल खेळ आनंदाने खेळतो.',
                ],
            ]);
        }

        $digitalEvidence = $evaluation->getDigitalLearningEvidence();

        return view('hpc.show', [
            'student' => $student->load('user'),
            'evaluation' => $evaluation,
            'term' => $term,
            'academicYear' => $academicYear,
            'digitalEvidence' => $digitalEvidence,
        ]);
    }

    /**
     * Edit 360° Evaluation Form
     */
    public function edit(Request $request, Student $student): View
    {
        $term = (int) $request->query('term', 1);
        $academicYear = AcademicYear::where('is_active', true)->first() ?? AcademicYear::first();

        $evaluation = HpcEvaluation::firstOrNew(
            [
                'student_id' => $student->id,
                'academic_year_id' => $academicYear?->id ?? 1,
                'term' => $term,
            ],
            [
                'status' => 'completed',
                'overall_fln_level' => 'NIPUN_PROFICIENT',
            ]
        );

        $classmates = Student::where('id', '!=', $student->id)->with('user')->get();

        return view('hpc.edit', [
            'student' => $student->load('user'),
            'evaluation' => $evaluation,
            'term' => $term,
            'academicYear' => $academicYear,
            'classmates' => $classmates,
        ]);
    }

    /**
     * Update or Store 360° Evaluation
     */
    public function update(Request $request, Student $student): RedirectResponse
    {
        $term = (int) $request->input('term', 1);
        $academicYear = AcademicYear::where('is_active', true)->first() ?? AcademicYear::first();

        $selfData = [
            'likes_school' => $request->input('self_likes_school', 'yes'),
            'favorite_subject' => $request->input('self_favorite_subject', 'गणित'),
            'favorite_game' => $request->input('self_favorite_game', 'AppleMath - सफरचंद गणित'),
            'helps_classmates' => $request->input('self_helps_classmates', 'always'),
            'expresses_ideas' => $request->input('self_expresses_ideas', 'confident'),
            'my_goal' => $request->input('self_my_goal', ''),
        ];

        $peerData = [
            'peer_name' => $request->input('peer_name', ''),
            'works_in_group' => $request->input('peer_works_in_group', 'yes'),
            'shares_resources' => $request->input('peer_shares_resources', 'yes'),
            'speaks_politely' => $request->input('peer_speaks_politely', 'yes'),
            'best_quality' => $request->input('peer_best_quality', ''),
        ];

        $teacherData = [
            'awareness_level' => $request->input('teacher_awareness_level', 'pragat'),
            'sensitivity_level' => $request->input('teacher_sensitivity_level', 'pragat'),
            'creativity_level' => $request->input('teacher_creativity_level', 'praveen'),
            'cognitive_score' => (int) $request->input('teacher_cognitive_score', 90),
            'affective_score' => (int) $request->input('teacher_affective_score', 90),
            'psychomotor_score' => (int) $request->input('teacher_psychomotor_score', 90),
            'fln_math_level' => $request->input('teacher_fln_math_level', 'NIPUN_PROFICIENT'),
            'fln_language_level' => $request->input('teacher_fln_language_level', 'NIPUN_PROFICIENT'),
            'strengths' => $request->input('teacher_strengths', ''),
            'areas_for_improvement' => $request->input('teacher_areas_for_improvement', ''),
            'teacher_remarks' => $request->input('teacher_remarks', ''),
        ];

        $mentorData = [
            'mentor_name' => $request->input('mentor_name', 'श्री. सोमनाथ वाळके सर'),
            'observation' => $request->input('mentor_observation', ''),
            'recommended_action' => $request->input('mentor_recommended_action', ''),
        ];

        $parentData = [
            'parent_name' => $request->input('parent_name', $student->guardian_name ?? 'पालक'),
            'home_reading_habit' => $request->input('parent_home_reading_habit', 'regular'),
            'digital_game_impact' => $request->input('parent_digital_game_impact', 'very_positive'),
            'parent_remarks' => $request->input('parent_remarks', ''),
        ];

        $flnLevel = $request->input('overall_fln_level', 'NIPUN_PROFICIENT');

        HpcEvaluation::updateOrCreate(
            [
                'student_id' => $student->id,
                'academic_year_id' => $academicYear?->id ?? 1,
                'term' => $term,
            ],
            [
                'evaluator_user_id' => Auth::id(),
                'peer_student_id' => $request->input('peer_student_id'),
                'self_assessment' => $selfData,
                'peer_assessment' => $peerData,
                'teacher_assessment' => $teacherData,
                'mentor_guidance' => $mentorData,
                'parent_feedback' => $parentData,
                'overall_fln_level' => $flnLevel,
                'status' => 'completed',
            ]
        );

        return redirect()
            ->route('hpc.show', ['student' => $student, 'term' => $term])
            ->with('status', '३६०° समग्र प्रगती पत्रक (HPC) यशस्वीरित्या जतन झाले!');
    }

    /**
     * Dedicated A4 Print Layout for Class 4 HPC
     */
    public function print(Request $request, Student $student): View
    {
        $term = (int) $request->query('term', 1);
        $academicYear = AcademicYear::where('is_active', true)->first() ?? AcademicYear::first();

        $evaluation = HpcEvaluation::where('student_id', $student->id)
            ->where('academic_year_id', $academicYear?->id ?? 1)
            ->where('term', $term)
            ->firstOrFail();

        $digitalEvidence = $evaluation->getDigitalLearningEvidence();

        return view('hpc.print', [
            'student' => $student->load('user'),
            'evaluation' => $evaluation,
            'term' => $term,
            'academicYear' => $academicYear,
            'digitalEvidence' => $digitalEvidence,
        ]);
    }

    /**
     * Auto-sync all Class 4 students HPC from latest Pre/Post test attempts and games
     */
    public function syncAll(Request $request, HpcAutoCompleteService $service): RedirectResponse
    {
        $term = (int) $request->input('term', 1);
        $academicYear = AcademicYear::where('is_active', true)->first() ?? AcademicYear::first();
        $synced = $service->syncAllClass4Students($academicYear, $term);

        return redirect()
            ->route('hpc.index', ['term' => $term])
            ->with('status', "इयत्ता ४ थी च्या सर्व {$synced} विद्यार्थ्यांचे HPC ३६०° प्रगती पत्रक पूर्व व उत्तर चाचणीनुसार स्वयं-अपडेट झाले!");
    }

    /**
     * Auto-sync a single student's HPC from latest Pre/Post test attempts and games
     */
    public function syncStudent(Request $request, Student $student, HpcAutoCompleteService $service): RedirectResponse
    {
        $term = (int) $request->input('term', 1);
        $academicYear = AcademicYear::where('is_active', true)->first() ?? AcademicYear::first();
        $service->syncForStudent($student, $academicYear, $term);

        return redirect()
            ->route('hpc.show', ['student' => $student, 'term' => $term])
            ->with('status', "विद्यार्थ्याचे HPC ३६०° प्रगती पत्रक पूर्व व उत्तर चाचणी निकालांनुसार यशस्वीरित्या स्वयं-अपडेट झाले!");
    }

    /**
     * Route for logged in student to view their own HPC card
     */
    public function myCard(Request $request): View|RedirectResponse
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('hpc.index');
        }

        return redirect()->route('hpc.show', $student);
    }
}
