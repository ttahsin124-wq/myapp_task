
<?php
$dsn = 'mysql:host=localhost;port=3306;dbname=myapp;charset=utf8mb4';
$pdo = new PDO($dsn, 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$errors = [];
$welcomeName = null;
$welcomeCity = null;

$name = '';
$age = '';
$city = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $age  = trim($_POST['age'] ?? '');
    $city = trim($_POST['city'] ?? '');

    // Validation
    if ($name === '') {
        $errors[] = 'Name is required.';
    }

    if ($city === '') {
        $errors[] = 'City is required.';
    }

    if ($age !== '' && (!ctype_digit($age) || (int)$age < 0)) {
        $errors[] = 'Age must be a valid non-negative number.';
    }

    // Insert data using a prepared statement
    if (empty($errors)) {
        $stmt = $pdo->prepare(
            'INSERT INTO users (name, age, city)
             VALUES (:name, :age, :city)'
        );

        $stmt->execute([
            'name' => $name,
            'age'  => $age === '' ? null : (int)$age,
            'city' => $city
        ]);

        $welcomeName = $name;
        $welcomeCity = $city;

        $name = $age = $city = '';
    }
}

$users = $pdo->query(
    'SELECT name, age, city FROM users ORDER BY id DESC'
)->fetchAll();

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up | Join Our Community</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
      rel="stylesheet">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --purple: #6c5ce7;
    --pink: #e056fd;
    --dark: #211b53;
    --muted: #8585a5;
    --border: #e7e5ff;
}

body {
    font-family: 'Inter', sans-serif;
    background:
        radial-gradient(circle at 8% 10%, #f5dfff 0, transparent 27%),
        radial-gradient(circle at 95% 80%, #e1e8ff 0, transparent 30%),
        #f7f8ff;
    color: var(--dark);
    min-height: 100vh;
    padding: 48px 20px;
}

.container {
    max-width: 900px;
    margin: auto;
}

.hero {
    position: relative;
    overflow: hidden;
    background: linear-gradient(120deg, #191d58, #49348c 65%, #8d4dc4);
    color: white;
    padding: 42px;
    border-radius: 28px;
    margin-bottom: 28px;
    box-shadow: 0 18px 45px #49348c25;
}

.hero::after {
    content: "";
    position: absolute;
    width: 220px;
    height: 220px;
    right: -70px;
    bottom: -150px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e056fd, #ff8bd1);
    opacity: .8;
}

.eyebrow {
    color: #e5caff;
    text-transform: uppercase;
    letter-spacing: 3px;
    font-size: 11px;
    font-weight: 700;
    margin-bottom: 14px;
}

.hero h1 {
    font-size: clamp(32px, 6vw, 48px);
    letter-spacing: -2px;
    margin-bottom: 12px;
}

.hero p {
    color: #e3ddff;
    line-height: 1.7;
    max-width: 550px;
}

.hero .heart {
    color: #ff9ce8;
}

.card {
    background: rgba(255,255,255,.92);
    border: 1px solid white;
    border-radius: 24px;
    padding: 34px;
    margin-bottom: 28px;
    box-shadow: 0 14px 45px #302b7210;
}

.section-heading {
    display: flex;
    align-items: center;
    gap: 13px;
    margin-bottom: 10px;
}

.section-heading .emoji {
    font-size: 27px;
}

.section-heading h2 {
    font-size: 24px;
    letter-spacing: -.7px;
}

.subtitle {
    color: var(--muted);
    font-size: 13px;
    line-height: 1.7;
    margin-bottom: 26px;
}

.alert {
    padding: 15px 17px;
    border-radius: 13px;
    margin-bottom: 20px;
    font-size: 13px;
    line-height: 1.8;
}

.alert.error {
    background: #fff0f3;
    color: #d6335c;
    border: 1px solid #ffd3dd;
}

.alert.success {
    background: #eafbf4;
    color: #087e5b;
    border: 1px solid #b9efd9;
}

.field {
    margin-bottom: 19px;
}

.field label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 9px;
    color: #47416f;
}

.field input {
    display: block;
    width: 100%;
    padding: 15px 16px;
    border: 1.5px solid #e2e0f5;
    border-radius: 12px;
    background: #fbfbff;
    font: inherit;
    font-size: 14px;
    color: var(--dark);
    outline: none;
    transition: .25s;
}

.field input::placeholder {
    color: #aaa8c1;
}

.field input:focus {
    border-color: var(--purple);
    background: white;
    box-shadow: 0 0 0 4px #6c5ce718;
}

.submit-btn {
    display: block;
    width: 100%;
    padding: 16px;
    border: 0;
    border-radius: 13px;
    margin-top: 26px;
    background: linear-gradient(110deg, #6257ed, #a448ec 65%, #df5bd4);
    color: white;
    font: inherit;
    font-weight: 700;
    font-size: 15px;
    cursor: pointer;
    box-shadow: 0 8px 20px #8d55dd30;
    transition: transform .2s, box-shadow .2s;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 25px #8d55dd45;
}

.table-wrap {
    overflow-x: auto;
    border: 1px solid var(--border);
    border-radius: 15px;
}

table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
    font-size: 13px;
}

thead {
    background: linear-gradient(100deg, #f0edff, #fcecff);
}

th {
    padding: 16px 19px;
    color: #5944b8;
    font-weight: 700;
    white-space: nowrap;
}

td {
    padding: 16px 19px;
    border-top: 1px solid #f0eff9;
    color: #514d70;
}

tbody tr {
    transition: background .2s;
}

tbody tr:nth-child(even) {
    background: #faf9ff;
}

tbody tr:hover {
    background: #f0edff;
}

.name-cell {
    font-weight: 600;
    color: #30265f;
}

.count {
    display: inline-block;
    background: #eeeaff;
    color: #6550d7;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    margin-left: auto;
}

.empty {
    padding: 35px 15px;
    text-align: center;
    color: var(--muted);
    font-size: 14px;
}

.footer {
    text-align: center;
    padding: 10px;
    color: #9290b1;
    font-size: 12px;
}

.footer span {
    color: #c45ce6;
}

@media (max-width: 600px) {
    body {
        padding: 22px 13px;
    }

    .hero {
        padding: 29px 23px;
        border-radius: 21px;
    }

    .card {
        padding: 23px 18px;
        border-radius: 19px;
    }

    .section-heading h2 {
        font-size: 21px;
    }

    th, td {
        padding: 13px 14px;
    }
}
</style>
</head>

<body>
<div class="container">

    <header class="hero">
        <div class="eyebrow">A little space to connect</div>
        <h1>Sign Up <span class="heart">♡</span></h1>
        <p>Create your account and become a part of our community.
           Your next chapter starts here.</p>
    </header>

    <main>
        <section class="card">
            <div class="section-heading">
                <span class="emoji">✦</span>
                <h2>Create your account</h2>
            </div>
            <p class="subtitle">
                Just a few details and you're good to go.
            </p>

            <?php if (!empty($errors)): ?>
                <div class="alert error" role="alert">
                    <strong>Oops! Please check the following:</strong>
                    <ul style="padding-left:20px; margin-top:6px;">
                        <?php foreach ($errors as $error): ?>
                            <li><?= e($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($welcomeName !== null): ?>
                <div class="alert success" role="status">
                    <strong>✓ You're all set!</strong><br>
                    Welcome <?= e($welcomeName) ?> from
                    <?= e($welcomeCity) ?>! We're happy you're here.
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="field">
                    <label for="name">Your name</label>
                    <input id="name" type="text" name="name"
                           placeholder="e.g. Alex Morgan"
                           value="<?= e($name) ?>" required>
                </div>

                <div class="field">
                    <label for="age">Your age <span style="color:#aaa">(optional)</span></label>
                    <input id="age" type="number" name="age"
                           placeholder="e.g. 21" min="0"
                           value="<?= e($age) ?>">
                </div>

                <div class="field">
                    <label for="city">Your city</label>
                    <input id="city" type="text" name="city"
                           placeholder="e.g. Dhaka"
                           value="<?= e($city) ?>" required>
                </div>

                <button class="submit-btn" type="submit">
                    ✨ &nbsp; Create My Account &nbsp; →
                </button>
            </form>
        </section>

        <section class="card">
            <div class="section-heading">
                <span class="emoji">♧</span>
                <h2>Our Community</h2>
                <span class="count">
                    <?= count($users) ?> MEMBERS
                </span>
            </div>

            <p class="subtitle">
                Every new person makes this community a little more special.
            </p>

            <?php if (empty($users)): ?>
                <div class="empty">
                    <div style="font-size:35px; margin-bottom:12px;">♡</div>
                    No members yet. Be the first to join!
                </div>
            <?php else: ?>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Age</th>
                                <th>City</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $i => $user): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td class="name-cell"><?= e($user['name']) ?></td>
                                    <td><?= e($user['age'] ?? '—') ?></td>
                                    <td><?= e($user['city']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer class="footer">
        Made with <span>♥</span> for a community that grows together.
    </footer>

</div>
</body>
</html>