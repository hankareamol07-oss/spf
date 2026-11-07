import sqlite3
import json
import datetime
import sys

sys.stdout.reconfigure(encoding='utf-8')
conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

# 1. Create table
cursor.execute("""
CREATE TABLE IF NOT EXISTS hpc_evaluations (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    student_id INTEGER NOT NULL,
    academic_year_id INTEGER NOT NULL DEFAULT 1,
    term INTEGER NOT NULL DEFAULT 1,
    evaluator_user_id INTEGER,
    peer_student_id INTEGER,
    self_assessment TEXT,
    peer_assessment TEXT,
    teacher_assessment TEXT,
    mentor_guidance TEXT,
    parent_feedback TEXT,
    overall_fln_level VARCHAR(50) NOT NULL DEFAULT 'NIPUN_PROFICIENT',
    status VARCHAR(30) NOT NULL DEFAULT 'completed',
    created_at DATETIME,
    updated_at DATETIME,
    CONSTRAINT hpc_student_year_term_unique UNIQUE (student_id, academic_year_id, term),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE RESTRICT,
    FOREIGN KEY (evaluator_user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (peer_student_id) REFERENCES students(id) ON DELETE SET NULL
)
""")

# 2. Add to migrations table if not present
mig_name = '2026_11_03_091800_create_hpc_evaluations_table'
exists = cursor.execute("SELECT id FROM migrations WHERE migration = ?", (mig_name,)).fetchone()
if not exists:
    cursor.execute("INSERT INTO migrations (migration, batch) VALUES (?, ?)", (mig_name, 3))

print("Created table hpc_evaluations and registered migration.")

# 3. Student Marathi names & details for Class 4 (ZP School No. 2 Kuchi)
marathi_students = [
    (1, "आरव सचिन पाटील", "STU-0401", "male", "सचिन पाटील", "9822100101"),
    (2, "तन्वी अमोल हंकारे", "STU-0402", "female", "अमोल हंकारे", "9960967574"),
    (3, "वेदांत महादेव शिंदे", "STU-0403", "male", "महादेव शिंदे", "9822100103"),
    (4, "अनुष्का संतोष चव्हाण", "STU-0404", "female", "संतोष चव्हाण", "9822100104"),
    (5, "ओंकार दीपक माने", "STU-0405", "male", "दीपक माने", "9822100105"),
    (6, "श्रावणी विजय पवार", "STU-0406", "female", "विजय पवार", "9822100106"),
    (7, "समर्थ विलास जाधव", "STU-0407", "male", "विलास जाधव", "9822100107"),
    (8, "समृद्धी संभाजी गायकवाड", "STU-0408", "female", "संभाजी गायकवाड", "9822100108"),
    (9, "अनिकेत सुनील मोरे", "STU-0409", "male", "सुनील मोरे", "9822100109"),
    (10, "प्रांजल विकास भोसले", "STU-0410", "female", "विकास भोसले", "9822100110"),
    (11, "रुद्र प्रताप कुंभार", "STU-0411", "male", "प्रताप कुंभार", "9822100111"),
    (12, "ऋतुजा गजानन सावंत", "STU-0412", "female", "गजानन सावंत", "9822100112"),
    (13, "साईराज नितीन वाघमारे", "STU-0413", "male", "नितीन वाघमारे", "9822100113"),
    (14, "सई किरण कांबळे", "STU-0414", "female", "किरण कांबळे", "9822100114"),
    (15, "युवराज दत्तात्रय लवटे", "STU-0415", "male", "दत्तात्रय लवटे", "9822100115"),
]

# Update users and students tables with realistic Marathi names
for sid, name_mr, roll_code, gender, guardian, phone in marathi_students:
    cursor.execute("""
        UPDATE users 
        SET name = ? 
        WHERE id = (SELECT user_id FROM students WHERE id = ?)
    """, (name_mr, sid))
    cursor.execute("""
        UPDATE students 
        SET student_number = ?, gender = ?, guardian_name = ?, guardian_phone = ? 
        WHERE id = ?
    """, (roll_code, gender, guardian, phone, sid))

print("Updated 15 Class 4 students with authentic Marathi names and details.")

# 4. Realistic 360° Evaluation Data tailored for Class 4 & SPF Fellowship
now_str = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")

eval_samples = [
    {
        "student_id": 1,
        "peer_id": 3,
        "peer_name": "वेदांत महादेव शिंदे",
        "self": {
            "likes_school": "yes",
            "favorite_subject": "गणित",
            "favorite_game": "AppleMath - सफरचंद गणित",
            "helps_classmates": "always",
            "expresses_ideas": "confident",
            "my_goal": "मला ४ अंकी संख्यांचे गुणाकार व भागाकार खेळातून जलद करायचे आहेत."
        },
        "peer": {
            "peer_name": "वेदांत महादेव शिंदे",
            "works_in_group": "yes",
            "shares_resources": "yes",
            "speaks_politely": "yes",
            "best_quality": "गणिताचे खेळ खेळताना सर्वांना सोबत घेऊन खेळतो आणि समजून सांगतो."
        },
        "teacher": {
            "awareness_level": "pragat",       # जाणीवजागृती: प्रगत
            "sensitivity_level": "pragat",     # संवेदनशीलता: प्रगत
            "creativity_level": "praveen",     # सर्जनशीलता: प्रवीण
            "cognitive_score": 94,
            "affective_score": 90,
            "psychomotor_score": 92,
            "fln_math_level": "NIPUN_PROFICIENT",
            "fln_language_level": "NIPUN_PROFICIENT",
            "strengths": "संख्याज्ञान, स्थानिक किंमत व मूलभूत क्रिया यांमध्ये अत्यंत अचूक. डिजिटल खेळात समस्या सोडवण्याचा वेग वाखाणण्याजोगा आहे.",
            "areas_for_improvement": "शब्दसंपत्ती व वर्णनात्मक मराठी लेखनाचा सराव वाढवणे.",
            "teacher_remarks": "आरव हा वर्गातील अत्यंत उत्साही व अभ्यासू विद्यार्थी आहे. खेळाधारित अध्ययनाचा त्याला मोठा फायदा झाला आहे."
        },
        "mentor": {
            "mentor_name": "श्री. सोमनाथ वाळके सर",
            "observation": "विद्यार्थ्याने डिजिटल खेळांच्या माध्यमातून स्वयंअध्ययनाची उत्कृष्ट सवय विकसित केली आहे. गणितातील आत्मविश्वास वाढला आहे.",
            "recommended_action": "अपूर्णांक पिझ्झा व संख्यारेषा खेळात पुढील आव्हाने द्यावीत."
        },
        "parent": {
            "parent_name": "सचिन पाटील",
            "home_reading_habit": "regular",
            "digital_game_impact": "very_positive",
            "parent_remarks": "घरी मोबाईलवर इतर गेम खेळण्याऐवजी AppleMath खेळ आवडीने खेळतो. गणिताची गोडी निर्माण झाली आहे."
        },
        "overall_fln": "NIPUN_PROFICIENT"
    },
    {
        "student_id": 2,
        "peer_id": 4,
        "peer_name": "अनुष्का संतोष चव्हाण",
        "self": {
            "likes_school": "yes",
            "favorite_subject": "भाषा व गणित",
            "favorite_game": "संख्यारेषा उडी व शब्दसंग्रह",
            "helps_classmates": "always",
            "expresses_ideas": "confident",
            "my_goal": "मला गोष्टींची पुस्तके वाचायला आणि नवीन कोडी सोडवायला खूप आवडते."
        },
        "peer": {
            "peer_name": "अनुष्का संतोष चव्हाण",
            "works_in_group": "yes",
            "shares_resources": "yes",
            "speaks_politely": "yes",
            "best_quality": "नेहमी आनंदी राहते आणि सुंदर अक्षर काढून मदत करते."
        },
        "teacher": {
            "awareness_level": "pragat",
            "sensitivity_level": "pragat",
            "creativity_level": "pragat",
            "cognitive_score": 96,
            "affective_score": 95,
            "psychomotor_score": 94,
            "fln_math_level": "NIPUN_PROFICIENT",
            "fln_language_level": "NIPUN_PROFICIENT",
            "strengths": "भाषेचे उत्तम आकलन, स्पष्ट वाचन, आणि गणितातील संकल्पनांची अचूक मांडणी. डिजिटल खेळात ९८% अचूकता.",
            "areas_for_improvement": "इंग्रजी शब्दसंग्रह अधिक वाढवणे.",
            "teacher_remarks": "तन्वी सर्वंकष प्रगती दाखवत असून तिची अध्ययन वृत्ती अत्यंत आदर्श आहे."
        },
        "mentor": {
            "mentor_name": "श्री. सोमनाथ वाळके सर",
            "observation": "तन्वीचे गणित व भाषा या दोन्ही विषयांत उत्कृष्ट नैपुण्य दिसून येते. नेतृत्व गुण उत्तम आहेत.",
            "recommended_action": "इतर सहकाऱ्यांना डिजिटल खेळात मेंटॉरिंग करण्यासाठी प्रोत्साहन द्यावे."
        },
        "parent": {
            "parent_name": "अमोल हंकारे",
            "home_reading_habit": "regular",
            "digital_game_impact": "very_positive",
            "parent_remarks": "शाळेतील खेळातून शिकण्याची पद्धत खूप प्रभावी आहे. वाचन व गणितात मुलगी स्वतःहून पुढाकार घेते."
        },
        "overall_fln": "NIPUN_PROFICIENT"
    },
    {
        "student_id": 3,
        "peer_id": 1,
        "peer_name": "आरव सचिन पाटील",
        "self": {
            "likes_school": "yes",
            "favorite_subject": "गणित व खेळ",
            "favorite_game": "स्थानिक किंमत घर (Place Value House)",
            "helps_classmates": "always",
            "expresses_ideas": "confident",
            "my_goal": "मोठ्या संख्यांची बेरीज-वजाबाकी न चुकता करायची आहे."
        },
        "peer": {
            "peer_name": "आरव सचिन पाटील",
            "works_in_group": "yes",
            "shares_resources": "yes",
            "speaks_politely": "yes",
            "best_quality": "कधीही चिडत नाही, खेळात छान साथ देतो."
        },
        "teacher": {
            "awareness_level": "pragat",
            "sensitivity_level": "praveen",
            "creativity_level": "praveen",
            "cognitive_score": 88,
            "affective_score": 86,
            "psychomotor_score": 90,
            "fln_math_level": "NIPUN_PROFICIENT",
            "fln_language_level": "PROGRESSING",
            "strengths": "स्थानिक किंमतीची संकल्पना पक्की. खेळातील स्कोअरिंगमध्ये सतत पुढे राहण्याची जिद्द.",
            "areas_for_improvement": "श्रुतलेखन व व्याकरणामध्ये सराव आवश्यक.",
            "teacher_remarks": "वेदांतने गणितात उत्तम प्रगती साधली आहे. मराठी वाचनातही प्रगती होत आहे."
        },
        "mentor": {
            "mentor_name": "श्री. सोमनाथ वाळke सर",
            "observation": "संख्याज्ञानाची पायाभूत उद्दिष्टे साध्य झाली आहेत. आत्मविश्वास वाढला आहे.",
            "recommended_action": "दैनंदिन वाचनासाठी लहान गोष्टींची पुस्तके उपलब्ध करून द्यावीत."
        },
        "parent": {
            "parent_name": "महादेव शिंदे",
            "home_reading_habit": "regular",
            "digital_game_impact": "very_positive",
            "parent_remarks": "मुलाला गणिताची भीती वाटायची, पण आता खेळांमुळे तो आनंदाने गणित सोडवतो."
        },
        "overall_fln": "NIPUN_PROFICIENT"
    }
]

# Seed for the rest of students 4 to 15 with varied realistic levels
general_goals = [
    "स्थानिक किंमत व दशमान पद्धत समजून घेणे.",
    "दैनंदिन व्यवहारातील पैशांची नाणी व नोटा मोजणे.",
    "अपूर्णांक संकल्पना पिझ्झा मॉडेलने पक्की करणे.",
    "सुंदर हस्ताक्षरात सुविचार व अनुभव लिहिणे.",
    "शाळेच्या बागेतील झाडांची काळजी घेणे व नोंद ठेवणे."
]

for sid in range(1, 16):
    existing = cursor.execute("SELECT id FROM hpc_evaluations WHERE student_id = ? AND academic_year_id = 1 AND term = 1", (sid,)).fetchone()
    
    # Specific pre-defined or generated realistic
    matched = next((item for item in eval_samples if item["student_id"] == sid), None)
    if matched:
        s_data = matched["self"]
        p_data = matched["peer"]
        t_data = matched["teacher"]
        m_data = matched["mentor"]
        par_data = matched["parent"]
        fln_lvl = matched["overall_fln"]
        peer_id = matched["peer_id"]
    else:
        peer_id = (sid % 15) + 1
        peer_row = next(s for s in marathi_students if s[0] == peer_id)
        cur_row = next(s for s in marathi_students if s[0] == sid)
        
        is_proficient = (sid % 3 != 0)
        fln_lvl = "NIPUN_PROFICIENT" if is_proficient else "PROGRESSING"
        lvl_key = "pragat" if is_proficient else "praveen"
        
        s_data = {
            "likes_school": "yes",
            "favorite_subject": "गणित व परिसर अभ्यास",
            "favorite_game": "AppleMath व संख्यारेषा",
            "helps_classmates": "always",
            "expresses_ideas": "confident",
            "my_goal": general_goals[sid % len(general_goals)]
        }
        p_data = {
            "peer_name": peer_row[1],
            "works_in_group": "yes",
            "shares_resources": "yes",
            "speaks_politely": "yes",
            "best_quality": "गटात मिळून काम करतो व खेळातील क्लृप्त्या सांगतो."
        }
        t_data = {
            "awareness_level": lvl_key,
            "sensitivity_level": lvl_key,
            "creativity_level": "praveen",
            "cognitive_score": 85 + (sid % 12),
            "affective_score": 88 + (sid % 10),
            "psychomotor_score": 87 + (sid % 11),
            "fln_math_level": fln_lvl,
            "fln_language_level": fln_lvl,
            "strengths": f"{cur_row[1]} ची खेळातील सहभागिता व गणितातील अचूकता उत्तम आहे.",
            "areas_for_improvement": "नियमित गृहअभ्यास व स्वयंअध्ययन अधिक दृढ करणे.",
            "teacher_remarks": "विद्यार्थ्याची प्रगती समाधानकारक असून खेळांद्वारे शिकण्यात विशेष रस दाखवतो."
        }
        m_data = {
            "mentor_name": "श्री. सोमनाथ वाळके सर",
            "observation": "डिजिटल शैक्षणिक साधनांचा वापर करून शिकण्याची गती वाढली आहे.",
            "recommended_action": "शाळेतील सराव सत्रांमध्ये नियमित सहभाग ठेवावा."
        }
        par_data = {
            "parent_name": cur_row[4],
            "home_reading_habit": "regular" if is_proficient else "occasional",
            "digital_game_impact": "very_positive",
            "parent_remarks": "मुलगा शाळेत जाण्यासाठी आनंदाने तयार असतो. डिजिटल खेळांमुळे अभ्यासातील भीती दूर झाली आहे."
        }

    if existing:
        cursor.execute("""
            UPDATE hpc_evaluations
            SET self_assessment = ?, peer_assessment = ?, teacher_assessment = ?,
                mentor_guidance = ?, parent_feedback = ?, overall_fln_level = ?,
                updated_at = ?
            WHERE id = ?
        """, (
            json.dumps(s_data, ensure_ascii=False),
            json.dumps(p_data, ensure_ascii=False),
            json.dumps(t_data, ensure_ascii=False),
            json.dumps(m_data, ensure_ascii=False),
            json.dumps(par_data, ensure_ascii=False),
            fln_lvl,
            now_str,
            existing[0]
        ))
    else:
        cursor.execute("""
            INSERT INTO hpc_evaluations (
                student_id, academic_year_id, term, evaluator_user_id, peer_student_id,
                self_assessment, peer_assessment, teacher_assessment, mentor_guidance,
                parent_feedback, overall_fln_level, status, created_at, updated_at
            ) VALUES (?, 1, 1, 1, ?, ?, ?, ?, ?, ?, ?, 'completed', ?, ?)
        """, (
            sid,
            peer_id,
            json.dumps(s_data, ensure_ascii=False),
            json.dumps(p_data, ensure_ascii=False),
            json.dumps(t_data, ensure_ascii=False),
            json.dumps(m_data, ensure_ascii=False),
            json.dumps(par_data, ensure_ascii=False),
            fln_lvl,
            now_str,
            now_str
        ))

conn.commit()
count = cursor.execute("SELECT COUNT(*) FROM hpc_evaluations").fetchone()[0]
print(f"Total HPC evaluations seeded: {count}")
conn.close()
