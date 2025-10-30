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

    private function formatTanggalIndonesia($tanggal)
    {
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $tgl = date('d', strtotime($tanggal));
        $bln = $bulan[(int)date('m', strtotime($tanggal))];
        $thn = date('Y', strtotime($tanggal));

        return "$tgl $bln $thn";
    }


    private function renderCutiHtml($cuti)
    {
        // $formatter = new IntlDateFormatter(
        //     'id_ID',
        //     IntlDateFormatter::LONG,
        //     IntlDateFormatter::NONE,
        //     'Asia/Jakarta',
        //     IntlDateFormatter::GREGORIAN,
        //     'dd MMMM yyyy'
        // );
        // $tanggalMulaiFormatted = $formatter->format(new DateTime($cuti['tanggal_mulai']));
        // $tanggalPengajuan = $formatter->format(new DateTime($cuti['created_at']));

        $tanggalMulaiFormatted = $this->formatTanggalIndonesia($cuti['tanggal_mulai']);
        $tanggalPengajuan = $this->formatTanggalIndonesia($cuti['created_at']);


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
                    position: relative;
                    text-align: center;
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

                .ministry {
                    font-size: 12px;
                    font-weight: bold;
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
                    display: block; /* flex diganti block */
                    width: 100%;
                    text-align: center;
                }

                .regulation-box {
                    display: inline-block;
                    text-align: left;
                    padding-left: 400px;
                    max-width: 100%;
                }
                .regulation-box-address{
                    display: inline-block;
                    text-align: left;
                    padding-left: 520px;
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
                    margin-bottom: 10px;
                }

                table, th, td {
                    border: 1px solid black;
                }

                th, td {
                    text-align: left;
                }

                .section-title {
                    font-weight: bold;
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
            <!-- Header -->
            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border: none; margin-bottom: 0;">
                <tr>
                    <!-- Logo -->
                    <td style="width: 100px; vertical-align: top; text-align: right; padding-left: 10px; padding-bottom: 5px; border: none;">
                        <img src="<?= $base64 ?>" style="width: 80px; height: auto; display: block;" alt="Logo">
                    </td>

                    <!-- Header Text -->
                    <td style="text-align: center; color: #1F9CF0; font-size: 10pt; vertical-align: middle; padding: 0; border: none;">
                        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border: none;">
                            <tr>
                                <td style="font-size: 13px; line-height: 1.15; text-align: center; border: none;">
                                    KEMENTERIAN PERTANIAN
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 14px; line-height: 1.15; text-align: center; margin-bottom: 5px; border: none;">
                                    DIREKTORAT JENDERAL PETERNAKAN DAN KESEHATAN HEWAN
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 15px; line-height: 1.15; text-align: center; margin-bottom: 5px; border: none;">
                                    BALAI PEMBIBITAN TERNAK UNGGUL DAN HIJAUAN PAKAN TERNAK SEMBAWA
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 11px; line-height: 1.2; text-align: center; border: none;">
                                    JALAN RAYA PALEMBANG-PANGKALAN BALAI KM.20 SEMBAWA KOTAK POS 1116 PALEMBANG 30001<br>
                                    TELEPON (0711) 7853010 &nbsp;&nbsp; e-Mail: bptuhptsembawa@yahoo.com &nbsp;&nbsp; Web: www.bptuhpt-sembawa
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <!-- Garis bawah -->
            <hr style="border: 0; border-top: 1.5px solid black; margin: 0 0 2px 0;">
            <hr style="border: 0; border-top: 0.3px solid black; margin: 0 0 4px 0;">


            <!-- Regulation -->
            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin: 0; padding: 0; font-size: 10pt; border: none;">
                <tr>
                    <td style="text-align: left; vertical-align: top; border: none; padding-left: 400px;">
                        ANAK LAMPIRAN 1.b<br>
                        PERATURAN BADAN KEPEGAWAIAN NEGARA<br>
                        REPUBLIK INDONESIA<br>
                        NOMOR 24 TAHUN 2017<br>
                        TENTANG<br>
                        TATA CARA PEMBERIAN CUTI PEGAWAI NEGERI SIPIL
                    </td>
                </tr>
            </table>

            <!-- Address -->
            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin: 0; padding: 0; font-size: 10pt; border: none;">
                <tr>
                    <td style="text-align: left; vertical-align: top; border: none; padding-left: 520px;">
                        Sembawa, <?= htmlspecialchars($tanggalPengajuan); ?><br>
                        Kepada,<br>
                        Yth. Kepala BPTU-HPT Sembawa<br>
                        di Tempat
                    </td>
                </tr>
            </table>

            <!-- Form Title -->
            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin: 8px 0; border: none;">
                <tr>
                    <td style="text-align: center; font-weight: bold; font-size: 10pt; padding: 2px; border: none;">
                        FORMULIR PERMINTAAN DAN PEMBERIAN CUTI
                    </td>
                </tr>
            </table>

            <!-- Data Pegawai -->
            <table border="1" style="border-collapse: collapse; width: 100%; font-size: 8pt; margin-top: 0;">
                <tr>
                    <td colspan="4" style="font-size: 10pt; padding: 2px;"><strong>I. DATA PEGAWAI</strong></td>
                </tr>
                <tr>
                    <td style="padding: 2px;">Nama</td>
                    <td style="padding: 2px;"><?= htmlspecialchars($cuti['nama_pemohon']); ?></td>
                    <td style="padding: 2px;">NIP</td>
                    <td style="padding: 2px;"><?= htmlspecialchars($cuti['nip']); ?></td>
                </tr>
                <tr>
                    <td style="padding: 2px;">Jabatan</td>
                    <td style="padding: 2px;"><?= htmlspecialchars($cuti['jabatan']); ?></td>
                    <td style="padding: 2px;">Masa Kerja</td>
                    <td style="padding: 2px;"><?= htmlspecialchars($cuti['tahun_masa_kerja']); ?> tahun <?= htmlspecialchars($cuti['bulan_masa_kerja']); ?> bulan</td>
                </tr>
                <tr>
                    <td style="padding: 2px;">Unit Kerja</td>
                    <td colspan="3" style="padding: 2px;">BPTU-HPT Sembawa</td>
                </tr>
            </table>

            <table border="1" style="border-collapse: collapse; width: 100%; font-size: 8pt; margin-top: 5px;">
                <tr>
                    <td colspan="4" style="font-size: 10pt; padding: 2px;"><strong>II. JENIS CUTI YANG DIAMBIL**</strong></td>
                </tr>
                <tr>
                    <td style="padding: 2px;">1. Cuti Tahunan</td>
                    <td style="text-align: center; padding: 2px;"><?= $cuti['kode_cuti'] === 'CT' ? 'v' : '-'; ?></td>
                    <td style="padding: 2px;">2. Cuti Besar</td>
                    <td style="text-align: center; padding: 2px;"><?= $cuti['kode_cuti'] === 'CB' ? 'v' : '-'; ?></td>
                </tr>
                <tr>
                    <td style="padding: 2px;">3. Cuti Sakit</td>
                    <td style="text-align: center; padding: 2px;"><?= $cuti['kode_cuti'] === 'CS' ? 'v' : '-'; ?></td>
                    <td style="padding: 2px;">4. Cuti Melahirkan</td>
                    <td style="text-align: center; padding: 2px;"><?= $cuti['kode_cuti'] === 'CM' ? 'v' : '-'; ?></td>
                </tr>
                <tr>
                    <td style="padding: 2px;">5. Cuti Karena Alasan Penting</td>
                    <td style="text-align: center; padding: 2px;"><?= $cuti['kode_cuti'] === 'CKAP' ? 'v' : '-'; ?></td>
                    <td style="padding: 2px;">6. Cuti di Luar Tanggungan Negara</td>
                    <td style="text-align: center; padding: 2px;"><?= $cuti['kode_cuti'] === 'CLTN' ? 'v' : '-'; ?></td>
                </tr>
            </table>


            <table border="1" style="border-collapse: collapse; width: 100%; font-size: 8pt; margin-top: 5px;">
                <tr>
                    <td colspan="4" style="font-size: 10pt; padding: 2px;"><strong>III. ALASAN CUTI</strong></td>
                </tr>
                <tr>
                    <td colspan="4" style="padding: 2px;"><?= htmlspecialchars($cuti['alasan']); ?></td>
                </tr>
            </table>

            <table border="1" style="border-collapse: collapse; width: 100%; font-size: 8pt; margin-top: 5px;">
                <tr>
                    <td colspan="4" style="font-size: 10pt; padding: 2px;"><strong>IV. LAMANYA CUTI</strong></td>
                </tr>
                <tr>
                    <td style="padding: 2px;">Selama</td>
                    <td style="padding: 2px;"><?= htmlspecialchars($cuti['lama_cuti']); ?> hari</td>
                    <td style="padding: 2px;">Mulai Tanggal</td>
                    <td style="padding: 2px;"><?= $tanggalMulaiFormatted; ?></td>
                </tr>
            </table>


            <table border="1" style="border-collapse: collapse; width: 100%; font-size: 8pt; margin-top: 5px;">
                <tr>
                    <td colspan="5" style="font-size: 10pt; padding: 2px;"><strong>V. CATATAN CUTI***</strong></td>
                </tr>
                <tr>
                    <td colspan="3" style="padding: 2px;">1. CUTI TAHUNAN</td>
                    <td style="padding: 2px;">2. CUTI BESAR</td>
                    <td style="padding: 2px;"><?= htmlspecialchars($cuti['cuti_besar']); ?> hari</td>
                </tr>
                <tr>
                    <td style="padding: 2px;">Tahun</td>
                    <td style="text-align: center; padding: 2px;">Sisa</td>
                    <td style="text-align: center; padding: 2px;">Keterangan</td>
                    <td style="padding: 2px;">3. CUTI SAKIT</td>
                    <td style="padding: 2px;"><?= htmlspecialchars($cuti['cuti_sakit']); ?> hari</td>
                </tr>
                <tr>
                    <td style="padding: 2px;">N-<?= date('Y') - 2 ?></td>
                    <td style="text-align: center; padding: 2px;"><?= htmlspecialchars($cuti['sisa_cuti_n_2']); ?> hari</td>
                    <td style="text-align: center; padding: 2px;"></td>
                    <td style="padding: 2px;">4. CUTI MELAHIRKAN</td>
                    <td style="padding: 2px;"><?= htmlspecialchars($cuti['cuti_melahirkan']); ?> hari</td>
                </tr>
                <tr>
                    <td style="padding: 2px;">N-<?= date('Y') - 1 ?></td>
                    <td style="text-align: center; padding: 2px;"><?= htmlspecialchars($cuti['sisa_cuti_n_1']); ?> hari</td>
                    <td style="text-align: center; padding: 2px;"></td>
                    <td style="padding: 2px;">5. CUTI KARENA ALASAN PENTING</td>
                    <td style="padding: 2px;"><?= htmlspecialchars($cuti['cuti_alasan_penting']); ?> hari</td>
                </tr>
                <tr>
                    <td style="padding: 2px;">N-<?= date('Y') ?></td>
                    <td style="text-align: center; padding: 2px;"><?= htmlspecialchars($cuti['sisa_cuti_n_0']); ?> hari</td>
                    <td style="text-align: center; padding: 2px;"></td>
                    <td style="padding: 2px;">6. CUTI DI LUAR TANGGUNGAN NEGARA</td>
                    <td style="padding: 2px;"><?= htmlspecialchars($cuti['cuti_diluar_tanggungan_negara']); ?> hari</td>
                </tr>
            </table>


            <table style="width: 100%; border-collapse: collapse;" border="1">
                <tr>
                    <td colspan="4" style="font-size: 10pt; padding: 2px;"><strong>VI. ALAMAT SELAMA MENJALANKAN CUTI</strong></td>
                </tr>
                <tr style="height: 100px;">
                    <!-- Alamat panjang -->
                    <td rowspan="2" colspan="2" style="text-align: left; vertical-align: top; width: 50%; font-size: 8pt; padding: 2px;">
                        <?= htmlspecialchars($cuti['alamat']); ?>
                    </td>

                    <!-- TELP -->
                    <td style="width: 10%; vertical-align: top; font-size: 8pt; padding: 2px;">TELP</td>
                    <td style="width: 40%; vertical-align: top; font-size: 8pt; padding: 2px;"><?= htmlspecialchars($cuti['phone_number']); ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center; vertical-align: top; font-size: 8pt; padding: 2px;">
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
                    <td style="width: 75%;" colspan="3">&nbsp;</td>
                    <td style="width: 25%; text-align: center; vertical-align: bottom; font-size: 8pt; padding: 2px; height: 100px;">
                        <?php 
                        $namaTtd = ($cuti['kode_jabatan'] !== 'KTA') ? $cuti['nama_ketua'] : $cuti['nama_kepala'];
                        $nipTtd  = ($cuti['kode_jabatan'] !== 'KTA') ? $cuti['nip_ketua'] : $cuti['nip_kepala'];
                        ?>
                        ( <?= htmlspecialchars($namaTtd); ?> )<br>
                        <?= htmlspecialchars($nipTtd); ?>
                    </td>
                </tr>
            </table>


            <table style="width: 100%; border-collapse: collapse;" border="1">
                <!-- Header Keputusan -->
                <tr>
                    <td colspan="4" style="font-size: 10pt; padding: 2px;">
                        <strong>VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI**</strong>
                    </td>
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

                <tr style="height: 100px;">
                    <td style="width: 75%;" colspan="3">&nbsp;</td>
                    <td style="width: 25%; text-align: center; vertical-align: bottom; font-size: 8pt; padding: 2px; height: 100px;">
                        ( <?= htmlspecialchars($cuti['nama_kepala']); ?> )<br>
                        <?= htmlspecialchars($cuti['nip_kepala']); ?>
                    </td>
                </tr>

            </table>

            
            <div style="font-size: 10pt; padding: 0 2px 0 2px; line-height: 1.1;">
                Catatan :
                <table style="border-collapse: collapse; font-size: 7pt; margin-bottom: 5px; border: none; width: 100%;">
                    <tr>
                        <td style="width: 50px; vertical-align: top; border: none; padding: 0;">*</td>
                        <td style="border: none; padding: 0;">Coret yang tidak perlu</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; border: none; padding: 0;">**</td>
                        <td style="border: none; padding: 0;">Pilih salah satu dengan memberi tanda centang (V)</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; border: none; padding: 0;">***</td>
                        <td style="border: none; padding: 0;">Diisi oleh pejabat yang menangani bidang kepegawaian sebelum PNS mengajukan cuti</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; border: none; padding: 0;">****</td>
                        <td style="border: none; padding: 0;">Diberi tanda centang dan alasannya</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; border: none; padding: 0;">N</td>
                        <td style="border: none; padding: 0;">Cuti Tahun berjalan</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; border: none; padding: 0;">N-1</td>
                        <td style="border: none; padding: 0;">Sisa cuti 1 tahun sebelumnya</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; border: none; padding: 0;">N-2</td>
                        <td style="border: none; padding: 0;">Sisa cuti 2 tahun sebelumnya</td>
                    </tr>
                </table>

                <!-- Garis bawah -->
                <hr style="border: 0; border-top: 0.3px solid black; margin: 0 0 2px 0;">
                <hr style="border: 0; border-top: 1.5px solid black; margin: 0 0 4px 0;">
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
        // Atau: $dompdf->setPaper('legal', 'portrait');

        // Render PDF
        $dompdf->render();

        // 🔽 Kirim output ke browser
        $pdfOutput = $dompdf->output();

        header("Content-Type: application/pdf");
        header("Content-Disposition: inline; filename=Formulir-Cuti.pdf");
        echo $pdfOutput;
        exit;
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