<?php
// This is a pure frontend PHP file - no backend needed, just serves the HTML
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🍅 Pomodoro Timer Pro - Focus & Productivity</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            transition: background 0.5s ease;
        }

        body.break-mode {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 30px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 500px;
            width: 100%;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .title {
            font-size: 32px;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtitle {
            color: #999;
            font-size: 14px;
        }

        .timer-display {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 40px;
            margin: 30px 0;
            text-align: center;
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .timer-display::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 50% 0%, rgba(255, 255, 255, 0.1), transparent);
            pointer-events: none;
        }

        .time {
            font-size: 80px;
            font-weight: 700;
            color: white;
            font-family: 'Courier New', monospace;
            letter-spacing: 5px;
            margin-bottom: 15px;
        }

        .session-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .progress-ring {
            width: 100%;
            height: 8px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            margin-top: 15px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #ffd700, #ffed4e);
            width: 100%;
            border-radius: 10px;
            transition: width 1s linear;
        }

        .controls {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
            justify-content: center;
        }

        button {
            padding: 12px 28px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .start-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            min-width: 120px;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .start-btn:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        .start-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .pause-btn {
            background: #ffa500;
            color: white;
            min-width: 120px;
        }

        .pause-btn:hover {
            background: #ff9100;
            transform: translateY(-3px);
        }

        .reset-btn {
            background: #ff6b6b;
            color: white;
            min-width: 120px;
        }

        .reset-btn:hover {
            background: #ff5252;
            transform: translateY(-3px);
        }

        .settings {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
        }

        .settings-title {
            font-size: 14px;
            font-weight: 700;
            color: #333;
            text-transform: uppercase;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        .setting-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .setting-group:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .setting-label {
            font-size: 13px;
            color: #666;
            font-weight: 600;
        }

        .setting-value {
            background: white;
            border: 2px solid #667eea;
            border-radius: 8px;
            padding: 8px 12px;
            min-width: 60px;
            text-align: center;
            font-weight: 700;
            color: #667eea;
            font-size: 14px;
        }

        input[type="range"] {
            width: 100%;
            height: 6px;
            border-radius: 3px;
            background: #ddd;
            outline: none;
            -webkit-appearance: none;
        }

        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        input[type="range"]::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            cursor: pointer;
            border: none;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .stats {
            background: #f0f4ff;
            padding: 20px;
            border-radius: 15px;
            border-left: 4px solid #667eea;
        }

        .stats-title {
            font-size: 13px;
            font-weight: 700;
            color: #333;
            text-transform: uppercase;
            margin-bottom: 12px;
            letter-spacing: 1px;
        }

        .stat-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .stat-label {
            color: #666;
        }

        .stat-value {
            font-weight: 700;
            color: #667eea;
        }

        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            transform: scale(1.1);
        }

        .notification {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #667eea;
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            animation: slideInUp 0.3s ease-out;
            pointer-events: none;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }

        .notification.hide {
            animation: slideOutDown 0.3s ease-out forwards;
        }

        @keyframes slideOutDown {
            to {
                opacity: 0;
                transform: translateX(-50%) translateY(20px);
            }
        }

        @media (max-width: 600px) {
            .container {
                padding: 25px;
            }

            .time {
                font-size: 60px;
                letter-spacing: 3px;
            }

            .controls {
                flex-direction: column;
                gap: 10px;
            }

            button {
                width: 100%;
            }

            .theme-toggle {
                width: 45px;
                height: 45px;
                font-size: 20px;
            }
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 30px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 24px;
            width: 24px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #667eea;
        }

        input:checked + .slider:before {
            transform: translateX(30px);
        }
    </style>
</head>
<body>
    <button class="theme-toggle" id="themeToggle">🌙</button>

    <div class="container">
        <div class="header">
            <div class="title">🍅 Pomodoro Timer</div>
            <div class="subtitle">Focus, Track, Achieve</div>
        </div>

        <div class="settings">
            <div class="settings-title">⚙️ Settings</div>
            <div class="setting-group">
                <label class="setting-label">Work Duration (min)</label>
                <div class="setting-value" id="workDisplay">25</div>
            </div>
            <input type="range" id="workDuration" min="1" max="60" value="25" step="1">

            <div class="setting-group" style="margin-top: 15px;">
                <label class="setting-label">Break Duration (min)</label>
                <div class="setting-value" id="breakDisplay">5</div>
            </div>
            <input type="range" id="breakDuration" min="1" max="30" value="5" step="1">

            <div class="setting-group" style="margin-top: 15px;">
                <label class="setting-label">Sound Notifications</label>
                <label class="toggle-switch">
                    <input type="checkbox" id="soundToggle" checked>
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <div class="timer-display">
            <div class="time" id="timerDisplay">25:00</div>
            <div class="session-label" id="sessionLabel">Work Session</div>
            <div class="progress-ring">
                <div class="progress-bar" id="progressBar"></div>
            </div>
        </div>

        <div class="controls">
            <button class="start-btn" id="startBtn" onclick="startTimer()">Start</button>
            <button class="pause-btn" id="pauseBtn" onclick="pauseTimer()" style="display:none;">Pause</button>
            <button class="reset-btn" onclick="resetTimer()">Reset</button>
        </div>

        <div class="stats">
            <div class="stats-title">📊 Today's Stats</div>
            <div class="stat-row">
                <span class="stat-label">Sessions Completed:</span>
                <span class="stat-value" id="sessionsCount">0</span>
            </div>
            <div class="stat-row">
                <span class="stat-label">Total Focus Time:</span>
                <span class="stat-value" id="totalTime">00:00:00</span>
            </div>
            <div class="stat-row">
                <span class="stat-label">Current Streak:</span>
                <span class="stat-value" id="streak">0</span>
            </div>
        </div>
    </div>

    <script>
        // State Management
        let state = {
            isRunning: false,
            isBreak: false,
            timeLeft: 25 * 60,
            totalSeconds: 25 * 60,
            workDuration: 25,
            breakDuration: 5,
            sessionsCompleted: 0,
            totalFocusTime: 0,
            soundEnabled: true,
            timerInterval: null
        };

        const elements = {
            timerDisplay: document.getElementById('timerDisplay'),
            sessionLabel: document.getElementById('sessionLabel'),
            startBtn: document.getElementById('startBtn'),
            pauseBtn: document.getElementById('pauseBtn'),
            progressBar: document.getElementById('progressBar'),
            workDuration: document.getElementById('workDuration'),
            breakDuration: document.getElementById('breakDuration'),
            workDisplay: document.getElementById('workDisplay'),
            breakDisplay: document.getElementById('breakDisplay'),
            sessionsCount: document.getElementById('sessionsCount'),
            totalTime: document.getElementById('totalTime'),
            streak: document.getElementById('streak'),
            soundToggle: document.getElementById('soundToggle'),
            themeToggle: document.getElementById('themeToggle')
        };

        // Load settings from localStorage
        function loadSettings() {
            const saved = localStorage.getItem('pomodoroSettings');
            if (saved) {
                const settings = JSON.parse(saved);
                state.workDuration = settings.workDuration || 25;
                state.breakDuration = settings.breakDuration || 5;
                state.sessionsCompleted = settings.sessionsCompleted || 0;
                state.totalFocusTime = settings.totalFocusTime || 0;
                state.soundEnabled = settings.soundEnabled !== false;
                
                elements.workDuration.value = state.workDuration;
                elements.breakDuration.value = state.breakDuration;
                elements.soundToggle.checked = state.soundEnabled;
                updateDisplay();
            }
        }

        // Save settings to localStorage
        function saveSettings() {
            localStorage.setItem('pomodoroSettings', JSON.stringify({
                workDuration: state.workDuration,
                breakDuration: state.breakDuration,
                sessionsCompleted: state.sessionsCompleted,
                totalFocusTime: state.totalFocusTime,
                soundEnabled: state.soundEnabled
            }));
        }

        // Update display
        function updateDisplay() {
            const minutes = Math.floor(state.timeLeft / 60);
            const seconds = state.timeLeft % 60;
            elements.timerDisplay.textContent = 
                String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
            
            const progress = ((state.totalSeconds - state.timeLeft) / state.totalSeconds) * 100;
            elements.progressBar.style.width = progress + '%';

            elements.sessionsCount.textContent = state.sessionsCompleted;
            updateTotalTime();
            updateStreak();
        }

        // Update total time display
        function updateTotalTime() {
            const hours = Math.floor(state.totalFocusTime / 3600);
            const mins = Math.floor((state.totalFocusTime % 3600) / 60);
            const secs = state.totalFocusTime % 60;
            elements.totalTime.textContent = 
                String(hours).padStart(2, '0') + ':' + 
                String(mins).padStart(2, '0') + ':' + 
                String(secs).padStart(2, '0');
        }

        // Update streak
        function updateStreak() {
            elements.streak.textContent = state.sessionsCompleted;
        }

        // Start timer
        function startTimer() {
            if (state.isRunning) return;
            
            state.isRunning = true;
            elements.startBtn.style.display = 'none';
            elements.pauseBtn.style.display = 'inline-block';

            state.timerInterval = setInterval(() => {
                state.timeLeft--;
                updateDisplay();

                if (state.timeLeft === 0) {
                    completeSession();
                }
            }, 1000);
        }

        // Pause timer
        function pauseTimer() {
            state.isRunning = false;
            clearInterval(state.timerInterval);
            elements.pauseBtn.style.display = 'none';
            elements.startBtn.style.display = 'inline-block';
        }

        // Reset timer
        function resetTimer() {
            pauseTimer();
            state.isBreak = false;
            state.timeLeft = state.workDuration * 60;
            state.totalSeconds = state.workDuration * 60;
            elements.sessionLabel.textContent = 'Work Session';
            document.body.classList.remove('break-mode');
            updateDisplay();
        }

        // Complete session
        function completeSession() {
            clearInterval(state.timerInterval);
            state.isRunning = false;

            if (!state.isBreak) {
                // Work session completed
                state.sessionsCompleted++;
                state.totalFocusTime += state.workDuration * 60;
                saveSettings();
                playNotificationSound();
                showNotification('🎉 Work session completed! Time for a break.');
                
                // Switch to break
                state.isBreak = true;
                state.timeLeft = state.breakDuration * 60;
                state.totalSeconds = state.breakDuration * 60;
                elements.sessionLabel.textContent = 'Break Time';
                document.body.classList.add('break-mode');
            } else {
                // Break completed
                playNotificationSound();
                showNotification('✨ Break finished! Ready for another session?');
                
                // Switch back to work
                state.isBreak = false;
                state.timeLeft = state.workDuration * 60;
                state.totalSeconds = state.workDuration * 60;
                elements.sessionLabel.textContent = 'Work Session';
                document.body.classList.remove('break-mode');
            }

            elements.pauseBtn.style.display = 'none';
            elements.startBtn.style.display = 'inline-block';
            updateDisplay();
        }

        // Play notification sound
        function playNotificationSound() {
            if (!state.soundEnabled) return;
            
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const now = audioContext.currentTime;
            
            // Create a pleasant beep tone
            const osc = audioContext.createOscillator();
            const gain = audioContext.createGain();
            
            osc.connect(gain);
            gain.connect(audioContext.destination);
            
            osc.frequency.setValueAtTime(800, now);
            osc.frequency.exponentialRampToValueAtTime(600, now + 0.1);
            
            gain.gain.setValueAtTime(0.3, now);
            gain.gain.exponentialRampToValueAtTime(0.01, now + 0.1);
            
            osc.start(now);
            osc.stop(now + 0.1);
        }

        // Show notification
        function showNotification(message) {
            const div = document.createElement('div');
            div.className = 'notification';
            div.textContent = message;
            document.body.appendChild(div);

            setTimeout(() => {
                div.classList.add('hide');
                setTimeout(() => div.remove(), 300);
            }, 3000);
        }

        // Update settings handlers
        elements.workDuration.addEventListener('input', (e) => {
            state.workDuration = parseInt(e.target.value);
            elements.workDisplay.textContent = state.workDuration;
            if (!state.isRunning) {
                resetTimer();
            }
        });

        elements.breakDuration.addEventListener('input', (e) => {
            state.breakDuration = parseInt(e.target.value);
            elements.breakDisplay.textContent = state.breakDuration;
        });

        elements.soundToggle.addEventListener('change', (e) => {
            state.soundEnabled = e.target.checked;
            saveSettings();
        });

        // Theme toggle
        elements.themeToggle.addEventListener('click', () => {
            document.body.style.filter = 
                document.body.style.filter === 'invert(1)' ? 'invert(0)' : 'invert(1)';
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.code === 'Space') {
                e.preventDefault();
                state.isRunning ? pauseTimer() : startTimer();
            }
            if (e.code === 'KeyR') {
                resetTimer();
            }
        });

        // Initialize
        loadSettings();
        updateDisplay();
    </script>
</body>
</html>
