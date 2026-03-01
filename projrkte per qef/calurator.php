<?php
// Mathematical expression evaluator
class Calculator {
    private $expression = '';
    private $pos = 0;

    public function evaluate($expression) {
        // Remove spaces and validate input
        $this->expression = str_replace(' ', '', $expression);
        $this->pos = 0;

        // Only allow valid characters
        if (!preg_match('/^[0-9+\-*\/.()%]+$/', $this->expression)) {
            throw new Exception('Invalid characters in expression');
        }

        if (empty($this->expression)) {
            throw new Exception('Empty expression');
        }

        $result = $this->parseExpression();
        
        if ($this->pos !== strlen($this->expression)) {
            throw new Exception('Invalid expression syntax');
        }

        return $result;
    }

    private function parseExpression() {
        $result = $this->parseTerm();

        while ($this->pos < strlen($this->expression) && in_array($this->expression[$this->pos], ['+', '-'])) {
            $op = $this->expression[$this->pos++];
            $right = $this->parseTerm();
            
            if ($op === '+') {
                $result += $right;
            } else {
                $result -= $right;
            }
        }

        return $result;
    }

    private function parseTerm() {
        $result = $this->parseFactor();

        while ($this->pos < strlen($this->expression) && in_array($this->expression[$this->pos], ['*', '/', '%'])) {
            $op = $this->expression[$this->pos++];
            $right = $this->parseFactor();
            
            if ($op === '*') {
                $result *= $right;
            } elseif ($op === '/') {
                if ($right == 0) {
                    throw new Exception('Division by zero');
                }
                $result /= $right;
            } elseif ($op === '%') {
                if ($right == 0) {
                    throw new Exception('Modulo by zero');
                }
                $result %= $right;
            }
        }

        return $result;
    }

    private function parseFactor() {
        // Handle parentheses
        if ($this->pos < strlen($this->expression) && $this->expression[$this->pos] === '(') {
            $this->pos++; // skip '('
            $result = $this->parseExpression();
            if ($this->pos >= strlen($this->expression) || $this->expression[$this->pos] !== ')') {
                throw new Exception('Missing closing parenthesis');
            }
            $this->pos++; // skip ')'
            return $result;
        }

        // Handle unary minus
        if ($this->pos < strlen($this->expression) && $this->expression[$this->pos] === '-') {
            $this->pos++;
            return -$this->parseFactor();
        }

        // Parse number
        return $this->parseNumber();
    }

    private function parseNumber() {
        $num = '';
        $dotCount = 0;

        while ($this->pos < strlen($this->expression) && (ctype_digit($this->expression[$this->pos]) || $this->expression[$this->pos] === '.')) {
            if ($this->expression[$this->pos] === '.') {
                if ($dotCount > 0) {
                    throw new Exception('Invalid number format');
                }
                $dotCount++;
            }
            $num .= $this->expression[$this->pos++];
        }

        if (empty($num) || $num === '.') {
            throw new Exception('Invalid number');
        }

        return floatval($num);
    }
}

// Handle AJAX requests for calculations
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    if ($_POST['action'] === 'calculate') {
        $expression = $_POST['expression'] ?? '';

        if (empty($expression)) {
            echo json_encode(['error' => 'Invalid expression']);
            exit;
        }

        try {
            $calculator = new Calculator();
            $result = $calculator->evaluate($expression);
            
            // Format result to avoid floating point errors
            $result = round($result, 10);
            
            // Clean up trailing zeros after decimal
            if (is_float($result) && strpos($result, '.') !== false) {
                $result = floatval(rtrim(rtrim($result, '0'), '.'));
            }

            echo json_encode([
                'result' => $result,
                'expression' => $expression
            ]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Calculator with History</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            display: flex;
            gap: 20px;
            max-width: 900px;
            width: 100%;
            flex-wrap: wrap;
        }

        .calculator {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 20px;
            width: 100%;
            max-width: 400px;
            animation: slideIn 0.5s ease-out;
        }

        .history-panel {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 20px;
            width: 100%;
            max-width: 300px;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            text-align: center;
        }

        .display {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 32px;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: right;
            min-height: 60px;
            word-wrap: break-word;
            word-break: break-all;
            font-weight: 500;
            box-shadow: inset 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .input-display {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 10px;
            min-height: 20px;
        }

        .result-display {
            font-size: 32px;
            min-height: 40px;
        }

        .buttons-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }

        button {
            padding: 15px;
            font-size: 18px;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        button:active {
            transform: translateY(0);
        }

        .number {
            background: #f0f0f0;
            color: #333;
        }

        .number:hover {
            background: #e0e0e0;
        }

        .operator {
            background: #667eea;
            color: white;
            font-size: 20px;
        }

        .operator:hover {
            background: #5568d3;
        }

        .equals {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            grid-column: span 2;
            font-size: 20px;
        }

        .equals:hover {
            background: linear-gradient(135deg, #5568d3 0%, #6a3f91 100%);
        }

        .clear {
            background: #ff6b6b;
            color: white;
            grid-column: span 2;
            font-size: 16px;
        }

        .clear:hover {
            background: #ff5252;
        }

        .delete {
            background: #ffa500;
            color: white;
            font-size: 16px;
        }

        .delete:hover {
            background: #ff9100;
        }

        .modulo {
            background: #667eea;
            color: white;
        }

        .modulo:hover {
            background: #5568d3;
        }

        .history-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            text-align: center;
        }

        .history-list {
            background: #f9f9f9;
            border-radius: 10px;
            max-height: 400px;
            overflow-y: auto;
            padding: 10px;
            margin-bottom: 15px;
        }

        .history-item {
            background: white;
            padding: 12px;
            margin-bottom: 8px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            cursor: pointer;
            transition: all 0.3s ease;
            word-break: break-all;
        }

        .history-item:hover {
            background: #f0f4ff;
            transform: translateX(5px);
        }

        .history-expression {
            font-size: 13px;
            color: #666;
            margin-bottom: 5px;
        }

        .history-result {
            font-size: 16px;
            font-weight: 600;
            color: #667eea;
        }

        .clear-history {
            width: 100%;
            background: #ff6b6b;
            color: white;
            padding: 12px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .clear-history:hover {
            background: #ff5252;
        }

        .no-history {
            text-align: center;
            color: #ccc;
            padding: 30px 10px;
            font-size: 14px;
        }

        .copy-feedback {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #667eea;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .copy-feedback.show {
            opacity: 1;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .calculator,
            .history-panel {
                max-width: 100%;
            }

            .display {
                font-size: 24px;
                min-height: 50px;
            }

            .buttons-grid {
                gap: 8px;
            }

            button {
                padding: 12px;
                font-size: 16px;
            }
        }

        /* Scrollbar styling */
        .history-list::-webkit-scrollbar {
            width: 6px;
        }

        .history-list::-webkit-scrollbar-track {
            background: #f0f0f0;
            border-radius: 10px;
        }

        .history-list::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 10px;
        }

        .history-list::-webkit-scrollbar-thumb:hover {
            background: #5568d3;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Calculator -->
        <div class="calculator">
            <div class="title">📱 Calculator</div>
            
            <div class="display">
                <div class="input-display" id="inputDisplay"></div>
                <div class="result-display" id="resultDisplay">0</div>
            </div>

            <div class="buttons-grid">
                <!-- Row 1 -->
                <button class="clear" onclick="clearDisplay()">AC</button>
                <button class="delete" onclick="deleteLastChar()">⌫</button>
                <button class="operator" onclick="appendOperator('/')">÷</button>
                <button class="modulo" onclick="appendOperator('%')">%</button>

                <!-- Row 2 -->
                <button class="number" onclick="appendNumber('7')">7</button>
                <button class="number" onclick="appendNumber('8')">8</button>
                <button class="number" onclick="appendNumber('9')">9</button>
                <button class="operator" onclick="appendOperator('*')">×</button>

                <!-- Row 3 -->
                <button class="number" onclick="appendNumber('4')">4</button>
                <button class="number" onclick="appendNumber('5')">5</button>
                <button class="number" onclick="appendNumber('6')">6</button>
                <button class="operator" onclick="appendOperator('-')">−</button>

                <!-- Row 4 -->
                <button class="number" onclick="appendNumber('1')">1</button>
                <button class="number" onclick="appendNumber('2')">2</button>
                <button class="number" onclick="appendNumber('3')">3</button>
                <button class="operator" onclick="appendOperator('+')">+</button>

                <!-- Row 5 -->
                <button class="number" onclick="appendNumber('0')" style="grid-column: span 2;">0</button>
                <button class="number" onclick="appendNumber('.')">.</button>
                <button class="equals" onclick="calculate()">=</button>
            </div>
        </div>

        <!-- History Panel -->
        <div class="history-panel">
            <div class="history-title">📋 History</div>
            <div class="history-list" id="historyList">
                <div class="no-history">No history yet</div>
            </div>
            <button class="clear-history" onclick="clearHistory()">Clear History</button>
        </div>
    </div>

    <div class="copy-feedback" id="copyFeedback">Copied to clipboard!</div>

    <script>
        let display = '0';
        let inputDisplay = '';
        let history = [];

        // Load history from localStorage
        function loadHistory() {
            const saved = localStorage.getItem('calculatorHistory');
            if (saved) {
                history = JSON.parse(saved);
                updateHistoryDisplay();
            }
        }

        // Save history to localStorage
        function saveHistory() {
            localStorage.setItem('calculatorHistory', JSON.stringify(history.slice(0, 50))); // Keep last 50
        }

        // Append number to display
        function appendNumber(num) {
            if (display === '0' && num !== '.') {
                display = num;
            } else if (num === '.' && display.includes('.')) {
                return;
            } else {
                display += num;
            }
            updateDisplay();
        }

        // Append operator
        function appendOperator(op) {
            if (display === '') return;
            
            const lastChar = display[display.length - 1];
            if (['+', '-', '*', '/', '%'].includes(lastChar)) {
                display = display.slice(0, -1) + op;
            } else {
                display += op;
            }
            updateDisplay();
        }

        // Delete last character
        function deleteLastChar() {
            display = display.slice(0, -1) || '0';
            updateDisplay();
        }

        // Clear display
        function clearDisplay() {
            display = '0';
            inputDisplay = '';
            updateDisplay();
        }

        // Update display
        function updateDisplay() {
            document.getElementById('resultDisplay').textContent = display;
            document.getElementById('inputDisplay').textContent = inputDisplay;
        }

        // Calculate result
        function calculate() {
            if (display === '' || display === '0') return;
            
            const expression = display;
            inputDisplay = expression;

            // Send to PHP backend
            fetch('calurator.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=calculate&expression=' + encodeURIComponent(display)
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    document.getElementById('resultDisplay').textContent = data.error;
                    display = '0';
                } else {
                    const result = data.result;
                    
                    // Add to history
                    const historyItem = {
                        expression: expression,
                        result: result,
                        time: new Date().toLocaleTimeString()
                    };
                    history.unshift(historyItem);
                    saveHistory();
                    updateHistoryDisplay();

                    // Update display
                    display = String(result);
                    inputDisplay = expression + ' =';
                    updateDisplay();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('resultDisplay').textContent = 'Error';
            });
        }

        // Update history display
        function updateHistoryDisplay() {
            const historyList = document.getElementById('historyList');
            
            if (history.length === 0) {
                historyList.innerHTML = '<div class="no-history">No history yet</div>';
                return;
            }

            historyList.innerHTML = history.map((item, index) => `
                <div class="history-item" onclick="useHistoryItem(${index})">
                    <div class="history-expression">${escapeHtml(item.expression)}</div>
                    <div class="history-result">${item.result}</div>
                    <small style="color: #999; font-size: 12px;">${item.time}</small>
                </div>
            `).join('');
        }

        // Use history item
        function useHistoryItem(index) {
            display = String(history[index].result);
            inputDisplay = history[index].expression + ' =';
            updateDisplay();
        }

        // Clear history
        function clearHistory() {
            if (confirm('Are you sure you want to clear history?')) {
                history = [];
                saveHistory();
                updateHistoryDisplay();
            }
        }

        // Escape HTML to prevent XSS
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Keyboard support
        document.addEventListener('keydown', function(event) {
            const key = event.key;
            
            if (key >= '0' && key <= '9') {
                appendNumber(key);
            } else if (key === '.') {
                appendNumber('.');
            } else if (key === '+' || key === '-' || key === '*' || key === '/') {
                event.preventDefault();
                appendOperator(key);
            } else if (key === 'Enter' || key === '=') {
                event.preventDefault();
                calculate();
            } else if (key === 'Backspace') {
                event.preventDefault();
                deleteLastChar();
            } else if (key === 'Escape') {
                clearDisplay();
            }
        });

        // Initialize
        loadHistory();
        updateDisplay();
    </script>
</body>
</html>
