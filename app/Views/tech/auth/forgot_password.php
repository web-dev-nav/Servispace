<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Servispace</title>
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
        .login-logo img {
            max-width: 150px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-logo">
                <img src="<?= base_url('assets/img/logo.png') ?>" alt="Servispace Logo" class="img-fluid">
                <h2>Forgot Password</h2>
                <p class="text-muted">Servispace</p>
            </div>
            
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <?php if(session()->getFlashdata('message')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('message') ?>
                </div>
                
                <?php if(session()->getFlashdata('resetLink')): ?>
                    <div class="alert alert-info">
                        <p><strong>Demo Only:</strong> In a real application, this link would be sent via email.</p>
                        <a href="<?= session()->getFlashdata('resetLink') ?>" class="btn btn-info btn-sm">Reset Password</a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            
            <p>Enter your email address below and we'll send you a link to reset your password.</p>
            
            <form action="<?= site_url('tech/forgot-password') ?>" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
                </div>
                <div class="text-center">
                    <a href="<?= site_url('tech/login') ?>">Back to Login</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>