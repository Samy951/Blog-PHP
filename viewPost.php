<?php
require_once 'lib/common.php';

// Get the post ID
$postId = $_GET['post_id'] ?? 0;


// Connect to the database, run a query, handle errors
$pdo = getPDO();
$stmt = $pdo->prepare(
    'SELECT
        title, created_at, body
    FROM
        post
    WHERE
        id = :id'
);
if ($stmt === false)
{
    throw new Exception('There was a problem preparing this query');
}
$result = $stmt->execute(
    array('id' => $postId, )
);
if ($result === false)
{
    throw new Exception('There was a problem running this query');
}
// Let's get a row
$row = $stmt->fetch(PDO::FETCH_ASSOC);


// Swap carriage returns for paragraph breaks
$bodyText = htmlEscape($row['body']);
$paraText = str_replace("\n", "</p><p>", $bodyText);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>
        A blog application |
        <?php echo htmlEscape($row['title']) ?>
    </title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
</head>
<body>
<?php require 'templates/title.php' ?>
<h2>
    <?php echo htmlEscape($row['title']) ?>
</h2>
<div>
    <?php echo convertSqlDate($row['created_at']) ?>
</div>
<p>
    <?php echo $paraText ?>
</p>
</body>
</html>