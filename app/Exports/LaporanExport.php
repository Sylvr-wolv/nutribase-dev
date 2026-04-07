<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class LaporanExport implements WithMultipleSheets
{
    public function __construct(
        private Collection $distribusiData,
        private Collection $penerimaData,
        private string     $periodeLabel,
    ) {}

    public function sheets(): array
    {
        return [
            new DistribusiSheet($this->distribusiData, $this->periodeLabel),
            new PenerimaSheet($this->penerimaData, $this->periodeLabel),
        ];
    }
}

// ── Sheet: Distribusi ──────────────────────────────────────────────────────────

class DistribusiSheet implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(
        private Collection $data,
        private string     $periodeLabel,
    ) {}

    public function title(): string { return 'Distribusi'; }

    public function headings(): array
    {
        return [
            ['LAPORAN DISTRIBUSI MAKANAN – NUTRIBASE'],
            ['Periode: ' . $this->periodeLabel],
            [''],
            ['No', 'Tanggal', 'Waktu', 'Penerima', 'Menu', 'Kader', 'Jadwal', 'Status', 'Keterangan'],
        ];
    }

    public function collection(): Collection
    {
        return $this->data->values()->map(fn ($d, $i) => [
            $i + 1,
            $d->waktu_distribusi->format('d/m/Y'),
            $d->waktu_distribusi->format('H:i'),
            $d->penerima->nama ?? '-',
            $d->menu->nama ?? '-',
            $d->kader->name ?? '-',
            $d->jadwal?->tanggal->format('d/m/Y') ?? '-',
            strtoupper($d->status),
            $d->keterangan ?? '-',
        ]);
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A3:I3');

        return [
            1 => [
                'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '06B13D']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            2 => [
                'font'      => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '4E6F5C']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D7F487']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            4 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4E6F5C']],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 14,
            'C' => 8,
            'D' => 22,
            'E' => 22,
            'F' => 20,
            'G' => 14,
            'H' => 12,
            'I' => 30,
        ];
    }
}

// ── Sheet: Penerima ────────────────────────────────────────────────────────────

class PenerimaSheet implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(
        private Collection $data,
        private string     $periodeLabel,
    ) {}

    public function title(): string { return 'Penerima'; }

    public function headings(): array
    {
        return [
            ['LAPORAN DATA PENERIMA – NUTRIBASE'],
            ['Periode: ' . $this->periodeLabel],
            [''],
            ['No', 'Nama', 'Username', 'NIK', 'No. Telepon', 'Alamat', 'RT', 'Kategori', 'Est. Selesai', 'Terdaftar'],
        ];
    }

    public function collection(): Collection
    {
        return $this->data->values()->map(fn ($p, $i) => [
            $i + 1,
            $p->user->name ?? '-',
            $p->user->username ?? '-',
            $p->nik,
            $p->no_telepon ?? '-',
            $p->alamat,
            'RT ' . $p->rt,
            match($p->kategori) {
                'ibu_hamil'    => 'Ibu Hamil',
                'ibu_menyusui' => 'Ibu Menyusui',
                'balita'       => 'Balita',
                default        => 'Lainnya',
            },
            $p->estimasi_durasi->format('d/m/Y'),
            $p->created_at->format('d/m/Y'),
        ]);
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->mergeCells('A1:J1');
        $sheet->mergeCells('A2:J2');
        $sheet->mergeCells('A3:J3');

        return [
            1 => [
                'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '06B13D']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            2 => [
                'font'      => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '4E6F5C']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D7F487']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            4 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4E6F5C']],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 22,
            'C' => 16,
            'D' => 18,
            'E' => 16,
            'F' => 30,
            'G' => 8,
            'H' => 14,
            'I' => 14,
            'J' => 14,
        ];
    }
}