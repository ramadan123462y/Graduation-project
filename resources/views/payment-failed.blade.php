
<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فشل الدفع</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<body>
    {{-- <style> 
        .swal2-confirm {
            display: none !important;
        }
    </style> --}}

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: "error",
                title: "فشل الدفع",
                text: " للأسف، لم تكتمل عملية الدفع. يرجى المحاولة مرة أخرى او التواصل مع الدعم.",
                confirmButtonText: "حسناً"
            });
        });
    </script>
</body>
