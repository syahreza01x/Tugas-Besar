<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="text-center text-white">
        <h1 class="display-1 fw-bold">404</h1>
        <i class="fas fa-ghost fa-5x mb-4"></i>
        <h2 class="mb-4">Page Not Found</h2>
        <p class="lead mb-4">The page you are looking for doesn't exist or has been moved.</p>
        <a href="<?= baseUrl('') ?>" class="btn btn-light btn-lg">
            <i class="fas fa-home me-2"></i>Go Home
        </a>
    </div>
</body>
</html>
