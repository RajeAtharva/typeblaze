DROP DATABASE IF EXISTS typeblaze;
CREATE DATABASE typeblaze CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE typeblaze;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','admin') NOT NULL DEFAULT 'user',
    avatar VARCHAR(20) NOT NULL DEFAULT 'keyboard',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    last_login DATETIME DEFAULT NULL,
    is_active BOOL NOT NULL DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE test_results (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    mode ENUM('sample','quote','code','custom') NOT NULL DEFAULT 'sample',
    wpm SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    accuracy DECIMAL(5,2) NOT NULL DEFAULT 100.00,
    mistakes SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    duration SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    char_count SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    taken_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_results_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE typing_texts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    mode ENUM('sample','quote','code','custom') NOT NULL DEFAULT 'sample',
    content TEXT NOT NULL,
    author VARCHAR(100) DEFAULT NULL,
    difficulty ENUM('easy','medium','hard') NOT NULL DEFAULT 'medium',
    is_active BOOL NOT NULL DEFAULT TRUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE daily_challenges (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    text_id INT UNSIGNED NOT NULL,
    challenge_date DATE NOT NULL UNIQUE,
    CONSTRAINT fk_challenge_text FOREIGN KEY (text_id) REFERENCES typing_texts(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_results_user ON test_results(user_id);
CREATE INDEX idx_results_wpm ON test_results(wpm DESC);
CREATE INDEX idx_results_date ON test_results(taken_at DESC);
CREATE INDEX idx_texts_mode ON typing_texts(mode, is_active);

INSERT INTO users (username, email, password, role, avatar) VALUES
('admin', 'admin@typeblaze.io', '$2y$12$LRkzsFwGLeRFLSDEoJHBxe3vmAYxqRpBjhGimMJ3YrdASmCZ5VRY6', 'admin', 'lightning'),
('demo_user', 'demo@typeblaze.io', '$2y$12$LRkzsFwGLeRFLSDEoJHBxe3vmAYxqRpBjhGimMJ3YrdASmCZ5VRY6', 'user', 'rocket');

INSERT INTO typing_texts (mode, content, author, difficulty) VALUES
('sample', 'The quick brown fox jumps over the lazy dog. This sentence is great for warming up your fingers before a long typing session.', NULL, 'easy'),
('sample', 'Typing is a skill that improves with consistent daily practice. Focus on accuracy first then gradually build your speed over time.', NULL, 'medium'),
('sample', 'A programmer must think logically and write precisely. Every character matters and every mistake can cause an unexpected bug in production.', NULL, 'hard'),
('quote', 'The only way to do great work is to love what you do. If you have not found it yet keep looking and do not settle.', 'Steve Jobs', 'medium'),
('quote', 'In the middle of every difficulty lies opportunity. Imagination is more important than knowledge for knowledge is limited.', 'Albert Einstein', 'medium'),
('quote', 'It does not matter how slowly you go as long as you do not stop. Perseverance is the key to mastery in any craft.', 'Confucius', 'easy'),
('code', 'function calculateWPM(words, seconds) { return Math.round((words / seconds) * 60); } const result = calculateWPM(50, 30); console.log(result);', NULL, 'hard'),
('code', 'const fetchData = async (url) => { try { const res = await fetch(url); const data = await res.json(); return data; } catch (err) { console.error(err); } };', NULL, 'hard'),
('code', 'SELECT username, AVG(wpm) AS avg_wpm FROM users JOIN test_results ON users.id = user_id GROUP BY users.id ORDER BY avg_wpm DESC LIMIT 10;', NULL, 'hard');

INSERT INTO test_results (user_id, mode, wpm, accuracy, mistakes, duration, char_count) VALUES
(2, 'sample', 87, 96.50, 3, 60, 320),
(2, 'quote', 73, 100.00, 0, 45, 180),
(2, 'sample', 91, 98.20, 2, 60, 340),
(2, 'code', 55, 94.10, 5, 60, 210),
(2, 'sample', 78, 97.00, 4, 30, 160);

CREATE OR REPLACE VIEW leaderboard AS
SELECT
    u.id,
    u.username,
    u.avatar,
    MAX(r.wpm) AS best_wpm,
    ROUND(AVG(r.accuracy), 1) AS avg_accuracy,
    COUNT(r.id) AS total_tests,
    MAX(r.taken_at) AS last_played
FROM users u
JOIN test_results r ON u.id = r.user_id
WHERE u.role = 'user' AND u.is_active = TRUE
GROUP BY u.id, u.username, u.avatar
ORDER BY best_wpm DESC;

CREATE OR REPLACE VIEW user_stats AS
SELECT
    user_id,
    COUNT(*) AS total_tests,
    MAX(wpm) AS best_wpm,
    ROUND(AVG(wpm), 1) AS avg_wpm,
    ROUND(AVG(accuracy), 1) AS avg_accuracy,
    SUM(mistakes) AS total_mistakes,
    SUM(duration) AS total_time_secs
FROM test_results
GROUP BY user_id;
