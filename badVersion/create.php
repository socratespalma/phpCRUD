<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=products_crud', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$errors = [];
$title = '';
$price = '';
$description = '';

if ($_SERVER['REQUEST_METHOD']=== 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $date = date('Y-m-d H:i:s');
    
    if (!$title) {
        $errors[] = 'Product title is required';
    }

    if(!$price){
        $errors[] = 'Product price is required';
    }

    if (!is_dir('images')) {
        mkdir('images');
    }

    if (empty($errors)) {
        $image = $_FILES['image'] ?? null;
        $imagePath = '';
        if ($image && $image['tmp_name']) {
            $imagePath = 'images/'.randomString(8).'/'.$image['name'];
            // echo '<pre>';
            // var_dump($imagePath);
            // echo '</pre>';
            // exit;
            mkdir(dirname($imagePath));
            move_uploaded_file($image['tmp_name'], $imagePath);
        }

        $statement = $pdo->prepare("INSERT INTO products (title, image, description, price, create_date) VALUES (:title, :image, :description, :price, :date)");

        $statement->bindValue(':title', $title);
        $statement->bindValue(':image', $imagePath);
        $statement->bindValue(':description', $description);
        $statement->bindValue(':price', $price);
        $statement->bindValue(':date', $date);
        $statement->execute();
        header('Location: index.php');
    }
}

function randomString($n){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    for ($i=0; $i < $n ; $i++) { 
        $index = rand(0, strlen($characters)-1);
        $str .= $characters[$index];
    }
    return $str;
}


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
    <h1>Create New Product</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
            <div><?php echo $error ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

<form action="" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Product Image</label>
        <br>
        <input type="file" name="image">
    </div>
    <div class="mb-3">
        <label>Product Title</label>
        <input type="text" name="title" class="form-control" value="<?php echo $title ?>">
    </div>
    <div class="mb-3">
        <label>Product Description</label>
        <textarea class="form-control" name="description"><?php echo $description ?></textarea>
    </div>
    <div class="mb-3">
        <label>Product Price</label>
        <input type="number" step=".01" name="price" class="form-control" value="<?php echo $price ?>">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
    
</body>
</html>