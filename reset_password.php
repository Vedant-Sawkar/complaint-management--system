<?php

include "includes/connection.php";

$message = "";

$token = $_GET['token'];

if (isset($_POST['reset'])) {

    $password = password_hash(
        $_POST['password'],
        PASSWORD_DEFAULT
    );

    mysqli_query(
        $conn,
        "UPDATE users
         SET password='$password',
             reset_token=NULL
         WHERE reset_token='$token'"
    );

    header("Location: login.php");
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Reset Password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="card shadow">

                <div class="card-header">

                    <h3>Reset Password</h3>

                </div>

                <div class="card-body">

                    <form method="POST">

                        <div class="mb-3">

                            <label>New Password</label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required
                            >

                        </div>

                        <button
                            type="submit"
                            name="reset"
                            class="btn btn-success w-100"
                        >

                            Reset Password

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>