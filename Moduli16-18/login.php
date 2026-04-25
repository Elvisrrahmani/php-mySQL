<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
  <?php
  include('header.php')
  ?>
<section class="vh-100 d-flex align-items-center justify-content-center bg-light">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-5 col-xl-4">
        <div class="card shadow-lg border-0 rounded-4">
          <div class="card-body p-5">

            <div class="text-center mb-4">
              <h2 class="fw-bold text-dark">Sign In</h2>
              <p class="text-muted">Enter your details to access your account</p>
            </div>

            <form>

              <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email Address</label>
                <input type="email" class="form-control form-control-lg" id="email" placeholder="name@example.com" required>
              </div>


              <div class="mb-3">
                <div class="d-flex justify-content-between">
                  <label for="password" class="form-label fw-semibold">Password</label>
                  <a href="#" class="text-decoration-none small fw-bold">Forgot password?</a>
                </div>
                <input type="password" class="form-control form-control-lg" id="password" placeholder="Enter password" required>
              </div>


              <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input" id="remember">
                <label class="form-check-label text-muted" for="remember">Remember me for 30 days</label>
              </div>


              <div class="d-grid mb-4">
                <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-3">Sign In</button>
              </div>

              <div class="position-relative mb-4 text-center">
                <hr class="text-muted">
                <span class="position-absolute top-50 start-50 translate-middle bg-white px-2 text-muted small">OR</span>
              </div>

              <div class="d-grid">
                <button type="button" class="btn btn-outline-dark btn-lg mb-2 rounded-3 d-flex align-items-center justify-content-center">
                  <i class="fab fa-google me-2"></i> <span class="small">Sign in with Google</span>
                </button>
              </div>
            </form>

            <div class="text-center mt-4">
              <p class="mb-0 text-muted">Don't have an account? <a href="index.php" class="text-decoration-none fw-bold">Sign up</a></p>
            </div>
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