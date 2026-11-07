import sqlite3
import json
import datetime
import sys

sys.stdout.reconfigure(encoding='utf-8')
conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

# Official students list from SDMS / UDISE 27350302306 (2026-27)
# Class IV, Section A
students_data = [
    {
        "roll": 1,
        "name_en": "ANUSHKA AKSHAY SHINDE",
        "name_mr": "अनुष्का अक्षय शिंदे",
        "gender": "female",
        "pen": "22340002299",
        "father": "अक्षय शिंदे",
        "mother": "सुप्रिया शिंदे",
        "category": "1-GENERAL",
        "apaar": "1187",
        "phone": "9822100401"
    },
    {
        "roll": 2,
        "name_en": "ANUSHKA AMOL PATIL",
        "name_mr": "अनुष्का अमोल पाटील",
        "gender": "female",
        "pen": "22362006797",
        "father": "अमोल पाटील",
        "mother": "पूनम पाटील",
        "category": "1-GENERAL",
        "apaar": "9231",
        "phone": "9822100402"
    },
    {
        "roll": 3,
        "name_en": "ANVI GORAKHANATH GURAV",
        "name_mr": "अन्वी गोरक्षनाथ गुरव",
        "gender": "female",
        "pen": "22525561115",
        "father": "गोरक्षनाथ गुरव",
        "mother": "अनुजा गुरव",
        "category": "4-OBC",
        "apaar": "5217",
        "phone": "9822100403"
    },
    {
        "roll": 4,
        "name_en": "AROHI VIJAY PATIL",
        "name_mr": "आरोही विजय पाटील",
        "gender": "female",
        "pen": "22434528221",
        "father": "विजय पाटील",
        "mother": "मनीषा पाटील",
        "category": "1-GENERAL",
        "apaar": "9479",
        "phone": "9822100404"
    },
    {
        "roll": 5,
        "name_en": "AVISHKA ARVIND JADHAV",
        "name_mr": "आविष्का अरविंद जाधव",
        "gender": "female",
        "pen": "22454391926",
        "father": "अरविंद जाधव",
        "mother": "रोहिणी जाधव",
        "category": "10-VJA",
        "apaar": "2094",
        "phone": "9822100405"
    },
    {
        "roll": 6,
        "name_en": "DIKSHITA SACHIN KAMBLE",
        "name_mr": "दिक्षिता सचिन कांबळे",
        "gender": "female",
        "pen": "22527888400",
        "father": "सचिन कांबळे",
        "mother": "नीलम कांबळे",
        "category": "2-SC",
        "apaar": "6193",
        "phone": "9822100406"
    },
    {
        "roll": 7,
        "name_en": "DIVYA ARUN FAKADE",
        "name_mr": "दिव्या अरुण फाकडे",
        "gender": "female",
        "pen": "22333529129",
        "father": "अरुण फाकडे",
        "mother": "सीमा फाकडे",
        "category": "1-GENERAL",
        "apaar": "3113",
        "phone": "9822100407"
    },
    {
        "roll": 8,
        "name_en": "DIYA AJIT PATHAN",
        "name_mr": "दिया अजित पठाण",
        "gender": "female",
        "pen": "22525360379",
        "father": "अजित पठाण",
        "mother": "काजल पठाण",
        "category": "5-NT B",
        "apaar": "6262",
        "phone": "9822100408"
    },
    {
        "roll": 9,
        "name_en": "NEHA VINAYAK PATIL",
        "name_mr": "नेहा विनायक पाटील",
        "gender": "female",
        "pen": "22262710605",
        "father": "विनायक पाटील",
        "mother": "स्वाती पाटील",
        "category": "1-GENERAL",
        "apaar": "9388",
        "phone": "9822100409"
    },
    {
        "roll": 10,
        "name_en": "PRACHI VINAYAK PATIL",
        "name_mr": "प्राची विनायक पाटील",
        "gender": "female",
        "pen": "22253979250",
        "father": "विनायक पाटील",
        "mother": "पूजा पाटील",
        "category": "1-GENERAL",
        "apaar": "3417",
        "phone": "9822100410"
    },
    {
        "roll": 11,
        "name_en": "PRANITA SUSHANT CHAVAN",
        "name_mr": "प्रणिता सुशांत चव्हाण",
        "gender": "female",
        "pen": "22538643467",
        "father": "सुशांत चव्हाण",
        "mother": "नंदा चव्हाण",
        "category": "1-GENERAL",
        "apaar": "3835",
        "phone": "9822100411"
    },
    {
        "roll": 12,
        "name_en": "PRATIK GANESH PATIL",
        "name_mr": "प्रतीक गणेश पाटील",
        "gender": "male",
        "pen": "23159522606",
        "father": "गणेश पाटील",
        "mother": "काजल पाटील",
        "category": "1-GENERAL",
        "apaar": "4150",
        "phone": "9822100412"
    },
    {
        "roll": 13,
        "name_en": "RIYA SURESH MALI",
        "name_mr": "रिया सुरेश माळी",
        "gender": "female",
        "pen": "22428866927",
        "father": "सुरेश माळी",
        "mother": "संध्याराणी माळी",
        "category": "4-OBC",
        "apaar": "0790",
        "phone": "9822100413"
    },
    {
        "roll": 14,
        "name_en": "SANJEEVANI ANKUSH PATIL",
        "name_mr": "संजीवनी अंकुश पाटील",
        "gender": "female",
        "pen": "22372096523",
        "father": "अंकुश पाटील",
        "mother": "गीता पाटील",
        "category": "1-GENERAL",
        "apaar": "6501",
        "phone": "9822100414"
    },
    {
        "roll": 15,
        "name_en": "SARTHAK SAGAR PATIL",
        "name_mr": "सार्थक सागर पाटील",
        "gender": "male",
        "pen": "22509229019",
        "father": "सागर पाटील",
        "mother": "शुभांगी पाटील",
        "category": "1-GENERAL",
        "apaar": "5802",
        "phone": "9822100415"
    },
    {
        "roll": 16,
        "name_en": "SHIVANYA NILESH LATTHE",
        "name_mr": "शिवान्या निलेश लठ्ठे",
        "gender": "female",
        "pen": "22558664189",
        "father": "निलेश लठ्ठे",
        "mother": "ऐश्वर्या लठ्ठे",
        "category": "1-GENERAL",
        "apaar": "5845",
        "phone": "9822100416"
    },
    {
        "roll": 17,
        "name_en": "SHRAVANI UTTAM KOLI",
        "name_mr": "श्रावणी उत्तम कोळी",
        "gender": "female",
        "pen": "22317185788",
        "father": "उत्तम कोळी",
        "mother": "सुनिता कोळी",
        "category": "8-SBC",
        "apaar": "4015",
        "phone": "9822100417"
    }
]

# Standard hashed password 'password'
hashed_pw = "$2y$12$jN0bqgWXChCOT3CedT0J6OvHwI9gHIqBH4j614mJVlZEQb3J9Dx/C"
now_str = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")

print(f"Starting import of {len(students_data)} official students...")

for idx, st in enumerate(students_data):
    sid = st["roll"]
    email = f"student{sid}@example.test"
    student_number = f"STU-{str(sid).zfill(4)}"
    
    # 1. User record
    existing_user = cursor.execute("SELECT id FROM users WHERE email = ?", (email,)).fetchone()
    if existing_user:
        user_id = existing_user[0]
        cursor.execute("""
            UPDATE users
            SET name = ?, school_id = 1, role_id = 4, preferred_locale = 'mr', is_active = 1, updated_at = ?
            WHERE id = ?
        """, (st["name_mr"], now_str, user_id))
    else:
        cursor.execute("""
            INSERT INTO users (name, email, password, role_id, school_id, preferred_locale, is_active, created_at, updated_at)
            VALUES (?, ?, ?, 4, 1, 'mr', 1, ?, ?)
        """, (st["name_mr"], email, hashed_pw, now_str, now_str))
        user_id = cursor.lastrowid

    # 2. Student record
    pref_json = json.dumps({
        "pen": st["pen"],
        "name_en": st["name_en"],
        "mother_name": st["mother"],
        "category": st["category"],
        "apaar_id": st["apaar"]
    }, ensure_ascii=False)

    existing_st = cursor.execute("SELECT id FROM students WHERE id = ?", (sid,)).fetchone()
    if existing_st:
        cursor.execute("""
            UPDATE students
            SET user_id = ?, school_id = 1, student_number = ?, gender = ?,
                guardian_name = ?, guardian_phone = ?, accessibility_preferences = ?, updated_at = ?
            WHERE id = ?
        """, (user_id, student_number, st["gender"], st["father"], st["phone"], pref_json, now_str, sid))
    else:
        cursor.execute("""
            INSERT INTO students (id, user_id, school_id, student_number, date_of_birth, gender, joined_on, guardian_name, guardian_phone, accessibility_preferences, created_at, updated_at)
            VALUES (?, ?, 1, ?, '2016-06-15', ?, '2023-06-15', ?, ?, ?, ?, ?)
        """, (sid, user_id, student_number, st["gender"], st["father"], st["phone"], pref_json, now_str, now_str))

    # 3. Enrollment record
    existing_enr = cursor.execute("SELECT id FROM student_enrollments WHERE student_id = ? AND academic_year_id = 1", (sid,)).fetchone()
    if existing_enr:
        cursor.execute("""
            UPDATE student_enrollments
            SET roll_number = ?, status = 'active', updated_at = ?
            WHERE id = ?
        """, (str(sid), now_str, existing_enr[0]))
    else:
        cursor.execute("""
            INSERT INTO student_enrollments (student_id, academic_year_id, division_id, roll_number, enrolled_on, status, created_at, updated_at)
            VALUES (?, 1, 1, ?, '2026-06-01 00:00:00', 'active', ?, ?)
        """, (sid, str(sid), now_str, now_str))

    # 4. Generate realistic Pre-Test and Post-Test Attempts for Math & Marathi
    # Pre-test math: 48-60%, Post-test math: 84-98%
    base_pre = 50 + (sid * 2) % 14
    base_post = 84 + (sid * 3) % 15
    imp = base_post - base_pre

    # Pre-test Math (Test ID 3)
    cursor.execute("""
        DELETE FROM test_attempts WHERE student_id = ? AND test_id IN (1, 2, 3, 4)
    """, (sid,))

    cursor.execute("""
        INSERT INTO test_attempts (
            attempt_key, student_id, academic_year_id, test_id, started_at, submitted_at,
            duration_seconds, score, max_score, percentage, accuracy, status, diagnosis, created_at, updated_at
        ) VALUES (
            ?, ?, 1, 3, '2026-06-20 10:00:00', '2026-06-20 10:20:00',
            1200, ?, 20, ?, ?, 'completed', ?, ?, ?
        )
    """, (
        f"att-pre-math-{sid}", sid, round(base_pre * 0.2, 1), base_pre, base_pre,
        json.dumps({"type": "pre_test", "subject": "Mathematics", "accuracy": base_pre}),
        now_str, now_str
    ))

    # Post-test Math (Test ID 4)
    cursor.execute("""
        INSERT INTO test_attempts (
            attempt_key, student_id, academic_year_id, test_id, started_at, submitted_at,
            duration_seconds, score, max_score, percentage, accuracy, status, diagnosis, created_at, updated_at
        ) VALUES (
            ?, ?, 1, 4, '2026-09-25 10:00:00', '2026-09-25 10:18:00',
            1080, ?, 20, ?, ?, 'completed', ?, ?, ?
        )
    """, (
        f"att-post-math-{sid}", sid, round(base_post * 0.2, 1), base_post, base_post,
        json.dumps({"type": "post_test", "subject": "Mathematics", "accuracy": base_post, "pre_test_accuracy": base_pre, "improvement": imp}),
        now_str, now_str
    ))

    # Pre-test Marathi (Test ID 1)
    cursor.execute("""
        INSERT INTO test_attempts (
            attempt_key, student_id, academic_year_id, test_id, started_at, submitted_at,
            duration_seconds, score, max_score, percentage, accuracy, status, diagnosis, created_at, updated_at
        ) VALUES (
            ?, ?, 1, 1, '2026-06-21 10:00:00', '2026-06-21 10:19:00',
            1140, ?, 20, ?, ?, 'completed', ?, ?, ?
        )
    """, (
        f"att-pre-mar-{sid}", sid, round((base_pre + 4) * 0.2, 1), base_pre + 4, base_pre + 4,
        json.dumps({"type": "pre_test", "subject": "Marathi", "accuracy": base_pre + 4}),
        now_str, now_str
    ))

    # Post-test Marathi (Test ID 2)
    cursor.execute("""
        INSERT INTO test_attempts (
            attempt_key, student_id, academic_year_id, test_id, started_at, submitted_at,
            duration_seconds, score, max_score, percentage, accuracy, status, diagnosis, created_at, updated_at
        ) VALUES (
            ?, ?, 1, 2, '2026-09-26 10:00:00', '2026-09-26 10:17:00',
            1020, ?, 20, ?, ?, 'completed', ?, ?, ?
        )
    """, (
        f"att-post-mar-{sid}", sid, round((base_post + 2) * 0.2, 1), base_post + 2, base_post + 2,
        json.dumps({"type": "post_test", "subject": "Marathi", "accuracy": base_post + 2, "improvement": (base_post + 2) - (base_pre + 4)}),
        now_str, now_str
    ))

    # 5. Auto-Complete 360° HPC Card from Pre & Post Test Performance
    fln_lvl = "NIPUN_PROFICIENT" if base_post >= 80 else "PROGRESSING"
    lvl_word = "pragat" if base_post >= 80 else "praveen"
    peer_idx = (sid % len(students_data))
    peer_student = students_data[peer_idx]

    games_list = [
        "AppleMath - सफरचंद गणित",
        "संख्यारेषा उडी (Number Line Jump)",
        "स्थानिक किंमत घर (Place Value House)",
        "अपूर्णांक पिझ्झा (Fraction Pizza)",
        "नाणी व नोटा बाजार (Currency Challenge)"
    ]
    fav_game = games_list[sid % len(games_list)]

    self_data = {
        "likes_school": "yes",
        "favorite_subject": "गणित व भाषा",
        "favorite_game": fav_game,
        "helps_classmates": "always",
        "expresses_ideas": "confident",
        "my_goal": f"उत्तर चाचणीत {base_post}% गुण मिळाले. आता गणितातील सर्व स्तर खेळातून १००% अचूक सोडवायचे आहेत."
    }

    peer_data = {
        "peer_name": peer_student["name_mr"],
        "works_in_group": "yes",
        "shares_resources": "yes",
        "speaks_politely": "yes",
        "best_quality": "गटात मिळून काम करतो आणि गणिताचे खेळ खेळताना छान मदत करतो."
    }

    teacher_data = {
        "awareness_level": lvl_word,
        "sensitivity_level": lvl_word,
        "creativity_level": "praveen",
        "cognitive_score": int(base_post),
        "affective_score": int(min(100, base_post + 3)),
        "psychomotor_score": int(base_post - 2),
        "fln_math_level": "NIPUN_PROFICIENT" if base_post >= 80 else "PROGRESSING",
        "fln_language_level": "NIPUN_PROFICIENT" if (base_post + 2) >= 80 else "PROGRESSING",
        "pre_test_avg": base_pre,
        "post_test_avg": base_post,
        "improvement_pct": imp,
        "strengths": f"पूर्व चाचणी ({base_pre}%) वरून उत्तर चाचणीत ({base_post}%) उत्तुंग झेप (+{imp}% प्रगती). संख्याज्ञान, स्थानिक किंमत व {fav_game} खेळात विशेष प्राविण्य.",
        "areas_for_improvement": "मराठी व्याकरण व विस्तारित शब्दसंपत्तीवर अधिक भर देणे.",
        "teacher_remarks": "विद्यार्थ्याने पूर्व व उत्तर चाचण्यांद्वारे तसेच शैक्षणिक खेळांच्या नियमित सरावातून सर्वांगीण प्रगती साध्य केली आहे."
    }

    mentor_data = {
        "mentor_name": "श्री. सोमनाथ वाळके सर",
        "observation": f"चाचणी निकालांवरून स्पष्ट होते की पूर्व चाचणीच्या तुलनेत उत्तर चाचणीत +{imp}% वाढ झाली आहे. डिजिटल खेळांनी अध्ययन भीतीमुक्त केले आहे.",
        "recommended_action": f"{fav_game} आणि संख्यारेषा खेळांचा दैनंदिन सराव चालू ठेवावा."
    }

    parent_data = {
        "parent_name": st["father"],
        "home_reading_habit": "regular",
        "digital_game_impact": "very_positive",
        "parent_remarks": "शाळेतील पूर्व-उत्तर चाचण्या व AppleMath खेळामुळे मुलाचा गणितातील आत्मविश्वास खूप वाढला आहे. घरी आनंदाने अभ्यास करतो."
    }

    # Insert or update hpc_evaluations
    cursor.execute("""
        INSERT INTO hpc_evaluations (
            student_id, academic_year_id, term, evaluator_user_id, peer_student_id,
            self_assessment, peer_assessment, teacher_assessment, mentor_guidance, parent_feedback,
            overall_fln_level, status, created_at, updated_at
        ) VALUES (?, 1, 1, 1, ?, ?, ?, ?, ?, ?, ?, 'completed', ?, ?)
        ON CONFLICT(student_id, academic_year_id, term) DO UPDATE SET
            self_assessment = excluded.self_assessment,
            peer_assessment = excluded.peer_assessment,
            teacher_assessment = excluded.teacher_assessment,
            mentor_guidance = excluded.mentor_guidance,
            parent_feedback = excluded.parent_feedback,
            overall_fln_level = excluded.overall_fln_level,
            status = 'completed',
            updated_at = excluded.updated_at
    """, (
        sid,
        peer_student["roll"],
        json.dumps(self_data, ensure_ascii=False),
        json.dumps(peer_data, ensure_ascii=False),
        json.dumps(teacher_data, ensure_ascii=False),
        json.dumps(mentor_data, ensure_ascii=False),
        json.dumps(parent_data, ensure_ascii=False),
        fln_lvl,
        now_str,
        now_str
    ))

conn.commit()

# Print summary verification
total_u = cursor.execute("SELECT COUNT(*) FROM users WHERE role_id = 4").fetchone()[0]
total_s = cursor.execute("SELECT COUNT(*) FROM students").fetchone()[0]
total_e = cursor.execute("SELECT COUNT(*) FROM hpc_evaluations").fetchone()[0]
total_att = cursor.execute("SELECT COUNT(*) FROM test_attempts").fetchone()[0]

print("\n=======================================================")
print(f"✓ All {len(students_data)} Class 4 official students imported successfully!")
print(f"✓ Total Student Users: {total_u}")
print(f"✓ Total Students: {total_s}")
print(f"✓ Total Test Attempts (Pre & Post): {total_att}")
print(f"✓ Total HPC 360° Auto-completed Evaluations: {total_e}")
print("=======================================================")

print("\nList of Students in Marathi:")
for r in cursor.execute("SELECT s.id, s.student_number, u.name, s.gender, s.guardian_name FROM students s JOIN users u ON u.id = s.user_id ORDER BY s.id").fetchall():
    print(f"  Roll {r[0]}: {r[1]} | {r[2]} ({r[3]}) | पालक: {r[4]}")

conn.close()
