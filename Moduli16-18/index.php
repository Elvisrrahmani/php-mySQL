<?php

include_once('config.php');

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password= $_POST['confirm_password'];

 if(empty($name) || empty($surname) || empty($username) ||empty($email) ||  empty($password) || empty($confirm_password)){
    echo " You are required to fill all of the fields above!";
 }
 else{
    $sql = "INSERT INTO users(name, surname, username, email,password, confirm_password) VALUES (:name,:surname,:username,:email, :password,:confirm_password)" ;

    $insertsql = $connect->prepare($sql);


    $insertsql->bindParam(':name', $name);
    $insertsql->bindParam(':surname', $surname);
    $insertsql->bindParam(':username', $username);
    $insertsql->bindParam(':email', $email);
    $insertsql->bindParam(':password', $password);
    $insertsql->bindParam(':confirm_passworde', $confirm_password);

    $insertsql->execute();

    header("Location: login.php");
}
 
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
  <?php
  include('header.php')
  ?>
<section class="min-vh-100 d-flex align-items-center bg-light">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-4">
        
        
        <div class="card border-0 shadow-sm rounded-3">
          <div class="card-body p-4 p-sm-5">
            <h3 class="card-title text-center mb-5 fw-light fs-2">Welcome Back</h3>
            
            <form>

              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Name</label>
              </div>

              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Surname</label>
              </div>
          <div 
          class="form-floating mb-3">
                <input type="text"class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Username</label>
              </div>
              <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Email address</label>
              </div>

              
              <div class="form-floating mb-3">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">Password</label>
              </div>

<div class="form-floating mb-3">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">confirm password</label>
              </div>
             
              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="" id="rememberMe">
                <label class="form-check-label" for="rememberMe">
                  Remember password
                </label>
              </div>

              
              <div class="d-grid">
                <button class="btn btn-dark btn-login text-uppercase fw-bold" type="submit">Login</button>
              </div>

              <hr class="my-4">

              <div class="d-grid mb-2">
                <button class="btn btn-outline-danger text-uppercase fw-bold" type="submit">
                  <i class="fab fa-google me-2"></i> Login with Google
                </button>
              </div>
              <div class="d-grid">
                <button class="btn btn-outline-primary text-uppercase fw-bold" type="submit">
                  <i class="fab fa-facebook-f me-2"></i> Login with Facebook
                </button>
              </div>
            </form>
            
          </div>
        </div>
        
      </div>
    </div>
  </div>
</section>
  <?php
  include('footer.php')
  ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>