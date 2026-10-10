<?php

$dsn = 'mysql:host=localhost;port=3306;dbname=myapp;charset=utf8mb4';
$pdo = new PDO($dsn, 'root', '');

$errors = [];
$welcomeName = null;
$welcomeCity = null;
// values  to keep in the form after failed submit
$name='';
$age='';
$city='';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name=trim($_POST['name'] ?? '');
    $age=trim($_POST['age'] ?? '');
    $city=trim($_POST['city'] ?? '');
  
    // TODO 1: Validate name and city. Put messages in $errors.
    if($name===''){
      $errors[]='Name is required';
    }
    if($city===''){
      $errors[]='City is required';
    }


    // TODO 2: If $errors is empty, INSERT the user with a prepared statement.
    if(empty($errors)){
      $stmt=$pdo->prepare(
        'INSERT INTO users(name,age,city)VALUES(:name,:age,:city)'
      );

      $stmt->execute([
        'name'=>$name,
        'age'=>$age==='' ? null : $age,
        'city'=>$city,
      ]);
      $welcomeName=$name;
      $welcomeCity=$city;

      $name = $age = $city = '';

    }
}
$users = $pdo->query('SELECT name, age, city FROM users ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
</head>
<body>
    <h1>Sign Up</h1>

    <!-- TODO 3: Show the errors here. -->
     <?php foreach($errors as $error): ?>
      <p><?=htmlspecialchars($error , ENT_QUOTES , 'UTF-8')?></p>
      <?php endforeach;?>

    <form method="POST">
        <p><input name="name" placeholder="Name" value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"></p>
        <p><input name="age" placeholder="Age" value="<?= htmlspecialchars($age,ENT_QUOTES,'UTF-8')?>"></p>
        <p><input name="city" placeholder="City "value="<?= htmlspecialchars($city,ENT_QUOTES,'UTF-8')?>"></p>
        <button>Sign Up</button>
    </form>

    <!-- TODO 4: Show "Welcome ___ from ___!" after a successful sign up. -->
     <?php if ($welcomeName !== NULL):?>
      <p>
        <strong>
           Welcome <?= htmlspecialchars($welcomeName, ENT_QUOTES, 'UTF-8') ?> from <?= htmlspecialchars($welcomeCity, ENT_QUOTES, 'UTF-8') ?> ! 
        </strong>
      </p>
      <?php endif; ?>

      <h2>All Users</h2>
      <?php if(empty ($users)): ?>
        <p>No users yet. </p>
      <?php else: ?>
        <table border="1" cellpadding="6">
            <tr><th>Name</th><th>Age</th><th>City</th></tr>
            <?php foreach($users as $user):?>
              <tr>
                <td><?=htmlspecialchars($user['name']) ?></td>
                <td><?=htmlspecialchars((string)$user['age']??'') ?></td>
                <td><?=htmlspecialchars($user['city']) ?></td>
            </tr>
          <?php endforeach ; ?>
        </table>
      <?php endif; ?>

</body>
</html>
