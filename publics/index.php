<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../controllers/UserController.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $userController = new UserController();
        $response = $userController->createUser($_POST);
        
        if($response['status'] === 'success') {
            $_SESSION['user_id'] = $response['user_id'];
            header('Location: ./publics/questions.php');
            exit();
        } else {
            $error = $response['message'];
            error_log("User creation failed: " . $error);
        }
    } catch (Exception $e) {
        error_log("Exception caught: " . $e->getMessage());
        header('Location: .././views/codepages/500.php');
        exit();
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tracer Study</title>

    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="./views/assets/css/styles.css" />

    <style>
      /* Posisi normal */
      .input-label {
        left: 10px;
        top: 35px;
        color: #a0aec0;
      }

      /* Posisi jika input aktif atau ada teks */
      .input-label.active {
        left: 0;
        top: 0;
        font-size: 0.8rem;
        color: #4a5568;
      }
      .checkbox-wrapper-2 .ikxBAC {
        appearance: none;
        background-color: #6e79d6;
        border-radius: 72px;
        border-style: none;
        flex-shrink: 0;
        height: 20px;
        margin: 0;
        position: relative;
        width: 30px;
      }

      .checkbox-wrapper-2 .ikxBAC::before {
        bottom: -6px;
        content: "";
        left: -6px;
        position: absolute;
        right: -6px;
        top: -6px;
      }

      .checkbox-wrapper-2 .ikxBAC,
      .checkbox-wrapper-2 .ikxBAC::after {
        transition: all 100ms ease-out;
      }

      .checkbox-wrapper-2 .ikxBAC::after {
        background-color: #fff;
        border-radius: 50%;
        content: "";
        height: 14px;
        left: 3px;
        position: absolute;
        top: 3px;
        width: 14px;
      }

      .checkbox-wrapper-2 input[type="checkbox"] {
        cursor: default;
      }

      .checkbox-wrapper-2 .ikxBAC:hover {
        background-color: #c9cbcd;
        transition-duration: 0s;
      }

      .checkbox-wrapper-2 .ikxBAC:checked {
        background-color: #6e79d6;
      }

      .checkbox-wrapper-2 .ikxBAC:checked::after {
        background-color: #fff;
        left: 13px;
      }

      .checkbox-wrapper-2 :focus:not(.focus-visible) {
        outline: 0;
      }

      .checkbox-wrapper-2 .ikxBAC:checked:hover {
        background-color: #535db3;
      }
    </style>
</head>
<body class="bg-neutral-50 transition-all duration-300 font-poppins">
<?php include __DIR__ . '/../views/components/navbar.php'; ?>
<?php include __DIR__ . '/../views/questions/form.php'; ?>
<script src="./views/assets/js/do_this_animator_exe.js"></script>
</body>
</html> 
