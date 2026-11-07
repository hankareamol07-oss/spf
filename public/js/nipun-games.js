/**
 * निपुण महाराष्ट्र व AppleMath गेम इंजिन (इयत्ता चौथी विशेष)
 * Sharad Pawar Inspire Fellowship in Education 2026-27
 * जि. प. प्राथमिक शाळा नं. २ कुची, ता. कवठेमहांकाळ, जि. सांगली
 */

(function () {
    'use strict';

    const root = document.querySelector('[data-nipun-game-root]');
    if (!root) return;

    const stateElement = document.getElementById(root.dataset.stateElement);
    if (!stateElement) return;

    let state = JSON.parse(stateElement.textContent);
    let soundEnabled = true;
    let submitting = false;
    let timerId = null;
    let timerDeadline = state.session.remaining_seconds === null
        ? null
        : Date.now() + (state.session.remaining_seconds * 1000);

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // UI Elements
    const elements = {
        score: root.querySelector('[data-game-score]'),
        lives: root.querySelector('[data-game-lives]'),
        timer: root.querySelector('[data-game-timer]'),
        progress: root.querySelector('[data-game-progress]'),
        progressBar: root.querySelector('[data-game-progress-bar]'),
        instruction: root.querySelector('[data-game-instruction]'),
        listenBtn: root.querySelector('[data-game-listen]'),
        prompt: root.querySelector('[data-game-prompt]'),
        visualArea: root.querySelector('[data-game-visual-area]'),
        choicesArea: root.querySelector('[data-game-choices]'),
        feedbackBanner: root.querySelector('[data-game-feedback-banner]'),
        feedbackTitle: root.querySelector('[data-game-feedback-title]'),
        feedbackMsg: root.querySelector('[data-game-feedback-msg]'),
        soundBtn: root.querySelector('[data-game-sound]'),
        loading: root.querySelector('[data-game-loading]'),
    };

    // =========================================================================
    // 1. Web Audio Synthesizer (Zero-latency offline synthesized sounds)
    // =========================================================================
    let audioCtx = null;
    function getAudioContext() {
        if (!audioCtx && (window.AudioContext || window.webkitAudioContext)) {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
        if (audioCtx && audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
        return audioCtx;
    }

    function playSound(type) {
        if (!soundEnabled) return;
        try {
            const ctx = getAudioContext();
            if (!ctx) return;

            const now = ctx.currentTime;

            if (type === 'success') {
                // Joyful celebratory arpeggio: C5 -> E5 -> G5 -> C6
                const notes = [523.25, 659.25, 783.99, 1046.50];
                notes.forEach((freq, idx) => {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'triangle';
                    osc.frequency.value = freq;
                    gain.gain.setValueAtTime(0, now + (idx * 0.08));
                    gain.gain.linearRampToValueAtTime(0.2, now + (idx * 0.08) + 0.02);
                    gain.gain.exponentialRampToValueAtTime(0.001, now + (idx * 0.08) + 0.28);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start(now + (idx * 0.08));
                    osc.stop(now + (idx * 0.08) + 0.3);
                });
            } else if (type === 'apple_pluck') {
                // Bubble / Apple pluck sound
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(750, now);
                osc.frequency.exponentialRampToValueAtTime(240, now + 0.12);
                gain.gain.setValueAtTime(0.3, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.12);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(now);
                osc.stop(now + 0.13);
            } else if (type === 'frog_jump') {
                // Cartoon spring boing
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(260, now);
                osc.frequency.linearRampToValueAtTime(580, now + 0.14);
                osc.frequency.exponentialRampToValueAtTime(320, now + 0.28);
                gain.gain.setValueAtTime(0.25, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.28);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(now);
                osc.stop(now + 0.3);
            } else if (type === 'coin') {
                // Metallic clink
                [2200, 3100].forEach((freq) => {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.value = freq;
                    gain.gain.setValueAtTime(0.18, now);
                    gain.gain.exponentialRampToValueAtTime(0.001, now + 0.25);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start(now);
                    osc.stop(now + 0.26);
                });
            } else if (type === 'error') {
                // Gentle low note
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(220, now);
                gain.gain.setValueAtTime(0.2, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.24);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(now);
                osc.stop(now + 0.25);
            }
        } catch (e) {
            console.warn('Web Audio error:', e);
        }
    }

    // =========================================================================
    // 2. Marathi Text-to-Speech
    // =========================================================================
    function speakMarathi(text) {
        if (!soundEnabled || !text || !window.speechSynthesis) return;
        window.speechSynthesis.cancel();
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'mr-IN';
        utterance.rate = 0.85;
        window.speechSynthesis.speak(utterance);
    }

    // =========================================================================
    // 3. Simple Confetti Effect
    // =========================================================================
    function triggerConfetti() {
        const colors = ['#e63946', '#ffb703', '#2a9d8f', '#5c34d4', '#0dcaf0'];
        const canvas = document.createElement('canvas');
        canvas.className = 'nipun-confetti-canvas';
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        document.body.appendChild(canvas);

        const ctx = canvas.getContext('2d');
        const particles = [];
        for (let i = 0; i < 45; i++) {
            particles.push({
                x: window.innerWidth * 0.5 + (Math.random() - 0.5) * 300,
                y: window.innerHeight * 0.45 + (Math.random() - 0.5) * 150,
                vx: (Math.random() - 0.5) * 10,
                vy: Math.random() * -8 - 4,
                size: Math.random() * 8 + 5,
                color: colors[Math.floor(Math.random() * colors.length)],
                rotation: Math.random() * 360,
                vRot: (Math.random() - 0.5) * 12,
                opacity: 1,
            });
        }

        let frame = 0;
        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach((p) => {
                p.x += p.vx;
                p.y += p.vy;
                p.vy += 0.35; // Gravity
                p.rotation += p.vRot;
                p.opacity -= 0.016;

                if (p.opacity > 0) {
                    ctx.save();
                    ctx.translate(p.x, p.y);
                    ctx.rotate((p.rotation * Math.PI) / 180);
                    ctx.fillStyle = p.color;
                    ctx.globalAlpha = Math.max(0, p.opacity);
                    ctx.fillRect(-p.size / 2, -p.size / 2, p.size, p.size);
                    ctx.restore();
                }
            });

            frame++;
            if (frame < 60) {
                requestAnimationFrame(animate);
            } else {
                canvas.remove();
            }
        }
        animate();
    }

    // =========================================================================
    // 4. Timer & Submission
    // =========================================================================
    function remainingSeconds() {
        if (timerDeadline === null) return null;
        return Math.max(0, Math.ceil((timerDeadline - Date.now()) / 1000));
    }

    function updateTimer() {
        if (!elements.timer) return;
        const seconds = remainingSeconds();
        if (seconds === null) {
            elements.timer.textContent = '∞';
            return;
        }
        elements.timer.textContent = `${seconds}s`;
        if (seconds === 0) {
            clearInterval(timerId);
            finishGame();
        }
    }

    async function finishGame() {
        if (submitting) return;
        submitting = true;
        if (elements.loading) elements.loading.hidden = false;

        try {
            const resp = await fetch(root.dataset.finishUrl, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({}),
            });
            window.location.assign(root.dataset.resultUrl);
        } catch (e) {
            window.location.assign(root.dataset.resultUrl);
        }
    }

    async function submitAnswer(questionId, value, btn) {
        if (submitting) return;
        submitting = true;

        elements.choicesArea.querySelectorAll('button').forEach((b) => {
            b.disabled = true;
        });
        if (btn) btn.classList.add('is-selected');

        try {
            const answerUrl = root.dataset.answerUrl.replace('__QUESTION__', questionId);
            const resp = await fetch(answerUrl, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ answer: { value: String(value) } }),
            });

            if (!resp.ok) throw new Error('Answer submission failed');

            state = await resp.json();
            timerDeadline = state.session.remaining_seconds === null
                ? null
                : Date.now() + (state.session.remaining_seconds * 1000);

            const isSuccess = state.feedback?.tone === 'success';
            playSound(isSuccess ? 'success' : 'error');

            if (isSuccess) {
                triggerConfetti();
            }

            showFeedback(state.feedback);

            if (state.completed) {
                setTimeout(() => {
                    window.location.assign(root.dataset.resultUrl);
                }, 900);
                return;
            }

            setTimeout(() => {
                hideFeedback();
                render();
                submitting = false;
            }, 850);
        } catch (e) {
            showFeedback({
                tone: 'error',
                title: 'पुन्हा प्रयत्न करा',
                message: 'उत्तर नोंदवता आले नाही. पुन्हा स्पर्श करा.',
            });
            elements.choicesArea.querySelectorAll('button').forEach((b) => {
                b.disabled = false;
            });
            submitting = false;
        }
    }

    function showFeedback(feedback) {
        if (!elements.feedbackBanner || !feedback) return;
        elements.feedbackTitle.textContent = feedback.title || (feedback.tone === 'success' ? 'शाब्बास! 🌟' : 'पुन्हा प्रयत्न करा');
        elements.feedbackMsg.textContent = feedback.message || '';
        elements.feedbackBanner.classList.toggle('is-error', feedback.tone !== 'success');
        elements.feedbackBanner.classList.add('show');
    }

    function hideFeedback() {
        if (!elements.feedbackBanner) return;
        elements.feedbackBanner.classList.remove('show');
    }

    // =========================================================================
    // 5. Visual Interactive Component Renderers
    // =========================================================================

    // 5A. AppleMath (सफरचंद झाड व टोपली)
    function renderAppleMath(question, operands, operator) {
        elements.visualArea.innerHTML = '';
        const stage = document.createElement('div');
        stage.className = 'apple-tree-stage';

        const [op1, op2] = operands || [4, 3];
        const displayOp1 = Math.min(op1, 10);
        const displayOp2 = Math.min(op2, 10);

        let basketCount = 0;

        // Tree SVG container
        const treeSvg = `
            <div class="apple-tree-svg-wrap">
                <svg class="apple-tree-canopy" viewBox="0 0 400 280">
                    <!-- Tree Trunk -->
                    <path d="M175 160 L160 270 Q200 275 240 270 L225 160 Z" fill="#784b2a"/>
                    <!-- Lush Tree Canopy -->
                    <path d="M70 140 Q40 80 100 40 Q160 10 200 35 Q250 10 310 45 Q370 85 340 145 Q370 190 320 220 Q270 240 200 230 Q130 240 80 215 Q30 185 70 140 Z" fill="#2d6a4f" opacity="0.95"/>
                    <path d="M90 130 Q70 85 115 55 Q165 30 200 50 Q240 30 290 55 Q335 90 315 135 Q340 175 300 200 Q255 215 200 210 Q145 215 100 195 Q60 170 90 130 Z" fill="#40916c"/>
                </svg>
                <div class="tree-apples-overlay" style="position: absolute; inset: 0; pointer-events: auto;"></div>
            </div>
        `;
        stage.innerHTML = treeSvg;

        const applesOverlay = stage.querySelector('.tree-apples-overlay');

        // Positions for apples on tree branches
        const applePositions = [
            { x: 100, y: 70 }, { x: 140, y: 55 }, { x: 190, y: 65 }, { x: 240, y: 60 }, { x: 285, y: 80 },
            { x: 80, y: 120 }, { x: 130, y: 110 }, { x: 180, y: 125 }, { x: 235, y: 115 }, { x: 290, y: 135 },
            { x: 110, y: 165 }, { x: 160, y: 170 }, { x: 220, y: 175 }, { x: 270, y: 170 }
        ];

        const totalVisualApples = Math.min(displayOp1 + (operator === '+' ? displayOp2 : 0), applePositions.length);

        for (let i = 0; i < totalVisualApples; i++) {
            const pos = applePositions[i];
            const apple = document.createElement('div');
            apple.className = 'tree-apple';
            apple.style.position = 'absolute';
            apple.style.left = `${pos.x}px`;
            apple.style.top = `${pos.y}px`;
            apple.style.fontSize = '2.2rem';
            apple.style.lineHeight = '1';
            apple.style.cursor = 'pointer';
            apple.style.userSelect = 'none';
            apple.textContent = i < displayOp1 ? '🍎' : '🍏';
            apple.title = 'सफरचंद टोपलीत टाका';

            apple.addEventListener('click', () => {
                if (apple.classList.contains('plucked')) return;
                apple.classList.add('plucked');
                playSound('apple_pluck');
                basketCount++;
                const countBadge = stage.querySelector('.apple-basket-count');
                if (countBadge) {
                    countBadge.textContent = basketCount.toLocaleString('mr-IN');
                }
            });

            applesOverlay.appendChild(apple);
        }

        // Basket & Live Counter Tray
        const basketTray = document.createElement('div');
        basketTray.className = 'apple-basket-tray';
        basketTray.innerHTML = `
            <div class="apple-basket-icon">🧺</div>
            <div class="apple-basket-info">
                <div style="font-size: 0.85rem; font-weight: 700; color: #40916c;">टोपलीतील सफरचंद (मोजा)</div>
                <div class="apple-basket-count">०</div>
            </div>
            <div class="apple-math-equation">
                <span>${op1.toLocaleString('mr-IN')}</span>
                <span style="color: #ff6b35;">${operator || '+'}</span>
                <span>${op2.toLocaleString('mr-IN')}</span>
                <span>=</span>
                <span style="color: #5c34d4;">?</span>
            </div>
        `;
        stage.appendChild(basketTray);

        elements.visualArea.appendChild(stage);
    }

    // 5B. Place Value House (स्थानिक किंमत घर - हजार, शतक, दशक, एकक)
    function renderPlaceValueHouse(question) {
        elements.visualArea.innerHTML = '';
        const pres = question.presentation || {};
        const house = document.createElement('div');
        house.className = 'nipun-place-house';

        const numStr = String(pres.number || '').replace(/[^\d०-९]/g, '');
        const targetDigit = String(pres.target_digit || '');

        const colLabels = numStr.length >= 4
            ? ['हजार', 'शतक', 'दशक', 'एकक']
            : (numStr.length === 3 ? ['शतक', 'दशक', 'एकक'] : ['दशक', 'एकक']);

        const icons = ['🏛️', '🧱', '🪵', '🥢'];

        const chars = Array.from(numStr);
        chars.forEach((digit, idx) => {
            const room = document.createElement('div');
            const isTarget = digit === targetDigit;
            room.className = `place-room ${isTarget ? 'is-target' : ''}`;
            room.innerHTML = `
                <span class="place-room-label">${colLabels[idx] || 'स्थान'}</span>
                <div class="place-room-digit">${digit}</div>
                <div class="place-room-visual">${icons[idx] || '⭐'}</div>
            `;
            house.appendChild(room);
        });

        elements.visualArea.appendChild(house);
    }

    // 5C. Frog Jump Number Line (संख्यारेषा बेडूक उडी)
    function renderFrogJump(question) {
        elements.visualArea.innerHTML = '';
        const pres = question.presentation || {};
        const start = Number(pres.start || 0);
        const jump = Number(pres.jump || 3);
        const end = start + jump;

        const stage = document.createElement('div');
        stage.className = 'frog-pond-stage';

        const track = document.createElement('div');
        track.className = 'frog-track';

        const minVal = Math.max(0, start - 1);
        const maxVal = end + 2;

        let startNodeOffset = 0;

        for (let val = minVal; val <= maxVal; val++) {
            const node = document.createElement('div');
            const isStart = val === start;
            const isTarget = val === end;
            node.className = `lilypad-node ${isStart ? 'is-start' : ''} ${isTarget ? 'is-target' : ''}`;
            node.textContent = val.toLocaleString('mr-IN');
            node.dataset.value = val;

            if (isStart) {
                const frog = document.createElement('span');
                frog.className = 'animated-frog';
                frog.textContent = '🐸';
                node.appendChild(frog);
            }

            track.appendChild(node);
        }

        stage.appendChild(track);

        const banner = document.createElement('div');
        banner.style.textAlign = 'center';
        banner.style.marginTop = '0.75rem';
        banner.style.fontWeight = '700';
        banner.style.color = '#0077b6';
        banner.innerHTML = `बेडूक <strong>${start.toLocaleString('mr-IN')}</strong> वरून पुढे <strong>+${jump.toLocaleString('mr-IN')}</strong> उड्या मारणार आहे 🚀`;
        stage.appendChild(banner);

        elements.visualArea.appendChild(stage);

        // Gentle boing sound
        setTimeout(() => playSound('frog_jump'), 200);
    }

    // 5D. Fraction Pizza
    function renderFraction(question) {
        elements.visualArea.innerHTML = '';
        const pres = question.presentation || {};
        const num = Number(pres.numerator || 1);
        const den = Number(pres.denominator || 2);
        const angle = Math.round((num / den) * 360);

        const pizza = document.createElement('div');
        pizza.className = 'nipun-pizza-wrap';
        pizza.style.setProperty('--fraction-angle', `${angle}deg`);
        pizza.innerHTML = `<div class="pizza-slice-highlight"></div>`;

        elements.visualArea.appendChild(pizza);
    }

    // 5E. Default Visuals
    function renderDefaultVisual(question) {
        elements.visualArea.innerHTML = '';
        const pres = question.presentation || {};
        if (pres.visual) {
            const visualBox = document.createElement('div');
            visualBox.style.fontSize = '3.5rem';
            visualBox.style.margin = '1rem auto';
            visualBox.textContent = pres.visual;
            elements.visualArea.appendChild(visualBox);
        }
    }

    // =========================================================================
    // 6. Choices Renderer
    // =========================================================================
    function renderChoices(question) {
        elements.choicesArea.innerHTML = '';
        if (!question.choices || !question.choices.length) return;

        question.choices.forEach((choice, idx) => {
            const btn = document.createElement('button');
            const isLong = String(choice.label || '').length > 6;
            btn.className = `nipun-choice-card ${isLong ? 'is-text-label' : ''}`;
            btn.type = 'button';
            btn.textContent = choice.label;
            btn.dataset.value = choice.value;

            btn.addEventListener('click', () => {
                submitAnswer(question.id, choice.value, btn);
            });

            elements.choicesArea.appendChild(btn);
        });
    }

    // =========================================================================
    // 7. Main Game Render Function
    // =========================================================================
    function render() {
        const { stats, question, session } = state;
        const totalQ = stats.question_count || 1;
        const answeredQ = stats.answered_count || 0;
        const percent = Math.min(100, Math.round((answeredQ / totalQ) * 100));

        if (elements.score) elements.score.textContent = (stats.score || 0).toLocaleString('mr-IN');
        if (elements.lives) {
            const remaining = stats.lives_remaining ?? 3;
            const total = stats.lives_total ?? 3;
            elements.lives.textContent = '♥ '.repeat(remaining) + '♡ '.repeat(Math.max(0, total - remaining));
        }
        if (elements.progress) elements.progress.textContent = `${answeredQ.toLocaleString('mr-IN')} / ${totalQ.toLocaleString('mr-IN')}`;
        if (elements.progressBar) elements.progressBar.style.width = `${percent}%`;

        if (!question) {
            finishGame();
            return;
        }

        const pres = question.presentation || {};
        const interaction = pres.interaction || state.game.engine_key;

        if (elements.instruction) {
            elements.instruction.textContent = pres.instruction || state.game.description_marathi || 'योग्य पर्यायाला स्पर्श करा.';
        }
        if (elements.prompt) {
            elements.prompt.textContent = question.prompt_marathi || question.prompt;
        }

        // Audio text
        const audioText = pres.audio_text || question.prompt_marathi || '';
        if (elements.listenBtn) {
            elements.listenBtn.dataset.audioText = audioText;
            elements.listenBtn.hidden = !audioText;
        }

        // Render appropriate visual engine
        if (interaction === 'apple_math' || interaction === 'arithmetic_adventure') {
            const operands = pres.operands || [4, 3];
            const operator = pres.operator || '+';
            renderAppleMath(question, operands, operator);
        } else if (interaction === 'place_value_house') {
            renderPlaceValueHouse(question);
        } else if (interaction === 'frog_jump' || interaction === 'number_line') {
            renderFrogJump(question);
        } else if (interaction === 'fraction_pizza') {
            renderFraction(question);
        } else {
            renderDefaultVisual(question);
        }

        // Render option choice buttons
        renderChoices(question);

        // Auto speak on first load if audio present
        if (soundEnabled && audioText) {
            setTimeout(() => speakMarathi(audioText), 250);
        }

        updateTimer();
    }

    // Listen button event
    if (elements.listenBtn) {
        elements.listenBtn.addEventListener('click', () => {
            speakMarathi(elements.listenBtn.dataset.audioText);
        });
    }

    // Sound toggle event
    if (elements.soundBtn) {
        elements.soundBtn.addEventListener('click', () => {
            soundEnabled = !soundEnabled;
            elements.soundBtn.setAttribute('aria-pressed', soundEnabled ? 'true' : 'false');
            elements.soundBtn.innerHTML = soundEnabled
                ? '<i class="bi bi-volume-up" aria-hidden="true"></i> <span>आवाज सुरू</span>'
                : '<i class="bi bi-volume-mute" aria-hidden="true"></i> <span>आवाज बंद</span>';
        });
    }

    // Initialize
    render();
    timerId = setInterval(updateTimer, 1000);

})();
