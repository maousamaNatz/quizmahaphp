<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>NIM</th>
                <th>Email</th>
                <th>Tahun Lulus</th>
                <th>Perguruan</th>
                <th>Jawaban</th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><?= htmlspecialchars($user['nama']) ?></td>
                <td><?= htmlspecialchars($user['nim']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['thn_lulus']) ?></td>
                <td><?= htmlspecialchars($user['perguruan']) ?></td>
                <td><a href="results.php?user_id=<?= $user['id'] ?>">Lihat Jawaban</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
