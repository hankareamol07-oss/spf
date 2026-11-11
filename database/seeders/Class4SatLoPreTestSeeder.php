<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Test;
use App\Models\TestQuestion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class4SatLoPreTestSeeder extends Seeder
{
    /**
     * Add Class 4 SAT-on-LO's (ZP Sangli vidyavedhsanglizp.org) adapted MCQ questions
     * into Class 4 Marathi Pre-Test (QuestionId 43..50) and Math Pre-Test (QuestionId 51..59).
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $marathiPre = Test::query()->where('code', 'MARATHI_CLASS4_PRE')->firstOrFail();
            $mathPre = Test::query()->where('code', 'MATH_CLASS4_PRE')->firstOrFail();

            $this->attachQuestions($marathiPre, $this->marathiQuestions(), 45);
            $this->attachQuestions($mathPre, $this->mathQuestions(), 55);
        });
    }

    /**
     * @param  list<array<string, mixed>>  $items
     */
    private function attachQuestions(Test $test, array $items, int $newDurationMinutes): void
    {
        $maxSort = (int) TestQuestion::query()->where('test_id', $test->id)->max('sort_order');

        foreach ($items as $item) {
            $existing = $test->questions()
                ->where('questions.prompt_marathi', $item['prompt_marathi'])
                ->first();

            if ($existing !== null) {
                continue;
            }

            $question = Question::query()->create([
                'skill_id' => $item['skill_id'],
                'learning_outcome_id' => $item['learning_outcome_id'],
                'error_type_id' => $item['error_type_id'],
                'created_by' => $test->created_by ?? 1,
                'type' => 'mcq',
                'prompt' => $item['prompt'],
                'prompt_marathi' => $item['prompt_marathi'],
                'correct_answer' => [],
                'explanation' => $item['explanation'],
                'explanation_marathi' => $item['explanation_marathi'],
                'difficulty' => $item['difficulty'] ?? 2,
                'marks' => 1,
                'is_active' => true,
            ]);

            foreach ($item['options'] as $idx => $opt) {
                QuestionOption::query()->create([
                    'question_id' => $question->id,
                    'label' => $opt['label'],
                    'label_marathi' => $opt['label_marathi'],
                    'is_correct' => (bool) ($opt['is_correct'] ?? false),
                    'sort_order' => $idx + 1,
                ]);
            }

            $maxSort++;
            TestQuestion::query()->create([
                'test_id' => $test->id,
                'question_id' => $question->id,
                'sort_order' => $maxSort,
                'marks' => 1,
            ]);
        }

        $activeCount = $test->questions()->where('is_active', true)->count();
        $test->update([
            'question_count' => $activeCount,
            'duration_minutes' => max((int) $test->duration_minutes, $newDurationMinutes),
        ]);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function marathiQuestions(): array
    {
        return [
            // SAT Q43 (CompetencyId 54): अक्षर स्तर - के, सै, पो, फु, तू, मॅ, टो, मौ, औ, सि
            [
                'skill_id' => 1,
                'learning_outcome_id' => 14,
                'error_type_id' => 1,
                'prompt' => 'SAT LO Q43: Which vowel is in the list (के, सै, पो, फु, तू, मॅ, टो, मौ, औ, सि)?',
                'prompt_marathi' => '‘के, सै, पो, फु, तू, मॅ, टो, मौ, औ, सि’ यांपैकी मूळ स्वर कोणता?',
                'explanation' => 'औ is a Marathi vowel (स्वर); the others have consonant-vowel combinations.',
                'explanation_marathi' => '‘औ’ हा स्वतंत्र स्वर आहे, तर इतर सर्व व्यंजन व स्वरचिन्ह जोडलेली अक्षरे आहेत.',
                'options' => [
                    ['label' => 'के', 'label_marathi' => 'के', 'is_correct' => false],
                    ['label' => 'औ', 'label_marathi' => 'औ', 'is_correct' => true],
                    ['label' => 'टो', 'label_marathi' => 'टो', 'is_correct' => false],
                    ['label' => 'सि', 'label_marathi' => 'सि', 'is_correct' => false],
                ],
            ],
            [
                'skill_id' => 1,
                'learning_outcome_id' => 14,
                'error_type_id' => 1,
                'prompt' => 'SAT LO Q43: Select the letter सै.',
                'prompt_marathi' => '‘के, सै, पो, फु, तू, मॅ, टो, मौ, औ, सि’ या यादीतून ‘सै’ हे अक्षर निवडा.',
                'explanation' => 'स + ऐ = सै (दोन मात्रेचे अक्षर).',
                'explanation_marathi' => '‘स’ ला ऐ ची दोन मात्रा लागून ‘सै’ हे अक्षर तयार होते.',
                'options' => [
                    ['label' => 'से', 'label_marathi' => 'से', 'is_correct' => false],
                    ['label' => 'सा', 'label_marathi' => 'सा', 'is_correct' => false],
                    ['label' => 'सै', 'label_marathi' => 'सै', 'is_correct' => true],
                    ['label' => 'सि', 'label_marathi' => 'सि', 'is_correct' => false],
                ],
            ],
            [
                'skill_id' => 4,
                'learning_outcome_id' => 14,
                'error_type_id' => 1,
                'prompt' => 'SAT LO Q43: Which vowel sign is attached to तू?',
                'prompt_marathi' => '‘तू’ या अक्षरात कोणती मात्रा (स्वरचिन्ह) आहे?',
                'explanation' => 'तू has the long उ (दीर्घ ऊ / दुसरा उकार) matra.',
                'explanation_marathi' => '‘तू’ या अक्षराला दीर्घ ऊ (दुसरा उकार) हे स्वरचिन्ह लागले आहे.',
                'options' => [
                    ['label' => 'दीर्घ ऊ', 'label_marathi' => 'दीर्घ ऊ', 'is_correct' => true],
                    ['label' => 'ऱ्हस्व इ', 'label_marathi' => 'ऱ्हस्व इ', 'is_correct' => false],
                    ['label' => 'दीर्घ ई', 'label_marathi' => 'दीर्घ ई', 'is_correct' => false],
                    ['label' => 'अनुस्वार', 'label_marathi' => 'अनुस्वार', 'is_correct' => false],
                ],
            ],
            // SAT Q44 (CompetencyId 55): शब्द वाचन - सावली, पतंग, वैशाख, थंडी, पाऊस, सैनिक, सुंदर, कपडे, वासरु
            [
                'skill_id' => 9,
                'learning_outcome_id' => 14,
                'error_type_id' => 5,
                'prompt' => 'SAT LO Q44: Fill the blank to form सावली.',
                'prompt_marathi' => 'योग्य अक्षर भरून शब्द पूर्ण करा: सा__ली',
                'explanation' => 'सा + व + ली = सावली (shadow).',
                'explanation_marathi' => 'सा + व + ली मिळून ‘सावली’ हा अर्थपूर्ण शब्द तयार होतो.',
                'options' => [
                    ['label' => 'व', 'label_marathi' => 'व', 'is_correct' => true],
                    ['label' => 'क', 'label_marathi' => 'क', 'is_correct' => false],
                    ['label' => 'ग', 'label_marathi' => 'ग', 'is_correct' => false],
                    ['label' => 'ज', 'label_marathi' => 'ज', 'is_correct' => false],
                ],
            ],
            [
                'skill_id' => 9,
                'learning_outcome_id' => 14,
                'error_type_id' => 5,
                'prompt' => 'SAT LO Q44: Select the correctly spelled word पतंग.',
                'prompt_marathi' => 'शुद्धलेखनानुसार ‘पतंग’ हा योग्य शब्द निवडा.',
                'explanation' => 'पतंग has anusvara on त (तं).',
                'explanation_marathi' => '‘पतंग’ या शब्दात ‘त’ वर अनुस्वार असतो.',
                'options' => [
                    ['label' => 'पंतग', 'label_marathi' => 'पंतग', 'is_correct' => false],
                    ['label' => 'पतंग', 'label_marathi' => 'पतंग', 'is_correct' => true],
                    ['label' => 'पातग', 'label_marathi' => 'पातग', 'is_correct' => false],
                    ['label' => 'पतन', 'label_marathi' => 'पतन', 'is_correct' => false],
                ],
            ],
            [
                'skill_id' => 9,
                'learning_outcome_id' => 14,
                'error_type_id' => 5,
                'prompt' => 'SAT LO Q44: Fill the blank in सै__क.',
                'prompt_marathi' => 'योग्य अक्षर भरून शब्द पूर्ण करा: सै__क',
                'explanation' => 'सै + नि + क = सैनिक (soldier).',
                'explanation_marathi' => 'सै + नि + क मिळून ‘सैनिक’ हा शब्द बनतो.',
                'options' => [
                    ['label' => 'ली', 'label_marathi' => 'ली', 'is_correct' => false],
                    ['label' => 'वा', 'label_marathi' => 'वा', 'is_correct' => false],
                    ['label' => 'नि', 'label_marathi' => 'नि', 'is_correct' => true],
                    ['label' => 'पा', 'label_marathi' => 'पा', 'is_correct' => false],
                ],
            ],
            // SAT Q45 (CompetencyId 56): जोडाक्षर युक्त शब्द - प्रार्थना, चैत्र, श्रावणी, निसर्ग, कष्ट, शब्द, प्रेम, सूर्य
            [
                'skill_id' => 6,
                'learning_outcome_id' => 14,
                'error_type_id' => 5,
                'prompt' => 'SAT LO Q45: Which word contains a conjunct consonant (जोडाक्षर)?',
                'prompt_marathi' => 'खालीलपैकी जोडाक्षर असलेला शब्द कोणता?',
                'explanation' => 'सूर्य contains the conjunct र्य (र् + य).',
                'explanation_marathi' => '‘सूर्य’ या शब्दात ‘र्य’ (र् + य) हे जोडाक्षर आहे.',
                'options' => [
                    ['label' => 'कपडे', 'label_marathi' => 'कपडे', 'is_correct' => false],
                    ['label' => 'पाऊस', 'label_marathi' => 'पाऊस', 'is_correct' => false],
                    ['label' => 'पतंग', 'label_marathi' => 'पतंग', 'is_correct' => false],
                    ['label' => 'सूर्य', 'label_marathi' => 'सूर्य', 'is_correct' => true],
                ],
            ],
            [
                'skill_id' => 6,
                'learning_outcome_id' => 14,
                'error_type_id' => 5,
                'prompt' => 'SAT LO Q45: Identify the conjunct consonant in कष्ट.',
                'prompt_marathi' => '‘कष्ट’ या शब्दातील जोडाक्षर कोणते?',
                'explanation' => 'ष्ट (ष् + ट) is the conjunct in कष्ट.',
                'explanation_marathi' => '‘कष्ट’ या शब्दात ‘ष्ट’ (ष् + ट) हे जोडाक्षर आहे.',
                'options' => [
                    ['label' => 'ष्ट', 'label_marathi' => 'ष्ट', 'is_correct' => true],
                    ['label' => 'क', 'label_marathi' => 'क', 'is_correct' => false],
                    ['label' => 'श', 'label_marathi' => 'श', 'is_correct' => false],
                    ['label' => 'ट', 'label_marathi' => 'ट', 'is_correct' => false],
                ],
            ],
            [
                'skill_id' => 6,
                'learning_outcome_id' => 14,
                'error_type_id' => 5,
                'prompt' => 'SAT LO Q45: Identify the starting conjunct in प्रेम.',
                'prompt_marathi' => '‘प्रेम’ या शब्दातील सुरुवातीचे जोडाक्षर निवडा.',
                'explanation' => 'प्रे (प्र + ए) begins with the conjunct प्र.',
                'explanation_marathi' => '‘प्रेम’ या शब्दाची सुरुवात ‘प्र’ (प् + र) या जोडाक्षराने होते.',
                'options' => [
                    ['label' => 'प', 'label_marathi' => 'प', 'is_correct' => false],
                    ['label' => 'प्र', 'label_marathi' => 'प्र', 'is_correct' => true],
                    ['label' => 'म', 'label_marathi' => 'म', 'is_correct' => false],
                    ['label' => 'र', 'label_marathi' => 'र', 'is_correct' => false],
                ],
            ],
            // SAT Q46 (CompetencyId 57): वाक्य वाचन
            [
                'skill_id' => 10,
                'learning_outcome_id' => 15,
                'error_type_id' => 4,
                'prompt' => 'SAT LO Q46: Read the sentence about winter clothes and answer.',
                'prompt_marathi' => 'वाचा: ‘हिवाळ्यात उबदार कपडे घालतात.’ हिवाळ्यात कसे कपडे घालतात?',
                'explanation' => 'The sentence states warm (उबदार) clothes are worn in winter.',
                'explanation_marathi' => 'वाक्यात स्पष्ट दिले आहे की हिवाळ्यात उबदार कपडे घालतात.',
                'options' => [
                    ['label' => 'ओले', 'label_marathi' => 'ओले', 'is_correct' => false],
                    ['label' => 'पातळ', 'label_marathi' => 'पातळ', 'is_correct' => false],
                    ['label' => 'उबदार', 'label_marathi' => 'उबदार', 'is_correct' => true],
                    ['label' => 'फाटके', 'label_marathi' => 'फाटके', 'is_correct' => false],
                ],
            ],
            [
                'skill_id' => 10,
                'learning_outcome_id' => 15,
                'error_type_id' => 4,
                'prompt' => 'SAT LO Q46: Read "मुलांनी अभ्यास केला." What did the children do?',
                'prompt_marathi' => 'वाचा: ‘मुलांनी अभ्यास केला.’ या वाक्यात मुलांनी काय केले?',
                'explanation' => 'The children studied (अभ्यास).',
                'explanation_marathi' => 'वाक्यानुसार मुलांनी अभ्यास केला.',
                'options' => [
                    ['label' => 'अभ्यास', 'label_marathi' => 'अभ्यास', 'is_correct' => true],
                    ['label' => 'नाच', 'label_marathi' => 'नाच', 'is_correct' => false],
                    ['label' => 'प्रवास', 'label_marathi' => 'प्रवास', 'is_correct' => false],
                    ['label' => 'स्वयंपाक', 'label_marathi' => 'स्वयंपाक', 'is_correct' => false],
                ],
            ],
            // SAT Q47 (CompetencyId 58): उतारा वाचन (5 lines Class 4 level comprehension)
            [
                'skill_id' => 15,
                'learning_outcome_id' => 14,
                'error_type_id' => 3,
                'prompt' => 'SAT LO Q47: Read the 5-line passage and find where the cuckoo sings.',
                'prompt_marathi' => 'उतारा वाचा: “चैत्र महिन्यात निसर्ग हिरवागार दिसतो. आंब्याच्या झाडावर कोकिळा गोड गाते. थंड सावलीत वासरू खेळते. मुले मैदानात पतंग उडवतात. संध्याकाळी सर्वजण घरी परततात.” कोकिळा कोठे बसून गोड गाते?',
                'explanation' => 'The passage says the cuckoo sings on the mango tree (आंब्याच्या झाडावर).',
                'explanation_marathi' => 'उताऱ्यात ‘आंब्याच्या झाडावर कोकिळा गोड गाते’ असे दिले आहे.',
                'options' => [
                    ['label' => 'आंब्याच्या झाडावर', 'label_marathi' => 'आंब्याच्या झाडावर', 'is_correct' => true],
                    ['label' => 'नदीकिनारी', 'label_marathi' => 'नदीकिनारी', 'is_correct' => false],
                    ['label' => 'शाळेच्या छतावर', 'label_marathi' => 'शाळेच्या छतावर', 'is_correct' => false],
                    ['label' => 'डोंगरावर', 'label_marathi' => 'डोंगरावर', 'is_correct' => false],
                ],
            ],
            [
                'skill_id' => 14,
                'learning_outcome_id' => 15,
                'error_type_id' => 4,
                'prompt' => 'SAT LO Q47: Based on the same passage, what do the children fly in the ground?',
                'prompt_marathi' => 'वरील उताऱ्यानुसार मुले मैदानात काय उडवतात?',
                'explanation' => 'Children fly kites (पतंग) in the ground.',
                'explanation_marathi' => 'उताऱ्यात दिले आहे की मुले मैदानात पतंग उडवतात.',
                'options' => [
                    ['label' => 'फुगे', 'label_marathi' => 'फुगे', 'is_correct' => false],
                    ['label' => 'पतंग', 'label_marathi' => 'पतंग', 'is_correct' => true],
                    ['label' => 'विमान', 'label_marathi' => 'विमान', 'is_correct' => false],
                    ['label' => 'चेंडू', 'label_marathi' => 'चेंडू', 'is_correct' => false],
                ],
            ],
            // SAT Q48 (CompetencyId 59): अक्षर स्तर (फा, तु, मो, रा, सै, मौ, रु, पो)
            [
                'skill_id' => 4,
                'learning_outcome_id' => 16,
                'error_type_id' => 1,
                'prompt' => 'SAT LO Q48: Which character from (फा, तु, मो, रा, सै, मौ, रु, पो) uses the ओ matra?',
                'prompt_marathi' => '‘फा, तु, मो, रा, सै, मौ, रु, पो’ यांपैकी म ला ‘ओ’ ची मात्रा लागून कोणते अक्षर बनले आहे?',
                'explanation' => 'म् + ओ = मो.',
                'explanation_marathi' => '‘म’ ला एक काना व एक मात्रा लागून ‘मो’ हे अक्षर तयार होते.',
                'options' => [
                    ['label' => 'मौ', 'label_marathi' => 'मौ', 'is_correct' => false],
                    ['label' => 'मो', 'label_marathi' => 'मो', 'is_correct' => true],
                    ['label' => 'सै', 'label_marathi' => 'सै', 'is_correct' => false],
                    ['label' => 'तु', 'label_marathi' => 'तु', 'is_correct' => false],
                ],
            ],
            // SAT Q49 (CompetencyId 60): शब्द अचूकता - बैल, अंगत, पतंग, सावत्र, गर्व, चिमण्या
            [
                'skill_id' => 7,
                'learning_outcome_id' => 16,
                'error_type_id' => 5,
                'prompt' => 'SAT LO Q49: Choose the correctly spelled word चिमण्या.',
                'prompt_marathi' => 'खालीलपैकी अचूक लिहिलेला शब्द निवडा.',
                'explanation' => 'चिमण्या (sparrows) uses short ि on च and conjunct ण्य.',
                'explanation_marathi' => '‘चिमण्या’ हा शुद्धलेखनानुसार बरोबर शब्द आहे.',
                'options' => [
                    ['label' => 'चिमण्या', 'label_marathi' => 'चिमण्या', 'is_correct' => true],
                    ['label' => 'चिमन्या', 'label_marathi' => 'चिमन्या', 'is_correct' => false],
                    ['label' => 'चीमण्या', 'label_marathi' => 'चीमण्या', 'is_correct' => false],
                    ['label' => 'चिमणा', 'label_marathi' => 'चिमणा', 'is_correct' => false],
                ],
            ],
            [
                'skill_id' => 7,
                'learning_outcome_id' => 16,
                'error_type_id' => 5,
                'prompt' => 'SAT LO Q49: Which word from the list contains a रफार (र्)?',
                'prompt_marathi' => '‘सैनिक, गर्व, बैल, पतंग’ यांपैकी रफार (र्) असलेला शब्द कोणता?',
                'explanation' => 'गर्व has the rafar on व (र्व).',
                'explanation_marathi' => '‘गर्व’ या शब्दातील ‘व’ वर रफार (र्) आहे.',
                'options' => [
                    ['label' => 'सैनिक', 'label_marathi' => 'सैनिक', 'is_correct' => false],
                    ['label' => 'बैल', 'label_marathi' => 'बैल', 'is_correct' => false],
                    ['label' => 'गर्व', 'label_marathi' => 'गर्व', 'is_correct' => true],
                    ['label' => 'पतंग', 'label_marathi' => 'पतंग', 'is_correct' => false],
                ],
            ],
            // SAT Q50 (CompetencyId 61): वाक्य स्तर - कोल्हा जंगलात शिकार शोधत होता.
            [
                'skill_id' => 11,
                'learning_outcome_id' => 17,
                'error_type_id' => 6,
                'prompt' => 'SAT LO Q50: Read "कोल्हा जंगलात शिकार शोधत होता." Where was the fox searching for prey?',
                'prompt_marathi' => 'वाचा: ‘कोल्हा जंगलात शिकार शोधत होता.’ कोल्हा शिकार कोठे शोधत होता?',
                'explanation' => 'The fox was searching in the forest (जंगलात).',
                'explanation_marathi' => 'वाक्यात कोल्हा ‘जंगलात’ शिकार शोधत असल्याचे सांगितले आहे.',
                'options' => [
                    ['label' => 'शाळेत', 'label_marathi' => 'शाळेत', 'is_correct' => false],
                    ['label' => 'बाजारात', 'label_marathi' => 'बाजारात', 'is_correct' => false],
                    ['label' => 'घरात', 'label_marathi' => 'घरात', 'is_correct' => false],
                    ['label' => 'जंगलात', 'label_marathi' => 'जंगलात', 'is_correct' => true],
                ],
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function mathQuestions(): array
    {
        return [
            // SAT Q51 (CompetencyId 62): संख्या ओळख - २४४, ३३१, ७०५, ८३६, २३१६, ५४६५, १०००, ९०१
            [
                'skill_id' => 26,
                'learning_outcome_id' => 1,
                'error_type_id' => 9,
                'prompt' => 'SAT LO Q51: Choose the number for seven hundred five.',
                'prompt_marathi' => '‘सातशे पाच’ ही संख्या अंकात निवडा.',
                'explanation' => 'Seven hundred five is 705 (७०५).',
                'explanation_marathi' => 'सात शतक, शून्य दशक व पाच एकक म्हणजे ७०५.',
                'options' => [
                    ['label' => '750', 'label_marathi' => '७५०', 'is_correct' => false],
                    ['label' => '705', 'label_marathi' => '७०५', 'is_correct' => true],
                    ['label' => '75', 'label_marathi' => '७५', 'is_correct' => false],
                    ['label' => '507', 'label_marathi' => '५०७', 'is_correct' => false],
                ],
            ],
            [
                'skill_id' => 26,
                'learning_outcome_id' => 1,
                'error_type_id' => 9,
                'prompt' => 'SAT LO Q51: Choose two thousand three hundred sixteen.',
                'prompt_marathi' => '‘दोन हजार तीनशे सोळा’ ही संख्या कोणती?',
                'explanation' => '2 thousands + 3 hundreds + 1 ten + 6 units = 2316.',
                'explanation_marathi' => 'दोन हजार तीनशे सोळा म्हणजे २३१६.',
                'options' => [
                    ['label' => '2361', 'label_marathi' => '२३६१', 'is_correct' => false],
                    ['label' => '2136', 'label_marathi' => '२१३६', 'is_correct' => false],
                    ['label' => '2316', 'label_marathi' => '२३१६', 'is_correct' => true],
                    ['label' => '236', 'label_marathi' => '२३६', 'is_correct' => false],
                ],
            ],
            [
                'skill_id' => 26,
                'learning_outcome_id' => 1,
                'error_type_id' => 9,
                'prompt' => 'SAT LO Q51: Choose five thousand four hundred sixty-five.',
                'prompt_marathi' => '‘पाच हजार चारशे पासष्ट’ ही संख्या निवडा.',
                'explanation' => 'Five thousand four hundred sixty-five is 5465.',
                'explanation_marathi' => 'पाच हजार चारशे पासष्ट म्हणजे ५४६५.',
                'options' => [
                    ['label' => '5465', 'label_marathi' => '५४६५', 'is_correct' => true],
                    ['label' => '5456', 'label_marathi' => '५४५६', 'is_correct' => false],
                    ['label' => '5645', 'label_marathi' => '५६४५', 'is_correct' => false],
                    ['label' => '546', 'label_marathi' => '५४६', 'is_correct' => false],
                ],
            ],
            [
                'skill_id' => 27,
                'learning_outcome_id' => 2,
                'error_type_id' => 10,
                'prompt' => 'SAT LO Q51: Identify the smallest 4-digit number from the list.',
                'prompt_marathi' => '‘२४४, ३३१, ७०५, ८३६, २३१६, ५४६५, १०००, ९०१’ यांपैकी चार अंकी सर्वात लहान संख्या कोणती?',
                'explanation' => 'Among 1000, 2316, 5465, the smallest 4-digit number is 1000.',
                'explanation_marathi' => '१००० ही सर्वात लहान चार अंकी संख्या आहे (९०१ तीन अंकी आहे).',
                'options' => [
                    ['label' => '901', 'label_marathi' => '९०१', 'is_correct' => false],
                    ['label' => '1000', 'label_marathi' => '१०००', 'is_correct' => true],
                    ['label' => '2316', 'label_marathi' => '२३१६', 'is_correct' => false],
                    ['label' => '836', 'label_marathi' => '८३६', 'is_correct' => false],
                ],
            ],
            // SAT Q52 (CompetencyId 63): बेरीज (संख्या)
            [
                'skill_id' => 30,
                'learning_outcome_id' => 3,
                'error_type_id' => 11,
                'prompt' => 'SAT LO Q52: 536 + 247 = ?',
                'prompt_marathi' => '५३६ + २४७ = ?',
                'explanation' => '536 + 247 = 783.',
                'explanation_marathi' => '६ + ७ = १३ (हातचा १); ३ + ४ + १ = ८; ५ + २ = ७ म्हणून उत्तर ७८३.',
                'options' => [
                    ['label' => '783', 'label_marathi' => '७८३', 'is_correct' => true],
                    ['label' => '773', 'label_marathi' => '७७३', 'is_correct' => false],
                    ['label' => '883', 'label_marathi' => '८८३', 'is_correct' => false],
                    ['label' => '782', 'label_marathi' => '७८२', 'is_correct' => false],
                ],
            ],
            [
                'skill_id' => 30,
                'learning_outcome_id' => 3,
                'error_type_id' => 11,
                'prompt' => 'SAT LO Q52: 435 + 345 = ?',
                'prompt_marathi' => '४३५ + ३४५ = ?',
                'explanation' => '435 + 345 = 780.',
                'explanation_marathi' => '५ + ५ = १० (हातचा १); ३ + ४ + १ = ८; ४ + ३ = ७ म्हणून उत्तर ७८०.',
                'options' => [
                    ['label' => '770', 'label_marathi' => '७७०', 'is_correct' => false],
                    ['label' => '780', 'label_marathi' => '७८०', 'is_correct' => true],
                    ['label' => '880', 'label_marathi' => '८८०', 'is_correct' => false],
                    ['label' => '680', 'label_marathi' => '६८०', 'is_correct' => false],
                ],
            ],
            // SAT Q53 (CompetencyId 64): बेरीज (शाब्दिक)
            [
                'skill_id' => 30,
                'learning_outcome_id' => 3,
                'error_type_id' => 16,
                'prompt' => 'SAT LO Q53: In a school there are 427 boys and 425 girls. Total students?',
                'prompt_marathi' => 'एका शाळेत ४२७ मुले व ४२५ मुली आहेत, तर एकूण विद्यार्थी किती आहेत?',
                'explanation' => '427 + 425 = 852.',
                'explanation_marathi' => '४२७ + ४२५ = ८५२ एकूण विद्यार्थी.',
                'options' => [
                    ['label' => '842', 'label_marathi' => '८४२', 'is_correct' => false],
                    ['label' => '862', 'label_marathi' => '८६२', 'is_correct' => false],
                    ['label' => '852', 'label_marathi' => '८५२', 'is_correct' => true],
                    ['label' => '851', 'label_marathi' => '८५१', 'is_correct' => false],
                ],
            ],
            // SAT Q54 (CompetencyId 65): वजाबाकी (संख्या)
            [
                'skill_id' => 31,
                'learning_outcome_id' => 4,
                'error_type_id' => 12,
                'prompt' => 'SAT LO Q54: 836 - 345 = ?',
                'prompt_marathi' => '८३६ − ३४५ = ?',
                'explanation' => '836 - 345 = 491.',
                'explanation_marathi' => '८३६ मधून ३४५ वजा केल्यास ४९१ उरतात.',
                'options' => [
                    ['label' => '491', 'label_marathi' => '४९१', 'is_correct' => true],
                    ['label' => '591', 'label_marathi' => '५९१', 'is_correct' => false],
                    ['label' => '481', 'label_marathi' => '४८१', 'is_correct' => false],
                    ['label' => '492', 'label_marathi' => '४९२', 'is_correct' => false],
                ],
            ],
            [
                'skill_id' => 31,
                'learning_outcome_id' => 4,
                'error_type_id' => 12,
                'prompt' => 'SAT LO Q54: 729 - 444 = ?',
                'prompt_marathi' => '७२९ − ४४४ = ?',
                'explanation' => '729 - 444 = 285.',
                'explanation_marathi' => '७२९ − ४४४ = २८५.',
                'options' => [
                    ['label' => '275', 'label_marathi' => '२७५', 'is_correct' => false],
                    ['label' => '285', 'label_marathi' => '२८५', 'is_correct' => true],
                    ['label' => '385', 'label_marathi' => '३८५', 'is_correct' => false],
                    ['label' => '284', 'label_marathi' => '२८४', 'is_correct' => false],
                ],
            ],
            // SAT Q55 (CompetencyId 66): वजाबाकी (शाब्दिक)
            [
                'skill_id' => 31,
                'learning_outcome_id' => 4,
                'error_type_id' => 16,
                'prompt' => 'SAT LO Q55: Out of 1567 sacks in a warehouse, 735 are bajra. Remaining tur sacks?',
                'prompt_marathi' => 'एका गोदामात एकूण १,५६७ पोती आहेत. त्यापैकी बाजरीची ७३५ पोती आहेत, तर उरलेली तुरीची किती पोती असतील?',
                'explanation' => '1567 - 735 = 832.',
                'explanation_marathi' => '१५६७ − ७३५ = ८३२ पोती तुरीची असतील.',
                'options' => [
                    ['label' => '822', 'label_marathi' => '८२२', 'is_correct' => false],
                    ['label' => '932', 'label_marathi' => '९३२', 'is_correct' => false],
                    ['label' => '832', 'label_marathi' => '८३२', 'is_correct' => true],
                    ['label' => '833', 'label_marathi' => '८३३', 'is_correct' => false],
                ],
            ],
            // SAT Q56 (CompetencyId 67): गुणाकार (संख्या)
            [
                'skill_id' => 32,
                'learning_outcome_id' => 5,
                'error_type_id' => 13,
                'prompt' => 'SAT LO Q56: 323 × 3 = ?',
                'prompt_marathi' => '३२३ × ३ = ?',
                'explanation' => '323 × 3 = 969.',
                'explanation_marathi' => '३ × ३ = ९; २ × ३ = ६; ३ × ३ = ९ म्हणून उत्तर ९६९.',
                'options' => [
                    ['label' => '969', 'label_marathi' => '९६९', 'is_correct' => true],
                    ['label' => '959', 'label_marathi' => '९५९', 'is_correct' => false],
                    ['label' => '979', 'label_marathi' => '९७९', 'is_correct' => false],
                    ['label' => '869', 'label_marathi' => '८६९', 'is_correct' => false],
                ],
            ],
            [
                'skill_id' => 32,
                'learning_outcome_id' => 5,
                'error_type_id' => 13,
                'prompt' => 'SAT LO Q56: 121 × 4 = ?',
                'prompt_marathi' => '१२१ × ४ = ?',
                'explanation' => '121 × 4 = 484.',
                'explanation_marathi' => '१ × ४ = ४; २ × ४ = ८; १ × ४ = ४ म्हणून उत्तर ४८४.',
                'options' => [
                    ['label' => '444', 'label_marathi' => '४४४', 'is_correct' => false],
                    ['label' => '484', 'label_marathi' => '४८४', 'is_correct' => true],
                    ['label' => '474', 'label_marathi' => '४७४', 'is_correct' => false],
                    ['label' => '488', 'label_marathi' => '४८८', 'is_correct' => false],
                ],
            ],
            // SAT Q57 (CompetencyId 68): गुणाकार (शाब्दिक)
            [
                'skill_id' => 32,
                'learning_outcome_id' => 5,
                'error_type_id' => 16,
                'prompt' => 'SAT LO Q57: One brush costs ₹7. Cost of 84 brushes?',
                'prompt_marathi' => 'एका ब्रशची किंमत ₹७ याप्रमाणे ८४ ब्रशची किंमत किती रुपये होईल?',
                'explanation' => '84 × 7 = 588 rupees.',
                'explanation_marathi' => '८४ × ७ = ₹५८८.',
                'options' => [
                    ['label' => '578', 'label_marathi' => '५७८', 'is_correct' => false],
                    ['label' => '598', 'label_marathi' => '५९८', 'is_correct' => false],
                    ['label' => '588', 'label_marathi' => '५८८', 'is_correct' => true],
                    ['label' => '488', 'label_marathi' => '४८८', 'is_correct' => false],
                ],
            ],
            // SAT Q58 (CompetencyId 69): भागाकार (संख्या)
            [
                'skill_id' => 33,
                'learning_outcome_id' => 6,
                'error_type_id' => 14,
                'prompt' => 'SAT LO Q58: 369 ÷ 3 = ?',
                'prompt_marathi' => '३६९ ÷ ३ = ?',
                'explanation' => '369 ÷ 3 = 123.',
                'explanation_marathi' => '३६९ ला ३ ने भागल्यास भागाकार १२३ येतो.',
                'options' => [
                    ['label' => '113', 'label_marathi' => '११३', 'is_correct' => false],
                    ['label' => '123', 'label_marathi' => '१२३', 'is_correct' => true],
                    ['label' => '133', 'label_marathi' => '१३३', 'is_correct' => false],
                    ['label' => '122', 'label_marathi' => '१२२', 'is_correct' => false],
                ],
            ],
            // SAT Q59 (CompetencyId 70): भागाकार (शाब्दिक)
            [
                'skill_id' => 33,
                'learning_outcome_id' => 6,
                'error_type_id' => 16,
                'prompt' => 'SAT LO Q59: Ovi has 456 flowers and makes bouquets of 4 flowers each. Total bouquets?',
                'prompt_marathi' => 'ओवीकडे ४५६ फुले आहेत. तिने ४ फुलांचा एक गुच्छ याप्रमाणे एकूण किती गुच्छ बनवले असतील?',
                'explanation' => '456 ÷ 4 = 114 bouquets.',
                'explanation_marathi' => '४५६ ÷ ४ = ११४ गुच्छ तयार होतील.',
                'options' => [
                    ['label' => '114', 'label_marathi' => '११४', 'is_correct' => true],
                    ['label' => '104', 'label_marathi' => '१०४', 'is_correct' => false],
                    ['label' => '124', 'label_marathi' => '१२४', 'is_correct' => false],
                    ['label' => '116', 'label_marathi' => '११६', 'is_correct' => false],
                ],
            ],
            [
                'skill_id' => 39,
                'learning_outcome_id' => 13,
                'error_type_id' => 16,
                'prompt' => 'SAT LO Q59: Which operation solves sharing 456 flowers into groups of 4?',
                'prompt_marathi' => '४५६ फुलांचे प्रत्येकी ४ फुलांचे गुच्छ बनवण्यासाठी कोणती गणिती क्रिया करावी?',
                'explanation' => 'Equal grouping uses division: 456 ÷ 4.',
                'explanation_marathi' => 'समान गट करण्यासाठी भागाकाराची क्रिया (४५६ ÷ ४) करावी लागते.',
                'options' => [
                    ['label' => '456 + 4', 'label_marathi' => '४५६ + ४', 'is_correct' => false],
                    ['label' => '456 - 4', 'label_marathi' => '४५६ − ४', 'is_correct' => false],
                    ['label' => '456 x 4', 'label_marathi' => '४५६ × ४', 'is_correct' => false],
                    ['label' => '456 ÷ 4', 'label_marathi' => '४५६ ÷ ४', 'is_correct' => true],
                ],
            ],
        ];
    }
}
