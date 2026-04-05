<?php
$pageTitle = 'The English Nook | Home';
include __DIR__ . '/includes/header.php';
require __DIR__ . '/php/db.php';

$allClasses = $pdo->query('SELECT * FROM classes ORDER BY id DESC')->fetchAll();
?>

<section id="home" class="section active-section">
    <div class="hero">
        <h1 id="hero-title">Master the English Language.</h1>
        <p id="hero-subtitle">Refine your grammar, expand your vocabulary, and explore literature.</p>
        <button class="cta-btn" onclick="startJourney()">Start Learning</button>
    </div>
</section>

<section id="learn" class="section hidden-section">
    <h2 id="learn-heading">The Library</h2>
    <p class="section-desc">Select a topic to begin studying.</p>

    <div class="card-grid" id="lesson-grid">
        <p>Loading Lessons...</p>
    </div>
</section>

<section id="grammar" class="section hidden-section">
    <h2>Grammar Mechanics</h2>
    <div class="card-grid">
        <div class="card">
            <h3>Tenses ⏳</h3>
            <p>Understand the difference between Past Simple and Present Perfect.</p>
        </div>
        <div class="card">
            <h3>Punctuation ✍️</h3>
            <p>Mastering the Oxford Comma, Semicolons, and Em-dashes.</p>
        </div>
        <div class="card">
            <h3>Active vs Passive 🗣️</h3>
            <p>"The mistake was made" vs "I made a mistake".</p>
        </div>
    </div>
</section>

<section id="lit" class="section hidden-section">
    <h2>Literature Analysis</h2>
    <div class="card-grid">
        <div class="card">
            <h3>Literary Devices</h3>
            <p>Metaphor, Simile, Alliteration, and Hyperbole explained.</p>
        </div>
        <div class="card">
            <h3>Poetry Analysis</h3>
            <p>How to dissect rhythm, meter, and tone in sonnets.</p>
        </div>
    </div>
</section>

<section id="play" class="section hidden-section">
    <h2 id="play-heading">The Game Zone</h2>
    <div class="game-container">
        <div class="game-card">
            <h3>Word Scramble</h3>
            <p>Unscramble the letters to find the hidden word.</p>
            <button onclick="startGame('scramble')">Play Now</button>
        </div>
        <div class="game-card">
            <h3>Poetry Puzzle</h3>
            <p>Arrange the lines to complete the poem.</p>
            <button onclick="startGame('poetry')">Play Now</button>
        </div>
    </div>
</section>

<section id="zoo" class="section hidden-section">
    <button class="back-btn" onclick="showSection('kids-hub')">⬅ Back to Play Zone</button>
    <h2 id="zoo-heading">Welcome to the Zoo! 🦁</h2>
    <p class="section-desc">Click an animal to hear its name!</p>
    <div class="zoo-grid"></div>
</section>

<section id="colors" class="section hidden-section">
    <button class="back-btn" onclick="showSection('kids-hub')">⬅ Back to Play Zone</button>
    <h2 style="text-align:center;">The Rainbow Room 🎨</h2>
    <p class="section-desc">Click a paint blob to learn the color!</p>
    <div class="color-grid"></div>
</section>

<section id="vocab" class="section hidden-section">
    <h2>Your Vocabulary Vault</h2>
    <div class="vocab-input-area">
        <input type="text" id="new-word" placeholder="Add a new word...">
        <button id="add-word-btn">Save</button>
    </div>
    <ul id="vocab-list">
    </ul>
</section>

<section id="classes" class="section hidden-section">
    <div class="adult-only">
        <div class="syllabus-header">
            <h2>Academic Courses 🎓</h2>
            <p>Comprehensive English training following the Local Syllabus & International Standards.</p>
        </div>

        <div class="course-grid">
            <?php
            $scholarClasses = array_filter($allClasses, function ($c) {
                return $c['category'] === 'scholar'; });
            if (empty($scholarClasses)): ?>
                <p>No scholar classes available yet.</p>
            <?php else:
                foreach ($scholarClasses as $class): ?>
                    <div class="course-card">
                        <span class="badge"><?php echo htmlspecialchars($class['level']); ?></span>
                        <h3><?php echo htmlspecialchars($class['title']); ?></h3>
                        <p><?php echo htmlspecialchars($class['short_description']); ?></p>
                        <a href="php/enroll.php?class_id=<?php echo (int) $class['id']; ?>" class="enroll-btn"
                            style="text-align:center; display:block; text-decoration:none;">Join Class</a>
                    </div>
                <?php endforeach; endif; ?>
        </div>
    </div>

    <div class="kids-only" style="display: none;">
        <div class="syllabus-header">
            <h2>Junior Classroom 🎒</h2>
            <p>Primary Syllabus (Grades 1-5) & Spoken English</p>
        </div>

        <div class="course-grid">
            <?php
            $explorerClasses = array_filter($allClasses, function ($c) {
                return $c['category'] === 'explorer'; });
            if (empty($explorerClasses)): ?>
                <p>No explorer classes available yet.</p>
            <?php else:
                foreach ($explorerClasses as $class): ?>
                    <div class="course-card">
                        <span class="badge"><?php echo htmlspecialchars($class['level']); ?></span>
                        <h3><?php echo htmlspecialchars($class['title']); ?></h3>
                        <p><?php echo htmlspecialchars($class['short_description']); ?></p>
                        <a href="php/enroll.php?class_id=<?php echo (int) $class['id']; ?>" class="enroll-btn"
                            style="text-align:center; display:block; text-decoration:none;">Join Class</a>
                    </div>
                <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<section id="kids-hub" class="section hidden-section">
    <div class="syllabus-header">
        <h2>The Play Zone 🎈</h2>
        <p>Choose your adventure!</p>
    </div>

    <div class="adventure-grid">
        <div class="adventure-card" onclick="showSection('naughty-quiz')">
            <div class="icon">🤪</div>
            <h3>Crazy Grammar</h3>
            <p>Catch the button if you can!</p>
            <button class="fun-btn">Play Now</button>
        </div>

        <div class="adventure-card" onclick="showSection('zoo')">
            <div class="icon">🦁</div>
            <h3>The Talking Zoo</h3>
            <p>Learn animal names & sounds.</p>
            <button class="fun-btn">Visit Zoo</button>
        </div>

        <div class="adventure-card" onclick="showSection('colors')">
            <div class="icon">🎨</div>
            <h3>Rainbow Room</h3>
            <p>Learn colors with magic paint.</p>
            <button class="fun-btn">Start Painting</button>
        </div>

        <div class="adventure-card">
            <div class="icon">🐯</div>
            <h3>Jungle Phonics</h3>
            <p>A-B-C sounds with Tiger & Friends.</p>
            <button class="fun-btn">Coming Soon</button>
        </div>

        <div class="adventure-card">
            <div class="icon">📚</div>
            <h3>Story Time</h3>
            <p>Read "The Little Hero" together.</p>
            <button class="fun-btn">Coming Soon</button>
        </div>
    </div>
</section>

<section id="naughty-quiz" class="section hidden-section">
    <button class="back-btn" onclick="showSection('kids-hub')">⬅ Back to Play Zone</button>

    <div class="quiz-container">
        <h2 class="cute-question">Is "JUMP" a Noun? 🤔</h2>

        <div class="cute-buttons">
            <button id="runaway-btn" onmouseover="moveButton()">YES</button>
            <button id="correct-btn" onclick="showSuccess()">NO</button>
        </div>

        <div id="success-msg" class="hidden-message">
            <img src="happy-owl.gif" alt="Happy Owl" class="cute-gif">
            <h3>Yay! You knew it! 🎉</h3>
            <p>"Jump" is a VERB (Action Word)!</p>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>