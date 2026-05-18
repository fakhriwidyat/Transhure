<?php
session_start();
include 'koneksi.php';

// Ubah pengecekan menggunakan session username
if (!isset($_SESSION['username'])) { 
    header("Location: index.html"); 
    exit; 
}

$query = "SELECT id_soal, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d FROM soal_quiz ORDER BY RAND() LIMIT 10";
$result = mysqli_query($conn, $query);

$daftar_soal = [];
while ($row = mysqli_fetch_assoc($result)) {
    $daftar_soal[] = $row;
}
$json_soal = json_encode($daftar_soal);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Mengerjakan Quiz</title>
    <link rel="stylesheet" href="quizphp.css?v=1.3">
</head>
<body>

<div class="quiz-box">
    <span class="nomor-soal" id="nomorDisplay">Soal 1 / 10</span>
    <h3 id="pertanyaanDisplay">Teks pertanyaan akan muncul di sini...</h3>
    
    <div id="opsiContainer"></div>

    <button class="nav-btn" id="nextBtn" onclick="nextSoal()">Selanjutnya</button>

    <form id="formKirim" action="proses_quiz.php" method="POST">
        <input type="hidden" name="jawaban_user" id="jawabanUserJSON">
    </form>
</div>

<script>
    const soal = <?php echo $json_soal; ?>;
    let indexSekarang = 0;
    let jawabanTersimpan = {};

    function tampilkanSoal() {
        const data = soal[indexSekarang];
        document.getElementById('nomorDisplay').innerText = `Soal ${indexSekarang + 1} / ${soal.length}`;
        document.getElementById('pertanyaanDisplay').innerText = data.pertanyaan;
        
        const container = document.getElementById('opsiContainer');
        container.innerHTML = ''; 

        const opsiList = [
            { huruf: 'A', teks: data.opsi_a },
            { huruf: 'B', teks: data.opsi_b },
            { huruf: 'C', teks: data.opsi_c },
            { huruf: 'D', teks: data.opsi_d }
        ];

        opsiList.forEach(opsi => {
            const btn = document.createElement('button');
            btn.className = 'opsi-btn';
            btn.innerText = opsi.teks;
            
            if(jawabanTersimpan[data.id_soal] === opsi.huruf) {
                btn.classList.add('selected');
            }

            btn.onclick = function() {
                document.querySelectorAll('.opsi-btn').forEach(b => b.classList.remove('selected'));
                btn.classList.add('selected');
                jawabanTersimpan[data.id_soal] = opsi.huruf;
            };
            container.appendChild(btn);
        });

        if(indexSekarang === soal.length - 1) {
            document.getElementById('nextBtn').innerText = "Selesai & Kirim";
        } else {
            document.getElementById('nextBtn').innerText = "Selanjutnya";
        }
    }

    function nextSoal() {
        const data = soal[indexSekarang];
        if(!jawabanTersimpan[data.id_soal]) {
            alert('Silakan pilih jawaban terlebih dahulu!');
            return;
        }

        if(indexSekarang < soal.length - 1) {
            indexSekarang++;
            tampilkanSoal();
        } else {
            document.getElementById('jawabanUserJSON').value = JSON.stringify(jawabanTersimpan);
            document.getElementById('formKirim').submit();
        }
    }

    tampilkanSoal();
</script>

</body>
</html>