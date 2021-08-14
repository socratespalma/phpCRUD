<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=products_crud', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$search = $_GET['search'] ?? '';

if ($search) {
  $statement = $pdo->prepare('SELECT * FROM products WHERE title LIKE :title ORDER BY create_date DESC');
  $statement->bindValue(':title', "%$search%");

} else {
  $statement = $pdo->prepare('SELECT * FROM products ORDER BY create_date DESC');

}

$statement->execute();
$products = $statement->fetchAll(PDO::FETCH_ASSOC);

// echo '<pre>';
// var_dump($products);
// echo '</pre>';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css" integrity="sha512-P5MgMn1jBN01asBgU0z60Qk4QxiXo86+wlFahKrsQf37c9cro517WzVSPPV1tDKzhku2iJ2FVgL67wG03SGnNA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="app.css">
    <title>Products CRUD</title>
</head>
<body>
    <h1>Hello World</h1>
    <p>
      <a href="create.php" class="btn btn-success">Create Product</a>
    </p>
    <form action="" method="get">
      <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Search for products" name="search" value="<?php echo $search ?>">
        <div class="input-group-append">
          <button class="btn btn-outline-secondary" type="submit">Search</button>
        </div>
      </div>
    </form>
    <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Image</th>
      <th scope="col">Title</th>
      <th scope="col">Price</th>
      <th scope="col">Create Date</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($products as $i => $product): ?>
  <tr>
      <th scope="row"><?php echo $i + 1 ?></th>
      <td>
        <img src="<?php echo $product['image']?>" class="thumb-image" alt="">
      </td>
      <td><?php echo $product['title'] ?></td>
      <td><?php echo $product['price'] ?></td>
      <td><?php echo $product['create_date'] ?></td>
      <td>
      <a href="update.php?id=<?php echo $product['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
      <form style="display: inline-block;" action="delete.php" method="post">
        <input type="hidden" name="id" value="<?php echo $product['id']?>">
        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
      </form>
      </td>
    </tr>
<?php endforeach; ?>
  </tbody>
</table>
</body>
</html>