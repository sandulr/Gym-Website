<?php

$page_title = "View Queries";
$page_desc = "View or delete submitted queries from the FitZone gym management system";

include 'admin_header.php';
include '../includes/db.php';


function timeAgo($datetime) {
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return "just now";
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return $minutes . " minute" . ($minutes > 1 ? "s" : "") . " ago";
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . " hour" . ($hours > 1 ? "s" : "") . " ago";
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . " day" . ($days > 1 ? "s" : "") . " ago";
    } elseif ($diff < 2592000) {
        $weeks = floor($diff / 604800);
        return $weeks . " week" . ($weeks > 1 ? "s" : "") . " ago";
    } elseif ($diff < 31536000) {
        $months = floor($diff / 2592000);
        return $months . " month" . ($months > 1 ? "s" : "") . " ago";
    } else {
        $years = floor($diff / 31536000);
        return $years . " year" . ($years > 1 ? "s" : "") . " ago";
    }
}

$sql = "SELECT * FROM contacts ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<div class="container">
    <h2>View Queries</h2>
    <table class="styled-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Date Submitted</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0) { ?>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr data-id="<?= $row['id'] ?>">
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <!-- nl2br = new line to break(ex: \b => <br />) -->
                        <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                        <td><?php echo timeAgo($row['created_at']); ?></td>
                        <td>
                            <button type="submit" class="delete-btn">Delete</button>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="6">No queries found.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<style>

.table-container {
    overflow-x: auto;
}

.styled-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.styled-table th, .styled-table td {
    padding: 12px 15px;
    border: 1px solid #ddd;
    text-align: center;
}
.styled-table tr:nth-child(even) {
    background: #f9f9f9;
}

button {
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    margin: 2px;
    cursor: pointer;
    font-size: 14px;
    transition: 0.3s;
}

.delete-btn { background: #dc3545; color: white; }
button:hover { opacity: 0.85; }

@media(max-width: 600px) {
    .styled-table th, .styled-table td { font-size: 12px; padding: 8px; }
    button { font-size: 12px; padding: 4px 8px; }
}

</style>

<script type="text/javascript">

document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        let row = this.closest('tr');
        let id = row.getAttribute('data-id');

        if(confirm("Are you sure you want to delete this query?")) {
            fetch("admin_delete_query.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    id
                })
            })
            .then(res => res.text())
            .then(() => row.remove());
        }
    });
});


</script>

<?php

include '../includes/footer.php';
?>