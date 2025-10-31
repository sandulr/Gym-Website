<?php

$page_title = "Manage Users";
$page_desc = "View, edit, or delete users from the FitZone gym management system";
include 'admin_header.php'; 

// Fetch all users
$result = mysqli_query($conn, "SELECT id, fullname, email, role FROM users ORDER BY id DESC");
?>

<style>

.table-container {
    width: 100%;
    overflow-x: auto;
    margin: 20px 0;
    -webkit-overflow-scrolling: touch;
}

.styled-table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Segoe UI', sans-serif;
    min-width: 600px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 8px;
    overflow: hidden;
}

.styled-table thead {
    background-color: #1976d2;
    color: white;
    font-weight: bold;
}

.styled-table thead th {
    padding: 12px 15px;
    text-align: left;
}


.styled-table tbody tr {
    border-bottom: 1px solid #ddd;
    transition: background-color 0.3s ease;
}

.styled-table tbody tr:hover {
    background-color: #f1f1f1;
}

.styled-table tbody tr.editing {
    background-color: #fff3e0;
}

.styled-table tbody td {
    padding: 10px 12px;
}


/* making it responsive */
@media (max-width: 768px) {
    .styled-table, .styled-table thead, .styled-table tbody, .styled-table th, .styled-table td, .styled-table tr {
        display: block;
    }
    .styled-table thead tr {
        display: none;
    }
    .styled-table tbody tr {
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 10px;
        background-color: #fff;
    }
    .styled-table tbody td {
        text-align: right;
        padding-left: 50%;
        position: relative;
    }
    .styled-table tbody td::before {
        content: attr(data-label);
        position: absolute;
        left: 12px;
        font-weight: bold;
        text-align: left;
    }
}

/* medium sized- mobile */
@media (max-width: 480px) {
    .styled-table, .styled-table thead, .styled-table tbody, .styled-table th, .styled-table td, .styled-table tr {
        display: block;
    }
    .styled-table thead tr {
        display: none;
    }
    .styled-table tbody tr {
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 10px;
        background-color: #fff;
    }
    .styled-table tbody td {
        text-align: right;
        padding-left: 50%;
        position: relative;
        white-space: normal;
        word-wrap: break-word;
    }
    .styled-table tbody td::before {
        content: attr(data-label);
        position: absolute;
        left: 12px;
        font-weight: bold;
        text-align: left;
        width: 45%;
    }
}

/* tiny screens = less than 300px */
@media (max-width: 300px) {
    .table-container {
        overflow-x: auto;
    }
    .styled-table tbody td {
        display: table-cell;
        padding-left: 8px;
    }
    .styled-table tbody td::before {
        content: none;
    }
}

button.action-btn {
    display: inline-block;
    max-width: 100px;
    padding: 5px 12px;
    margin-right: 5px;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
    color: white;
    text-align: center;
    white-space: nowrap;
}

button.edit-btn { background-color: #4caf50; }
button.edit-btn.editing { background-color: #1976d2; }
button.delete-btn { background-color: #f44336; }


button.action-btn:hover {
    opacity: 0.9;
    transform: scale(1.05);
    transition: all 0.2s ease;
}

</style>


<div class="table-container">
    <table id="usersTable" class="styled-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr data-id="<?= $row['id'] ?>">
                <td class="user-id"><?= $row['id'] ?></td>
                <td class="user-name"><?= htmlspecialchars($row['fullname']) ?></td>
                <td class="user-email"><?= htmlspecialchars($row['email']) ?></td>
                <td class="user-role"><?= ucfirst($row['role']) ?></td>
                <td>
                    <button class="action-btn edit-btn">Edit</button>
                    <button class="action-btn delete-btn">Delete</button>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>


<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function() {
    const table = document.getElementById("usersTable");

    table.addEventListener("click", function(e) {
        const target = e.target;
        const row = target.closest("tr");
        const userId = row.dataset.id;

        // Edit user
        if (target.classList.contains("edit-btn")) {
            const isEditing = row.classList.contains("editing");

            if (!isEditing) {
                // Enable editing
                row.classList.add("editing");

                const nameCell = row.querySelector(".user-name");
                const emailCell = row.querySelector(".user-email");
                const roleCell = row.querySelector(".user-role");

                nameCell.contentEditable = true;
                emailCell.contentEditable = true;
                roleCell.contentEditable = true;

                target.textContent = "Save";
                target.classList.add("editing");

                //  Focus on Full Name cell and set cursor
                nameCell.focus();

                // Optional: move cursor to end of text
                const range = document.createRange();
                const sel = window.getSelection();
                range.selectNodeContents(nameCell);
                range.collapse(false); // place cursor at the end
                sel.removeAllRanges();
                sel.addRange(range);

            } else {
                // Save changes via AJAX
                const name = row.querySelector(".user-name").textContent.trim();
                const email = row.querySelector(".user-email").textContent.trim();
                const role = row.querySelector(".user-role").textContent.trim();

                fetch("admin_update_user.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ id: userId, name, email, role })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert("User updated successfully!");
                        row.classList.remove("editing");
                        target.textContent = "Edit";
                        target.classList.remove("editing");
                        row.querySelector(".user-name").contentEditable = false;
                        row.querySelector(".user-email").contentEditable = false;
                        row.querySelector(".user-role").contentEditable = false;
                    } else {
                        alert("Error: " + data.error);
                    }
                });
            }
        }

        // Delete user (unchanged)
        if (target.classList.contains("delete-btn")) {
            if (confirm("Are you sure you want to delete this user?")) {
                fetch("admin_delete_user.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ id: userId })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        row.remove();
                        alert("User deleted successfully!");
                    } else {
                        alert("Error: " + data.error);
                    }
                });
            }
        }
    });
});



</script>

<?php

include '../includes/footer.php';