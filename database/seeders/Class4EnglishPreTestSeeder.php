<?php

namespace Database\Seeders;

use App\Models\ErrorType;
use App\Models\LearningOutcome;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Skill;
use App\Models\Subject;
use App\Models\Test;
use App\Models\TestQuestion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Class4EnglishPreTestSeeder extends Seeder
{
    /**
     * Seed the Class 4 English subject, skills, learning outcomes, error types,
     * and baseline Pre-Test (ENGLISH_CLASS4_PRE) adapted from SAT-on-LO's QuestionId 60..66.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $english = Subject::query()->updateOrCreate(
                ['school_id' => null, 'code' => 'ENGLISH'],
                [
                    'name' => 'English',
                    'name_marathi' => 'इंग्रजी',
                    'description' => 'Class 4 foundational English reading, vocabulary and writing skills.',
                    'sort_order' => 3,
                    'is_active' => true,
                ],
            );

            $skills = [];
            foreach ($this->skillDefinitions() as $index => $def) {
                $skills[$def['code']] = Skill::query()->updateOrCreate(
                    ['subject_id' => $english->id, 'code' => $def['code']],
                    [
                        'parent_skill_id' => null,
                        'name' => $def['name'],
                        'name_marathi' => $def['name_marathi'],
                        'description' => $def['description'],
                        'sort_order' => $index + 1,
                        'is_active' => true,
                    ],
                );
            }

            $outcomes = [];
            foreach ($this->outcomeDefinitions() as $index => $def) {
                $outcomes[$def['code']] = LearningOutcome::query()->updateOrCreate(
                    ['code' => $def['code']],
                    [
                        'subject_id' => $english->id,
                        'skill_id' => $skills[$def['skill_code']]->id,
                        'grade_level' => 4,
                        'statement' => $def['statement'],
                        'statement_marathi' => $def['statement_marathi'],
                        'competency' => $def['competency'],
                        'competency_marathi' => $def['competency_marathi'],
                        'sort_order' => $index + 1,
                        'is_active' => true,
                    ],
                );
            }

            $errors = [];
            foreach ($this->errorDefinitions() as $def) {
                $errors[$def['code']] = ErrorType::query()->updateOrCreate(
                    ['code' => $def['code']],
                    [
                        'skill_id' => $skills[$def['skill_code']]->id,
                        'name' => $def['name'],
                        'name_marathi' => $def['name_marathi'],
                        'description' => 'The response indicates that this Class 4 English competency needs targeted support.',
                        'remediation' => ['hint' => $def['hint']],
                    ],
                );
            }

            $test = Test::query()->updateOrCreate(
                ['code' => 'ENGLISH_CLASS4_PRE'],
                [
                    'school_id' => 1,
                    'academic_year_id' => 1,
                    'school_class_id' => 1,
                    'subject_id' => $english->id,
                    'created_by' => 1,
                    'type' => 'pre_test',
                    'title' => 'Class 4 English baseline',
                    'title_marathi' => 'इयत्ता चौथी इंग्रजी पूर्व चाचणी',
                    'instructions' => 'Answer every question without help.',
                    'instructions_marathi' => 'मदतीशिवाय प्रत्येक प्रश्न सोडवा.',
                    'duration_minutes' => 30,
                    'max_attempts' => 1,
                    'passing_score' => 60,
                    'shuffle_questions' => false,
                    'status' => 'published',
                    'difficulty' => 4,
                    'question_count' => 16,
                ],
            );

            $maxSort = (int) TestQuestion::query()->where('test_id', $test->id)->max('sort_order');

            foreach ($this->questionDefinitions() as $item) {
                $existing = $test->questions()
                    ->where('questions.prompt_marathi', $item['prompt_marathi'])
                    ->first();

                if ($existing !== null) {
                    continue;
                }

                $question = Question::query()->create([
                    'skill_id' => $skills[$item['skill_code']]->id,
                    'learning_outcome_id' => $outcomes[$item['lo_code']]->id,
                    'error_type_id' => $errors[$item['error_code']]->id,
                    'created_by' => $test->created_by ?? 1,
                    'type' => 'mcq',
                    'prompt' => $item['prompt'],
                    'prompt_marathi' => $item['prompt_marathi'],
                    'correct_answer' => [],
                    'explanation' => $item['explanation'],
                    'explanation_marathi' => $item['explanation_marathi'],
                    'difficulty' => $item['difficulty'],
                    'marks' => 1,
                    'is_active' => true,
                ]);

                foreach ($item['options'] as $idx => $opt) {
                    QuestionOption::query()->create([
                        'question_id' => $question->id,
                        'label' => $opt['label'],
                        'label_marathi' => $opt['label_marathi'],
                        'is_correct' => (bool) $opt['is_correct'],
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

            $activeCount = $test->questions()->where('questions.is_active', true)->count();
            $test->forceFill([
                'question_count' => $activeCount,
                'duration_minutes' => 30,
            ])->save();
        });
    }

    /**
     * @return list<array<string, string>>
     */
    private function skillDefinitions(): array
    {
        return [
            [
                'code' => 'ENG_PICTURE_VOCABULARY',
                'name' => 'Picture reading & vocabulary',
                'name_marathi' => 'चित्रवाचन व शब्दओळख',
                'description' => 'Associates familiar pictures and objects with spoken and written English words.',
            ],
            [
                'code' => 'ENG_LETTER_RECOGNITION',
                'name' => 'Letter recognition',
                'name_marathi' => 'अक्षर ओळख (इंग्रजी)',
                'description' => 'Identifies uppercase and lowercase English letters and vowel-consonant sounds.',
            ],
            [
                'code' => 'ENG_WORD_READING',
                'name' => 'Word reading & spelling',
                'name_marathi' => 'शब्द वाचन व शुद्धलेखन',
                'description' => 'Reads simple CVC/familiar English words and chooses accurate spellings.',
            ],
            [
                'code' => 'ENG_PARAGRAPH_READING',
                'name' => 'Passage reading & comprehension',
                'name_marathi' => 'उतारा वाचन व आकलन',
                'description' => 'Reads short English sentences and extracts direct factual details.',
            ],
            [
                'code' => 'ENG_LETTER_WRITING',
                'name' => 'Letter formation & dictation',
                'name_marathi' => 'अक्षर लेखन (श्रुतलेखन)',
                'description' => 'Matches uppercase and lowercase letter forms and alphabetical order.',
            ],
            [
                'code' => 'ENG_SENTENCE_WRITING',
                'name' => 'Sentence structure & writing',
                'name_marathi' => 'वाक्य रचना व लेखन',
                'description' => 'Recognizes proper capitalization, verb agreement, and end punctuation in simple sentences.',
            ],
        ];
    }

    /**
     * @return list<array<string, string>>
     */
    private function outcomeDefinitions(): array
    {
        return [
            [
                'code' => 'STD4-ENG-01',
                'skill_code' => 'ENG_PICTURE_VOCABULARY',
                'statement' => 'Names familiar objects, animals and pictures in English.',
                'statement_marathi' => 'परिचित चित्रे, वस्तू व प्राणी ओळखून त्यांची नावे इंग्रजीत सांगतो.',
                'competency' => 'Picture reading',
                'competency_marathi' => 'चित्रवाचन',
            ],
            [
                'code' => 'STD4-ENG-02',
                'skill_code' => 'ENG_LETTER_RECOGNITION',
                'statement' => 'Recognizes capital and small English letters accurately.',
                'statement_marathi' => 'इंग्रजी कॅपिटल व स्मॉल अक्षरे अचूक ओळखतो व वाचतो.',
                'competency' => 'Letter reading',
                'competency_marathi' => 'अक्षर वाचन',
            ],
            [
                'code' => 'STD4-ENG-03',
                'skill_code' => 'ENG_WORD_READING',
                'statement' => 'Reads and identifies correct spelling of familiar English words.',
                'statement_marathi' => 'सोपे व परिचित इंग्रजी शब्द वाचतो व अचूक स्पेलिंग निवडतो.',
                'competency' => 'Word reading & spelling',
                'competency_marathi' => 'शब्द वाचन व लेखन',
            ],
            [
                'code' => 'STD4-ENG-04',
                'skill_code' => 'ENG_PARAGRAPH_READING',
                'statement' => 'Reads short English passages and answers simple comprehension questions.',
                'statement_marathi' => 'लहान इंग्रजी उतारा वाचून त्यावरील साध्या प्रश्नांची अचूक उत्तरे देतो.',
                'competency' => 'Paragraph comprehension',
                'competency_marathi' => 'उतारा वाचन व आकलन',
            ],
            [
                'code' => 'STD4-ENG-05',
                'skill_code' => 'ENG_LETTER_WRITING',
                'statement' => 'Identifies small-capital pairs and alphabetical order from dictation sets.',
                'statement_marathi' => 'श्रुतलेखनातील अक्षरांची लहान-मोठी रूपे व वर्णक्रम अचूक ओळखतो.',
                'competency' => 'Letter writing',
                'competency_marathi' => 'अक्षर लेखन (श्रुतलेखन)',
            ],
            [
                'code' => 'STD4-ENG-06',
                'skill_code' => 'ENG_SENTENCE_WRITING',
                'statement' => 'Recognizes grammatically correct simple English sentences with punctuation.',
                'statement_marathi' => 'सोपी इंग्रजी वाक्ये अचूक व्याकरण व विरामचिन्हांसह ओळखतो.',
                'competency' => 'Sentence writing',
                'competency_marathi' => 'वाक्य लेखन',
            ],
        ];
    }

    /**
     * @return list<array<string, string>>
     */
    private function errorDefinitions(): array
    {
        return [
            [
                'code' => 'CLASS4_ENG_PICTURE_VOCAB_ERROR',
                'skill_code' => 'ENG_PICTURE_VOCABULARY',
                'name' => 'Picture vocabulary misconception',
                'name_marathi' => 'चित्र-शब्द ओळख चूक',
                'hint' => 'Practice matching flashcards of everyday objects, fruits and animals with English words.',
            ],
            [
                'code' => 'CLASS4_ENG_LETTER_CONFUSION',
                'skill_code' => 'ENG_LETTER_RECOGNITION',
                'name' => 'English letter recognition confusion',
                'name_marathi' => 'इंग्रजी अक्षर ओळख गोंधळ',
                'hint' => 'Compare capital and small letter pairs side-by-side and sort vowels (A, E, I, O, U).',
            ],
            [
                'code' => 'CLASS4_ENG_WORD_SPELLING_ERROR',
                'skill_code' => 'ENG_WORD_READING',
                'name' => 'English word spelling misconception',
                'name_marathi' => 'इंग्रजी शब्द लेखन चूक',
                'hint' => 'Break short CVC words into beginning, middle vowel and ending sounds before choosing spelling.',
            ],
            [
                'code' => 'CLASS4_ENG_COMPREHENSION_ERROR',
                'skill_code' => 'ENG_PARAGRAPH_READING',
                'name' => 'English passage comprehension misconception',
                'name_marathi' => 'इंग्रजी उतारा आकलन चूक',
                'hint' => 'Read one sentence at a time aloud and underline key nouns and action words.',
            ],
            [
                'code' => 'CLASS4_ENG_SENTENCE_SYNTAX_ERROR',
                'skill_code' => 'ENG_SENTENCE_WRITING',
                'name' => 'English sentence syntax misconception',
                'name_marathi' => 'इंग्रजी वाक्य रचना चूक',
                'hint' => 'Check for a capital first letter, matching "is/am/are" verb, and a full stop at the end.',
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function questionDefinitions(): array
    {
        return [
            // Competency 71: Picture Reading [PICTURE READING]
            [
                'skill_code' => 'ENG_PICTURE_VOCABULARY',
                'lo_code' => 'STD4-ENG-01',
                'error_code' => 'CLASS4_ENG_PICTURE_VOCAB_ERROR',
                'difficulty' => 1,
                'prompt' => 'Look at the picture 🍎 and choose its correct English name.',
                'prompt_marathi' => '🍎 हे चित्र ओळखून योग्य इंग्रजी शब्द निवडा.',
                'explanation' => 'The picture shows an apple.',
                'explanation_marathi' => '🍎 हे सफरचंदाचे चित्र असून त्याला इंग्रजीत apple म्हणतात.',
                'options' => $this->opts(['apple', 'ball', 'bat', 'book'], 0),
            ],
            [
                'skill_code' => 'ENG_PICTURE_VOCABULARY',
                'lo_code' => 'STD4-ENG-01',
                'error_code' => 'CLASS4_ENG_PICTURE_VOCAB_ERROR',
                'difficulty' => 1,
                'prompt' => 'Identify the animal shown in this picture: 🐟',
                'prompt_marathi' => '🐟 हे चित्र पाहून इंग्रजीतील योग्य नाव ओळखा.',
                'explanation' => 'The picture shows a fish.',
                'explanation_marathi' => '🐟 म्हणजे मासा — इंग्रजीत त्याला fish म्हणतात.',
                'options' => $this->opts(['frog', 'fish', 'duck', 'crow'], 1),
            ],
            [
                'skill_code' => 'ENG_PICTURE_VOCABULARY',
                'lo_code' => 'STD4-ENG-01',
                'error_code' => 'CLASS4_ENG_PICTURE_VOCAB_ERROR',
                'difficulty' => 1,
                'prompt' => 'Which English word matches the picture 🌳?',
                'prompt_marathi' => '🌳 हे चित्र कशाचे आहे? योग्य इंग्रजी शब्द निवडा.',
                'explanation' => 'The picture shows a tree.',
                'explanation_marathi' => '🌳 हे झाडाचे चित्र असून त्याला इंग्रजीत tree म्हणतात.',
                'options' => $this->opts(['train', 'table', 'tree', 'toy'], 2),
            ],

            // Competency 72: Letter Reading (D E X V H)
            [
                'skill_code' => 'ENG_LETTER_RECOGNITION',
                'lo_code' => 'STD4-ENG-02',
                'error_code' => 'CLASS4_ENG_LETTER_CONFUSION',
                'difficulty' => 1,
                'prompt' => 'Which letter in the set “D, E, X, V, H” is a vowel?',
                'prompt_marathi' => '‘D, E, X, V, H’ या अक्षरांपैकी स्वर (Vowel) कोणते अक्षर आहे?',
                'explanation' => 'E is one of the five English vowels (A, E, I, O, U).',
                'explanation_marathi' => 'A, E, I, O, U हे पाच स्वर आहेत; दिलेल्या समूहात E हा स्वर आहे.',
                'options' => $this->opts(['D', 'E', 'X', 'H'], 1),
            ],
            [
                'skill_code' => 'ENG_LETTER_RECOGNITION',
                'lo_code' => 'STD4-ENG-02',
                'error_code' => 'CLASS4_ENG_LETTER_CONFUSION',
                'difficulty' => 2,
                'prompt' => 'Choose the correct small letter form of capital “H” from “D, E, X, V, H”.',
                'prompt_marathi' => '‘D, E, X, V, H’ मधील ‘H’ या कॅपिटल अक्षराचे स्मॉल (Small letter) रूप कोणते?',
                'explanation' => 'The lowercase form of H is h.',
                'explanation_marathi' => 'कॅपिटल H चे स्मॉल रूप h असे असते.',
                'options' => $this->opts(['b', 'n', 'h', 'd'], 2),
            ],
            [
                'skill_code' => 'ENG_LETTER_RECOGNITION',
                'lo_code' => 'STD4-ENG-02',
                'error_code' => 'CLASS4_ENG_LETTER_CONFUSION',
                'difficulty' => 1,
                'prompt' => 'Which letter from “D, E, X, V, H” begins the word “Dog”?',
                'prompt_marathi' => '‘D, E, X, V, H’ पैकी कोणत्या अक्षराने ‘Dog’ हा शब्द सुरू होतो?',
                'explanation' => 'Dog starts with the letter D.',
                'explanation_marathi' => 'Dog या शब्दाची सुरुवात D या अक्षराने होते.',
                'options' => $this->opts(['V', 'D', 'X', 'E'], 1),
            ],

            // Competency 73: Word Reading (cut sun lion cold dog)
            [
                'skill_code' => 'ENG_WORD_READING',
                'lo_code' => 'STD4-ENG-03',
                'error_code' => 'CLASS4_ENG_WORD_SPELLING_ERROR',
                'difficulty' => 1,
                'prompt' => 'From “cut, sun, lion, cold, dog”, choose the word that shines brightly in the daytime sky.',
                'prompt_marathi' => '‘cut, sun, lion, cold, dog’ यांपैकी ‘सूर्य’ या अर्थाचा इंग्रजी शब्द निवडा.',
                'explanation' => 'Sun means सूर्य.',
                'explanation_marathi' => '‘sun’ म्हणजे सूर्य होय.',
                'options' => $this->opts(['cut', 'sun', 'cold', 'dog'], 1),
            ],
            [
                'skill_code' => 'ENG_WORD_READING',
                'lo_code' => 'STD4-ENG-03',
                'error_code' => 'CLASS4_ENG_WORD_SPELLING_ERROR',
                'difficulty' => 1,
                'prompt' => 'Select the animal word from “cut, sun, lion, cold, dog” known as the king of the jungle (सिंह).',
                'prompt_marathi' => '‘cut, sun, lion, cold, dog’ मधील ‘सिंह’ या अर्थाचा इंग्रजी शब्द निवडा.',
                'explanation' => 'Lion means सिंह.',
                'explanation_marathi' => '‘lion’ म्हणजे सिंह होय.',
                'options' => $this->opts(['lion', 'dog', 'sun', 'cold'], 0),
            ],
            [
                'skill_code' => 'ENG_WORD_READING',
                'lo_code' => 'STD4-ENG-03',
                'error_code' => 'CLASS4_ENG_WORD_SPELLING_ERROR',
                'difficulty' => 2,
                'prompt' => 'Choose the opposite word of “hot” from “cut, sun, lion, cold, dog”.',
                'prompt_marathi' => '‘cut, sun, lion, cold, dog’ मधून ‘hot’ (गरम) या शब्दाचा विरुद्धार्थी शब्द निवडा.',
                'explanation' => 'Cold is the opposite of hot.',
                'explanation_marathi' => '‘hot’ (गरम) च्या विरुद्ध अर्थ असलेला शब्द ‘cold’ (थंड) आहे.',
                'options' => $this->opts(['cut', 'dog', 'cold', 'sun'], 2),
            ],

            // Competency 74: Paragraph Reading ("This is my book. I can read. The lion is strong.")
            [
                'skill_code' => 'ENG_PARAGRAPH_READING',
                'lo_code' => 'STD4-ENG-04',
                'error_code' => 'CLASS4_ENG_COMPREHENSION_ERROR',
                'difficulty' => 2,
                'prompt' => 'Read: “This is my book. I can read. The lion is strong.” Fill in the blank: I can ____.',
                'prompt_marathi' => 'वाचा: “This is my book. I can read. The lion is strong.” रिकामी जागा भरा: I can ____.',
                'explanation' => 'The second sentence states “I can read.”',
                'explanation_marathi' => 'उताऱ्यातील दुसऱ्या वाक्यात “I can read.” असे स्पष्ट दिले आहे.',
                'options' => $this->opts(['swim', 'read', 'fly', 'sing'], 1),
            ],
            [
                'skill_code' => 'ENG_PARAGRAPH_READING',
                'lo_code' => 'STD4-ENG-04',
                'error_code' => 'CLASS4_ENG_COMPREHENSION_ERROR',
                'difficulty' => 2,
                'prompt' => 'From the passage “This is my book. I can read. The lion is strong.”, what is strong?',
                'prompt_marathi' => '“This is my book. I can read. The lion is strong.” या उताऱ्यानुसार कोण मजबूत (strong) आहे?',
                'explanation' => 'The passage says “The lion is strong.”',
                'explanation_marathi' => 'उताऱ्यात “The lion is strong.” असे सांगितले आहे.',
                'options' => $this->opts(['The book', 'The cow', 'The lion', 'The tree'], 2),
            ],

            // Competency 75: Letter Writing Dictation (K G M T F)
            [
                'skill_code' => 'ENG_LETTER_WRITING',
                'lo_code' => 'STD4-ENG-05',
                'error_code' => 'CLASS4_ENG_LETTER_CONFUSION',
                'difficulty' => 2,
                'prompt' => 'Choose the correct lowercase (small) letter for capital “G” from “K, G, M, T, F”.',
                'prompt_marathi' => '‘K, G, M, T, F’ या अक्षरांपैकी ‘G’ चे स्मॉल (Small letter) रूप निवडा.',
                'explanation' => 'Capital G pairs with lowercase g.',
                'explanation_marathi' => 'कॅपिटल G चे स्मॉल अक्षर g असे लिहिले जाते.',
                'options' => $this->opts(['q', 'j', 'g', 'p'], 2),
            ],
            [
                'skill_code' => 'ENG_LETTER_WRITING',
                'lo_code' => 'STD4-ENG-05',
                'error_code' => 'CLASS4_ENG_LETTER_CONFUSION',
                'difficulty' => 2,
                'prompt' => 'Which letter in the set “K, G, M, T, F” comes first in alphabetical order?',
                'prompt_marathi' => 'वर्णक्रमानुसार (A to Z) ‘K, G, M, T, F’ यांतील कोणते अक्षर सर्वात आधी येते?',
                'explanation' => 'Alphabetical order among these letters is F, G, K, M, T.',
                'explanation_marathi' => 'वर्णक्रमानुसार F, G, K, M, T असा क्रम येतो; त्यामुळे F सर्वात आधी येते.',
                'options' => $this->opts(['K', 'F', 'G', 'M'], 1),
            ],

            // Competency 76: Word Writing Dictation (hut cook old cow cat)
            [
                'skill_code' => 'ENG_WORD_READING',
                'lo_code' => 'STD4-ENG-03',
                'error_code' => 'CLASS4_ENG_WORD_SPELLING_ERROR',
                'difficulty' => 2,
                'prompt' => 'Select the correctly spelled word from “hut, cook, old, cow, cat” meaning a small house (झोपडी).',
                'prompt_marathi' => '‘झोपडी’ या अर्थासाठी योग्य स्पेलिंग असलेला शब्द निवडा.',
                'explanation' => '“hut” means a small simple house or झोपडी.',
                'explanation_marathi' => 'झोपडीला इंग्रजीत ‘hut’ म्हणतात.',
                'options' => $this->opts(['hat', 'hot', 'hut', 'hit'], 2),
            ],
            [
                'skill_code' => 'ENG_WORD_READING',
                'lo_code' => 'STD4-ENG-03',
                'error_code' => 'CLASS4_ENG_WORD_SPELLING_ERROR',
                'difficulty' => 2,
                'prompt' => 'Choose the correct spelling of the word meaning “to prepare food” (अन्न शिजवणे).',
                'prompt_marathi' => '‘अन्न शिजवणे’ यासाठी योग्य इंग्रजी स्पेलिंग कोणते?',
                'explanation' => 'The correct spelling is “cook”.',
                'explanation_marathi' => '‘cook’ हे योग्य स्पेलिंग आहे.',
                'options' => $this->opts(['coke', 'cook', 'coock', 'kook'], 1),
            ],

            // Competency 77: Sentence Writing Dictation (She is my mother.)
            [
                'skill_code' => 'ENG_SENTENCE_WRITING',
                'lo_code' => 'STD4-ENG-06',
                'error_code' => 'CLASS4_ENG_SENTENCE_SYNTAX_ERROR',
                'difficulty' => 3,
                'prompt' => 'Choose the correctly written and punctuated sentence.',
                'prompt_marathi' => 'व्याकरण व विरामचिन्हांनुसार (Capital letter व Full stop) अचूक इंग्रजी वाक्य निवडा.',
                'explanation' => 'A correct sentence starts with a capital letter “S”, uses “is” with “She”, and ends with a full stop.',
                'explanation_marathi' => 'अचूक वाक्याची सुरुवात कॅपिटल अक्षराने (She), योग्य क्रियापदाने (is) आणि शेवटी पूर्णविरामाने (.) होते.',
                'options' => $this->opts([
                    'she is my mother',
                    'She am my mother.',
                    'She is my mother.',
                    'She are my mother',
                ], 2),
            ],
        ];
    }

    /**
     * Build four uniform MCQ options where label and label_marathi preserve exact English text.
     *
     * @param  list<string>  $labels
     * @return list<array{label: string, label_marathi: string, is_correct: bool}>
     */
    private function opts(array $labels, int $correctIndex): array
    {
        return array_values(array_map(
            static fn (string $label, int $idx): array => [
                'label' => $label,
                'label_marathi' => $label,
                'is_correct' => $idx === $correctIndex,
            ],
            $labels,
            array_keys($labels),
        ));
    }
}
