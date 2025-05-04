<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Your Password</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="border: 1px solid #ddd; border-radius: 5px; padding: 20px;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2>Reset Your Password</h2>
        </div>
        
        <p>Hello <?= $technician['name'] ?>,</p>
        
        <p>We received a request to reset your password for your Servispace technician account. Click the button below to set a new password:</p>
        
        <p style="text-align: center; margin: 30px 0;">
            <a href="<?= $resetLink ?>" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">Reset Password</a>
        </p>
        
        <p>This link will expire in 1 hour. If you did not request a password reset, please ignore this email.</p>
        
        <p>Regards,<br>The Servispace Team</p>
    </div>
</body>
</html>