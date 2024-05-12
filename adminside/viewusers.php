<?php
include "config.php";
$sql = "SELECT * FROM user_form";
$all_users = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en" title="Coding design">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>View Users</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</head>

<div class="sidebar">
    <img src="images/logo.png" alt="Logo">
    <div class="sidebar-nav">
        <a href="dashboard.php">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="viewbuyer.php">
            <i class="fas fa-ticket-alt"></i> Buyers
        </a>
        <a href="viewconcert.php">
            <i class="fas fa-music"></i> Concerts
        </a>
        <a href="viewusers.php">
            <i class="fas fa-users"></i> Users
        </a>
        <a href="../login.php">
            <i class="fas fa-sign-out-alt"></i>
            </i> Exit
        </a>
    </div>
</div>


<body>
    <main class="table">
        <section class="table__header">
            <h1>Concert User Information</h1>
            <div class="input-group">
                <input type="text" name="search" class="searchinput" placeholder="Search Data...">
                <img src="images/search.png" alt="">
            </div>
        </section>
        <section class="table__body">
            <table>
                <thead>
                    <tr>
                        <th scope="col">User ID</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        <th scope="col">Password</th>
                        <th scope="col">Fullname</th>
                        <th scope="col">DOB</th>
                        <th scope="col">Phone Number</th>
                        <th scope="col">Address</th>
                        <th scope="col">Account Date Created</th>
                        <th scope="col">Image</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php


                    while($row = $all_users->fetch_assoc()) {
                        ?>
                    <tr>
                        <td>
                            <?php echo $row['id'] ?>
                        </td>
                        <td>
                            <?php echo $row['username'] ?>
                        </td>
                        <td>
                            <?php echo $row['email'] ?>
                        </td>
                        <td>
                            <?php echo $row['password'] ?>
                        </td>
                        <td>
                            <?php echo $row['fullname'] ?>
                        </td>
                        <td>
                            <?php echo $row['dob'] ?>
                        </td>
                        <td>
                            <?php echo $row['phonenum'] ?>
                        </td>
                        <td>
                            <?php echo $row['address'] ?>
                        </td>
                        <td>
                            <?php echo $row['accdate'] ?>
                        </td>
                        <td>
                            <?php echo $row['image'] ?>
                        </td>
                        <td>
                            <a href="edituser.php?id=<?php echo $row['id'] ?>" class="link-dark"><i
                                    class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>

                            <a href="#" class="link-dark" onclick="confirmDelete(<?php echo $row['id'] ?>)">
                                <i class="fa-solid fa-trash fs-5 "></i>
                            </a>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

                    <script>
                    function confirmDelete(userid) {
                        var confirmation = confirm("Are you sure you want to delete this concert?");
                        if (confirmation) {
                            window.location.href = "deleteuser.php?id=" + userid;
                        }
                    }

                    $(document).ready(function() {
                        $('.searchinput').keyup(function() {
                            // Get the search query
                            var query = $(this).val();
                            $.ajax({
                                type: 'POST',
                                url: 'searchuser.php',
                                data: {
                                    query: query
                                },
                                dataType: 'json',
                                success: function(response) {
                                    // Update the table with the received data
                                    updateTable(response);
                                },
                                error: function(xhr, status, error) {
                                    console.error(xhr.responseText);
                                }
                            });
                        });

                        function updateTable(data) {
                            var tableBody = $('tbody');
                            tableBody.empty();

                            if (data.length > 0) {
                                // Append the new rows to the table
                                $.each(data, function(index, row) {
                                    var newRow = '<tr>';
                                    newRow += '<td>' + row.id + '</td>';
                                    newRow += '<td>' + row.username + '</td>';
                                    newRow += '<td>' + row.email + '</td>';
                                    newRow += '<td>' + row.password + '</td>';
                                    newRow += '<td>' + row.fullname + '</td>';
                                    newRow += '<td>' + row.dob + '</td>';
                                    newRow += '<td>' + row.phonenum + '</td>';
                                    newRow += '<td>' + row.address + '</td>';
                                    newRow += '<td>' + row.accdate + '</td>';
                                    newRow += '<td>' + row.image + '</td>';
                                    newRow += '<td>';
                                    newRow += '<a href="editcust.php?id=' + row.id +
                                        '" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-2"></i></a>';
                                    newRow += '<a href="#" onclick="confirmDelete(' + row.id +
                                        ')" class="link-dark"><i class="fa-solid fa-trash fs-5"></i></a>';
                                    newRow += '</td>';
                                    newRow += '</tr>';
                                    tableBody.append(newRow);
                                });
                            } else {
                                var noResultsRow = '<tr><td colspan="14">No results found</td></tr>';
                                tableBody.append(noResultsRow);
                            }
                        }
                    });
                    </script>
                </tbody>
            </table>
        </section>
    </main>
</body>

</html>