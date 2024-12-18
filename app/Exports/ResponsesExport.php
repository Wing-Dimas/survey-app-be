<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;

class ResponsesExport implements FromArray, WithHeadings, WithColumnWidths, WithStyles, WithEvents
{
    protected $responses;
    protected $headers;

    public function __construct(array $responses, array $headers)
    {
        $this->responses = $responses;
        $this->headers = $headers;
    }

    public function array(): array
    {
        return $this->responses;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    protected function numberToAlphabet($number) {
        $alphabet = '';
        while ($number >= 0) {
            $alphabet = chr(65 + ($number % 26)) . $alphabet;
            $number = floor($number / 26) - 1;
        }
        return $alphabet;
    }

    public function columnWidths(): array
    {
        $size = [];
        foreach ($this->headers as $key => $value) {
            $size[$this->numberToAlphabet($key)] = 40;
        }
        $size['A'] = 8;
        return $size;
    }

    public function styles($sheet)
    {
        return [
            // Style the first row as bold text, background gray, text center.
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => 'D9D9D9'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:' . $event->sheet->getHighestColumn() . $event->sheet->getHighestRow();
                $event->sheet->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ]
                    ]
                ]);
            },
        ];
    }
}
