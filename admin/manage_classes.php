<?php

$page_title = "Manage Classes";
$page_desc = "View, edit, or delete classes from the FitZone gym management system";
$headerButton = '<button class="add-class-btn" id="addClassBtn">Add New Class</button>';
include 'admin_header.php';


$sql = "SELECT classes.id, classes.title, classes.description, classes.schedule, classes.capacity, trainers.name AS trainer 
        FROM classes 
        LEFT JOIN trainers ON classes.trainer_id = trainers.id";
$result = $conn->query($sql);
?>



<div class="table-container">
    <table class="styled-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Schedule</th>
                <th>Trainer</th>
                <th>Capacity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()) { ?>
            <tr data-id="<?= $row['id'] ?>">
                <td><?= $row['id'] ?></td>
                <td class="editable"><?= htmlspecialchars($row['title']) ?></td>
                <td class="editable"><?= htmlspecialchars($row['description']) ?></td>
                <td class="editable"><?= htmlspecialchars($row['schedule']) ?></td>
                <td><?= htmlspecialchars($row['trainer']) ?></td>
                <td class="editable"><?= $row['capacity'] ?></td>
                <td>
                    <button class="edit-btn">Edit</button>
                    <button class="save-btn" style="display:none;">Save</button>
                    <button class="delete-btn">Delete</button>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<style>

/* Modal */
.modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0; top: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.6);
  justify-content: center;
  align-items: center;
}

.modal-content {
  background: #fff;
  padding: 25px;
  border-radius: 12px;
  width: 400px;
  max-width: 95%;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.close {
  float: right;
  font-size: 24px;
  cursor: pointer;
  color: #444;
}

.close:hover {
  color: #000;
}

/* Buttons */
.btn-add {
  background: #28a745;
  color: white;
  border: none;
  padding: 10px 18px;
  border-radius: 6px;
  cursor: pointer;
}

.btn-add:hover {
  background: #218838;
}

.btn-submit {
  background: #007bff;
  color: white;
  border: none;
  padding: 10px 18px;
  border-radius: 6px;
  cursor: pointer;
}

.btn-submit:hover {
  background: #0056b3;
}


</style>

<!-- Modal Structure -->
<div id="classModal" class="modal">
  <div class="modal-content">
    <span id="closeModalBtn" class="close">&times;</span>
    <h3>Add a New Class</h3>
    <form action="admin_add_class.php" method="POST" id="addClassForm">
      <div class="form-group">
        <label for="title">Class Title</label>
        <input type="text" id="title" name="title" required>
      </div>

      <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" required></textarea>
      </div>

      <div class="form-group">
        <label for="schedule">Schedule</label>
        <input type="text" id="schedule" name="schedule" required>
      </div>

      <div class="form-group">
        <label for="capacity">Capacity</label>
        <input type="number" id="capacity" name="capacity" min="1" required>
      </div>

      <div class="form-group">
        <label for="trainer_id">Trainer</label>
        <select id="trainer_id" name="trainer_id" required>
          <?php
            $result = $conn->query("SELECT id, name FROM trainers");
            while($row = $result->fetch_assoc()){
              echo "<option value='".$row['id']."'>".ucwords($row['name'])."</option>";
            }
          ?>
        </select>
      </div>

      <button type="submit" class="btn-submit">Save Class</button>
    </form>
  </div>
</div>


<script>
// Editing class data
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        let row = this.closest('tr');
        row.classList.add('editing');

        row.querySelectorAll('.editable').forEach(cell => {
            let text = cell.textContent.trim();
            cell.innerHTML = `<input type="text" value="${text}">`;
        });

        // Focus on first input (Title column)
        row.querySelector('input').focus();

        this.style.display = "none";
        row.querySelector('.save-btn').style.display = "inline-block";
    });
});

// (updating class data)
document.querySelectorAll('.save-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        let row = this.closest('tr');
        let id = row.getAttribute('data-id');
        let inputs = row.querySelectorAll('input');
        let values = Array.from(inputs).map(input => input.value);


        fetch("admin_update_class.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                id,
                title: values[0],
                description: values[1],
                schedule: values[2],
                capacity: values[3]
            })
        })
        .then(res => res.text())
        .then(() => {
            row.querySelectorAll('.editable').forEach((cell, i) => {
                cell.textContent = values[i];
            });
            row.classList.remove('editing');
            row.querySelector('.edit-btn').style.display = "inline-block";
            this.style.display = "none";
            //console.log(values);
        });
    });
});

// deleting a class
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        let row = this.closest('tr');
        let id = row.getAttribute('data-id');
        console.log(id);

        if(confirm("Are you sure you want to delete this class?")) {
            fetch("admin_delete_class.php", {
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


//Modal opening...
const modal = document.getElementById("classModal");
const openModalBtn = document.getElementById("addClassBtn");
const closeModalBtn = document.getElementById("closeModalBtn");

openModalBtn.onclick = function() {
    modal.style.display = "flex";
}

closeModalBtn.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
}

const form = document.getElementById("addClassForm");

// Handle form submission with AJAX
form.addEventListener("submit", function(e) {
    e.preventDefault();

        fetch("admin_add_class.php", {
            method: "POST",
            body: new FormData(form)
        })
        .then(res => res.text())
        .then(data => {
            alert(data);
            modal.classList.add("hidden");
            form.reset();

            // Auto-refresh the page to show new class
            location.reload();
        })
        .catch(err => console.error(err));
    });

</script>

<style>
.header-container {
    text-align: center;
    background: linear-gradient(135deg, #00b09b, #96c93d);
    color: white;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.add-class-btn {
    position: absolute;
    right: 20px;
    bottom: 20px;
    padding: 10px 18px;
    background: #c9113a;
    color: #ffee01;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: all 0.2s ease;
    max-width: 160px;
    text-align: center;
}

.add-class-btn:hover {
    background: #17a673;
    transform: translateY(-2px);
}

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
.editing {
    background: #fffae6 !important;
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
.edit-btn { background: #007bff; color: white; }
.save-btn { background: #28a745; color: white; }
.delete-btn { background: #dc3545; color: white; }
button:hover { opacity: 0.85; }
@media(max-width: 600px) {
    .styled-table th, .styled-table td { font-size: 12px; padding: 8px; }
    button { font-size: 12px; padding: 4px 8px; }
}
.page-header{
    position: relative !important;
}
</style>

<?php

include '../includes/footer.php';