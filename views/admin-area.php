<h2>Admin Dashboard</h2>            
<p>Manage users, view reports, and configure settings here.</p>

<?php if (isset($message)): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>
<hr>
<h3 class="mt-3">Tambah Peserta</h3>
<b>Upload CSV File</b><br>
<form action="/upload-csv" method="post" enctype="multipart/form-data">
    <label for="csv">Select CSV file:</label>
    <input type="file" id="csv" name="csv" accept=".csv" required>
    <button type="submit" class="btn btn-success">Upload</button>
</form>

<hr>
<h3 class="mt-3">Download Peserta</h3>
<b>Download CSV File</b><br>
<form action="/download-csv" method="post">
    <label for="exam_code">Select Exam Code:</label>
    <select id="exam_code" name="exam_code" style="margin-right:35px;" required>
        <option value="">-- Select Exam Code --</option>
        <?php foreach ($examCodes as $examCode): ?>
            <option value="<?= htmlspecialchars($examCode->exam_code) ?>">
                <?= htmlspecialchars($examCode->exam_code) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit" class="btn btn-success">Download</button>
</form>

<!-- DataTable HTML -->
<hr>
<h3 class="mt-3">Data Peserta</h3>
<table id="userTable" class="display">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Username</th>
            <th>Status</th>
            <th>Foto</th>
            <th>Kode Ujian</th>
            <th>Reset</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $index => $user): ?>
            <tr>
                <td><?= htmlspecialchars($index + 1) ?></td>
                <td><?= strtoupper(htmlspecialchars($user->name)) ?> <?= strtoupper(htmlspecialchars($user->middle_name)) ?> <?= strtoupper(htmlspecialchars($user->last_name)) ?></td>
                <td><?= htmlspecialchars($user->username) ?></td>
                <td>
                    <?php
                        // Map status_id to status description
                        switch ($user->exam_status_id) {
                            case 1:
                                echo 'Ongoing';
                                break;
                            case 2:
                                echo 'Complete';
                                break;
                            default:
                                echo 'N/A';
                        }
                    ?>
                </td>
                <td>
                    <?php if ($user->cam_image): ?>
                        <img src="/uploads/<?= htmlspecialchars($user->cam_image) ?>" alt="Foto" style="width: 100px;">
                    <?php else: ?>
                        No photo
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($user->exam_code ?? 'N/A') ?></td>
                <td>
                    <a href="/reset-exam/<?= htmlspecialchars($user->id) ?>" class="btn btn-dark btn-sm">Reset</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Include DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#userTable').DataTable();
    });
</script>