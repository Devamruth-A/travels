<?php
if (isset($_POST['cust_id'])) {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "@hogwarts123";
    $dbname = "travel";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $cust_id = $_POST['cust_id'];
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $destination = $_POST['destination'];
    $booking_id = $_POST['booking_id'];
    $dest_id = $_POST['dest_id'];
    $travel_date = $_POST['travel_date'];
    $return_date = $_POST['return_date'];

    // Insert data into customer table
    $stmt = $conn->prepare("INSERT INTO customers (cust_id, f_name, l_name, email, phone) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $cust_id, $f_name, $l_name, $email, $phone);
    if ($stmt->execute()) {
        echo "New record created successfully in customers table<br>";
    } else {
        echo "Error: " . $stmt->error . "<br>";
    }
    $stmt->close();

    // Insert data into booking table
    $stmt = $conn->prepare("INSERT INTO bookings (booking_id, cust_id, dest_id, travel_date, return_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $booking_id, $cust_id, $dest_id, $travel_date, $return_date);
    if ($stmt->execute()) {
        echo "New record created successfully in bookings table<br>";
    } else {
        echo "Error: " . $stmt->error . "<br>";
    }
    $stmt->close();

    // Fetch and display total cost
    $stmt = $conn->prepare("SELECT (DATEDIFF(return_date, travel_date) + 1) * cost AS Total_cost FROM bookings INNER JOIN Destinations ON bookings.dest_id = Destinations.dest_id WHERE booking_id = ?");
    $stmt->bind_param("s", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total_cost = $row['Total_cost'];

    // Display total cost in an HTML table
    echo "<table border='1'>
            <tr>
                <th>Booking ID</th>
                <th>Total Cost</th>
            </tr>
            <tr>
                <td>$booking_id</td>
                <td>$$total_cost</td>
            </tr>
          </table>";

    // Close connection
    $stmt->close();
    $conn->close();
}
?>
