<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Servispace</title>
    <!-- Zephyr Bootswatch Theme -->
    <link rel="stylesheet" href="https://bootswatch.com/5/zephyr/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-logo i {
            font-size: 3rem;
            color: #506cd0;
            margin-bottom: 15px;
        }
        .btn-primary {
            background-color: #506cd0;
            border-color: #4963c5;
        }
        .btn-primary:hover {
            background-color: #3e56b2;
            border-color: #3a50a8;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
        <div class="login-logo">
            <img src="<?= base_url('assets/img/logo.png') ?>" alt="Servispace Logo" class="img-fluid mb-3" style="max-width: 150px;">
            <h2>Admin Login</h2>
            <p class="text-muted">Servispace</p>
        </div>
            
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <?php if(session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <ul>
                    <?php foreach(session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form action="<?= site_url('admin/login') ?>" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= old('username') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary w-100">Log In</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>