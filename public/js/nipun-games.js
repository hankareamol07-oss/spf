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

    const elements = {
        score: root.querySelector('[data-game-score]'),
        lives: root.querySelector('[data-game-lives]'),
        timer: root.querySelector('[data-game-timer]'),
        progress: root.querySelector('[data-game-progress]'),
        progressBar: root.querySelector('[data-game-progress-bar]'),
        instruction: root.querySelector('[data-game-instruction]'),
        listenBtn: root.querySelector('[data-game-listen]'),
        prompt: root.querySelector('[data-game-prompt]'),
        contextBox: root.querySelector('[data-game-context]'),
        visualArea: root.querySelector('[data-game-visual-area]'),
        choicesArea: root.querySelector('[data-game-choices]'),
        feedbackBanner: root.querySelector('[data-game-feedback-banner]'),
        feedbackTitle: root.querySelector('[data-game-feedback-title]'),
        feedbackMsg: root.querySelector('[data-game-feedback-msg]'),
        soundBtn: root.querySelector('[data-game-sound]'),
        loading: root.querySelector('[data-game-loading]'),
    };

    // =========================================================================
    // 1. Web Audio Synthesizer
    // =========================================================================
    let audioCtx = null;
    function getAudioContext() {
        if (!audioCtx && (window.AudioContext || window.webkitAudioContext)) {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
        if (audioCtx && audioCtx.state === 'suspended') {
            audioCtx.resume().catch(() => {});
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
                [523.25, 659.25, 783.99, 1046.50].forEach((freq, idx) => {
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
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(750, now);
                osc.frequency.exponentialRampToValueAtTime(240, now + 0.12);
                gain.gain.setValueAtTime(0.28, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.12);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(now);
                osc.stop(now + 0.13);
            } else if (type === 'frog_jump') {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(260, now);
                osc.frequency.linearRampToValueAtTime(580, now + 0.14);
                osc.frequency.exponentialRampToValueAtTime(320, now + 0.28);
                gain.gain.setValueAtTime(0.22, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.28);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(now);
                osc.stop(now + 0.3);
            } else if (type === 'error') {
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
        } catch {
            // Audio synthesis unavailable in environment
        }
    }

    // =========================================================================
    // 2. Marathi Text-to-Speech
    // =========================================================================
    function speakMarathi(text) {
        if (!soundEnabled || !text || !window.speechSynthesis) return;
        try {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(String(text));
            const voices = window.speechSynthesis.getVoices ? window.speechSynthesis.getVoices() : [];
            const mrVoice = voices.find((v) => v.lang && v.lang.toLowerCase().startsWith('mr'))
                || voices.find((v) => v.lang && v.lang.toLowerCase().startsWith('hi'));
            if (mrVoice) {
                utterance.voice = mrVoice;
                utterance.lang = mrVoice.lang;
            } else {
                utterance.lang = 'mr-IN';
            }
            utterance.rate = 0.85;
            window.speechSynthesis.speak(utterance);
        } catch {
            // Speech synthesis unsupported or blocked before user gesture
        }
    }

    // =========================================================================
    // 3. Confetti Effect
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
                p.vy += 0.35;
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
    function stopTimer() {
        if (timerId !== null) {
            clearInterval(timerId);
            timerId = null;
        }
    }

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
            stopTimer();
            finishGame();
        }
    }

    async function finishGame() {
        if (submitting) return;
        submitting = true;
        stopTimer();
        const retryButton = root.querySelector('[data-game-finish-retry]');
        if (retryButton) retryButton.hidden = true;
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
            if (!resp.ok) throw new Error('Game completion was not saved.');
            const finalState = await resp.json();
            if (!finalState.completed) throw new Error('Game has not completed.');
            window.location.assign(root.dataset.resultUrl);
        } catch (e) {
            submitting = false;
            showFeedback({
                tone: 'error',
                title: 'जोडणी तपासूया',
                message: 'निकाल जतन झाला नाही. इंटरनेट तपासून पुन्हा प्रयत्न कर.',
            });
            if (retryButton) retryButton.hidden = false;
        } finally {
            if (elements.loading) elements.loading.hidden = true;
        }
    }
    root.querySelector('[data-game-finish-retry]')?.addEventListener('click', finishGame);

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

            const nextState = await resp.json();
            if (!nextState.session || !nextState.stats) throw new Error('Invalid game response');
            state = nextState;
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
                stopTimer();
                setTimeout(() => {
                    window.location.assign(root.dataset.resultUrl);
                }, 850);
                return;
            }

            setTimeout(() => {
                hideFeedback();
                submitting = false;
                render();
                elements.prompt?.focus({ preventScroll: true });
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
            if (btn) btn.classList.remove('is-selected');
            submitting = false;
        }
    }

    function showFeedback(feedback) {
        if (!elements.feedbackBanner || !feedback) return;
        elements.feedbackTitle.textContent = feedback.title || (feedback.tone === 'success' ? 'शाब्बास!' : 'पुन्हा प्रयत्न करा');
        elements.feedbackMsg.textContent = feedback.message || '';
        elements.feedbackBanner.classList.toggle('is-error', feedback.tone !== 'success');
        elements.feedbackBanner.classList.add('show');
    }

    function hideFeedback() {
        if (!elements.feedbackBanner) return;
        elements.feedbackBanner.classList.remove('show');
    }

    function decodeText(raw) {
        return String(raw || '').replace(/\\n/g, '\n');
    }

    // =========================================================================
    // 5. Visual Interactive Component Renderers
    // =========================================================================

    // 5A. AppleMath (सफरचंद झाड व टोपली) - Responsive % coordinates
    function renderAppleMath(question, operands, operator) {
        elements.visualArea.innerHTML = '';
        const stage = document.createElement('div');
        stage.className = 'apple-tree-stage';

        const op1 = Number((operands && operands[0]) ?? 4);
        const op2 = Number((operands && operands[1]) ?? 3);
        const isAddition = (operator || '+') === '+';
        const isInteractiveSmall = isAddition
            ? (op1 <= 10 && op2 <= 10 && (op1 + op2) <= 14)
            : (op1 <= 14);

        const applePositions = [
            { x: 25, y: 25 }, { x: 35, y: 20 }, { x: 47, y: 23 }, { x: 60, y: 21 }, { x: 71, y: 28 },
            { x: 20, y: 42 }, { x: 32, y: 39 }, { x: 45, y: 44 }, { x: 59, y: 41 }, { x: 72, y: 48 },
            { x: 28, y: 58 }, { x: 40, y: 60 }, { x: 55, y: 62 }, { x: 67, y: 59 },
        ];

        const treeSvg = `
            <div class="apple-tree-svg-wrap">
                <svg class="apple-tree-canopy" viewBox="0 0 400 280" aria-hidden="true">
                    <path d="M175 160 L160 270 Q200 275 240 270 L225 160 Z" fill="#784b2a"/>
                    <path d="M70 140 Q40 80 100 40 Q160 10 200 35 Q250 10 310 45 Q370 85 340 145 Q370 190 320 220 Q270 240 200 230 Q130 240 80 215 Q30 185 70 140 Z" fill="#2d6a4f" opacity="0.95"/>
                    <path d="M90 130 Q70 85 115 55 Q165 30 200 50 Q240 30 290 55 Q335 90 315 135 Q340 175 300 200 Q255 215 200 210 Q145 215 100 195 Q60 170 90 130 Z" fill="#40916c"/>
                </svg>
                <div class="tree-apples-overlay"></div>
            </div>
        `;
        stage.innerHTML = treeSvg;

        const applesOverlay = stage.querySelector('.tree-apples-overlay');
        const redCount = isInteractiveSmall ? Math.min(op1, applePositions.length) : 6;
        const greenCount = isAddition && isInteractiveSmall ? Math.min(op2, applePositions.length - redCount) : (isAddition ? 4 : 0);
        const totalVisualApples = redCount + greenCount;

        let pluckedCount = 0;

        for (let i = 0; i < totalVisualApples; i++) {
            const pos = applePositions[i];
            const apple = document.createElement('button');
            apple.type = 'button';
            apple.className = 'tree-apple';
            apple.style.left = `${pos.x}%`;
            apple.style.top = `${pos.y}%`;
            apple.textContent = i < redCount ? '🍎' : '🍏';
            apple.setAttribute('aria-label', isAddition ? 'सफरचंद टोपलीत टाका' : 'सफरचंद झाडावरून काढा');

            apple.addEventListener('click', () => {
                if (apple.classList.contains('plucked')) return;
                apple.classList.add('plucked');
                apple.disabled = true;
                playSound('apple_pluck');
                pluckedCount++;
                const countBadge = stage.querySelector('.apple-basket-count');
                if (countBadge && isInteractiveSmall) {
                    const shown = isAddition ? pluckedCount : Math.max(0, op1 - pluckedCount);
                    countBadge.textContent = shown.toLocaleString('mr-IN');
                }
            });

            applesOverlay.appendChild(apple);
        }

        const basketTray = document.createElement('div');
        basketTray.className = 'apple-basket-tray';
        const trayLabel = !isInteractiveSmall
            ? 'प्रतीकात्मक झाड · मोठ्या संख्येचे समीकरण सोडवा'
            : (isAddition ? 'टोपलीतील सफरचंद (मोजा)' : `झाडावर उरलेली सफरचंद (${op2.toLocaleString('mr-IN')} काढा)`);
        const initialCountText = !isInteractiveSmall
            ? '—'
            : (isAddition ? '०' : op1.toLocaleString('mr-IN'));

        basketTray.innerHTML = `
            <div class="apple-basket-icon" aria-hidden="true">🧺</div>
            <div class="apple-basket-info">
                <div style="font-size: 0.85rem; font-weight: 700; color: #2d6a4f;">${trayLabel}</div>
                <div class="apple-basket-count">${initialCountText}</div>
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

    // 5B. Place Value House (स्थानिक किंमत घर - अचूक स्थान हायलाइट)
    function renderPlaceValueHouse(question) {
        elements.visualArea.innerHTML = '';
        const pres = question.presentation || {};
        const house = document.createElement('div');
        house.className = 'nipun-place-house';

        const numStr = String(pres.number || '').replace(/[^\d०-९]/g, '');
        const targetDigit = String(pres.target_digit || '');
        const targetPlace = pres.place ? Number(pres.place) : null;
        const placeNamesFromRight = ['एकक', 'दशक', 'शतक', 'हजार', 'दशहजार'];
        const iconsFromRight = ['🥢', '🪵', '🧱', '🏛️', '🏰'];

        const chars = Array.from(numStr);
        chars.forEach((digit, idx) => {
            const offsetFromRight = chars.length - 1 - idx;
            const colPlace = Math.pow(10, offsetFromRight);
            const isTarget = targetPlace !== null
                ? colPlace === targetPlace
                : digit === targetDigit;

            const room = document.createElement('div');
            room.className = `place-room ${isTarget ? 'is-target' : ''}`;
            room.innerHTML = `
                <span class="place-room-label">${placeNamesFromRight[offsetFromRight] || 'स्थान'}</span>
                <div class="place-room-digit">${digit}</div>
                <div class="place-room-visual" aria-hidden="true">${iconsFromRight[offsetFromRight] || '⭐'}</div>
            `;
            house.appendChild(room);
        });

        elements.visualArea.appendChild(house);
    }

    // 5C. Frog Jump Number Line (संख्यारेषा बेडूक उडी - उत्तर न दाखवता)
    function renderFrogJump(question) {
        elements.visualArea.innerHTML = '';
        const pres = question.presentation || {};
        const start = Number(pres.start ?? 0);
        const jump = Number(pres.jump ?? 3);

        const stage = document.createElement('div');
        stage.className = 'frog-pond-stage';

        if (jump <= 8) {
            const track = document.createElement('div');
            track.className = 'frog-track';
            const minVal = Math.max(0, start - 1);
            const maxVal = start + jump + 2;

            for (let val = minVal; val <= maxVal; val++) {
                const node = document.createElement('div');
                const isStart = val === start;
                node.className = `lilypad-node ${isStart ? 'is-start' : ''}`;
                node.textContent = val.toLocaleString('mr-IN');

                if (isStart) {
                    const frog = document.createElement('span');
                    frog.className = 'animated-frog';
                    frog.textContent = '🐸';
                    frog.setAttribute('aria-hidden', 'true');
                    node.appendChild(frog);
                }

                track.appendChild(node);
            }
            stage.appendChild(track);
        } else {
            const compact = document.createElement('div');
            compact.className = 'frog-track';
            [
                { text: start.toLocaleString('mr-IN'), cls: 'is-start', frog: true },
                { text: `+${jump.toLocaleString('mr-IN')}`, cls: 'is-hop-badge', frog: false },
                { text: '?', cls: '', frog: false },
            ].forEach((item) => {
                const node = document.createElement('div');
                node.className = `lilypad-node ${item.cls}`;
                node.textContent = item.text;
                if (item.frog) {
                    const frog = document.createElement('span');
                    frog.className = 'animated-frog';
                    frog.textContent = '🐸';
                    frog.setAttribute('aria-hidden', 'true');
                    node.appendChild(frog);
                }
                compact.appendChild(node);
            });
            stage.appendChild(compact);
        }

        const banner = document.createElement('div');
        banner.className = 'frog-jump-banner';
        banner.innerHTML = `बेडूक <strong>${start.toLocaleString('mr-IN')}</strong> वरून पुढे <strong>+${jump.toLocaleString('mr-IN')}</strong> उड्या मारणार आहे`;
        stage.appendChild(banner);

        elements.visualArea.appendChild(stage);
        setTimeout(() => playSound('frog_jump'), 180);
    }

    // 5D. Fraction Pizza (समान भागांच्या रेषांसह)
    function renderFraction(question) {
        elements.visualArea.innerHTML = '';
        const pres = question.presentation || {};
        const num = Number(pres.numerator || 1);
        const den = Math.max(2, Number(pres.denominator || 2));
        const angle = Math.round((num / den) * 360);
        const sliceDeg = 360 / den;

        const wrap = document.createElement('div');
        wrap.className = 'nipun-pizza-stage';

        const pizza = document.createElement('div');
        pizza.className = 'nipun-pizza-wrap';
        pizza.style.setProperty('--fraction-angle', `${angle}deg`);
        pizza.innerHTML = `
            <div class="pizza-slice-highlight"></div>
            <div class="pizza-slice-grid" style="--slice-deg: ${sliceDeg}deg;"></div>
        `;

        const caption = document.createElement('div');
        caption.className = 'nipun-pizza-caption';
        caption.textContent = `एकूण समान भाग: ${den.toLocaleString('mr-IN')} · रंगवलेले भाग: ${num.toLocaleString('mr-IN')}`;

        wrap.append(pizza, caption);
        elements.visualArea.appendChild(wrap);
    }

    // 5E. Number Train & Word Train (आगगाडी डबे)
    function renderTrain(items) {
        elements.visualArea.innerHTML = '';
        const train = document.createElement('div');
        train.className = 'nipun-train';
        const engine = document.createElement('span');
        engine.className = 'nipun-train-engine';
        engine.textContent = '🚂';
        engine.setAttribute('aria-hidden', 'true');
        train.appendChild(engine);

        items.forEach((raw) => {
            const text = String(raw).trim();
            const isBlank = text === '__' || text === '?';
            const car = document.createElement('span');
            car.className = `nipun-train-car ${isBlank ? 'is-empty' : ''}`;
            car.textContent = isBlank ? '?' : text;
            train.appendChild(car);
        });

        elements.visualArea.appendChild(train);
    }

    // 5F. Number Comparison Scale (मोठी / लहान संख्या तराजू)
    function renderComparisonScale(question) {
        elements.visualArea.innerHTML = '';
        const pres = question.presentation || {};
        const nums = pres.numbers || [];
        if (nums.length < 2) {
            renderDefaultVisual(question);
            return;
        }
        const scale = document.createElement('div');
        scale.className = 'nipun-scale';
        scale.innerHTML = `
            <span class="nipun-scale-num">${nums[0]}</span>
            <span class="nipun-scale-icon" aria-hidden="true">⚖️</span>
            <span class="nipun-scale-num">${nums[1]}</span>
        `;
        elements.visualArea.appendChild(scale);
    }

    // 5G. Multiplication Array (अंतराळ तारे रांग व स्तंभ)
    function renderMultiplicationArray(question) {
        elements.visualArea.innerHTML = '';
        const pres = question.presentation || {};
        const rows = Math.max(1, Number(pres.rows || 1));
        const cols = Math.max(1, Number(pres.columns || 1));
        const wrap = document.createElement('div');
        wrap.className = 'nipun-visual-card';

        if (rows * cols <= 48) {
            const grid = document.createElement('div');
            grid.className = 'nipun-star-array';
            grid.style.gridTemplateColumns = `repeat(${Math.min(cols, 12)}, minmax(1.4rem, 2rem))`;
            for (let i = 0; i < rows * cols; i++) {
                const star = document.createElement('span');
                star.textContent = '⭐';
                star.setAttribute('aria-hidden', 'true');
                grid.appendChild(star);
            }
            wrap.appendChild(grid);
        }

        const note = document.createElement('div');
        note.className = 'nipun-visual-caption';
        note.textContent = `${rows.toLocaleString('mr-IN')} ओळी × ${cols.toLocaleString('mr-IN')} स्तंभ`;
        wrap.appendChild(note);
        elements.visualArea.appendChild(wrap);
    }

    // 5H. Division Sharing Baskets (समान वाटप टोपल्या)
    function renderDivisionSharing(question) {
        elements.visualArea.innerHTML = '';
        const pres = question.presentation || {};
        const groups = Math.max(2, Number(pres.groups || 2));
        const objects = Math.max(1, Number(pres.objects || groups));

        const board = document.createElement('div');
        board.className = 'nipun-visual-card';

        if (objects <= 36) {
            const pile = document.createElement('div');
            pile.className = 'nipun-sharing-pile';
            for (let i = 0; i < objects; i++) {
                const dot = document.createElement('span');
                dot.className = 'nipun-sharing-item';
                dot.textContent = '🟠';
                pile.appendChild(dot);
            }
            board.appendChild(pile);
        } else {
            const badge = document.createElement('div');
            badge.className = 'nipun-visual-caption';
            badge.textContent = `एकूण ${objects.toLocaleString('mr-IN')} वस्तू`;
            board.appendChild(badge);
        }

        const baskets = document.createElement('div');
        baskets.className = 'nipun-sharing-baskets';
        for (let g = 1; g <= Math.min(groups, 10); g++) {
            const basket = document.createElement('div');
            basket.className = 'nipun-sharing-basket';
            basket.innerHTML = `<span aria-hidden="true">🧺</span><strong>${g.toLocaleString('mr-IN')}</strong>`;
            baskets.appendChild(basket);
        }
        board.appendChild(baskets);
        elements.visualArea.appendChild(board);
    }

    // 5I. Shopping Receipt (किराणा बाजार पावती)
    function renderShopping(question) {
        elements.visualArea.innerHTML = '';
        const pres = question.presentation || {};
        const qty = Number(pres.quantity || 1);
        const price = Number(pres.price || 1);
        const icon = pres.item_visual || '🛍️';
        const name = pres.item_name || 'वस्तू';

        const card = document.createElement('div');
        card.className = 'nipun-shop-receipt';
        card.innerHTML = `
            <div class="nipun-shop-items">${icon.repeat(Math.min(qty, 10))}</div>
            <div class="nipun-shop-math">${qty.toLocaleString('mr-IN')} ${name} × ₹${price.toLocaleString('mr-IN')}</div>
        `;
        elements.visualArea.appendChild(card);
    }

    // 5J. Token Chips Preview (अक्षर / शब्द कार्ड्स)
    function renderTokenChips(tokens, separator) {
        elements.visualArea.innerHTML = '';
        const wrap = document.createElement('div');
        wrap.className = 'nipun-token-tiles';
        tokens.forEach((tok, idx) => {
            if (idx > 0 && separator) {
                const sep = document.createElement('span');
                sep.className = 'nipun-token-sep';
                sep.textContent = separator;
                wrap.appendChild(sep);
            }
            const tile = document.createElement('span');
            tile.className = 'nipun-token-chip';
            tile.textContent = tok;
            wrap.appendChild(tile);
        });
        elements.visualArea.appendChild(wrap);
    }

    // 5K. Default & Listen Visuals
    function renderDefaultVisual(question) {
        elements.visualArea.innerHTML = '';
        const pres = question.presentation || {};
        if (pres.visual) {
            const text = decodeText(pres.visual);
            if (text.includes('\n')) {
                const pre = document.createElement('pre');
                pre.className = 'nipun-visual-pre';
                pre.textContent = text;
                elements.visualArea.appendChild(pre);
            } else {
                const box = document.createElement('div');
                box.className = 'nipun-visual-badge';
                box.textContent = text;
                elements.visualArea.appendChild(box);
            }
            return;
        }

        if (pres.interaction === 'listen_and_select') {
            const hint = document.createElement('div');
            hint.className = 'nipun-visual-badge nipun-listen-hint';
            hint.textContent = '🎧 शब्द ऐकण्यासाठी वरील "मोठ्याने ऐका" बटणावर स्पर्श करा.';
            elements.visualArea.appendChild(hint);
        }
    }

    // =========================================================================
    // 6. Choices Renderer
    // =========================================================================
    function formatChoiceLabel(choice, interaction) {
        const raw = String(choice.label || choice.value || '');
        if (interaction === 'matra_balloons' && /^[\u0900-\u0903\u093A-\u094F\u0951-\u0957\u0962\u0963]+$/u.test(raw)) {
            return `◌${raw}`;
        }
        return raw;
    }

    function renderChoices(question) {
        elements.choicesArea.innerHTML = '';
        if (!question.choices || !question.choices.length) return;

        const interaction = question.presentation?.interaction || state.game.engine_key;
        const isBalloon = interaction === 'matra_balloons';
        const isCatch = interaction === 'catch';
        const isFraction = interaction === 'fraction_pizza';

        question.choices.forEach((choice) => {
            const btn = document.createElement('button');
            const labelText = formatChoiceLabel(choice, interaction);
            const isLong = labelText.length > 6;

            const classes = ['nipun-choice-card'];
            if (isLong) classes.push('is-text-label');
            if (isBalloon) classes.push('nipun-choice-balloon');
            if (isCatch) classes.push('nipun-choice-catch');
            btn.className = classes.join(' ');
            btn.type = 'button';
            btn.dataset.value = choice.value;

            if (isFraction && String(choice.label).includes('/')) {
                const [num, den] = String(choice.label).split('/').map(Number);
                if (den > 0) {
                    const mini = document.createElement('span');
                    mini.className = 'nipun-mini-pizza';
                    mini.style.setProperty('--fraction-angle', `${Math.round((num / den) * 360)}deg`);
                    const lbl = document.createElement('span');
                    lbl.textContent = labelText;
                    btn.append(mini, lbl);
                } else {
                    btn.textContent = labelText;
                }
            } else if (isBalloon) {
                const icon = document.createElement('span');
                icon.className = 'nipun-balloon-dot';
                icon.setAttribute('aria-hidden', 'true');
                icon.textContent = '🎈';
                const lbl = document.createElement('span');
                lbl.textContent = labelText;
                btn.append(icon, lbl);
            } else {
                btn.textContent = labelText;
            }

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
        const { stats, question } = state;
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
        root.querySelector('[role="progressbar"]')?.setAttribute('aria-valuenow', String(percent));

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

        // Render Reading / Poem / Grammar Context Box when present
        if (elements.contextBox) {
            const ctxText = decodeText(pres.context_marathi || '').trim();
            elements.contextBox.textContent = ctxText;
            elements.contextBox.hidden = ctxText === '';
        }

        // Audio text
        const audioText = pres.audio_text || question.prompt_marathi || '';
        if (elements.listenBtn) {
            elements.listenBtn.dataset.audioText = audioText;
            elements.listenBtn.hidden = !audioText;
        }

        // Render dedicated visual component
        if (interaction === 'apple_math' || interaction === 'arithmetic_adventure') {
            renderAppleMath(question, pres.operands || [4, 3], pres.operator || '+');
        } else if (interaction === 'place_value_house') {
            renderPlaceValueHouse(question);
        } else if (interaction === 'frog_jump' || interaction === 'number_line') {
            renderFrogJump(question);
        } else if (interaction === 'fraction_pizza') {
            renderFraction(question);
        } else if (interaction === 'number_train' && Array.isArray(pres.sequence)) {
            renderTrain(pres.sequence);
        } else if (interaction === 'word_train') {
            const parts = String(question.prompt_marathi || '').split('→').filter((p) => p.trim() !== '');
            if (parts.length >= 2) {
                renderTrain(parts);
            } else {
                renderDefaultVisual(question);
            }
        } else if (interaction === 'number_comparison') {
            renderComparisonScale(question);
        } else if (interaction === 'multiplication_array') {
            renderMultiplicationArray(question);
        } else if (interaction === 'division_sharing') {
            renderDivisionSharing(question);
        } else if (interaction === 'shopping') {
            renderShopping(question);
        } else if (interaction === 'word_builder' && Array.isArray(pres.tokens)) {
            renderTokenChips(pres.tokens, '+');
        } else if (interaction === 'word_order' && Array.isArray(pres.tokens)) {
            renderTokenChips(pres.tokens, '');
        } else {
            renderDefaultVisual(question);
        }

        renderChoices(question);

        if (soundEnabled && audioText) {
            setTimeout(() => speakMarathi(audioText), 250);
        }

        updateTimer();
    }

    if (elements.listenBtn) {
        elements.listenBtn.addEventListener('click', () => {
            speakMarathi(elements.listenBtn.dataset.audioText);
        });
    }

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
    if (timerDeadline !== null && remainingSeconds() > 0 && !state.completed) {
        timerId = setInterval(updateTimer, 1000);
    }
})();
