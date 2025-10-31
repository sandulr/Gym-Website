<?php

$page_title = "Manage Trainers";
$page_desc = "View, edit, or delete trainers from the FitZone gym management system";
$headerButton = '<button class="add-class-btn" id="addClassBtn">Add a New Trainer</button>';
include 'admin_header.php';


$sql = "SELECT * FROM trainers";
$result = $conn->query($sql);
?>


<div class="table-container">
    <table class="styled-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Bio</th>
                <th>Specialties</th>
                <th>Photo</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()) { ?>
            <tr data-id="<?= $row['id'] ?>">
                <td><?= $row['id'] ?></td>
                <td class="editable"><?= htmlspecialchars($row['name']) ?></td>
                <td class="editable"><?= htmlspecialchars($row['bio']) ?></td>
                <td class="editable"><?= htmlspecialchars($row['specialties']) ?></td>
                <td>
                    <?php if(!empty($row['photo'])): ?>
                        <img src="../assets/images/<?= $row['photo'] ?>" class="trainer-photo" alt="<?= htmlspecialchars($row['name']) ?>">
                    <?php else: ?>
                        <img src="../assets/images/def_trainer.png" class="trainer-photo" alt="Default Trainer Photo">
                    <?php endif; ?>
                    <!-- file input(hidden by def) -->
                    <input type="file" name="photo" id="photoInput_<?= $row['id'] ?>" class="photo-input hidden" accept="image/*">
                </td>
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

.photo-input.hidden {
    display: none;
}


.trainer-photo {
    max-width: 120px;
    max-height: 120px;
    width: auto;
    height: auto;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
}


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
    <h3>Add a New Trainer</h3>
    <form action="admin_add_trainer.php" method="POST" id="addClassForm">
      <div class="form-group">
        <label for="name">Trainer Name</label>
        <input type="text" id="name" name="name" required>
      </div>

      <div class="form-group">
        <label for="bio">Description</label>
        <textarea id="bio" name="bio" required></textarea>
      </div>

      <div class="form-group">
        <label for="specialties">Specialties</label>
        <input type="text" id="specialties" name="specialties" required>
      </div>

      <div class="form-group">
        <label for="photo">Photo</label>
        <input type="file" name="photo" id="photo" class="photo-input" accept="image/*">
      </div>
      <br />

      <button type="submit" class="btn-submit">Add Trainer</button>
    </form>
  </div>
</div>


<script>

// Editing class data
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        let row = this.closest('tr');
        let id = row.getAttribute('data-id');
        //console.log(id);
        row.classList.add('editing');

        row.querySelectorAll('.editable').forEach(cell => {
            let text = cell.textContent.trim();
            cell.innerHTML = `<input type="text" value="${text}">`;
        });

        // Focus on first input (Title column)
        row.querySelector('input').focus();

        // Show the file input
        //console.log(document.getElementById(`photoInput_`+id));
        document.getElementById(`photoInput_${id}`).classList.remove("hidden");

        this.style.display = "none";
        row.querySelector('.save-btn').style.display = "inline-block";
    });
});

// updating trainer data
document.querySelectorAll('.save-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        let row = this.closest('tr');
        let id = row.getAttribute('data-id');
        let inputs = row.querySelectorAll('input');
        let values = Array.from(inputs).map(input => input.value);

        let attArray = ['name', 'bio', 'specialties', 'photo'];

        const formData = new FormData();

        const photoInput = document.getElementById(`photoInput_${id}`);

        values.forEach(function(item, index) {
            
            if(index == 3){

                if(photoInput.files.length > 0){
                    formData.append(attArray[index], photoInput.files[0]);
                }
            }else{
                formData.append(attArray[index], item);
            }
        });

        formData.append("id", id);
    
        //console.log(formData);

        fetch("admin_update_trainer.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(data => {
            alert(data);
            location.reload();
        })
        .catch(err => console.error(err));
    });
});


// deleting a trainer
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        let row = this.closest('tr');
        let id = row.getAttribute('data-id');
        console.log(id);

        if(confirm("Are you sure you want to delete this trainer?")) {
            fetch("admin_delete_trainer.php", {
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

// add new trainer
form.addEventListener("submit", function(e) {
    e.preventDefault();

        fetch("admin_add_trainer.php", {
            method: "POST",
            body: new FormData(form)
        })
        .then(res => res.text())
        .then(data => {
            alert(data);
            modal.classList.add("hidden");
            form.reset();
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

.page-header{
    position: relative !important;
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
</style>
<?php

include '../includes/footer.php';