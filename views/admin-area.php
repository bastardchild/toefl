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
    </table>   

<!-- Include DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
        $(document).ready(function() {
            $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/api/users',
                columns: [
                    { data: 0 }, // No
                    { 
                        data: 1, // Nama
                        render: function(data, type, row) {
                            return data.toUpperCase();
                        }
                    },
                    { data: 2 }, // Username
                    { 
                        data: 3, // Status
                        render: function(data, type, row) {
                            switch(data) {
                                case 1: return 'Ongoing';
                                case 2: return 'Complete';
                                default: return 'N/A';
                            }
                        }
                    },
                    { 
                        data: 4, // Foto
                        render: function(data, type, row) {
                            return data ? `<img src="/uploads/${data}" alt="Foto" style="width: 100px;">` : 'No photo';
                        }
                    },
                    { data: 5 }, // Kode Ujian
                    { 
                        data: 0, // Reset (using ID from first column)
                        render: function(data, type, row) {
                            return `<a href="/reset-exam/${data}" class="btn btn-dark btn-sm">Reset</a>`;
                        }
                    }
                ]
            });
        });
    </script>