<?php
require_once __DIR__ . '/../models/CutiModel.php';
require_once __DIR__ . '/../models/PerizinanModel.php';
require_once __DIR__ . '/../models/TipeCutiModel.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/JabatanModel.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class CutiController
{
    private $cutiModel;
    private $perizinanModel;
    private $tipeCutiModel;
    private $modelUser;
    private $modelJabatan;

    public function __construct($db)
    {
        $this->cutiModel = new CutiModel($db);
        $this->perizinanModel = new PerizinanModel($db);
        $this->tipeCutiModel = new TipeCutiModel($db);
        $this->modelUser = new User($db);
        $this->modelJabatan = new JabatanModel($db);
    }

    public function formAjukanCuti()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login");
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $kodeJabatan = $_SESSION['jabatan'];
        $kepala_id = $_SESSION['kepala_balai'];
        $ketua_id = $_SESSION['atasan'];

        $jabatan_user = $this->modelJabatan->getNamaJabatanByKode($kodeJabatan);
        $kepala_balai = $this->modelUser->getKepalaBalaiById($kepala_id);
        $ketua_tim = $this->modelUser->getKetuaTimById($ketua_id);
        
        if (!$kepala_balai) {
            die("Data kepala balai tidak ditemukan. Hubungi admin.");
        }

        if (!$ketua_tim) {
            die("Data ketua tim tidak ditemukan. Hubungi admin.");
        }

        $tipeCutiList = $this->tipeCutiModel->getTipeCutiList();
        if (!$tipeCutiList) {
            die("Tipe cuti tidak tersedia. Hubungi admin.");
        }

        $sisaCuti = $this->cutiModel->getLeaveRemaining($user_id);

        require_once __DIR__ . '/../views/cuti/create_cuti.php';
    }

    public function ajukanCuti()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $user_id = $_SESSION['user_id'];
            $jenis_cuti_id = $_POST['tipeCuti'];
            $atasan_id = $_POST['atasan'];
            $ketua_id = $_POST['ketua'];
            $from_date = $_POST['dari_tanggal'];
            $to_date = $_POST['sampai_tanggal'];
            $alamat = $_POST['alamat'];
            $deskripsi = $_POST['deskripsi'];
            $jabatan = $_SESSION['jabatan'];

            $result = $this->cutiModel->insertCuti(
                $user_id, $jenis_cuti_id, $atasan_id, 
                $ketua_id, $from_date, $to_date, $alamat, 
                $deskripsi, $jabatan);

            if ($result === true) {
                $_SESSION['success'] = "Pengajuan cuti berhasil dikirim.";
            } else {
                $_SESSION['error'] = "Terjadi kesalahan: " . $result;
            }
            session_write_close();

            header("Location: ajukan_cuti");
            exit();
        }
    }

    public function leaveHistory()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login");
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $leaveHistory = $this->cutiModel->getLeaveHistory($user_id);

        require_once __DIR__ . '/../views/cuti/leave_history.php';
    }

    private function cmToPt($cm)
    {
        return $cm * 28.35;
    }

    public function cetakCuti()
    {
        if (!isset($_POST['id'])) {
            echo "ID cuti tidak ditemukan.";
            return;
        }

        $id = $_POST['id'];

        $cuti = $this->cutiModel->getCutiById($id);

        if (!$cuti) {
            echo "Data cuti tidak ditemukan.";
            exit;
        }

        $html = $this->exportCutiPdf($cuti);

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true); 
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);

        // Custom ukuran kertas dalam cm
        $width = $this->cmToPt(15);   
        $height = $this->cmToPt(25);  
        $dompdf->setPaper([0, 0, $width, $height]);

        $dompdf->render();
        $dompdf->stream("Formulir_Cuti_{$cuti['id']}.pdf", ["Attachment" => true]);
    }

    private function renderCutiHtml($cuti)
    {
        $formatter = new IntlDateFormatter(
            'id_ID',
            IntlDateFormatter::LONG,
            IntlDateFormatter::NONE,
            'Asia/Jakarta',
            IntlDateFormatter::GREGORIAN,
            'dd MMMM yyyy'
        );
        $tanggalMulaiFormatted = $formatter->format(new DateTime($cuti['tanggal_mulai']));
        $tanggalPengajuan = $formatter->format(new DateTime($cuti['created_at']));

        // $logoPath = $_SERVER['DOCUMENT_ROOT'] . "/app-perijinan/assets/image/logo.png";
        // $logoPath = 'file://' . realpath($logoPath);

        $logoPath = $_SERVER['DOCUMENT_ROOT'] . "/app-perijinan/assets/image/logo.png";
        $type = pathinfo($logoPath, PATHINFO_EXTENSION);
        $data = file_get_contents($logoPath);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);


        ob_start();
    ?>
        <html>

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Formulir Permintaan dan Pemberian Cuti</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 15px;
                    font-size: 14px;
                }

                .header-container {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    position: relative;
                    padding: 10px 20px;
                }

                .logo {
                    position: absolute;
                    left: 20px;
                    width: 70px;
                    height: auto;
                }

                .header-text {
                    text-align: center;
                    color: #1F9CF0;
                    font-size: 10pt;
                    line-height: 1.4;
                }

                /* .header-text .ministry {
                    font-weight: bold;
                } */

                .header {
                    text-align: center;
                    margin-bottom: 2px;
                    padding-bottom: 5px;
                }

                .ministry {
                    font-size: 12px;
                }

                .directorate {
                    font-size: 13px;
                }

                .office {
                    font-size: 14px;
                    margin-top: 5px;
                }

                .contact {
                    font-size: 10px;
                    margin-top: 5px;
                }

                .divider {
                    border-top: 1px solid #000;
                    margin: 10px 0;
                }

                .center-container {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    width: 100%;
                }
                
                .regulation-box {
                    margin-top: 1px;
                    text-align: left;
                    /* border: 1px solid #000;  */
                    padding: 0px;
                    padding-left: 400px;
                    width: fit-content;
                    max-width: 100%;
                }

                .note-box {
                    margin-top: 1px;
                    text-align: left;
                    /* border: 1px solid #000;  */
                    /* padding-left: 310px; */
                    width: fit-content;
                    max-width: 100%;
                }

                .regulation-box-address {
                    text-align: left;
                    padding: 0px;
                    padding-left: 520px;
                    width: fit-content;
                    max-width: 100%;
                }

                .form-title {
                    font-weight: bold;
                    text-align: center;
                    margin-bottom: 20px;
                    font-size: 16px;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 15px;
                }

                table,
                th,
                td {
                    border: 1px solid black;
                }

                th,
                td {
                    padding: 8px;
                    text-align: left;
                }

                .section-title {
                    font-weight: bold;
                    /* background-color: #f2f2f2; */
                }

                .empty-row {
                    height: 30px;
                }

                .footer {
                    margin-top: 30px;
                    text-align: right;
                }
            </style>
        </head>

        <body> 
            <div class="header-container">
                <img src="<?= $base64 ?>" class="logo" alt="Logo"> 
                <div class="header-text">
                    <div class="ministry">KEMENTERIAN PERTANIAN</div>
                    <div class="directorate">DIREKTORAT JENDERAL PETERNAKAN DAN KESEHATAN HEWAN</div>
                    <div class="office">BALAI PEMBIBITAN TERNAK UNGGUL DAN HIJAUAN PAKAN TERNAK SEMBAWA</div>
                    <div class="contact">
                        JALAN RAYA PALEMBANG-PANGKALAN BALAI KM.20 SEMBAWA KOTAK POS 1116 PALEMBANG 30001<br>
                        TELEPON (0711) 7853010 &nbsp;&nbsp; e-Mail: bptuhptsembawa@yahoo.com &nbsp;&nbsp; Web: www.bptuhpt-sembawa
                    </div>
                </div>
            </div>

            <div style="border-bottom: 2px solid black; margin-bottom: 2px;"></div>
            <div style="border-bottom: 1px solid black;"></div>

            <div class="center-container" style="font-size: 10pt; padding: 0px; padding-left: 2px;">
                <div class="regulation-box">
                    ANAK LAMPIRAN 1.b<br>
                    PERATURAN BADAN KEPEGAWAIAN NEGARA<br>
                    REPUBLIK INDONESIA<br>
                    NOMOR 24 TAHUN 2017<br>
                    TENTANG<br>
                    TATA CARA PEMBERIAN CUTI PEGAWAI NEGERI SIPIL
                </div>
                
                <div class="regulation-box-address" style="margin-top: 5px;">
                    Sembawa, <?= htmlspecialchars($tanggalPengajuan); ?><br>
                    Kepada,<br>
                    Yth. Kepala BPTU-HPT Sembawa<br>
                    di Tempat
                </div>
            </div>

            <div class="form-title" style="margin-top: 10px; margin-bottom: 10px; font-size: 10pt;">FORMULIR PERMINTAAN DAN PEMBERIAN CUTI</div>

            <table border="1" style="border-collapse: collapse; width: 100%;">
                <tr class="section-title">
                    <td colspan="4" style="font-size: 10pt; padding: 0px; padding-left: 2px;"><strong>I. DATA PEGAWAI</strong></td>
                </tr>
                <tr>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;">Nama</td>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;"><?= htmlspecialchars($cuti['nama_pemohon']); ?></td>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;">NIP</td>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;"><?= htmlspecialchars($cuti['nip']); ?></td>
                </tr>
                <tr>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;">Jabatan</td>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;"><?= htmlspecialchars($cuti['jabatan']); ?></td>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;">Masa Kerja</td>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;"><?= htmlspecialchars($cuti['tahun_masa_kerja']); ?> tahun <?= htmlspecialchars($cuti['bulan_masa_kerja']); ?> bulan</td>
                </tr>
                <tr>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;">Unit Kerja</td>
                    <td colspan="3" style="font-size: 8pt; padding: 0px; padding-left: 2px;">BPTU-HPT Sembawa</td>
                </tr>
            </table>

            <table>
                <tr class="section-title">
                    <td colspan="4" style="font-size: 10pt; padding: 0px; padding-left: 2px;">II. JENIS CUTI YANG DIAMBIL**</td>
                </tr>
                <tr>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;">1. Cuti Tahunan</td>
                    <td style="text-align: center; font-size: 10pt; padding: 2px;">
                        <?= $cuti['kode_cuti'] === 'CT' ? 'v' : '-'; ?>
                    </td>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;">2. Cuti Besar</td>
                    <td style="text-align: center; font-size: 10pt; padding: 2px;">
                        <?= $cuti['kode_cuti'] === 'CB' ? 'v' : '-'; ?>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;">3. Cuti Sakit</td>
                    <td style="text-align: center; font-size: 10pt; padding: 2px;">
                        <?= $cuti['kode_cuti'] === 'CS' ? 'v' : '-'; ?>
                    </td>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;">4. Cuti Melahirkan</td>
                    <td style="text-align: center; font-size: 10pt; padding: 2px;">
                        <?= $cuti['kode_cuti'] === 'CM' ? 'v' : '-'; ?>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;">5. Cuti Karena Alasan Penting</td>
                    <td style="text-align: center; font-size: 10pt; padding: 2px;">
                        <?= $cuti['kode_cuti'] === 'CKAP' ? 'v' : '-'; ?>
                    </td>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;">6. Cuti di Luar Tanggungan Negara</td>
                    <td style="text-align: center; font-size: 10pt; padding: 2px;">
                        <?= $cuti['kode_cuti'] === 'CLTN' ? 'v' : '-'; ?>
                    </td>
                </tr>
            </table>

            <table>
                <tr class="section-title">
                    <td colspan="4" style="font-size: 10pt; padding: 0px; padding-left: 2px;">III. ALASAN CUTI</td>
                </tr>
                <tr>
                    <td colspan="4" style="font-size: 8pt; padding: 0px; padding-left: 2px;"><?= htmlspecialchars($cuti['alasan']); ?></td>
                </tr>
            </table>

            <table>
                <tr class="section-title">
                    <td colspan="4" style="font-size: 10pt; padding: 0px; padding-left: 2px;">IV. LAMANYA CUTI</td>
                </tr>
                <tr>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;">Selama</td>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;"><?= htmlspecialchars($cuti['lama_cuti']); ?> hari</td>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;">Mulai Tanggal</td>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;"><?= $tanggalMulaiFormatted; ?></td>
                </tr>
            </table>

            <table>
                <tr class="section-title">
                    <td colspan="5" style="font-size: 10pt; padding: 0px; padding-left: 2px;">V. CATATAN CUTI***</td>
                </tr>
                <tr>
                    <td colspan="3" style="font-size: 8pt; padding: 0px; padding-left: 2px;">1. CUTI TAHUNAN</td>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;">2. CUTI BESAR</td>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;"><?= htmlspecialchars($cuti['cuti_besar']); ?> hari</td>
                </tr>
                <tr>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;">Tahun</td>
                    <td style="text-align: center; font-size: 8pt; padding: 0px; padding-left: 2px;">Sisa</td>
                    <td style="text-align: center; font-size: 8pt; padding: 0px; padding-left: 2px;">Keterangan</td>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;">3. CUTI SAKIT</td>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;"><?= htmlspecialchars($cuti['cuti_sakit']); ?> hari</td>
                </tr>
                <tr>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;">N-<?= date('Y') - 2 ?></td>
                    <td style="text-align: center; font-size: 8pt; padding: 0px; padding-left: 2px;"><?= htmlspecialchars($cuti['sisa_cuti_n_2']); ?> hari</td>
                    <td style="text-align: center; font-size: 8pt; padding: 0px; padding-left: 2px;"></td>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;">4. CUTI MELAHIRKAN</td>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;"><?= htmlspecialchars($cuti['cuti_melahirkan']); ?> hari</td>
                </tr>
                <tr>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;">N-<?= date('Y') - 1 ?></td>
                    <td style="text-align: center; font-size: 8pt; padding: 0px; padding-left: 2px;"><?= htmlspecialchars($cuti['sisa_cuti_n_1']); ?> hari</td>
                    <td style="text-align: center; font-size: 8pt; padding: 0px; padding-left: 2px;"></td>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;">5. CUTI KARENA ALASAN PENTING</td>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;"><?= htmlspecialchars($cuti['cuti_alasan_penting']); ?> hari</td>
                </tr>
                <tr>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;">N-<?= date('Y') ?></td>
                    <td style="text-align: center; font-size: 8pt; padding: 0px; padding-left: 2px;"><?= htmlspecialchars($cuti['sisa_cuti_n_0']); ?> hari</td>
                    <td style="text-align: center; font-size: 8pt; padding: 0px; padding-left: 2px;"></td>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;">6. CUTI DILIUAR TANGGUNGAN NEGARA</td>
                    <td style="font-size: 8pt; padding: 0px; padding-left: 2px;"><?= htmlspecialchars($cuti['cuti_diluar_tanggungan_negara']); ?> hari</td>
                </tr>
            </table>

            <table style="width: 100%; border-collapse: collapse;" border="1">
                <tr>
                    <td colspan="4" style="font-size: 10pt; padding: 0px; padding-left: 2px;"><strong>VI. ALAMAT SELAMA MENJALANKAN CUTI</strong></td>
                </tr>
                <tr style="height: 100px;">
                    <!-- Alamat panjang -->
                    <td rowspan="2" colspan="2" style="text-align: center; vertical-align: middle; width: 50%; font-size: 8pt; padding: 0px; padding-left: 2px;">
                        <?= htmlspecialchars($cuti['alamat']); ?>
                    </td>

                    <!-- TELP -->
                    <td style="width: 10%; vertical-align: top; font-size: 8pt; padding: 0px; padding-left: 2px;">TELP</td>
                    <td style="width: 40%; vertical-align: top; font-size: 8pt; padding: 0px; padding-left: 2px;"><?= htmlspecialchars($cuti['phone_number']); ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center; vertical-align: top; font-size: 8pt; padding: 0px; padding-left: 2px;">
                        Hormat saya,<br><br><br><br><br>
                        NIP. <?= htmlspecialchars($cuti['nip']); ?>
                    </td>
                </tr>
            </table>

            <table style="width: 100%; border-collapse: collapse;" border="1">
                <!-- Header Pertimbangan -->
                <tr>
                    <td colspan="4" style="font-size: 10pt; padding: 2px;"><strong>VII. PERTIMBANGAN ATASAN LANGSUNG**</strong></td>
                </tr>

                <!-- Opsi Disetujui dst -->
                <tr>
                    <td style="text-align: center; font-size: 8pt; padding: 2px;">DISETUJUI</td>
                    <td style="text-align: center; font-size: 8pt; padding: 2px;">PERUBAHAN****</td>
                    <td style="text-align: center; font-size: 8pt; padding: 2px;">DITANGGUHKAN****</td>
                    <td style="text-align: center; font-size: 8pt; padding: 2px;">TIDAK DISETUJUI****</td>
                </tr>

                <!-- Tanda centang -->
                <tr>
                    <td style="text-align: center; font-size: 10pt; padding: 2px;">
                        <?= $cuti['status'] === 'Disetujui' ? 'v' : '-'; ?>
                    </td>
                    <td style="text-align: center; font-size: 10pt; padding: 2px;">-</td>
                    <td style="text-align: center; font-size: 10pt; padding: 2px;">-</td>
                    <td style="text-align: center; font-size: 10pt; padding: 2px;">
                        <?= $cuti['status'] === 'Ditolak' ? 'v' : '-'; ?>
                    </td>
                </tr>

                <!-- Area tanda tangan -->
                <tr style="height: 100px;">
                    <!-- Kolom kiri kosong, tanpa border kiri dan bawah -->
                    <td rowspan="2" colspan="3" 
                        style="vertical-align: top; font-size: 8pt; padding: 0px 0px 0px 2px; border-left: none; border-bottom: none;">
                    </td>

                    <!-- Kolom tanda tangan -->
                    <td style="text-align: center; vertical-align: top; font-size: 8pt; padding: 0px;">
                        <div style="padding-top: 60px;">
                            ( Dr. Muhammad Imron, S.Pt .M.Si )<br>
                            NIP. 197311301998031006
                        </div>
                    </td>
                </tr>
            </table>

            <table style="width: 100%; border-collapse: collapse;" border="1">
                <!-- Header Pertimbangan -->
                <tr>
                    <td colspan="4" style="font-size: 10pt; padding: 2px;"><strong>VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI**</strong></td>
                </tr>

                <!-- Opsi Disetujui dst -->
                <tr>
                    <td style="text-align: center; font-size: 8pt; padding: 2px;">DISETUJUI</td>
                    <td style="text-align: center; font-size: 8pt; padding: 2px;">PERUBAHAN****</td>
                    <td style="text-align: center; font-size: 8pt; padding: 2px;">DITANGGUHKAN****</td>
                    <td style="text-align: center; font-size: 8pt; padding: 2px;">TIDAK DISETUJUI****</td>
                </tr>

                <!-- Tanda centang -->
                <tr>
                    <td style="text-align: center; font-size: 10pt; padding: 2px;">
                        <?= $cuti['status'] === 'Disetujui' ? 'v' : '-'; ?>
                    </td>
                    <td style="text-align: center; font-size: 10pt; padding: 2px;">-</td>
                    <td style="text-align: center; font-size: 10pt; padding: 2px;">-</td>
                    <td style="text-align: center; font-size: 10pt; padding: 2px;">
                        <?= $cuti['status'] === 'Ditolak' ? 'v' : '-'; ?>
                    </td>
                </tr>

                <!-- Area tanda tangan -->
                <tr style="height: 100px;">
                    <!-- Kolom kiri kosong, tanpa border kiri dan bawah -->
                    <td rowspan="2" colspan="3" 
                        style="vertical-align: top; font-size: 8pt; padding: 0px 0px 0px 2px; border-left: none; border-bottom: none;">
                    </td>

                    <!-- Kolom tanda tangan -->
                    <td style="text-align: center; vertical-align: top; font-size: 8pt; padding: 0px;">
                        <div style="padding-top: 60px;">
                            ( Dr. Muhammad Imron, S.Pt .M.Si )<br>
                            NIP. 197311301998031006
                        </div>
                    </td>
                </tr>
            </table>
            
            <div style="font-size: 10pt; padding-left: 2px;">
                Catatan :
                <table style="border-collapse: collapse; font-size: 7pt; margin-top: 2px; border: none;">
                    <tr>
                        <td style="width: 60px; vertical-align: top; border: none; padding: 0px; padding-left: 2px;">*</td>
                        <td style="border: none; padding: 0px; padding-left: 2px;">Coret yang tidak perlu</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; border: none; padding: 0px; padding-left: 2px;">**</td>
                        <td style="border: none; padding: 0px; padding-left: 2px;">Pilih salah satu dengan memberi tanda centang (V)</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; border: none; padding: 0px; padding-left: 2px;">***</td>
                        <td style="border: none; padding: 0px; padding-left: 2px;">Diisi oleh pejabat yang menangani bidang kepegawaian sebelum PNS mengajukan cuti</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; border: none; padding: 0px; padding-left: 2px;">****</td>
                        <td style="border: none; padding: 0px; padding-left: 2px;">Diberi tanda centang dan alasannya</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; border: none; padding: 0px; padding-left: 2px;">N</td>
                        <td style="border: none; padding: 0px; padding-left: 2px;">Cuti Tahun berjalan</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; border: none; padding: 0px; padding-left: 2px;">N-1</td>
                        <td style="border: none; padding: 0px; padding-left: 2px;">Sisa cuti 1 tahun sebelumnya</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; border: none; padding: 0px; padding-left: 2px;">N-2</td>
                        <td style="border: none; padding: 0px; padding-left: 2px;">Sisa cuti 2 tahun sebelumnya</td>
                    </tr>
                </table>
            </div>
                
            <div style="border-bottom: 1px solid black; margin-top: 2px; margin-bottom: 2px;"></div>
            <div style="border-bottom: 2px solid black;"></div>
            </div>
            
        </body>

        </html>
    <?php
        return ob_get_clean();
    }

    private function exportCutiPdf($cuti)
    {
        // Ambil HTML dari fungsi render
        $html = $this->renderCutiHtml($cuti);

        // Inisialisasi Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);

        // Load HTML ke Dompdf
        $dompdf->loadHtml($html);

        // Contoh: 10 x 13 inch (custom size lebih lebar dari letter)
        $customPaper = [0, 0, 720, 1070]; // 72 pt x inci
        $dompdf->setPaper($customPaper, 'portrait');
        // Set ukuran kertas ke 'letter'
        // $dompdf->setPaper('legal', 'portrait');

        // Render dan keluarkan PDF
        $dompdf->render();
        $dompdf->stream('Formulir-Cuti.pdf', ['Attachment' => false]); // tampil di browser
    }

    public function hapusCuti()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error'] = "ID tidak valid.";
            header("Location: leave_history");
            exit;
        }

        $id = $_GET['id'];
        $status = $this->cutiModel->getStatusCuti($id);

        if (!$status) {
            $_SESSION['error'] = "Data tidak ditemukan!";
            header("Location: leave_history");
            exit;
        }

        if ($status['status'] === 'Disetujui' || $status['status'] === 'Ditolak') {
            $_SESSION['error'] = "Data tidak bisa dihapus karena status sudah " . $status['status'];
            header("Location: leave_history");
            exit;
        }

        if ($this->cutiModel->delete($id)) {
            $_SESSION['success'] = "Data berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus data.";
        }
        
        header("Location: leave_history");
        exit;
    }
}
?>